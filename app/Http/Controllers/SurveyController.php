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
        // Jika user sudah punya bisnis, redirect ke dashboard
        if (Auth::user()->business()->exists()) {
            return redirect()->route('dashboard');
        }

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
        $business = Business::create([
            'user_id' => Auth::id(), // <--- INI PALING PENTING (Memasukkan ID user login)
            'category_id' => $category->id,
            'nama_bisnis' => $validated['nama_bisnis'],
            'status_bisnis' => $validated['status_bisnis'],
            'channel_penjualan' => $validated['channel_penjualan'],
            'target_pasar' => $validated['target_pasar'],
            'range_omset' => $validated['range_omset'],
            'jumlah_tim' => $validated['jumlah_tim'],
            'tujuan_utama' => $validated['tujuan_utama'],
        ]);

        $user = Auth::user();
        $user->business_id = $business->id;
        $user->save();

        return redirect()->route('dashboard');
    }
}
