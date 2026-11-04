<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Pengaturan - CMS Jurusan RPL')]
class Settings extends Component
{
    // Change password form
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updateUserPassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $defaultPasswords = [
                        strtoupper(User::DEFAULT_SISWA_PASSWORD),
                        strtoupper(User::DEFAULT_ADMIN_PASSWORD),
                        strtoupper(User::DEFAULT_GURU_PASSWORD),
                    ];
                    if (in_array(strtoupper(trim((string) $value)), $defaultPasswords, true)) {
                        $fail('Password baru tidak boleh menggunakan password default bawaan.');
                    }
                },
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->password = Hash::make($this->password);
        $user->save();

        // Bersihkan status password default di session
        $user->clearDefaultPasswordFlag();

        // Sinkronkan hash password di session agar tidak ter-logout otomatis
        if (request()->hasSession()) {
            request()->session()->put([
                'password_hash_' . Auth::getDefaultDriver() => $user->getAuthPassword(),
            ]);
        }
        Auth::setUser($user->fresh());

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->resetValidation();

        session()->flash('message', 'Password berhasil diperbarui! Akun Anda kini lebih aman.');
    }

    public function render()
    {
        $user = Auth::user()?->fresh();

        return view('livewire.settings', [
            'user' => $user,
            'isDefaultPassword' => $user ? $user->isUsingDefaultPassword() : false,
        ]);
    }
}
