<?php

namespace App\Livewire\Components;

use App\Models\KaryaSiswa;
use Livewire\Attributes\On;
use Livewire\Component;

class SidebarMenu extends Component
{
    public bool $isMobileOpen = false;

    #[On('karya-updated')]
    #[On('profile-photo-updated')]
    #[On('profile-updated')]
    public function refreshPending(): void
    {
        // Re-evaluates on next render when karya status or user profile photo updates
        if (auth()->check()) {
            auth()->setUser(auth()->user()->fresh());
        }
    }

    /**
     * Get menu items with role-based filtering
     */
    public function getMenuItems(): array
    {
        $user = auth()->user();

        // Return empty array if user is not authenticated
        if (!$user) {
            return [];
        }

        $isAdmin = in_array($user->role, ['admin', 'guru']);
        $pendingKaryaCount = $isAdmin ? KaryaSiswa::where('status', 'review')->count() : 0;

        return collect($this->allMenuItems())
            ->filter(fn($item) => in_array($user->role, $item['access']))
            ->map(function ($item) use ($user, $isAdmin, $pendingKaryaCount) {
                if (!$isAdmin && $item['key'] === 'karya_siswa') {
                    $item['label'] = 'Karya Saya';
                }

                if ($item['key'] === 'karya_verifikasi' && $pendingKaryaCount > 0) {
                    $item['has_badge'] = true;
                    $item['badge_count'] = $pendingKaryaCount;
                    $item['badge_title'] = $pendingKaryaCount . ' karya perlu verifikasi';
                }

                if ($item['key'] === 'settings' && $user->isUsingDefaultPassword()) {
                    $item['has_badge'] = true;
                    $item['badge_count'] = '!';
                    $item['badge_title'] = 'Password masih default — segera ganti password';
                }

                return $item;
            })
            ->values()
            ->toArray();
    }

