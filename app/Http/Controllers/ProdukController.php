<?php

namespace App\Http\Controllers;
 
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::latest()->get();
        return view('produk.index', compact('produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'inventori' => 'required|integer|min:0',
            'jenis_produk' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('produk-images', 'public');
            $validated['gambar'] = $gambarPath;
        }

        // Set business_id (adjust based on your auth logic)
        $validated['business_id'] = auth()->user()->business_id ?? 1;

        Produk::create($validated);

        return redirect()->route('produk.index')->with('success', 'Produk created successfully.');
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'inventori' => 'required|integer|min:0',
            'jenis_produk' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Changed to nullable
        ]);

        // Handle file upload if new image is provided
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            
            $gambarPath = $request->file('gambar')->store('produk-images', 'public');
            $validated['gambar'] = $gambarPath;
        }

        $produk->update($validated);

        return redirect()->route('produk.index')->with('success', 'Produk updated successfully.');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        // Delete image file if exists
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk deleted successfully.');
    }
}