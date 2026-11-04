<?php

namespace App\Livewire\Siswa;

use App\Models\KaryaSiswa;
use App\Models\Prestasi;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Profil Saya - CMS Jurusan RPL')]
class ProfilPublikEdit extends Component
{
    public $user;

    // Form state
    public string $headline = '';
    public string $bio = '';
    public string $keahlian = '';
    public string $username = '';
    public $profil_readme = null;
    public bool $showEditorModal = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->initForm();
    }

    public function initForm()
    {
        $this->headline = $this->user->headline ?? '';
        $this->bio = $this->user->bio ?? '';
        $this->keahlian = $this->user->keahlian ?? '';
        $this->username = $this->user->username ?? '';
    }

    // Edit modal state (foto profil)
    public bool $showEditModal = false;
    public $photoBase64 = null;
    public $crop_data = null;

    protected $imageService;

    public function boot(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function openEditModal()
    {
        $this->photoBase64 = null;
        $this->crop_data = null;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->photoBase64 = null;
        $this->crop_data = null;
    }

    /**
     * Simpan foto profil (modal edit foto).
     * Urutan aman: proses foto baru dulu -> update DB -> hapus foto lama.
     */
    public function savePhoto()
    {
        if (!$this->photoBase64 || !str_starts_with($this->photoBase64, 'data:image')) {
            $this->closeEditModal();
            return;
        }

        $oldPhotoPath = $this->user->photo;

        // Per-account folder: users/photos/u{id} — each account owns its own directory
        $newPhotoPath = $this->imageService->processBase64AndStore(
            $this->photoBase64,
            'users/photos/u' . $this->user->id,
            ['width' => 500, 'height' => 500]
        );

        if ($newPhotoPath) {
            $this->user->update(['photo' => $newPhotoPath]);

            // Delete the old photo file so it doesn't pile up as orphaned storage
            if ($oldPhotoPath && $oldPhotoPath !== $newPhotoPath) {
                $this->imageService->delete($oldPhotoPath);
            }

            session()->flash('message', 'Foto profil berhasil diperbarui!');
        } else {
            logger()->error('Gagal memproses foto profil untuk user ID: ' . $this->user->id);
            session()->flash('error', 'Gagal memproses foto. Coba lagi.');
        }

        // Refresh user model
        $this->user = $this->user->fresh();

        $this->showEditModal = false;
        $this->photoBase64 = null;
    }

    public function openEditorModal()
    {
        $this->showEditorModal = true;
    }

    public function closeEditorModal()
    {
        $this->showEditorModal = false;
    }

    public function saveReadme()
    {
        $this->validate([
            'profil_readme' => 'nullable|string|max:20000',
        ], [
            'profil_readme.max' => 'README maksimal 20.000 karakter.',
        ]);

        $this->user->update([
            'profil_readme' => app(\App\Services\MarkdownService::class)->normalize($this->profil_readme),
        ]);

        $this->showEditorModal = false;
        session()->flash('message', 'README profil berhasil disimpan!');
    }

    public function saveBiodata()
    {
        $this->validate([
            'headline' => 'nullable|string|max:120',
            'bio' => 'nullable|string|max:500',
            'keahlian' => 'nullable|string|max:255',
            'username' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-z0-9][a-z0-9-]*[a-z0-9]$/',
                'unique:users,username,' . $this->user->id,
                'not_in:admin,api,settings,login,logout,dashboard,profil',
            ],
        ], [
            'headline.max' => 'Headline maksimal 120 karakter.',
            'bio.max' => 'Bio maksimal 500 karakter.',
            'username.regex' => 'Username hanya boleh huruf kecil, angka, dan tanda hubung (-). Contoh: ahmad-syahputra.',
            'username.unique' => 'Username ini sudah dipakai pengguna lain.',
            'username.not_in' => 'Username ini tidak boleh digunakan.',
        ]);

        $this->user->update([
            'headline' => $this->headline,
            'bio' => $this->bio,
            'keahlian' => $this->keahlian,
            'username' => $this->username ?: null,
        ]);

        session()->flash('message', 'Biodata profil berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.siswa.profil-publik-edit');
    }
}
