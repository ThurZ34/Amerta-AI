<?php

namespace App\Livewire;

use App\Models\CashJournal;
use App\Models\DailySaleItem;
use App\Models\Riwayat;
use App\Services\KolosalService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    // use WithPagination; // Pagination removed as per request

    public $range = 'week';

    public function updatedRange()
    {
        // jangan diisi
    }

    public function dismissInsight()
    {
        Session::put('amerta_insight_dismissed', true);
    }

    protected function getBusinessHealth($revenueThisMonth, $expenseThisMonth, $profitThisMonth, $cashBalance)
    {
        $cacheKey = 'business_health_'.auth()->user()->business_id;
        if (Session::has($cacheKey)) {
            $cached = Session::get($cacheKey);
            if (Carbon::parse($cached['generated_at'])->diffInHours(now()) < 24) {
                return $cached;
            }
        }

        $score = 50;

        if ($revenueThisMonth > 0) {
            $profitMargin = ($profitThisMonth / $revenueThisMonth) * 100;
            if ($profitMargin >= 20) {
                $score += 20;
            } elseif ($profitMargin >= 10) {
                $score += 10;
            } elseif ($profitMargin >= 0) {
                $score += 0;
            } else {
                $score -= 20;
            }
        }

        if ($cashBalance > 0) {
            $score += 15;
        } elseif ($cashBalance >= -500000) {
            $score += 0;
        } else {
            $score -= 15;
        }

        if ($revenueThisMonth > 0) {
            $score += 15;
        } else {
            $score -= 10;
        }

        if ($score >= 70) {
            $status = 'SEHAT';
            $statusColor = 'emerald';
            $statusEmoji = '💪';
        } elseif ($score >= 40) {
            $status = 'WASPADA';
            $statusColor = 'amber';
            $statusEmoji = '⚠️';
        } else {
            $status = 'KRITIS';
            $statusColor = 'rose';
            $statusEmoji = '🚨';
        }

        try {
            $kolosalService = app(KolosalService::class);
            $business = auth()->user()->business;

            $prompt = "Kamu adalah konsultan bisnis UMKM bernama Amerta. Data bisnis bulan ini:\n";
            $prompt .= '- Omset: Rp '.number_format($revenueThisMonth, 0, ',', '.')."\n";
            $prompt .= '- Pengeluaran: Rp '.number_format($expenseThisMonth, 0, ',', '.')."\n";
            $prompt .= '- Profit: Rp '.number_format($profitThisMonth, 0, ',', '.')."\n";
            $prompt .= '- Saldo kas: Rp '.number_format($cashBalance, 0, ',', '.')."\n\n";

            $prompt .= "PENTING: Format jawabanmu menggunakan HTML tag agar rapi di website:\n";
            $prompt .= "- Gunakan tag <b>...</b> untuk menebalkan kata kunci (JANGAN pakai markdown **).\n";
            $prompt .= "- Gunakan tag <br> untuk ganti baris/enter.\n";
            $prompt .= "- Gunakan tag <ul> dan <li> untuk membuat list poin-poin saran.\n";
            $prompt .= "- Gaya bahasa tetap santai.\n\n";

            if ($status === 'SEHAT') {
                $prompt .= "Bisnis ini SEHAT dengan skor {$score}/100. Berikan 1-2 kalimat motivasi singkat. ";
                $prompt .= 'Ingatkan bahwa fluktuasi penjualan itu normal dan pertahankan konsistensi. Gunakan emoji dan bahasa santai.';
            } elseif ($status === 'WASPADA') {
                $prompt .= "Bisnis ini perlu PERHATIAN dengan skor {$score}/100. ";
                $prompt .= 'Berikan 2 saran KONKRET dan SPESIFIK untuk memperbaiki kondisi. ';
                $prompt .= 'Fokus pada: (1) cara meningkatkan omset atau (2) cara mengurangi pengeluaran. Bahasa santai.';
            } else {
                $prompt .= "Bisnis ini dalam kondisi KRITIS dengan skor {$score}/100. ";
                $prompt .= 'Berikan 2-3 langkah URGENT yang harus segera dilakukan untuk menyelamatkan bisnis. ';
                $prompt .= 'Prioritaskan: (1) menstabilkan cash flow (2) menghentikan kebocoran uang. Tegas tapi suportif.';
            }

            $message = $kolosalService->sendChat($prompt, $business);
        } catch (\Exception $e) {
            if ($status === 'SEHAT') {
                $message = 'Bisnis kamu berjalan bagus! Fluktuasi penjualan itu normal, yang penting konsisten. Pertahankan! 🔥';
            } elseif ($status === 'WASPADA') {
                $message = 'Perlu sedikit perhatian. Coba review pengeluaran dan cari cara meningkatkan penjualan minggu ini.';
            } else {
                $message = 'Kondisi perlu tindakan segera. Fokus stabilkan cash flow dan tunda pengeluaran yang bisa ditunda.';
            }
        }

        $health = [
            'score' => max(0, min(100, $score)),
            'status' => $status,
            'statusColor' => $statusColor,
            'statusEmoji' => $statusEmoji,
            'message' => $message,
            'generated_at' => now()->toDateTimeString(),
        ];

        Session::put($cacheKey, $health);

        return $health;
    }

    public function render()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $businessId = auth()->user()->business->id;

        $totalInflow = CashJournal::inflows()->sum('amount');
        $totalOutflow = CashJournal::outflows()->sum('amount');
        $cashBalance = $totalInflow - $totalOutflow;

        $revenueThisMonth = CashJournal::operatingRevenues()
            ->where('business_id', $businessId)
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->where('description', '!=', 'Modal Awal Bisnis')
            ->sum('amount');

        $expenseThisMonth = CashJournal::outflows()
            ->where('business_id', $businessId)
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $expenseBahanBakuOnly = Riwayat::where('business_id', $businessId)
            ->where('jenis', 'pengeluaran')
            ->whereBetween('tanggal_pembelian', [$startOfMonth, $endOfMonth])
            ->where('kategori', 'like', '%Bahan Baku%')
            ->whereNotNull('cash_journal_id')
            ->sum('total_harga');

        $hppThisMonth = DailySaleItem::whereHas('dailySale', function ($q) use ($startOfMonth, $endOfMonth, $businessId) {
            $q->where('business_id', $businessId)
              ->whereBetween('date', [$startOfMonth, $endOfMonth]);
        })
            ->sum(DB::raw('cost * quantity'));

        $operationalExpense = max(0, $expenseThisMonth - $expenseBahanBakuOnly);

        $profitThisMonth = $revenueThisMonth - $hppThisMonth - $operationalExpense;

        $revenueLastMonth = CashJournal::operatingRevenues()
            ->whereBetween('transaction_date', [$startOfMonth->copy()->subMonth(), $endOfMonth->copy()->subMonth()])
            ->sum('amount');

        $growthPercentage = 0;
        if ($revenueLastMonth > 0) {
            $growthPercentage = (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100;
        } elseif ($revenueThisMonth > 0) {
            $growthPercentage = 100;
        }

        $chartLabels = [];
        $chartData = [];

        switch ($this->range) {
            case 'day':
                // Fetch all data for today in one query
                $rawData = CashJournal::operatingRevenues()
                    ->whereDate('transaction_date', Carbon::today())
                    ->get(['created_at', 'amount']);

                // Group by hour in memory
                $grouped = $rawData->groupBy(function ($item) {
                    return $item->created_at->format('H');
                });

                for ($i = 0; $i <= 23; $i++) {
                    $hour = sprintf('%02d', $i);
                    $chartLabels[] = $hour.':00';
                    $chartData[] = $grouped->has($hour) ? $grouped->get($hour)->sum('amount') : 0;
                }
                break;

            case 'month':
                $daysInMonth = $now->daysInMonth;
                // Fetch all data for this month in one query
                $rawData = CashJournal::operatingRevenues()
                    ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                    ->get(['transaction_date', 'amount']);

                $grouped = $rawData->groupBy(function ($item) {
                    return $item->transaction_date->format('j'); // Day of month without leading zeros
                });

                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $day = (string) $i;
                    $chartLabels[] = $day;
                    $chartData[] = $grouped->has($day) ? $grouped->get($day)->sum('amount') : 0;
                }
                break;

            case 'year':
                // Fetch all data for this year in one query
                $startOfYear = $now->copy()->startOfYear();
                $endOfYear = $now->copy()->endOfYear();

                $rawData = CashJournal::operatingRevenues()
                    ->whereBetween('transaction_date', [$startOfYear, $endOfYear])
                    ->get(['transaction_date', 'amount']);

                $grouped = $rawData->groupBy(function ($item) {
                    return $item->transaction_date->format('n'); // Month number without leading zeros
                });

                for ($i = 1; $i <= 12; $i++) {
                    $date = Carbon::createFromDate($now->year, $i, 1);
                    $chartLabels[] = $date->translatedFormat('M');
                    $monthNum = (string) $i;

                    // Future months logic preserved
                    if ($date->gt($now)) {
                        $chartData[] = 0;
                    } else {
                        $chartData[] = $grouped->has($monthNum) ? $grouped->get($monthNum)->sum('amount') : 0;
                    }
                }
                break;

            case 'decade':
                $currentYear = $now->year;
                $startYear = $currentYear - 9;

                // Fetch all data for the last 10 years
                $rawData = CashJournal::operatingRevenues()
                    ->whereYear('transaction_date', '>=', $startYear)
                    ->whereYear('transaction_date', '<=', $currentYear)
                    ->get(['transaction_date', 'amount']);

                $grouped = $rawData->groupBy(function ($item) {
                    return $item->transaction_date->format('Y');
                });

                for ($i = 9; $i >= 0; $i--) {
                    $year = (string) ($currentYear - $i);
                    $chartLabels[] = $year;
                    $chartData[] = $grouped->has($year) ? $grouped->get($year)->sum('amount') : 0;
                }
                break;

            case 'week':
            default:
                // Fetch all data for the last 7 days
                $startDate = Carbon::today()->subDays(6);
                $endDate = Carbon::today();

                $rawData = CashJournal::operatingRevenues()
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->get(['transaction_date', 'amount']);

                $grouped = $rawData->groupBy(function ($item) {
                    return $item->transaction_date->format('Y-m-d');
                });

                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $dateString = $date->format('Y-m-d');
                    $chartLabels[] = $date->translatedFormat('l');
                    $chartData[] = $grouped->has($dateString) ? $grouped->get($dateString)->sum('amount') : 0;
                }
                break;
        }

        $expenseAllocationQuery = Riwayat::where('business_id', $businessId)
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
            $expenseLabels = $expenseAllocationQuery->map(function ($item) {
                return $item->kategori ?: 'Lain-lain';
            });
            $expenseData = $expenseAllocationQuery->pluck('total');
        }

        $recentTransactions = CashJournal::with('coa')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $aiMessage = null;
        if (! Session::has('amerta_insight_dismissed')) {
            if (Session::has('amerta_insight_quote')) {
                $aiMessage = Session::get('amerta_insight_quote');
            } else {
                try {
                    $business = Auth::user()->business;
                    if ($business) {
                        $kolosal = app(KolosalService::class);
                        $prompt = 'Berikan satu kalimat motivasi singkat, unik, dan semangat untuk pemilik bisnis ini. Jangan terlalu panjang, maksimal 15-20 kata. Gaya bahasa santai tapi profesional. jangan memakai emoji ataupun simbol';
                        $aiMessage = $kolosal->sendChat($prompt, $business);

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

        $initialCapital = CashJournal::where('business_id', $businessId)
            ->where('description', 'Modal Awal Bisnis')
            ->value('amount') ?? 0;

        $businessHealth = $this->getBusinessHealth($revenueThisMonth, $expenseThisMonth, $profitThisMonth, $cashBalance);

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
            'expenseAllocationQuery', // Pass full collection for legend
            'aiMessage',
            'businessHealth',
            'initialCapital'
        ))
            ->extends('layouts.app')
            ->section('content');
    }
}