    private function allMenuItems(): array
    {
        $items = [
            [
                'key' => 'dashboard_siswa',
                'label' => 'Dashboard',
                'route' => 'siswa.profil',
                'icon' => 'home',
                'access' => ['siswa'],
            ],
            [
                'key' => 'profil_publik_siswa',
                'label' => 'Profil Publik',
                'route' => 'siswa.profil-publik',
                'icon' => 'star',
                'access' => ['siswa'],
            ],
            [
                'key' => 'saluran_siswa',
                'label' => 'Saluran Saya',
                'route' => 'siswa.saluran',
                'icon' => 'link',
                'access' => ['siswa'],
            ],
            [
                'key' => 'dashboard',
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'home',
                'access' => ['admin', 'guru'],
            ],
            [
                'key' => 'landing_page_slider',
                'label' => 'Landing Page Slider',
                'route' => 'landing-page-slider.index',
                'icon' => 'image',
                'access' => ['admin'],
            ],
            [
                'key' => 'struktur_organisasi_rpl',
                'label' => 'Struktur Organisasi RPL',
                'route' => 'struktur-organisasi-rpl.index',
                'icon' => 'users',
                'access' => ['admin', 'guru'], // Changed to admin+guru access
            ],
            [
                'key' => 'prestasi_siswa',
                'label' => 'Prestasi Siswa',
                'route' => 'prestasi.index',
                'icon' => 'star',
                'access' => ['admin', 'guru'],
            ],
            [
                'key' => 'siswa_rpl',
                'label' => 'Akun Siswa RPL',
                'route' => 'siswa-rpl.index',
                'icon' => 'academic-cap',
                'access' => ['admin', 'guru'],
            ],
            [
                'key' => 'karya_verifikasi',
                'label' => 'Verifikasi Karya',
                'route' => 'karya-siswa.verifikasi',
                'icon' => 'star',
                'access' => ['admin', 'guru'],
            ],
            [
                'key' => 'karya_siswa',
                'label' => 'Karya Siswa',
                'route' => 'karya-siswa.index',
                'icon' => 'code',
                'access' => ['admin', 'guru', 'siswa'],
            ],
            [
                'key' => 'fasilitas',
                'label' => 'Fasilitas',
                'route' => 'fasilitas.index',
                'icon' => 'building',
                'access' => ['admin'],
            ],
            [
                'key' => 'unit_produksi',
                'label' => 'Unit Produksi',
                'route' => null,
                'external' => true,
                'url' => '#', // Configure in settings
                'icon' => 'shopping-bag',
                'access' => ['admin', 'guru'],
            ],
            [
                'key' => 'dokumen',
                'label' => 'Dokumen & Kurikulum',
                'route' => 'dokumen-kurikulum.index',
                'icon' => 'folder',
                'access' => ['admin'],
            ],
            [
                'key' => 'galeri',
                'label' => 'Galeri',
                'route' => 'galeri.index',
                'icon' => 'photograph',
                'access' => ['admin', 'guru'],
            ],
            [
                'key' => 'alumni',
                'label' => 'Alumni',
                'route' => 'alumni.index',
                'icon' => 'academic-cap',
                'access' => ['admin'],
            ],
            [
                'key' => 'prospek_karir',
                'label' => 'Prospek Karir',
                'route' => 'prospek-karir.index',
                'icon' => 'briefcase',
                'access' => ['admin'],
            ],
            [
                'key' => 'berita',
                'label' => 'Berita & Artikel',
                'route' => 'berita.index',
                'icon' => 'document-text',
                'access' => ['admin'],
            ],
            [
                'key' => 'mitra',
                'label' => 'Mitra & Industri',
                'route' => 'mitra.index',
                'icon' => 'office-building',
                'access' => ['admin'],
            ],
            [
                'key' => 'users',
                'label' => 'Manajemen Pengguna',
                'route' => 'users.index',
                'icon' => 'users',
                'access' => ['admin'],
            ],
            [
                'key' => 'settings',
                'label' => 'Pengaturan',
                'route' => 'settings',
                'icon' => 'cog',
                'access' => ['admin', 'guru', 'siswa'],
            ],
            [
                'key' => 'global_settings',
                'label' => 'Pengaturan Global',
                'route' => 'global-settings.index',
                'icon' => 'cog',
                'access' => ['admin'],
            ],
        ];

        $groups = [
            'dashboard_siswa' => 'Utama',
            'dashboard' => 'Utama',
            // Alur kerja karya dipisah agar badge verifikasi mudah terlihat
            'karya_verifikasi' => 'Karya',
            'karya_siswa' => 'Karya',
            // Pengelolaan konten situs publik
            'landing_page_slider' => 'Konten',
            'struktur_organisasi_rpl' => 'Konten',
            'prestasi_siswa' => 'Konten',
            'fasilitas' => 'Konten',
            'dokumen' => 'Konten',
            'galeri' => 'Konten',
            'alumni' => 'Konten',
            'prospek_karir' => 'Konten',
            'berita' => 'Konten',
            'mitra' => 'Konten',
            'unit_produksi' => 'Konten',
            // Akun & sistem
            'profil_publik_siswa' => 'Akun & Sistem',
            'saluran_siswa' => 'Akun & Sistem',
            'siswa_rpl' => 'Akun & Sistem',
            'users' => 'Akun & Sistem',
            'settings' => 'Akun & Sistem',
            'global_settings' => 'Akun & Sistem',
        ];

        return array_map(function (array $item) use ($groups) {
            $item['group'] = $groups[$item['key']] ?? 'Konten';

            return $item;
        }, $items);
    }

    /**
     * Check if menu item is active (exact match, plus .index suffix for resource routes)
     */
    public function isActive(string $routeName): bool
    {
        if (!$routeName) {
            return false;
        }

        return request()->routeIs($routeName) || request()->routeIs($routeName . '.index');
    }

    /**
     * Toggle mobile menu
     */
    public function toggleMobile()
    {
        $this->isMobileOpen = !$this->isMobileOpen;
    }

    /**
     * Close mobile menu
     */
    public function closeMobile()
    {
        $this->isMobileOpen = false;
    }

    public function render()
    {
        $currentUser = auth()->check() ? auth()->user()->fresh() : null;
        if ($currentUser) {
            auth()->setUser($currentUser);
        }

        $menuItems = $this->getMenuItems();

        return view('livewire.components.sidebar-menu', [
            'menuItems' => $menuItems,
            'menuGroups' => $this->groupMenuItems($menuItems),
            'currentUser' => $currentUser,
        ]);
    }

    /**
     * Kelompokkan menu agar sidebar tidak terlihat padat.
     * Urutan grup dipaksa lewat $groupOrder, bukan urutan alfabet.
     */
    private function groupMenuItems(array $menuItems): \Illuminate\Support\Collection
    {
        $groupOrder = ['Utama', 'Karya', 'Konten', 'Akun & Sistem'];

        return collect($menuItems)
            ->groupBy('group')
            ->sortBy(function ($items, $name) use ($groupOrder) {
                $position = array_search($name, $groupOrder, true);

                return $position === false ? 99 : $position;
            });
    }
}
