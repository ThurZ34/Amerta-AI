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
        // 1. Profit Yesterday
        $yesterday = Carbon::yesterday();
        $revenueYesterday = CashJournal::inflows()
            ->whereDate('transaction_date', $yesterday)
            ->sum('amount');
        
        $expenseYesterday = CashJournal::outflows()
            ->whereDate('transaction_date', $yesterday)
            ->sum('amount');
            
        $profitYesterday = $revenueYesterday - $expenseYesterday;

        // 2. Stock Warnings
        $lowStockProducts = Produk::whereColumn('inventori', '<=', 'min_stock')
            ->limit(5)
            ->get();

        // 3. Amerta Insight
        $insight = $this->generateInsight($profitYesterday, $lowStockProducts);

        // 4. Monthly Target
        $business = Auth::user()->business;
        $targetRevenue = $business->target_revenue ?? 0;
        $revenueThisMonth = CashJournal::inflows()
            ->whereBetween('transaction_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('amount');
        
        $targetPercentage = $targetRevenue > 0 ? ($revenueThisMonth / $targetRevenue) * 100 : 0;
        $targetPercentage = min($targetPercentage, 100); // Cap at 100% for bar width

        // 5. Top Products (Produk Jagoan)
        // Since we don't have a direct sales table linked to products yet, we'll simulate this 
        // or use a placeholder if no sales data. 
        // Ideally, we would query a TransactionDetail model.
        // For now, let's assume we can get it from Produk model if it has a 'sold' count or similar.
        // If not, we'll just take random products for demo or based on 'inventori' changes if tracked.
        // Let's use a placeholder logic: Get 3 random products for now as "Top Selling" simulation 
        // until we have real sales data linked.
        $topProducts = Produk::inRandomOrder()->limit(3)->get(); 

        // 6. Streak (Daily Check-in)
        $streakDays = $this->calculateStreak();

        return view('main_menu', compact(
            'profitYesterday', 
            'lowStockProducts', 
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

    private function generateInsight($profit, $lowStock)
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

        // Stock Insight
        if ($lowStock->count() > 0) {
            $messages[] = "Perhatian! Ada " . $lowStock->count() . " produk yang stoknya menipis. Segera lakukan restock.";
        }

        // General Motivation if no issues
        if ($profit >= 0 && $lowStock->count() == 0) {
            $messages[] = "Semua sistem berjalan lancar. Fokus pada pengembangan bisnis hari ini!";
        }

        return implode(" ", $messages);
    }
}
