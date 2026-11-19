{{-- Grid Karya Siswa: kartu showcase minimalis (cover, kategori, judul, deskripsi). --}}
<div class="space-y-6">
    {{-- Kontrol urutkan & filter: satu gugus terpusat, sejajar dengan judul dan search. --}}
    <div class="space-y-3">
        {{-- Urutkan Karya: rata kanan, sejajar dengan kolom pencarian di header. --}}
        <div class="flex flex-wrap items-center justify-end gap-2">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-gray-400">{{ __('Urutkan') }}</span>
            <button
                type="button"
                wire:click="$set('sort', 'terpopuler')"
                class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $sort === 'terpopuler' ? 'bg-sky-600 text-white border-sky-600 shadow-xs' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:border-sky-300' }}"
            >
                {{ __('Terpopuler') }}
            </button>
            <button
                type="button"
                wire:click="$set('sort', 'terbaru')"
                class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $sort === 'terbaru' ? 'bg-sky-600 text-white border-sky-600 shadow-xs' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:border-sky-300' }}"
            >
                {{ __('Terbaru') }}
            </button>
        </div>

        {{-- Filter Kategori --}}
        @if($categories->isNotEmpty())
            <div class="flex flex-wrap items-center justify-start gap-2">
                <button
                    type="button"
                    wire:click="$set('filterKategori', '')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $filterKategori === '' ? 'bg-sky-600 text-white border-sky-600 shadow-xs dark:bg-sky-600 dark:border-sky-600 dark:text-white' : 'morph-sweep morph-chip bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800' }}"
                >
                    {{ __('Semua Kategori') }}
                </button>
                @foreach($categories as $cat)
                    <button
                        type="button"
                        wire:click="$set('filterKategori', '{{ $cat }}')"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer {{ $filterKategori === $cat ? 'bg-sky-600 text-white border-sky-600 shadow-xs dark:bg-sky-600 dark:border-sky-600 dark:text-white' : 'morph-sweep morph-chip bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800' }}"
                    >
                        {{ __($cat) }}
                    </button>
                @endforeach

                @if($search !== '' || $filterKategori !== '')
                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="ml-auto inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-rose-600 dark:text-rose-400 hover:underline cursor-pointer sm:ml-0"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>{{ __('Reset Filter') }}</span>
                    </button>
                @endif
            </div>
        @endif
    </div>

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
        <div class="columns-1 sm:columns-2 lg:columns-3 gap-5">
            @foreach($karyas as $item)
                @php
                    $kategori = $item->kategori ?: 'Rekayasa Perangkat Lunak';
                    $kategoriLower = strtolower((string) $item->kategori);
                    $deskripsi = $item->deskripsi_singkat ?: $item->deskripsi_excerpt;
                    $coverAspect = $loop->index % 3 === 1 ? 'aspect-[4/3]' : 'aspect-[16/9]';
                    $iconPaths = match (true) {
                        str_contains($kategoriLower, 'mobile') => '<rect x="6" y="2" width="12" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
                        str_contains($kategoriLower, 'game') => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        str_contains($kategoriLower, 'ai') => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                        str_contains($kategoriLower, 'iot') => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>',
                        default => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                    };
                @endphp

                <article class="group relative mb-5 break-inside-avoid overflow-hidden rounded-2xl border border-slate-200/90 bg-white transition-colors duration-150 hover:border-sky-500/60 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-sky-500/50">
                    {{-- Seluruh kartu bisa diklik tanpa link bersarang. --}}
                    <a
                        href="{{ route('karya.detail', $item->id) }}"
                        class="absolute inset-0 z-10 rounded-2xl focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-sky-500"
                        aria-label="{{ __('Detail') }}: {{ $item->judul }}"
                    ></a>

                    <div class="relative {{ $coverAspect }} overflow-hidden bg-slate-100 dark:bg-[#0f1626]">
                        @if($item->gambar_url)
                            <img
                                src="{{ $item->gambar_url }}"
                                alt=""
                                width="800"
                                height="500"
                                loading="lazy"
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 motion-reduce:transition-none group-hover:scale-[1.03] motion-reduce:group-hover:scale-100"
                            >
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                        @else
                            {{-- Cover fallback: pola titik + ikon kategori + slug aplikasi. --}}
                            <svg class="absolute inset-0 h-full w-full text-slate-300 dark:text-gray-700/60" aria-hidden="true">
                                <defs>
                                    <pattern id="karya-dots-{{ $item->id }}" width="18" height="18" patternUnits="userSpaceOnUse">
                                        <circle cx="1" cy="1" r="1" fill="currentColor"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#karya-dots-{{ $item->id }})"/>
                            </svg>
                            <div class="relative flex h-full flex-col items-center justify-center gap-2.5 px-4 text-center">
                                <span class="flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200/90 bg-white text-sky-600 shadow-xs dark:border-gray-700 dark:bg-gray-900 dark:text-sky-400">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                        {!! $iconPaths !!}
                                    </svg>
                                </span>
                                <span class="max-w-full truncate font-mono text-[11px] text-slate-400 dark:text-gray-500">
                                    {{ \Illuminate\Support\Str::slug($item->judul) }}.app
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-slate-100 p-4 dark:border-gray-800">
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-gray-400">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                {!! $iconPaths !!}
                            </svg>
                            <span class="truncate">{{ __($kategori) }}</span>
                        </div>

                        <h3 class="mt-2 line-clamp-2 text-base font-semibold tracking-tight text-balance text-slate-900 transition-colors group-hover:text-sky-600 dark:text-gray-50 dark:group-hover:text-sky-400">
                            {{ $item->judul }}
                        </h3>

                        @if($deskripsi)
                            <p class="mt-1 line-clamp-3 text-sm text-pretty text-slate-500 dark:text-gray-400">
                                {{ $deskripsi }}
                            </p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>