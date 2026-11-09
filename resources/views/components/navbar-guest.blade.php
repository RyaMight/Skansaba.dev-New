@php
    $isBerandaActive = request()->routeIs('beranda') || request()->is('/');

    $isStrukturActive = request()->routeIs('struktur-organisasi.*') || request()->is('struktur-organisasi*');
    $isFasilitasActive = request()->routeIs('fasilitas.*') || request()->is('fasilitas*');
    $isProfilActive = $isStrukturActive || $isFasilitasActive || request()->routeIs('profil.*') || request()->is('profil*');

    $isPrestasiActive = request()->routeIs('prestasi.*') || request()->is('prestasi*');
    $isKaryaActive = request()->routeIs('karya.*') || request()->is('karya*');
    $isAlumniActive = request()->routeIs('alumni.*') || request()->is('alumni*');
    $isSiswaActive = $isPrestasiActive || $isKaryaActive || $isAlumniActive;

    $isUnitProduksiActive = request()->routeIs('unit-produksi*') || request()->is('unit-produksi*');

    $isBeritaActive = request()->routeIs('berita.*') || request()->is('berita*');
    $isDokumenActive = request()->routeIs('dokumen.*') || request()->is('dokumen*');
    $isGalleryActive = request()->routeIs('gallery.*') || request()->is('gallery*');
    $isInformasiActive = $isBeritaActive || $isDokumenActive || $isGalleryActive;
