<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- Header dengan Breadcrumb --}}
    <section class="bg-white dark:bg-gray-950 py-8">
        <div class="site-container text-center">
            {{-- Breadcrumb --}}
            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-4">
                <svg class="w-5 h-5" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <a href="/" class="hover:opacity-80 cursor-pointer">{{ __('Beranda') }}</a>
                <span>›</span>
                <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Berita & Artikel') }}</span>
            </div>
            
            <h1 class="text-2xl sm:text-3xl lg:text-5xl font-bold text-gray-900 dark:text-gray-50 mb-3">
                {{ __('Berita &') }} <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Artikel') }}</span>
            </h1>
            <p class="text-gray-600 dark:text-gray-300 text-sm sm:text-lg max-w-4xl mx-auto">{{ __('Informasi terkini seputar kegiatan dan berita RPL') }}</p>
        </div>
    </section>

    {{-- Search Only --}}
    <section class="py-6 bg-white dark:bg-gray-950">
        <div class="site-container">
            <div class="max-w-2xl">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="{{ __('Cari berita...') }}"
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:border-transparent text-gray-700 dark:text-gray-200"
                        style="--tw-ring-color: {{ $globalSettings->primary_color_style }}"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- Berita Grid --}}
    <section class="py-12">
        <div class="site-container">
            @if($beritas->count() > 0)
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($beritas as $berita)
                        <a href="/berita/{{ $berita->slug }}" class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition group">
                            @if($berita->thumbnail)
                                <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="{{ $berita->title }}" class="w-full aspect-video object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300"></div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    @if($berita->is_highlight)
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $globalSettings->primary_color_style }}22; color: {{ $globalSettings->primary_color_style }}">
                                            ⭐ {{ __('Unggulan') }}
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $berita->published_at->translatedFormat('d M Y') }}</span>
                                </div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-50 mb-2 line-clamp-2 group-hover:opacity-80">
                                    {{ $berita->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4">
                                    {{ $berita->excerpt ?? Str::limit(strip_tags($berita->content_md), 120) }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ $berita->author->name }}</span>
                                    <span>{{ $berita->views }} {{ __('views') }}</span>
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
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('Tidak ada berita ditemukan') }}</p>
                </div>
            @endif
        </div>
    </section>
</div>
