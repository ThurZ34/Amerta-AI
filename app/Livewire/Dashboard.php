<?php

namespace App\Livewire;

use App\Models\CashJournal;
use App\Models\Produk;
use App\Services\GeminiService;
use App\Models\DailySaleItem;
use \App\Models\Riwayat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    // Filter Range (Default: Minggu ini)
    public $range = 'week';

    // Reset pagination saat filter berubah agar data tidak error
    public function updatedRange()
    {
        $this->resetPage();
    }

    public function dismissInsight()
    {
        Session::put('amerta_insight_dismissed', true);
    }

    public function render()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $businessId = auth()->user()->business->id;

        // --- 1. RINGKASAN KEUANGAN ---
        $totalInflow = CashJournal::inflows()->sum('amount');
        $totalOutflow = CashJournal::outflows()->sum('amount');
        $cashBalance = $totalInflow - $totalOutflow;

        $revenueThisMonth = CashJournal::inflows()
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $expenseThisMonth = CashJournal::outflows()
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $expenseBahanBakuOnly = Riwayat::where('business_id', $businessId)
            ->where('jenis', 'pengeluaran') // Pastikan hanya ambil pengeluaran
            ->whereBetween('tanggal_pembelian', [$startOfMonth, $endOfMonth]) // Perhatikan nama kolom tanggal di riwayat
            ->where('kategori', 'like', '%Bahan Baku%') // Filter string kategori
            ->sum('total_harga');

        $hppThisMonth = DailySaleItem::whereHas('dailySale', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('date', [$startOfMonth, $endOfMonth]);
        })
            ->sum(DB::raw('cost * quantity'));

        $operationalExpense = $expenseThisMonth - $expenseBahanBakuOnly;

        $profitThisMonth = $revenueThisMonth - $hppThisMonth - $operationalExpense;

        $revenueLastMonth = CashJournal::inflows()
            ->whereBetween('transaction_date', [$startOfMonth->copy()->subMonth(), $endOfMonth->copy()->subMonth()])
            ->sum('amount');

        $growthPercentage = 0;
        if ($revenueLastMonth > 0) {
            $growthPercentage = (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100;
        } elseif ($revenueThisMonth > 0) {
            $growthPercentage = 100;
        }

        // --- 2. CHART TREN (FILTER LIVEWIRE) ---
        $chartLabels = [];
        $chartData = [];

        switch ($this->range) {
            case 'day':
                for ($i = 0; $i <= 23; $i++) {
                    $chartLabels[] = sprintf('%02d:00', $i);
                    $chartData[] = CashJournal::inflows()
                        ->whereDate('transaction_date', \Carbon\Carbon::today())
                        ->whereTime('created_at', '>=', sprintf('%02d:00:00', $i))
                        ->whereTime('created_at', '<=', sprintf('%02d:59:59', $i))
                        ->sum('amount');
                }
                break;

            case 'month':
                $daysInMonth = $now->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = Carbon::createFromDate($now->year, $now->month, $i);
                    $chartLabels[] = (string) $i;
                    // Jika tanggal belum lewat, isi 0 biar grafiknya ga turun tajam di masa depan
                    $chartData[] = CashJournal::inflows()
                        ->whereDate('transaction_date', $date)
                        ->sum('amount');
                }
                break;

            case 'year':
                for ($i = 1; $i <= 12; $i++) {
                    $date = Carbon::createFromDate($now->year, $i, 1);
                    $chartLabels[] = $date->translatedFormat('M');
                    $chartData[] = $date->gt($now) ? 0 : CashJournal::inflows()
                        ->whereYear('transaction_date', $now->year)
                        ->whereMonth('transaction_date', $i)
                        ->sum('amount');
                }
                break;

            case 'decade':
                $currentYear = $now->year;
                for ($i = 9; $i >= 0; $i--) {
                    $year = $currentYear - $i;
                    $chartLabels[] = (string) $year;
                    $chartData[] = CashJournal::inflows()
                        ->whereYear('transaction_date', $year)
                        ->sum('amount');
                }
                break;

            case 'week':
            default:
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $chartLabels[] = $date->translatedFormat('l');
                    $chartData[] = CashJournal::inflows()
                        ->whereDate('transaction_date', $date)
                        ->sum('amount');
                }
                break;
        }

        // --- 3. CHART ALOKASI BIAYA ---
        // --- 3. CHART ALOKASI BIAYA ---
        // Menggunakan data dari Riwayat agar kategori sesuai input user
        $expenseAllocationQuery = \App\Models\Riwayat::where('business_id', $businessId)
            ->where('jenis', 'pengeluaran')
            ->whereBetween('tanggal_pembelian', [$startOfMonth, $endOfMonth])
            ->select('kategori', DB::raw('sum(total_harga) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        if ($expenseAllocationQuery->isEmpty()) {
            $expenseLabels = ['Belum Ada Pengeluaran'];
            $expenseData = [1];
        } else {
            // Map null category to 'Lain-lain' or 'Tanpa Kategori'
            $expenseLabels = $expenseAllocationQuery->map(function ($item) {
                return $item->kategori ?: 'Lain-lain';
            });
            $expenseData = $expenseAllocationQuery->pluck('total');
        }

        // --- 4. TRANSAKSI (PAGINATION) ---
        $recentTransactions = CashJournal::with('coa')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        // Pesan Semangat Amerta (Quotes) - AI Generated & Session Based
        $aiMessage = null;
        if (!Session::has('amerta_insight_dismissed')) {
            if (Session::has('amerta_insight_quote')) {
                $aiMessage = Session::get('amerta_insight_quote');
            } else {
                // Generate new quote via Gemini
                try {
                    $business = Auth::user()->business;
                    if ($business) {
                        $gemini = app(GeminiService::class);
                        $prompt = 'Berikan satu kalimat motivasi singkat, unik, dan semangat untuk pemilik bisnis ini. Jangan terlalu panjang, maksimal 15-20 kata. Gaya bahasa santai tapi profesional. jangan memakai emoji ataupun simbol';
                        $aiMessage = $gemini->sendChat($prompt, $business);

                        // Clean up quotes if AI adds them
                        $aiMessage = trim($aiMessage, '"\'');

                        Session::put('amerta_insight_quote', $aiMessage);
                    } else {
                        $aiMessage = 'Semangat terus membangun bisnismu!';
                    }
                } catch (\Exception $e) {
                    $aiMessage = 'Sukses adalah perjalanan, nikmati prosesnya.';
                }
            }
        }

        // --- LOW STOCK ALERT ---
        // Assuming $businessId is available in this context, e.g., from authenticated user or a property.
        // For demonstration, let's assume a placeholder value if not explicitly defined elsewhere.
        // If $businessId is not defined, this line will cause an error.
        $businessId = Auth::user()->business_id;
        $lowStockProducts = Produk::where('business_id', $businessId)
            ->whereColumn('inventori', '<=', 'min_stock')
            ->get();

        // ✅ PERBAIKAN UTAMA: Menggunakan extends() dan section() agar sesuai layout blade biasa
        return view('livewire.dashboard', compact(
            'cashBalance',
            'revenueThisMonth',
            'expenseThisMonth',
            'profitThisMonth',
            'growthPercentage',
            'recentTransactions',
            'chartLabels',
            'chartData',
            'expenseLabels',
            'expenseData',
            'aiMessage',
            'lowStockProducts'
        ))
            ->extends('layouts.app') // Pastikan ini sesuai nama file layout Anda (resources/views/layouts/app.blade.php)
            ->section('content');    // Pastikan ini sesuai nama @yield('content') di layout Anda
    }
}
