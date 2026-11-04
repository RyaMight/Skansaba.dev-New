<?php

namespace Database\Seeders;

use App\Models\LandingPageSlider;
use Illuminate\Database\Seeder;

class LandingPageSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data disinkronkan dari https://skansaba.dev
     * Gambar sudah tersedia di storage/app/public/landing-sliders/
     */
    public function run(): void
    {
        // Truncate table for idempotency
        LandingPageSlider::truncate();

        $sliders = [
            [
                'title' => 'Guru Rekayasa Perangkat Lunak',
                'subtitle' => 'Formasi Guru RPL 2026',
                'link' => null,
                'order' => 0,
                'is_active' => true,
                'image' => 'landing-sliders/69e5ca40b5ce7_1776667200.webp',
            ],
            [
                'title' => 'ExplroAItion Mini Hackathon Komdigi x Jagoan Hosting',
                'subtitle' => null,
                'link' => null,
                'order' => 1,
                'is_active' => true,
                'image' => 'landing-sliders/69e1d276aaf44_1776407158.webp',
            ],
            [
                'title' => 'GRAND FINAL JHIC 2.O 2026',
                'subtitle' => null,
                'link' => null,
                'order' => 2,
                'is_active' => true,
                'image' => 'landing-sliders/6ad576bb9285b_1792374459.webp',
            ],
            [
                'title' => 'Grand Final JHIC 2025',
                'subtitle' => null,
                'link' => null,
                'order' => 3,
                'is_active' => true,
                'image' => 'landing-sliders/69e1d2ca9a59a_1776407242.webp',
            ],
            [
                'title' => 'Sertifikasi Industri Dicoding 2026',
                'subtitle' => null,
                'link' => null,
                'order' => 4,
                'is_active' => true,
                'image' => 'landing-sliders/6adeae6027357_1792978528.webp',
            ],
            [
                'title' => 'SKANSABA.DEV Serahkan Website kepada SMKN 1 DLINGO',
                'subtitle' => 'Wujud Nyata Kolaborasi dan Pembelajaran Berbasis Proyek',
                'link' => null,
                'order' => 5,
                'is_active' => true,
                'image' => 'landing-sliders/6a8564cd56da5_1787126989.webp',
            ],
            [
                'title' => 'Sertifikasi Industri Maspion IT',
                'subtitle' => 'bersama Bapak Ir. Okky Tri Hutomo, M.IT',
                'link' => null,
                'order' => 6,
                'is_active' => true,
                'image' => 'landing-sliders/69f2ff190ac28_1777532697.webp',
            ],
            [
                'title' => 'Uji Kompetensi Keahlian 2026',
                'subtitle' => null,
                'link' => null,
                'order' => 7,
                'is_active' => true,
                'image' => 'landing-sliders/69f2ff715c691_1777532785.webp',
            ],
        ];

        foreach ($sliders as $slider) {
            // Lewati slider tanpa file gambar (misal setelah fresh clone)
            if ($slider['image'] && !\Storage::disk('public')->exists($slider['image'])) {
                $this->command->warn("Gambar tidak ditemukan, lewati: {$slider['image']}");
                continue;
            }

            LandingPageSlider::create($slider);
        }

        $this->command->info('✓ Landing page sliders seeded successfully (8 records) - data skansaba.dev');
    }
}
