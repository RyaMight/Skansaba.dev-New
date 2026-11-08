{{-- Grid Karya Siswa: Menampilkan Data Asli + Katalog Dummy dengan UI Kartu Blueprint & Cover Image (Identik Screenshot) --}}
<div class="space-y-6">
    {{-- Filter Kategori --}}
    @if($categories->isNotEmpty())
        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                wire:click="$set('filterKategori', '')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $filterKategori === '' ? 'bg-sky-600 text-white border-sky-600 shadow-xs dark:bg-sky-600 dark:border-sky-600 dark:text-white' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800' }}"
            >
                {{ __('Semua Kategori') }}
            </button>
            @foreach($categories as $cat)
                <button
                    type="button"
                    wire:click="$set('filterKategori', '{{ $cat }}')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $filterKategori === $cat ? 'bg-sky-600 text-white border-sky-600 shadow-xs dark:bg-sky-600 dark:border-sky-600 dark:text-white' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800' }}"
                >
                    {{ __($cat) }}
                </button>
            @endforeach

            @if($search !== '' || $filterKategori !== '')
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
    @endif

    @if($karyas->isEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-slate-200 dark:border-gray-800 p-12 text-center">
            <div class="w-11 h-11 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
            </div>
            <h3 class="type-card-title-xs text-slate-900 dark:text-gray-100">{{ __('Tidak ada karya siswa yang cocok') }}</h3>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">{{ __('Coba gunakan kata kunci pencarian atau pilih kategori lain.') }}</p>
            <button
                type="button"
                wire:click="resetFilters"
                class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-bold bg-slate-900 dark:bg-sky-600 text-white hover:opacity-90 transition cursor-pointer"
            >
                {{ __('Tampilkan Semua Karya') }}
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-left">
            {{-- ================================================================ --}}
            {{-- 1. DATA ASLI DARI DATABASE ($karyas - published)                  --}}
            {{-- ================================================================ --}}
            @foreach($karyas as $item)
                @php
                    $creatorUser = ($item->user && $item->user->isSiswa())
                        ? $item->user
                        : ($siswaLookup[mb_strtolower(trim($item->nama_siswa ?? ''))] ?? null);
                    $creatorName = $creatorUser?->name ?? $item->nama_siswa ?? 'Siswa RPL';
                    $creatorInitials = $creatorUser
                        ? $creatorUser->initials()
                        : \Illuminate\Support\Str::of($creatorName)->explode(' ')->take(2)->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('');
                    $techList = is_array($item->teknologi) ? $item->teknologi : [];
                    $tahun = $item->tahun ?: date('Y');
                @endphp

                <a
                    href="{{ route('karya.detail', $item->id) }}"
                    class="group h-full bg-white dark:bg-gray-900 rounded-xl border border-slate-200/90 dark:border-gray-800 hover:border-sky-500/70 dark:hover:border-sky-500/60 shadow-xs transition-colors duration-150 overflow-hidden flex flex-col justify-between"
                >
                    <div class="flex-1 flex flex-col">
                        {{-- Cover Area --}}
                        @if($item->gambar_url)
                            <div class="relative aspect-[16/10] bg-slate-100 dark:bg-gray-950 border-b border-slate-200/80 dark:border-gray-800 overflow-hidden">
                                <img
                                    src="{{ $item->gambar_url }}"
                                    alt="{{ $item->judul }}"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
                                    loading="lazy"
                                >
                                @if($item->kategori)
                                    <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-900/85 text-white border border-white/15">
                                        {{ __($item->kategori) }}
                                    </span>
                                @endif
                            </div>
                        @else
                            {{-- Clean Architectural Software Blueprint Cover --}}
                            <div class="relative aspect-[16/10] bg-slate-100/90 dark:bg-[#111622] border-b border-slate-200/80 dark:border-gray-800 p-4 flex flex-col justify-between text-slate-700 dark:text-slate-200 select-none">
                                {{-- Top Window Chrome --}}
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                        <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                        <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                    </div>
                                    <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 truncate max-w-[150px]">
                                        {{ \Illuminate\Support\Str::slug($item->judul) }}.app
                                    </span>
                                </div>

                                {{-- Center Icon & Project Type --}}
                                <div class="my-auto flex items-center gap-3 py-1">
                                    <div class="w-10 h-10 rounded-lg bg-white dark:bg-sky-500/15 border border-slate-200/90 dark:border-sky-400/25 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 shadow-xs">
                                        @if(str_contains(strtolower($item->kategori ?? ''), 'mobile'))
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <rect x="6" y="2" width="12" height="20" rx="2"/>
                                                <line x1="12" y1="18" x2="12.01" y2="18"/>
                                            </svg>
                                        @elseif(str_contains(strtolower($item->kategori ?? ''), 'game'))
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @elseif(str_contains(strtolower($item->kategori ?? ''), 'ai'))
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        @elseif(str_contains(strtolower($item->kategori ?? ''), 'iot'))
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-semibold text-slate-800 dark:text-white truncate">{{ $item->judul }}</div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ $item->kategori ?: 'Rekayasa Perangkat Lunak' }}</div>
                                    </div>
                                </div>

                                {{-- Bottom Blueprint Bar --}}
                                <div class="flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-200 dark:border-slate-800/90">
                                    <span class="inline-flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                        {{ __('Terpublikasi') }}
                                    </span>
                                    <span class="font-mono text-slate-400 dark:text-slate-400">{{ $tahun }}</span>
                                </div>
                            </div>
                        @endif

                        {{-- Card Body --}}
                        <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <span class="text-[11px] font-semibold text-sky-600 dark:text-sky-400 truncate">
                                        {{ $item->kategori ? __($item->kategori) : __('Proyek RPL') }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 dark:text-gray-500 font-mono shrink-0">
                                        {{ $tahun }}
                                    </span>
                                </div>

                                <h3 class="type-card-title-xs text-slate-900 dark:text-gray-50 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors line-clamp-1" title="{{ $item->judul }}">
                                    {{ $item->judul }}
                                </h3>

                                <p class="type-body-xs text-slate-500 dark:text-gray-400 mt-1.5 line-clamp-2">
                                    {{ $item->deskripsi_singkat ?: $item->deskripsi_excerpt }}
                                </p>
                            </div>

                            {{-- Tech Stack Pills --}}
                            @if(count($techList) > 0)
                                <div class="flex flex-wrap gap-1.5 mt-3">
                                    @foreach(array_slice($techList, 0, 3) as $tech)
                                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border border-slate-200/80 dark:border-gray-700 rounded text-[10px] font-bold">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                    @if(count($techList) > 3)
                                        <span class="px-1.5 py-0.5 text-[10px] font-medium text-slate-400 dark:text-gray-500">
                                            +{{ count($techList) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card Footer: Creator & Link --}}
                    <div class="px-4 py-3 border-t border-slate-100 dark:border-gray-800/80 bg-slate-50/50 dark:bg-gray-900/50 flex items-center justify-between gap-2 mt-auto">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-6 h-6 rounded-full overflow-hidden bg-sky-50 dark:bg-sky-500/15 border border-sky-200/70 dark:border-sky-500/30 text-sky-700 dark:text-sky-300 flex items-center justify-center text-[10px] font-bold shrink-0">
                                @if($creatorUser && $creatorUser->photo_url)
                                    <img src="{{ $creatorUser->photo_url }}" alt="{{ $creatorName }}" class="w-full h-full object-cover">
                                @else
                                    {{ $creatorInitials }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="type-body-xs font-semibold text-slate-700 dark:text-gray-200 truncate">{{ $creatorName }}</p>
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-600 dark:text-sky-400 shrink-0 group-hover:translate-x-0.5 transition-transform">
                            <span>{{ __('Detail') }}</span>
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
