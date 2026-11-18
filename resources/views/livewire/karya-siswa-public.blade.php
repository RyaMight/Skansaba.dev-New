<div class="min-h-screen bg-slate-100 dark:bg-gray-950 py-8 lg:py-12">
    <div class="site-container">

        {{-- Judul halaman. Tab lama dihapus: navigasi Karya/Profil kini lewat menu navbar. --}}
        <header class="mb-8 text-center">
            <h1 class="type-page-title text-slate-900 dark:text-gray-50">
                {{ $activeTab === 'profil' ? __('Profil Siswa') : __('Karya Siswa') }}
            </h1>
        </header>

        {{-- Search Input Bar Terpusat --}}
        <div class="max-w-xl mx-auto mb-8">
            <div class="relative">
                <label for="karya-search" class="sr-only">
                    {{ $activeTab === 'profil' ? __('Cari nama siswa, keahlian, atau username...') : __('Cari judul karya, teknologi, atau pembuat...') }}
                </label>
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    id="karya-search"
                    type="search"
                    name="q"
                    autocomplete="off"
                    spellcheck="false"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ $activeTab === 'profil' ? __('Cari nama siswa, keahlian, atau username...') : __('Cari judul karya, teknologi, atau pembuat...') }}"
                    class="w-full pl-10 pr-10 py-2.5 bg-white dark:bg-gray-900 border border-slate-200/90 dark:border-gray-800 rounded-2xl text-sm text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-sky-500 focus:border-transparent shadow-xs transition"
                >
                @if($search !== '')
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-gray-200 p-0.5 rounded transition-colors cursor-pointer"
                        title="{{ __('Bersihkan pencarian') }}"
                        aria-label="{{ __('Bersihkan pencarian') }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        {{-- Konten: Karya Siswa atau Profil Siswa sesuai tab aktif dari query string --}}
        @if($activeTab === 'profil')
            @include('livewire.partials.profil-grid')
        @else
            @include('livewire.partials.karya-grid')
        @endif
    </div>
</div>
