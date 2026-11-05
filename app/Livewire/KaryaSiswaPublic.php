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

        // Kategori karya siswa yang tersedia di database untuk filter
        $categories = KaryaSiswa::where('status', 'published')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori')
            ->sort()
            ->values();

        // 2. Data Asli 35 Akun Siswa RPL (untuk Tab "Profil Siswa" & relasi nama_siswa)
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
            'totalKaryaCount' => $karyas->count(),
            'categories' => $categories,
            'siswas' => $siswas,
            'siswaLookup' => $siswaLookup,
            'totalSiswasCount' => $totalSiswasCount,
            'siswaWithKaryaCount' => $siswaWithKaryaCount,
            'availableSkills' => $availableSkills,
        ])->layout('components.layouts.guest');
    }
}
