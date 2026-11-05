<div class="min-h-screen bg-gray-50 dark:bg-gray-800">
    {{-- Header dengan Breadcrumb --}}
    <section class="bg-white dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-6">
            {{-- Breadcrumb --}}
            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-6">
                <svg class="w-5 h-5" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <a href="/" class="hover:opacity-80">{{ __('Beranda') }}</a>
                <span>›</span>
                <a href="/karya" class="hover:opacity-80">{{ __('Karya Siswa') }}</a>
                <span>›</span>
                <span style="color: {{ $globalSettings->primary_color_style }}">{{ Str::limit($karya->judul, 30) }}</span>
            </div>
            
            {{-- Back Button --}}
            <div class="flex justify-center">
                <a href="/karya" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:text-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Kembali ke Karya Siswa') }}
                </a>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm overflow-hidden">
                {{-- Featured Image --}}
                @if($karya->gambar_url)
                    <img src="{{ $karya->gambar_url }}" alt="{{ $karya->judul }}" class="w-full aspect-video object-cover">
                @else
                    <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300"></div>
                @endif

                <div class="p-8">
                    {{-- Metadata --}}
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        @if($karya->kategori)
                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full" style="background-color: {{ $globalSettings->primary_color_style }}22; color: {{ $globalSettings->primary_color_style }}">
                                {{ $karya->kategori }}
                            </span>
                        @endif
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $karya->tahun }}</span>
                        @foreach($karya->tags ?? [] as $tag)
                            <span class="inline-block px-3 py-1 text-sm font-medium bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-100 dark:border-sky-500/30 rounded-full">{{ $tag }}</span>
                        @endforeach
                    </div>

                    {{-- Title --}}
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-50 mb-4">{{ $karya->judul }}</h1>

                    {{-- Author Info --}}
                    @if($karya->user && $karya->user->isSiswa())
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-6 border-b border-gray-100 dark:border-gray-800">
                            <a href="{{ $karya->user->profil_url }}" class="inline-flex items-center gap-3 text-gray-700 dark:text-gray-200 hover:text-sky-600 transition group">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-100 dark:bg-gray-800 ring-2 ring-gray-100 dark:ring-gray-700 shrink-0">
                                    @if($karya->user->photo_url)
                                        <img src="{{ $karya->user->photo_url }}" alt="{{ $karya->user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 flex items-center justify-center text-sm font-bold">
                                            {{ $karya->user->initials() }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900 dark:text-gray-50 group-hover:text-sky-600 transition text-sm">{{ $karya->user->name }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-200/70 dark:border-sky-500/30">
                                            {{ $karya->user->kelas ? __('Kelas :kelas', ['kelas' => $karya->user->kelas]) : __('Siswa RPL') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Penulis & Pembuat Karya') }}</p>
                                </div>
                            </a>

                            {{-- Keahlian / Tech Stack Creator Preview --}}
                            @if(count($karya->user->keahlian_list) > 0)
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mr-0.5">{{ __('Keahlian:') }}</span>
                                    @foreach(array_slice($karya->user->keahlian_list, 0, 4) as $skill)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-200/70 dark:border-emerald-500/30 rounded-md text-[11px] font-medium">
                                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ $skill }}</span>
                                        </span>
                                    @endforeach
                                    @if(count($karya->user->keahlian_list) > 4)
                                        <span class="text-[11px] text-gray-400">+{{ count($karya->user->keahlian_list) - 4 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @elseif($karya->nama_siswa)
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300 mb-6 pb-6 border-b border-gray-100 dark:border-gray-800">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium text-gray-900 dark:text-gray-50">{{ $karya->nama_siswa }}</span>
                            @if($karya->kelas)
                                <span class="text-gray-400">•</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $karya->kelas }}</span>
                            @endif
                        </div>
                    @endif

                    {{-- Description --}}
                    @if($karya->deskripsi)
                        <div class="mb-5 pb-3.5 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500 dark:text-gray-400 notranslate">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                                @if(app()->getLocale() === 'en')
                                    <span>{{ __('Deskripsi proyek ini otomatis diterjemahkan ke Bahasa Inggris') }}</span>
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

                        <div class="prose prose-lg max-w-none mb-8">
                            {!! $karya->deskripsi_html !!}
                        </div>
                    @endif

                    {{-- Teknologi --}}
                    @if($karya->teknologi && is_array($karya->teknologi) && count($karya->teknologi) > 0)
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50 mb-3">{{ __('Teknologi yang Digunakan') }}</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($karya->teknologi as $tech)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $tech }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Tangkapan Layar (Screenshots) Aplikasi --}}
                    @if(count($karya->screenshots ?? []) > 0)
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-3">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50">{{ __('Tangkapan Layar Aplikasi') }}</h3>
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-100 dark:border-sky-500/30">
                                    {{ count($karya->screenshots) }} {{ __('Foto') }}
                                </span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($karya->screenshots as $index => $shot)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($shot) }}" target="_blank" class="group relative aspect-video rounded-xl overflow-hidden border border-slate-200 dark:border-gray-800 bg-slate-100 dark:bg-gray-800 hover:border-sky-500/70 transition shadow-2xs block">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($shot) }}" alt="{{ $karya->judul }} Screenshot {{ $index + 1 }}" class="w-full h-full object-cover group-hover:scale-102 transition duration-300">
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-xs font-semibold text-white bg-slate-900/80 px-2.5 py-1 rounded-lg backdrop-blur-xs flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                {{ __('Buka Gambar') }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-100 dark:border-gray-800">
                        @if($karya->url_demo)
                            <a href="{{ $karya->url_demo }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-medium text-white hover:opacity-90 transition" style="background-color: {{ $globalSettings->primary_color_style }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('Lihat Demo') }}
                            </a>
                        @endif

                        @if($karya->url_repo)
                            <a href="{{ $karya->url_repo }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-medium border-2 hover:bg-gray-50 dark:hover:bg-gray-800 transition" style="border-color: {{ $globalSettings->primary_color_style }}; color: {{ $globalSettings->primary_color_style }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('Source Code') }}
                            </a>
                        @endif
                    </div>

                    {{-- Tentang Pembuat Karya / Profil Siswa Card --}}
                    @if($karya->user && $karya->user->isSiswa())
                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
                            <div class="p-5 bg-slate-50 dark:bg-gray-800/80 border border-slate-200/80 dark:border-gray-700 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-start sm:items-center gap-4 min-w-0">
                                    <div class="w-14 h-14 rounded-full overflow-hidden bg-slate-200 dark:bg-gray-700 ring-2 ring-white dark:ring-gray-800 shadow-xs shrink-0">
                                        @if($karya->user->photo_url)
                                            <img src="{{ $karya->user->photo_url }}" alt="{{ $karya->user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 flex items-center justify-center text-base font-bold">
                                                {{ $karya->user->initials() }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h4 class="text-base font-bold text-gray-900 dark:text-gray-50 truncate">{{ $karya->user->name }}</h4>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-200/70 dark:border-sky-500/30">
                                                {{ $karya->user->kelas ? __('Kelas :kelas', ['kelas' => $karya->user->kelas]) : __('Siswa RPL') }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ '@' . ($karya->user->username ?: $karya->user->id) }}
                                            @if($karya->user->bio)
                                                • {{ Str::limit($karya->user->bio, 80) }}
                                            @endif
                                        </p>

                                        {{-- Keahlian / Tech Stack Siswa --}}
                                        @if(count($karya->user->keahlian_list) > 0)
                                            <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                                <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mr-0.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                                    </svg>
                                                    <span>Tech Stack:</span>
                                                </span>
                                                @foreach($karya->user->keahlian_list as $skill)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-200/70 dark:border-emerald-500/30 rounded-md text-[11px] font-medium">
                                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span>{{ $skill }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <a
                                    href="{{ $karya->user->profil_url }}"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-600 hover:border-sky-500 text-slate-700 dark:text-gray-200 hover:text-sky-600 rounded-lg text-xs font-semibold shadow-2xs transition shrink-0"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span>{{ __('Lihat Profil Siswa') }}</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Related Karya --}}
            @if($relatedKaryas->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-50 mb-6">{{ __('Karya Lainnya') }}</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($relatedKaryas as $related)
                            <a href="/karya/{{ $related->id }}" class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition group">
                                @if($related->gambar_url)
                                    <img src="{{ $related->gambar_url }}" alt="{{ $related->judul }}" class="w-full aspect-video object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300"></div>
                                @endif
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-900 dark:text-gray-50 mb-1 line-clamp-2">{{ $related->judul }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $related->nama_siswa }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
