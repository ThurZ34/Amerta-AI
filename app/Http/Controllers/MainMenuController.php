<?php

namespace App\Http\Controllers;

use App\Models\CashJournal;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DailySale;

class MainMenuController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $businessId = $user->business_id;

        $yesterday = Carbon::yesterday();
        $revenueYesterday = CashJournal::where('business_id', $businessId)
            ->inflows()
            ->whereDate('transaction_date', $yesterday)
            ->sum('amount');

        $expenseYesterday = CashJournal::where('business_id', $businessId)
            ->outflows()
            ->whereDate('transaction_date', $yesterday)
            ->sum('amount');

        $profitYesterday = $revenueYesterday - $expenseYesterday;

        // 3. Amerta Insight
        $insight = $this->generateInsight($profitYesterday);

        // 4. Monthly Target
        $business = $user->business;
        $targetRevenue = $business->target_revenue ?? 0;
        $revenueThisMonth = CashJournal::where('business_id', $businessId)
            ->inflows()
            ->whereBetween('transaction_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('amount');

        $targetPercentage = $targetRevenue > 0 ? ($revenueThisMonth / $targetRevenue) * 100 : 0;
        $targetPercentage = min($targetPercentage, 100); // Cap at 100% for bar width

        $topProducts = Produk::where('business_id', $businessId)
        ->inRandomOrder()
        ->limit(3)
        ->get();

        // 6. Streak (Daily Check-in)
        $streakDays = $this->calculateStreak();

        return view('main_menu', compact(
            'profitYesterday',
            'insight',
            'targetRevenue',
            'revenueThisMonth',
            'targetPercentage',
            'topProducts',
            'streakDays'
        ));
    }

    public function updateTarget(Request $request)
    {
        $request->validate([
            'target_revenue' => 'required|numeric|min:0',
        ]);

        $business = Auth::user()->business;
        $business->update(['target_revenue' => $request->target_revenue]);

        return redirect()->back()->with('success', 'Target bulanan berhasil diperbarui!');
    }

    private function calculateStreak()
    {
        $businessId = Auth::user()->business_id;

        // We need to count consecutive days backwards from today/yesterday.
        // Using DailySale as the check-in record
        $checkins = DailySale::where('business_id', $businessId)
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->unique()
            ->values()
            ->all();

        if (empty($checkins)) return 0;

        $streak = 0;
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        // Check if the latest checkin is today or yesterday to start the streak
        if ($checkins[0] !== $today && $checkins[0] !== $yesterday) {
            return 0;
        }

        $currentDate = Carbon::parse($checkins[0]);
        $streak = 1;

        for ($i = 1; $i < count($checkins); $i++) {
            $prevDate = Carbon::parse($checkins[$i]);
            if ($currentDate->diffInDays($prevDate) === 1) {
                $streak++;
                $currentDate = $prevDate;
            } else {
                break;
            }
        }

        return $streak;
    }

    private function generateInsight($profit)
    {
        $messages = [];

        // Profit Insight
        if ($profit > 0) {
            $messages[] = "Kemarin Anda mencetak profit sebesar Rp " . number_format($profit, 0, ',', '.') . ". Pertahankan momentum ini!";
        } elseif ($profit < 0) {
            $messages[] = "Kemarin ada defisit sebesar Rp " . number_format(abs($profit), 0, ',', '.') . ". Cek pengeluaran Anda.";
        } else {
            $messages[] = "Belum ada aktivitas keuangan yang signifikan kemarin.";
        }

        // General Motivation
        if ($profit >= 0) {
            $messages[] = "Semua sistem berjalan lancar. Fokus pada pengembangan bisnis hari ini!";
        }

        return implode(" ", $messages);
    }
}
