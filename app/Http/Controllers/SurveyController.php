<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function index()
    {
        return view('setup-bisnis');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bisnis' => 'required|string|max:255',
            'status_bisnis' => 'required|string',
            'kategori_bisnis' => 'required|string',
            'masalah_utama' => 'nullable|string',
            'channel_penjualan' => 'required|string',
            'range_omset' => 'required|string',
            'target_pasar' => 'required|string',
            'jumlah_tim' => 'required|string',
            'tujuan_utama' => 'required|string',
        ]);

        $request->user()->business()->create($validated);

        return redirect()->route('dashboard');
    }
}
