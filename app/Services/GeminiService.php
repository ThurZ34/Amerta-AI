<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Business;

class GeminiService
{
    public function sendChat(string $message, Business $business, ?string $imagePath = null)
    {
        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model');

        $categoryName = $business->category ? $business->category->name : 'Tidak ada';

        $systemInstruction = "
            PERAN: Kamu adalah 'Amerta', asisten konsultan bisnis profesional untuk UMKM.
            PROFIL BISNIS PENGGUNA:
            - Nama Bisnis: {$business->nama_bisnis}
            - Kategori: {$categoryName}
            - Status: {$business->status_bisnis}
            - Target Pasar: {$business->target_pasar}
            - Omset: {$business->range_omset}
            - Tim: {$business->jumlah_tim} orang
            - Tujuan: {$business->tujuan_utama}
            - Channel Jualan: {$business->channel_penjualan}

            INSTRUKSI UTAMA:
            1. Jawab pertanyaan user secara spesifik berdasarkan data profil di atas.
            2. Gunakan FORMAT MARKDOWN:
               - Gunakan **bold** untuk poin kunci.
               - Gunakan daftar (bullet points) untuk langkah-langkah.
               - Berikan spasi antar paragraf agar enak dibaca.
            3. Gaya bahasa: Profesional, santai, suportif (seperti mentor yang baik).
            4. Jika user hanya menyapa (Halo/Hai), sapa balik dengan menyebut nama bisnisnya.
            5. JANGAN menjawab hal di luar topik bisnis.

            TUGAS VISUAL (JIKA ADA GAMBAR):
            1. ANALISA GAMBAR: Cek apakah gambar relevan dengan operasional bisnis (Struk, Laporan Tulis Tangan, Produk, Tempat Usaha, Promosi).
            2. TOLAK JIKA TIDAK RELEVAN: Jika gambar adalah selfie, pemandangan, hewan, atau meme, jawab: 'Maaf, saya hanya bisa menganalisa gambar yang berkaitan dengan bisnis {$business->nama_bisnis}.'
            3. EKSEKUSI: Jika relevan (misal struk), baca detail angkanya dan berikan saran keuangan/stok.
        ";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $userContentParts = [];

        $userContentParts[] = ['text' => $systemInstruction . "\n\nPERTANYAAN USER: " . $message];

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            $mimeType = Storage::disk('public')->mimeType($imagePath);

            $supportedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/heic', 'image/heif'];

            if (in_array($mimeType, $supportedMimes)) {
                $imageData = base64_encode(Storage::disk('public')->get($imagePath));
                $userContentParts[] = [
                    'inlineData' => [
                        'mimeType' => $mimeType,
                        'data' => $imageData
                    ]
                ];
            } else {
                Log::warning("Format gambar tidak didukung: {$mimeType}");
                $userContentParts[] = ['text' => "[SISTEM: User mencoba upload gambar tapi format $mimeType tidak didukung. Beritahu user untuk upload JPG/PNG]"];
            }
        }

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($url, [
                'contents' => [[
                    'role' => 'user',
                    'parts' => $userContentParts
                ]]
            ]);

        if ($response->failed()) {
            Log::error('Gemini API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return "Waduh, koneksi ke otak AI terganggu. Coba kirim ulang ya Bos.";
        }

        $data = $response->json();

        return $data['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, saya tidak bisa membaca respon AI.";
    }
    public function analyzeProductPromotions($business, $products)
    {
        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $productsData = "";
        foreach ($products as $p) {
            $sales = $p->total_terjual_bulan_ini ?? 0;
            $margin = $p->harga_jual - $p->modal;
            $ageDays = $p->created_at ? round(now()->diffInDays($p->created_at)) : 30; 
            $productsData .= "- ID: {$p->id} | Nama: {$p->nama_produk} | Harga: {$p->harga_jual} | Modal: {$p->modal} | Terjual Bulan Ini: {$sales} | Umur: {$ageDays} hari\n";
        }

        $prompt = "
            PERAN: Kamu adalah Konsultan Bisnis & Pricing Expert.

            KONTEKS BISNIS:
            Nama: {$business->nama_bisnis}

            DATA PRODUK:
            {$productsData}

            TUGAS:
            Analisis data penjualan. Identifikasi produk yang butuh diskon/promosi.

            PANDUAN STRATEGI:
            1. Jika 'Umur' < 7 hari dan sales 0: JANGAN anggap stok mati. Ini produk baru. Diskon hanya jika 'Intro Price' strategis (maks 10-15%).
            2. Jika 'Umur' > 30 hari dan sales 0: Ini 'Dead Stock'. Diskon agresif (20-50%) untuk cuci gudang.
            3. Jika Sales tinggi & Margin tebal: Tawarkan 'Bundling' atau 'Volume Discount'.

            OUTPUT JSON (HANYA JSON):
            Kembalikan object JSON dimana KEY adalah ID Produk, dan VALUE adalah object rekomendasi.
            Hanya sertakan produk yang MEMANG butuh tindakan strategis.

            Key 'duration_days' adalah rekomendasi durasi promo dalam hari (misal 3, 7, 30).

            Contoh Format:
            {
                \"12\": {
                    \"type\": \"Cuci Gudang\",
                    \"discount_percent\": 20,
                    \"duration_days\": 7,
                    \"reason\": \"Barang lama (60 hari) tidak laku, perlu likuidasi stok.\"
                },
                \"15\": {
                    \"type\": \"Promo Bundling\",
                    \"discount_percent\": 10,
                    \"duration_days\": 14,
                    \"reason\": \"Produk laku keras dengan margin tebal, dorong pembelian jumlah banyak.\"
                }
            }
        ";

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($url, [
                'contents' => [[
                    'parts' => [['text' => $prompt]]
                ]]
            ]);

        if ($response->failed()) {
            return [];
        }

        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if ($text) {
            $text = str_replace(['```json', '```'], '', $text);
            return json_decode(trim($text), true) ?? [];
        }

        return [];
    }
    public function analyzeReceipt(string $imagePath)
    {
        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        if (!Storage::disk('public')->exists($imagePath)) {
            return null;
        }

        $mimeType = Storage::disk('public')->mimeType($imagePath);
        $imageData = base64_encode(Storage::disk('public')->get($imagePath));

        $prompt = "
            Analyze this receipt image and extract the following information in JSON format:
            - merchant_name (string)
            - transaction_date (YYYY-MM-DD)
            - total_amount (number, just the value)
            - items (array of strings, just item names)
            - category_suggestion (string, e.g., 'Bahan Baku', 'Operasional', 'Lainnya')

            Return ONLY the JSON. No markdown formatting, no code blocks.
        ";

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($url, [
                'contents' => [[
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inlineData' => [
                                'mimeType' => $mimeType,
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]]
            ]);

        if ($response->failed()) {
            Log::error('Gemini Receipt Analysis Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;
        }

        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if ($text) {
            $text = str_replace('```json', '', $text);
            $text = str_replace('```', '', $text);
            return json_decode(trim($text), true);
        }

        return null;
    }
}
