<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\User;
use App\Models\Category;

class ProfilController extends Controller
{
    public function bussiness_index()
    {
        $business = Business::where('user_id', auth()->user()->id)->first();
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

        $business->update($request->all());

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
