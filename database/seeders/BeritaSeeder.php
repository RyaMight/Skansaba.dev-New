<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\User;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data disinkronkan dari https://skansaba.dev
     * Thumbnail sudah tersedia di storage/app/public/news/
     */
    public function run(): void
    {
        Berita::truncate();

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

        $berita = [
            [
                'slug' => 'smkn-1-bantul-raih-juara-3-business-plan-di-jhic-2026-dua-tim-tampil-di-ajang-final-jhic-nasional',
                'title' => 'SMKN 1 Bantul Raih Juara 3 Business Plan di JHIC 2026, Dua Tim Tampil di Ajang Final JHIC Nasional',
                'excerpt' => 'Dua tim RPL SMKN 1 Bantul tampil di final JHIC 2026: Tim Patang Puluh Patang raih Juara 3 Business Plan, tim 360 School Maps peringkat 7 Web Development.',
                'content_md' => "SKANSABA.DEV – Prestasi membanggakan kembali ditorehkan oleh siswa Konsentrasi Keahlian Rekayasa Perangkat Lunak (RPL) SMKN 1 Bantul dalam ajang Jagoan Hosting Innovation Competition (JHIC) 2026 tingkat nasional. Pada babak final yang diselenggarakan pada 17 Oktober 2026 di Performance Hall, Fakultas Bahasa, Seni, dan Budaya (FBSB), Universitas Negeri Yogyakarta (UNY), SMKN 1 Bantul berhasil mengirimkan dua tim terbaiknya untuk berkompetisi di bidang Business Plan dan Web Development.\n\nTim Patang Puluh Patang yang beranggotakan Arrassyd Nanda Fairuz, Faiz Baraka Putra, dan Reifan Putra Pratama dari kelas XII RPL 2 berhasil meraih **Juara 3 kategori Business Plan** melalui inovasi FINSPIRE, sebuah media pembelajaran finansial berbasis gamifikasi yang dirancang untuk membantu remaja memahami pengelolaan keuangan secara menarik dan interaktif. Dalam kategori ini, penilaian dilakukan oleh Wisnu Manupraba, CEO PT Javan Cipta Solusi, dan Irfan Akbar, AI Educator Creator.\n\nSementara itu, tim **360 School Maps** dari kelas XI RPL 1 yang beranggotakan Arya Saputra Nugraha, Hasan Nur Adhi Wibowo, Hilal Nabiil Mukhtar Prasetya, Rifky Dhafa Putra Triwardhana, dan Syandana Reska Josawa berhasil menempati peringkat ke-7 kategori Web Development. Tim ini mengusung inovasi website sekolah dengan konsep peta sekolah interaktif 360 derajat untuk memberikan pengalaman eksplorasi lingkungan sekolah secara digital. Kategori Web Development dinilai oleh Rizqinofa Putra Muliawan, CEO Dev Code, dan Hafidh Chaplin, Founder NDH Digital.\n\nKegiatan final JHIC 2026 turut dihadiri oleh sejumlah tokoh pendidikan dan industri teknologi, di antaranya Ir. Okky Tri Hutomo, M.IT., Direktur Maspion IT; Danton Prabawanto, Founder & CEO Beon Intermedia (Jagoan Hosting); Andy Novianto, General Manager Beon Intermedia (Jagoan Hosting); Drs. Raden Suci Rohmadi, M.IP., Kepala Dinas Pendidikan, Pemuda, dan Olahraga DIY; serta Dr. Wiwik Indriyani, S.Pd., M.Si., Kepala Bidang Pembinaan SMK Dinas Dikpora DIY.\n\nKeberhasilan kedua tim ini menjadi bukti bahwa siswa RPL SMKN 1 Bantul mampu mengembangkan ide kreatif dan inovatif, sekaligus menunjukkan kompetensi dalam bidang teknologi digital dan kewirausahaan di tingkat nasional. Prestasi ini juga merupakan hasil dari kerja keras, kolaborasi, bimbingan, serta semangat belajar yang terus dikembangkan melalui Unit Produksi SKANSABA.DEV.\n\nSemoga pencapaian ini menjadi motivasi bagi seluruh siswa untuk terus berkarya, berinovasi, dan berani berkompetisi, serta menghadirkan solusi digital yang bermanfaat bagi masyarakat.\n\nSKANSABA.DEV — Disiplin dalam Sikap, Profesional dalam Berkarya, Berintegritas dalam Berkarya, Siap Berkarya untuk Indonesia.",
                'thumbnail' => 'news/6ad575dfe1fd0_1792374239.webp',
                'author_id' => $admin->id,
                'published_at' => '2026-10-19 00:00:00',
                'status' => 'published',
                'is_highlight' => true,
                'views' => 63,
            ],
            [
                'slug' => '5-tim-smkn-1-bantul-lolos-short-bootcamp-jhic-20',
                'title' => '5 Tim SMKN 1 Bantul Lolos Short Bootcamp JHIC 2.0',
                'excerpt' => 'Dari 9 tim yang diikutkan, 5 tim SMKN 1 Bantul berhasil lolos ke tahap Short Bootcamp JHIC 2.0: 4 tim Website Development dan 1 tim Business Plan.',
                'content_md' => "SKANSABA.DEV — SMKN 1 Bantul kembali menorehkan prestasi dalam ajang **JHIC 2.0**. Setelah melalui serangkaian tahapan seleksi yang cukup ketat, 5 tim dari SMKN 1 Bantul berhasil lolos ke tahap Short Bootcamp.\n\nPada tahap awal, SMKN 1 Bantul mengirimkan 9 tim untuk mengikuti kompetisi. Dari proses seleksi tersebut, sebanyak 7 tim berhasil lolos dan berkesempatan mengikuti tahap presentasi yang dilaksanakan di Performance Hall FSBS Universitas Negeri Yogyakarta (UNY). Alhamdulillah, dari hasil seleksi presentasi tersebut, 5 tim berhasil melanjutkan perjuangan ke tahap Short Bootcamp, terdiri atas 4 tim kategori Website Development dan 1 tim kategori Business Plan.\n\nPencapaian ini menjadi hasil dari kerja keras, kreativitas, kolaborasi, dan semangat belajar para siswa SMKN 1 Bantul dalam menghadapi kompetisi berbasis teknologi dan inovasi.\n\nSaat ini, kelima tim masih terus berjuang dalam tahap pengembangan dan penyempurnaan website untuk kategori Website Development serta finalisasi pitch deck dan penguatan ide bisnis untuk kategori Business Plan.\n\nPerjalanan masih panjang, karena setelah tahap Short Bootcamp akan dipilih tim yang berhak melaju ke Grand Final yang akan diumumkan pada 9 Oktober 2026.\n\nMohon doa dan dukungan dari seluruh keluarga besar SMKN 1 Bantul agar 5 tim SKANSABA.DEV dapat terus memberikan yang terbaik dan melanjutkan perjuangan hingga tahap berikutnya.\n\nTetap semangat, terus berkarya, dan jangan berhenti belajar! 🚀",
                'thumbnail' => 'news/6abdb3326142d_1790817074.webp',
                'author_id' => $admin->id,
                'published_at' => '2026-10-01 00:00:00',
                'status' => 'published',
                'is_highlight' => false,
                'views' => 70,
            ],
            [
                'slug' => 'image-croper',
                'title' => 'SKANSABA.DEV Serahkan Website kepada SMKN 1 DLINGO, Wujud Nyata Kolaborasi dan Pembelajaran Berbasis Proyek',
                'excerpt' => 'Unit Produksi SKANSABA.DEV melaksanakan serah terima website kepada SMKN 1 Dlingo sebagai implementasi pembelajaran berbasis proyek yang nyata.',
                'content_md' => "Bantul, 19 Agustus 2026 — Unit Produksi SKANSABA.DEV, Konsentrasi Keahlian Rekayasa Perangkat Lunak (RPL) SMKN 1 Bantul, melaksanakan kegiatan **Serah Terima Website** kepada SMKN 1 Dlingo sebagai bagian dari pelaksanaan proyek pengembangan perangkat lunak berbasis kebutuhan nyata di lingkungan satuan pendidikan.\n\nKegiatan serah terima ini menjadi salah satu bentuk implementasi pembelajaran berbasis proyek (project-based learning) yang memberikan kesempatan kepada siswa RPL untuk terlibat secara langsung dalam proses pengembangan produk teknologi, mulai dari analisis kebutuhan, perancangan, pengembangan, pengujian, hingga proses serah terima kepada pengguna.\n\nWebsite yang dikembangkan oleh tim SKANSABA.DEV ditujukan untuk mendukung kebutuhan informasi dan layanan digital SMKN 1 Dlingo. Dalam proses pengerjaannya, tim RPL SMKN 1 Bantul tidak hanya berfokus pada aspek teknis pengembangan, tetapi juga memperhatikan kebutuhan pengguna, tampilan antarmuka, kemudahan penggunaan, serta pengujian fungsi website sebelum diserahkan kepada pihak sekolah.\n\nProses serah terima dilaksanakan setelah website melalui tahapan pemeriksaan dan **User Acceptance Testing (UAT)** untuk memastikan fitur-fitur yang dikembangkan telah berjalan sesuai dengan kebutuhan dan kesepakatan bersama.\n\nDalam kegiatan tersebut, dilakukan pula penyerahan sejumlah dokumen pendukung proyek, antara lain Berita Acara Serah Terima (BAST), dokumen hasil UAT/pemeriksaan website, serta daftar aset dan akses website. Dokumen tersebut menjadi bagian penting dalam memastikan proses serah terima produk berlangsung secara tertib, terdokumentasi, dan dapat dipertanggungjawabkan.\n\nKegiatan ini juga menjadi pengalaman berharga bagi siswa RPL SMKN 1 Bantul. Mereka mendapatkan pengalaman menghadapi proyek nyata dengan pengguna sebenarnya, berkomunikasi dengan klien, menyelesaikan permasalahan teknis, bekerja dalam tim, serta memahami bahwa pengembangan perangkat lunak tidak berhenti pada tahap coding, tetapi juga mencakup pengujian, dokumentasi, komunikasi, dan layanan purna proyek.\n\nSKANSABA.DEV sebagai Unit Produksi Konsentrasi Keahlian RPL SMKN 1 Bantul berharap kerja sama ini dapat menjadi langkah awal untuk membangun kolaborasi yang lebih luas dengan berbagai sekolah, instansi, maupun dunia kerja dalam pengembangan solusi digital.\n\nMelalui kegiatan ini, SKANSABA.DEV terus mendorong terwujudnya pembelajaran RPL yang nyata, produktif, profesional, dan berorientasi pada kebutuhan pengguna, sekaligus memberikan pengalaman kepada siswa untuk menghasilkan karya yang benar-benar dapat dimanfaatkan oleh masyarakat.\n\nSKANSABA.DEV — Berkarya, Berinovasi, dan Siap Menghadapi Dunia Industri.",
                'thumbnail' => 'news/6a86735892b6c_1787196248.webp',
                'author_id' => $admin->id,
                'published_at' => '2026-08-20 00:00:00',
                'status' => 'published',
                'is_highlight' => false,
                'views' => 134,
            ],
        ];

        foreach ($berita as $item) {
            // Lewati thumbnail jika file tidak ada (misal setelah fresh clone)
            if ($item['thumbnail'] && !\Storage::disk('public')->exists($item['thumbnail'])) {
                $this->command->warn("Thumbnail tidak ditemukan: {$item['thumbnail']}");
                $item['thumbnail'] = null;
            }

            Berita::create($item);
        }

        $this->command->info('✓ Berita seeded successfully (' . count($berita) . ' records) - data skansaba.dev');
    }
}
