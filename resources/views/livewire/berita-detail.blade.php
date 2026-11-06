<div class="min-h-screen bg-gray-50 dark:bg-gray-800">
    {{-- Hero Section --}}
    <section class="bg-white dark:bg-gray-900 py-8 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="flex justify-center mb-6">
                <a href="/berita" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:opacity-80 transition" style="color: {{ $globalSettings->primary_color_style }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Kembali ke Berita') }}
                </a>
            </div>

            @if($berita->is_highlight)
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full mb-4" style="background-color: {{ $globalSettings->primary_color_style }}22; color: {{ $globalSettings->primary_color_style }}">
                    ⭐ {{ __('Berita Unggulan') }}
                </span>
            @endif

            <h1 class="text-2xl sm:text-4xl font-bold text-gray-900 dark:text-gray-50 mb-4">{{ $berita->title }}</h1>

            <div class="flex flex-wrap items-center gap-3 sm:gap-6 text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>{{ $berita->author->name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $berita->published_at->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>{{ $berita->views }} {{ __('views') }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm overflow-hidden">
                {{-- Featured Image --}}
                @if($berita->thumbnail)
                    <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="{{ $berita->title }}" class="w-full aspect-video object-cover">
                @else
                    <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300"></div>
                @endif

                {{-- Excerpt --}}
                @if($berita->excerpt)
                    <div class="p-8 bg-gray-50 dark:bg-gray-800 border-b">
                        <p class="text-lg text-gray-700 dark:text-gray-200 italic">{{ $berita->excerpt }}</p>
                    </div>
                @endif

                {{-- Content --}}
                <div class="p-8">
                    <div class="mb-5 pb-3.5 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500 dark:text-gray-400 notranslate">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            @if(app()->getLocale() === 'en')
                                <span>{{ __('Konten berita ini otomatis diterjemahkan ke Bahasa Inggris') }}</span>
                            @else
                                <span>{{ __('Tersedia terjemahan otomatis saat mode bahasa EN aktif') }}</span>
                            @endif
                        </div>
                        <a 
                            href="{{ route('locale.switch', app()->getLocale() === 'en' ? 'id' : 'en') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 hover:bg-slate-200 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 font-semibold transition shadow-2xs"
                        >
                            <span>{{ app()->getLocale() === 'en' ? __('Baca Versi Asli (ID)') : __('Terjemahkan ke English (EN)') }}</span>
                        </a>
                    </div>

                    <div class="prose max-w-none">
                        {!! $berita->content_html !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Berita --}}
    @if($relatedBeritas->count() > 0)
        <section class="py-12 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-50 mb-8">{{ __('Berita Terkait') }}</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedBeritas as $related)
                        <a href="/berita/{{ $related->slug }}" class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition group">
                            @if($related->thumbnail)
                                <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->title }}" class="w-full aspect-video object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300"></div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 dark:text-gray-50 mb-2 line-clamp-2">{{ $related->title }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 mb-2">{{ $related->excerpt }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $related->published_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
