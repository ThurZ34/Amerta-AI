<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function index()
    {
        return view('setup-bisnis');
    }

    public function store(Request $request)
    {
        // Validasi data (sesuai name di form html kamu)
        $validated = $request->validate([
            'nama_bisnis' => 'required|string',
            'status_bisnis' => 'required',
            'kategori_bisnis' => 'required',
            'kategori_manual' => 'nullable|string', // Handle input "Lainnya"
            'channel_penjualan' => 'required',
            'target_pasar' => 'required',
            'range_omset' => 'required',
            'jumlah_tim' => 'required',
            'tujuan_utama' => 'required',
        ]);

        // Logic untuk Kategori Lainnya
        $kategoriFinal = $validated['kategori_bisnis'];
        if ($kategoriFinal === 'Lainnya' && !empty($validated['kategori_manual'])) {
            $kategoriFinal = $validated['kategori_manual'];
        }

        // Find or create category
        $category = Category::firstOrCreate(
            ['name' => $kategoriFinal],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // Simpan ke Database via Relasi User
        Auth::user()->business()->create([
            'nama_bisnis' => $validated['nama_bisnis'],
            'status_bisnis' => $validated['status_bisnis'],
            'category_id' => $category->id,
            'channel_penjualan' => $validated['channel_penjualan'],
            'target_pasar' => $validated['target_pasar'],
            'range_omset' => $validated['range_omset'],
            'jumlah_tim' => $validated['jumlah_tim'],
            'tujuan_utama' => $validated['tujuan_utama'],
        ]);

        return redirect()->route('dashboard');
    }
}
