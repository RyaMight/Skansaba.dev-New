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

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-50 tracking-tight">Profil Saya</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola profil, karya, dan prestasi Anda di CMS Jurusan RPL</p>
    </div>

    {{-- Main Profile Card --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
            {{-- Avatar --}}
            <div class="relative w-20 h-20 rounded-full overflow-hidden bg-slate-100 ring-2 ring-gray-100 shrink-0">
                @if($user->photo_url)
                    <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ $user->initials() }}
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-50 leading-tight">
                    {{ $user->name ?: 'Ahmad Syahputra' }}
                </h2>
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mt-1">
                    Siswa • NISN: {{ $user->nisn ?: '0054321980' }}
                </p>
                <p class="text-xs md:text-sm text-gray-600 dark:text-gray-300 mt-2 max-w-2xl leading-relaxed">
                    {{ $user->bio ?: 'Tertarik pada Web Development dan UI/UX Design. Senang belajar hal baru dan berkarya di bidang Rekayasa Perangkat Lunak.' }}
                </p>
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
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-sky-300 text-sky-600 bg-sky-50 dark:bg-sky-500//50 hover:bg-sky-50 dark:bg-sky-500/10 rounded-lg text-xs font-semibold transition"
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
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-50 dark:$110"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-50 dark:$110"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-50 dark:$110"></span>
                                </div>
                                <div class="space-y-1">
                                    <div class="text-[7px] font-bold text-white">Portfolio Personal</div>
                                    <div class="h-1 bg-slate-600 rounded w-16"></div>
                                </div>
                                <div class="h-2 w-8 bg-sky-50 dark:$110 rounded text-[5px] text-white flex items-center justify-center font-bold">VIEW</div>
                            </div>
                        </div>
                        <div class="p-3">
                            <span class="inline-block px-2 py-0.5 bg-sky-100 dark:bg-sky-50 dark:bg-sky-500//20 text-sky-700 text-[10px] font-semibold rounded-full mb-1">
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
                            <span class="inline-block px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-semibold rounded-full mb-1">
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
                            <span class="inline-block px-2 py-0.5 bg-purple-100 dark:bg-purple-50 dark:bg-purple-500//20 text-purple-700 text-[10px] font-semibold rounded-full mb-1">
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
                    <span class="text-[11px] font-normal text-sky-600 bg-sky-50 dark:bg-sky-500/10 px-2 py-0.5 rounded-full">Profil Siswa</span>
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

                    {{-- 3. Keahlian Terverifikasi --}}
                    <div>
                        <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider block mb-1">Keahlian Terverifikasi</span>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 border border-emerald-200/60 rounded-md text-[11px] font-medium">
                                <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Web Development</span>
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 border border-emerald-200/60 rounded-md text-[11px] font-medium">
                                <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Mobile Apps</span>
                            </span>
                        </div>
                    </div>

                    {{-- 4. Saluran yang Bisa Dihubungi --}}
                    @php
                        $saluran = $user->saluran_kontak ?? [];
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Saluran yang Bisa Dihubungi</span>
                            <a href="{{ route('siswa.saluran') }}" wire:navigate class="text-[10px] text-sky-600 hover:underline">Kelola</a>
                        </div>
                        <div class="p-2.5 bg-slate-50 border border-slate-100 rounded-xl space-y-1.5">
                            @php
                                $hasActiveSaluran = false;
                            @endphp

                            {{-- Instagram --}}
                            @if(!empty($saluran['instagram']['active']) && !empty($saluran['instagram']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 flex items-center justify-center text-white text-[8px] font-bold">IG</span>
                                        <span>Instagram</span>
                                    </span>
                                    <a href="{{ $saluran['instagram']['url'] }}" target="_blank" class="text-sky-600 hover:underline font-medium truncate max-w-[120px]">
                                        {{ str_replace(['https://instagram.com/', 'https://www.instagram.com/'], '@', $saluran['instagram']['url']) }}
                                    </a>
                                </div>
                            @endif

                            {{-- TikTok --}}
                            @if(!empty($saluran['tiktok']['active']) && !empty($saluran['tiktok']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-black flex items-center justify-center text-white text-[8px] font-bold">TT</span>
                                        <span>TikTok</span>
                                    </span>
                                    <a href="{{ $saluran['tiktok']['url'] }}" target="_blank" class="text-sky-600 hover:underline font-medium truncate max-w-[120px]">
                                        {{ str_replace(['https://tiktok.com/', 'https://www.tiktok.com/'], '', $saluran['tiktok']['url']) }}
                                    </a>
                                </div>
                            @endif

                            {{-- WhatsApp --}}
                            @if(!empty($saluran['whatsapp']['active']) && !empty($saluran['whatsapp']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-emerald-50 dark:$110 flex items-center justify-center text-white text-[8px] font-bold">WA</span>
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
                                        <span class="w-3.5 h-3.5 rounded bg-red-600 flex items-center justify-center text-white text-[8px] font-bold">YT</span>
                                        <span>YouTube</span>
                                    </span>
                                    <a href="{{ $saluran['youtube']['url'] }}" target="_blank" class="text-sky-600 hover:underline font-medium truncate max-w-[120px]">Channel</a>
                                </div>
                            @endif

                            {{-- LinkedIn --}}
                            @if(!empty($saluran['linkedin']['active']) && !empty($saluran['linkedin']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-[#0077b5] flex items-center justify-center text-white text-[8px] font-bold">IN</span>
                                        <span>LinkedIn</span>
                                    </span>
                                    <a href="{{ $saluran['linkedin']['url'] }}" target="_blank" class="text-sky-600 hover:underline font-medium truncate max-w-[120px]">
                                        {{ str_replace(['https://linkedin.com/in/', 'https://www.linkedin.com/in/'], '', $saluran['linkedin']['url']) }}
                                    </a>
                                </div>
                            @endif

                            {{-- Twitter / X --}}
                            @if(!empty($saluran['twitter']['active']) && !empty($saluran['twitter']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-slate-900 flex items-center justify-center text-white text-[8px] font-bold">X</span>
                                        <span>Twitter/X</span>
                                    </span>
                                    <a href="{{ $saluran['twitter']['url'] }}" target="_blank" class="text-sky-600 hover:underline font-medium truncate max-w-[120px]">
                                        {{ str_replace(['https://x.com/', 'https://twitter.com/'], '@', $saluran['twitter']['url']) }}
                                    </a>
                                </div>
                            @endif

                            {{-- GitHub --}}
                            @if(!empty($saluran['github']['active']) && !empty($saluran['github']['url']))
                                @php $hasActiveSaluran = true; @endphp
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded bg-[#24292e] flex items-center justify-center text-white text-[8px] font-bold">GH</span>
                                        <span>GitHub</span>
                                    </span>
                                    <a href="{{ $saluran['github']['url'] }}" target="_blank" class="text-sky-600 hover:underline font-medium truncate max-w-[120px]">
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
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 leading-relaxed bg-gray-50/60 p-2.5 rounded-lg border border-gray-100 dark:border-gray-800">
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
                    <div class="bg-gray-50/80 border border-gray-100 dark:border-gray-800 rounded-xl p-2.5">
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium block">Draft</span>
                        <span class="text-base font-bold text-gray-800 dark:text-gray-100 mt-0.5 block">{{ $draftCount ?: 0 }}</span>
                    </div>

                    {{-- Review --}}
                    <div class="bg-amber-50 dark:bg-amber-500//50 border border-amber-100/80 rounded-xl p-2.5">
                        <span class="text-[10px] text-amber-600 font-medium block">Review</span>
                        <span class="text-base font-bold text-amber-700 mt-0.5 block">{{ $reviewCount ?: 0 }}</span>
                    </div>

                    {{-- Published --}}
                    <div class="bg-emerald-50 dark:bg-emerald-500//60 border border-emerald-100 rounded-xl p-2.5">
                        <span class="text-[10px] text-emerald-600 font-medium block">Published</span>
                        <span class="text-base font-bold text-emerald-700 mt-0.5 block">{{ $karyaCount ?: 8 }}</span>
                    </div>

                    {{-- Archived --}}
                    <div class="bg-rose-50 dark:bg-rose-500//40 border border-rose-100/80 rounded-xl p-2.5">
                        <span class="text-[10px] text-rose-500 font-medium block">Archived</span>
                        <span class="text-base font-bold text-rose-700 mt-0.5 block">{{ $archivedCount ?: 0 }}</span>
                    </div>
                </div>
            </div>

            {{-- Lencana & Penghargaan --}}
            <div>
                <h4 class="text-xs font-bold text-gray-800 dark:text-gray-100 mb-2.5">Lencana & Penghargaan</h4>
                <div class="flex flex-wrap gap-2">
                    {{-- Badge 1 --}}
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-xs font-medium">
                        <span class="text-amber-500 text-sm">🏆</span>
                        <span>Juara LKS</span>
                    </span>

                    {{-- Badge 2 --}}
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-xs font-medium">
                        <span class="text-emerald-500 text-sm">⭐</span>
                        <span>Siswa Aktif</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profile Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-gray-100 dark:border-gray-800 space-y-4 max-h-[92vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50">Edit Profil Siswa</h3>
                    <button type="button" wire:click="closeEditModal" class="text-gray-400 dark:text-gray-400 hover:text-gray-600 dark:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form x-on:submit.prevent="const c = window.imageCropper; const save = () => $wire.saveProfile(); (c && c.sendToLivewireAsync) ? c.sendToLivewireAsync().then(save).catch(save) : save();" class="space-y-4 text-xs">
                    {{-- Upload & Adjust Foto Profil (Croppie 1:1) --}}
                    <div>
                        <x-image-cropper
                            modelName="photoBase64"
                            cropDataName="crop_data"
                            label="Foto Profil (Bisa Diatur Posisi, Zoom & Putar)"
                            :aspectRatio="1"
                            aspectRatioLabel="1:1 Bulat/Kotak"
                            :maxWidth="500"
                            :maxHeight="500"
                            :currentImage="$user->photo"
                            :previewSize="160"
                        />
                    </div>

                    {{-- Nama Panjang Siswa --}}
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-200 mb-1">Nama Panjang Siswa</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none" placeholder="Masukkan nama lengkap">
                        @error('name') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email Aktif --}}
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-200 mb-1">Email Aktif</label>
                        <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none" placeholder="nama@email.com">
                        @error('email') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-200 mb-1">Bio</label>
                        <textarea wire:model="bio" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none" placeholder="Tuliskan bio atau minat Anda..."></textarea>
                        @error('bio') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
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
