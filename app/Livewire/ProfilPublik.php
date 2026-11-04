<?php

namespace App\Livewire;

use App\Models\KaryaSiswa;
use App\Models\Prestasi;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class ProfilPublik extends Component
{
    public User $profileUser;

    public $karya;

    public $prestasi;

    public function mount(string $slug)
    {
        $user = User::resolveProfile($slug);

        // Tidak ditemukan / bukan siswa aktif -> 404
        if (!$user || !$user->isSiswa()) {
            abort(404);
        }

        $this->profileUser = $user;

        $this->karya = KaryaSiswa::where('user_id', $user->id)
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get();

        $this->prestasi = Prestasi::where('user_id', $user->id)
            ->published()
            ->latest('tanggal_prestasi')
            ->take(6)
            ->get();
    }

    public function render()
    {
        $saluran = $this->profileUser->saluran_kontak ?? [];

        return view('livewire.profil-publik', [
            'title' => ($this->profileUser->name ?? 'Profil') . ' - Profil Siswa RPL',
            'readmeHtml' => app(\App\Services\MarkdownService::class)->parseReadme($this->profileUser->profil_readme),
            'saluran' => $saluran,
            'showEmail' => !empty($saluran['email']['active']) && !empty($this->profileUser->email),
        ]);
    }
}
