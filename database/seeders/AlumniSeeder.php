<?php

namespace Database\Seeders;

use App\Models\Alumni;
use Illuminate\Database\Seeder;

class AlumniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data disinkronkan dari https://skansaba.dev
     * Foto sudah tersedia di storage/app/public/alumni/
     */
    public function run(): void
    {
        Alumni::truncate();

        $admin = \App\Models\User::where('role', 'admin')->first();
        $adminId = $admin?->id ?? 1;

        $alumni = [
            // Angkatan 2026
            [
                'nama' => 'Fitrah Nur Ivanto',
                'tahun_lulus' => 2026,
                'status_alumni' => 'kerja',
                'institusi' => 'Creativemu',
                'bidang' => 'Web Developer',
                'deskripsi' => null,
                'foto' => 'alumni/6ad59accddc71_1792383692.webp',
            ],
            [
                'nama' => 'Ridhwan Nur Alif',
                'tahun_lulus' => 2026,
                'status_alumni' => 'kerja',
                'institusi' => 'PT Indo Technomedic',
                'bidang' => 'Programer',
                'deskripsi' => 'PT Indo Technomedic adalah Penyedia ekosistem sistem informasi manajemen rumah sakit (SIMRS) dan fasilitas kesehatan terpadu, berstandar regulasi nasional Kemenkes & BPJS Kesehatan.',
                'foto' => 'alumni/6ad59b723d4de_1792383858.webp',
            ],
            [
                'nama' => 'Dimas Mayang Seto',
                'tahun_lulus' => 2026,
                'status_alumni' => 'kuliah',
                'institusi' => 'Universitas Ahmad Dahlan',
                'bidang' => 'Pendidikan Guru Sekolah Dasar',
                'deskripsi' => null,
                'foto' => 'alumni/6ad59bf70f552_1792383991.webp',
            ],
            [
                'nama' => 'Hasna Sajida',
                'tahun_lulus' => 2026,
                'status_alumni' => 'kuliah',
                'institusi' => 'Universitas Muhammadiyah Yogyakarta',
                'bidang' => 'Pendidikan Bahasa Jepang',
                'deskripsi' => null,
                'foto' => 'alumni/6ad5a00d12fde_1792385037.webp',
            ],
            [
                'nama' => 'Rafi Ibnugroho Wicaksono',
                'tahun_lulus' => 2026,
                'status_alumni' => 'kuliah',
                'institusi' => 'Universitas Negeri Yogyakarta',
                'bidang' => 'S1 Teknologi Informasi',
                'deskripsi' => null,
                'foto' => 'alumni/6ad5a06f8fcba_1792385135.webp',
            ],
            [
                'nama' => 'Qisra Adena Isaura',
                'tahun_lulus' => 2026,
                'status_alumni' => 'kuliah',
                'institusi' => 'Universitas Telkom',
                'bidang' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => null,
                'foto' => 'alumni/6ad5a0be27c9b_1792385214.webp',
            ],
            [
                'nama' => 'Arifzha Reski',
                'tahun_lulus' => 2026,
                'status_alumni' => 'kuliah',
                'institusi' => 'Universitas Negeri Yogyakarta',
                'bidang' => 'Pendidikan Teknik Informatika',
                'deskripsi' => null,
                'foto' => 'alumni/6ad5a11a4f438_1792385306.webp',
            ],

            // Angkatan 2025
            [
                'nama' => 'Lathif Nur Hidayat',
                'tahun_lulus' => 2025,
                'status_alumni' => 'kerja',
                'institusi' => 'PT Indo Techno Medic',
                'bidang' => 'Junior Programmer',
                'deskripsi' => 'PT INDO TECHNO MEDIC merupakan sebuah perusahaan yang bergerak di bidang teknologi, produk yang kami hasilkan adalah software aplikasi berbasis web maupun android.',
                'foto' => 'alumni/69fd80cb1cda2_1778221259.webp',
            ],

            // Angkatan 2024
            [
                'nama' => 'Muhammad Aulia Rasyid',
                'tahun_lulus' => 2024,
                'status_alumni' => 'kerja',
                'institusi' => 'PT Arita Prima Indonesia Tbk',
                'bidang' => 'Junior Programmer',
                'deskripsi' => "Muhammad Aulia Rasyid pernah meraih juara 2 LKS Web Technologies tingkat Provinsi DIY tahun 2023. Saat ini bekerja sebagai Junior Programmer di PT Arita Prima Indonesia Tbk, sebuah perusahaan yang bergerak dalam bidang impor, distribusi, dan servis produk valve.",
                'foto' => 'alumni/69f2d65a0e67f_1777522266.webp',
            ],

            // Angkatan 2023
            [
                'nama' => 'Enricko Putra Hartono',
                'tahun_lulus' => 2023,
                'status_alumni' => 'kerja',
                'institusi' => 'PT. BINAV MAJU SEJAHTERA',
                'bidang' => 'Junior Programmer',
                'deskripsi' => 'PT. Binav Maju Sejahtera Pusat inovasi pemetaan, survei, dan produk kartografi yang terkemuka.',
                'foto' => 'alumni/69fc4f0f2dde4_1778142991.webp',
            ],
            [
                'nama' => 'Alfian Gading Saputra',
                'tahun_lulus' => 2023,
                'status_alumni' => 'kuliah',
                'institusi' => 'Universitas Teknologi Yogyakarta',
                'bidang' => 'S1 Teknik Informatika',
                'deskripsi' => 'Alfian Gading Saputra pernah menjadi perwakilan Daerah Istimewa Yogyakarta dalam ajang Lomba Kompetensi Siswa tingkat NASIONAL Bidang IT Software Solution for Business dan membawa pulang medali Perunggu tahun 2022.',
                'foto' => 'alumni/69fd8394a241f_1778221972.webp',
            ],
        ];

        foreach ($alumni as $data) {
            if ($data['foto'] && !\Storage::disk('public')->exists($data['foto'])) {
                $this->command->warn("Foto tidak ditemukan, lewati: {$data['foto']}");
                $data['foto'] = null;
            }

            Alumni::create([
                'user_id' => $adminId,
                'nama' => $data['nama'],
                'tahun_lulus' => $data['tahun_lulus'],
                'status_alumni' => $data['status_alumni'],
                'institusi' => $data['institusi'],
                'bidang' => $data['bidang'],
                'deskripsi' => $data['deskripsi'],
                'foto' => $data['foto'],
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        $this->command->info('✓ Alumni seeded successfully (' . count($alumni) . ' records) - data skansaba.dev');
    }
}
