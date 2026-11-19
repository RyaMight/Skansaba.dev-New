<div class="min-h-screen bg-slate-100 dark:bg-gray-950">
    {{-- Header ringkas: judul, subjudul, dan pencarian dalam satu blok --}}
    <section class="bg-slate-100 dark:bg-gray-950">
        <div class="site-container py-8 lg:py-11">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h1 class="type-page-title text-gray-900 dark:text-gray-50 leading-tight mb-2">
                        {{ __('Prestasi') }} <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Siswa RPL') }}</span>
                    </h1>
                    <p class="type-lead text-gray-600 dark:text-gray-300">
                        {{ __('Pencapaian dan prestasi terbaik siswa Rekayasa Perangkat Lunak') }}
                    </p>
                </div>

                <div class="w-full lg:w-80 shrink-0">
                    <label for="prestasi-search" class="sr-only">{{ __('Cari prestasi') }}</label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            id="prestasi-search"
                            type="search"
                            name="q"
                            autocomplete="off"
                            spellcheck="false"
                            wire:model.live.debounce.300ms="search"
                            placeholder="{{ __('Cari judul, siswa, atau penyelenggara...') }}"
                            class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:border-transparent transition-colors"
                            style="--tw-ring-color: {{ $globalSettings->primary_color_style }}33"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Prestasi Grid: pola kartu sama dengan Karya Siswa --}}
    <section class="pb-12 lg:pb-16">
        <div class="site-container">
            @if($prestasis->count() > 0)
                <div class="columns-1 sm:columns-2 lg:columns-3 gap-5">
                    @foreach($prestasis as $prestasi)
                        @php
                            $coverAspect = $loop->index % 3 === 1 ? 'aspect-[4/3]' : 'aspect-[16/9]';
                        @endphp

                        <article class="group relative mb-5 break-inside-avoid overflow-hidden rounded-2xl border border-slate-200/90 bg-white transition-colors duration-150 hover:border-sky-500/60 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-sky-500/50">
                            {{-- Seluruh kartu bisa diklik tanpa link bersarang. --}}
                            <a
                                href="{{ route('prestasi.detail', $prestasi->id) }}"
                                class="absolute inset-0 z-10 rounded-2xl focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-sky-500"
                                aria-label="{{ __('Detail') }}: {{ $prestasi->judul }}"
                            ></a>

                            <div class="relative {{ $coverAspect }} overflow-hidden bg-slate-100 dark:bg-[#0f1626]">
                                @if($prestasi->gambar_url)
                                    <img
                                        src="{{ $prestasi->gambar_url }}"
                                        alt=""
                                        width="800"
                                        height="500"
                                        loading="lazy"
                                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 motion-reduce:transition-none group-hover:scale-[1.03] motion-reduce:group-hover:scale-100"
                                    >
                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                                @else
                                    {{-- Cover fallback: pola titik + ikon prestasi + tingkat/tahun. --}}
                                    <svg class="absolute inset-0 h-full w-full text-slate-300 dark:text-gray-700/60" aria-hidden="true">
                                        <defs>
                                            <pattern id="prestasi-dots-{{ $prestasi->id }}" width="18" height="18" patternUnits="userSpaceOnUse">
                                                <circle cx="1" cy="1" r="1" fill="currentColor"/>
                                            </pattern>
                                        </defs>
                                        <rect width="100%" height="100%" fill="url(#prestasi-dots-{{ $prestasi->id }})"/>
                                    </svg>
                                    <div class="relative flex h-full flex-col items-center justify-center gap-2.5 px-4 text-center">
                                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200/90 bg-white text-sky-600 shadow-xs dark:border-gray-700 dark:bg-gray-900 dark:text-sky-400">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                            </svg>
                                        </span>
                                        <span class="max-w-full truncate font-mono text-[11px] text-slate-400 dark:text-gray-500">
                                            {{ __(ucfirst($prestasi->tingkat)) }} · {{ $prestasi->tahun ?? $prestasi->tanggal_prestasi->format('Y') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="border-t border-slate-100 p-4 dark:border-gray-800">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 dark:bg-amber-500/15 border border-amber-200/80 dark:border-amber-500/30 text-amber-800 dark:text-amber-300 text-xs font-bold rounded-full">
                                        <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        <span>{{ __(ucfirst($prestasi->jenis)) }}</span>
                                    </span>
                                    <span class="inline-block px-2 py-1 bg-blue-100 dark:bg-blue-500/20 text-blue-800 dark:text-blue-300 text-xs font-bold rounded-full">
                                        {{ __(ucfirst($prestasi->tingkat)) }}
                                    </span>
                                </div>

                                <h3 class="mt-2 line-clamp-2 text-base font-semibold tracking-tight text-balance text-slate-900 transition-colors group-hover:text-sky-600 dark:text-gray-50 dark:group-hover:text-sky-400">
                                    {{ $prestasi->judul }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500 dark:text-gray-400">{{ $prestasi->nama_siswa }}</p>
                                <p class="mt-1 text-xs text-slate-400 dark:text-gray-500">{{ $prestasi->tanggal_prestasi->translatedFormat('d M Y') }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $prestasis->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-slate-200 dark:border-gray-800 p-12 text-center">
                    <div class="w-11 h-11 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <h3 class="type-card-title-xs text-slate-900 dark:text-gray-100">{{ __('Tidak ada prestasi ditemukan') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">{{ __('Coba gunakan kata kunci pencarian lain.') }}</p>
                    @if($search !== '')
                        <button
                            type="button"
                            wire:click="$set('search', '')"
                            class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-bold bg-slate-900 dark:bg-sky-600 text-white hover:opacity-90 transition cursor-pointer"
                        >
                            {{ __('Tampilkan Semua Prestasi') }}
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>
