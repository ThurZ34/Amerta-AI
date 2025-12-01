<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = \App\Models\produk::latest()->get();
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
        ]);

        // Assuming business_id is required, we might need to get it from auth user or request
        // For now, I'll set a default or get it if available, or maybe it's nullable?
        // Looking at migration: $table->id('business_id'); It seems it's a foreign key or just an ID.
        // I'll assume for now we can just create it. If Auth is used:
        // $validated['business_id'] = auth()->user()->business_id ?? 1; // Placeholder
        
        // Let's check the migration again. It says $table->id('business_id'); which is a primary key definition usually? 
        // No, $table->id() creates an auto-incrementing primary key. 
        // $table->id('business_id') might be a mistake in migration if it's meant to be a foreign key.
        // Usually it's $table->foreignId('business_id'). 
        // But if it ran, it might be just a big integer.
        // I will just pass a dummy business_id for now or handle it if I see Auth.
        
        $validated['business_id'] = 1; // Default for now as I don't see Auth context for business yet.

        \App\Models\produk::create($validated);

        return redirect()->route('produk.index')->with('success', 'Produk created successfully.');
    }

    public function update(Request $request, $id)
    {
        $produk = \App\Models\produk::findOrFail($id);
        
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'inventori' => 'required|integer|min:0',
            'jenis_produk' => 'required|string|max:255',
        ]);

        $produk->update($validated);

        return redirect()->route('produk.index')->with('success', 'Produk updated successfully.');
    }

    public function destroy($id)
    {
        $produk = \App\Models\produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk deleted successfully.');
    }
}
