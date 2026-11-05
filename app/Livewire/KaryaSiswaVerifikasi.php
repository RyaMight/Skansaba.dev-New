<?php

namespace App\Livewire;

use App\Models\KaryaLog;
use App\Models\KaryaSiswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Verifikasi Karya Siswa')]
class KaryaSiswaVerifikasi extends Component
{
    #[Url]
    public ?int $userId = null;

    public $karyaList = [];

    // Tag tunggal yang dipilih per karya: [karyaId => 'Tag Name']
    public array $selectedTag = [];

    // Tag preset keahlian yang dapat dipilih admin (maksimal 1 per akun siswa)
    public const TAG_PRESETS = [
        'Web Development',
        'Mobile Development',
        'Cyber Security',
        'Game Development',
        'UI/UX Design',
        'IoT & Embedded',
        'AI & Machine Learning',
        'Data Science',
        'Desain Grafis',
        'Animasi & 3D',
        'Robotika',
        'Jaringan Komputer',
        'DevOps & Cloud',
        'Open Source',
    ];

    // Modal state konfirmasi persetujuan (custom di tengah layar)
    public ?int $approvingId = null;

    // Modal state preview karya di dalam halaman
    public ?int $previewId = null;

    // Modal state penolakan karya
    public array $rejectNotes = [];
    public ?int $rejectingId = null;

    public function mount()
    {
        if (!in_array(Auth::user()->role, ['admin', 'guru'])) {
            abort(403);
        }

        $this->loadKarya();
    }

    public function loadKarya()
    {
        $query = KaryaSiswa::with('user')
            ->where('status', 'review')
            ->oldest('updated_at');

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        $this->karyaList = $query->get();

        // Prefill single tag: utamakan keahlian akun siswa jika ada, atau tag pertama karya
        foreach ($this->karyaList as $karya) {
            $defaultTag = $karya->user?->keahlian;
            if (!$defaultTag && !empty($karya->tags) && is_array($karya->tags)) {
                $defaultTag = $karya->tags[0] ?? null;
            }
            $this->selectedTag[$karya->id] = $this->selectedTag[$karya->id] ?? $defaultTag;
        }
    }

    public function clearUserFilter(): void
    {
        $this->userId = null;
        $this->loadKarya();
    }

    public function selectTag(int $karyaId, string $tag): void
    {
        // Toggle single tag selection (1 tag per siswa)
        if (($this->selectedTag[$karyaId] ?? null) === $tag) {
            $this->selectedTag[$karyaId] = null;
        } else {
            $this->selectedTag[$karyaId] = $tag;
        }
    }

    public function openApproveModal(int $id): void
    {
        $this->approvingId = $id;
    }

    public function closeApproveModal(): void
    {
        $this->approvingId = null;
    }

    public function confirmApprove(): void
    {
        if (!$this->approvingId) {
            return;
        }

        $karya = KaryaSiswa::with('user')->findOrFail($this->approvingId);

        if ($karya->status !== 'review') {
            $this->closeApproveModal();
            return;
        }

        $tag = $this->selectedTag[$this->approvingId] ?? null;

        $karya->status = 'published';
        $karya->published_at = now();
        $karya->tags = $tag ? [$tag] : [];
        $karya->catatan_admin = null;
        $karya->save();

        // Berikan tag verifikasi ke akun siswa (memberikan tag tambahan tanpa menimpa tag lama)
        if ($karya->user && $tag) {
            $karya->user->giveTag($tag);
        }

        // Catat ke log keterangan karya (maks 5 log, tertua terhapus otomatis)
        KaryaLog::record(
            userId: $karya->user_id,
            judulKarya: $karya->judul,
            status: 'published',
            action: 'approved',
            catatan: 'Karya disetujui dan telah resmi dipublikasikan' . ($tag ? " dengan keahlian {$tag}." : '.'),
            karyaId: $karya->id,
            adminId: Auth::id(),
            metadata: ['tag' => $tag]
        );

        $siswaName = $karya->nama_siswa ?? $karya->user?->name ?? 'Siswa';
        session()->flash('message', "Karya \"{$karya->judul}\" telah resmi dipublikasikan!" . ($tag ? " Tag \"{$tag}\" berhasil diberikan ke akun {$siswaName}." : ''));

        $this->closeApproveModal();
        $this->dispatch('karya-updated');
        $this->loadKarya();
    }

    public function openPreviewModal(int $id): void
    {
        $this->previewId = $id;
    }

    public function closePreviewModal(): void
    {
        $this->previewId = null;
    }

    public function openRejectModal(int $id): void
    {
        $this->rejectingId = $id;
        $this->rejectNotes[$id] = $this->rejectNotes[$id] ?? '';
    }

    public function closeRejectModal(): void
    {
        $this->rejectingId = null;
    }

    public function confirmReject(): void
    {
        $this->validate([
            "rejectNotes.$this->rejectingId" => 'required|string|min:10|max:1000',
        ], [
            "rejectNotes.$this->rejectingId.required" => 'Tuliskan alasan penolakan agar siswa tahu bagian mana yang harus diperbaiki.',
            "rejectNotes.$this->rejectingId.min" => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $karya = KaryaSiswa::findOrFail($this->rejectingId);

        if ($karya->status !== 'review') {
            $this->closeRejectModal();
            return;
        }

        $catatanTolak = $this->rejectNotes[$this->rejectingId];

        $karya->status = 'draft';
        $karya->catatan_admin = $catatanTolak;
        $karya->save();

        // Catat ke log keterangan karya (maks 5 log, tertua terhapus otomatis)
        KaryaLog::record(
            userId: $karya->user_id,
            judulKarya: $karya->judul,
            status: 'rejected',
            action: 'rejected',
            catatan: $catatanTolak,
            karyaId: $karya->id,
            adminId: Auth::id()
        );

        session()->flash('message', "Karya \"{$karya->judul}\" ditolak dan dikembalikan sebagai draft dengan catatan perbaikan.");
        $this->closeRejectModal();
        $this->dispatch('karya-updated');
        $this->loadKarya();
    }

    public function render()
    {
        return view('livewire.karya-siswa-verifikasi');
    }
}
