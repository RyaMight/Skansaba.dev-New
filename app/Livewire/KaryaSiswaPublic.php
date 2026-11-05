<?php

namespace App\Livewire;

use App\Models\KaryaSiswa;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class KaryaSiswaPublic extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterKategori = '';
    public string $filterProfil = '';
    public string $activeTab = 'karya';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterKategori' => ['except' => ''],
        'filterProfil' => ['except' => ''],
        'activeTab' => ['except' => 'karya', 'as' => 'tab'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterKategori(): void
    {
        $this->resetPage();
    }

    public function updatingFilterProfil(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['karya', 'profil'], true)) {
            $this->activeTab = $tab;
        }
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterKategori = '';
        $this->filterProfil = '';
        $this->resetPage();
    }

    /**
     * Data dummy karya siswa yang dipertahankan sebagai katalog pelengkap
     * di samping karya siswa asli yang dipublikasikan dari database.
     */
    protected function getBaseDummyKaryas(): array
    {
        return [
            [
                'judul' => 'Sistem Informasi Perpustakaan',
                'siswa' => 'Alief Saktiawan Prananda',
                'kelas' => 'XI RPL 1',
                'kategori' => 'Web Development',
                'teknologi' => ['Laravel', 'Tailwind CSS', 'MySQL'],
                'deskripsi' => 'Sistem manajemen sirkulasi buku, katalog digital, dan peminjaman terintegrasi kartu anggota sekolah.',
                'tahun' => 2026,
                'icon' => 'book',
            ],
            [
                'judul' => 'Aplikasi Kasir UMKM',
                'siswa' => 'Azka Chirza Saifani Chasan',
                'kelas' => 'XI RPL 1',
                'kategori' => 'Desktop & POS',
                'teknologi' => ['C#', '.NET', 'SQL Server'],
                'deskripsi' => 'Aplikasi Point of Sale (POS) dengan fitur cetak struk termal, manajemen inventaris, dan rekap laba rugi harian.',
                'tahun' => 2026,
                'icon' => 'receipt',
            ],
            [
                'judul' => 'Game Edukasi Matematika',
                'siswa' => 'Dafa Fadhila Dzikri',
                'kelas' => 'XI RPL 1',
                'kategori' => 'Game & Simulasi',
                'teknologi' => ['Unity', 'C#', '2D Physics'],
                'deskripsi' => 'Permainan interaktif berbasis petualangan untuk melatih logika berhitung dan pemecahan soal aritmatika siswa.',
                'tahun' => 2026,
                'icon' => 'gamepad',
            ],
            [
                'judul' => 'Portal Akademik & Profil Sekolah',
                'siswa' => 'Dafa Muhammad Sulistyo',
                'kelas' => 'XI RPL 1',
                'kategori' => 'Web Development',
                'teknologi' => ['Laravel', 'Livewire', 'Alpine.js'],
                'deskripsi' => 'Portal informasi akademik, pengumuman kegiatan, dan direktori prestasi siswa berbasis CMS modern.',
                'tahun' => 2026,
                'icon' => 'building',
            ],
            [
                'judul' => 'Dashboard IoT Smart Home',
                'siswa' => 'Dini Nur Meilia',
                'kelas' => 'XI RPL 1',
                'kategori' => 'IoT & Embedded',
                'teknologi' => ['ESP32', 'MQTT', 'Vue.js'],
                'deskripsi' => 'Panel pemantauan suhu ruangan, kelembapan, dan kendali sakelar lampu jarak jauh secara real-time.',
                'tahun' => 2026,
                'icon' => 'cpu',
            ],
            [
                'judul' => 'Asisten Chatbot Panduan Siswa',
                'siswa' => 'Fahrezy Ramadinansya',
                'kelas' => 'XI RPL 1',
                'kategori' => 'AI & Otomasi',
                'teknologi' => ['Python', 'FastAPI', 'NLP'],
                'deskripsi' => 'Layanan tanya-jawab otomatis seputar jadwal pelajaran, tata tertib, dan informasi administrasi sekolah.',
                'tahun' => 2026,
                'icon' => 'sparkles',
            ],
            [
                'judul' => 'Aplikasi Presensi QR Geolocation',
                'siswa' => 'Fairuz Adam Rizqulah',
                'kelas' => 'XI RPL 1',
                'kategori' => 'Mobile Development',
                'teknologi' => ['Flutter', 'Dart', 'REST API'],
                'deskripsi' => 'Aplikasi presensi kehadiran siswa menggunakan pemindaian kode QR dinamis dan validasi titik koordinat.',
                'tahun' => 2026,
                'icon' => 'qrcode',
            ],
            [
                'judul' => 'Sistem Monitoring Parkir Cerdas',
                'siswa' => 'Faiz Ibnu Majid',
                'kelas' => 'XI RPL 1',
                'kategori' => 'IoT & Embedded',
                'teknologi' => ['Arduino', 'Node.js', 'WebSocket'],
                'deskripsi' => 'Deteksi ketersediaan slot parkir kendaraan di lingkungan sekolah menggunakan sensor ultrasonik.',
                'tahun' => 2026,
                'icon' => 'sensor',
            ],
            [
                'judul' => 'Marketplace Koperasi & Produk Siswa',
                'siswa' => 'Fandy Miftakhul Zanuar',
                'kelas' => 'XI RPL 1',
                'kategori' => 'Web Development',
                'teknologi' => ['Laravel', 'Tailwind CSS', 'Midtrans'],
                'deskripsi' => 'Platform jual-beli karya kreatif dan produk kewirausahaan siswa antar jurusan di SMKN 1 Bantul.',
                'tahun' => 2026,
                'icon' => 'cart',
            ],
            [
                'judul' => 'Deteksi Penyakit Daun Tanaman',
                'siswa' => 'Kalis Lintang Pradipta',
                'kelas' => 'XI RPL 1',
                'kategori' => 'AI & Otomasi',
                'teknologi' => ['Python', 'TensorFlow', 'React'],
                'deskripsi' => 'Klasifikasi citra daun tanaman untuk mendeteksi gejala penyakit dini beserta rekomendasi penanganan.',
                'tahun' => 2026,
                'icon' => 'leaf',
            ],
            [
                'judul' => 'Portal Berita & Majalah Dinding Digital',
                'siswa' => 'Khansa Maysun Zuhayr',
                'kelas' => 'XI RPL 1',
                'kategori' => 'Web Development',
                'teknologi' => ['Next.js', 'TypeScript', 'PostgreSQL'],
                'deskripsi' => 'Media publikasi artikel jurnalistik, liputan kegiatan, dan karya tulis siswa dengan editor markdown.',
                'tahun' => 2026,
                'icon' => 'newspaper',
            ],
            [
                'judul' => 'Media Pembelajaran Algoritma Interaktif',
                'siswa' => 'Kusumastuti Endah Retnadi',
                'kelas' => 'XI RPL 1',
                'kategori' => 'Desktop & POS',
                'teknologi' => ['Electron', 'JavaScript', 'Canvas API'],
                'deskripsi' => 'Visualisasi langkah demi langkah algoritma pengurutan (sorting) dan struktur data untuk siswa kelas X.',
                'tahun' => 2026,
                'icon' => 'code',
            ],
        ];
    }

    public function render()
    {
        $searchTerm = trim($this->search);

        // 1. Karya Siswa Asli (Published di Database — karya terbaru yang di-publish muncul paling atas)
        $karyas = KaryaSiswa::query()
            ->with(['user' => fn ($q) => $q->select('id', 'name', 'username', 'nisn', 'kelas', 'photo', 'keahlian', 'role', 'status')])
            ->where('status', 'published')
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $like = '%' . $searchTerm . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('judul', 'like', $like)
                        ->orWhere('nama_siswa', 'like', $like)
                        ->orWhere('deskripsi', 'like', $like)
                        ->orWhere('deskripsi_singkat', 'like', $like)
                        ->orWhere('kategori', 'like', $like)
                        ->orWhere('teknologi', 'like', $like)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like)->orWhere('username', 'like', $like));
                });
            })
            ->when($this->filterKategori !== '', fn ($q) => $q->where('kategori', $this->filterKategori))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();

        // 2. Karya Dummy Pelengkap (dipertahankan, difilter juga dengan search & kategori, tanpa duplikasi judul)
        $publishedTitles = $karyas->pluck('judul')->map(fn ($t) => mb_strtolower(trim($t)))->all();

        $dummyKaryas = collect($this->getBaseDummyKaryas())
            ->reject(fn (array $item) => in_array(mb_strtolower(trim($item['judul'])), $publishedTitles, true))
            ->when($searchTerm !== '', function ($collection) use ($searchTerm) {
                $needle = mb_strtolower($searchTerm);
                return $collection->filter(function (array $item) use ($needle) {
                    $haystack = mb_strtolower(implode(' ', [
                        $item['judul'],
                        $item['siswa'],
                        $item['kategori'],
                        $item['deskripsi'],
                        implode(' ', $item['teknologi'] ?? []),
                    ]));
                    return str_contains($haystack, $needle);
                });
            })
            ->when($this->filterKategori !== '', function ($collection) {
                return $collection->where('kategori', $this->filterKategori);
            })
            ->values();

        // Kategori unik gabungan (Data Asli + Dummy) untuk filter
        $dbCategories = KaryaSiswa::where('status', 'published')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori');

        $dummyCategories = collect($this->getBaseDummyKaryas())->pluck('kategori');

        $categories = $dbCategories
            ->merge($dummyCategories)
            ->unique()
            ->sort()
            ->values();

        // 3. Data Asli 35 Akun Siswa RPL (untuk Tab "Profil Siswa" & relasi nama_siswa)
        $allActiveSiswas = User::query()
            ->where('role', 'siswa')
            ->where('status', 'active')
            ->withCount([
                'karyaSiswas as published_karya_count' => fn ($q) => $q->where('status', 'published'),
            ])
            ->get(['id', 'name', 'username', 'nisn', 'kelas', 'photo', 'keahlian', 'role', 'status']);

        $siswaLookup = $allActiveSiswas->keyBy(fn (User $u) => mb_strtolower(trim($u->name)));

        $totalSiswasCount = $allActiveSiswas->count();
        $siswaWithKaryaCount = $allActiveSiswas->where('published_karya_count', '>', 0)->count();

        $availableSkills = $allActiveSiswas
            ->flatMap(fn (User $u) => $u->keahlian_list)
            ->unique()
            ->sort()
            ->values();

        $siswas = User::query()
            ->where('role', 'siswa')
            ->where('status', 'active')
            ->withCount([
                'karyaSiswas as published_karya_count' => fn ($q) => $q->where('status', 'published'),
            ])
            ->with([
                'karyaSiswas' => fn ($q) => $q->where('status', 'published')
                    ->select('id', 'user_id', 'judul', 'kategori', 'teknologi', 'published_at')
                    ->latest('published_at'),
            ])
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $like = '%' . $searchTerm . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('username', 'like', $like)
                        ->orWhere('nisn', 'like', $like)
                        ->orWhere('keahlian', 'like', $like)
                        ->orWhere('bio', 'like', $like)
                        ->orWhere('kelas', 'like', $like)
                        ->orWhereHas('karyaSiswas', fn ($k) => $k->where('status', 'published')->where('judul', 'like', $like));
                });
            })
            ->when($this->filterProfil !== '', function ($query) {
                if ($this->filterProfil === 'with_karya') {
                    $query->whereHas('karyaSiswas', fn ($q) => $q->where('status', 'published'));
                } else {
                    $query->where('keahlian', 'like', '%' . $this->filterProfil . '%');
                }
            })
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.karya-siswa-public', [
            'karyas' => $karyas,
            'dummyKaryas' => $dummyKaryas,
            'totalKaryaCount' => $karyas->count() + $dummyKaryas->count(),
            'categories' => $categories,
            'siswas' => $siswas,
            'siswaLookup' => $siswaLookup,
            'totalSiswasCount' => $totalSiswasCount,
            'siswaWithKaryaCount' => $siswaWithKaryaCount,
            'availableSkills' => $availableSkills,
        ])->layout('components.layouts.guest');
    }
}
