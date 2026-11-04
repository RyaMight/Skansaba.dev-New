<?php

namespace App\Livewire\Siswa;

use App\Models\ProfilAset;
use App\Services\ImageService;
use App\Services\MarkdownService;
use App\Services\SvgService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use RuntimeException;

#[Layout('components.layouts.app')]
#[Title('Profil Saya - CMS Jurusan RPL')]
class ProfilPublikEdit extends Component
{
    use WithFileUploads;

    public $user;

    // Form state
    public string $bio = '';

    public string $keahlian = '';

    public string $username = '';

    public $profil_readme = null;

    public bool $showEditorModal = false;

    public bool $showUsernameConfirmModal = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->initForm();
    }

    public function initForm()
    {
        $this->bio = $this->user->bio ?? '';
        $this->keahlian = $this->user->keahlian ?? '';
        $this->username = $this->user->username ?? '';
    }

    // Edit modal state (foto profil)
    public bool $showEditModal = false;

    public $photoBase64 = null;

    public $crop_data = null;

    // Aset SVG README
    public $fileSvg = null;

    public function updatedFileSvg()
    {
        $this->validate([
            'fileSvg' => 'nullable|file|mimes:svg|max:1024',
        ], [
            'fileSvg.mimes' => 'File harus berekstensi .svg',
            'fileSvg.max' => 'Ukuran SVG maksimal 1 MB.',
        ]);

        $this->resetErrorBag('fileSvg');
    }

    public function uploadSvg()
    {
        $this->validate([
            'fileSvg' => 'required|file|mimes:svg|max:1024',
        ], [
            'fileSvg.required' => 'Pilih file SVG dulu.',
            'fileSvg.mimes' => 'File harus berekstensi .svg',
            'fileSvg.max' => 'Ukuran SVG maksimal 1 MB.',
        ]);

        $jumlah = ProfilAset::where('user_id', $this->user->id)->count();
        if ($jumlah >= SvgService::MAX_FILES_PER_USER) {
            $this->addError('fileSvg', 'Kuota aset penuh. Maksimal '.SvgService::MAX_FILES_PER_USER.' file SVG per akun — hapus salah satu dulu.');

            return;
        }

        $svgService = app(SvgService::class);

        try {
            $svgService->store($this->fileSvg, $this->user->id);
        } catch (RuntimeException $e) {
            $this->addError('fileSvg', $e->getMessage());

            return;
        }

        $this->reset('fileSvg');
        session()->flash('message', 'SVG berhasil diupload! Klik "Sisipkan" untuk menaruhnya di README.');
    }

    public function deleteSvg(int $asetId)
    {
        $aset = ProfilAset::where('user_id', $this->user->id)->find($asetId);

        if ($aset) {
            app(SvgService::class)->delete($aset);
            session()->flash('message', 'Aset SVG dihapus.');
        }
    }

    protected function asetSvgList()
    {
        return ProfilAset::where('user_id', $this->user->id)
            ->orderByDesc('created_at')
            ->get();
    }

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
    public function savePhoto(?string $base64Photo = null)
    {
        $photoData = $base64Photo ?: $this->photoBase64;

        if (! $photoData || ! str_starts_with($photoData, 'data:image')) {
            session()->flash('error', 'Silakan pilih foto profil terlebih dahulu sebelum menyimpan.');
            return;
        }

        $oldPhotoPath = $this->user->photo;

        // Per-account folder: users/photos/u{id} — each account owns its own directory
        $newPhotoPath = $this->imageService->processBase64AndStore(
            $photoData,
            'users/photos/u'.$this->user->id,
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
            logger()->error('Gagal memproses foto profil untuk user ID: '.$this->user->id);
            session()->flash('error', 'Gagal memproses foto. Silakan coba lagi.');
        }

        // Refresh user model & sync auth guard + sidebar
        $this->user = $this->user->fresh();
        Auth::setUser($this->user);
        $this->dispatch('profile-photo-updated');

        $this->showEditModal = false;
        $this->photoBase64 = null;
    }

    public function openEditorModal()
    {
        // Muat isi README terkini supaya edit tidak mulai dari kosong
        $this->profil_readme = $this->user->fresh()->profil_readme ?? '';

        $this->showEditorModal = true;
    }

    public function closeEditorModal()
    {
        $this->showEditorModal = false;
    }

    public function loadReadmeTemplate(string $type)
    {
        $nama = $this->user->name ?? 'Nama Siswa';
        $email = $this->user->email ?? 'nama@email.com';
        $urlProfil = $this->user->profil_url;

        if ($type === 'magang') {
            $this->profil_readme = "# Halo, Saya {$nama} 👋\nSiswa Rekayasa Perangkat Lunak di SMKN 1 Bantul.\n\n---\n\n### 🎯 Tentang Saya\nSaya berfokus pada pengembangan aplikasi web dan antarmuka pengguna (UI/UX). Terbiasa belajar hal baru dengan cepat dan siap berkontribusi dalam program magang (PKL) industri.\n\n### 🛠️ Keahlian & Teknologi\n- **Bahasa Pemrograman:** PHP, JavaScript, HTML5, CSS3\n- **Framework & Libs:** Laravel, Tailwind CSS, Livewire\n- **Tools:** Git, GitHub, VS Code, Figma\n\n### 🚀 Proyek Pilihan\n1. **Aplikasi Manajemen Sekolah** — Dibangun dengan Laravel & Tailwind CSS.\n2. **Website Portofolio Responsif** — Menampilkan karya-karya terbaik selama masa sekolah.\n\n### 📫 Kontak & Terhubung\n- **Email:** {$email}\n- **Portofolio:** [Lihat Profil Saya]({$urlProfil})";
        } elseif ($type === 'developer') {
            $this->profil_readme = "<div align=\"center\">\n\n# 💻 {$nama}\n**Junior Fullstack Developer · Siswa RPL SMKN 1 Bantul**\n\n<i>\"Fokus membangun aplikasi web yang cepat, bersih, dan mudah digunakan.\"</i>\n\n</div>\n\n---\n\n### ⚡ Tech Stack Utama\n- **Backend:** PHP / Laravel, MySQL, RESTful API\n- **Frontend:** Tailwind CSS, JavaScript, Alpine.js / Livewire\n- **Dev Tools:** Git, Visual Studio Code, Postman\n\n### 📌 Riwayat Proyek\n- **Sistem Web Fullstack:** Fitur multi-role authentication dan manajemen data terstruktur.\n- **Landing Page Interaktif:** Desain responsif mobile-friendly dengan performa tinggi.\n\n### 🤝 Terhubung\nTerbuka untuk diskusi proyek kolaborasi dan kesempatan magang (PKL) industri!";
        }

        session()->flash('template_message', 'Template berhasil dimasukkan! Silakan sesuaikan teks nama dan datamu.');
    }

    public function getProfileCompletionProperty(): array
    {
        $items = [
            'Foto Profil' => (bool) $this->user->photo_url,
            'Bio Singkat' => ! empty(trim($this->user->bio ?? '')),
            'Keahlian (Skills)' => ! empty(trim($this->user->keahlian ?? '')),
            'Username Kustom' => ! empty(trim($this->user->username ?? '')),
            'README Profil' => ! empty(trim($this->user->profil_readme ?? '')),
        ];

        $completed = count(array_filter($items));
        $total = count($items);
        $percent = (int) round(($completed / $total) * 100);

        return [
            'items' => $items,
            'completed' => $completed,
            'total' => $total,
            'percentage' => $percent,
        ];
    }

    public function saveReadme()
    {
        $this->validate([
            'profil_readme' => 'nullable|string|max:20000',
        ], [
            'profil_readme.max' => 'README maksimal 20.000 karakter.',
        ]);

        $this->user->update([
            'profil_readme' => app(MarkdownService::class)->normalize($this->profil_readme),
        ]);

        $this->showEditorModal = false;
        session()->flash('message', 'README profil berhasil disimpan!');
    }

    public function closeUsernameConfirmModal()
    {
        $this->showUsernameConfirmModal = false;
    }

    protected function validateBiodataWithUsername()
    {
        return $this->validate([
            'bio' => 'nullable|string|max:500',
            'keahlian' => 'nullable|string|max:255',
            'username' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-z0-9][a-z0-9-]*[a-z0-9]$/',
                'unique:users,username,'.$this->user->id,
                'not_in:admin,api,settings,login,logout,dashboard,profil',
                function (string $attribute, string $value, \Closure $fail) {
                    if ($this->mengandungKataTerlarang($value)) {
                        $fail('Username mengandung kata tidak pantas (SARA/kasar). Pilih yang lain.');
                    }
                },
            ],
        ], [
            'bio.max' => 'Bio maksimal 500 karakter.',
            'username.regex' => 'Username hanya boleh huruf kecil, angka, dan tanda hubung (-). Contoh: ahmad-syahputra.',
            'username.unique' => 'Username ini sudah dipakai pengguna lain.',
            'username.not_in' => 'Username ini tidak boleh digunakan.',
        ]);
    }

    public function saveBiodata()
    {
        $normalizedNewUsername = trim($this->username) !== '' ? trim($this->username) : null;
        $currentUsername = $this->user->username;
        $isUsernameChanging = $normalizedNewUsername !== $currentUsername;

        if ($isUsernameChanging) {
            // Cek apakah akun masih dalam masa cooldown 7 hari
            if (! $this->user->canChangeUsername()) {
                $sisaHari = $this->user->daysUntilNextUsernameChange();
                $tglBisa = $this->user->usernameAvailableAgainAt()?->translatedFormat('d F Y, H:i');
                $this->addError('username', "Username hanya bisa diubah 1 kali setiap 7 hari. Kamu baru bisa menggantinya lagi pada {$tglBisa} ({$sisaHari} hari lagi).");

                return;
            }

            // Validasi format, keunikan, dan kata terlarang
            $this->validateBiodataWithUsername();

            // Tampilkan popup konfirmasi 7 hari sebelum perubahan diterapkan
            $this->showUsernameConfirmModal = true;

            return;
        }

        // Jika username tidak berubah, hanya update biodata (bio, keahlian)
        $this->validate([
            'bio' => 'nullable|string|max:500',
            'keahlian' => 'nullable|string|max:255',
        ], [
            'bio.max' => 'Bio maksimal 500 karakter.',
        ]);

        $this->user->update([
            'bio' => $this->bio,
            'keahlian' => $this->keahlian,
        ]);

        $this->user = $this->user->fresh();
        session()->flash('message', 'Biodata profil berhasil diperbarui!');
    }

    public function confirmSaveBiodata()
    {
        $normalizedNewUsername = trim($this->username) !== '' ? trim($this->username) : null;
        $currentUsername = $this->user->username;
        $isUsernameChanging = $normalizedNewUsername !== $currentUsername;

        if ($isUsernameChanging) {
            if (! $this->user->canChangeUsername()) {
                $sisaHari = $this->user->daysUntilNextUsernameChange();
                $tglBisa = $this->user->usernameAvailableAgainAt()?->translatedFormat('d F Y, H:i');
                $this->addError('username', "Username hanya bisa diubah 1 kali setiap 7 hari. Kamu baru bisa menggantinya lagi pada {$tglBisa} ({$sisaHari} hari lagi).");
                $this->showUsernameConfirmModal = false;

                return;
            }

            $this->validateBiodataWithUsername();

            $this->user->update([
                'bio' => $this->bio,
                'keahlian' => $this->keahlian,
                'username' => $normalizedNewUsername,
                'username_changed_at' => now(),
            ]);
        } else {
            $this->user->update([
                'bio' => $this->bio,
                'keahlian' => $this->keahlian,
            ]);
        }

        $this->user = $this->user->fresh();
        $this->initForm();
        $this->showUsernameConfirmModal = false;

        session()->flash('message', 'Biodata dan username profil berhasil diperbarui! Kamu dapat mengubah username lagi setelah 7 hari.');
    }

    /**
     * Daftar kata terlarang (SARA/kasar, ID+EN). Normalisasi leetspeak:
     * 0->o, 1->i, 3->e, 4->a, 5->s, @->a, $->s, 7->t, !->i.
     * shortcut: blacklist statis sederhana, bukan layanan profanity filter —
     * kalau mau lebih pintar ( fuzzy match, bahasa lain), pakai paket seperti webmozart/profanity-filter.
     */
    private const KATA_TERLARANG = [
        // Indonesia - SARA & kasar
        'anjing', 'anjg', 'bgst', 'bangsat', 'babi', 'bjat', 'bajingan',
        'kontol', 'memek', 'pepek', 'ngentot', 'entot', 'jancuk', 'jancok',
        'fuck', 'asu', 'asw', 'tai', 'tahi', 'kehed', 'goblog', 'goblok',
        'bodoh', 'idiot', 'tolol', 'ngentod', 'pentil', 'titit', 'peler',
        'crot', 'bokep', 'seks', 'sex', 'bugil', 'telanjang', 'ngewe',
        'jatimtiming', 'perebutan', 'gay', 'lesbian', 'homo', 'babel',
        // SARA
        'kafir', 'haramjadi', 'pki', 'g30s', 'komunis', 'zionis', 'yahudi',
        'nasidem', 'fasis', 'nazi', 'hitler', 'kuil', 'jesus', 'tuhan',
        // Inggris - kasar
        'fuck', 'fck', 'shit', 'bitch', 'bastard', 'dick', 'cock', 'pussy',
        'asshole', 'arse', 'cunt', 'damn', 'whore', 'slut', 'nigga', 'nigger',
        'faggot', 'retard', 'rape', 'penis', 'vagina', 'boobs', 'tits',
        'nude', 'nudes', 'porn', 'hentai', 'jerking', 'horny', 'dildo',
        'blowjob', 'handjob', 'cumshot', 'cum', 'anal', 'horni',
    ];

    private function mengandungKataTerlarang(string $username): bool
    {
        // Normalisasi: hapus pemisah + ganti leetspeak umum
        $normalized = str_replace(
            ['0', '1', '3', '4', '5', '7', '@', '$', '!'],
            ['o', 'i', 'e', 'a', 's', 't', 'a', 's', 'i'],
            strtolower($username)
        );
        $normalized = preg_replace('/[^a-z]/', '', $normalized);

        foreach (self::KATA_TERLARANG as $word) {
            if (str_contains($normalized, $word)) {
                return true;
            }
        }

        return false;
    }

    public function render()
    {
        $this->user = $this->user->fresh();
        Auth::setUser($this->user);

        return view('livewire.siswa.profil-publik-edit', [
            'asetSvgList' => $this->asetSvgList(),
        ]);
    }
}
