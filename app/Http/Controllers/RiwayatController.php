<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Riwayat;
use App\Services\GeminiService;

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

            if (!$data) {
                return back()->with('error', 'Gagal menganalisa struk. Pastikan gambar jelas.');
            }

            // Return view with pre-filled data (using session or passing variable)
            // We'll redirect back with session data to open the modal
            return redirect()->route('riwayat.index')
                ->with('scan_result', $data)
                ->with('success', 'Struk berhasil dianalisa! Silakan cek data sebelum disimpan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'harga_satuan' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'inventori' => 'required|numeric|min:0',
            'jenis' => 'required|in:pengeluaran,pendapatan',
            'metode_pembayaran' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $business = auth()->user()->business;

        Riwayat::create([
            'business_id' => $business->id,
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total_harga' => $request->total_harga,
            'inventori' => $request->inventori,
            'jenis' => $request->jenis,
            'metode_pembayaran' => $request->metode_pembayaran,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('riwayat.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'harga_satuan' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'inventori' => 'required|numeric|min:0',
            'jenis' => 'required|in:pengeluaran,pendapatan',
            'metode_pembayaran' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $riwayat = Riwayat::where('business_id', auth()->user()->business->id)->findOrFail($id);
        $riwayat->update($request->all());

        return redirect()->route('riwayat.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $riwayat = Riwayat::where('business_id', auth()->user()->business->id)->findOrFail($id);
        $riwayat->delete();

        return redirect()->route('riwayat.index')->with('success', 'Data berhasil dihapus.');
    }
}
