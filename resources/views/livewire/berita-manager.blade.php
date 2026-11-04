<div class="p-6">
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-50">Manajemen Berita</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Kelola berita, pengumuman, dan artikel terbaru.</p>
            </div>
            <button 
                wire:click="openCreateModal"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shadow-sm"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Berita
            </button>
        </div>

        {{-- Alerts --}}
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-500//10 border border-green-500 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('message') }}
            </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Cari Berita</label>
                    <div class="relative">
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text" 
                            placeholder="Cari berdasarkan judul..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        >
                        <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Status</label>
                    <select 
                        wire:model.live="filterStatus"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    >
                        <option value="">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thumbnail & Berita</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Highlight</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Penulis</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tgl Publish</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($beritas as $berita)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-10 rounded overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                            @if($berita->thumbnail)
                                                <img src="{{ Storage::url($berita->thumbnail) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-400">
                                                    <x-icon-image class="w-5 h-5" />
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-50 truncate max-w-xs">{{ $berita->title }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $berita->excerpt }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'px-2 py-1 text-xs font-medium rounded-full',
                                        'bg-yellow-100 dark:bg-yellow-50 dark:bg-yellow-500//20 text-yellow-800' => $berita->status === 'draft',
                                        'bg-green-100 dark:bg-green-50 dark:bg-green-500//20 text-green-800' => $berita->status === 'published',
                                        'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100' => $berita->status === 'archived',
                                    ])>
                                        {{ ucfirst($berita->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($berita->is_highlight)
                                        <span class="text-yellow-500">
                                            <x-icon-star class="w-5 h-5 mx-auto" />
                                        </span>
                                    @else
                                        <span class="text-gray-300">
                                            <x-icon-star class="w-5 h-5 mx-auto" />
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-50 font-medium">{{ $berita->author->name ?? 'System' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Author</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-50">
                                        {{ $berita->published_at ? $berita->published_at->format('d M Y') : '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $berita->published_at ? $berita->published_at->format('H:i') : '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            wire:click="openEditModal({{ $berita->id }})"
                                            class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:bg-blue-500/10 rounded-lg transition-colors"
                                            title="Edit"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="delete({{ $berita->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus berita ini?"
                                            class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:bg-red-500/10 rounded-lg transition-colors"
                                            title="Hapus"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v12a2 2 0 01-2 2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-50">Belum ada berita</p>
                                        <p class="text-gray-500 dark:text-gray-400">Silakan tambahkan berita baru untuk ditampilkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $beritas->links() }}
            </div>
        </div>
    </div>

    {{-- Form Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl max-w-7xl w-full max-h-[90vh] overflow-y-auto">
                <form 
                    x-on:submit.prevent="
                        if (window.imageCropper && window.imageCropper.sendToLivewire) window.imageCropper.sendToLivewire();
                        $wire.save();
                    " 
                >
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-50">
                            {{ $isEditMode ? 'Edit Berita' : 'Tambah Berita' }}
                        </h3>
                        <button type="button" wire:click="closeModal" class="text-gray-400 dark:text-gray-400 hover:text-gray-600 dark:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-6 bg-white dark:bg-gray-900">
                        <div class="space-y-8">
                            {{-- Top Section: Main Content (Full Width) --}}
                            <div class="space-y-6">
                                {{-- Title --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Judul Berita <span class="text-red-500">*</span></label>
                                    <input 
                                        wire:model.live="title"
                                        type="text" 
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Masukkan judul berita..."
                                        required
                                    >
                                    @error('title') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                {{-- Excerpt --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Ringkasan (Excerpt)</label>
                                    <textarea 
                                        wire:model.live="excerpt"
                                        rows="2"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Ringkasan singkat berita..."
                                    ></textarea>
                                    @error('excerpt') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                {{-- Content Markdown Editor --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Isi Berita <span class="text-red-500">*</span></label>
                                    <div class="max-h-[500px] overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg">
                                        <x-markdown-editor 
                                            modelName="content_md" 
                                            placeholder="Tulis berita Anda di sini menggunakan Markdown..."
                                        />
                                    </div>
                                    @error('content_md') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Bottom Section: Settings & Thumbnail (Full Width) --}}
                            <div class="grid grid-cols-1 gap-8 pt-8 border-t border-gray-100 dark:border-gray-800">
                                {{-- Status & Options --}}
                                <div class="space-y-6">
                                    <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-xl space-y-4 border border-gray-100 dark:border-gray-800">
                                        <h4 class="font-bold text-gray-900 dark:text-gray-50 text-sm uppercase tracking-wider">Pengaturan Publikasi</h4>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Status</label>
                                            <select 
                                                wire:model.live="status"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            >
                                                <option value="draft">Draft</option>
                                                <option value="published">Published</option>
                                                <option value="archived">Archived</option>
                                            </select>
                                        </div>

                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <div class="relative inline-flex items-center">
                                                <input type="checkbox" wire:model.live="is_highlight" class="sr-only peer">
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white dark:bg-gray-900 after:border-gray-300 dark:border-gray-600 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:text-blue-400 transition-colors">Tandai sebagai Highlight</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Thumbnail Section --}}
                                <div>
                                    <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-xl border border-gray-100 dark:border-gray-800">
                                        <h4 class="font-bold text-gray-900 dark:text-gray-50 text-sm uppercase tracking-wider mb-4">Thumbnail Berita (16:9)</h4>
                                        <x-image-cropper 
                                            modelName="thumbnail" 
                                            :currentImage="$currentThumbnail"
                                            aspectRatio="1.77777777778"
                                            aspectRatioLabel="16:9"
                                            previewSize="400"
                                        />
                                    </div>
                                    @error('thumbnail') <p class="mt-1 text-sm text-red-600 dark:text-red-400 px-2">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:text-gray-50 font-medium transition-colors">
                            Batal
                        </button>
                        <div class="flex items-center gap-3">
                            <button 
                                type="submit" 
                                wire:loading.attr="disabled"
                                class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all shadow-sm flex items-center gap-2"
                            >
                                <span wire:loading.remove wire:target="save">
                                    {{ $isEditMode ? 'Simpan Perubahan' : 'Terbitkan Berita' }}
                                </span>
                                <span wire:loading wire:target="save" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
