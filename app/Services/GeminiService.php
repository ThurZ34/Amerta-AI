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
        $apiKey = env('GEMINI_API_KEY');
        $model = "gemini-2.5-flash"; // Pastikan model ini aktif

        // Get category name safely
        $categoryName = $business->category ? $business->category->name : 'Tidak ada';

        // --- CONTEXT INJECTION (RAHASIA AI PINTAR) ---
        // Kita rangkum data database jadi kalimat instruksi
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

        // Siapkan Payload (Isi Pesan)
        $userContentParts = [];

        // 1. Masukkan Instruksi & Teks User
        $userContentParts[] = ['text' => $systemInstruction . "\n\nPERTANYAAN USER: " . $message];

        // 2. Masukkan Gambar (Jika ada)
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            $mimeType = Storage::disk('public')->mimeType($imagePath);

            // Gemini hanya support tipe file tertentu untuk gambar
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
                // Opsional: Tambahkan text info ke AI bahwa gambar gagal dimuat
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

        // Error Handling
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
}
