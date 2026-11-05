<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Pesan Kontak - Admin')]
class ContactMessageManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all'; // all, unread, read
    public $selectedMessage = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function viewMessage($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->markAsRead();
        $this->selectedMessage = $message;
    }

    public function closeDetail()
    {
        $this->selectedMessage = null;
    }

    public function deleteMessage($id)
    {
        ContactMessage::findOrFail($id)->delete();
        $this->selectedMessage = null;
        session()->flash('success', __('Pesan berhasil dihapus'));
    }

    public function render()
    {
        $query = ContactMessage::query()
            ->when($this->filter === 'unread', fn($q) => $q->unread())
            ->when($this->filter === 'read', fn($q) => $q->read())
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('message', 'like', "%{$this->search}%");
                });
            })
            ->latest();

        $messages = $query->paginate(20);
        $unreadCount = ContactMessage::unread()->count();

        return view('livewire.contact-message-manager', [
            'messages' => $messages,
            'unreadCount' => $unreadCount,
        ]);
    }
}
