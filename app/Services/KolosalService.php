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
            2. Gunakan FORMAT MARKDOWN (Bold poin kunci, bullet points).
            3. Gaya bahasa: Profesional, santai, suportif.
            4. Jika user sapaan, sapa balik dengan nama bisnis.
            5. JANGAN bahas di luar topik bisnis.

            TUGAS VISUAL (JIKA ADA GAMBAR):
            Analisa apakah relevan (Struk, Produk, Laporan). Jika selfie/meme, tolak dengan sopan.
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
            return "Waduh, koneksi ke Amerta (Kolosal) sedang gangguan. Coba kirim ulang ya Bos.";
        }

        return $response->json()['choices'][0]['message']['content'] ?? "Maaf, respon kosong.";
    }

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
                'temperature' => 0.1
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
