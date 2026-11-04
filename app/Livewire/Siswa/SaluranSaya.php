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

        // Defaults sesuai gambar & preferensi jika belum pernah disimpan
        $defaults = [
            'instagram' => [
                'url' => 'https://instagram.com/rizky_pratama',
                'active' => true,
            ],
            'tiktok' => [
                'url' => 'https://tiktok.com/@rizky_pratama',
                'active' => true,
            ],
            'whatsapp' => [
                'url' => '+6281234567890',
                'active' => true,
            ],
            'youtube' => [
                'url' => '',
                'active' => false,
            ],
            'linkedin' => [
                'url' => 'https://linkedin.com/in/rizky-pratama',
                'active' => true,
            ],
            'twitter' => [
                'url' => 'https://x.com/rizky_pratama',
                'active' => true,
            ],
            'github' => [
                'url' => 'https://github.com/rizkypratama',
                'active' => true,
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
    }

    public function toggleActive($key)
    {
        if (isset($this->saluran[$key])) {
            $this->saluran[$key]['active'] = !$this->saluran[$key]['active'];
        }
    }

    public function save()
    {
        $this->validate([
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'saluran.instagram.url' => 'nullable|string|max:255',
            'saluran.tiktok.url' => 'nullable|string|max:255',
            'saluran.whatsapp.url' => 'nullable|string|max:255',
            'saluran.youtube.url' => 'nullable|string|max:255',
            'saluran.linkedin.url' => 'nullable|string|max:255',
            'saluran.twitter.url' => 'nullable|string|max:255',
            'saluran.github.url' => 'nullable|string|max:255',
        ], [
            'email.required' => 'Email aktif wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar oleh pengguna lain.',
        ]);

        $this->user->update([
            'email' => $this->email,
            'saluran_kontak' => $this->saluran,
        ]);

        session()->flash('message', 'Email, saluran & kontak berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.siswa.saluran-saya');
    }
}
