<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\DailySaleItem;
use App\Models\Produk;
use App\Services\GeminiService;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $businessId = auth()->user()->business?->id;
        $dailySales = DailySale::where('business_id', $businessId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy(fn($sale) => $sale->date->format('Y-m-d'));

        return view('daily-checkin.index', compact('dailySales', 'startOfMonth', 'endOfMonth'));
    }

    public function create(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        // Check if already exists
        $businessId = auth()->user()->business?->id;
        $existing = DailySale::where('business_id', $businessId)
            ->where('date', $date)
            ->first();
        if ($existing) {
            return redirect()->route('daily-checkin.show', $existing->id);
        }

        $produks = Produk::where('business_id', $businessId)->get();

        return view('daily-checkin.create', compact('produks', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => [
                'required',
                'date',
                \Illuminate\Validation\Rule::unique('daily_sales')->where(function ($query) {
                    return $query->where('business_id', auth()->user()->business?->id);
                }),
            ],
            'sales' => 'required|array',
            'sales.*' => 'required|integer|min:0',
        ]);

        $salesData = [];
        $totalRevenue = 0;
        $totalCost = 0;
        $totalProfit = 0;

        $businessId = auth()->user()->business?->id;
        $dailySale = DailySale::create([
            'business_id' => $businessId,
            'date' => $request->date,
            'total_revenue' => 0, // Update later
            'total_profit' => 0, // Update later
            'ai_analysis' => 'Analyzing...',
        ]);

        foreach ($request->sales as $produkId => $qty) {
            if ($qty > 0) {
                $produk = Produk::where('business_id', $businessId)->find($produkId);
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

                    $salesData[] = [
                        'name' => $produk->nama_produk,
                        'qty' => $qty,
                        'revenue' => $revenue,
                        'profit' => $profit,
                    ];
                }
            }
        }

        // Prepare prompt for Gemini
        $prompt = 'Analisis penjualan harian saya untuk tanggal '.Carbon::parse($request->date)->translatedFormat('l, d F Y').":\n";
        foreach ($salesData as $data) {
            $prompt .= "- {$data['name']}: Terjual {$data['qty']} unit. Profit: Rp ".number_format($data['profit'], 0, ',', '.')."\n";
        }
        $prompt .= "\nTotal Omset: Rp ".number_format($totalRevenue, 0, ',', '.');
        $prompt .= "\nTotal Profit: Rp ".number_format($totalProfit, 0, ',', '.');
        $prompt .= "\n\nBerikan evaluasi singkat, apakah ini untung atau rugi? Jika untung besar, beri ucapan selamat yang memotivasi. Jika rugi atau untung tipis, beri saran konkret untuk meningkatkan penjualan besok. Gunakan bahasa yang santai dan suportif.";

        // Get Business context
        $business = auth()->user()->business;
        if (! $business) {
            $business = new \App\Models\Business;
            $business->nama_bisnis = 'Bisnis Saya';
            $business->kategori_bisnis = 'Umum';
            $business->status_bisnis = 'Berjalan';
            $business->target_pasar = 'Umum';
            $business->range_omset = 'Unknown';
            $business->jumlah_tim = 1;
            $business->tujuan_utama = 'Profit';
            $business->channel_penjualan = 'Offline';
        }

        $aiResponse = $this->geminiService->sendChat($prompt, $business);

        $dailySale->update([
            'total_revenue' => $totalRevenue,
            'total_profit' => $totalProfit,
            'ai_analysis' => $aiResponse,
        ]);

        return redirect()->route('daily-checkin.show', $dailySale->id);
    }

    public function show($id)
    {
        $dailySale = DailySale::where('business_id', auth()->user()->business?->id)
            ->with('items.produk')
            ->findOrFail($id);

        return view('daily-checkin.show', compact('dailySale'));
    }
}
