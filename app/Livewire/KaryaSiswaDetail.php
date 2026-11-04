<?php

namespace App\Livewire;

use App\Models\KaryaSiswa;
use Livewire\Component;

class KaryaSiswaDetail extends Component
{
    public $karya;
    public $relatedKaryas;

    public function mount($id)
    {
        $query = KaryaSiswa::with('user');

        // Pemilik karya & admin/guru dapat melihat preview karya meskipun masih draft/review
        $user = auth()->user();
        $canViewUnpublished = $user && in_array($user->role, ['admin', 'guru'], true);

        if (!$canViewUnpublished) {
            $query->where(function ($q) use ($user) {
                $q->where('status', 'published');
                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            });
        }

        $this->karya = $query->findOrFail($id);

        // Get related karya by kategori or tahun
        $this->relatedKaryas = KaryaSiswa::where('status', 'published')
            ->where('id', '!=', $this->karya->id)
            ->where(function($query) {
                $query->where('kategori', $this->karya->kategori)
                      ->orWhere('tahun', $this->karya->tahun);
            })
            ->latest()
            ->limit(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.karya-siswa-detail')
            ->layout('components.layouts.guest');
    }
}
