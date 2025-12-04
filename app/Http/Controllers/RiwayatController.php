<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        Riwayat::create([
            'business_id' => $business->id,
            'nama_barang' => $request->nama_barang,
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'total_harga' => $request->total_harga,
            'keterangan' => $request->keterangan,
            'bukti_pembayaran' => $path,
            'jenis' => $request->jenis,
        ]);

        return redirect()->route('riwayat.index')->with('success', 'Data berhasil ditambahkan.');
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

        $riwayat->update($data);

        return redirect()->route('riwayat.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $riwayat = Riwayat::where('business_id', auth()->user()->business->id)->findOrFail($id);

        if ($riwayat->bukti_pembayaran) {
            Storage::disk('public')->delete($riwayat->bukti_pembayaran);
        }

        $riwayat->delete();

        return redirect()->route('riwayat.index')->with('success', 'Data berhasil dihapus.');
    }
}
