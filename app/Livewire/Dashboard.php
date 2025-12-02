<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CashJournal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function render()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

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

        $profitThisMonth = $revenueThisMonth - $expenseThisMonth;

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
            case 'day': // Per Jam Hari Ini
                for ($i = 0; $i <= 23; $i++) {
                    $chartLabels[] = sprintf("%02d:00", $i);
                    $chartData[] = CashJournal::inflows()
                        ->whereDate('transaction_date', Carbon::today())
                        ->whereTime('created_at', '>=', sprintf("%02d:00:00", $i))
                        ->whereTime('created_at', '<=', sprintf("%02d:59:59", $i))
                        ->sum('amount');
                }
                break;

            case 'month':
                $daysInMonth = $now->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = Carbon::createFromDate($now->year, $now->month, $i);
                    $chartLabels[] = (string)$i;

                    if ($date->gt($now)) {
                        $chartData[] = 0;
                    } else {
                        $chartData[] = CashJournal::inflows()
                            ->whereDate('transaction_date', $date)
                            ->sum('amount');
                    }
                }
                break;

            case 'year':
                for ($i = 1; $i <= 12; $i++) {
                    $date = Carbon::createFromDate($now->year, $i, 1);
                    if ($date->gt($now)) {
                        $chartData[] = 0;
                    } else {
                        $chartData[] = CashJournal::inflows()
                            ->whereYear('transaction_date', $now->year)
                            ->whereMonth('transaction_date', $i)
                            ->sum('amount');
                    }
                    $chartLabels[] = $date->translatedFormat('M');
                }
                break;

            case 'decade': // Per Tahun (10 Tahun Terakhir)
                $currentYear = $now->year;
                for ($i = 9; $i >= 0; $i--) {
                    $year = $currentYear - $i;
                    $chartLabels[] = (string)$year;
                    $chartData[] = CashJournal::inflows()
                        ->whereYear('transaction_date', $year)
                        ->sum('amount');
                }
                break;

            case 'week':
            default: // 7 Hari Terakhir
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
        $expenseAllocationQuery = CashJournal::outflows()
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->join('coa', 'cash_journals.coa_id', '=', 'coa.id')
            ->select('coa.name', DB::raw('sum(amount) as total'))
            ->groupBy('coa.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        if ($expenseAllocationQuery->isEmpty()) {
             $expenseLabels = ['Belum Ada Pengeluaran'];
             $expenseData = [1];
        } else {
             $expenseLabels = $expenseAllocationQuery->pluck('name');
             $expenseData = $expenseAllocationQuery->pluck('total');
        }

        // --- 4. TRANSAKSI (PAGINATION) ---
        $recentTransactions = CashJournal::with('coa')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        // Pesan AI Sederhana
        $aiMessage = "Halo! Omset bulan ini Rp " . number_format($revenueThisMonth, 0,',','.') . ". ";
        $aiMessage .= ($profitThisMonth > 0)
            ? "Profit positif Rp " . number_format($profitThisMonth, 0,',','.') . ". Bagus!"
            : "Hati-hati, pengeluaran lebih besar dari pemasukan.";

        // ✅ PERBAIKAN UTAMA: Menggunakan extends() dan section() agar sesuai layout blade biasa
        return view('livewire.dashboard', compact(
            'cashBalance', 'revenueThisMonth', 'expenseThisMonth', 'profitThisMonth',
            'growthPercentage', 'revenueLastMonth', 'chartLabels', 'chartData',
            'expenseLabels', 'expenseData', 'recentTransactions', 'aiMessage'
        ))
        ->extends('layouts.app') // Pastikan ini sesuai nama file layout Anda (resources/views/layouts/app.blade.php)
        ->section('content');    // Pastikan ini sesuai nama @yield('content') di layout Anda
    }
}
