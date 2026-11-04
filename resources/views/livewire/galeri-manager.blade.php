<div class="min-h-screen bg-gray-50 dark:bg-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-6">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-50">Kelola Galeri</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Dokumentasi visual kegiatan siswa dan jurusan RPL</p>
            </div>
            <button wire:click="openCreateModal"
                class="px-6 py-3 bg-[#12B4E0] text-white rounded-lg hover:bg-[#0e91b8] font-semibold flex items-center gap-2 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Galeri
            </button>
        </div>

        {{-- Flash Messages --}}
        @if(session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-500//10 border border-green-500 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('message') }}
            </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative">
                    <input type="text" wire:model.live="search" placeholder="Cari judul galeri..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#12B4E0]">
                    <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div>
                    <select wire:model.live="filterKategori" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        <option value="">Semua Kategori</option>
                        <option value="kegiatan">Kegiatan</option>
                        <option value="lomba">Lomba</option>
                        <option value="pembelajaran">Pembelajaran</option>
                        <option value="kunjungan">Kunjungan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <select wire:model.live="filterStatus" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        <option value="">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Grid Gallery --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($galeriList as $item)
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-100 dark:bg-gray-800">
                        @if($item->gambar)
                            <img src="{{ Storage::url($item->gambar) }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @endif
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md {{ $item->kategori_badge_color }}">
                                {{ $item->kategori }}
                            </span>
                        </div>
                        {{-- Image Count Badge --}}
                        <div class="absolute bottom-2 right-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded-md flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $item->images_count ?? $item->images->count() }} Foto
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 dark:text-gray-50 truncate mb-1">{{ $item->judul }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">{{ $item->created_at->format('d M Y') }}</p>
                        
                        <div class="flex items-center gap-2">
                            <button wire:click="openDetailModal({{ $item->id }})" class="flex-1 py-2 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-xs font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">Detail</button>
                            <button wire:click="openEditModal({{ $item->id }})" class="p-2 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:bg-blue-50 dark:bg-blue-500//20 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus galeri ini?" class="p-2 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:bg-red-50 dark:bg-red-500//20 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada galeri yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Form Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-50">{{ $isEditMode ? 'Edit Galeri' : 'Tambah Galeri Baru' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 dark:text-gray-400 hover:text-gray-600 dark:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            {{-- Judul --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Judul Galeri *</label>
                                <input type="text" wire:model="judul" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#12B4E0]" placeholder="Masukkan judul...">
                                @error('judul') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Kategori *</label>
                                <select wire:model="kategori" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                                    <option value="kegiatan">Kegiatan</option>
                                    <option value="lomba">Lomba</option>
                                    <option value="pembelajaran">Pembelajaran</option>
                                    <option value="kunjungan">Kunjungan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Status Publikasi *</label>
                                <select wire:model="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            {{-- Deskripsi --}}
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Deskripsi Ringkas</label>
                            <textarea wire:model="deskripsi" rows="8" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#12B4E0]" placeholder="Ceritakan tentang foto-foto ini..."></textarea>
                        </div>
                    </div>

                    {{-- Multi Image Upload --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Pilih Foto-Foto (Bisa pilih banyak) *</label>
                        <div 
                            class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-[#12B4E0] transition-colors cursor-pointer relative bg-gray-50 dark:bg-gray-800"
                            x-data="{ isUploading: false }"
                            x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                        >
                            <input type="file" wire:model="bulkImages" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Klik untuk memilih foto-foto</p>
                            <div x-show="isUploading" class="mt-4">
                                <div class="w-full bg-gray-200 rounded-full h-1">
                                    <div class="bg-blue-600 h-1 rounded-full animate-pulse" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        @error('bulkImages') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Previews --}}
                    @if($bulkImages)
                        <div class="grid grid-cols-6 gap-2 mt-2">
                            @foreach($bulkImages as $image)
                                <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </form>

                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 flex justify-end gap-3">
                    <button wire:click="closeModal" class="px-6 py-2 text-gray-600 dark:text-gray-300 font-semibold">Batal</button>
                    <button wire:click="save" class="px-10 py-2 bg-[#12B4E0] text-white rounded-lg font-bold hover:bg-[#0e91b8] transition flex items-center gap-2" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Album</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Detail Modal --}}
    @if($showDetailModal && $detailGaleri)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80">
            <div class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-50">{{ $detailGaleri->judul }}</h3>
                    <button wire:click="closeDetailModal" class="text-gray-400 dark:text-gray-400 hover:text-gray-600 dark:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="mb-6 prose prose-sm max-w-none">
                        {!! $detailGaleri->deskripsi_html !!}
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($detailGaleri->images as $img)
                            <a href="{{ Storage::url($img->file_path) }}" target="_blank" class="aspect-[4/3] rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 group">
                                <img src="{{ Storage::url($img->file_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>