<?php

namespace Database\Seeders;

use App\Models\Mitra;
use Illuminate\Database\Seeder;

class MitraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data disinkronkan dari https://skansaba.dev
     * Logo sudah tersedia di storage/app/public/mitra/
     */
    public function run(): void
    {
        Mitra::truncate();

        $mitras = [
            [
                'nama_mitra' => 'PT Dicoding Akademi Indonesia',
                'logo' => 'mitra/6ad0410c0bf2c_1792033036.webp',
                'website' => 'https://www.dicoding.com/',
                'order' => 1,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'Maspion IT',
                'logo' => 'mitra/6ad567c8dfc5b_1792370632.webp',
                'website' => 'https://maspionsquare.co.id/',
                'order' => 2,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'PT FARMA GLOBAL TEKNOLOGI',
                'logo' => 'mitra/696dee4fb7386_1768812111.webp',
                'website' => 'https://farmagitechs.co.id/',
                'order' => 3,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'PT Techno Medic',
                'logo' => 'mitra/696df135db504_1768812853.webp',
                'website' => 'https://www.technomedic.id/web/',
                'order' => 4,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'PT Gamatechno Indonesia',
                'logo' => 'mitra/696df03735fe6_1768812599.webp',
                'website' => 'https://www.gamatechno.com/',
                'order' => 5,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'PT Global Intermedia',
                'logo' => 'mitra/696df09349f60_1768812691.webp',
                'website' => 'https://www.gi.co.id/',
                'order' => 6,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'Lembaga Sistem Informasi UMY',
                'logo' => 'mitra/69f2d808448e9_1777522696.webp',
                'website' => 'https://ddi.umy.ac.id/',
                'order' => 7,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'PT BPR BANK BANTUL',
                'logo' => 'mitra/69f2d88b2a3a3_1777522827.webp',
                'website' => 'https://bankbantul.co.id/',
                'order' => 8,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'CV. Bursa Multimedia Group',
                'logo' => 'mitra/69fd876a5e1c7_1778222954.webp',
                'website' => 'https://bursamultimediagroup.web.indotrading.com/',
                'order' => 9,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'CV Sinergi Telematika',
                'logo' => 'mitra/696df0fd90044_1768812797.webp',
                'website' => null,
                'order' => 10,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'PT. Global Prima Utama',
                'logo' => 'mitra/696df06aaffb5_1768812650.webp',
                'website' => null,
                'order' => 11,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'Creativemu ID',
                'logo' => 'mitra/69fd8a96542a4_1778223766.webp',
                'website' => 'https://creativemuid.blogspot.com/',
                'order' => 12,
                'status' => 'published',
            ],
            [
                'nama_mitra' => 'SMKN 1 Dlingo',
                'logo' => 'mitra/6ad565af3c0f5_1792370095.webp',
                'website' => 'https://smkn1dlingo.sch.id/',
                'order' => 13,
                'status' => 'published',
            ],
        ];

        foreach ($mitras as $data) {
            // Lewati jika file logo tidak ada (misal setelah fresh clone)
            if (!\Storage::disk('public')->exists($data['logo'])) {
                $this->command->warn("Logo tidak ditemukan, lewati: {$data['logo']}");
                continue;
            }

            Mitra::create($data);
        }

        $this->command->info('✓ Mitra seeded successfully (' . count($mitras) . ' records) - data skansaba.dev');
    }
}
