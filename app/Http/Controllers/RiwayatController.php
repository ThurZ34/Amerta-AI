<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Riwayat;

class RiwayatController extends Controller
{
    public function index()
    {
        $business = auth()->user()->business;
        $riwayats = Riwayat::where('business_id', $business->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('riwayat.index', compact('riwayats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|string',
            'harga_satuan' => 'required|string',
            'total_harga' => 'required|string',
            'inventori' => 'required|string',
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
            'jumlah' => 'required|string',
            'harga_satuan' => 'required|string',
            'total_harga' => 'required|string',
            'inventori' => 'required|string',
            'jenis' => 'required|in:pengeluaran,pendapatan',
            'metode_pembayaran' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $riwayat = Riwayat::findOrFail($id);
        $riwayat->update($request->all());

        return redirect()->route('riwayat.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $riwayat = Riwayat::findOrFail($id);
        $riwayat->delete();

        return redirect()->route('riwayat.index')->with('success', 'Data berhasil dihapus.');
    }
}
