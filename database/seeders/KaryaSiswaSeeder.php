<?php

namespace Database\Seeders;

use App\Models\KaryaSiswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class KaryaSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Dummy data untuk section "Karya Siswa" di landing page.
     * Poster SVG tersedia di storage/app/public/karya-siswa/posters/
     * Catatan: skansaba.dev belum punya karya published - data ini dummy,
     * hapus/replace saat ada data asli dari admin.
     */
    public function run(): void
    {
        KaryaSiswa::truncate();

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

        $karya = [
            [
                'judul' => 'Kelas Angkasa',
                'deskripsi' => "Platform e-learning interaktif untuk belajar pemrograman secara mandiri.\n\nFitur unggulan:\n- Video pembelajaran terstruktur\n- Kuis dan sertifikat otomatis\n- Forum diskusi antar siswa\n- Progress tracking per materi",
                'kategori' => 'Web Development',
                'teknologi' => ['Laravel', 'Tailwind CSS', 'MySQL'],
                'nama_siswa' => 'Arya Saputra Nugraha',
                'kelas' => 'XI RPL 1',
                'tahun' => 2026,
                'gambar' => 'karya-siswa/posters/kelas-angkasa.svg',
                'url_demo' => null,
                'url_repo' => null,
            ],
            [
                'judul' => 'SiKasir',
                'deskripsi' => "Aplikasi kasir modern untuk UMKM dengan laporan penjualan real-time.\n\nFitur unggulan:\n- Scan barcode produk\n- Struk digital via WhatsApp\n- Laporan harian & bulanan\n- Manajemen stok otomatis",
                'kategori' => 'Web Development',
                'teknologi' => ['Laravel', 'Livewire', 'MySQL'],
                'nama_siswa' => 'Faiz Baraka Putra',
                'kelas' => 'XII RPL 2',
                'tahun' => 2026,
                'gambar' => 'karya-siswa/posters/sikasir.svg',
                'url_demo' => null,
                'url_repo' => null,
            ],
            [
                'judul' => 'Solaria App',
                'deskripsi' => "Aplikasi pemesanan makanan online untuk kantin sekolah.\n\nFitur unggulan:\n- Pre-order makanan tanpa antre\n- Notifikasi pesanan siap\n- Dompet digital siswa\n- Rekomendasi menu favorit",
                'kategori' => 'Mobile Development',
                'teknologi' => ['Flutter', 'Firebase'],
                'nama_siswa' => 'Hilal Nabiil Mukhtar Prasetya',
                'kelas' => 'XI RPL 1',
                'tahun' => 2026,
                'gambar' => 'karya-siswa/posters/solaria-app.svg',
                'url_demo' => null,
                'url_repo' => null,
            ],
            [
                'judul' => 'MediMate',
                'deskripsi' => "Platform konsultasi kesehatan digital untuk remaja.\n\nFitur unggulan:\n- Chat dengan konselor\n- Cek kebutuhan kalori & gizi\n- Reminder jadwal minum obat\n- Artikel kesehatan terkurasi",
                'kategori' => 'Web Development',
                'teknologi' => ['Laravel', 'Alpine.js', 'PostgreSQL'],
                'nama_siswa' => 'Syandana Reska Josawa',
                'kelas' => 'XI RPL 1',
                'tahun' => 2026,
                'gambar' => 'karya-siswa/posters/medimate.svg',
                'url_demo' => null,
                'url_repo' => null,
            ],
            [
                'judul' => 'Campus Map 360',
                'deskripsi' => "Tur virtual kampus dengan peta interaktif 360 derajat.\n\nFitur unggulan:\n- Panorama 360 derajat\n- Navigasi antar gedung\n- Info point menarik\n- Embed ke website sekolah",
                'kategori' => 'Web Development',
                'teknologi' => ['Three.js', 'JavaScript', 'PHP'],
                'nama_siswa' => 'Rifky Dhafa Putra Triwardhana',
                'kelas' => 'XI RPL 1',
                'tahun' => 2026,
                'gambar' => 'karya-siswa/posters/campus-map-360.svg',
                'url_demo' => null,
                'url_repo' => null,
            ],
            [
                'judul' => 'FINSPIRE',
                'deskripsi' => "Media pembelajaran finansial berbasis gamifikasi untuk remaja.\n\nJuara 3 Business Plan JHIC 2026. Fitur unggulan:\n- Simulasi investasi virtual\n- Misi harian & reward XP\n- Leaderboard nasional\n- Materi literasi keuangan",
                'kategori' => 'Game & Simulasi',
                'teknologi' => ['Laravel', 'Vue.js', 'MySQL'],
                'nama_siswa' => 'Arrassyd Nanda Fairuz',
                'kelas' => 'XII RPL 2',
                'tahun' => 2026,
                'gambar' => 'karya-siswa/posters/finspire.svg',
                'url_demo' => null,
                'url_repo' => null,
            ],
            [
                'judul' => 'Trippa',
                'deskripsi' => "Perencana perjalanan wisata Yogyakarta berbasis AI.\n\nFitur unggulan:\n- Generate itinerary otomatis\n- Estimasi biaya perjalanan\n- Rekomendasi kuliner lokal\n- Sharing itinerary ke teman",
                'kategori' => 'Mobile Development',
                'teknologi' => ['React Native', 'Laravel API'],
                'nama_siswa' => 'Hasan Nur Adhi Wibowo',
                'kelas' => 'XI RPL 1',
                'tahun' => 2026,
                'gambar' => 'karya-siswa/posters/trippa.svg',
                'url_demo' => null,
                'url_repo' => null,
            ],
            [
                'judul' => 'Libra.net',
                'deskripsi' => "Sistem perpustakaan digital sekolah dengan katalog online.\n\nFitur unggulan:\n- Peminjaman & reservasi online\n- Rekomendasi buku\n- Notifikasi jatuh tempo\n- Statistik baca siswa",
                'kategori' => 'Web Development',
                'teknologi' => ['CodeIgniter', 'Bootstrap', 'MySQL'],
                'nama_siswa' => 'Reifan Putra Pratama',
                'kelas' => 'XII RPL 2',
                'tahun' => 2026,
                'gambar' => 'karya-siswa/posters/libra-net.svg',
                'url_demo' => null,
                'url_repo' => null,
            ],
        ];

        foreach ($karya as $data) {
            if ($data['gambar'] && !\Storage::disk('public')->exists($data['gambar'])) {
                $this->command->warn("Gambar tidak ditemukan, lewati: {$data['gambar']}");
                continue;
            }

            KaryaSiswa::create([
                'user_id' => $admin->id,
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'kategori' => $data['kategori'],
                'teknologi' => $data['teknologi'],
                'nama_siswa' => $data['nama_siswa'],
                'kelas' => $data['kelas'],
                'tahun' => $data['tahun'],
                'gambar' => $data['gambar'],
                'url_demo' => $data['url_demo'],
                'url_repo' => $data['url_repo'],
                'status' => 'published',
                'published_at' => now()->subDays(count($karya)),
            ]);
        }

        $this->command->info('✓ Karya Siswa seeded successfully (' . count($karya) . ' dummy records)');
    }
}
