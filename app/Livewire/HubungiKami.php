<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use App\Models\GlobalSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Hubungi Kami - Jurusan RPL')]
class HubungiKami extends Component
{
    #[Validate('required|min:3|max:100')]
    public $name = '';

    #[Validate('required|email|max:100')]
    public $email = '';

    #[Validate('required|min:10|max:1000')]
    public $message = '';

    public $successMessage = '';

    public function submitMessage()
    {
        $this->validate();

        try {
            ContactMessage::create([
                'name' => $this->name,
                'email' => $this->email,
                'message' => $this->message,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $this->successMessage = __('Pesan Anda berhasil terkirim! Kami akan menghubungi Anda segera.');
            $this->reset(['name', 'email', 'message']);

            // Dispatch browser event untuk notifikasi
            $this->dispatch('message-sent');
        } catch (\Exception $e) {
            $this->addError('form', __('Terjadi kesalahan. Silakan coba lagi.'));
        }
    }

    public function render()
    {
        $globalSettings = GlobalSetting::instance();

        return view('livewire.hubungi-kami', [
            'globalSettings' => $globalSettings,
        ]);
    }
}
