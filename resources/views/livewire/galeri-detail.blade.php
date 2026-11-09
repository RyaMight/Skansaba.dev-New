<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- Header dengan Breadcrumb --}}
    <section class="bg-white dark:bg-gray-950 py-8">
        <div class="site-container">
            {{-- Breadcrumb --}}
            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-6">
                <svg class="w-5 h-5" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <a href="/" class="link-underline hover:opacity-80">{{ __('Beranda') }}</a>
                <span>›</span>
                <a href="/gallery" class="link-underline hover:opacity-80">{{ __('Galeri') }}</a>
                <span>›</span>
                <span style="color: {{ $globalSettings->primary_color_style }}">{{ Str::limit($galeri->judul, 30) }}</span>
            </div>
            
            {{-- Back Button --}}
            <div class="flex justify-center">
                <a href="/gallery" class="btn-morph btn-morph--primary type-button inline-flex">
                    <svg class="btn-morph__icon btn-morph__icon--back w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Kembali ke Galeri') }}
                </a>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12">
        <div class="site-container">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm overflow-hidden">
                {{-- Featured Image --}}
                @if($galeri->gambar_url)
                    <img src="{{ $galeri->gambar_url }}" alt="{{ $galeri->judul }}" class="w-full h-auto object-cover">
                @else
                    <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                <div class="p-8">
                    {{-- Kategori Badge --}}
                    @if($galeri->kategori)
                        <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full bg-cyan-500/10 text-cyan-600">
                                {{ $galeri->kategori }}
                            </span>
                        </div>
                    @endif

                    {{-- Title --}}
                    <h1 class="type-page-title text-gray-900 dark:text-gray-50 mb-6">{{ $galeri->judul }}</h1>

                    {{-- Share Buttons --}}
                    <div class="flex flex-wrap items-center gap-4 mb-8 pb-6 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Bagikan:') }}</span>
                        <div class="flex flex-wrap gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                               target="_blank"
                               class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition shadow-sm"
                               title="{{ __('Bagikan ke Facebook') }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($galeri->judul) }}" 
                               target="_blank"
                               class="w-10 h-10 rounded-full bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition shadow-sm"
                               title="{{ __('Bagikan ke Twitter') }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($galeri->judul . ' ' . request()->fullUrl()) }}" 
                               target="_blank"
                               class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition shadow-sm"
                               title="{{ __('Bagikan ke WhatsApp') }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72 1.017 3.691 1.553 5.712 1.553h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                            <button onclick="if(navigator.share){ navigator.share({title: '{{ $galeri->judul }}', url: window.location.href}); } else { alert(@js(__('Browser tidak mendukung Web Share'))); }" 
                                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 dark:text-gray-200 flex items-center justify-center hover:bg-gray-300 transition shadow-sm sm:hidden"
                                    title="{{ __('Bagikan') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 100-2.684 3 3 0 000 2.684zm0 12.684a3 3 0 100-2.684 3 3 0 000 2.684z"/></svg>
                            </button>
                            <button onclick="navigator.clipboard.writeText(window.location.href); alert(@js(__('Link disalin!')))" 
                                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 dark:text-gray-200 flex items-center justify-center hover:bg-gray-300 transition shadow-sm hidden sm:flex"
                                    title="{{ __('Salin Link') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($galeri->deskripsi)
                        <div class="prose prose-lg max-w-none text-gray-600 dark:text-gray-300 mb-12">
                            {!! $galeri->deskripsi_html !!}
                        </div>
                    @endif

                    {{-- Additional Images --}}
                    @if($galeri->images->count() > 0)
                        <div class="border-t border-gray-100 dark:border-gray-800 pt-12">
                            <h2 class="type-section-title text-gray-900 dark:text-gray-50 mb-8 flex items-center gap-2">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ __('Foto Dokumentasi') }}
                                <span class="type-body-sm font-normal text-gray-500 dark:text-gray-400">({{ __(':count Foto', ['count' => $galeri->images->count()]) }})</span>
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($galeri->images as $img)
                                    <a href="{{ $img->url }}" target="_blank" class="group relative aspect-[4/3] rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 block">
                                        <img src="{{ $img->url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-white opacity-0 group-hover:opacity-100 transition duration-300 scale-50 group-hover:scale-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                            </svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Related Galeri --}}
            @if($relatedGaleris->count() > 0)
                <div class="mt-12">
                    <h2 class="type-section-title text-gray-900 dark:text-gray-50 mb-6">{{ __('Galeri Lainnya') }}</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($relatedGaleris as $related)
                            <a href="/gallery/{{ $related->id }}" class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition group">
                                @if($related->gambar_url)
                                    <img src="{{ $related->gambar_url }}" alt="{{ $related->judul }}" class="w-full aspect-video object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="type-card-title-sm text-gray-900 dark:text-gray-50 line-clamp-2">{{ $related->judul }}</h3>
                                    @if($related->kategori)
                                        <p class="type-body-sm text-cyan-600 mt-1">{{ $related->kategori }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
