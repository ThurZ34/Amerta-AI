<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GeminiService;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class DashboardChat extends Component
{
    public $isThinking = false;
    public $limit = 4;
    public $totalChats = 0;

    public function mount()
    {
        $this->totalChats = ChatHistory::where('user_id', Auth::id())->count();
    }

    public function loadMore()
    {
        $this->limit += 4;
    }

    public function sendMessage($messageText)
    {
        if (empty(trim($messageText))) {
            return;
        }

        ChatHistory::create([
            'user_id' => Auth::id(),
            'role' => 'user',
            'message' => $messageText
        ]);

        $this->totalChats++;

        $this->isThinking = true;

        $this->dispatch('process-ai-reply');
    }

    #[On('process-ai-reply')]
    public function generateAiReply(GeminiService $gemini)
    {
        $business = Auth::user()->business;

        $lastUserChat = ChatHistory::where('user_id', Auth::id())
            ->where('role', 'user')
            ->latest()
            ->first();

        $userMessage = $lastUserChat ? $lastUserChat->message : '';

        try {
            $aiReply = $gemini->sendChat($userMessage, $business);
        } catch (\Exception $e) {
            $aiReply = "Maaf Bos, koneksi terputus. Coba lagi ya.";
        }

        ChatHistory::create([
            'user_id' => Auth::id(),
            'role' => 'ai',
            'message' => $aiReply
        ]);

        $this->totalChats++;
        $this->isThinking = false;

        $this->dispatch('chat-updated');
    }

    public function render()
    {
        $chats = ChatHistory::where('user_id', Auth::id())
            ->latest()
            ->take($this->limit)
            ->get()
            ->sortBy('id');

        return view('livewire.chatbot', [
            'chats' => $chats
        ]);
    }
}
