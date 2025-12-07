<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function index()
    {
        if (Auth::user()->business()->exists()) {
            return redirect()->route('analisis.dashboard');
        }

        return view('setup-bisnis');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bisnis' => 'required|string',
            'status_bisnis' => 'required',
            'masalah_utama' => 'nullable|string',
            'kategori_bisnis' => 'required',
            'kategori_manual' => 'nullable|string',
            'channel_penjualan' => 'required',
            'target_pasar' => 'required',
            'range_omset' => 'required',
            'jumlah_tim' => 'nullable',
            'tujuan_utama' => 'required',
        ]);

        $kategoriFinal = $validated['kategori_bisnis'];
        if ($kategoriFinal === 'Lainnya' && !empty($validated['kategori_manual'])) {
            $kategoriFinal = $validated['kategori_manual'];
        }

        $category = Category::firstOrCreate(
            ['name' => $kategoriFinal],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $business = Business::create([
            'user_id' => Auth::id(),
            'category_id' => $category->id,
            'nama_bisnis' => $validated['nama_bisnis'],
            'status_bisnis' => $validated['status_bisnis'],
            'masalah_utama' => $validated['masalah_utama'] ?? null,
            'channel_penjualan' => $validated['channel_penjualan'],
            'target_pasar' => $validated['target_pasar'],
            'range_omset' => $validated['range_omset'],
            'jumlah_tim' => $validated['jumlah_tim'] ?? null,
            'tujuan_utama' => $validated['tujuan_utama'],
        ]);

        $user = Auth::user();
        $user->business_id = $business->id;
        $user->role = 'owner';
        $user->save();

        session()->flash('first_time_entry', true);

        return redirect()->route('main_menu');
    }
}
