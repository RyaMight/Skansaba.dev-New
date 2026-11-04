<div class="p-6 max-w-7xl mx-auto space-y-6">
    {{-- Toast Notifikasi --}}
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
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-50 tracking-tight">Selamat datang, {{ strtok($user->name ?? '', ' ') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Ringkasan aktivitas karya dan prestasi kamu.</p>
        </div>
        <div class="flex items-center gap-2">
            <a
                href="{{ $user->profil_url }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-3 py-2 border border-sky-300 dark:border-sky-500/30 text-sky-600 dark:text-sky-400 bg-sky-50/50 dark:bg-sky-500/10 hover:bg-sky-50 dark:hover:bg-sky-500/20 rounded-lg text-xs font-semibold transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>Lihat Profil Publik</span>
            </a>
            <a
                href="{{ route('siswa.profil-publik') }}"
                wire:navigate
                class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg text-xs font-semibold transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                <span>Hias Profil Publik</span>
            </a>
        </div>
    </div>

    {{-- Student Profile & Tech Stack Hero Banner --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4 min-w-0">
            <div class="relative w-14 h-14 rounded-full overflow-hidden bg-slate-100 dark:bg-gray-800 ring-2 ring-gray-100 dark:ring-gray-700 shrink-0">
                @if($user->photo_url)
                    <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white text-lg font-bold">
                        {{ $user->initials() }}
                    </div>
                @endif
            </div>
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base md:text-lg font-bold text-gray-900 dark:text-gray-50 truncate">{{ $user->name }}</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-200/70 dark:border-sky-500/30">
                        {{ $user->kelas ? 'Kelas ' . $user->kelas : 'Siswa RPL' }}
                    </span>
                    <span class="text-xs text-gray-400">• NISN: {{ $user->nisn ?: '-' }}</span>
                </div>
                
                {{-- Keahlian / Tech Stack Badges --}}
                <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mr-0.5 flex items-center gap-1">
                        <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        <span>Tech Stack:</span>
                    </span>
                    @forelse($user->keahlian_list as $skill)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-200/70 dark:border-sky-500/30 rounded-md text-[11px] font-medium">
                            <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $skill }}</span>
                        </span>
                    @empty
                        <a href="{{ route('siswa.profil-publik') }}" wire:navigate class="inline-flex items-center gap-1 text-[11px] text-sky-600 dark:text-sky-400 hover:text-sky-700 italic hover:underline">
                            + Tambah Keahlian / Tech Stack
                        </a>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="shrink-0 flex items-center gap-2">
            <a
                href="{{ route('siswa.profil') }}"
                wire:navigate
                class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-gray-200 hover:bg-slate-50 dark:hover:bg-gray-800 rounded-lg text-xs font-semibold transition"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Kelola Profil</span>
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center text-sky-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Karya Published</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 mt-0.5">{{ $karyaCount }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Sedang Review</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 mt-0.5">{{ $reviewCount }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2m8-2v4H8V2h8z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Draft</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 mt-0.5">{{ $draftCount }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Prestasi</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 mt-0.5">{{ $prestasiCount }}</p>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Karya Terbaru --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base md:text-lg font-bold text-gray-900 dark:text-gray-50">Karya Terbaru</h3>
                <a
                    href="{{ route('karya-siswa.index') }}"
                    wire:navigate
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-sky-300 dark:border-sky-500/30 text-sky-600 dark:text-sky-400 bg-sky-50/50 dark:bg-sky-500/10 hover:bg-sky-50 dark:hover:bg-sky-500/20 rounded-lg text-xs font-semibold transition"
                >
                    <span class="text-sm leading-none">+</span>
                    <span>Kelola Karya</span>
                </a>
            </div>

            @if($karyaTerbaru->isEmpty())
                <div class="text-center py-10 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-xl">
                    <p class="text-sm text-gray-400 dark:text-gray-400">Belum ada karya. Mulai buat karya pertamamu!</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($karyaTerbaru as $karya)
                        <a href="{{ route('karya.detail', $karya->id) }}" class="flex items-center gap-4 p-3 rounded-xl border border-gray-100 dark:border-gray-800 hover:border-sky-200 dark:hover:border-sky-500/30 hover:bg-sky-50/30 dark:hover:bg-sky-500/10 transition group">
                            <div class="w-14 h-14 rounded-lg bg-slate-100 dark:bg-gray-800 overflow-hidden shrink-0 flex items-center justify-center">
                                @if($karya->gambar_url)
                                    <img src="{{ $karya->gambar_url }}" alt="{{ $karya->judul }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-50 truncate group-hover:text-sky-600 transition">{{ $karya->judul }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $karya->kategori ?? 'Karya' }}
                                    @if($karya->teknologi && is_array($karya->teknologi) && count($karya->teknologi))
                                        • {{ implode(', ', array_slice($karya->teknologi, 0, 3)) }}
                                    @endif
                                </p>
                            </div>
                            <span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold
                                @if($karya->status === 'published') bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300
                                @elseif($karya->status === 'review') bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300
                                @elseif($karya->status === 'archived') bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300
                                @else bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 @endif">
                                {{ ucfirst($karya->status) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Prestasi & Profile Completion --}}
        <div class="space-y-6">
            {{-- Kelengkapan Profil --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-50 mb-4">Kelengkapan Profil</h3>
                @php
                    $checks = [
                        'Foto profil' => (bool) $user->photo,
                        'Bio' => (bool) $user->bio,
                        'README profil' => (bool) $user->profil_readme,
                        'Username kustom' => (bool) $user->username,
                        'Keahlian & Tech Stack' => (bool) !empty(trim($user->keahlian ?? '')),
                    ];
                    $filled = count(array_filter($checks));
                    $total = count($checks);
                    $percent = (int) round($filled / $total * 100);
                @endphp
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl font-bold {{ $percent >= 80 ? 'text-emerald-600' : ($percent >= 50 ? 'text-amber-600' : 'text-gray-400 dark:text-gray-400') }}">{{ $percent }}%</span>
                    <span class="text-xs text-gray-400 dark:text-gray-400">{{ $filled }}/{{ $total }} terisi</span>
                </div>
                <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden mb-4">
                    <div class="h-full rounded-full {{ $percent >= 80 ? 'bg-emerald-500' : ($percent >= 50 ? 'bg-amber-500' : 'bg-gray-300') }}" style="width: {{ $percent }}%"></div>
                </div>
                <div class="space-y-1.5">
                    @foreach($checks as $label => $done)
                        <div class="flex items-center gap-2 text-xs">
                            @if($done)
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300 line-through decoration-gray-300">{{ $label }}</span>
                            @else
                                <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9" stroke-width="2"/>
                                </svg>
                                <span class="text-gray-500 dark:text-gray-400">{{ $label }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('siswa.profil-publik') }}" wire:navigate class="mt-4 block text-center px-3 py-2 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold transition">
                    Lengkapi Sekarang
                </a>
            </div>

            {{-- Prestasi --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-50 mb-4">Prestasi Terbaru</h3>
                @if($prestasiTerbaru->isEmpty())
                    <p class="text-xs text-gray-400 dark:text-gray-400 text-center py-4">Belum ada prestasi yang dipublikasikan.</p>
                @else
                    <div class="space-y-3">
                        @foreach($prestasiTerbaru as $prestasi)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-500 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 dark:text-gray-50 truncate">{{ $prestasi->judul }}</p>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">{{ $prestasi->tingkat ?? '-' }} • {{ $prestasi->penyelenggara ?? '-' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
