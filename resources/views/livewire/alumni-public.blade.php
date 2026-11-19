<div class="min-h-screen bg-slate-100 dark:bg-gray-950">
    {{-- Header ringkas: judul, subjudul, dan pencarian dalam satu blok --}}
    <section class="bg-slate-100 dark:bg-gray-950">
        <div class="site-container py-8 lg:py-11">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h1 class="type-page-title text-gray-900 dark:text-gray-50 leading-tight mb-2">
                        {{ __('Jejak') }} <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Alumni RPL') }}</span>
                    </h1>
                    <p class="type-lead text-gray-600 dark:text-gray-300">
                        {{ __('Alumni Jurusan Rekayasa Perangkat Lunak yang telah berkarir di berbagai bidang') }}
                    </p>
                </div>

                <div class="w-full lg:w-80 shrink-0">
                    <label for="alumni-search" class="sr-only">{{ __('Cari alumni') }}</label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            id="alumni-search"
                            type="search"
                            name="q"
                            autocomplete="off"
                            spellcheck="false"
                            wire:model.live.debounce.300ms="search"
                            placeholder="{{ __('Cari nama, institusi, atau bidang...') }}"
                            class="w-full pl-10 pr-10 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:border-transparent transition-colors"
                            style="--tw-ring-color: {{ $globalSettings->primary_color_style }}33"
                        >
                        @if($search !== '')
                            <button
                                type="button"
                                wire:click="$set('search', '')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-gray-200 p-0.5 rounded transition-colors cursor-pointer"
                                title="{{ __('Bersihkan pencarian') }}"
                                aria-label="{{ __('Bersihkan pencarian') }}"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Alumni per angkatan: pola kartu sama dengan Karya/Prestasi --}}
    <section class="pb-12 lg:pb-16">
        <div class="site-container">
            @if($alumniData->count() > 0)
                @foreach($alumniData as $tahun => $alumnis)
                    <div class="mb-10 last:mb-0">
                        <h2 class="type-section-title text-gray-900 dark:text-gray-50 mb-5 flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm font-bold" style="background-color: {{ $globalSettings->primary_color_style }}1a; color: {{ $globalSettings->primary_color_style }}">
                                {{ substr($tahun, -2) }}
                            </span>
                            {{ __('Angkatan :tahun', ['tahun' => $tahun]) }}
                            <span class="text-xs font-medium text-slate-400 dark:text-gray-500">{{ $alumnis->count() }} {{ __('alumni') }}</span>
                        </h2>

                        <div class="columns-1 sm:columns-2 lg:columns-3 gap-5">
                            @foreach($alumnis as $alumni)
                                @php
                                    $coverAspect = $loop->index % 3 === 1 ? 'aspect-[4/3]' : 'aspect-[16/9]';
                                @endphp

                                <article class="group relative mb-5 break-inside-avoid overflow-hidden rounded-2xl border border-slate-200/90 bg-white transition-colors duration-150 hover:border-sky-500/60 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-sky-500/50">
                                    {{-- Seluruh kartu bisa diklik tanpa link bersarang. --}}
                                    <a
                                        href="{{ route('alumni.detail', $alumni->id) }}"
                                        class="absolute inset-0 z-10 rounded-2xl focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-sky-500"
                                        aria-label="{{ __('Detail') }}: {{ $alumni->nama }}"
                                    ></a>

                                    <div class="relative {{ $coverAspect }} overflow-hidden bg-slate-100 dark:bg-[#0f1626]">
                                        @if($alumni->foto_url)
                                            <img
                                                src="{{ $alumni->foto_url }}"
                                                alt=""
                                                width="800"
                                                height="500"
                                                loading="lazy"
                                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 motion-reduce:transition-none group-hover:scale-[1.03] motion-reduce:group-hover:scale-100"
                                            >
                                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                                        @else
                                            {{-- Cover fallback: pola titik + inisial + angkatan. --}}
                                            <svg class="absolute inset-0 h-full w-full text-slate-300 dark:text-gray-700/60" aria-hidden="true">
                                                <defs>
                                                    <pattern id="alumni-dots-{{ $alumni->id }}" width="18" height="18" patternUnits="userSpaceOnUse">
                                                        <circle cx="1" cy="1" r="1" fill="currentColor"/>
                                                    </pattern>
                                                </defs>
                                                <rect width="100%" height="100%" fill="url(#alumni-dots-{{ $alumni->id }})"/>
                                            </svg>
                                            <div class="relative flex h-full flex-col items-center justify-center gap-2.5 px-4 text-center">
                                                <span class="flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200/90 bg-white text-lg font-bold text-sky-600 shadow-xs dark:border-gray-700 dark:bg-gray-900 dark:text-sky-400">
                                                    {{ strtoupper(substr($alumni->nama, 0, 2)) }}
                                                </span>
                                                <span class="max-w-full truncate font-mono text-[11px] text-slate-400 dark:text-gray-500">
                                                    {{ __('Alumni') }} · {{ $alumni->tahun_lulus }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="border-t border-slate-100 p-4 dark:border-gray-800">
                                        @if($alumni->status_alumni)
                                            @php
                                                $statusChip = match ($alumni->status_alumni) {
                                                    'kerja' => 'border-emerald-200/80 bg-emerald-50 text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/15 dark:text-emerald-300',
                                                    'kuliah' => 'border-sky-200/80 bg-sky-50 text-sky-800 dark:border-sky-500/30 dark:bg-sky-500/15 dark:text-sky-300',
                                                    'wirausaha' => 'border-violet-200/80 bg-violet-50 text-violet-800 dark:border-violet-500/30 dark:bg-violet-500/15 dark:text-violet-300',
                                                    default => 'border-slate-200 bg-slate-50 text-slate-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300',
                                                };
                                            @endphp
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-bold {{ $statusChip }}">
                                                    {{ $alumni->status_alumni_label }}
                                                </span>
                                            </div>
                                        @endif

                                        <h3 class="mt-2 line-clamp-2 text-base font-semibold tracking-tight text-balance text-slate-900 transition-colors group-hover:text-sky-600 dark:text-gray-50 dark:group-hover:text-sky-400">
                                            {{ $alumni->nama }}
                                        </h3>

                                        @if($alumni->institusi)
                                            <p class="mt-1 line-clamp-1 text-sm text-slate-500 dark:text-gray-400">{{ $alumni->institusi }}</p>
                                        @endif

                                        @if($alumni->bidang)
                                            <p class="mt-0.5 line-clamp-1 text-xs text-slate-400 dark:text-gray-500">{{ $alumni->bidang }}</p>
                                        @endif

                                        @if($alumni->deskripsi_excerpt)
                                            <p class="mt-2 line-clamp-3 text-pretty text-sm text-slate-600 dark:text-gray-300">
                                                {{ $alumni->deskripsi_excerpt }}
                                            </p>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="rounded-xl border border-slate-200 bg-white p-12 text-center dark:border-gray-800 dark:bg-gray-900">
                    <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-gray-800 dark:text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                        </svg>
                    </div>
                    <h3 class="type-card-title-xs text-slate-900 dark:text-gray-100">
                        @if($search || $filterStatus)
                            {{ __('Alumni tidak ditemukan') }}
                        @else
                            {{ __('Belum Ada Data Alumni') }}
                        @endif
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-gray-400">
                        @if($search || $filterStatus)
                            {{ __('Tidak ada alumni yang sesuai dengan pencarian Anda.') }}
                        @else
                            {{ __('Data alumni belum tersedia saat ini.') }}
                        @endif
                    </p>
                    @if($search !== '')
                        <button
                            type="button"
                            wire:click="$set('search', '')"
                            class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-bold text-white transition hover:opacity-90 cursor-pointer dark:bg-sky-600"
                        >
                            {{ __('Tampilkan Semua Alumni') }}
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>
