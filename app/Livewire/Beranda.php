<?php

namespace App\Livewire;

use App\Models\LandingPageSlider;
use App\Models\KaryaSiswa;
use App\Models\Berita;
use App\Models\Mitra;
use App\Models\ProspekKarir;
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
            ->take(8)
            ->get();

        $berita = Berita::where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $prospekKarir = ProspekKarir::active()->ordered()->get();

        $mataPelajaran = [
            [
                'judul' => 'Pemrograman Web & Mobile',
                'deskripsi' => 'Mengembangkan aplikasi website dan mobile dengan teknologi modern',
                'ikon' => 'code',
            ],
            [
                'judul' => 'Database Management',
                'deskripsi' => 'Mengelola dan mengoptimalkan sistem database untuk aplikasi enterprise',
                'ikon' => 'database',
            ],
            [
                'judul' => 'UI/UX Design',
                'deskripsi' => 'Mendesain antarmuka pengguna yang menarik dan mudah digunakan',
                'ikon' => 'palette',
            ],
            [
                'judul' => 'Keamanan Siber',
                'deskripsi' => 'Melindungi sistem dan data dari ancaman keamanan digital',
                'ikon' => 'shield',
            ],
            [
                'judul' => 'Cloud Computing',
                'deskripsi' => 'Mengembangkan solusi berbasis cloud untuk skala enterprise',
                'ikon' => 'cloud',
            ],
            [
                'judul' => 'Kecerdasan Buatan',
                'deskripsi' => 'Membangun sistem AI dan machine learning untuk aplikasi cerdas',
                'ikon' => 'cpu',
            ],
        ];

        $globalSettings = GlobalSetting::first();

        return view('livewire.beranda', [
            'sliders' => $sliders,
            'mitras' => $mitras,
            'karyaSiswa' => $karyaSiswa,
            'berita' => $berita,
            'prospekKarir' => $prospekKarir,
            'mataPelajaran' => $mataPelajaran,
            'globalSettings' => $globalSettings,
        ]);
    }
}
