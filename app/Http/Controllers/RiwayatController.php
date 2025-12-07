<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;
use App\Models\DailySale;
use App\Models\CashJournal;
use App\Models\Coa;
use App\Models\Produk;
use App\Services\KolosalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\DailySaleItem;

class RiwayatController extends Controller
{
    protected $kolosalService;

    public function __construct(KolosalService $kolosalService)
    {
        $this->kolosalService = $kolosalService;
    }

    public function kasir()
    {
        $business = auth()->user()->business;
        $products = Produk::where('business_id', $business->id)->get();

        return view('riwayat.kasir', compact('products'));
    }

    public function index(Request $request)
    {
        $business = auth()->user()->business;

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $riwayats = Riwayat::where('business_id', $business->id)
            ->whereMonth('tanggal_pembelian', $month)
            ->whereYear('tanggal_pembelian', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Riwayat::where('business_id', $business->id)
            ->whereNotNull('kategori')
            ->where('jenis', 'pengeluaran'  )
            ->distinct()
            ->pluck('kategori')
            ->sort()
            ->values();

        return view('riwayat.index', [
            'riwayats' => $riwayats,
            'currentMonth' => $month,
            'currentYear' => $year,
            'categories' => $categories
        ]);
    }

    public function scan(Request $request)
    {
        $request->validate([
            'receipt_image' => 'required|image|max:5120',
        ]);

        try {
            $path = $request->file('receipt_image')->store('receipts', 'public');
            $data = $this->kolosalService->analyzeReceipt($path);

            if (! $data) {
                return back()->with('error', 'Gagal menganalisa struk. Pastikan gambar jelas.');
            }

            return redirect()->route('riwayat.index')
                ->with('scan_result', $data)
                ->with('success', 'Struk berhasil dianalisa! Silakan cek data sebelum disimpan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'tanggal_pembelian' => 'required|date',
            'total_harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|image|max:5120',
            'jenis' => 'required|in:pengeluaran,pendapatan',
        ]);

        $business = auth()->user()->business;
        $path = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('receipts', 'public');
        }

        DB::beginTransaction();
        try {
            $coaName = $request->jenis === 'pendapatan' ? 'Pendapatan Lainnya' : 'Beban Operasional';
            $coa = Coa::where('name', $coaName)->first();

            if (!$coa) {
                $coaType = $request->jenis === 'pendapatan' ? 'INFLOW' : 'OUTFLOW';
                $coa = Coa::where('type', $coaType)->first();
            }

            $cashJournal = null;
            if ($coa) {
                $cashJournal = CashJournal::create([
                    'business_id' => $business->id,
                    'transaction_date' => $request->tanggal_pembelian,
                    'coa_id' => $coa->id,
                    'amount' => $request->total_harga,
                    'is_inflow' => $request->jenis === 'pendapatan',
                    'payment_method' => 'Kas',
                    'description' => $request->nama_barang . ($request->keterangan ? ' - ' . $request->keterangan : ''),
                ]);
            }

            Riwayat::create([
                'business_id' => $business->id,
                'nama_barang' => $request->nama_barang,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'total_harga' => $request->total_harga,
                'keterangan' => $request->keterangan,
                'bukti_pembayaran' => $path,
                'jenis' => $request->jenis,
                'kategori' => $request->kategori,
                'cash_journal_id' => $cashJournal ? $cashJournal->id : null,
            ]);

            if ($request->has('items') && $request->jenis === 'pendapatan') {
                $items = json_decode($request->items, true);

                if (is_array($items) && count($items) > 0) {
                    $dailySale = DailySale::firstOrCreate(
                        [
                            'business_id' => $business->id,
                            'date' => $request->tanggal_pembelian,
                        ],
                        [
                            'ai_analysis' => 'Menunggu analisis...',
                        ]
                    );

                    foreach ($items as $item) {
                        $produk = Produk::find($item['id']);
                        if ($produk) {
                             DailySaleItem::create([
                                'daily_sale_id' => $dailySale->id,
                                'produk_id' => $produk->id,
                                'quantity' => $item['qty'],
                                'price' => $item['harga_jual'],
                                'cost' => $produk->modal ?? 0,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('riwayat.index')->with('success', 'Data berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'tanggal_pembelian' => 'required|date',
            'total_harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|image|max:5120',
            'jenis' => 'required|in:pengeluaran,pendapatan',
        ]);

        $riwayat = Riwayat::where('business_id', auth()->user()->business->id)->findOrFail($id);
        $data = $request->except('bukti_pembayaran');

        if ($request->hasFile('bukti_pembayaran')) {
            if ($riwayat->bukti_pembayaran) {
                Storage::disk('public')->delete($riwayat->bukti_pembayaran);
            }
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('receipts', 'public');
        }

        DB::beginTransaction();
        try {
            $riwayat->update($data);

            if ($riwayat->cash_journal_id) {
                $cashJournal = CashJournal::find($riwayat->cash_journal_id);
                if ($cashJournal) {
                    $coaName = $request->jenis === 'pendapatan' ? 'Pendapatan Lainnya' : 'Beban Operasional';
                    $coa = Coa::where('name', $coaName)->first();
                     if (!$coa) {
                        $coaType = $request->jenis === 'pendapatan' ? 'INFLOW' : 'OUTFLOW';
                        $coa = Coa::where('type', $coaType)->first();
                    }

                    $cashJournal->update([
                        'transaction_date' => $request->tanggal_pembelian,
                        'amount' => $request->total_harga,
                        'is_inflow' => $request->jenis === 'pendapatan',
                        'description' => $request->nama_barang . ($request->keterangan ? ' - ' . $request->keterangan : ''),
                        'coa_id' => $coa ? $coa->id : $cashJournal->coa_id,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('riwayat.index')->with('success', 'Data berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $riwayat = Riwayat::where('business_id', auth()->user()->business->id)->findOrFail($id);

        DB::beginTransaction();
        try {
            if ($riwayat->bukti_pembayaran) {
                Storage::disk('public')->delete($riwayat->bukti_pembayaran);
            }

            if ($riwayat->cash_journal_id) {
                CashJournal::destroy($riwayat->cash_journal_id);
            }

            $riwayat->delete();

            DB::commit();
            return redirect()->route('riwayat.index')->with('success', 'Data berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
