<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-50">Prospek Karir</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Kelola prospek karir lulusan jurusan RPL</p>
                </div>
                <button wire:click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Prospek Karir
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="mb-6 bg-green-50 dark:bg-green-500/10 border border-green-500 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ session('message') }}</span>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="max-w-md">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Cari</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul atau deskripsi..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>
        </div>

        <!-- Prospek Karir Table -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
            wire:key="prospek-karir-table-container">
            @if (count($prospek_karirs) > 0)
                <div x-data="{
                        items: @js($prospek_karirs),
                        draggedIndex: null,

                        init() {
                            // Watch for Livewire updates to keep items in sync
                            this.$watch('$wire.prospek_karirs', value => {
                                this.items = JSON.parse(JSON.stringify(value));
                            });
                        },

                        dragStart(index) {
                            this.draggedIndex = index;
                        },

                        dragEnd() {
                            this.draggedIndex = null;
                            this.updateOrder();
                        },

                        dragOver(index) {
                            if (this.draggedIndex === null || this.draggedIndex === index) return;

                            const draggedItem = this.items.splice(this.draggedIndex, 1)[0];
                            this.items.splice(index, 0, draggedItem);
                            this.draggedIndex = index;
                        },

                        updateOrder() {
                            const reordered = this.items.map((item, index) => ({
                                id: item.id,
                                order: index
                            }));
                            @this.call('updateOrder', reordered);
                        }
                    }">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">
                                        Urutan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Judul & Icon</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200">
                                <template x-for="(item, index) in items" :key="item.id">
                                    <tr draggable="true" @dragstart="dragStart(index)" @dragend="dragEnd()"
                                        @dragover.prevent="dragOver(index)"
                                        :class="{ 'bg-blue-50 dark:bg-blue-500/10 opacity-50': draggedIndex === index }"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors cursor-move group">

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="text-gray-300 group-hover:text-blue-500 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M4 8h16M4 16h16" />
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-bold text-gray-600 dark:text-gray-300" x-text="index + 1"></span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 mr-4"
                                                    x-show="item.image">
                                                    <img :src="'/storage/' + item.image" :alt="item.title"
                                                        class="w-full h-full object-cover">
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-50"
                                                            x-text="item.title"></div>
                                                        <span x-show="item.icon" class="text-lg" x-text="item.icon"></span>
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-1"
                                                        x-html="item.description_excerpt"></div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" :checked="!!item.is_active" @change="
                                                                item.is_active = $el.checked;
                                                                $wire.toggleActive(item.id)
                                                            " class="sr-only peer">
                                                    <div
                                                        class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white dark:bg-gray-900 after:border-gray-400 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 transition-all">
                                                    </div>
                                                </label>
                                                <span class="text-xs font-bold uppercase tracking-wider min-w-[70px]"
                                                    :class="item.is_active ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-400'"
                                                    x-text="item.is_active ? 'Aktif' : 'Nonaktif'"></span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="$wire.openEditModal(item.id)"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 mr-3 transition-colors"
                                                title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button
                                                @click="if(confirm('Yakin ingin menghapus prospek karir ini?')) $wire.delete(item.id)"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 transition-colors" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="p-20 text-center">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-50">Belum ada prospek karir</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-xs mx-auto">Mulai dengan menambahkan prospek karir pertama untuk
                        ditampilkan di landing page.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4 backdrop-blur-sm transition-all duration-300"
            x-data="{ show: @entangle('showModal') }">
            <div
                class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto custom-scrollbar transform transition-all duration-300 relative">
                <form @submit.prevent="
                            if (window.imageCropper && window.imageCropper.sendToLivewire) window.imageCropper.sendToLivewire();
                            $wire.save();
                        ">
                    <!-- Modal Header (Sticky) -->
                    <div
                        class="sticky top-0 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 px-8 py-5 flex items-center justify-between z-20">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-50">
                                {{ $prospek_karir_id ? 'Edit Prospek Karir' : 'Tambah Prospek Karir' }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola informasi detail prospek karir siswa</p>
                        </div>
                        <button type="button" @click="$wire.closeModal()"
                            class="text-gray-400 dark:text-gray-400 hover:text-gray-600 dark:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <div class="max-w-4xl mx-auto space-y-10">
                            <!-- Section: Basic Information -->
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                            Judul Prospek <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" wire:model="title"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="Contoh: Full Stack Developer">
                                        @error('title')
                                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                            Icon (Emoji)
                                        </label>
                                        <input type="text" wire:model="icon"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="💻, 🎨, 📱">
                                        @error('icon')
                                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                            Urutan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" wire:model="order"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        @error('order')
                                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div
                                    class="p-4 bg-blue-50 dark:bg-blue-500/10 rounded-xl border border-blue-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white dark:bg-gray-900 after:border-gray-400 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 transition-all">
                                            </div>
                                        </label>
                                        <div>
                                            <span class="block text-sm font-bold text-blue-900">Status Aktif</span>
                                            <span class="block text-xs text-blue-600 dark:text-blue-400">Munculkan di halaman landing page
                                                publik</span>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-white dark:bg-gray-900 rounded-lg text-xs font-bold uppercase tracking-wider"
                                        :class="$wire.is_active ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-400'"
                                        x-text="$wire.is_active ? 'Aktif' : 'Draft'"></span>
                                </div>
                            </div>

                            <!-- Section: Representative Image -->
                            <div class="pt-6 border-t border-gray-100 dark:border-gray-800">
                                <div class="mb-4 text-center">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-gray-50 uppercase tracking-widest">Gambar
                                        Representatif</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gunakan rasio 4:3 untuk hasil terbaik pada grid
                                        prospek.</p>
                                </div>
                                <x-image-cropper modelName="new_image" label="" :aspectRatio="4 / 3"
                                    aspectRatioLabel="4:3" :maxWidth="800" :maxHeight="600" :currentImage="$image"
                                    :previewSize="200" />
                            </div>

                            <!-- Section: Career Description -->
                            <div class="pt-6 border-t border-gray-100 dark:border-gray-800">
                                <div class="mb-4">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-gray-50 uppercase tracking-widest">Deskripsi
                                        Prospek</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tuliskan detail pekerjaan, gaji, dan peluang
                                        masa depan.</p>
                                </div>
                                <x-markdown-editor modelName="description" label=""
                                    placeholder="Jelaskan mengenai prospek karir ini..." :rows="12" :required="true" />
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer (Sticky) -->
                    <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-8 py-5 flex justify-end gap-3 z-20">
                        <button type="button" @click="$wire.closeModal()"
                            class="px-6 py-2.5 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 font-bold rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-10 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 focus:outline-none transition-all shadow-lg shadow-blue-600/20 flex items-center gap-2">
                            <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</div>