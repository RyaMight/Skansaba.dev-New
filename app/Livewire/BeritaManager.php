<?php

namespace App\Livewire;

use App\Models\Berita;
use App\Services\ImageService;
use App\Services\MarkdownService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Kelola Berita')]
class BeritaManager extends Component
{
    use WithPagination;

    // Search and Filters
    public $search = '';
    public $filterStatus = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Form properties
    public $showModal = false;
    public $isEditMode = false;
    public $beritaId = null;

    // Form fields
    public $title = '';
    public $excerpt = '';
    public $content_md = '';
    public $thumbnail = null;
    public $currentThumbnail = null;
    public $status = 'draft';
    public $is_highlight = false;

    protected $queryString = ['search', 'filterStatus'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $berita = Berita::findOrFail($id);
        $this->beritaId = $berita->id;
        $this->title = $berita->title;
        $this->excerpt = $berita->excerpt;
        $this->content_md = $berita->content_md;
        $this->currentThumbnail = $berita->thumbnail;
        $this->status = $berita->status;
        $this->is_highlight = $berita->is_highlight;
        
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content_md' => ['required', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_highlight' => ['boolean'],
            'thumbnail' => ['nullable', 'string'], // base64
        ];

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content_md' => $this->content_md,
            'status' => $this->status,
            'is_highlight' => $this->is_highlight,
            'author_id' => Auth::id(),
        ];

        // Handle published_at
        if ($this->status === 'published' && (!$this->isEditMode || Berita::find($this->beritaId)->status !== 'published')) {
            $data['published_at'] = now();
        }

        // Handle Thumbnail
        if ($this->thumbnail && str_starts_with($this->thumbnail, 'data:image')) {
            $imageService = app(ImageService::class);
            
            // Delete old thumbnail if editing
            if ($this->isEditMode && $this->currentThumbnail) {
                $imageService->delete($this->currentThumbnail);
            }

            $data['thumbnail'] = $imageService->processBase64AndStore(
                $this->thumbnail,
                'news',
                ['width' => 1200, 'height' => 675] // 16:9 ratio
            );
        }

        if ($this->isEditMode) {
            Berita::findOrFail($this->beritaId)->update($data);
            session()->flash('message', 'Berita berhasil diperbarui.');
        } else {
            Berita::create($data);
            session()->flash('message', 'Berita berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $berita = Berita::findOrFail($id);
        
        // Don't delete image on soft delete? 
        // Usually better to keep it in case of restore.
        
        $berita->delete();
        session()->flash('message', 'Berita berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'beritaId', 'title', 'excerpt', 'content_md', 'thumbnail', 
            'currentThumbnail', 'status', 'is_highlight'
        ]);
        $this->status = 'draft';
        $this->is_highlight = false;
    }

    public function render()
    {
        $beritas = Berita::with('author')
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.berita-manager', [
            'beritas' => $beritas
        ]);
    }
}
