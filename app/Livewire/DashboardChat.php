<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Auth;

class DashboardChat extends Component
{
    public $message = '';
    public $chatHistory = []; // Menyimpan chat sesi ini
    public $isLoading = false;

    // Fungsi ini dipanggil saat tombol kirim ditekan
    public function sendMessage(GeminiService $gemini)
    {
        $this->validate(['message' => 'required|string']);

        $userMessage = $this->message;
        $this->message = ''; // Reset input field
        $this->isLoading = true;

        // 1. Tampilkan chat user dulu di UI
        $this->chatHistory[] = [
            'role' => 'user',
            'message' => $userMessage,
            'time' => now()->format('H:i')
        ];

        // 2. Ambil Data Bisnis User Login
        $business = Auth::user()->business;

        // 3. Panggil AI dengan Konteks Bisnis
        try {
            $aiReply = $gemini->sendChat($userMessage, $business);
        } catch (\Exception $e) {
            $aiReply = "Error: Gagal terhubung ke AI.";
        }

        // 4. Masukkan jawaban AI ke UI
        $this->chatHistory[] = [
            'role' => 'ai',
            'message' => $aiReply,
            'time' => now()->format('H:i')
        ];

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.dashboard-chat');
    }
}
