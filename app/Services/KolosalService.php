<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Business;

class KolosalService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('KOLOSAL_API_KEY');
        $this->baseUrl = env('KOLOSAL_BASE_URL', 'https://api.kolosal.ai/v1/chat/completions');
        $this->model = env('KOLOSAL_MODEL', 'Claude Sonnet 4.5');
    }
    public function sendChat(string $message, Business $business, ?string $imagePath = null)
    {
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

        $userContent = [];

        $userContent[] = [
            'type' => 'text',
            'text' => $message
        ];

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            $mimeType = Storage::disk('public')->mimeType($imagePath);
            $imageData = base64_encode(Storage::disk('public')->get($imagePath));
            $base64Url = "data:{$mimeType};base64,{$imageData}";

            $userContent[] = [
                'type' => 'image_url',
                'image_url' => [
                    'url' => $base64Url
                ]
            ];
        }

        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemInstruction],
                    ['role' => 'user', 'content' => $userContent]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1500,
            ]);

        if ($response->failed()) {
            Log::error('Kolosal API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return "Waduh, koneksi ke otak AI (Kolosal) terganggu. Coba kirim ulang ya Bos.";
        }

        return $response->json()['choices'][0]['message']['content'] ?? "Maaf, respon AI kosong.";
    }

    public function analyzeProductPromotions($business, $products)
    {
        $productsData = "";
        foreach ($products as $p) {
            $sales = $p->total_terjual_bulan_ini ?? 0;
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

            Contoh Format JSON (Jangan pakai markdown code block):
            {
                \"12\": {
                    \"type\": \"Cuci Gudang\",
                    \"discount_percent\": 20,
                    \"duration_days\": 7,
                    \"reason\": \"Barang lama (60 hari) tidak laku, perlu likuidasi stok.\"
                }
            }
        ";

        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.2,
                'response_format' => ['type' => 'json_object']
            ]);

        if ($response->failed()) {
            return [];
        }

        $text = $response->json()['choices'][0]['message']['content'] ?? null;

        if ($text) {
            $text = str_replace(['```json', '```'], '', $text);
            return json_decode(trim($text), true) ?? [];
        }

        return [];
    }

    /**
     * Fungsi 3: Analisa Struk/Gambar
     */
    public function analyzeReceipt(string $imagePath)
    {
        if (!Storage::disk('public')->exists($imagePath)) {
            return null;
        }

        $mimeType = Storage::disk('public')->mimeType($imagePath);
        $imageData = base64_encode(Storage::disk('public')->get($imagePath));
        $base64Url = "data:{$mimeType};base64,{$imageData}";

        $prompt = "
            Analyze this receipt image and extract the following information in JSON format:
            - merchant_name (string)
            - transaction_date (YYYY-MM-DD)
            - total_amount (number, just the value)
            - items (array of strings, just item names)
            - category_suggestion (string, e.g., 'Bahan Baku', 'Operasional', 'Lainnya')

            Return ONLY the JSON object. Do not wrap in markdown code blocks.
        ";

        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => $prompt],
                            ['type' => 'image_url', 'image_url' => ['url' => $base64Url]]
                        ]
                    ]
                ],
                'temperature' => 0.1, 
            ]);

        if ($response->failed()) {
            Log::error('Kolosal Receipt Error', ['body' => $response->body()]);
            return null;
        }

        $text = $response->json()['choices'][0]['message']['content'] ?? null;

        if ($text) {
            $text = str_replace(['```json', '```'], '', $text);
            return json_decode(trim($text), true);
        }

        return null;
    }
}
