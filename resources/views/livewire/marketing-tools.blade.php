
@section('header' , 'Marketing Tools')

<div class="min-h-screen bg-gray-50 dark:bg-gray-950 transition-colors duration-300">

    <div class="relative bg-gradient-to-br from-pink-600 via-purple-600 to-indigo-700 pb-32 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-b from-pink-600/90 to-purple-900/95 mix-blend-multiply"></div>
            <div class="absolute top-10 left-10 w-72 h-72 bg-yellow-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-pink-400/20 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 1s"></div>
        </div>

        <div class="relative max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('main_menu') }}" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <span class="text-white/50">|</span>
                <span class="text-white/70 text-sm">Marketing Tools</span>
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl md:text-5xl mb-2">
                🎨 AI Marketing Studio
            </h1>
            <p class="text-purple-100 text-lg max-w-2xl leading-relaxed">
                Buat konten marketing yang menarik dengan bantuan AI. Caption, deskripsi produk, promosi, dan lainnya!
            </p>
        </div>
    </div>

    <main class="-mt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative z-10">

        @if (!$selectedTool)
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                @foreach ($tools as $key => $tool)
                    <button wire:click="selectTool('{{ $key }}')"
                        class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden text-left">

                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-{{ $tool['color'] }}-50 dark:bg-{{ $tool['color'] }}-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-150 duration-500">
                        </div>

                        <div class="relative z-10">
                            <span class="text-4xl mb-4 block">{{ $tool['icon'] }}</span>
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-{{ $tool['color'] }}-600 dark:group-hover:text-{{ $tool['color'] }}-400 transition-colors">
                                {{ $tool['title'] }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $tool['description'] }}</p>
                        </div>

                        {{-- Arrow indicator --}}
                        <div
                            class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all">
                            <svg class="w-5 h-5 text-{{ $tool['color'] }}-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </button>
                @endforeach
            </div>

            {{-- Quick Tips --}}
            <div
                class="mt-8 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-2xl p-6 border border-indigo-100 dark:border-indigo-800">
                <h3 class="font-bold text-indigo-900 dark:text-indigo-300 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tips Marketing untuk UMKM
                </h3>
                <ul class="text-sm text-indigo-800 dark:text-indigo-200 space-y-2">
                    <li>• <strong>Konsisten posting</strong> - 3-5x seminggu untuk Instagram/TikTok</li>
                    <li>• <strong>Gunakan storytelling</strong> - Ceritakan kisah di balik produkmu</li>
                    <li>• <strong>CTA yang jelas</strong> - Selalu ajak audience untuk bertindak</li>
                </ul>
            </div>
        @else
            {{-- Tool Form --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">

                {{-- Tool Header --}}
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button wire:click="backToTools"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <span class="text-2xl">{{ $tools[$selectedTool]['icon'] }}</span>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ $tools[$selectedTool]['title'] }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $tools[$selectedTool]['description'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Input Form --}}
                    <div class="space-y-4">
                        {{-- Product Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="productName"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                placeholder="Contoh: Kopi Arabika Premium">
                            @error('productName')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Product Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi Produk (Opsional)
                            </label>
                            <textarea wire:model="productDescription" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none"
                                placeholder="Jelaskan produkmu secara singkat..."></textarea>
                        </div>

                        @if (in_array($selectedTool, ['promo', 'wa_broadcast']))
                            {{-- Promo Details --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Detail Promo (Opsional)
                                </label>
                                <input type="text" wire:model="promoDetails"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                    placeholder="Contoh: Diskon 50% khusus hari ini">
                            </div>
                        @endif

                        {{-- Target Audience --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Target Audiens (Opsional)
                            </label>
                            <input type="text" wire:model="targetAudience"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                placeholder="Contoh: Ibu rumah tangga usia 25-40 tahun">
                        </div>

                        {{-- Tone & Platform --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gaya
                                    Bahasa</label>
                                <select wire:model="tone"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="friendly">😊 Ramah</option>
                                    <option value="professional">👔 Profesional</option>
                                    <option value="casual">😎 Santai</option>
                                    <option value="viral">🔥 Viral</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Platform</label>
                                <select wire:model="platform"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="instagram">📸 Instagram</option>
                                    <option value="tiktok">🎵 TikTok</option>
                                    <option value="whatsapp">💬 WhatsApp</option>
                                    <option value="marketplace">🛒 Marketplace</option>
                                </select>
                            </div>
                        </div>

                        {{-- Generate Button --}}
                        <button wire:click="generate" wire:loading.attr="disabled" wire:target="generate"
                            class="w-full py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="generate">
                                ✨ Generate Konten
                            </span>
                            <span wire:loading wire:target="generate" class="flex items-center gap-2">
                                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Generating...
                            </span>
                        </button>
                    </div>

                    {{-- Output Panel --}}
                    <div
                        class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Hasil Generate</h3>
                            @if ($generatedContent)
                                <div class="flex items-center gap-2">
                                    <button wire:click="regenerate" wire:loading.attr="disabled"
                                        class="text-sm text-purple-600 hover:text-purple-700 dark:text-purple-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Regenerate
                                    </button>
                                    <button
                                        onclick="navigator.clipboard.writeText(document.getElementById('generated-content').innerText); alert('Copied!')"
                                        class="text-sm text-green-600 hover:text-green-700 dark:text-green-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Copy
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if ($error)
                            <div
                                class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-lg p-4">
                                {{ $error }}
                            </div>
                        @elseif ($generatedContent)
                            <div id="generated-content"
                                class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                {{ $generatedContent }}</div>
                        @elseif ($isGenerating)
                            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                                <svg class="animate-spin w-10 h-10 mb-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <p>AI sedang membuat konten...</p>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                                <svg class="w-12 h-12 mb-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <p class="text-center">Isi form di samping lalu klik <strong>Generate Konten</strong>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </main>
</div>
