<?php

namespace App\Livewire\Siswa;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Saluran & Kontak - CMS Jurusan RPL')]
class SaluranSaya extends Component
{
    public $user;

    // Indikasi ada perubahan yang belum disimpan
    public bool $hasUnsavedChanges = false;

    // Snapshot state terakhir yang disimpan (untuk deteksi perubahan).
    // Harus public agar Livewire mem-persist-nya antar request.
    public array $savedSnapshot = [];

    // Email state (bisa diedit sendiri oleh siswa)
    public string $email = '';

    // Saluran & Kontak states
    public array $saluran = [
        'instagram' => [
            'url' => '',
            'active' => true,
        ],
        'tiktok' => [
            'url' => '',
            'active' => true,
        ],
        'whatsapp' => [
            'url' => '',
            'active' => true,
        ],
        'youtube' => [
            'url' => '',
            'active' => false,
        ],
        'linkedin' => [
            'url' => '',
            'active' => true,
        ],
        'twitter' => [
            'url' => '',
            'active' => true,
        ],
        'github' => [
            'url' => '',
            'active' => true,
        ],
        'email' => [
            'active' => false,
        ],
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->email = $this->user->email ?? '';
        $this->loadSaluran();
    }

    public function loadSaluran()
    {
        $saved = $this->user->saluran_kontak ?? [];

        // Default kosong untuk akun siswa yang belum pernah mengisi saluran kontak
        $defaults = [
            'instagram' => [
                'url' => '',
                'active' => false,
            ],
            'tiktok' => [
                'url' => '',
                'active' => false,
            ],
            'whatsapp' => [
                'url' => '',
                'active' => false,
            ],
            'youtube' => [
                'url' => '',
                'active' => false,
            ],
            'linkedin' => [
                'url' => '',
                'active' => false,
            ],
            'twitter' => [
                'url' => '',
                'active' => false,
            ],
            'github' => [
                'url' => '',
                'active' => false,
            ],
            'email' => [
                'active' => false,
            ],
        ];

        foreach ($defaults as $key => $defaultVal) {
            if ($key === 'email') {
                // Email khusus: hanya flag active, url diambil dari data akun
                $this->saluran[$key]['active'] = isset($saved[$key]['active']) ? (bool)$saved[$key]['active'] : $defaultVal['active'];
                continue;
            }

            $this->saluran[$key] = [
                'url' => $saved[$key]['url'] ?? $defaultVal['url'],
                'active' => isset($saved[$key]['active']) ? (bool)$saved[$key]['active'] : $defaultVal['active'],
            ];
        }

        $this->savedSnapshot = [
            'email' => $this->email,
            'saluran' => $this->saluran,
        ];
        $this->hasUnsavedChanges = false;
    }

    public function toggleActive($key)
    {
        if (isset($this->saluran[$key])) {
            $this->saluran[$key]['active'] = !$this->saluran[$key]['active'];
            $this->checkUnsavedChanges();
        }
    }

    public function updated($property)
    {
        $this->checkUnsavedChanges();
    }

    private function checkUnsavedChanges(): void
    {
        // Bandingkan state sekarang dengan snapshot tersimpan (trim url agar whitespace dianggap sama)
        $normalize = fn ($s) => array_map(
            fn ($v) => ['url' => trim($v['url'] ?? ''), 'active' => (bool)($v['active'] ?? false)],
            $s
        );
        $this->hasUnsavedChanges = ['email' => $this->email, 'saluran' => $normalize($this->saluran)]
            != ['email' => $this->savedSnapshot['email'], 'saluran' => $normalize($this->savedSnapshot['saluran'])];
    }

    public function save()
    {
        $this->email = trim($this->email);

        // Normalisasi URL saluran sebelum validasi (cegah javascript: XSS & tambahkan https:// bila belum ada)
        foreach (['instagram', 'tiktok', 'youtube', 'linkedin', 'twitter', 'github'] as $channelKey) {
            $rawUrl = trim($this->saluran[$channelKey]['url'] ?? '');
            if ($rawUrl !== '') {
                if (preg_match('/^(javascript|data|vbscript):/i', $rawUrl)) {
                    $this->addError("saluran.{$channelKey}.url", 'Format tautan tidak diizinkan.');
                    return;
                }
                if (!preg_match('/^https?:\/\//i', $rawUrl)) {
                    $rawUrl = 'https://' . ltrim($rawUrl, '/');
                }
            } else {
                $this->saluran[$channelKey]['active'] = false;
            }
            $this->saluran[$channelKey]['url'] = $rawUrl;
        }

        $waRaw = trim($this->saluran['whatsapp']['url'] ?? '');
        if ($waRaw === '') {
            $this->saluran['whatsapp']['active'] = false;
        }
        $this->saluran['whatsapp']['url'] = $waRaw;

        $this->validate([
            'email' => 'nullable|email|max:255|unique:users,email,' . $this->user->id,
            'saluran.instagram.url' => 'nullable|url|max:255',
            'saluran.tiktok.url' => 'nullable|url|max:255',
            'saluran.whatsapp.url' => 'nullable|string|max:255',
            'saluran.youtube.url' => 'nullable|url|max:255',
            'saluran.linkedin.url' => 'nullable|url|max:255',
            'saluran.twitter.url' => 'nullable|url|max:255',
            'saluran.github.url' => 'nullable|url|max:255',
        ], [
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar oleh pengguna lain.',
            'saluran.*.url.url' => 'Masukkan alamat URL yang valid (contoh: https://instagram.com/username).',
        ]);

        if ($this->email === '') {
            $this->saluran['email']['active'] = false;
        }

        $this->user->update([
            'email' => $this->email !== '' ? $this->email : null,
            'saluran_kontak' => $this->saluran,
        ]);

        session()->flash('message', 'Email, saluran & kontak berhasil diperbarui!');
        $this->hasUnsavedChanges = false;
        $this->savedSnapshot = [
            'email' => $this->email,
            'saluran' => $this->saluran,
        ];
    }

    public function render()
    {
        return view('livewire.siswa.saluran-saya');
    }
}
