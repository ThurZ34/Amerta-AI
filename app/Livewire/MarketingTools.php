<?php

namespace App\Livewire;

use App\Services\KolosalService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MarketingTools extends Component
{
    public $selectedTool = null;

    public $productName = '';

    public $productDescription = '';

    public $promoDetails = '';

    public $targetAudience = '';

    public $tone = 'friendly'; // friendly, professional, casual, viral

    public $platform = 'instagram'; // instagram, tiktok, whatsapp, marketplace

    public $generatedContent = '';

    public $isGenerating = false;

    public $error = null;

    public $tools = [
        'caption' => [
            'title' => 'Caption Sosmed',
            'description' => 'Generate caption untuk Instagram, TikTok, Facebook',
            'icon' => '📸',
            'color' => 'pink',
        ],
        'product_desc' => [
            'title' => 'Deskripsi Produk',
            'description' => 'Deskripsi menarik untuk marketplace',
            'icon' => '🛒',
            'color' => 'orange',
        ],
        'promo' => [
            'title' => 'Teks Promosi',
            'description' => 'Kata-kata promosi yang catchy',
            'icon' => '📢',
            'color' => 'yellow',
        ],
        'wa_broadcast' => [
            'title' => 'Template WA',
            'description' => 'Template broadcast WhatsApp',
            'icon' => '💬',
            'color' => 'green',
        ],
        'hashtag' => [
            'title' => 'Hashtag Generator',
            'description' => 'Generate hashtag yang relevan',
            'icon' => '#️⃣',
            'color' => 'blue',
        ],
        'content_idea' => [
            'title' => 'Ide Konten',
            'description' => 'Ide konten mingguan untuk sosmed',
            'icon' => '💡',
            'color' => 'purple',
        ],
    ];

    public function selectTool($tool)
    {
        $this->selectedTool = $tool;
        $this->generatedContent = '';
        $this->error = null;
    }

    public function backToTools()
    {
        $this->selectedTool = null;
        $this->generatedContent = '';
        $this->error = null;
        $this->resetInputs();
    }

    public function resetInputs()
    {
        $this->productName = '';
        $this->productDescription = '';
        $this->promoDetails = '';
        $this->targetAudience = '';
        $this->tone = 'friendly';
        $this->platform = 'instagram';
    }

    public function generate(KolosalService $aiService)
    {
        $this->validate([
            'productName' => 'required|min:2',
        ], [
            'productName.required' => 'Nama produk wajib diisi',
            'productName.min' => 'Nama produk minimal 2 karakter',
        ]);

        $this->isGenerating = true;
        $this->error = null;
        $this->generatedContent = '';

        $business = Auth::user()->business;
        $prompt = $this->buildPrompt();

        try {
            $this->generatedContent = $aiService->sendChat($prompt, $business);
        } catch (\Exception $e) {
            $this->error = 'Gagal generate konten. Silakan coba lagi.';
        }

        $this->isGenerating = false;
    }

    protected function buildPrompt(): string
    {
        $toneMap = [
            'friendly' => 'ramah dan akrab',
            'professional' => 'profesional dan formal',
            'casual' => 'santai dan gaul',
            'viral' => 'catchy dan viral ala Gen-Z',
        ];

        $platformMap = [
            'instagram' => 'Instagram (caption + emoji + hashtag)',
            'tiktok' => 'TikTok (pendek, catchy, hook kuat)',
            'whatsapp' => 'WhatsApp broadcast (personal, langsung ke point)',
            'marketplace' => 'Marketplace Shopee/Tokopedia (deskriptif, SEO-friendly)',
        ];

        $toneName = $toneMap[$this->tone] ?? 'friendly';
        $platformName = $platformMap[$this->platform] ?? 'Instagram';

        $baseContext = 'Kamu adalah copywriter profesional untuk UMKM Indonesia. ';
        $baseContext .= "Produk: {$this->productName}. ";

        if ($this->productDescription) {
            $baseContext .= "Detail produk: {$this->productDescription}. ";
        }
        if ($this->targetAudience) {
            $baseContext .= "Target audiens: {$this->targetAudience}. ";
        }

        switch ($this->selectedTool) {
            case 'caption':
                return $baseContext."Buatkan 3 variasi caption untuk {$platformName}. Gaya bahasa: {$toneName}. Sertakan emoji yang relevan dan call-to-action.";

            case 'product_desc':
                return $baseContext."Buatkan deskripsi produk yang menarik untuk {$platformName}. Sertakan: fitur utama, keunggulan, dan alasan kenapa harus beli. Gaya: {$toneName}.";

            case 'promo':
                $promoInfo = $this->promoDetails ? "Detail promo: {$this->promoDetails}. " : '';

                return $baseContext.$promoInfo."Buatkan 3 variasi teks promosi yang catchy. Gaya: {$toneName}. Buat pembaca merasa FOMO dan ingin segera beli.";

            case 'wa_broadcast':
                $promoInfo = $this->promoDetails ? "Info promo: {$this->promoDetails}. " : '';

                return $baseContext.$promoInfo."Buatkan template pesan WhatsApp broadcast yang personal dan tidak terasa spam. Gaya: {$toneName}. Sertakan sapaan, info produk/promo, dan CTA.";

            case 'hashtag':
                return $baseContext.'Generate 20-30 hashtag yang relevan untuk Instagram. Campuran hashtag populer dan niche. Format: satu baris, dipisah spasi.';

            case 'content_idea':
                return $baseContext.'Buatkan 7 ide konten untuk 1 minggu (Senin-Minggu). Setiap hari berisi: jenis konten (foto/video/carousel), topik, dan brief singkat. Format sebagai daftar yang rapi.';

            default:
                return $baseContext.'Buatkan konten marketing yang menarik.';
        }
    }

    public function copyContent()
    {
        $this->dispatch('copy-to-clipboard', content: $this->generatedContent);
    }

    public function regenerate(KolosalService $aiService)
    {
        $this->generate($aiService);
    }

    public function render()
    {
        return view('livewire.marketing-tools')
            ->extends('layouts.app')
            ->section('content');
    }
}
