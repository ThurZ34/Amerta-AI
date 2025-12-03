<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function bussiness_index()
    {
        $business = Business::with('category')->where('user_id', auth()->user()->id)->first();
        $categories = Category::orderBy('name')->get();
        return view('profil.index', compact('business', 'categories'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_bisnis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string',
            'target_pasar' => 'nullable|string',
            'jumlah_tim' => 'nullable|integer',
            'tujuan_utama' => 'nullable|string',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
        ]);

        $business = Business::where('user_id', auth()->user()->id)->first();
        
        if (!$business) {
            $business = new Business();
            $business->user_id = auth()->user()->id;
        }

        // Handle category - find or create
        if ($request->filled('kategori')) {
            $category = Category::firstOrCreate(
                ['name' => $request->kategori],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $business->category_id = $category->id;
        }

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $request->validate([
                'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Delete old image if exists
            if ($business->gambar && Storage::disk('public')->exists($business->gambar)) {
                Storage::disk('public')->delete($business->gambar);
            }

            $path = $request->file('gambar')->store('business_images', 'public');
            $business->gambar = $path;
        }

        // Update other fields
        $business->fill($request->except(['kategori', 'gambar']));
        $business->save();

        return redirect()->route('profil_bisnis')->with('success', 'Profil bisnis berhasil diperbarui.');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'category' => $category,
        ]);
    }
}
