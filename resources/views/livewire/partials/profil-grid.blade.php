{{-- Grid Profil Siswa Asli (35 Akun Siswa RPL dari Database) - Format 3 Kolom Sesuai Screenshot --}}
@php
    $avatarPalettes = [
        'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:border-emerald-500/30',
        'bg-sky-500/10 text-sky-400 border-sky-500/20 dark:bg-sky-500/15 dark:text-sky-300 dark:border-sky-500/30',
        'bg-amber-500/10 text-amber-400 border-amber-500/20 dark:bg-amber-500/15 dark:text-amber-300 dark:border-amber-500/30',
        'bg-indigo-500/10 text-indigo-400 border-indigo-500/20 dark:bg-indigo-500/15 dark:text-indigo-300 dark:border-indigo-500/30',
        'bg-teal-500/10 text-teal-400 border-teal-500/20 dark:bg-teal-500/15 dark:text-teal-300 dark:border-teal-500/30',
        'bg-rose-500/10 text-rose-400 border-rose-500/20 dark:bg-rose-500/15 dark:text-rose-300 dark:border-rose-500/30',
    ];
@endphp

<div class="space-y-6">
    {{-- Filter Bar Profil Siswa --}}
    <div class="flex flex-wrap items-center gap-2">
        <button
            type="button"
            wire:click="$set('filterProfil', '')"
            class="px-3.5 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $filterProfil === '' ? 'bg-sky-600 text-white border-sky-600 shadow-xs dark:bg-sky-600 dark:border-sky-600 dark:text-white' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800' }}"
        >
            {{ __('Semua Siswa') }} ({{ $totalSiswasCount }})
        </button>

        @if($siswaWithKaryaCount > 0)
            <button
                type="button"
                wire:click="$set('filterProfil', 'with_karya')"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $filterProfil === 'with_karya' ? 'bg-sky-600 text-white border-sky-600 shadow-xs dark:bg-sky-600 dark:border-sky-600 dark:text-white' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800' }}"
            >
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>{{ __('Memiliki Karya') }} ({{ $siswaWithKaryaCount }})</span>
            </button>
        @endif

        @foreach($availableSkills as $skill)
            <button
                type="button"
                wire:click="$set('filterProfil', '{{ $skill }}')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $filterProfil === $skill ? 'bg-sky-600 text-white border-sky-600 shadow-xs dark:bg-sky-600 dark:border-sky-600 dark:text-white' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800' }}"
            >
                {{ $skill }}
            </button>
        @endforeach

        @if($search !== '' || $filterProfil !== '')
            <button
                type="button"
                wire:click="resetFilters"
                class="ml-auto inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-rose-600 dark:text-rose-400 hover:underline cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>{{ __('Reset Filter') }}</span>
            </button>
        @endif
    </div>

    @if($siswas->isEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-slate-200 dark:border-gray-800 p-12 text-center">
            <div class="w-11 h-11 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="type-card-title-xs text-slate-900 dark:text-gray-100">{{ __('Tidak ada profil siswa yang cocok') }}</h3>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">{{ __('Coba cari dengan nama siswa, keahlian, atau kata kunci lain.') }}</p>
            <button
                type="button"
                wire:click="resetFilters"
                class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-bold bg-slate-900 dark:bg-sky-600 text-white hover:opacity-90 transition cursor-pointer"
            >
                {{ __('Tampilkan Semua Siswa') }}
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 text-left">
            @foreach($siswas as $index => $siswa)
                @php
                    $palette = $avatarPalettes[$siswa->id % count($avatarPalettes)];
                    $verifiedSkills = $siswa->keahlian_list;
                    $karyaTechs = $siswa->karyaSiswas
                        ->flatMap(fn ($k) => is_array($k->teknologi) ? $k->teknologi : [])
                        ->unique()
                        ->values()
                        ->all();
                @endphp

                <a
                    href="{{ $siswa->profil_url }}"
                    class="group h-full bg-white dark:bg-gray-900 rounded-xl p-4 sm:p-5 border border-slate-200/90 dark:border-gray-800 hover:border-sky-500/70 dark:hover:border-sky-500/60 shadow-xs transition-colors duration-150 flex flex-col justify-between"
                >
                    <div class="flex-1 flex flex-col">
                        {{-- Header: Avatar & Identitas Siswa --}}
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-xl overflow-hidden border {{ $siswa->photo_url ? 'border-slate-200 dark:border-gray-700 bg-slate-100 dark:bg-gray-800' : $palette }} flex items-center justify-center text-sm font-bold shrink-0">
                                @if($siswa->photo_url)
                                    <img
                                        src="{{ $siswa->photo_url }}"
                                        alt="{{ $siswa->name }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >
                                @else
                                    {{ $siswa->initials() }}
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="type-card-title-xs text-slate-900 dark:text-gray-50 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors line-clamp-1" title="{{ $siswa->name }}">
                                        {{ $siswa->name }}
                                    </h3>
                                </div>

                                <div class="flex flex-wrap items-center gap-1.5 mt-0.5">
                                    <span class="text-xs text-slate-500 dark:text-gray-400 font-mono truncate max-w-[200px]">
                                        {{ '@' . ($siswa->username ?: $siswa->id) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Bio atau Ringkasan Portofolio --}}
                        <div class="mt-3">
                            @if($siswa->bio)
                                <p class="type-body-xs text-slate-600 dark:text-gray-300 line-clamp-2">
                                    {{ $siswa->bio }}
                                </p>
                            @elseif($siswa->karyaSiswas->isNotEmpty())
                                <p class="type-body-xs text-slate-600 dark:text-gray-300 line-clamp-2">
                                    {{ __('Kreator proyek') }} <span class="font-semibold text-slate-800 dark:text-gray-100">{{ $siswa->karyaSiswas->pluck('judul')->join(', ') }}</span>.
                                </p>
                            @else
                                <p class="type-body-xs text-slate-500 dark:text-gray-400 line-clamp-2">
                                    {{ __('Siswa Rekayasa Perangkat Lunak SMKN 1 Bantul.') }}
                                </p>
                            @endif
                        </div>

                        {{-- Keahlian Terverifikasi / Tech Stack / Kelas --}}
                        <div class="mt-3 flex flex-wrap items-center gap-1.5">
                            @if(count($verifiedSkills) > 0)
                                @foreach(array_slice($verifiedSkills, 0, 3) as $skill)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-200/70 dark:border-emerald-500/30 rounded-md text-[10px] font-bold">
                                        <svg class="w-2.5 h-2.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>{{ $skill }}</span>
                                    </span>
                                @endforeach
                                @if(count($verifiedSkills) > 3)
                                    <span class="text-[10px] font-medium text-slate-400 dark:text-gray-500">
                                        +{{ count($verifiedSkills) - 3 }}
                                    </span>
                                @endif
                            @elseif(count($karyaTechs) > 0)
                                @foreach(array_slice($karyaTechs, 0, 3) as $tech)
                                    <span class="px-2 py-0.5 bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-200/70 dark:border-sky-500/30 rounded-md text-[10px] font-bold">
                                        {{ $tech }}
                                    </span>
                                @endforeach
                            @else
                                <span class="px-2 py-0.5 bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200/80 dark:border-gray-700 rounded-md text-[10px] font-bold">
                                    {{ $siswa->kelas ? __('Kelas :kelas', ['kelas' => $siswa->kelas]) : __('Rekayasa Perangkat Lunak') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Footer: Status Karya & CTA Lihat Profil --}}
                    <div class="mt-auto pt-3 border-t border-slate-100 dark:border-gray-800/80 flex items-center justify-between gap-2">
                        @if($siswa->published_karya_count > 0)
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-700 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>{{ $siswa->published_karya_count }} {{ __('Karya Terpublikasi') }}</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-500 dark:text-gray-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-gray-600"></span>
                                <span>{{ $siswa->kelas ?: __('Siswa RPL') }}</span>
                            </span>
                        @endif

                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-600 dark:text-sky-400 group-hover:translate-x-0.5 transition-transform shrink-0">
                            <span>{{ __('Lihat Profil') }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
