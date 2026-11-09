<?php

namespace App\Livewire;

use App\Models\Galeri;
use App\Models\Prestasi;
use Livewire\Component;

class GalleryPublic extends Component
{
    public $filterKategori = '';

    protected $queryString = [
        'filterKategori' => ['except' => ''],
    ];

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    public function render()
    {
        $galleries = Galeri::query()
            ->withCount('images')
            ->where('status', 'published')
            ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
            ->orderBy('published_at', 'desc')
            ->get();

        // Get unique categories for filter
        $categories = Galeri::where('status', 'published')
            ->distinct()
            ->pluck('kategori')
            ->filter()
            ->sort()
            ->values();

        $prestasis = Prestasi::where('status', 'published')
            ->orderByDesc('tanggal_prestasi')
            ->take(8)
            ->get();

        return view('livewire.gallery-public', [
            'galleries' => $galleries,
            'categories' => $categories,
            'prestasis' => $prestasis,
        ])->layout('components.layouts.guest');
    }
}
