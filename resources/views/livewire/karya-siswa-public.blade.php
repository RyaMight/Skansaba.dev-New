<div class="min-h-screen bg-slate-100 dark:bg-gray-950">
    {{-- Header ringkas: judul, subjudul, dan pencarian dalam satu blok. Tab lama dihapus: navigasi Karya/Profil kini lewat menu navbar. --}}
    <section class="bg-slate-100 dark:bg-gray-950">
        <div class="site-container py-8 lg:py-11">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h1 class="type-page-title text-slate-900 dark:text-gray-50 leading-tight mb-2">
                        {{ $activeTab === 'profil' ? __('Profil Siswa') : __('Karya Siswa') }}
                    </h1>
                    <p class="type-lead text-gray-600 dark:text-gray-300">
                        {{ $activeTab === 'profil' ? __('Kenali siswa dan keahlian Rekayasa Perangkat Lunak') : __('Kumpulan karya dan proyek siswa Rekayasa Perangkat Lunak') }}
                    </p>
                </div>

                <div class="w-full lg:w-80 shrink-0">
                    <label for="karya-search" class="sr-only">
                        {{ $activeTab === 'profil' ? __('Cari nama siswa, keahlian, atau username...') : __('Cari judul karya, teknologi, atau pembuat...') }}
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
                            class="w-full pl-10 pr-10 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:border-transparent transition-colors"
                            style="--tw-ring-color: {{ $globalSettings->primary_color_style }}33"
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
            </div>

            @if($activeTab !== 'profil')
                {{-- Urutkan Karya: di dalam header, rata kanan sejajar kolom pencarian. --}}
                <div class="mt-4 flex flex-wrap items-center justify-end gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-gray-400">{{ __('Urutkan') }}</span>
                    <button
                        type="button"
                        wire:click="$set('sort', 'terpopuler')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $sort === 'terpopuler' ? 'bg-sky-600 text-white border-sky-600 shadow-xs' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:border-sky-300' }}"
                    >
                        {{ __('Terpopuler') }}
                    </button>
                    <button
                        type="button"
                        wire:click="$set('sort', 'terbaru')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $sort === 'terbaru' ? 'bg-sky-600 text-white border-sky-600 shadow-xs' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:border-sky-300' }}"
                    >
                        {{ __('Terbaru') }}
                    </button>
                </div>
            @endif
        </div>
    </section>

    {{-- Konten: Karya Siswa atau Profil Siswa sesuai tab aktif dari query string --}}
    <section class="pb-12 lg:pb-16">
        <div class="site-container">
            @if($activeTab === 'profil')
                @include('livewire.partials.profil-grid')
            @else
                @include('livewire.partials.karya-grid')
            @endif
        </div>
    </section>
</div>
