<?php

namespace App\Livewire;

use App\Models\KaryaLike;
use App\Models\KaryaSiswa;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Livewire\Component;

class KaryaLikeButton extends Component
{
    public int $karyaId;
    public int $count = 0;
    public bool $liked = false;
    public bool $overlay = false;

    public function mount(int $karyaId, bool $overlay = false): void
    {
        $this->karyaId = $karyaId;
        $this->overlay = $overlay;
        $this->refreshState();
    }

    public function toggle(): void
    {
        $karya = KaryaSiswa::where('status', 'published')->find($this->karyaId);

        if (!$karya) {
            return;
        }

        $existing = $this->findLike();

        if ($existing) {
            $existing->delete();
            $this->refreshState();
        } else {
            $guestToken = auth()->check() ? null : $this->guestToken(true);

            KaryaLike::create([
                'karya_siswa_id' => $this->karyaId,
                'user_id' => auth()->id(),
                'guest_token' => $guestToken,
            ]);

            $this->refreshState($guestToken);
        }
    }

    protected function refreshState(?string $guestTokenOverride = null): void
    {
        $this->count = KaryaLike::where('karya_siswa_id', $this->karyaId)->count();
        $this->liked = $this->findLike($guestTokenOverride) !== null;
    }

    protected function findLike(?string $guestTokenOverride = null): ?KaryaLike
    {
        $query = KaryaLike::where('karya_siswa_id', $this->karyaId);

        if ($userId = auth()->id()) {
            return $query->where('user_id', $userId)->first();
        }

        $token = $guestTokenOverride ?? $this->guestToken();

        return $token ? $query->where('guest_token', $token)->first() : null;
    }

    protected function guestToken(bool $create = false): ?string
    {
        $token = request()->cookie('karya_guest');

        if (!$token && $create) {
            $token = Str::random(40);
            Cookie::queue(Cookie::forever('karya_guest', $token));
        }

        return $token ?: null;
    }

    public function render()
    {
        return view('livewire.karya-like-button');
    }
}