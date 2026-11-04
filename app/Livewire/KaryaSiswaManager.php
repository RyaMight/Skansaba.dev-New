<?php

namespace App\Livewire;

use App\Models\KaryaSiswa;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Koleksi Karya Siswa RPL')]
class KaryaSiswaManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public string $filterKategori = '';
    public string $filterTahun = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Detail Modal State
    public bool $showDetailModal = false;
    public ?KaryaSiswa $detailKarya = null;

    // Delete Modal State
    public ?int $confirmingDeleteId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    public function updatingFilterTahun()
    {
        $this->resetPage();
    }

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function setStatusFilter(string $status): void
    {
        $this->filterStatus = ($this->filterStatus === $status) ? '' : $status;
        $this->resetPage();
    }

    // --- Detail Modal ---
    public function openDetailModal(int $id): void
    {
        $karya = KaryaSiswa::with('user')->findOrFail($id);

        if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki hak akses untuk melihat detail karya ini.');
            return;
        }

        $this->detailKarya = $karya;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->detailKarya = null;
    }

    // --- Delete Confirmation Modal ---
    public function confirmDelete(int $id): void
    {
        $karya = KaryaSiswa::findOrFail($id);

        if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki hak akses untuk menghapus karya ini.');
            return;
        }

        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function delete(): void
    {
        if (!$this->confirmingDeleteId) {
            return;
        }

        try {
            $karya = KaryaSiswa::findOrFail($this->confirmingDeleteId);

            // Authorization check
            if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
                session()->flash('error', 'Anda tidak memiliki hak akses untuk menghapus karya ini.');
                $this->cancelDelete();
                return;
            }

            $judul = $karya->judul;

            // Delete thumbnail image if present
            if ($karya->gambar) {
                app(ImageService::class)->delete($karya->gambar);
            }

            $karya->delete();

            session()->flash('message', "Karya \"{$judul}\" berhasil dihapus.");
            $this->cancelDelete();

            if ($this->showDetailModal && $this->detailKarya?->id === $karya->id) {
                $this->closeDetailModal();
            }

            $this->dispatch('karya-updated');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function render()
    {
        $user = Auth::user();

        $baseQuery = KaryaSiswa::query()
            ->when($user->role === 'siswa', fn($q) => $q->where('user_id', $user->id));

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'review' => (clone $baseQuery)->where('status', 'review')->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
        ];

        $karyaSiswas = (clone $baseQuery)
            ->with('user')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_siswa', 'like', '%' . $this->search . '%')
                        ->orWhere('kelas', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterKategori, fn($q) => $q->where('kategori', 'like', '%' . $this->filterKategori . '%'))
            ->when($this->filterTahun, fn($q) => $q->where('tahun', $this->filterTahun))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(12);

        $years = KaryaSiswa::query()
            ->whereNotNull('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('livewire.karya-siswa-manager', [
            'karyaSiswas' => $karyaSiswas,
            'stats' => $stats,
            'years' => $years,
        ]);
    }
}
