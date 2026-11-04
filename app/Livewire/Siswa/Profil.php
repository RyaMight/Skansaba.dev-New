<?php

namespace App\Livewire\Siswa;

use App\Models\KaryaSiswa;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Edit Profil - CMS Jurusan RPL')]
class Profil extends Component
{
    public $user;

    // Edit modal state
    public bool $showEditModal = false;
    public string $name = '';
    public string $email = '';
    public string $bio = '';
    public string $keahlian = '';
    public $photoBase64 = null;
    public $crop_data = null;

    protected $imageService;

    public function boot(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->initForm();
    }

    public function initForm()
    {
        $this->name = $this->user->name ?? '';
        $this->email = $this->user->email ?? '';
        $this->bio = $this->user->bio ?? 'Tertarik pada Web Development dan UI/UX Design. Senang belajar hal baru dan berkarya di bidang Rekayasa Perangkat Lunak.';
        $this->keahlian = $this->user->keahlian ?? '';
        $this->photoBase64 = null;
    }

    public function openEditModal()
    {
        $this->initForm();
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->photoBase64 = null;
        $this->crop_data = null;
    }

    public function saveProfile(?string $base64Photo = null)
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'bio' => 'nullable|string|max:500',
            'keahlian' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email aktif wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar oleh pengguna lain.',
        ]);

        $photoData = $base64Photo ?: $this->photoBase64;

        // Process base64 cropped photo if provided.
        // Save the new file FIRST, update the DB, then delete the old file —
        // this way the old photo is never lost if processing fails.
        if ($photoData && str_starts_with($photoData, 'data:image')) {
            $oldPhotoPath = $this->user->photo;

            // Per-account folder: users/photos/u{id} — each account owns its own directory
            $newPhotoPath = $this->imageService->processBase64AndStore(
                $photoData,
                'users/photos/u' . $this->user->id,
                ['width' => 500, 'height' => 500]
            );

            if ($newPhotoPath) {
                $this->user->update(['photo' => $newPhotoPath]);

                // Delete the old photo file so it doesn't pile up as orphaned storage
                if ($oldPhotoPath && $oldPhotoPath !== $newPhotoPath) {
                    $this->imageService->delete($oldPhotoPath);
                }
            } else {
                logger()->error('Gagal memproses foto profil untuk user ID: ' . $this->user->id);
            }
        }

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'bio' => $this->bio,
            'keahlian' => $this->keahlian,
        ]);

        // Refresh user model & sync auth guard + sidebar
        $this->user = $this->user->fresh();
        Auth::setUser($this->user);
        $this->dispatch('profile-photo-updated');
        $this->dispatch('profile-updated');

        $this->showEditModal = false;
        $this->photoBase64 = null;
        session()->flash('message', 'Profil berhasil diperbarui!');
    }

    public function render()
    {
        $karyaCount = KaryaSiswa::where('user_id', $this->user->id)->published()->count();
        $draftCount = KaryaSiswa::where('user_id', $this->user->id)->draft()->count();
        $reviewCount = KaryaSiswa::where('user_id', $this->user->id)->inReview()->count();
        $archivedCount = KaryaSiswa::where('user_id', $this->user->id)->archived()->count();

        $karyas = KaryaSiswa::where('user_id', $this->user->id)->latest()->take(3)->get();

        return view('livewire.siswa.profil', [
            'karyaCount' => $karyaCount ?: 8,
            'draftCount' => $draftCount,
            'reviewCount' => $reviewCount,
            'archivedCount' => $archivedCount,
            'karyas' => $karyas,
        ]);
    }
}
