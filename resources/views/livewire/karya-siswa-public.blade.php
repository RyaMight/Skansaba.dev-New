<div class="min-h-screen bg-[#f8fafc] dark:bg-gray-950 py-8 lg:py-12" x-data="{ tab: @entangle('activeTab').live }">
    <div class="site-container">
        {{-- Breadcrumb di bagian atas --}}
        <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-6">
            <a href="{{ route('beranda') }}" class="inline-flex items-center gap-1.5 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <span>{{ __('Beranda') }}</span>
            </a>
            <span>›</span>
            <span style="color: {{ $globalSettings->primary_color_style }}" class="font-medium" x-text="tab === 'profil' ? @js(__('Profil Siswa')) : @js(__('Karya Siswa'))">
                {{ __('Karya Siswa') }}
            </span>
        </div>

        {{-- Tab Switcher Header (Identik persis dengan Gambar 0 & Gambar 1) --}}
        <div class="flex items-center justify-center gap-4 sm:gap-14 mb-8 select-none">
            {{-- Tab Karya Siswa --}}
            <button
                type="button"
                @click="tab = 'karya'"
                :class="tab === 'karya'
                    ? 'text-slate-900 dark:text-white font-bold border-b-4 border-slate-900 dark:border-white'
                    : 'text-slate-400/90 dark:text-gray-500 font-bold border-b-4 border-transparent hover:text-slate-600 dark:hover:text-gray-300'"
                class="type-section-title pb-2 transition-all cursor-pointer relative whitespace-nowrap"
            >
                {{ __('Karya Siswa') }}
            </button>

            {{-- Tab Profil Siswa --}}
            <button
                type="button"
                @click="tab = 'profil'"
                :class="tab === 'profil'
                    ? 'text-slate-900 dark:text-white font-bold border-b-4 border-slate-900 dark:border-white'
                    : 'text-slate-400/90 dark:text-gray-500 font-bold border-b-4 border-transparent hover:text-slate-600 dark:hover:text-gray-300'"
                class="type-section-title pb-2 transition-all cursor-pointer relative whitespace-nowrap"
            >
                {{ __('Profil Siswa') }}
            </button>
        </div>

        {{-- Search Input Bar Terpusat --}}
        <div class="max-w-xl mx-auto mb-8">
            <div class="relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    :placeholder="tab === 'profil' ? @js(__('Cari nama siswa, keahlian, atau username...')) : @js(__('Cari judul karya, teknologi, atau pembuat...'))"
                    class="w-full pl-10 pr-10 py-2.5 bg-white dark:bg-gray-900 border border-slate-200/90 dark:border-gray-800 rounded-2xl text-sm text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-sky-500 focus:border-transparent shadow-xs transition"
                >
                @if($search !== '')
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-gray-200 p-0.5 rounded transition-colors cursor-pointer"
                        title="{{ __('Bersihkan pencarian') }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        {{-- Konten Tab --}}
        <div>
            {{-- Tab 1: Karya Siswa --}}
            <div x-show="tab === 'karya'" x-transition.opacity.duration.200ms>
                @include('livewire.partials.karya-grid')
            </div>

            {{-- Tab 2: Profil Siswa --}}
            <div x-show="tab === 'profil'" x-cloak x-transition.opacity.duration.200ms>
                @include('livewire.partials.profil-grid')
            </div>
        </div>
    </div>
</div>
