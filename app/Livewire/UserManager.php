<?php

namespace App\Livewire;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class UserManager extends Component
{
    public $users;
    
    // Form fields
    public $user_id;
    public $name;
    public $email;
    public $password;
    public $role = 'guru';
    public $status = 'active';
    public $photo;
    
    // Filters
    public $search = '';
    public $filterRole = '';
    public $filterStatus = '';
    
    // Modal states
    public $showFormModal = false;
    public $showDetailModal = false;
    public $showDeleteModal = false;
    public $isEditing = false;
    
    // Detail & delete
    public $selectedUser;
    public $userToDelete;
    
    protected $imageService;

    public function boot(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function mount()
    {
        $this->authorize('viewAny', User::class);
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $query = User::query()
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, fn($q) => $q->byRole($this->filterRole))
            ->when($this->filterStatus, fn($q) => $q->byStatus($this->filterStatus))
            ->orderBy('created_at', 'desc');

        $this->users = $query->get();
    }

    #[On('refreshUsers')]
    public function refreshUsers()
    {
        $this->loadUsers();
    }

    public function updatedSearch()
    {
        $this->loadUsers();
    }

    public function updatedFilterRole()
    {
        $this->loadUsers();
    }

    public function updatedFilterStatus()
    {
        $this->loadUsers();
    }

    public function openCreateModal()
    {
        $this->authorize('create', User::class);
        
        $this->reset(['user_id', 'name', 'email', 'password', 'role', 'status', 'photo']);
        $this->role = 'guru';
        $this->status = 'active';
        $this->password = $this->generatePassword();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function openEditModal($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->role;
        $this->status = $user->status;
        $this->photo = null;
        
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function openDetailModal($id)
    {
        $this->selectedUser = User::findOrFail($id);
        $this->authorize('view', $this->selectedUser);
        $this->showDetailModal = true;
    }

    public function closeFormModal()
    {
        $this->showFormModal = false;
        $this->reset(['user_id', 'name', 'email', 'password', 'role', 'status', 'photo']);
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedUser = null;
    }

    #[On('imageUpdated')]
    public function handleImageUpdate($data)
    {
        $this->photo = $data['base64'];
    }

    public function save()
    {
        \Illuminate\Support\Facades\Log::info('Save method reached. isEditing: ' . ($this->isEditing ? 'yes' : 'no'));
        try {
            if ($this->isEditing) {
                $this->update();
            } else {
                $this->store();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('User Management Error: ' . $e->getMessage());
        }
    }

    public function store()
    {
        \Illuminate\Support\Facades\Log::info('Store method reached');
        $this->authorize('create', User::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,guru,siswa'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role harus admin, guru, atau siswa.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus active atau inactive.',
        ]);

        // Process photo if provided
        if ($this->photo && str_starts_with($this->photo, 'data:image')) {
            $validated['photo'] = $this->imageService->processBase64AndStore(
                $this->photo,
                'users',
                ['width' => 500, 'height' => 500]
            );
        }

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        $this->closeFormModal();
        $this->loadUsers();
        
        session()->flash('message', 'User berhasil ditambahkan.');
    }

    public function update()
    {
        \Illuminate\Support\Facades\Log::info('Update method reached for user ID: ' . $this->user_id);
        $user = User::findOrFail($this->user_id);
        $this->authorize('update', $user);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user_id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,guru,siswa'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role harus admin, guru, atau siswa.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus active atau inactive.',
        ]);

        // Cegah admin menonaktifkan atau menurunkan role akunnya sendiri
        if ($user->id === auth()->id()) {
            if ($validated['status'] !== 'active') {
                $this->addError('status', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
                return;
            }
            if ($validated['role'] !== 'admin') {
                $this->addError('role', 'Anda tidak dapat mengubah role akun Anda sendiri.');
                return;
            }
        }

        // Process photo if provided.
        // Save the new file FIRST, update the DB, then delete the old file —
        // this way the old photo is never lost if processing fails.
        if ($this->photo && str_starts_with($this->photo, 'data:image')) {
            $oldPhotoPath = $user->photo;

            // Per-account folder: users/u{id} — each account owns its own directory
            $validated['photo'] = $this->imageService->processBase64AndStore(
                $this->photo,
                'users/u' . $user->id,
                ['width' => 500, 'height' => 500]
            );

            if (!empty($validated['photo'])) {
                $user->update(['photo' => $validated['photo']]);

                // Delete the old photo file so it doesn't pile up as orphaned storage
                if ($oldPhotoPath && $oldPhotoPath !== $validated['photo']) {
                    $this->imageService->delete($oldPhotoPath);
                }
            } else {
                logger()->error('Gagal memproses foto profil untuk user ID: ' . $user->id);
            }

            unset($validated['photo']); // prevent double-update below
        }

        // Update password only if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        $this->closeFormModal();
        $this->loadUsers();
        
        session()->flash('message', 'User berhasil diperbarui.');
    }

    public function confirmDelete($id)
    {
        $this->userToDelete = User::findOrFail($id);
        $this->authorize('delete', $this->userToDelete);
        
        // Check if trying to delete self
        if ($this->userToDelete->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }
        
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if (!$this->userToDelete) {
            return;
        }

        $this->authorize('delete', $this->userToDelete);

        // Delete photo file if exists
        if ($this->userToDelete->photo) {
            $this->imageService->delete($this->userToDelete->photo);
        }

        // Remove the account's photo directory (per-account folder) so no orphaned files remain
        $accountDir = 'users/u' . $this->userToDelete->id;
        if (Storage::disk('public')->directoryExists($accountDir)) {
            Storage::disk('public')->deleteDirectory($accountDir);
        }

        $this->userToDelete->delete();

        $this->showDeleteModal = false;
        $this->userToDelete = null;
        $this->loadUsers();
        
        session()->flash('message', 'User berhasil dihapus.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }

    public function generatePassword()
    {
        return Str::random(12);
    }

    public function regeneratePassword()
    {
        $this->password = $this->generatePassword();
    }

    public function render()
    {
        return view('livewire.user-manager')->layout('components.layouts.app');
    }
}
