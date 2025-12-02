<?php

namespace App\Http\Controllers;

use App\Models\CashJournal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
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

        $range = $request->input('range', 'week');
        $chartLabels = [];
        $chartData = [];

        switch ($range) {
            case 'day':
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
                $daysInMonth = Carbon::now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $chartLabels[] = (string)$i;
                    $chartData[] = CashJournal::inflows()
                        ->whereDate('transaction_date', Carbon::createFromDate(null, null, $i))
                        ->sum('amount');
                }
                break;

            case 'year':
                for ($i = 1; $i <= 12; $i++) {
                    $chartLabels[] = Carbon::create(null, $i, 1)->translatedFormat('M');
                    $chartData[] = CashJournal::inflows()
                        ->whereYear('transaction_date', Carbon::now()->year)
                        ->whereMonth('transaction_date', $i)
                        ->sum('amount');
                }
                break;

            case 'decade':
                $currentYear = Carbon::now()->year;
                for ($i = 9; $i >= 0; $i--) {
                    $year = $currentYear - $i;
                    $chartLabels[] = (string)$year;
                    $chartData[] = CashJournal::inflows()
                        ->whereYear('transaction_date', $year)
                        ->sum('amount');
                }
                break;

            case 'week':
            default:
                // 1 Minggu (7 Hari Terakhir)
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $chartLabels[] = $date->translatedFormat('l');
                    $chartData[] = CashJournal::inflows()
                        ->whereDate('transaction_date', $date)
                        ->sum('amount');
                }
                break;
        }

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

        $recentTransactions = CashJournal::with('coa')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(4)
            ->withQueryString();

        $aiMessage = "Halo! Bulan ini omset Anda mencapai Rp " . number_format($revenueThisMonth, 0,',','.') . ". ";
        if ($profitThisMonth > 0) {
            $aiMessage .= "Kinerja bagus! Profit positif Rp " . number_format($profitThisMonth, 0,',','.') . ".";
        } else {
            $aiMessage .= "Perhatian, pengeluaran melebihi pemasukan.";
        }

        return view('dashboard', compact(
            'cashBalance',
            'revenueThisMonth',
            'expenseThisMonth',
            'profitThisMonth',
            'growthPercentage',
            'revenueLastMonth',
            'chartLabels',
            'chartData',
            'expenseLabels',
            'expenseData',
            'recentTransactions',
            'aiMessage',
            'range'
        ));
    }
}
