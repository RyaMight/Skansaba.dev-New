<div class="min-h-screen bg-slate-50 dark:bg-gray-950" x-data="{ tab: @entangle('activeTab').live }">
    {{-- Header dengan Breadcrumb --}}
    <section class="bg-white dark:bg-gray-900 border-b border-slate-200/80 dark:border-gray-800 py-8 lg:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            {{-- Breadcrumb --}}
            <div class="flex items-center justify-center gap-2 text-xs sm:text-sm text-slate-500 dark:text-gray-400 mb-3">
                <a href="{{ route('beranda') }}" class="inline-flex items-center gap-1.5 hover:text-slate-900 dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <span>{{ __('Beranda') }}</span>
                </a>
                <span>/</span>
                <span class="font-medium" style="color: {{ $globalSettings->primary_color_style }}">{{ __('Karya & Profil Siswa') }}</span>
            </div>

            <h1 class="text-3xl lg:text-4xl font-bold text-slate-900 dark:text-gray-50 tracking-tight mb-2.5">
                {{ __('Karya &') }} <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Profil Siswa') }}</span>
            </h1>
            <p class="text-slate-600 dark:text-gray-400 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                {{ __('Direktori proyek perangkat lunak dan portofolio talenta siswa Rekayasa Perangkat Lunak SMKN 1 Bantul.') }}
            </p>

            {{-- Search & Quick Summary Bar --}}
            <div class="mt-6 max-w-2xl mx-auto">
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        :placeholder="tab === 'profil' ? @js(__('Cari nama siswa, username, atau keahlian...')) : @js(__('Cari judul karya, teknologi, atau nama pembuat...'))"
                        class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-gray-800/90 border border-slate-200 dark:border-gray-700 rounded-lg text-sm text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:border-transparent transition"
                        style="--tw-ring-color: {{ $globalSettings->primary_color_style }}"
                    >
                    @if($search !== '')
                        <button
                            type="button"
                            wire:click="$set('search', '')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-gray-200 p-0.5 rounded transition-colors"
                            title="{{ __('Bersihkan pencarian') }}"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-8 lg:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            {{-- Top Showcase (2 Featured Projects) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                {{-- Showcase 1: Sistem Informasi Manajemen Sekolah --}}
                <div class="bg-white dark:bg-gray-900 rounded-xl p-5 lg:p-6 shadow-xs border border-slate-200/80 dark:border-gray-800 flex flex-col md:flex-row items-center gap-6">
                    {{-- Web Browser Mockup --}}
                    <div class="w-full md:w-[300px] bg-slate-100 dark:bg-[#161a23] rounded-xl p-2.5 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white shrink-0 select-none shadow-xs">
                        {{-- Browser Window Top Bar --}}
                        <div class="flex items-center gap-2 mb-2 px-1">
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="w-2 h-2 rounded-full bg-[#ff5f56]"></span>
                                <span class="w-2 h-2 rounded-full bg-[#ffbd2e]"></span>
                                <span class="w-2 h-2 rounded-full bg-[#27c93f]"></span>
                            </div>
                            <div class="bg-white dark:bg-[#242b3a] text-slate-600 dark:text-slate-400 border border-slate-200/90 dark:border-transparent text-[9px] px-2.5 py-0.5 rounded flex-1 text-center font-mono truncate">
                                https://sims.skansaba.dev/portal
                            </div>
                        </div>

                        {{-- Browser Body Content --}}
                        <div class="flex bg-white dark:bg-[#161a23] rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800/80">
                            {{-- Mini Sidebar --}}
                            <div class="w-20 bg-slate-50 dark:bg-[#11141c] p-2 flex flex-col gap-1.5 shrink-0 border-r border-slate-200 dark:border-slate-800/60">
                                <div class="text-[8px] font-bold text-sky-600 dark:text-sky-400 truncate">SMK EduTech</div>
                                <div class="bg-sky-600 text-[8px] text-white px-1.5 py-1 rounded font-medium mt-1">
                                    Dashboard
                                </div>
                                <div class="space-y-1.5 mt-1">
                                    <div class="h-1 bg-slate-200 dark:bg-slate-700/60 rounded w-10"></div>
                                    <div class="h-1 bg-slate-200 dark:bg-slate-700/60 rounded w-12"></div>
                                    <div class="h-1 bg-slate-200 dark:bg-slate-700/60 rounded w-8"></div>
                                </div>
                            </div>

                            {{-- Mini Dashboard --}}
                            <div class="flex-1 bg-white dark:bg-gray-900 p-2.5 text-slate-800">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-[9px] font-bold text-slate-800 dark:text-gray-100 leading-tight">Ringkasan Akademik</div>
                                    <span class="text-[7px] text-slate-500 bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 px-1 py-0.5 rounded leading-none">Genap</span>
                                </div>

                                {{-- 4 Metric Cards --}}
                                <div class="grid grid-cols-4 gap-1 mb-2">
                                    <div class="bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700 p-1 rounded text-center">
                                        <div class="text-[6px] text-slate-400 leading-none">Siswa</div>
                                        <div class="text-[8px] font-bold text-slate-800 dark:text-gray-100 leading-tight mt-0.5">1,240</div>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700 p-1 rounded text-center">
                                        <div class="text-[6px] text-slate-400 leading-none">Guru</div>
                                        <div class="text-[8px] font-bold text-slate-800 dark:text-gray-100 leading-tight mt-0.5">96</div>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700 p-1 rounded text-center">
                                        <div class="text-[6px] text-slate-400 leading-none">Rombel</div>
                                        <div class="text-[8px] font-bold text-slate-800 dark:text-gray-100 leading-tight mt-0.5">36</div>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700 p-1 rounded text-center">
                                        <div class="text-[6px] text-slate-400 leading-none">Hadir</div>
                                        <div class="text-[8px] font-bold text-emerald-600 leading-tight mt-0.5">98.4%</div>
                                    </div>
                                </div>

                                {{-- Chart Visuals --}}
                                <div class="flex items-end justify-between gap-2 pt-1.5 border-t border-slate-100 dark:border-gray-800">
                                    <div class="flex items-end gap-1 h-9">
                                        <div class="w-1.5 bg-sky-400 rounded-t h-4"></div>
                                        <div class="w-1.5 bg-sky-500 rounded-t h-6"></div>
                                        <div class="w-1.5 bg-sky-400 rounded-t h-3"></div>
                                        <div class="w-1.5 bg-sky-600 rounded-t h-8"></div>
                                        <div class="w-1.5 bg-sky-500 rounded-t h-5"></div>
                                        <div class="w-1.5 bg-sky-600 rounded-t h-7"></div>
                                    </div>
                                    <div class="relative w-8 h-8 flex items-center justify-center shrink-0">
                                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                            <circle cx="18" cy="18" r="14" fill="none" class="stroke-slate-200 dark:stroke-slate-700" stroke-width="4"></circle>
                                            <circle cx="18" cy="18" r="14" fill="none" stroke="#0ea5e9" stroke-width="4" stroke-dasharray="88" stroke-dashoffset="20" stroke-linecap="round"></circle>
                                        </svg>
                                        <span class="absolute text-[8px] font-bold text-slate-700 dark:text-gray-200">78%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Project Details Kanan --}}
                    <div class="flex-1 flex flex-col justify-center items-start">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-300 border border-sky-200/70 dark:border-sky-500/20 text-[11px] font-semibold mb-2.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                            </svg>
                            <span>{{ __('Unggulan Web Enterprise') }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-gray-50 tracking-tight leading-snug">
                            {{ __('Sistem Informasi Manajemen Sekolah') }}
                        </h3>
                        <p class="text-xs text-sky-600 dark:text-sky-400 font-medium mt-1">Laravel 12 · Livewire · Tailwind CSS · MySQL</p>
                        <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed mt-2.5">
                            {{ __('Platform terintegrasi untuk pengelolaan presensi akademik, penilaian rapor semester, dan pemantauan aktivitas belajar mengajar secara real-time.') }}
                        </p>
                    </div>
                </div>

                {{-- Showcase 2: Aplikasi E-Commerce Lokal --}}
                <div class="bg-white dark:bg-gray-900 rounded-xl p-5 lg:p-6 shadow-xs border border-slate-200/80 dark:border-gray-800 flex flex-col md:flex-row items-center gap-6">
                    {{-- 2 Smartphone Mockup --}}
                    <div class="w-full md:w-[300px] flex items-center justify-center gap-3 shrink-0 select-none">
                        {{-- Phone 1 --}}
                        <div class="w-[120px] bg-slate-900 dark:bg-[#141822] rounded-2xl p-1.5 border border-slate-800 shrink-0 shadow-md">
                            <div class="bg-white dark:bg-gray-900 rounded-xl p-2 flex flex-col justify-between h-[165px] overflow-hidden">
                                <div>
                                    <div class="flex items-center justify-between pb-1 border-b border-slate-100 dark:border-gray-800">
                                        <span class="text-[8px] font-bold text-slate-800 dark:text-gray-100">SkansabaMart</span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    </div>
                                    <div class="bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-200/60 dark:border-sky-500/20 text-center py-1.5 rounded font-semibold text-[8px] my-2">
                                        Katalog UMKM Bantul
                                    </div>
                                    <div class="grid grid-cols-2 gap-1">
                                        <div class="bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700 rounded p-1.5 h-10 flex items-center justify-center text-[7px] text-slate-600 dark:text-gray-300 font-medium">
                                            Kriya
                                        </div>
                                        <div class="bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700 rounded p-1.5 h-10 flex items-center justify-center text-[7px] text-slate-600 dark:text-gray-300 font-medium">
                                            Kuliner
                                        </div>
                                    </div>
                                </div>
                                <div class="w-6 h-0.5 bg-slate-300 dark:bg-gray-700 rounded-full mx-auto"></div>
                            </div>
                        </div>

                        {{-- Phone 2 --}}
                        <div class="w-[120px] bg-slate-900 dark:bg-[#141822] rounded-2xl p-1.5 border border-slate-800 shrink-0 shadow-md">
                            <div class="bg-white dark:bg-gray-900 rounded-xl p-2 flex flex-col justify-between h-[165px] overflow-hidden">
                                <div>
                                    <div class="flex items-center justify-between pb-1 border-b border-slate-100 dark:border-gray-800">
                                        <span class="text-[8px] font-bold text-slate-800 dark:text-gray-100">Checkout</span>
                                        <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/>
                                        </svg>
                                    </div>
                                    <div class="grid grid-cols-2 gap-1 my-2">
                                        <div class="bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700 rounded p-1 text-center">
                                            <div class="w-full h-5 bg-slate-200 dark:bg-gray-700 rounded mb-0.5"></div>
                                            <div class="text-[6px] text-slate-700 dark:text-gray-200 leading-tight">Batik Tulis</div>
                                            <div class="text-[6px] text-slate-400 font-medium">Rp 120k</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700 rounded p-1 text-center">
                                            <div class="w-full h-5 bg-slate-200 dark:bg-gray-700 rounded mb-0.5"></div>
                                            <div class="text-[6px] text-slate-700 dark:text-gray-200 leading-tight">Tas Rajut</div>
                                            <div class="text-[6px] text-slate-400 font-medium">Rp 85k</div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="bg-sky-600 text-white text-center py-1 rounded text-[7px] font-medium mb-1">
                                        Bayar QRIS
                                    </div>
                                    <div class="w-6 h-0.5 bg-slate-300 dark:bg-gray-700 rounded-full mx-auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Project Details Kanan --}}
                    <div class="flex-1 flex flex-col justify-center items-start">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-200/70 dark:border-emerald-500/20 text-[11px] font-semibold mb-2.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                                <line x1="12" y1="18" x2="12.01" y2="18"></line>
                            </svg>
                            <span>{{ __('Unggulan Aplikasi Mobile') }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-gray-50 tracking-tight leading-snug">
                            {{ __('Aplikasi Niaga & Katalog UMKM Lokal') }}
                        </h3>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-1">Flutter · Laravel REST API · Payment Gateway</p>
                        <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed mt-2.5">
                            {{ __('Aplikasi mobile lintas platform untuk membantu pelaku UMKM memasarkan produk lokal dengan manajemen stok otomatis dan pembayaran QRIS.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tab Navigation: Karya Siswa & Profil Siswa --}}
            <div class="mt-10">
                <div class="border-b border-slate-200 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-8">
                        <button
                            type="button"
                            @click="tab = 'karya'"
                            :class="tab === 'karya'
                                ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400 font-bold'
                                : 'text-slate-500 dark:text-gray-400 border-transparent hover:text-slate-700 dark:hover:text-gray-200 font-semibold'"
                            class="relative pb-3.5 border-b-2 text-base sm:text-lg tracking-tight transition-colors duration-150 inline-flex items-center gap-2.5 cursor-pointer"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                            <span>{{ __('Karya Siswa') }}</span>
                            <span
                                :class="tab === 'karya'
                                    ? 'bg-sky-50 text-sky-700 border-sky-200/80 dark:bg-sky-500/20 dark:text-sky-300 dark:border-sky-500/30'
                                    : 'bg-slate-100 text-slate-600 dark:bg-gray-800 dark:text-gray-400 border-slate-200 dark:border-gray-700'"
                                class="px-2 py-0.5 text-xs font-semibold rounded-md border transition-colors"
                            >
                                {{ $totalKaryaCount }}
                            </span>
                        </button>

                        <button
                            type="button"
                            @click="tab = 'profil'"
                            :class="tab === 'profil'
                                ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400 font-bold'
                                : 'text-slate-500 dark:text-gray-400 border-transparent hover:text-slate-700 dark:hover:text-gray-200 font-semibold'"
                            class="relative pb-3.5 border-b-2 text-base sm:text-lg tracking-tight transition-colors duration-150 inline-flex items-center gap-2.5 cursor-pointer"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ __('Profil Siswa') }}</span>
                            <span
                                :class="tab === 'profil'
                                    ? 'bg-sky-50 text-sky-700 border-sky-200/80 dark:bg-sky-500/20 dark:text-sky-300 dark:border-sky-500/30'
                                    : 'bg-slate-100 text-slate-600 dark:bg-gray-800 dark:text-gray-400 border-slate-200 dark:border-gray-700'"
                                class="px-2 py-0.5 text-xs font-semibold rounded-md border transition-colors"
                            >
                                {{ $siswas->count() }}
                            </span>
                        </button>
                    </div>

                    {{-- Contextual Info Right --}}
                    <div class="pb-3 text-xs text-slate-500 dark:text-gray-400 flex items-center gap-2">
                        <template x-if="tab === 'karya'">
                            <span>{{ __('Menampilkan') }} <strong class="font-semibold text-slate-700 dark:text-gray-200">{{ $karyas->count() }} {{ __('karya terpublikasi') }}</strong> &amp; {{ $dummyKaryas->count() }} {{ __('katalog portofolio') }}</span>
                        </template>
                        <template x-if="tab === 'profil'">
                            <span>{{ __('Direktori') }} <strong class="font-semibold text-slate-700 dark:text-gray-200">{{ $siswas->count() }} {{ __('dari') }} {{ $totalSiswasCount }}</strong> {{ __('akun siswa aktif RPL') }}</span>
                        </template>
                    </div>
                </div>

                {{-- Konten Tab --}}
                <div class="mt-6">
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
    </section>
</div>
