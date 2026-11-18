@php
    $shareUrl = url()->current();
    $shareText = $berita->title;
    $shareTargets = [
        'whatsapp' => 'https://wa.me/?text=' . urlencode($shareText . ' ' . $shareUrl),
        'x' => 'https://twitter.com/intent/tweet?text=' . urlencode($shareText) . '&url=' . urlencode($shareUrl),
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($shareUrl),
        'telegram' => 'https://t.me/share/url?url=' . urlencode($shareUrl) . '&text=' . urlencode($shareText),
        'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($shareUrl),
    ];
@endphp

<div class="min-h-screen bg-slate-100 dark:bg-gray-950">
    {{-- Hero Section --}}
    <section class="bg-slate-100 dark:bg-gray-950 pt-12 sm:pt-16 pb-6 sm:pb-8">
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
                class="mt-7 border-t border-gray-200/80 pt-5 dark:border-gray-800"
                x-data="{
                    open: false,
                    copied: false,
                    copyFailed: false,
                    show() {
                        this.open = true;
                        this.$el.dataset.prevOverflow = document.body.style.overflow;
                        document.body.style.overflow = 'hidden';
                    },
                    close() {
                        this.open = false;
                        document.body.style.overflow = this.$el.dataset.prevOverflow || '';
                    },
                    copyLink() {
                        const url = this.$el.dataset.shareUrl;
                        const done = () => { this.copied = true; setTimeout(() => this.copied = false, 1800); };
                        const fail = () => {
                            this.copyFailed = true;
                            setTimeout(() => this.copyFailed = false, 4000);
                            const input = this.$refs.link;
                            if (input) { input.focus(); input.select(); }
                        };
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
                            if (ok) { done(); } else { fail(); }
                        };
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(url).then(done).catch(legacy);
                        } else {
                            legacy();
                        }
                    }
                }"
                data-share-url="{{ $shareUrl }}"
                @keydown.escape.window="close()"
            >
                {{-- Pemicu: satu tombol besar --}}
                <button
                    type="button"
                    @click="show()"
                    aria-haspopup="dialog"
                    class="group relative inline-flex h-11 cursor-pointer items-center justify-center gap-2.5 overflow-hidden rounded-2xl border border-white/[0.06] bg-zinc-900 px-5 text-[13px] font-medium tracking-tight text-zinc-100 shadow-[inset_0px_0.5px_0px_1px_#65656522] drop-shadow-md transition-all duration-200 hover:bg-zinc-800 active:scale-[0.97] dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white"
                >
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/>
                        <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>
                    </svg>
                    <span>{{ __('Bagikan') }}</span>
                </button>

                {{-- Dialog bagikan --}}
                <div
                    x-show="open"
                    x-cloak
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="share-dialog-title"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                >
                    <div class="absolute inset-0 bg-slate-950/55 backdrop-blur-sm" @click="close()"></div>

                    <div class="relative max-h-[calc(100vh-2rem)] w-full max-w-md overflow-y-auto rounded-[28px] border border-gray-200/80 bg-white p-6 shadow-2xl dark:border-gray-800 dark:bg-gray-900 sm:p-8">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/>
                                    <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>
                                </svg>
                            </div>
                            <button
                                type="button"
                                @click="close()"
                                aria-label="{{ __('Tutup') }}"
                                class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-full border border-gray-200 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-100"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <h2 id="share-dialog-title" class="mt-5 text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-50">
                            {{ __('Bagikan Artikel') }}
                        </h2>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500 dark:text-gray-400">
                            {{ __('Berita lebih bermanfaat saat dibagikan ke teman dan jaringanmu.') }}
                        </p>

                        <p class="mt-6 text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Tautan artikel') }}</p>
                        <div class="mt-2 flex items-center gap-2 rounded-2xl border border-gray-200 bg-gray-50 p-1.5 pl-4 dark:border-gray-700 dark:bg-gray-800/60">
                            <input
                                x-ref="link"
                                type="text"
                                readonly
                                value="{{ $shareUrl }}"
                                @focus="$el.select()"
                                aria-label="{{ __('Tautan artikel') }}"
                                class="min-w-0 flex-1 border-0 bg-transparent p-0 text-sm text-gray-700 focus:outline-none focus:ring-0 dark:text-gray-300"
                            >
                            <button
                                type="button"
                                @click="copyLink()"
                                class="inline-flex shrink-0 cursor-pointer items-center gap-1.5 rounded-xl bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm ring-1 ring-gray-200 transition-colors hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:ring-gray-700 dark:hover:bg-gray-800"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="9" y="9" width="12" height="12" rx="2.5"/>
                                    <path d="M6 15H5a2 2 0 01-2-2V5a2 2 0 012-2h8a2 2 0 012 2v1"/>
                                </svg>
                                <span x-text="copied ? @js(__('Tersalin')) : @js(__('Salin'))">{{ __('Salin') }}</span>
                            </button>
                        </div>
                        <p x-show="copyFailed" x-cloak class="mt-2 text-xs text-amber-600 dark:text-amber-400">
                            {{ __('Tautan sudah dipilih, tekan Ctrl+C untuk menyalin.') }}
                        </p>

                        <p class="mt-6 text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Bagikan ke') }}</p>
                        <div class="mt-3 grid grid-cols-5 gap-1 sm:gap-2">
                            <a href="{{ $shareTargets['facebook'] }}" target="_blank" rel="noopener noreferrer" class="group flex flex-col items-center gap-2 text-center">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full text-white transition-transform duration-200 group-hover:-translate-y-0.5" style="background-color: #1877F2">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-8.2h2.76l.41-3.2H13.5V7.55c0-.93.26-1.56 1.59-1.56h1.7V3.13c-.3-.04-1.31-.13-2.49-.13-2.46 0-4.15 1.5-4.15 4.27v2.33H7.38v3.2h2.77V21h3.35z"/></svg>
                                </span>
                                <span class="text-[11px] font-medium text-gray-600 dark:text-gray-400">Facebook</span>
                            </a>

                            <a href="{{ $shareTargets['x'] }}" target="_blank" rel="noopener noreferrer" class="group flex flex-col items-center gap-2 text-center">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full text-white ring-1 ring-white/10 transition-transform duration-200 group-hover:-translate-y-0.5" style="background-color: #0f1419">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 2H22l-6.77 7.74L23.3 22h-6.24l-4.89-6.4L6.6 22H3.5l7.24-8.28L1 2h6.4l4.42 5.85L18.9 2zm-1.1 18h1.73L7.3 3.8H5.45L17.8 20z"/></svg>
                                </span>
                                <span class="text-[11px] font-medium text-gray-600 dark:text-gray-400">X</span>
                            </a>

                            <a href="{{ $shareTargets['whatsapp'] }}" target="_blank" rel="noopener noreferrer" class="group flex flex-col items-center gap-2 text-center">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full text-white transition-transform duration-200 group-hover:-translate-y-0.5" style="background-color: #25D366">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2a9.9 9.9 0 00-8.45 15.1L2 22l5.05-1.32A9.9 9.9 0 1012.04 2zm0 1.66a8.24 8.24 0 016.99 12.6l-.23.36.5 1.84-1.9-.5-.36.21a8.24 8.24 0 01-11.4-11.4 8.2 8.2 0 016.4-3.11zm-3.6 4.2c-.17 0-.45.06-.68.31-.23.25-.9.87-.9 2.13s.92 2.47 1.05 2.64c.13.17 1.8 2.9 4.44 3.95 2.2.87 2.65.7 3.13.66.48-.05 1.55-.63 1.77-1.24.22-.61.22-1.13.15-1.24-.06-.11-.24-.17-.5-.3-.27-.13-1.55-.77-1.79-.85-.24-.09-.42-.13-.6.13-.18.25-.7.88-.86 1.06-.16.18-.31.2-.57.07-.27-.13-1.11-.41-2.12-1.31-.78-.7-1.31-1.56-1.47-1.82-.15-.25-.02-.39.11-.52.12-.11.27-.3.4-.44.14-.15.18-.25.27-.42.09-.17.04-.32-.02-.45-.07-.13-.6-1.45-.82-1.98-.22-.52-.44-.45-.6-.45z"/></svg>
                                </span>
                                <span class="text-[11px] font-medium text-gray-600 dark:text-gray-400">WhatsApp</span>
                            </a>

                            <a href="{{ $shareTargets['telegram'] }}" target="_blank" rel="noopener noreferrer" class="group flex flex-col items-center gap-2 text-center">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full text-white transition-transform duration-200 group-hover:-translate-y-0.5" style="background-color: #229ED9">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M21.9 4.3l-3 14.2c-.23 1-.82 1.25-1.66.78l-4.6-3.39-2.22 2.14c-.25.25-.45.45-.92.45l.33-4.68 8.5-7.68c.37-.33-.08-.51-.57-.18L7.27 13.3l-4.52-1.41c-.98-.31-1-.98.2-1.45L20.4 3.1c.82-.3 1.53.2 1.5 1.2z"/></svg>
                                </span>
                                <span class="text-[11px] font-medium text-gray-600 dark:text-gray-400">Telegram</span>
                            </a>

                            <a href="{{ $shareTargets['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="group flex flex-col items-center gap-2 text-center">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full text-white transition-transform duration-200 group-hover:-translate-y-0.5" style="background-color: #0A66C2">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 11-.02 5 2.5 2.5 0 01.02-5zM3 9h4v12H3V9zm6.5 0h3.8v1.7h.06c.53-.95 1.83-1.95 3.76-1.95 4.02 0 4.76 2.5 4.76 5.75V21h-4v-5.3c0-1.27-.02-2.9-1.8-2.9-1.8 0-2.08 1.38-2.08 2.8V21h-4V9z"/></svg>
                                </span>
                                <span class="text-[11px] font-medium text-gray-600 dark:text-gray-400">LinkedIn</span>
                            </a>
                        </div>
                    </div>
                </div>
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
        <section class="py-14 lg:py-20 bg-slate-100 dark:bg-gray-950">
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
