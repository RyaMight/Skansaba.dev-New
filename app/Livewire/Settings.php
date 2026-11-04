<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
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
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($this->password);
        $user->save();

        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('message', 'Password berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.settings', [
            'user' => Auth::user(),
        ]);
    }
}
