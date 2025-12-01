<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Business; // Import Model Business

class GeminiService
{
    public function sendChat(string $message, Business $business)
    {
        $apiKey = env('GEMINI_API_KEY');
        $model = "gemini-2.5-flash"; // Pastikan model ini aktif

        // --- CONTEXT INJECTION (RAHASIA AI PINTAR) ---
        // Kita rangkum data database jadi kalimat instruksi
        $systemInstruction = "
            PERAN: Kamu adalah 'Amerta', asisten konsultan bisnis profesional untuk UMKM.

            PROFIL BISNIS PENGGUNA (CONTEXT):
            - Nama Bisnis: {$business->nama_bisnis}
            - Kategori: {$business->kategori_bisnis}
            - Status: {$business->status_bisnis}
            - Target Pasar: {$business->target_pasar}
            - Omset: {$business->range_omset}
            - Tim: {$business->jumlah_tim} orang
            - Tujuan: {$business->tujuan_utama}
            - Channel Jualan: {$business->channel_penjualan}

            TUGAS:
            1. Jawab pertanyaan user berdasarkan data profil di atas.
            2. Jika user bertanya strategi, sesuaikan dengan omset dan ukuran tim mereka.
            3. Gaya bahasa: Profesional tapi santai, suportif, dan memotivasi.
            4. Gunakan format Markdown (bold, list) agar mudah dibaca.
            5. JANGAN menjawab hal di luar topik bisnis/pengembangan diri.
        ";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        // Gabungkan System Instruction dengan Pertanyaan User
                        ['text' => $systemInstruction . "\n\nPERTANYAAN USER: " . $message]
                    ]
                ]
            ]
        ]);

        if ($response->failed()) {
            Log::error('Gemini API Error: ' . $response->body());
            return "Maaf Bos, sistem lagi sibuk. Coba lagi nanti ya.";
        }

        $data = $response->json();

        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            return $data['candidates'][0]['content']['parts'][0]['text'];
        }

        return "Maaf, saya tidak mengerti konteksnya.";
    }
}
