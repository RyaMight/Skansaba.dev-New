<?php

namespace App\Livewire;

use App\Models\Alumni;
use App\Models\LandingPageSlider;
use App\Models\KaryaSiswa;
use App\Models\Berita;
use App\Models\Mitra;
use App\Models\ProspekKarir;
use App\Models\GlobalSetting;
use App\Models\User;
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

        // --- Sekilas RPL: statistik ringkas ---
        $totalSiswaAktif = User::where('role', 'siswa')
            ->where('status', 'active')
            ->count();

        $totalAlumni = Alumni::published()->count();

        $totalMitra = $mitras->count();

        // --- Sekilas RPL: satu alumni sorotan (tanpa kolom is_featured) ---
        $featuredAlumni = $this->resolveFeaturedAlumni();

        return view('livewire.beranda', [
            'sliders' => $sliders,
            'mitras' => $mitras,
            'karyaSiswa' => $karyaSiswa,
            'berita' => $berita,
            'prospekKarir' => $prospekKarir,
            'mataPelajaran' => $mataPelajaran,
            'globalSettings' => $globalSettings,
            'totalSiswaAktif' => $totalSiswaAktif,
            'totalAlumni' => $totalAlumni,
            'totalMitra' => $totalMitra,
            'featuredAlumni' => $featuredAlumni,
        ]);
    }

    /**
     * Tentukan alumni sorotan untuk section "Sekilas RPL".
     *
     * Prioritas: config('sekilas.featured_alumni_ids') → fallback otomatis
     * (alumni published dengan profil paling lengkap). Tidak memakai kolom
     * is_featured supaya tidak perlu migration.
     */
    private function resolveFeaturedAlumni(): ?Alumni
    {
        $featuredIds = array_values(array_filter(
            (array) config('sekilas.featured_alumni_ids', []),
            fn ($id) => is_numeric($id)
        ));

        if (! empty($featuredIds)) {
            $alumni = Alumni::published()
                ->whereIn('id', $featuredIds)
                ->get()
                ->sortBy(fn (Alumni $item) => array_search($item->id, $featuredIds))
                ->first();

            if ($alumni) {
                return $alumni;
            }
        }

        return Alumni::published()
            ->whereNotNull('foto')
            ->whereNotNull('institusi')
            ->whereNotNull('bidang')
            ->whereNotNull('deskripsi')
            ->orderByDesc('tahun_lulus')
            ->latest('published_at')
            ->first();
    }
}
