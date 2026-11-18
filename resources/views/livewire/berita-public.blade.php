<div class="min-h-screen bg-slate-100 dark:bg-slate-900">
    {{-- Header ringkas: judul, subjudul, dan pencarian dalam satu blok --}}
    <section class="bg-slate-100 dark:bg-slate-900 border-b border-gray-200/80 dark:border-gray-800">
        <div class="site-container py-8 lg:py-11">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="type-meta uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                        {{ __('Informasi') }}
                    </p>
                    <h1 class="type-page-title text-gray-900 dark:text-gray-50 leading-tight mb-2">
                        {{ __('Berita &') }} <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Artikel') }}</span>
                    </h1>
                    <p class="type-lead text-gray-600 dark:text-gray-300">
                        {{ __('Informasi terkini seputar kegiatan dan berita RPL') }}
                    </p>
                </div>

                <div class="w-full lg:w-80 shrink-0">
                    <label for="berita-search" class="sr-only">{{ __('Cari berita') }}</label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            id="berita-search"
                            type="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="{{ __('Cari berita...') }}"
                            class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:border-transparent transition-colors"
                            style="--tw-ring-color: {{ $globalSettings->primary_color_style }}33"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Berita Grid --}}
    <section class="py-10 lg:py-14">
        <div class="site-container">
            @if($beritas->count() > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
                    @foreach($beritas as $berita)
                        <a href="/berita/{{ $berita->slug }}" class="card-live group flex flex-col bg-white dark:bg-gray-900 rounded-xl overflow-hidden border border-gray-200/80 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700 transition-colors">
                            <div class="relative overflow-hidden">
                                @if($berita->thumbnail)
                                    <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="{{ $berita->title }}" class="w-full aspect-video object-cover group-hover:scale-[1.03] transition-transform duration-500 ease-out">
                                @else
                                    <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-800 dark:to-gray-700"></div>
                                @endif
                                @if($berita->is_highlight)
                                    <span class="absolute top-3 left-3 inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold rounded-full backdrop-blur-sm" style="background-color: {{ $globalSettings->primary_color_style }}1f; color: {{ $globalSettings->primary_color_style }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L1.5 7.7l5.9-.9z"/></svg>
                                        {{ __('Unggulan') }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <span class="type-meta text-gray-500 dark:text-gray-400 mb-2">{{ $berita->published_at->translatedFormat('d M Y') }}</span>
                                <h3 class="type-card-title text-gray-900 dark:text-gray-50 mb-2 line-clamp-2">
                                    {{ $berita->title }}
                                </h3>
                                <p class="type-body-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4">
                                    {{ $berita->excerpt ?? Str::limit(strip_tags($berita->content_md), 120) }}
                                </p>
                                <div class="type-meta mt-auto flex items-center justify-between text-gray-500 dark:text-gray-400 pt-3 border-t border-gray-100 dark:border-gray-800">
                                    <span class="truncate">{{ $berita->author->name }}</span>
                                    <span class="shrink-0">{{ $berita->views }} {{ __('views') }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $beritas->links() }}
                </div>
            @else
                <div class="text-center py-16 border border-dashed border-gray-200 dark:border-gray-800 rounded-xl bg-white/50 dark:bg-gray-900/40">
                    <svg class="w-14 h-14 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <p class="type-subsection-title text-gray-600 dark:text-gray-300">{{ __('Tidak ada berita ditemukan') }}</p>
                    @if($search !== '')
                        <p class="type-body-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Coba kata kunci lain.') }}</p>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>