<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\DailySaleItem;
use App\Models\Produk;
use App\Models\CashJournal;
use App\Models\Coa;
use App\Services\GeminiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyCheckinController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m'));
        $startOfMonth = Carbon::parse($date)->startOfMonth();
        $endOfMonth = Carbon::parse($date)->endOfMonth();

        $dailySales = DailySale::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->leftJoin('cash_journals', function ($join) {
                $join->on('daily_sales.date', '=', 'cash_journals.transaction_date')
                     ->where('cash_journals.business_id', auth()->user()->business->id);
            })
            ->select(
                'daily_sales.*',
                DB::raw('COALESCE(SUM(CASE WHEN cash_journals.is_inflow = 1 THEN cash_journals.amount ELSE 0 END), 0) as total_revenue'),
                DB::raw('COALESCE(SUM(CASE WHEN cash_journals.is_inflow = 1 THEN cash_journals.amount ELSE -cash_journals.amount END), 0) as total_profit')
            )
            ->groupBy('daily_sales.id')
            ->get()
            ->keyBy(fn($sale) => $sale->date->format('Y-m-d'));

        return view('daily-checkin.index', compact('dailySales', 'startOfMonth', 'endOfMonth'));
    }

    public function create(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $existing = DailySale::where('date', $date)->first();
        if ($existing) {
            return redirect()->route('daily-checkin.show', $existing->id);
        }

        $produks = Produk::all();

        return view('daily-checkin.create', compact('produks', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:daily_sales,date',
            'sales' => 'required|array',
            'sales.*' => 'required|integer|min:0',
        ]);

        $salesData = [];
        $totalRevenue = 0;
        $totalCost = 0;
        $totalProfit = 0;

        $coaRevenue = Coa::where('name', 'Penjualan Produk')->first();
        $coaCost = Coa::where('name', 'Beban Bahan Baku')->first();

        if (!$coaRevenue || !$coaCost) {
            Log::error("COA not found for sales transaction: Revenue exists=" . ($coaRevenue ? 'Yes' : 'No') . ", Cost exists=" . ($coaCost ? 'Yes' : 'No'));
            return back()->withInput()->withErrors('Konfigurasi Akun Keuangan (COA) belum lengkap. Harap cek data seeder.');
        }

        DB::beginTransaction();

        try {
            $dailySale = DailySale::create([
                'date' => $request->date,
                'ai_analysis' => 'Analyzing...',
            ]);

            foreach ($request->sales as $produkId => $qty) {
                if ($qty > 0) {
                    $produk = Produk::find($produkId);
                    if ($produk) {
                        $revenue = $produk->harga_jual * $qty;
                        $cost = $produk->modal * $qty;
                        $profit = $revenue - $cost;

                        $totalRevenue += $revenue;
                        $totalCost += $cost;
                        $totalProfit += $profit;

                        DailySaleItem::create([
                            'daily_sale_id' => $dailySale->id,
                            'produk_id' => $produk->id,
                            'quantity' => $qty,
                            'price' => $produk->harga_jual,
                            'cost' => $produk->modal,
                        ]);

                        // Decrement stock
                        $produk->decrement('inventori', $qty);

                        CashJournal::create([
                            'business_id' => auth()->user()->business->id,
                            'transaction_date' => $request->date,
                            'coa_id' => $coaRevenue->id,
                            'amount' => $revenue,
                            'is_inflow' => true,
                            'payment_method' => 'Kas',
                            'description' => "Penjualan {$qty} unit {$produk->nama_produk}",
                        ]);

                        CashJournal::create([
                            'business_id' => auth()->user()->business->id,
                            'transaction_date' => $request->date,
                            'coa_id' => $coaCost->id,
                            'amount' => $cost,
                            'is_inflow' => false,
                            'payment_method' => 'Kas',
                            'description' => "Pengeluaran modal (HPP) untuk {$qty} unit {$produk->nama_produk}",
                        ]);

                        $salesData[] = [
                            'name' => $produk->nama_produk,
                            'qty' => $qty,
                            'revenue' => $revenue,
                            'profit' => $profit,
                        ];
                    }
                }
            }

            $prompt = 'Analisis penjualan harian saya untuk tanggal '.Carbon::parse($request->date)->translatedFormat('l, d F Y').":\n";
            foreach ($salesData as $data) {
                $prompt .= "- {$data['name']}: Terjual {$data['qty']} unit. Profit: Rp ".number_format($data['profit'], 0, ',', '.')."\n";
            }
            $prompt .= "\nTotal Omset: Rp ".number_format($totalRevenue, 0, ',', '.');
            $prompt .= "\nTotal Profit: Rp ".number_format($totalProfit, 0, ',', '.');
            $prompt .= "\n\nBerikan evaluasi singkat, apakah ini untung atau rugi? Jika untung besar, beri ucapan selamat yang memotivasi. Jika rugi atau untung tipis, beri saran konkret untuk meningkatkan penjualan besok. Gunakan bahasa yang santai dan suportif.";

            $business = auth()->user()->business;
            if (! $business) {
                throw new \Exception('Bisnis tidak ditemukan. Silakan setup bisnis terlebih dahulu.');
            }

            $aiResponse = $this->geminiService->sendChat($prompt, $business);

            $dailySale->update([
                'ai_analysis' => $aiResponse,
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error during DailySale store and CashJournal creation: " . $e->getMessage());
            return back()->withInput()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }

        return redirect()->route('daily-checkin.show', $dailySale->id);
    }

    public function show($id)
    {
        $dailySale = DailySale::with('items.produk')->findOrFail($id);

        $totalRevenue = CashJournal::where('business_id', auth()->user()->business->id)->inflows()->whereDate('transaction_date', $dailySale->date)->sum('amount');
        $totalExpense = CashJournal::where('business_id', auth()->user()->business->id)->outflows()->whereDate('transaction_date', $dailySale->date)->sum('amount');

        $dailySale->total_revenue = $totalRevenue;
        $dailySale->total_profit = $totalRevenue - $totalExpense;

        return view('daily-checkin.show', compact('dailySale'));
    }
}
