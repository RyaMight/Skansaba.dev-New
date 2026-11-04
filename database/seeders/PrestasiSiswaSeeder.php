<?php

namespace Database\Seeders;

use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class PrestasiSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 8 prestasi siswa RPL (data dari Eduroute) + foto achievements
     * yang tersedia di storage/app/public/prestasi/.
     */
    public function run(): void
    {
        Prestasi::truncate();

        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $admin = User::firstOrCreate(
                ['email' => 'admin@rpl.sch.id'],
                [
                    'name' => 'Administrator',
                    'password' => \Hash::make('admin123'),
                    'role' => 'admin',
                    'status' => 'active',
                ]
            );
        }

        $prestasi = [
            [
                'judul' => 'Juara 3 Business Plan JHIC 2026',
                'deskripsi' => "Dua tim RPL SMKN 1 Bantul tampil di final JHIC 2026. Tim Patang Puluh Patang meraih Juara 3 Business Plan dan dua tim tampil di tahap Short Bootcamp JHIC 2.0.",
                'jenis' => 'kompetisi',
                'tingkat' => 'nasional',
                'penyelenggara' => 'Jogja Human Capital Initiative (JHIC)',
                'nama_siswa' => 'Tim Patang Puluh Patang',
                'kelas' => 'XII RPL',
                'tanggal_prestasi' => '2026-10-19',
                'tahun' => 2026,
                'gambar' => 'prestasi/jhic-2025.webp',
            ],
            [
                'judul' => '5 Tim Lolos Short Bootcamp JHIC 2.0',
                'deskripsi' => "Dari 9 tim yang dikutkan, 5 tim SMKN 1 Bantul berhasil lolos ke tahap Short Bootcamp JHIC 2.0 dan mengikuti intensitas pendampingan bisnis digital.",
                'jenis' => 'kompetisi',
                'tingkat' => 'nasional',
                'penyelenggara' => 'Jogja Human Capital Initiative (JHIC)',
                'nama_siswa' => '5 Tim RPL SMKN 1 Bantul',
                'kelas' => 'XI-XII RPL',
                'tanggal_prestasi' => '2026-10-01',
                'tahun' => 2026,
                'gambar' => 'prestasi/mini-hackathon-2026.webp',
            ],
            [
                'judul' => 'Serah Terima Website ke SMKN 1 Dlingo',
                'deskripsi' => "Unit Produksi SKANSABA.DEV melaksanakan serah terima website kepada SMKN 1 Dlingo — wujud nyata pembelajaran proyek berbasis klien eksternal.",
                'jenis' => 'non-akademik',
                'tingkat' => 'kota',
                'penyelenggara' => 'SMKN 1 Dlingo',
                'nama_siswa' => 'Tim Unit Produksi SKANSABA.DEV',
                'kelas' => 'XI RPL',
                'tanggal_prestasi' => '2026-08-20',
                'tahun' => 2026,
                'gambar' => 'prestasi/iias-proyek-ai-2025.webp',
            ],
            [
                'judul' => 'Juara LKS Provinsi DIY Web Technologies',
                'deskripsi' => "Perwakilan RPL SMKN 1 Bantul meraih posisi terdepan di LKS tingkat Provinsi DIY bidang Web Technologies dan melaju ke pembinaan nasional.",
                'jenis' => 'kompetisi',
                'tingkat' => 'provinsi',
                'penyelenggara' => 'LKS Provinsi DIY',
                'nama_siswa' => 'Perwakilan Web Technologies',
                'kelas' => 'XII RPL',
                'tanggal_prestasi' => '2026-05-15',
                'tahun' => 2026,
                'gambar' => 'prestasi/lks-provinsi-2026.webp',
            ],
            [
                'judul' => 'Finalis Logic & Code Competition AMIKOM',
                'deskripsi' => "Tim RPL lolos ke babak final Logic & Code Competition di Universitas Amikom Yogyakarta, bersaing dengan SMK se-Indonesia bagian barat.",
                'jenis' => 'kompetisi',
                'tingkat' => 'nasional',
                'penyelenggara' => 'Universitas Amikom Yogyakarta',
                'nama_siswa' => 'Tim Logic & Code',
                'kelas' => 'XI RPL',
                'tanggal_prestasi' => '2026-04-10',
                'tahun' => 2026,
                'gambar' => 'prestasi/logic-code-amikom-2026.webp',
            ],
            [
                'judul' => 'Sertifikasi Cyber Security Komdigi',
                'deskripsi' => "Siswa RPL menyelesaikan sertifikasi Cyber Security dari Kominfo (kini Komdigi) dengan kompetensi dasar ethical hacking dan network hardening.",
                'jenis' => 'sertifikasi',
                'tingkat' => 'nasional',
                'penyelenggara' => 'Komdigi RI',
                'nama_siswa' => 'Kelas XII RPL',
                'kelas' => 'XII RPL',
                'tanggal_prestasi' => '2026-03-20',
                'tahun' => 2026,
                'gambar' => 'prestasi/cyber-security-komdigi-2026.webp',
            ],
            [
                'judul' => 'Juara 1 Bulu Tangkis O2SN Tingkat Kota',
                'deskripsi' => "Atlet RPL membawa nama sekolah sebagai Juara 1 bulu tangkis pada Olimpiade Siswa Nasional Olahraga (O2SN) tingkat Kota Bantul.",
                'jenis' => 'non-akademik',
                'tingkat' => 'kota',
                'penyelenggara' => 'O2SN Kota Bantul',
                'nama_siswa' => 'Atlet Bulu Tangkis RPL',
                'kelas' => 'X RPL',
                'tanggal_prestasi' => '2026-02-14',
                'tahun' => 2026,
                'gambar' => 'prestasi/bulutangkis-2026.webp',
            ],
            [
                'judul' => 'Medali Emas Taekwondo UGM Cup',
                'deskripsi' => "Peraih medali emas taekwondo kelas poomsae pada UGM Cup 2026, kompetisi taekwondo antar pelajar tingkat nasional.",
                'jenis' => 'non-akademik',
                'tingkat' => 'nasional',
                'penyelenggara' => 'UGM Cup 2026',
                'nama_siswa' => 'Atlet Taekwondo RPL',
                'kelas' => 'XI RPL',
                'tanggal_prestasi' => '2026-01-25',
                'tahun' => 2026,
                'gambar' => 'prestasi/taekwondo-ugm-2026.webp',
            ],
        ];

        foreach ($prestasi as $data) {
            if ($data['gambar'] && !\Storage::disk('public')->exists($data['gambar'])) {
                $this->command->warn("Gambar tidak ditemukan: {$data['gambar']}");
                $data['gambar'] = null;
            }

            Prestasi::create([
                'user_id' => $admin->id,
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'jenis' => $data['jenis'],
                'tingkat' => $data['tingkat'],
                'penyelenggara' => $data['penyelenggara'],
                'nama_siswa' => $data['nama_siswa'],
                'kelas' => $data['kelas'],
                'tanggal_prestasi' => $data['tanggal_prestasi'],
                'tahun' => $data['tahun'],
                'gambar' => $data['gambar'],
                'status' => 'published',
                'published_at' => $data['tanggal_prestasi'],
            ]);
        }

        $this->command->info('✓ Prestasi Siswa seeded successfully (' . count($prestasi) . ' records)');
    }
}
