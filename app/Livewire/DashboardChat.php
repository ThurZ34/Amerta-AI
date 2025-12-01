<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GeminiService;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\Auth;

class DashboardChat extends Component
{
    public $message = '';

    public function sendMessage(GeminiService $gemini)
    {
        $this->validate(['message' => 'required|string']);

        $userMessage = $this->message;

        // 1. Simpan Chat User ke DB
        ChatHistory::create([
            'user_id' => Auth::id(),
            'role' => 'user',
            'message' => $userMessage
        ]);

        // 2. Reset Input (Ini memperbaiki bug input nyangkut)
        $this->reset('message');

        // 3. Ambil Data Bisnis
        $business = Auth::user()->business;

        // 4. Panggil AI
        try {
            // Ambil 5 chat terakhir sebagai konteks tambahan (supaya AI ingat obrolan sebelumnya)
            // Opsional, tapi bagus untuk UX. Untuk sekarang kita kirim pesan baru saja.
            $aiReply = $gemini->sendChat($userMessage, $business);
        } catch (\Exception $e) {
            $aiReply = "Maaf Bos, koneksi terputus. Coba lagi ya.";
        }

        // 5. Simpan Balasan AI ke DB
        ChatHistory::create([
            'user_id' => Auth::id(),
            'role' => 'ai',
            'message' => $aiReply
        ]);
    }

    public function render()
    {
        // Load semua history punya user ini
        $chats = ChatHistory::where('user_id', Auth::id())->get();

        return view('livewire.chatbot', [
            'chats' => $chats
        ]);
    }
}
