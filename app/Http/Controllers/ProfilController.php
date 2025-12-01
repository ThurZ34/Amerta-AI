<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\User;

class ProfilController extends Controller
{
    public function bussiness_index()
    {
        $business = Business::where('user_id', auth()->user()->id)->first();
        return view('profil.index', compact('business'));
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
}
