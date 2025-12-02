<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function suggestPrice(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'modal' => 'required|numeric',
            'jenis_produk' => 'required|string',
        ]);

        $prompt = "Saya memiliki produk baru:\n";
        $prompt .= "- Nama: {$request->nama_produk}\n";
        $prompt .= '- Modal: Rp '.number_format($request->modal, 0, ',', '.')."\n";
        $prompt .= "- Kategori: {$request->jenis_produk}\n\n";
        $prompt .= 'Berikan rekomendasi harga jual yang kompetitif namun menguntungkan. ';
        $prompt .= 'Berikan output HANYA dalam format JSON valid berikut tanpa markdown ```json: {"price": 15000, "reason": "Alasan singkat maksimal 2 kalimat"}';

        // Get Business context
        $business = auth()->user()->business;
        if (! $business) {
            $business = new \App\Models\Business;
            $business->nama_bisnis = 'Bisnis Saya';
            // Defaults...
        }

        try {
            $response = $this->geminiService->sendChat($prompt, $business);

            // Clean up response if it contains markdown code blocks
            $cleanResponse = str_replace(['```json', '```'], '', $response);
            $json = json_decode($cleanResponse, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return response()->json($json);
            } else {
                // Fallback if JSON parsing fails
                return response()->json([
                    'price' => $request->modal * 1.3, // Default 30% margin
                    'reason' => $response,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $produks = Produk::where('business_id', auth()->user()->business?->id)->latest()->get();

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
        $validated['business_id'] = auth()->user()->business?->id;

        Produk::create($validated);

        return redirect()->route('produk.index')->with('success', 'Produk created successfully.');
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::where('business_id', auth()->user()->business?->id)->findOrFail($id);

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
        $produk = Produk::where('business_id', auth()->user()->business?->id)->findOrFail($id);

        // Delete image file if exists
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk deleted successfully.');
    }
}
