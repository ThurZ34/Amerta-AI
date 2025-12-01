<div class="flex flex-col h-full relative">

    <div
        class="h-16 border-b border-gray-800 flex items-center justify-between px-6 bg-gray-900/90 backdrop-blur z-10 absolute top-0 w-full">
        <div>
            <h2 class="text-lg font-bold text-white">Amerta Assistant</h2>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                <span class="text-xs text-gray-400">Online & Siap Membantu</span>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto chat-scroll p-6 pt-24 pb-24 space-y-6" id="chat-container">

        <div class="flex items-start gap-4 max-w-[85%]">
            <div
                class="w-8 h-8 rounded-full bg-indigo-600 shrink-0 flex items-center justify-center text-white text-xs font-bold">
                AI</div>
            <div
                class="bg-gray-800/80 p-4 rounded-2xl rounded-tl-none border border-gray-700 text-gray-200 text-sm shadow-sm">
                <p>Halo Bos! 👋 Profil <strong>{{ Auth::user()->business->nama_bisnis }}</strong> sudah siap. Mau bahas
                    strategi apa?</p>
            </div>
        </div>

        @foreach ($chats as $chat)
            @if ($chat->role == 'user')
                <div class="flex items-end gap-3 justify-end">
                    <div class="bg-indigo-600 text-white p-4 rounded-2xl rounded-tr-none max-w-[80%] text-sm shadow-md">
                        {{ $chat->message }}
                    </div>
                </div>
            @else
                <div class="flex items-start gap-4 max-w-[85%]">
                    <div
                        class="w-8 h-8 rounded-full bg-indigo-600 shrink-0 flex items-center justify-center text-white text-xs font-bold">
                        AI</div>

                    <div
                        class="bg-gray-800/80 p-5 rounded-2xl rounded-tl-none border border-gray-700 text-gray-200 shadow-sm prose prose-invert prose-sm max-w-none">
                        <div x-data x-html="marked.parse(@js($chat->message))"></div>
                    </div>
                </div>
            @endif
        @endforeach

        <div wire:loading wire:target="sendMessage" class="flex items-start gap-4">
            <span class="text-gray-500 text-sm italic ml-12">Sedang mengetik...</span>
        </div>

    </div>

    <div class="absolute bottom-0 left-0 w-full bg-gray-900 border-t border-gray-800 p-4">
        <form wire:submit.prevent="sendMessage" class="relative max-w-4xl mx-auto flex items-end gap-2">

            <div class="flex-1 relative">
                <input type="text" wire:model="message" placeholder="Tanya strategi, curhat masalah stok..."
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl py-3 pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-gray-500 shadow-sm"
                    autocomplete="off">
            </div>

            <button type="submit"
                class="p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg transition-all disabled:opacity-50"
                wire:loading.attr="disabled">
                <svg class="w-6 h-6 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
    // Auto Scroll ke bawah saat chat bertambah
    document.addEventListener('livewire:initialized', () => {
        const chatContainer = document.getElementById('chat-container');

        // Scroll saat load pertama
        chatContainer.scrollTop = chatContainer.scrollHeight;

        // Scroll saat ada pesan baru
        Livewire.hook('morph.updated', ({
            component
        }) => {
            setTimeout(() => {
                chatContainer.scrollTo({
                    top: chatContainer.scrollHeight,
                    behavior: 'smooth'
                });
            }, 100);
        });
    });
</script>
