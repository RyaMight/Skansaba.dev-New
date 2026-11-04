<div class="p-6 max-w-7xl mx-auto space-y-6">
    {{-- Toast Notifikasi (fixed di layar, selalu terlihat meskipun halaman di-scroll) --}}
    @if (session()->has('message'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 right-6 z-[60] w-auto max-w-sm"
            role="status"
            aria-live="polite"
        >
            <div class="flex items-center gap-3 bg-emerald-600 text-white rounded-xl shadow-lg px-4 py-3">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/20 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <span class="text-sm font-medium">{{ session('message') }}</span>
                <button type="button" @click="show = false" class="ml-1 text-white/70 hover:text-white shrink-0" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Toast Error Notifikasi --}}
    @if (session()->has('error'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 right-6 z-[60] w-auto max-w-sm"
            role="alert"
        >
            <div class="flex items-center gap-3 bg-rose-600 text-white rounded-xl shadow-lg px-4 py-3">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/20 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
                <span class="text-sm font-medium">{{ session('error') }}</span>
                <button type="button" @click="show = false" class="ml-1 text-white/70 hover:text-white shrink-0" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-50 tracking-tight">Profil Saya</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola profil, karya, dan prestasi Anda di CMS Jurusan RPL</p>
    </div>

    {{-- Main Profile Card --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
            {{-- Avatar --}}
            <div class="relative w-20 h-20 rounded-full overflow-hidden bg-slate-100 dark:bg-gray-800 ring-2 ring-gray-100 dark:ring-gray-700 shrink-0">
                @if($user->photo_url)
                    <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ $user->initials() }}
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-50 leading-tight">
                        {{ $user->name ?: 'Ahmad Syahputra' }}
                    </h2>
                    @if($user->kelas)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-200/70 dark:border-sky-500/20">
                            Kelas {{ $user->kelas }}
                        </span>
                    @endif
                </div>
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mt-1">
                    Siswa • NISN: {{ $user->nisn ?: '0054321980' }}
                </p>
                <p class="text-xs md:text-sm text-gray-600 dark:text-gray-300 mt-2 max-w-2xl leading-relaxed">
                    {{ $user->bio ?: 'Tertarik pada Web Development dan UI/UX Design. Senang belajar hal baru dan berkarya di bidang Rekayasa Perangkat Lunak.' }}
                </p>

                {{-- Keahlian / Tech Stack Badges --}}
                <div class="mt-3.5 pt-3 border-t border-gray-100 dark:border-gray-800 flex flex-wrap items-center gap-1.5">
                    <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mr-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        <span>Tech Stack:</span>
                    </span>
                    @forelse($user->keahlian_list as $skill)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-200/70 dark:border-sky-500/20 rounded-md text-xs font-medium">
                            <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $skill }}</span>
                        </span>
                    @empty
                        <button type="button" wire:click="openEditModal" class="inline-flex items-center gap-1 text-xs text-sky-600 dark:text-sky-400 hover:text-sky-700 italic hover:underline cursor-pointer">
                            + Tambah Keahlian / Tech Stack
                        </button>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Edit Profile Button --}}
        <div class="shrink-0 self-start md:self-center">
            <button 
                type="button" 
                wire:click="openEditModal"
                class="inline-flex items-center gap-2 px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-sm font-semibold rounded-lg shadow-xs transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                <span>Edit Profil</span>
            </button>
        </div>
    </div>

    {{-- Three Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
        {{-- Stat 1: Karya Diterbitkan --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center text-sky-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Karya Diterbitkan</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 mt-0.5">8</p>
            </div>
        </div>

        {{-- Stat 2: Total Prestasi --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Prestasi</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 mt-0.5">3</p>
            </div>
        </div>

        {{-- Stat 3: Sertifikasi & Lencana --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Sertifikasi & Lencana</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 mt-0.5">5</p>
            </div>
        </div>
    </div>

    {{-- Main Content Grid: Karya Kreatif (Left) & Bio/Status (Right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Karya Kreatif Saya --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
            <div>
                {{-- Header Karya --}}
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base md:text-lg font-bold text-gray-900 dark:text-gray-50">Karya Kreatif Saya</h3>
                    <a 
                        href="{{ route('karya-siswa.index') }}" 
                        wire:navigate 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-sky-300 dark:border-sky-500/30 text-sky-600 dark:text-sky-400 bg-sky-50/50 dark:bg-sky-500/10 hover:bg-sky-50 dark:hover:bg-sky-500/20 rounded-lg text-xs font-semibold transition"
                    >
                        <span class="text-sm leading-none">+</span>
                        <span>Tambah Karya</span>
                    </a>
                </div>

                {{-- 3 Items Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    {{-- Item 1: Portfolio Personal --}}
                    <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden hover:shadow-md transition group">
                        <div class="w-full aspect-[4/2.6] bg-slate-900 p-2 flex items-center justify-center relative overflow-hidden">
                            <div class="w-full h-full bg-slate-800 rounded-md p-1.5 flex flex-col justify-between border border-slate-700">
                                <div class="flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                </div>
                                <div class="space-y-1">
                                    <div class="text-[7px] font-bold text-white">Portfolio Personal</div>
                                    <div class="h-1 bg-slate-600 rounded w-16"></div>
                                </div>
                                <div class="h-2 w-8 bg-sky-500 rounded text-[5px] text-white flex items-center justify-center font-bold">VIEW</div>
                            </div>
                        </div>
                        <div class="p-3">
                            <span class="inline-block px-2 py-0.5 bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-400 text-[10px] font-semibold rounded-full mb-1">
                                Web • PHP
                            </span>
                            <h4 class="font-bold text-gray-900 dark:text-gray-50 text-sm group-hover:text-sky-600 transition truncate">Portfolio Personal</h4>
                            <p class="text-[11px] text-gray-400 dark:text-gray-400 mt-0.5">Web • PHP</p>
                        </div>
                    </div>

                    {{-- Item 2: Aplikasi Kasir Kantin --}}
                    <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden hover:shadow-md transition group">
                        <div class="w-full aspect-[4/2.6] bg-slate-100 p-2 flex items-center justify-center relative overflow-hidden">
                            <div class="w-16 h-full bg-[#055038] rounded-lg p-1.5 flex flex-col justify-between shadow-xs border border-emerald-900 text-white">
                                <div class="w-2 h-0.5 bg-white/40 rounded-full mx-auto"></div>
                                <div class="text-[6px] font-bold text-center">Kasir App</div>
                                <div class="w-3 h-3 bg-amber-400 rounded-full mx-auto"></div>
                                <div class="w-4 h-0.5 bg-white/60 rounded-full mx-auto"></div>
                            </div>
                            <div class="absolute right-3 w-8 h-8 rounded-lg bg-gradient-to-tr from-purple-600 to-amber-500 flex items-center justify-center text-white font-black text-xs shadow-md">
                                K
                            </div>
                        </div>
                        <div class="p-3">
                            <span class="inline-block px-2 py-0.5 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[10px] font-semibold rounded-full mb-1">
                                Mobile • Kotlin
                            </span>
                            <h4 class="font-bold text-gray-900 dark:text-gray-50 text-sm group-hover:text-amber-600 transition truncate">Aplikasi Kasir Kantin</h4>
                            <p class="text-[11px] text-gray-400 dark:text-gray-400 mt-0.5">Mobile • Kotlin</p>
                        </div>
                    </div>

                    {{-- Item 3: Redesain Website SMK --}}
                    <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden hover:shadow-md transition group">
                        <div class="w-full aspect-[4/2.6] bg-[#1a2035] p-2 flex items-center justify-center relative overflow-hidden">
                            <div class="w-full h-full bg-slate-900 rounded-md p-1.5 flex flex-col justify-between border border-slate-700">
                                <div class="flex items-center justify-between">
                                    <div class="text-[7px] text-sky-400 font-bold">SMK Website</div>
                                    <div class="flex gap-0.5">
                                        <div class="w-1 h-1 rounded-full bg-purple-400"></div>
                                        <div class="w-1 h-1 rounded-full bg-pink-400"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-1">
                                    <div class="h-4 bg-slate-800 rounded"></div>
                                    <div class="h-4 bg-slate-800 rounded"></div>
                                </div>
                                <div class="h-1 bg-slate-700 rounded w-12"></div>
                            </div>
                        </div>
                        <div class="p-3">
                            <span class="inline-block px-2 py-0.5 bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 text-[10px] font-semibold rounded-full mb-1">
                                UI/UX • Figma
                            </span>
                            <h4 class="font-bold text-gray-900 dark:text-gray-50 text-sm group-hover:text-purple-600 dark:text-purple-400 transition truncate">Redesain Website SMK</h4>
                            <p class="text-[11px] text-gray-400 dark:text-gray-400 mt-0.5">UI/UX • Figma</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Bio (Urutan: Nama Panjang Siswa, Email, Keahlian Terverifikasi, Saluran yang Bisa Dihubungi, Isi Bio), Status Karya, Lencana --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800 space-y-6">
            {{-- Bio Card --}}
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-50 mb-3 pb-2 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <span>Bio</span>
                    <span class="text-[11px] font-normal text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-500/10 px-2 py-0.5 rounded-full">Profil Siswa</span>
                </h3>
                
                <div class="space-y-3.5 text-xs text-gray-700 dark:text-gray-200">
                    {{-- 1. Nama Panjang Siswa --}}
                    <div>
                        <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Nama Panjang Siswa</span>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-50 mt-0.5">{{ $user->name ?: 'Ahmad Syahputra' }}</p>
                    </div>

                    {{-- 2. Email --}}
                    <div>
                        <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Email Aktif</span>
                        <p class="text-xs font-medium text-gray-800 dark:text-gray-100 mt-0.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $user->email ?: 'ahmad.syah@smk.sch.id' }}</span>
                        </p>
                    </div>

                    {{-- 3. Keahlian & Tech Stack --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Keahlian &amp; Tech Stack</span>
                            <button type="button" wire:click="openEditModal" class="text-[10px] text-sky-600 dark:text-sky-400 hover:underline cursor-pointer">Edit</button>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            @forelse($user->keahlian_list as $skill)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-500/20 rounded-md text-[11px] font-medium">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $skill }}</span>
                                </span>
                            @empty
                                <span class="text-[11px] text-gray-400 italic">Belum terverifikasi — ajukan karya nyata atau tambahkan melalui edit profil</span>
                            @endforelse
                        </div>
                    </div>

                    {{-- 4. Saluran yang Bisa Dihubungi --}}
                    @php
                        $saluran = $user->saluran_kontak ?? [];
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Saluran yang Bisa Dihubungi</span>
                            <a href="{{ route('siswa.saluran') }}" wire:navigate class="text-[10px] text-sky-600 dark:text-sky-400 hover:underline">Kelola</a>
                        </div>
                        <div class="p-2.5 bg-slate-50 dark:bg-gray-800/60 border border-slate-100 dark:border-gray-800 rounded-xl space-y-1.5">
                            @php
                                $hasActiveSaluran = false;
                            @endphp

                            {{-- Instagram --}}
                            @if(!empty($saluran['instagram']['active']) && !empty($saluran['instagram']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 flex items-center justify-center text-white">
                                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                        </span>
                                        <span>Instagram</span>
                                    </span>
                                    <a href="{{ $saluran['instagram']['url'] }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline font-medium truncate max-w-[120px]">
                                        {{ str_replace(['https://instagram.com/', 'https://www.instagram.com/'], '@', $saluran['instagram']['url']) }}
                                    </a>
                                </div>
                            @endif

                            {{-- TikTok --}}
                            @if(!empty($saluran['tiktok']['active']) && !empty($saluran['tiktok']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-black flex items-center justify-center text-white">
                                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                        </span>
                                        <span>TikTok</span>
                                    </span>
                                    <a href="{{ $saluran['tiktok']['url'] }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline font-medium truncate max-w-[120px]">
                                        {{ str_replace(['https://tiktok.com/', 'https://www.tiktok.com/'], '', $saluran['tiktok']['url']) }}
                                    </a>
                                </div>
                            @endif

                            {{-- WhatsApp --}}
                            @if(!empty($saluran['whatsapp']['active']) && !empty($saluran['whatsapp']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-emerald-500 flex items-center justify-center text-white">
                                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm.01 16.59c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31c-.82-1.31-1.26-2.83-1.26-4.38 0-4.54 3.7-8.24 8.24-8.24 2.2 0 4.27.86 5.82 2.42 1.56 1.55 2.41 3.63 2.41 5.84 0 4.55-3.7 8.22-8.22 8.22zm4.52-6.17c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.44.06-.66.31-.23.25-.88.86-.88 2.09 0 1.23.9 2.42 1.02 2.59.12.17 1.77 2.7 4.28 3.79.6.26 1.07.41 1.43.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.07-.12-.23-.19-.48-.31z"/></svg>
                                        </span>
                                        <span>WhatsApp</span>
                                    </span>
                                    <span class="text-gray-700 dark:text-gray-200 font-mono text-[11px]">{{ $saluran['whatsapp']['url'] }}</span>
                                </div>
                            @endif

                            {{-- YouTube --}}
                            @if(!empty($saluran['youtube']['active']) && !empty($saluran['youtube']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-red-600 flex items-center justify-center text-white">
                                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                        </span>
                                        <span>YouTube</span>
                                    </span>
                                    <a href="{{ $saluran['youtube']['url'] }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline font-medium truncate max-w-[120px]">Channel</a>
                                </div>
                            @endif

                            {{-- LinkedIn --}}
                            @if(!empty($saluran['linkedin']['active']) && !empty($saluran['linkedin']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-[#0077b5] flex items-center justify-center text-white">
                                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                        </span>
                                        <span>LinkedIn</span>
                                    </span>
                                    <a href="{{ $saluran['linkedin']['url'] }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline font-medium truncate max-w-[120px]">
                                        {{ str_replace(['https://linkedin.com/in/', 'https://www.linkedin.com/in/'], '', $saluran['linkedin']['url']) }}
                                    </a>
                                </div>
                            @endif

                            {{-- Twitter / X --}}
                            @if(!empty($saluran['twitter']['active']) && !empty($saluran['twitter']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-slate-900 flex items-center justify-center text-white">
                                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                        </span>
                                        <span>Twitter/X</span>
                                    </span>
                                    <a href="{{ $saluran['twitter']['url'] }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline font-medium truncate max-w-[120px]">
                                        {{ str_replace(['https://x.com/', 'https://twitter.com/'], '@', $saluran['twitter']['url']) }}
                                    </a>
                                </div>
                            @endif

                            {{-- GitHub --}}
                            @if(!empty($saluran['github']['active']) && !empty($saluran['github']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-[#24292e] flex items-center justify-center text-white">
                                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                                        </span>
                                        <span>GitHub</span>
                                    </span>
                                    <a href="{{ $saluran['github']['url'] }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline font-medium truncate max-w-[120px]">
                                        {{ str_replace(['https://github.com/'], '@', $saluran['github']['url']) }}
                                    </a>
                                </div>
                            @endif

                            {{-- Fallback default jika belum ada saluran aktif --}}
                            @if(!$hasActiveSaluran)
                                <div class="text-gray-400 dark:text-gray-400 text-center py-1">Belum ada saluran yang diaktifkan</div>
                            @endif
                        </div>
                    </div>

                    {{-- 5. Isi Bio --}}
                    <div>
                        <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Isi Bio</span>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 leading-relaxed bg-gray-50/60 dark:bg-gray-800/60 p-2.5 rounded-lg border border-gray-100 dark:border-gray-800">
                            {{ $user->bio ?: 'Tertarik pada Web Development dan UI/UX Design. Senang belajar hal baru dan berkarya di bidang Rekayasa Perangkat Lunak.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Status Karya --}}
            <div>
                <h4 class="text-xs font-bold text-gray-800 dark:text-gray-100 mb-2.5">Status Karya</h4>
                <div class="grid grid-cols-2 gap-2">
                    {{-- Draft --}}
                    <div class="bg-gray-50/80 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-800 rounded-xl p-2.5">
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium block">Draft</span>
                        <span class="text-base font-bold text-gray-800 dark:text-gray-100 mt-0.5 block">{{ $draftCount ?: 0 }}</span>
                    </div>

                    {{-- Review --}}
                    <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-100/80 dark:border-amber-500/20 rounded-xl p-2.5">
                        <span class="text-[10px] text-amber-600 dark:text-amber-400 font-medium block">Review</span>
                        <span class="text-base font-bold text-amber-700 dark:text-amber-300 mt-0.5 block">{{ $reviewCount ?: 0 }}</span>
                    </div>

                    {{-- Published --}}
                    <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-xl p-2.5">
                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium block">Published</span>
                        <span class="text-base font-bold text-emerald-700 dark:text-emerald-300 mt-0.5 block">{{ $karyaCount ?: 8 }}</span>
                    </div>

                    {{-- Archived --}}
                    <div class="bg-rose-50 dark:bg-rose-500/10 border border-rose-100/80 dark:border-rose-500/20 rounded-xl p-2.5">
                        <span class="text-[10px] text-rose-500 dark:text-rose-400 font-medium block">Archived</span>
                        <span class="text-base font-bold text-rose-700 dark:text-rose-300 mt-0.5 block">{{ $archivedCount ?: 0 }}</span>
                    </div>
                </div>
            </div>

            {{-- Lencana & Penghargaan --}}
            <div>
                <h4 class="text-xs font-bold text-gray-800 dark:text-gray-100 mb-2.5">Lencana & Penghargaan</h4>
                <div class="flex flex-wrap gap-2">
                    {{-- Badge 1 --}}
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-gray-200 rounded-lg text-xs font-medium">
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span>Juara LKS</span>
                    </span>

                    {{-- Badge 2 --}}
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-gray-200 rounded-lg text-xs font-medium">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <span>Siswa Aktif</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profile Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-3xl w-full p-6 shadow-xl border border-gray-100 dark:border-gray-800 space-y-4 max-h-[92vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50">Edit Profil Siswa</h3>
                    <button type="button" wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form 
                    x-data="{ isSaving: false }"
                    x-on:submit.prevent="
                        if (isSaving) return;
                        isSaving = true;
                        const form = $el;
                        const cropper = window.imageCropper || form.querySelector('[x-data]')?._cropperInstance;
                        const submitDirectly = (res) => {
                            $wire.saveProfile(res).catch(err => {
                                console.error('Gagal menyimpan profil:', err);
                            }).finally(() => { 
                                isSaving = false; 
                            });
                        };
                        if (cropper && typeof cropper.getCropResultAsync === 'function' && cropper.showCropper) {
                            cropper.getCropResultAsync().then(result => {
                                submitDirectly(result && result.startsWith('data:image') ? result : null);
                            }).catch(err => {
                                console.error('Gagal mengambil crop:', err);
                                submitDirectly(null);
                            });
                        } else {
                            submitDirectly(null);
                        }
                    " 
                    class="space-y-4 text-xs"
                >
                    {{-- Upload & Adjust Foto Profil (Croppie 1:1) --}}
                    <div>
                        <x-image-cropper
                            modelName="photoBase64"
                            cropDataName="crop_data"
                            label="Foto Profil"
                            :aspectRatio="1"
                            aspectRatioLabel="1:1 Bulat/Kotak"
                            :maxWidth="500"
                            :maxHeight="500"
                            :currentImage="$user->photo"
                            :previewSize="200"
                            :isAvatar="true"
                            :avatarName="$user->name"
                            :avatarSubtitle="$user->kelas ? 'Kelas ' . $user->kelas . ' • Siswa RPL' : 'Siswa RPL'"
                            :avatarSkills="$user->keahlian_list"
                        />
                    </div>

                    {{-- Nama Panjang Siswa --}}
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-200 mb-1">Nama Panjang Siswa</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none" placeholder="Masukkan nama lengkap">
                        @error('name') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email Aktif --}}
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-200 mb-1">Email Aktif</label>
                        <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none" placeholder="nama@email.com">
                        @error('email') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-200 mb-1">Bio</label>
                        <textarea wire:model="bio" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none" placeholder="Tuliskan bio atau minat Anda..."></textarea>
                        @error('bio') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    {{-- Keahlian / Tech Stack --}}
                    <div x-data="{
                        get tags() {
                            const val = $wire.keahlian || '';
                            return val.split(',').map(s => s.trim()).filter(Boolean);
                        },
                        addTag(tag) {
                            let current = ($wire.keahlian || '').split(',').map(s => s.trim()).filter(Boolean);
                            if (!current.includes(tag)) {
                                current.push(tag);
                                $wire.keahlian = current.join(', ');
                            }
                        }
                    }">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block font-medium text-gray-700 dark:text-gray-200">Keahlian / Tech Stack</label>
                            <span class="text-[10px] text-gray-400" x-text="tags.length + ' keahlian terdeteksi'"></span>
                        </div>
                        <input 
                            type="text" 
                            wire:model.live.debounce.250ms="keahlian" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none" 
                            placeholder="Contoh: Laravel, Tailwind CSS, Vue.js, MySQL, Flutter, Figma"
                        >
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Pisahkan setiap keahlian atau teknologi dengan tanda koma (,).</p>
                        @error('keahlian') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror

                        {{-- Quick Presets --}}
                        <div class="mt-2 flex flex-wrap items-center gap-1">
                            <span class="text-[10px] text-gray-400">Pilihan cepat:</span>
                            @foreach(['PHP', 'Laravel', 'Tailwind CSS', 'JavaScript', 'Vue.js', 'MySQL', 'Flutter', 'Figma', 'Python'] as $preset)
                                <button 
                                    type="button" 
                                    @click="addTag('{{ $preset }}')"
                                    class="px-2 py-0.5 rounded text-[10px] bg-slate-100 dark:bg-gray-800 hover:bg-sky-50 dark:hover:bg-sky-500/10 hover:text-sky-700 dark:hover:text-sky-400 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 transition cursor-pointer"
                                >
                                    + {{ $preset }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Live Badges Preview --}}
                        <div class="mt-2.5 p-2.5 bg-slate-50 dark:bg-gray-800/60 border border-slate-100 dark:border-gray-700 rounded-lg">
                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block mb-1.5">Preview Lencana:</span>
                            <div class="flex flex-wrap gap-1.5" x-show="tags.length > 0">
                                <template x-for="(tag, idx) in tags" :key="idx">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-200 dark:border-sky-500/20 rounded text-xs font-medium">
                                        <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="tag"></span>
                                    </span>
                                </template>
                            </div>
                            <p x-show="tags.length === 0" class="text-xs text-gray-400 italic">Ketik keahlian di atas untuk melihat lencana preview di sini.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <button type="button" wire:click="closeEditModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer">
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg font-semibold transition cursor-pointer flex items-center gap-1.5"
                        >
                            <span wire:loading wire:target="saveProfile" class="inline-block animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
