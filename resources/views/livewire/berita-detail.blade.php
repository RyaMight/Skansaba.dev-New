@php
    $shareUrl = url()->current();
    $shareText = $berita->title;
    $shareTargets = [
        'whatsapp' => 'https://wa.me/?text=' . urlencode($shareText . ' ' . $shareUrl),
        'x' => 'https://twitter.com/intent/tweet?text=' . urlencode($shareText) . '&url=' . urlencode($shareUrl),
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($shareUrl),
        'telegram' => 'https://t.me/share/url?url=' . urlencode($shareUrl) . '&text=' . urlencode($shareText),
    ];
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- Hero Section --}}
    <section class="bg-white dark:bg-gray-950 pt-12 sm:pt-16 pb-6 sm:pb-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="flex justify-center mb-6">
                <a href="/berita" class="btn-morph btn-morph--primary type-button inline-flex">
                    <svg class="btn-morph__icon btn-morph__icon--back w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Kembali ke Berita') }}
                </a>
            </div>

            @if($berita->is_highlight)
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full mb-4" style="background-color: {{ $globalSettings->primary_color_style }}22; color: {{ $globalSettings->primary_color_style }}">
                    ⭐ {{ __('Berita Unggulan') }}
                </span>
            @endif

            <h1 class="type-page-title text-gray-900 dark:text-gray-50 mb-4">{{ $berita->title }}</h1>

            <div class="type-body-sm flex flex-wrap items-center gap-3 sm:gap-6 text-gray-600 dark:text-gray-300">
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

            {{-- Bagikan artikel --}}
            <div
                class="mt-7 pt-5 border-t border-gray-200/80 dark:border-gray-800 flex flex-wrap items-center gap-2"
                x-data="{
                    copied: false,
                    copyLink() {
                        const url = this.$el.dataset.shareUrl;
                        const done = () => { this.copied = true; setTimeout(() => this.copied = false, 1800); };
                        const legacy = () => {
                            const ta = document.createElement('textarea');
                            ta.value = url;
                            ta.setAttribute('readonly', '');
                            ta.style.position = 'fixed';
                            ta.style.opacity = '0';
                            document.body.appendChild(ta);
                            ta.select();
                            let ok = false;
                            try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
                            document.body.removeChild(ta);
                            if (ok) { done(); } else { window.prompt(this.$el.dataset.promptLabel, url); }
                        };
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(url).then(done).catch(legacy);
                        } else {
                            legacy();
                        }
                    }
                }"
                data-share-url="{{ $shareUrl }}"
                data-prompt-label="{{ __('Salin tautan:') }}"
                data-copied-label="{{ __('Tersalin!') }}"
                data-copy-label="{{ __('Salin tautan') }}"
            >
                <span class="type-meta uppercase tracking-wider text-gray-500 dark:text-gray-400 mr-1">{{ __('Bagikan') }}</span>

                <a href="{{ $shareTargets['whatsapp'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('Bagikan ke WhatsApp') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 dark:border-gray-800 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2a9.9 9.9 0 00-8.45 15.1L2 22l5.05-1.32A9.9 9.9 0 1012.04 2zm0 1.66a8.24 8.24 0 016.99 12.6l-.23.36.5 1.84-1.9-.5-.36.21a8.24 8.24 0 01-11.4-11.4 8.2 8.2 0 016.4-3.11zm-3.6 4.2c-.17 0-.45.06-.68.31-.23.25-.9.87-.9 2.13s.92 2.47 1.05 2.64c.13.17 1.8 2.9 4.44 3.95 2.2.87 2.65.7 3.13.66.48-.05 1.55-.63 1.77-1.24.22-.61.22-1.13.15-1.24-.06-.11-.24-.17-.5-.3-.27-.13-1.55-.77-1.79-.85-.24-.09-.42-.13-.6.13-.18.25-.7.88-.86 1.06-.16.18-.31.2-.57.07-.27-.13-1.11-.41-2.12-1.31-.78-.7-1.31-1.56-1.47-1.82-.15-.25-.02-.39.11-.52.12-.11.27-.3.4-.44.14-.15.18-.25.27-.42.09-.17.04-.32-.02-.45-.07-.13-.6-1.45-.82-1.98-.22-.52-.44-.45-.6-.45z"/></svg>
                    WhatsApp
                </a>

                <a href="{{ $shareTargets['x'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('Bagikan ke X') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 dark:border-gray-800 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.9 2H22l-6.77 7.74L23.3 22h-6.24l-4.89-6.4L6.6 22H3.5l7.24-8.28L1 2h6.4l4.42 5.85L18.9 2zm-1.1 18h1.73L7.3 3.8H5.45L17.8 20z"/></svg>
                    X
                </a>

                <a href="{{ $shareTargets['facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('Bagikan ke Facebook') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 dark:border-gray-800 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M13.5 21v-8.2h2.76l.41-3.2H13.5V7.55c0-.93.26-1.56 1.59-1.56h1.7V3.13c-.3-.04-1.31-.13-2.49-.13-2.46 0-4.15 1.5-4.15 4.27v2.33H7.38v3.2h2.77V21h3.35z"/></svg>
                    Facebook
                </a>

                <a href="{{ $shareTargets['telegram'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('Bagikan ke Telegram') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 dark:border-gray-800 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M21.9 4.3l-3 14.2c-.23 1-.82 1.25-1.66.78l-4.6-3.39-2.22 2.14c-.25.25-.45.45-.92.45l.33-4.68 8.5-7.68c.37-.33-.08-.51-.57-.18L7.27 13.3l-4.52-1.41c-.98-.31-1-.98.2-1.45L20.4 3.1c.82-.3 1.53.2 1.5 1.2z"/></svg>
                    Telegram
                </a>

                <button type="button" @click="copyLink()"
                        class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 dark:border-gray-800 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <span x-text="copied ? $root.dataset.copiedLabel : $root.dataset.copyLabel">{{ __('Salin tautan') }}</span>
                </button>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="pt-6 lg:pt-8 pb-14 lg:pb-20">
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
                        <p class="type-lead text-gray-700 dark:text-gray-200 italic">{{ $berita->excerpt }}</p>
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
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 hover:bg-slate-200 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 font-bold transition shadow-2xs"
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
        <section class="py-14 lg:py-20 bg-white dark:bg-gray-950">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="type-section-title text-gray-900 dark:text-gray-50 mb-8">{{ __('Berita Terkait') }}</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedBeritas as $related)
                        <a href="/berita/{{ $related->slug }}" class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition group">
                            @if($related->thumbnail)
                                <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->title }}" class="w-full aspect-video object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300"></div>
                            @endif
                            <div class="p-4">
                                <h3 class="type-card-title-sm text-gray-900 dark:text-gray-50 mb-2 line-clamp-2">{{ $related->title }}</h3>
                                <p class="type-body-sm text-gray-600 dark:text-gray-300 line-clamp-2 mb-2">{{ $related->excerpt }}</p>
                                <p class="type-meta text-gray-500 dark:text-gray-400">{{ $related->published_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
