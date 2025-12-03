<div class="flex h-full relative bg-gray-50 dark:bg-gray-900 transition-colors duration-300 overflow-hidden"
    x-data="{ sidebarOpen: false }">

    {{-- SIDEBAR (History) - Only for Full Mode --}}
    @if ($mode === 'full')
        <div class="absolute inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-900 text-gray-900 dark:text-white transform transition-transform duration-300 ease-in-out flex flex-col border-r border-gray-200 dark:border-gray-800"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- New Chat Button --}}
            <div class="p-4">
                <button wire:click="newChat" @click="sidebarOpen = false"
                    class="w-full flex items-center gap-2 px-4 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-xl transition-colors border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-900 dark:text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Chat Baru
                </button>
            </div>

            {{-- History List --}}
            <div class="flex-1 overflow-y-auto px-2 space-y-1 custom-scrollbar">
                <div class="px-2 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Riwayat</div>
                @foreach ($conversations as $conversation)
                    <button wire:click="loadConversation({{ $conversation->id }})" @click="sidebarOpen = false"
                        class="w-full text-left px-3 py-2 rounded-lg text-sm truncate transition-colors {{ $conversationId == $conversation->id ? 'bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        {{ $conversation->title ?? 'Percakapan Baru' }}
                    </button>
                @endforeach
            </div>

            {{-- User Profile (Bottom) --}}
            <div class="p-4 border-t border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->business->nama_bisnis ?? 'Bisnis' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Overlay --}}
        {{-- Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-20"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
        </div>
    @endif

    {{-- MAIN CHAT AREA --}}
    <div class="flex-1 flex flex-col h-full relative w-full">

        {{-- Header --}}
        <div
            class="h-16 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-4 sm:px-6 bg-white/90 dark:bg-gray-900/90 backdrop-blur z-10">
            <div class="flex items-center gap-3">
                @if ($mode === 'full')
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                @endif

                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                        {{ $mode === 'full' ? 'Amerta Studio' : 'Amerta Quick Assist' }}
                    </h2>
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $mode === 'full' ? 'Mode Konsultasi Penuh' : 'Mode Cepat' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chat Messages --}}
        <div class="flex-1 overflow-y-auto chat-scroll p-4 sm:p-6 space-y-6" id="chat-container">

            @if ($totalChats > $limit && $mode === 'full')
                <div x-intersect="$wire.loadMore()" class="flex justify-center py-2">
                    <div
                        class="bg-indigo-50 dark:bg-gray-800 text-indigo-600 dark:text-gray-400 text-xs px-3 py-1 rounded-full flex items-center gap-2 shadow-sm">
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
            @endif

            @if ($chats->isEmpty())
                <div class="flex flex-col items-center justify-center h-full text-center px-4 fade-in-up">
                    <div
                        class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center mb-4 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                        Halo, {{ Auth::user()->name }}!
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-md mb-8">
                        Saya Amerta, asisten bisnis pribadi Anda. Ada yang bisa saya bantu analisa hari ini?
                    </p>

                    @if ($mode === 'full')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full max-w-2xl">
                            <button wire:click="sendMessage('Buatkan strategi pemasaran digital untuk bulan ini')"
                                class="text-left p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-indigo-500 dark:hover:border-indigo-500 hover:shadow-md transition-all group">
                                <span class="text-lg mb-2 block">🚀</span>
                                <h3
                                    class="font-semibold text-gray-800 dark:text-white text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                    Strategi Pemasaran</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Rencana konten & iklan.</p>
                            </button>
                            <button wire:click="sendMessage('Analisis tren penjualan saya dan berikan saran')"
                                class="text-left p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-indigo-500 dark:hover:border-indigo-500 hover:shadow-md transition-all group">
                                <span class="text-lg mb-2 block">📊</span>
                                <h3
                                    class="font-semibold text-gray-800 dark:text-white text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                    Analisis Tren</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Cek performa & peluang.</p>
                            </button>
                            <button
                                wire:click="sendMessage('Buatkan caption Instagram yang menarik untuk produk unggulan')"
                                class="text-left p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-indigo-500 dark:hover:border-indigo-500 hover:shadow-md transition-all group">
                                <span class="text-lg mb-2 block">✍️</span>
                                <h3
                                    class="font-semibold text-gray-800 dark:text-white text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                    Ide Konten</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Caption IG/TikTok viral.</p>
                            </button>
                            <button wire:click="sendMessage('Bagaimana cara menghemat biaya operasional?')"
                                class="text-left p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-indigo-500 dark:hover:border-indigo-500 hover:shadow-md transition-all group">
                                <span class="text-lg mb-2 block">💰</span>
                                <h3
                                    class="font-semibold text-gray-800 dark:text-white text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                    Efisiensi Biaya</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tips hemat budget.</p>
                            </button>
                        </div>
                    @endif
                </div>
            @else
                @foreach ($chats as $chat)
                    @if ($chat->role == 'user')
                        <div class="flex items-end gap-3 justify-end fade-in-up">
                            <div
                                class="bg-indigo-600 text-white p-4 rounded-2xl rounded-tr-none max-w-[85%] sm:max-w-[75%] text-sm shadow-md flex flex-col gap-2">
                                @if ($chat->image_path)
                                    <img src="{{ asset('storage/' . $chat->image_path) }}"
                                        class="rounded-lg w-full max-w-[200px] h-auto object-cover border border-indigo-500">
                                @endif
                                <p>{{ $chat->message }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-4 max-w-[90%] sm:max-w-[85%] fade-in-up group">
                            <div
                                class="w-8 h-8 rounded-full bg-indigo-600 shrink-0 flex items-center justify-center text-white text-xs font-bold shadow-md mt-1">
                                AI</div>
                            <div x-data="{
                                copied: false,
                                copyToClipboard() {
                                    navigator.clipboard.writeText(@js($chat->message)).then(() => {
                                        this.copied = true;
                                        setTimeout(() => this.copied = false, 2000);
                                    });
                                }
                            }"
                                class="relative bg-white dark:bg-gray-800 px-5 py-4 rounded-2xl rounded-tl-none border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 shadow-sm text-sm leading-relaxed w-full">
                                <div
                                    class="prose prose-sm prose-indigo dark:prose-invert max-w-none prose-p:my-2 prose-headings:mb-2 prose-headings:mt-4">
                                    <div x-data
                                        x-html="DOMPurify.sanitize(marked.parse(@js($chat->message)))"></div>
                                </div>
                                <div
                                    class="absolute -bottom-6 right-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex justify-end">
                                    <button @click="copyToClipboard()"
                                        class="flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-medium transition-all duration-200"
                                        :class="copied ? 'text-green-600 bg-green-50 dark:bg-green-900/20' :
                                            'text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300'">
                                        <span x-text="copied ? 'Disalin!' : 'Copy'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            @if ($isThinking)
                <div class="flex items-start gap-4 fade-in-up">
                    <div
                        class="w-8 h-8 rounded-full bg-indigo-600 shrink-0 flex items-center justify-center text-white text-xs font-bold animate-pulse mt-1">
                        ...</div>
                    <div
                        class="bg-gray-100 dark:bg-gray-800 p-4 rounded-2xl rounded-tl-none border border-gray-200 dark:border-gray-700 flex items-center gap-1 w-fit h-10 shadow-sm">
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                            style="animation-delay: 0s"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                            style="animation-delay: 0.15s"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                            style="animation-delay: 0.3s"></span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Input Area --}}
        <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
            <div class="max-w-4xl mx-auto">
                @if ($image)
                    <div class="mb-2 inline-block relative fade-in-up">
                        <img src="{{ $image->temporaryUrl() }}"
                            class="h-16 w-auto rounded-lg border border-gray-300 shadow-sm">
                        <button wire:click="removeImage"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                <form x-data="{ userMessage: '' }"
                    @submit.prevent="if(userMessage.trim() !== '' || $wire.image) { $wire.sendMessage(userMessage); userMessage = ''; }"
                    class="relative flex items-end gap-2 bg-gray-100 dark:bg-gray-800 rounded-2xl p-2 border border-transparent focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition-all">

                    @if ($mode === 'full')
                        <div class="pb-1 pl-1">
                            <input type="file" wire:model="image" id="file-upload" class="hidden"
                                accept="image/*">
                            <label for="file-upload"
                                class="cursor-pointer text-gray-400 hover:text-indigo-600 transition-colors p-2 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </label>
                        </div>
                    @endif

                    <input type="text" x-model="userMessage" placeholder="Kirim pesan ke Amerta..."
                        class="flex-1 bg-transparent border-none text-gray-900 dark:text-white placeholder-gray-500 focus:ring-0 py-3 px-2 max-h-32 overflow-y-auto"
                        autocomplete="off" @if ($isThinking) disabled @endif>

                    <button type="submit"
                        class="p-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed mb-1 mr-1"
                        :disabled="(userMessage.trim() === '' && !$wire.image) || @js($isThinking)">
                        <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        <svg wire:loading wire:target="sendMessage" class="animate-spin w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </form>
                <p class="text-center text-xs text-gray-400 dark:text-gray-600 mt-2">
                    Amerta AI dapat membuat kesalahan. Cek informasi penting.
                </p>
            </div>
        </div>
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
            Livewire.on('chat-updated', () => setTimeout(scrollBottom, 100));
            Livewire.hook('commit', ({
                component,
                commit,
                succeed
            }) => {
                succeed(() => {
                    if (commit.calls.some(call => call.method === 'sendMessage')) {
                        setTimeout(scrollBottom, 50);
                    }
                })
            });
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #374151;
            border-radius: 20px;
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
    </style>
</div>
