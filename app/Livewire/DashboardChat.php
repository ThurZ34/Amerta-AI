<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GeminiService;
use Livewire\WithFileUploads;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class DashboardChat extends Component
{
    use WithFileUploads;

    public $image;
    public $isThinking = false;
    public $limit = 4;
    public $totalChats = 0;
    public $mode = 'full'; // 'full' or 'quick'
    public $ephemeralChats = []; // For quick mode

    public function mount($mode = 'full')
    {
        $this->mode = $mode;
        if ($this->mode === 'full') {
            $this->totalChats = ChatHistory::where('user_id', Auth::id())->count();
        }
    }

    public function loadMore()
    {
        $this->limit += 4;
    }

    public function sendMessage($messageText)
    {
        if (empty(trim($messageText)) && empty($this->image)) {
            return;
        }

        $this->validate([
            'image' => 'nullable|image|max:4096|mimes:jpg,jpeg,png,webp',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('chat-images', 'public');
        }

        if ($this->mode === 'full') {
            ChatHistory::create([
                'user_id' => Auth::id(),
                'role' => 'user',
                'message' => $messageText ?? 'Menganalisa gambar...',
                'image_path' => $imagePath
            ]);
            $this->totalChats++;
        } else {
            $this->ephemeralChats[] = [
                'id' => uniqid(),
                'role' => 'user',
                'message' => $messageText,
                'image_path' => null, // Quick mode doesn't support image upload
                'created_at' => now(),
            ];
        }

        $this->isThinking = true;

        $this->reset('image');

        $this->dispatch('process-ai-reply');
    }

    #[On('process-ai-reply')]
    public function generateAiReply(GeminiService $gemini)
    {
        $business = Auth::user()->business;
        $userMessage = '';
        $imagePath = null;

        if ($this->mode === 'full') {
            $lastUserChat = ChatHistory::where('user_id', Auth::id())
                ->where('role', 'user')
                ->latest()
                ->first();

            $userMessage = $lastUserChat ? $lastUserChat->message : '';
            $imagePath = $lastUserChat ? $lastUserChat->image_path : null;
        } else {
            // Get last user message from ephemeral chats
            $lastChat = end($this->ephemeralChats);
            if ($lastChat && $lastChat['role'] === 'user') {
                $userMessage = $lastChat['message'];
            }
        }

        try {
            $aiReply = $gemini->sendChat($userMessage, $business, $imagePath);
        } catch (\Exception $e) {
            $aiReply = "Maaf Bos, koneksi terputus. Coba lagi ya.";
        }

        if ($this->mode === 'full') {
            ChatHistory::create([
                'user_id' => Auth::id(),
                'role' => 'ai',
                'message' => $aiReply
            ]);
            $this->totalChats++;
        } else {
            $this->ephemeralChats[] = [
                'id' => uniqid(),
                'role' => 'ai',
                'message' => $aiReply,
                'created_at' => now(),
            ];
        }

        $this->isThinking = false;
        $this->dispatch('chat-updated');
    }

    public function removeImage() {
        $this->reset('image');
    }

    public function render()
    {
        if ($this->mode === 'full') {
            $chats = ChatHistory::where('user_id', Auth::id())
                ->latest()
                ->take($this->limit)
                ->get()
                ->sortBy('id');
        } else {
            $chats = collect($this->ephemeralChats);
        }

        return view('livewire.chatbot', [
            'chats' => $chats
        ]);
    }
}
