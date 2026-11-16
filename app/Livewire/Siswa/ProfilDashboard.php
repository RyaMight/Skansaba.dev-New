<?php

namespace App\Livewire\Siswa;

use App\Models\KaryaSiswa;
use App\Models\LoginAudit;
use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard - CMS Jurusan RPL')]
class ProfilDashboard extends Component
{
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        $this->user = Auth::user()->fresh();
        Auth::setUser($this->user);
        $userId = $this->user->id;

        $karyaCount = KaryaSiswa::where('user_id', $userId)->published()->count();
        $draftCount = KaryaSiswa::where('user_id', $userId)->draft()->count();
        $reviewCount = KaryaSiswa::where('user_id', $userId)->inReview()->count();
        $archivedCount = KaryaSiswa::where('user_id', $userId)->archived()->count();

        $prestasiCount = Prestasi::where('user_id', $userId)->published()->count();

        $karyaTerbaru = KaryaSiswa::where('user_id', $userId)->latest()->take(5)->get();

        $prestasiTerbaru = Prestasi::where('user_id', $userId)
            ->published()
            ->latest('tanggal_prestasi')
            ->take(4)
            ->get();

        $isModerator = $this->user->isModerator();

        $moderatorPendingCount = $isModerator
            ? KaryaSiswa::where('status', 'review')->where('user_id', '!=', $userId)->count()
            : 0;

        $moderatorFailedLogins = $isModerator
            ? LoginAudit::failed()->where('attempted_at', '>=', now()->subDay())->count()
            : 0;

        $moderatorLockedAccounts = $isModerator
            ? LoginAudit::failed()
                ->where('attempted_at', '>=', now()->subMinutes(15))
                ->select('email')
                ->groupBy('email')
                ->havingRaw('COUNT(*) >= 5')
                ->get()
                ->count()
            : 0;

        $moderatorInactiveAccounts = $isModerator
            ? User::where('role', 'siswa')->where('status', 'inactive')->count()
            : 0;

        return view('livewire.siswa.profil-dashboard', [
            'karyaCount' => $karyaCount,
            'draftCount' => $draftCount,
            'reviewCount' => $reviewCount,
            'archivedCount' => $archivedCount,
            'prestasiCount' => $prestasiCount,
            'karyaTerbaru' => $karyaTerbaru,
            'prestasiTerbaru' => $prestasiTerbaru,
            'isModerator' => $isModerator,
            'moderatorPendingCount' => $moderatorPendingCount,
            'moderatorFailedLogins' => $moderatorFailedLogins,
            'moderatorLockedAccounts' => $moderatorLockedAccounts,
            'moderatorInactiveAccounts' => $moderatorInactiveAccounts,
        ]);
    }
}
