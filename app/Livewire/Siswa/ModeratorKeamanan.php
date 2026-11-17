<?php

namespace App\Livewire\Siswa;

use App\Models\LoginAudit;
use App\Models\ModerationLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Keamanan & Audit - Moderator')]
class ModeratorKeamanan extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterStatus = '';

    public string $auditSearch = '';

    public string $auditStatus = 'failed';

    public function mount(): void
    {
        if (! Auth::user()?->isModerator()) {
            abort(403);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage('studentsPage');
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage('studentsPage');
    }

    public function updatingAuditSearch(): void
    {
        $this->resetPage('auditPage');
    }

    public function updatingAuditStatus(): void
    {
        $this->resetPage('auditPage');
    }

    /**
     * Nonaktifkan akun siswa yang mencurigakan.
     */
    public function lockAccount(int $studentId): void
    {
        $student = $this->resolveTargetStudent($studentId);

        if ($student->status === 'inactive') {
            return;
        }

        $student->update(['status' => 'inactive']);

        ModerationLog::record(
            actorId: Auth::id(),
            action: 'account.locked',
            description: "Moderator menonaktifkan akun {$student->name}.",
            targetUserId: $student->id
        );

        session()->flash('message', "Akun {$student->name} berhasil dinonaktifkan.");
    }

    /**
     * Aktifkan kembali akun siswa yang sebelumnya dinonaktifkan.
     */
    public function unlockAccount(int $studentId): void
    {
        $student = $this->resolveTargetStudent($studentId);

        if ($student->status === 'active') {
            return;
        }

        $student->update(['status' => 'active']);

        ModerationLog::record(
            actorId: Auth::id(),
            action: 'account.unlocked',
            description: "Moderator mengaktifkan kembali akun {$student->name}.",
            targetUserId: $student->id
        );

        session()->flash('message', "Akun {$student->name} berhasil diaktifkan kembali.");
    }

    /**
     * Hapus riwayat gagal login terbaru agar akun tidak lagi terkunci sementara.
     */
    public function clearLoginLock(int $studentId): void
    {
        $student = $this->resolveTargetStudent($studentId);

        $identifiers = array_values(array_filter([
            $student->email,
            $student->nisn,
        ]));

        if (count($identifiers) === 0) {
            session()->flash('error', 'Akun ini tidak memiliki email atau NISN untuk dibuka blokirnya.');

            return;
        }

        $deleted = LoginAudit::failed()
            ->whereIn('email', $identifiers)
            ->where('attempted_at', '>=', now()->subMinutes(15))
            ->delete();

        ModerationLog::record(
            actorId: Auth::id(),
            action: 'login.unlocked',
            description: "Moderator membuka blokir login sementara untuk {$student->name}.",
            targetUserId: $student->id,
            metadata: ['deleted_attempts' => $deleted]
        );

        session()->flash('message', "Blokir login sementara untuk {$student->name} telah dibuka.");
    }

    /**
     * Moderator hanya boleh mengelola akun siswa lain, bukan dirinya sendiri.
     */
    protected function resolveTargetStudent(int $studentId): User
    {
        $actor = Auth::user();

        if (! $actor?->isModerator()) {
            abort(403);
        }

        $student = User::findOrFail($studentId);

        if (! $student->isSiswa()) {
            abort(403, 'Moderator hanya dapat mengelola akun siswa.');
        }

        if ($student->isModerator() && ! $actor->isAdmin()) {
            abort(403, 'Akun moderator hanya dapat dinonaktifkan atau diaktifkan oleh administrator.');
        }

        if ($student->id === $actor->id) {
            abort(403, 'Anda tidak dapat mengelola akun Anda sendiri.');
        }

        return $student;
    }

    public function render()
    {
        $failedByEmail = LoginAudit::failed()
            ->where('attempted_at', '>=', now()->subMinutes(15))
            ->selectRaw('email, COUNT(*) as total')
            ->groupBy('email')
            ->pluck('total', 'email');

        $students = User::query()
            ->where('role', 'siswa')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('nisn', 'like', '%'.$this->search.'%')
                        ->orWhere('username', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('is_moderator')
            ->orderBy('name')
            ->paginate(10, ['*'], 'studentsPage');

        $audits = LoginAudit::query()
            ->when($this->auditStatus !== 'all', fn ($q) => $q->where('status', $this->auditStatus))
            ->when($this->auditSearch, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('email', 'like', '%'.$this->auditSearch.'%')
                        ->orWhere('ip_address', 'like', '%'.$this->auditSearch.'%');
                });
            })
            ->latest('attempted_at')
            ->paginate(10, ['*'], 'auditPage');

        $stats = [
            'failed_24h' => LoginAudit::failed()->where('attempted_at', '>=', now()->subDay())->count(),
            'success_24h' => LoginAudit::successful()->where('attempted_at', '>=', now()->subDay())->count(),
            'locked_accounts' => $failedByEmail->filter(fn ($total) => (int) $total >= 5)->count(),
            'inactive_students' => User::where('role', 'siswa')->where('status', 'inactive')->count(),
        ];

        $recentLogs = ModerationLog::with(['actor', 'target'])
            ->latest('id')
            ->take(8)
            ->get();

        return view('livewire.siswa.moderator-keamanan', [
            'students' => $students,
            'audits' => $audits,
            'stats' => $stats,
            'failedByEmail' => $failedByEmail,
            'recentLogs' => $recentLogs,
        ]);
    }
}