@endphp
<header class="sticky top-0 z-50 pt-4 px-4 sm:px-6 notranslate">
    <nav class="max-w-6xl mx-auto bg-white dark:bg-gray-900 dark:border-gray-800 rounded-2xl shadow-[0_2px_16px_rgba(15,23,42,0.08)] border border-gray-100 dark:border-gray-800">
        <div class="flex items-center justify-between h-16 px-5 sm:px-7">
            {{-- Logo & Brand --}}
            <a href="/" class="flex items-center gap-2.5 flex-shrink-0">
                @if($globalSettings->logo_primary_url)
                    <img src="{{ $globalSettings->logo_primary_url }}" alt="{{ $globalSettings->site_name }}" class="h-9 w-auto">
                @else
                    <svg class="w-8 h-8" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25-9 5.25m18 0l-9 5.25m9-5.25v9l-9 5.25m-9-14.25l9 5.25m-9-5.25v9l9 5.25" />
                    </svg>
                @endif
                <span class="type-card-title uppercase" style="color: {{ $globalSettings->primary_color_style }}">{{ $globalSettings->site_name ?? 'Skansaba.dev' }}</span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-7">
                <a href="/" @class([
                    'link-underline text-sm transition hover-primary',
                    'font-semibold text-gray-900 dark:text-gray-100' => $isBerandaActive,
                    'font-semibold text-gray-500 dark:text-gray-400' => !$isBerandaActive,
                ])>{{ __('Beranda') }}</a>

                {{-- Profil Dropdown --}}
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" @class([
                        'link-underline flex items-center gap-1 text-sm transition hover-primary cursor-pointer',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isProfilActive,
                        'font-semibold text-gray-500 dark:text-gray-400' => !$isProfilActive,
                    ])>
                        {{ __('Profil') }}
                        <svg class="chevron-swap w-3.5 h-3.5" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:enter-start="opacity-0 -translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition duration-150 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 -translate-y-1 scale-95" class="nav-dropdown absolute top-full mt-3 w-48 origin-top bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-100 dark:border-gray-800 py-2">
                        <a href="/struktur-organisasi" @class([
                            'nav-dd-item block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 hover-primary',
                            'font-semibold text-gray-900 dark:text-gray-100 bg-gray-50/80 dark:bg-gray-800/50' => $isStrukturActive,
                            'text-gray-600 dark:text-gray-300' => !$isStrukturActive,
                        ])>{{ __('Struktur Organisasi') }}</a>
                        <a href="/fasilitas" @class([
                            'nav-dd-item block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 hover-primary',
                            'font-semibold text-gray-900 dark:text-gray-100 bg-gray-50/80 dark:bg-gray-800/50' => $isFasilitasActive,
                            'text-gray-600 dark:text-gray-300' => !$isFasilitasActive,
                        ])>{{ __('Fasilitas') }}</a>
                    </div>
                </div>

                {{-- Siswa Dropdown --}}
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" @class([
                        'link-underline flex items-center gap-1 text-sm transition hover-primary cursor-pointer',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isSiswaActive,
                        'font-semibold text-gray-500 dark:text-gray-400' => !$isSiswaActive,
                    ])>
                        {{ __('Siswa') }}
                        <svg class="chevron-swap w-3.5 h-3.5" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:enter-start="opacity-0 -translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition duration-150 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 -translate-y-1 scale-95" class="nav-dropdown absolute top-full mt-3 w-48 origin-top bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-100 dark:border-gray-800 py-2">
                        <a href="/prestasi" @class([
                            'nav-dd-item block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 hover-primary',
                            'font-semibold text-gray-900 dark:text-gray-100 bg-gray-50/80 dark:bg-gray-800/50' => $isPrestasiActive,
                            'text-gray-600 dark:text-gray-300' => !$isPrestasiActive,
                        ])>{{ __('Prestasi') }}</a>
                        <a href="/karya" @class([
                            'nav-dd-item block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 hover-primary',
                            'font-semibold text-gray-900 dark:text-gray-100 bg-gray-50/80 dark:bg-gray-800/50' => $isKaryaActive,
                            'text-gray-600 dark:text-gray-300' => !$isKaryaActive,
                        ])>{{ __('Karya Siswa') }}</a>
                        <a href="/alumni" @class([
                            'nav-dd-item block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 hover-primary',
                            'font-semibold text-gray-900 dark:text-gray-100 bg-gray-50/80 dark:bg-gray-800/50' => $isAlumniActive,
                            'text-gray-600 dark:text-gray-300' => !$isAlumniActive,
                        ])>{{ __('Alumni') }}</a>
                    </div>
                </div>

                <a href="/unit-produksi" @class([
                    'link-underline text-sm transition hover-primary',
                    'font-semibold text-gray-900 dark:text-gray-100' => $isUnitProduksiActive,
                    'font-semibold text-gray-500 dark:text-gray-400' => !$isUnitProduksiActive,
                ])>{{ __('Unit Produksi') }}</a>

                {{-- Informasi Dropdown --}}
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" @class([
                        'link-underline flex items-center gap-1 text-sm transition hover-primary cursor-pointer',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isInformasiActive,
                        'font-semibold text-gray-500 dark:text-gray-400' => !$isInformasiActive,
                    ])>
                        {{ __('Informasi') }}
                        <svg class="chevron-swap w-3.5 h-3.5" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:enter-start="opacity-0 -translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition duration-150 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 -translate-y-1 scale-95" class="nav-dropdown absolute top-full mt-3 w-48 origin-top bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-100 dark:border-gray-800 py-2">
                        <a href="/berita" @class([
                            'nav-dd-item block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 hover-primary',
                            'font-semibold text-gray-900 dark:text-gray-100 bg-gray-50/80 dark:bg-gray-800/50' => $isBeritaActive,
                            'text-gray-600 dark:text-gray-300' => !$isBeritaActive,
                        ])>{{ __('Berita') }}</a>
                        <a href="/dokumen" @class([
                            'nav-dd-item block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 hover-primary',
                            'font-semibold text-gray-900 dark:text-gray-100 bg-gray-50/80 dark:bg-gray-800/50' => $isDokumenActive,
                            'text-gray-600 dark:text-gray-300' => !$isDokumenActive,
                        ])>{{ __('Dokumen') }}</a>
                        <a href="/gallery" @class([
                            'nav-dd-item block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 hover-primary',
                            'font-semibold text-gray-900 dark:text-gray-100 bg-gray-50/80 dark:bg-gray-800/50' => $isGalleryActive,
                            'text-gray-600 dark:text-gray-300' => !$isGalleryActive,
                        ])>{{ __('Gallery') }}</a>
                    </div>
                </div>
            </div>

            {{-- Blok kanan: Language Switcher + Theme Toggle + Akun --}}
            <div class="hidden lg:flex items-center gap-3">
                <x-language-switcher />
                <div class="mr-[20px]">
                    <x-theme-toggle />
                </div>

                @guest
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <a href="/login" class="link-underline font-semibold text-gray-700 dark:text-gray-200 hover-primary transition">{{ __('Masuk') }}</a>
                    </div>
                @else
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        {{-- Avatar (foto profil) --}}
                        <button @click="open = !open" class="flex items-center gap-2 group cursor-pointer" title="{{ __('Akun Saya') }}">
                            @if(auth()->user()->photo_url)
                                <img 
                                    src="{{ auth()->user()->photo_url }}" 
                                    alt="{{ auth()->user()->name }}" 
                                    class="w-9 h-9 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700 group-hover:border-[var(--primary-color)] transition"
                                >
                            @else
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-semibold border-2 border-gray-200 dark:border-gray-700 group-hover:border-[var(--primary-color)] transition" style="background-color: {{ $globalSettings->primary_color_style }}">
                                    {{ auth()->user()->initials() }}
                                </div>
                            @endif
                            <svg class="chevron-swap w-4 h-4 text-gray-400 dark:text-gray-400" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Akun --}}
                        <div x-show="open" x-transition:enter="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:enter-start="opacity-0 -translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition duration-150 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 -translate-y-1 scale-95" class="nav-dropdown absolute right-0 top-full mt-2 w-60 origin-top-right bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-100 dark:border-gray-800 py-2 z-50">
                            <div class="nav-dd-item px-4 py-2 border-b border-gray-100 dark:border-gray-800">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a 
                                href="{{ auth()->user()->role === 'siswa' ? route('siswa.profil') : route('dashboard') }}" 
                                class="nav-dd-item flex items-center gap-2 px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover-primary transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ auth()->user()->role === 'siswa' ? __('Profil Saya') : __('Dashboard') }}
                            </a>
                            @if(auth()->user()->role === 'siswa')
                                <a 
                                    href="{{ auth()->user()->profil_url }}" 
                                    target="_blank"
                                    class="nav-dd-item flex items-center gap-2 px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover-primary transition"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    {{ __('Profil Publik Saya') }}
                                </a>
                            @endif
                            <button 
                                @click="open = false; $dispatch('confirm-logout')" 
                                class="nav-dd-item w-full flex items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 dark:bg-rose-500/10 transition cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                {{ __('Logout') }}
                            </button>
                        </div>
                    </div>
                @endguest
            </div>

            {{-- Mobile Actions: Theme Toggle + Menu Button --}}
            <div class="lg:hidden flex items-center gap-2">
                <x-theme-toggle />
                <button
                    x-data="{ open: false }"
                    @toggle-mobile-menu.window="open = !open"
                    type="button"
                    @click="$dispatch('toggle-mobile-menu')"
                    :aria-label="open ? '{{ __('Tutup menu') }}' : '{{ __('Buka menu') }}'"
                    class="w-10 h-10 min-w-[40px] min-h-[40px] flex items-center justify-center text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800 active:scale-95 active:bg-gray-200 dark:active:bg-gray-700 rounded-xl transition cursor-pointer select-none"
                >
                    <span class="icon-swap w-6 h-6 pointer-events-none" :class="{ 'is-b': open }">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </nav>

    <style>
        .hover-primary:hover {
            color: {{ $globalSettings->primary_color_style }} !important;
        }
    </style>

    {{-- Mobile Menu --}}
    <div
        x-data="{ open: false }"
        @toggle-mobile-menu.window="open = !open"
        x-show="open"
        x-transition:enter="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition duration-150 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="lg:hidden fixed top-0 left-0 w-full max-h-[90vh] overflow-y-auto overscroll-contain bg-white dark:bg-gray-900 rounded-b-2xl border-b border-gray-100 dark:border-gray-800 shadow-xl z-50"
    >
        <div class="flex items-center justify-between px-6 pt-6 pb-2">
            <div class="flex items-center gap-2.5">
                @if($globalSettings->logo_primary_url)
                    <img src="{{ $globalSettings->logo_primary_url }}" alt="{{ $globalSettings->site_name }}" class="h-9 w-auto">
                @else
                    <svg class="w-8 h-8" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25-9 5.25m18 0l-9 5.25m9-5.25v9l-9 5.25m-9-14.25l9 5.25m-9-5.25v9l9 5.25" />
                    </svg>
                @endif
                <span class="type-card-title" style="color: {{ $globalSettings->primary_color_style }}">{{ $globalSettings->site_name ?? 'Skansaba.dev' }}</span>
            </div>
            <div class="flex items-center gap-2">
                <x-language-switcher />
                <x-theme-toggle />
                <button @click="$dispatch('toggle-mobile-menu')" class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-600 dark:text-gray-300 transition" aria-label="{{ __('Tutup menu') }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="px-6 py-2 pb-8 space-y-1">
            <a href="/" @class([
                'block py-2.5 border-b border-gray-100 dark:border-gray-800 hover-primary',
                'font-semibold text-gray-900 dark:text-gray-100' => $isBerandaActive,
                'font-semibold text-gray-600 dark:text-gray-300' => !$isBerandaActive,
            ])>{{ __('Beranda') }}</a>

            {{-- Profil Mobile Accordion --}}
            <div x-data="{ expanded: {{ $isProfilActive ? 'true' : 'false' }} }" class="border-b border-gray-100 dark:border-gray-800">
                <button @click="expanded = !expanded" @class([
                    'w-full flex items-center justify-between py-2.5 cursor-pointer hover-primary',
                    'font-semibold text-gray-900 dark:text-gray-100' => $isProfilActive,
                    'font-semibold text-gray-600 dark:text-gray-300' => !$isProfilActive,
                ])>
                    <span>{{ __('Profil') }}</span>
                    <svg class="chevron-swap w-4 h-4" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="expanded" x-transition:enter="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition duration-150 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-1" class="pl-4 pb-2 space-y-1">
                    <a href="/struktur-organisasi" @class([
                        'block py-2 text-sm hover-primary',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isStrukturActive,
                        'text-gray-600 dark:text-gray-300' => !$isStrukturActive,
                    ])>{{ __('Struktur Organisasi') }}</a>
                    <a href="/fasilitas" @class([
                        'block py-2 text-sm hover-primary',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isFasilitasActive,
                        'text-gray-600 dark:text-gray-300' => !$isFasilitasActive,
                    ])>{{ __('Fasilitas') }}</a>
                </div>
            </div>

            {{-- Siswa Mobile Accordion --}}
            <div x-data="{ expanded: {{ $isSiswaActive ? 'true' : 'false' }} }" class="border-b border-gray-100 dark:border-gray-800">
                <button @click="expanded = !expanded" @class([
                    'w-full flex items-center justify-between py-2.5 cursor-pointer hover-primary',
                    'font-semibold text-gray-900 dark:text-gray-100' => $isSiswaActive,
                    'font-semibold text-gray-600 dark:text-gray-300' => !$isSiswaActive,
                ])>
                    <span>{{ __('Siswa') }}</span>
                    <svg class="chevron-swap w-4 h-4" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="expanded" x-transition:enter="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition duration-150 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-1" class="pl-4 pb-2 space-y-1">
                    <a href="/prestasi" @class([
                        'block py-2 text-sm hover-primary',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isPrestasiActive,
                        'text-gray-600 dark:text-gray-300' => !$isPrestasiActive,
                    ])>{{ __('Prestasi') }}</a>
                    <a href="/karya" @class([
                        'block py-2 text-sm hover-primary',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isKaryaActive,
                        'text-gray-600 dark:text-gray-300' => !$isKaryaActive,
                    ])>{{ __('Karya Siswa') }}</a>
                    <a href="/alumni" @class([
                        'block py-2 text-sm hover-primary',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isAlumniActive,
                        'text-gray-600 dark:text-gray-300' => !$isAlumniActive,
                    ])>{{ __('Alumni') }}</a>
                </div>
            </div>

            <a href="/unit-produksi" @class([
                'block py-2.5 border-b border-gray-100 dark:border-gray-800 hover-primary',
                'font-semibold text-gray-900 dark:text-gray-100' => $isUnitProduksiActive,
                'font-semibold text-gray-600 dark:text-gray-300' => !$isUnitProduksiActive,
            ])>{{ __('Unit Produksi') }}</a>

            {{-- Informasi Mobile Accordion --}}
            <div x-data="{ expanded: {{ $isInformasiActive ? 'true' : 'false' }} }" class="border-b border-gray-100 dark:border-gray-800">
                <button @click="expanded = !expanded" @class([
                    'w-full flex items-center justify-between py-2.5 cursor-pointer hover-primary',
                    'font-semibold text-gray-900 dark:text-gray-100' => $isInformasiActive,
                    'font-semibold text-gray-600 dark:text-gray-300' => !$isInformasiActive,
                ])>
                    <span>{{ __('Informasi') }}</span>
                    <svg class="chevron-swap w-4 h-4" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="expanded" x-transition:enter="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition duration-150 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-1" class="pl-4 pb-2 space-y-1">
                    <a href="/berita" @class([
                        'block py-2 text-sm hover-primary',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isBeritaActive,
                        'text-gray-600 dark:text-gray-300' => !$isBeritaActive,
                    ])>{{ __('Berita') }}</a>
                    <a href="/dokumen" @class([
                        'block py-2 text-sm hover-primary',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isDokumenActive,
                        'text-gray-600 dark:text-gray-300' => !$isDokumenActive,
                    ])>{{ __('Dokumen') }}</a>
                    <a href="/gallery" @class([
                        'block py-2 text-sm hover-primary',
                        'font-semibold text-gray-900 dark:text-gray-100' => $isGalleryActive,
                        'text-gray-600 dark:text-gray-300' => !$isGalleryActive,
                    ])>{{ __('Gallery') }}</a>
                </div>
            </div>

            @guest
                <a href="/login" class="block py-2 font-semibold pt-2 border-t border-gray-100 dark:border-gray-800 text-gray-700 dark:text-gray-200 hover-primary">{{ __('Masuk') }}</a>
            @else
                {{-- Akun Mobile --}}
                <div x-data="{ open: false }" class="pt-2 border-t">
                    <div class="flex items-center gap-3 py-2">
                        @if(auth()->user()->photo_url)
                            <img 
                                src="{{ auth()->user()->photo_url }}" 
                                alt="{{ auth()->user()->name }}" 
                                class="w-9 h-9 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700"
                            >
                        @else
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-semibold" style="background-color: {{ $globalSettings->primary_color_style }}">
                                {{ auth()->user()->initials() }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <a 
                        href="{{ auth()->user()->role === 'siswa' ? route('siswa.profil') : route('dashboard') }}" 
                        @click="$dispatch('toggle-mobile-menu')"
                        class="flex items-center gap-2 py-2 text-sm text-gray-600 dark:text-gray-300 hover-primary"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ auth()->user()->role === 'siswa' ? __('Profil Saya') : __('Dashboard') }}
                    </a>
                    <button 
                        @click="open = false; $dispatch('confirm-logout')" 
                        class="w-full flex items-center gap-2 py-2 text-sm text-rose-600 hover:text-rose-700 transition cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('Logout') }}
                    </button>
                </div>
            @endguest
        </div>
    </div>

    {{-- Modal Konfirmasi Logout --}}
    @auth
        <div 
            x-data="{ show: false }"
            @confirm-logout.window="show = true"
            x-cloak
            x-show="show"
            x-transition:enter="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition duration-150 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
        >
            <div 
                x-show="show"
                x-transition:enter="transition duration-200 ease-[cubic-bezier(0.16,1,0.3,1)]"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition duration-150 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white dark:bg-gray-900 rounded-2xl max-w-sm w-full p-6 shadow-xl text-center space-y-4"
            >
                <div class="mx-auto w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <div>
                    <h3 class="type-card-title text-gray-900 dark:text-gray-100">{{ __('Yakin mau logout?') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Sesi Anda akan diakhiri. Anda bisa masuk lagi kapan saja.') }}</p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button 
                        @click="show = false" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer"
                    >
                        {{ __('Batal') }}
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="contents">
                        @csrf
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold transition cursor-pointer"
                        >
                            {{ __('Ya, Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endauth
</header>
