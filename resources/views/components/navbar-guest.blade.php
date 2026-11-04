<header class="sticky top-0 z-50 pt-4 px-4 sm:px-6">
    <nav class="max-w-6xl mx-auto bg-white rounded-2xl shadow-[0_2px_16px_rgba(15,23,42,0.08)] border border-gray-100">
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
                <span class="font-bold text-lg tracking-tight" style="color: {{ $globalSettings->primary_color_style }}">{{ $globalSettings->site_name ?? 'Skansaba.dev' }}</span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-7">
                <a href="/" class="text-sm font-bold text-gray-900 transition hover-primary">Beranda</a>

                {{-- Profil Dropdown --}}
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center gap-1 text-sm font-medium text-gray-500 transition hover-primary">
                        Profil
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute top-full mt-3 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                        <a href="/struktur-organisasi" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover-primary">Struktur Organisasi</a>
                        <a href="/fasilitas" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover-primary">Fasilitas</a>
                    </div>
                </div>

                {{-- Siswa Dropdown --}}
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center gap-1 text-sm font-medium text-gray-500 transition hover-primary">
                        Siswa
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute top-full mt-3 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                        <a href="/prestasi" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover-primary">Prestasi</a>
                        <a href="/karya" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover-primary">Karya Siswa</a>
                        <a href="/alumni" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover-primary">Alumni</a>
                    </div>
                </div>

                <a href="/unit-produksi" class="text-sm font-medium text-gray-500 transition hover-primary">Unit Produksi</a>

                {{-- Informasi Dropdown --}}
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center gap-1 text-sm font-medium text-gray-500 transition hover-primary">
                        Informasi
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute top-full mt-3 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                        <a href="/berita" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover-primary">Berita</a>
                        <a href="/dokumen" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover-primary">Dokumen</a>
                        <a href="/gallery" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover-primary">Gallery</a>
                    </div>
                </div>
            </div>

            {{-- Login Button --}}
            <a href="/login" class="hidden sm:flex items-center gap-2 text-sm font-semibold text-gray-900 hover:opacity-70 transition">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 1115 0v.25H4.5v-.25z"/>
                </svg>
                Login
            </a>

            {{-- Mobile Menu Button --}}
            <button
                x-data
                @click="$dispatch('toggle-mobile-menu')"
                class="lg:hidden p-2 text-gray-700 hover:text-gray-900 transition"
                aria-label="Buka menu"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
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
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="lg:hidden fixed top-0 left-0 w-full bg-white rounded-b-2xl border-b border-gray-100 shadow-xl z-50"
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
                <span class="font-bold text-lg" style="color: {{ $globalSettings->primary_color_style }}">{{ $globalSettings->site_name ?? 'Skansaba.dev' }}</span>
            </div>
            <button @click="open = false" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition" aria-label="Tutup menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-6 py-2 pb-8 space-y-1">
            <a href="/" class="block py-2.5 text-gray-900 font-bold">Beranda</a>

            {{-- Profil Mobile Accordion --}}
            <div x-data="{ expanded: false }" class="border-b border-gray-100">
                <button @click="expanded = !expanded" class="w-full flex items-center justify-between py-2.5 text-gray-600 font-semibold">
                    <span>Profil</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="expanded" x-transition class="pl-4 pb-2 space-y-1">
                    <a href="/struktur-organisasi" class="block py-2 text-sm text-gray-600">Struktur Organisasi</a>
                    <a href="/fasilitas" class="block py-2 text-sm text-gray-600">Fasilitas</a>
                </div>
            </div>

            {{-- Siswa Mobile Accordion --}}
            <div x-data="{ expanded: false }" class="border-b border-gray-100">
                <button @click="expanded = !expanded" class="w-full flex items-center justify-between py-2.5 text-gray-600 font-semibold">
                    <span>Siswa</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="expanded" x-transition class="pl-4 pb-2 space-y-1">
                    <a href="/prestasi" class="block py-2 text-sm text-gray-600">Prestasi</a>
                    <a href="/karya" class="block py-2 text-sm text-gray-600">Karya Siswa</a>
                    <a href="/alumni" class="block py-2 text-sm text-gray-600">Alumni</a>
                </div>
            </div>

            <a href="/unit-produksi" class="block py-2.5 text-gray-600 font-semibold border-b border-gray-100">Unit Produksi</a>

            {{-- Informasi Mobile Accordion --}}
            <div x-data="{ expanded: false }" class="border-b border-gray-100">
                <button @click="expanded = !expanded" class="w-full flex items-center justify-between py-2.5 text-gray-600 font-semibold">
                    <span>Informasi</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="expanded" x-transition class="pl-4 pb-2 space-y-1">
                    <a href="/berita" class="block py-2 text-sm text-gray-600">Berita</a>
                    <a href="/dokumen" class="block py-2 text-sm text-gray-600">Dokumen</a>
                    <a href="/gallery" class="block py-2 text-sm text-gray-600">Gallery</a>
                </div>
            </div>

            <a href="/login" class="flex items-center gap-2 py-3 font-semibold text-gray-900">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 1115 0v.25H4.5v-.25z"/>
                </svg>
                Login
            </a>
        </div>
    </div>
</header>
