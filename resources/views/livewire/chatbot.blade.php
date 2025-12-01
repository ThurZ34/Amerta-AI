<div class="flex flex-col h-full relative bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div
        class="h-16 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-6 bg-white/90 dark:bg-gray-900/90 backdrop-blur z-10 absolute top-0 w-full transition-colors duration-300">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">Amerta Assistant</h2>
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400">Online & Siap Membantu</span>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto chat-scroll p-6 pt-24 pb-24 space-y-6" id="chat-container" x-ref="chatContainer">

        @if ($totalChats > $limit)
            <div x-intersect="$wire.loadMore()" class="flex justify-center py-2">
                <div
                    class="bg-indigo-50 dark:bg-gray-800 text-indigo-600 dark:text-gray-400 text-xs px-3 py-1 rounded-full flex items-center gap-2 shadow-sm transition-all duration-300">
                    <svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span>Memuat chat lama...</span>
                </div>
            </div>
        @else
            <div class="flex items-start gap-4 max-w-[85%] fade-in-up">
                <div
                    class="w-8 h-8 rounded-full bg-indigo-600 shrink-0 flex items-center justify-center text-white text-xs font-bold shadow-md">
                    AI</div>
                <div
                    class="bg-white dark:bg-gray-800 p-4 rounded-2xl rounded-tl-none border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm shadow-sm">
                    <p>Halo Bos! 👋 Profil <strong>{{ Auth::user()->business->nama_bisnis ?? 'Bisnis Kamu' }}</strong>
                        sudah siap. Ada yang bisa saya bantu?</p>
                </div>
            </div>
        @endif

        @foreach ($chats as $chat)
            @if ($chat->role == 'user')
                <div class="flex items-end gap-3 justify-end fade-in-up">
                    <div class="bg-indigo-600 text-white p-4 rounded-2xl rounded-tr-none max-w-[85%] text-sm shadow-md">
                        {{ $chat->message }}
                    </div>
                </div>
            @else
                <div class="flex items-start gap-4 max-w-[85%] fade-in-up">
                    <div
                        class="w-8 h-8 rounded-full bg-indigo-600 shrink-0 flex items-center justify-center text-white text-xs font-bold shadow-md">
                        AI</div>
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-2xl rounded-tl-none border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 shadow-sm text-sm leading-relaxed prose prose-sm prose-indigo dark:prose-invert max-w-none">
                        <div x-data x-html="marked.parse(@js($chat->message))"></div>
                    </div>
                </div>
            @endif
        @endforeach

        @if ($isThinking)
            <div class="flex items-start gap-4 fade-in-up">
                <div
                    class="w-8 h-8 rounded-full bg-indigo-600 shrink-0 flex items-center justify-center text-white text-xs font-bold animate-pulse">
                    ...</div>
                <div
                    class="bg-gray-100 dark:bg-gray-800 p-4 rounded-2xl rounded-tl-none border border-gray-200 dark:border-gray-700 flex items-center gap-1 w-fit h-10 shadow-sm">
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.15s"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                </div>
            </div>
        @endif

    </div>

    <div
        class="absolute bottom-0 left-0 w-full bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 p-4 transition-colors duration-300">

        <form x-data="{ userMessage: '' }"
            @submit.prevent="
                if(userMessage.trim() !== '') {
                    $wire.sendMessage(userMessage); // Kirim ke Livewire
                    userMessage = ''; // Hapus teks INSTANT di browser
                }
            "
            class="relative max-w-4xl mx-auto flex items-end gap-2">

            <div class="flex-1 relative">
                <input type="text" x-model="userMessage" placeholder="Tanya strategi, curhat masalah stok..."
                    class="w-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl py-3 pl-4 pr-12 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-gray-500 transition-colors shadow-inner"
                    autocomplete="off" @if ($isThinking) disabled @endif>
            </div>

            <button type="submit"
                class="p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed hover:-translate-y-1"
                :disabled="userMessage.trim() === '' || @js($isThinking)">
                <svg wire:loading.remove wire:target="sendMessage" class="w-6 h-6 transform rotate-90" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>

                <svg wire:loading wire:target="sendMessage" class="animate-spin w-6 h-6 text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const chatContainer = document.getElementById('chat-container');

            const scrollBottom = () => {
                if (chatContainer) {
                    chatContainer.scrollTo({
                        top: chatContainer.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            };

            scrollBottom();

            Livewire.on('chat-updated', () => {
                setTimeout(scrollBottom, 100);
            });

            Livewire.hook('commit', ({
                component,
                commit,
                respond,
                succeed,
                fail
            }) => {
                succeed(({
                    snapshot,
                    effect
                }) => {
                    if (commit.calls.some(call => call.method === 'sendMessage')) {
                        setTimeout(scrollBottom, 50);
                    }
                })
            });
        });
    </script>

    <style>
        .fade-in-up {
            animation: fadeInUp 0.3s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .chat-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .dark .chat-scroll::-webkit-scrollbar-thumb {
            background-color: #4b5563;
        }
    </style>

</div>
