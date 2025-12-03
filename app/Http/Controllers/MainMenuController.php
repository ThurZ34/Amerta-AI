<?php

namespace App\Http\Controllers;

use App\Models\CashJournal;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        return view('main_menu', compact('profitYesterday', 'lowStockProducts', 'insight'));
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
