<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\KolosalService;
use Livewire\WithFileUploads;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use App\Models\Conversation;
use Illuminate\Support\Str;

class DashboardChat extends Component
{
    use WithFileUploads;

    public $image;
    public $isThinking = false;
    public $limit = 4;
    public $totalChats = 0;
    public $mode = 'full';
    public $ephemeralChats = [];
    public $conversationId = null;
    public $conversations = [];

    public function mount($mode = 'full')
    {
        $this->mode = $mode;
        if ($this->mode === 'full') {
            $this->loadConversations();
            $this->conversationId = null;
            $this->totalChats = 0;
        }
    }

    public function loadConversations()
    {
        $this->conversations = Conversation::where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function loadConversation($id)
    {
        $conversation = Conversation::where('user_id', Auth::id())->find($id);
        if ($conversation) {
            $this->conversationId = $conversation->id;
            $this->totalChats = $conversation->chats()->count();
            $this->limit = max(4, $this->totalChats);
        }
    }

    public function newChat()
    {
        $this->conversationId = null;
        $this->totalChats = 0;
        $this->reset('image');
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

            if (!$this->conversationId) {
                $title = Str::limit($messageText ?? 'Image Analysis', 30);
                $conversation = Conversation::create([
                    'user_id' => Auth::id(),
                    'title' => $title
                ]);
                $this->conversationId = $conversation->id;
                $this->loadConversations();
            }

            ChatHistory::create([
                'user_id' => Auth::id(),
                'conversation_id' => $this->conversationId,
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
                'image_path' => null,
                'created_at' => now(),
            ];
        }

        $this->isThinking = true;

        $this->reset('image');

        $this->dispatch('process-ai-reply');
    }

    #[On('process-ai-reply')]
    public function generateAiReply(KolosalService $aiService)
    {
        $business = Auth::user()->business;
        $userMessage = '';
        $imagePath = null;

        if ($this->mode === 'full') {
            $lastUserChat = ChatHistory::where('user_id', Auth::id())
                ->where('conversation_id', $this->conversationId)
                ->where('role', 'user')
                ->latest()
                ->first();

            $userMessage = $lastUserChat ? $lastUserChat->message : '';
            $imagePath = $lastUserChat ? $lastUserChat->image_path : null;
        } else {
            $lastChat = end($this->ephemeralChats);
            if ($lastChat && $lastChat['role'] === 'user') {
                $userMessage = $lastChat['message'];
            }
        }

        try {
            $aiReply = $aiService->sendChat($userMessage, $business, $imagePath);
        } catch (\Exception $e) {
            $aiReply = "Maaf Bos, koneksi ke Amerta terputus. Coba lagi ya.";
        }

        if ($this->mode === 'full') {
            ChatHistory::create([
                'user_id' => Auth::id(),
                'conversation_id' => $this->conversationId,
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
            if ($this->conversationId) {
                $chats = ChatHistory::where('conversation_id', $this->conversationId)
                    ->latest()
                    ->take($this->limit)
                    ->get()
                    ->sortBy('id');
            } else {
                $chats = collect([]);
            }
        } else {
            $chats = collect($this->ephemeralChats)->map(function ($chat) {
                return (object) $chat;
            });
        }

        return view('livewire.chatbot', [
            'chats' => $chats
        ]);
    }
}
