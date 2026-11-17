<?php

namespace App\Livewire\Admin;

use App\Livewire\KaryaSiswaVerifikasi;
use App\Models\ModerationLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Manajemen Akun Siswa RPL')]
class SiswaRplManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterKeahlian = '';

    public string $filterStatus = '';

    public string $filterModerator = '';

    // Modal state: Detail Siswa
    public ?int $viewingStudentId = null;

    // Modal state: Atur Tag Keahlian
    public ?int $editingTagStudentId = null;

    public ?string $selectedKeahlian = null;

    // Modal state: Tambah Akun Siswa Baru (Role strictly 'siswa')
    public bool $showCreateModal = false;

    public string $name = '';

    public string $nisn = '';

    public string $kelas = '';

    public string $email = '';

    public string $password = '';

    public string $status = 'active';

    public ?string $verified_tag = null;

    public function mount()
    {
        if (! in_array(Auth::user()->role, ['admin', 'guru'])) {
            abort(403);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKeahlian()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterModerator()
    {
        $this->resetPage();
    }

    // --- Detail Modal ---
    public function openViewModal(int $id): void
    {
        $this->viewingStudentId = $id;
    }

    public function closeViewModal(): void
    {
        $this->viewingStudentId = null;
    }

    // --- Tag Keahlian Modal & Actions ---
    public function openTagModal(int $id): void
    {
        $this->editingTagStudentId = $id;
    }

    public function closeTagModal(): void
    {
        $this->editingTagStudentId = null;
    }

    public function giveTag(int $studentId, string $tag): void
    {
        $student = User::where('role', 'siswa')->findOrFail($studentId);
        if ($student->giveTag($tag)) {
            session()->flash('message', "Tag \"{$tag}\" berhasil diberikan ke {$student->name}.");
        }
    }

    public function revokeTag(int $studentId, string $tag): void
    {
        $student = User::where('role', 'siswa')->findOrFail($studentId);
        if ($student->revokeTag($tag)) {
            session()->flash('message', "Tag \"{$tag}\" berhasil dicabut dari {$student->name}.");
        }
    }

    // --- Status Moderator (hanya Admin) ---
    public function grantModerator(int $studentId): void
    {
        $this->ensureAdminCanManageModerator();

        $student = User::where('role', 'siswa')->findOrFail($studentId);

        if ($student->is_moderator) {
            return;
        }

        $student->update(['is_moderator' => true]);

        ModerationLog::record(
            actorId: Auth::id(),
            action: 'moderator.granted',
            description: "Status moderator diberikan kepada {$student->name}.",
            targetUserId: $student->id
        );

        session()->flash('message', "{$student->name} sekarang menjadi moderator siswa.");
    }

    public function revokeModerator(int $studentId): void
    {
        $this->ensureAdminCanManageModerator();

        $student = User::where('role', 'siswa')->findOrFail($studentId);

        if (! $student->is_moderator) {
            return;
        }

        $student->update(['is_moderator' => false]);

        ModerationLog::record(
            actorId: Auth::id(),
            action: 'moderator.revoked',
            description: "Status moderator dicabut dari {$student->name}.",
            targetUserId: $student->id
        );

        // Status moderator dicabut; request berikutnya otomatis memakai akses siswa biasa.
        session()->flash('message', "Status moderator {$student->name} telah dicabut.");
    }

    protected function ensureAdminCanManageModerator(): void
    {
        if (! Auth::user()?->isAdmin()) {
            abort(403, 'Hanya administrator yang dapat mengatur status moderator.');
        }
    }

    // --- Tambah Akun Siswa RPL ---
    public function openCreateModal(): void
    {
        $this->reset(['name', 'nisn', 'kelas', 'email', 'password', 'status', 'verified_tag']);
        $this->password = User::DEFAULT_SISWA_PASSWORD;
        $this->status = 'active';
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->reset(['name', 'nisn', 'kelas', 'email', 'password', 'status', 'verified_tag']);
    }

    public function createStudent(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'nisn' => ['nullable', 'required_without:email', 'string', 'max:20', 'unique:users,nisn'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'required_without:nisn', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'status' => ['required', 'in:active,inactive'],
            'verified_tag' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama lengkap siswa wajib diisi.',
            'nisn.required_without' => 'NIS / NISN atau Email wajib diisi salah satu.',
            'email.required_without' => 'Email atau NIS / NISN wajib diisi salah satu.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'nisn.unique' => 'NISN sudah digunakan.',
            'password.required' => 'Password awal wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        // Auto-generate username dasar dari nama
        $baseUsername = Str::slug($this->name);
        $username = $baseUsername;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername.'-'.$i++;
        }

        $user = User::create([
            'name' => $this->name,
            'nisn' => trim($this->nisn) !== '' ? trim($this->nisn) : null,
            'kelas' => trim($this->kelas) !== '' ? trim($this->kelas) : null,
            'username' => $username,
            'email' => trim($this->email) !== '' ? trim($this->email) : null,
            'password' => Hash::make($this->password),
            'role' => 'siswa', // Role selalu siswa
            'status' => $this->status,
            'verified_tags' => $this->verified_tag ?: null,
        ]);

        session()->flash('message', "Akun siswa \"{$user->name}\" berhasil ditambahkan.");
        $this->closeCreateModal();
    }

    public function toggleStatus(int $id): void
    {
        $student = User::where('role', 'siswa')->findOrFail($id);

        if ($student->isModerator() && ! Auth::user()?->isAdmin()) {
            abort(403, 'Akun moderator hanya dapat dinonaktifkan atau diaktifkan oleh administrator.');
        }

        $newStatus = $student->status === 'active' ? 'inactive' : 'active';
        $student->update(['status' => $newStatus]);

        session()->flash('message', "Status akun {$student->name} diubah menjadi: {$student->status_label}");
    }

    public function render()
    {
        $students = User::query()
            ->where('role', 'siswa')
            ->withCount([
                'karyaSiswas as total_karya',
                'karyaSiswas as published_karya' => fn ($q) => $q->where('status', 'published'),
                'karyaSiswas as pending_karya' => fn ($q) => $q->where('status', 'review'),
            ])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('nisn', 'like', '%'.$this->search.'%')
                        ->orWhere('username', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterKeahlian, fn ($q) => $q->where('verified_tags', 'like', '%'.$this->filterKeahlian.'%'))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterModerator === 'yes', fn ($q) => $q->where('is_moderator', true))
            ->when($this->filterModerator === 'no', fn ($q) => $q->where('is_moderator', false))
            ->latest('id')
            ->paginate(15);

        $viewingStudent = $this->viewingStudentId
            ? User::with(['karyaSiswas' => fn ($q) => $q->latest()])->find($this->viewingStudentId)
            : null;

        return view('livewire.admin.siswa-rpl-manager', [
            'students' => $students,
            'tagPresets' => KaryaSiswaVerifikasi::TAG_PRESETS,
            'viewingStudent' => $viewingStudent,
            'canManageModerator' => Auth::user()?->isAdmin() ?? false,
        ]);
    }
}
