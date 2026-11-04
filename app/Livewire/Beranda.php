<?php

namespace App\Livewire;

use App\Models\LandingPageSlider;
use App\Models\KaryaSiswa;
use App\Models\Berita;
use App\Models\Mitra;
use App\Models\GlobalSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Beranda - Jurusan RPL')]
class Beranda extends Component
{
    public function render()
    {
        $sliders = LandingPageSlider::where('is_active', true)
            ->orderBy('order')
            ->get();

        $mitras = Mitra::published()->get();

        $karyaSiswa = KaryaSiswa::where('status', 'published')
            ->latest('published_at')
            ->take(10)
            ->get();

        $berita = Berita::where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $globalSettings = GlobalSetting::first();

        return view('livewire.beranda', [
            'sliders' => $sliders,
            'mitras' => $mitras,
            'karyaSiswa' => $karyaSiswa,
            'berita' => $berita,
            'globalSettings' => $globalSettings,
        ]);
    }
}
