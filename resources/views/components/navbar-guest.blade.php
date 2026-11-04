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

            {{-- Login Button / User Account --}}
            <div class="hidden lg:flex items-center gap-3">
                @guest
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <a href="/login" class="font-medium transition hover-primary">Masuk</a>
                @else
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        {{-- Avatar (foto profil) --}}
                        <button @click="open = !open" class="flex items-center gap-2 group cursor-pointer" title="Akun Saya">
                            @if(auth()->user()->photo_url)
                                <img 
                                    src="{{ auth()->user()->photo_url }}" 
                                    alt="{{ auth()->user()->name }}" 
                                    class="w-9 h-9 rounded-full object-cover border-2 border-gray-200 group-hover:border-[var(--primary-color)] transition"
                                >
                            @else
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-semibold border-2 border-gray-200 group-hover:border-[var(--primary-color)] transition" style="background-color: {{ $globalSettings->primary_color_style }}">
                                    {{ auth()->user()->initials() }}
                                </div>
                            @endif
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Akun --}}
                        <div x-show="open" x-transition class="absolute right-0 top-full mt-2 w-60 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a 
                                href="{{ auth()->user()->role === 'siswa' ? route('siswa.profil') : route('dashboard') }}" 
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover-primary transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ auth()->user()->role === 'siswa' ? 'Profil Saya' : 'Dashboard' }}
                            </a>
                            @if(auth()->user()->role === 'siswa')
                                <a 
                                    href="{{ auth()->user()->profil_url }}" 
                                    target="_blank"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover-primary transition"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Profil Publik Saya
                                </a>
                            @endif
                            <button 
                                @click="open = false; $dispatch('confirm-logout')" 
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </div>
                    </div>
                @endguest
            </div>

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

            @guest
                <a href="/login" class="block py-2 font-medium pt-2 border-t hover-primary">Masuk</a>
            @else
                {{-- Akun Mobile --}}
                <div x-data="{ open: false }" class="pt-2 border-t">
                    <div class="flex items-center gap-3 py-2">
                        @if(auth()->user()->photo_url)
                            <img 
                                src="{{ auth()->user()->photo_url }}" 
                                alt="{{ auth()->user()->name }}" 
                                class="w-9 h-9 rounded-full object-cover border-2 border-gray-200"
                            >
                        @else
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-semibold" style="background-color: {{ $globalSettings->primary_color_style }}">
                                {{ auth()->user()->initials() }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <a 
                        href="{{ auth()->user()->role === 'siswa' ? route('siswa.profil') : route('dashboard') }}" 
                        @click="$dispatch('toggle-mobile-menu')"
                        class="flex items-center gap-2 py-2 text-sm text-gray-600 hover-primary"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ auth()->user()->role === 'siswa' ? 'Profil Saya' : 'Dashboard' }}
                    </a>
                    <button 
                        @click="open = false; $dispatch('confirm-logout')" 
                        class="w-full flex items-center gap-2 py-2 text-sm text-rose-600 hover:text-rose-700 transition cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
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
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
        >
            <div 
                x-show="show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl text-center space-y-4"
            >
                <div class="mx-auto w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Yakin mau logout?</h3>
                    <p class="text-sm text-gray-500 mt-1">Sesi Anda akan diakhiri. Anda bisa masuk lagi kapan saja.</p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button 
                        @click="show = false" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="contents">
                        @csrf
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-semibold transition cursor-pointer"
                        >
                            Ya, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endauth
</nav>
</header>
