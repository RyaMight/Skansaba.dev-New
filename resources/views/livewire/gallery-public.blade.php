<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- Header dengan Breadcrumb --}}
    <section class="bg-white dark:bg-gray-950 py-8">
        <div class="site-container text-center">
            {{-- Breadcrumb --}}
            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-4">
                <svg class="w-5 h-5" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <a href="/" class="link-underline hover:opacity-80 cursor-pointer">{{ __('Beranda') }}</a>
                <span>›</span>
                <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Galeri') }}</span>
            </div>
            
            <h1 class="type-page-title text-gray-900 dark:text-gray-50 leading-tight mb-3">
                <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Galeri') }}</span> {{ __('Kegiatan') }}
            </h1>
            <p class="type-lead text-gray-600 dark:text-gray-300 max-w-4xl mx-auto">{{ __('Dokumentasi kegiatan dan momen Jurusan RPL') }}</p>
        </div>
    </section>

    {{-- Gallery Grid (Masonry) --}}
    <section class="py-12">
        <div class="site-container">
            @if($galleries->count() > 0)
                <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
                    @foreach($galleries as $gallery)
                        <div class="break-inside-avoid">
                            <a href="/gallery/{{ $gallery->id }}" class="card-live bg-white dark:bg-gray-900 rounded-xl overflow-hidden hover:shadow-lg group block">
                                @if($gallery->gambar_url)
                                    <div class="relative overflow-hidden">
                                        <img src="{{ $gallery->gambar_url }}" alt="{{ $gallery->judul }}" class="w-full h-auto object-cover group-hover:scale-105 transition duration-300">
                                        
                                        {{-- Overlay on hover --}}
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition duration-300 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                            </svg>
                                        </div>

                                        {{-- Image Count Badge --}}
                                        <div class="absolute bottom-2 right-2 bg-black/60 backdrop-blur-sm text-white text-[10px] px-2 py-1 rounded-md flex items-center gap-1 font-bold">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ __(':count Foto', ['count' => $gallery->images_count]) }}
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="card-live__title type-card-title-sm text-gray-900 dark:text-gray-50 flex-1">{{ $gallery->judul }}</h3>
                                        <div class="flex items-center gap-1">
                                            @if($gallery->kategori)
                                                <span class="px-3 py-1 text-xs font-bold rounded-full whitespace-nowrap bg-cyan-500/10 text-cyan-600">
                                                    {{ $gallery->kategori }}
                                                </span>
                                            @endif
                                            <button
                                                x-data="{ copied: false }"
                                                @click.prevent.stop="
                                                    navigator.clipboard.writeText(window.location.origin + '/gallery/{{ $gallery->id }}');
                                                    copied = true;
                                                    setTimeout(() => copied = false, 1800);
                                                "
                                                class="icon-pop p-1.5 text-gray-400 dark:text-gray-400 hover:text-cyan-600 hover:bg-cyan-50 rounded-lg"
                                                :class="copied ? 'text-emerald-600' : ''"
                                                :title="copied ? @js(__('Tersalin')) : @js(__('Salin Link'))"
                                                :aria-label="copied ? @js(__('Tersalin')) : @js(__('Salin Link'))"
                                            >
                                                <span class="icon-swap" :class="{ 'is-b': copied }">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 100-2.684 3 3 0 000 2.684zm0 12.684a3 3 0 100-2.684 3 3 0 000 2.684z"/>
                                                    </svg>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @if($gallery->deskripsi)
                                        <div class="type-body-sm text-gray-600 dark:text-gray-300 line-clamp-2 prose prose-sm max-w-none">
                                            {!! $gallery->deskripsi_html !!}
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background-color: {{ $globalSettings->primary_color_style }}22">
                        <svg class="w-8 h-8" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="type-subsection-title text-gray-900 dark:text-gray-50 mb-2">{{ __('Belum Ada Galeri') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        @if($filterKategori)
                            {{ __('Tidak ada galeri dalam kategori yang dipilih') }}
                        @else
                            {{ __('Galeri belum tersedia saat ini') }}
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </section>
</div>
