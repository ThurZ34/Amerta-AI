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

        $business = auth()->user()->business;
        if (! $business) {
            return response()->json(['error' => 'Bisnis tidak ditemukan. Silakan setup bisnis terlebih dahulu.'], 400);
        }

        try {
            $response = $this->geminiService->sendChat($prompt, $business);

            $cleanResponse = str_replace(['```json', '```'], '', $response);
            $json = json_decode($cleanResponse, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return response()->json($json);
            } else {
                return response()->json([
                    'price' => $request->modal * 1.3,
                    'reason' => 'AI sedang sibuk. Ini adalah estimasi margin standar 30%. ('.Str::limit($response, 50).')',
                    'is_fallback' => true,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        $produks = Produk::where('business_id', auth()->user()->business?->id)->latest()->get();

        $produks->each(function ($produk) use ($month, $year) {
            $produk->total_terjual_bulan_ini = $produk->getTotalTerjualPerBulan($month, $year);
        });

        return view('produk.index', compact('produks', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'harga_coret' => 'nullable|numeric|min:0',
            'promo_end_date' => 'nullable|date',
            'jenis_produk' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('produk-images', 'public');
            $validated['gambar'] = $gambarPath;
        }

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
            'harga_coret' => 'nullable|numeric|min:0',
            'promo_end_date' => 'nullable|date',
            'jenis_produk' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($request->hasFile('gambar')) {
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

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk deleted successfully.');
    }
    public function analyze(Request $request)
    {
        $business = auth()->user()->business;
        if (!$business) {
            return response()->json(['error' => 'Bisnis tidak ditemukan'], 400);
        }

        $month = now()->format('m');
        $year = now()->format('Y');

        $products = Produk::where('business_id', $business->id)->get();
        
        $products->each(function ($produk) use ($month, $year) {
            $produk->total_terjual_bulan_ini = $produk->getTotalTerjualPerBulan($month, $year);
        });

        try {
            $analysis = $this->geminiService->analyzeProductPromotions($business, $products);
            return response()->json($analysis);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
