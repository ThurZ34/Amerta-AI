<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;
use App\Models\CashJournal;
use App\Models\Coa;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index()
    {
        $business = auth()->user()->business;
        $riwayats = Riwayat::where('business_id', $business->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('riwayat.index', compact('riwayats'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'receipt_image' => 'required|image|max:5120', // Max 5MB
        ]);

        try {
            $path = $request->file('receipt_image')->store('receipts', 'public');
            $data = $this->geminiService->analyzeReceipt($path);

            if (! $data) {
                return back()->with('error', 'Gagal menganalisa struk. Pastikan gambar jelas.');
            }

            // Return view with pre-filled data (using session or passing variable)
            // We'll redirect back with session data to open the modal
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
            // Determine COA based on jenis
            $coaName = $request->jenis === 'pendapatan' ? 'Pendapatan Lainnya' : 'Beban Operasional';
            $coa = Coa::where('name', $coaName)->first();
            
            // Fallback if specific COA not found, try generic types
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
                    'payment_method' => 'Kas', // Default to Kas for now
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
                'cash_journal_id' => $cashJournal ? $cashJournal->id : null,
            ]);

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

            // CashJournal will be deleted automatically via cascade if defined in migration, 
            // but since we added nullable foreign key on riwayats table pointing to cash_journals,
            // we need to manually delete the cash_journal entry if we want to remove the financial record.
            // Wait, the relation is Riwayat belongsTo CashJournal. 
            // So Riwayat has cash_journal_id.
            // If we delete Riwayat, the CashJournal entry remains unless we delete it explicitly.
            
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
