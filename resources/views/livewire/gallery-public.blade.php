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
                                                class="icon-pop morph-fill morph-copy p-1.5 text-gray-400 dark:text-gray-400 hover:text-cyan-600 rounded-lg"
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

    {{-- ============================================ --}}
    {{-- Prestasi Siswa — panggung 3D time-machine --}}
    {{-- ============================================ --}}
    @if($prestasis->isNotEmpty())
        <section class="bg-slate-100 py-12 lg:py-16 dark:bg-gray-950" aria-labelledby="prestasi-galeri-heading">
            <div class="site-container">
                <div class="mb-8 flex items-end justify-between gap-6 lg:mb-10" data-reveal>
                    <h2 id="prestasi-galeri-heading" class="type-section-title text-gray-900 dark:text-gray-50">
                        {{ __('Prestasi Siswa') }}
                    </h2>
                    <a href="/prestasi" class="btn-morph type-button hidden sm:inline-flex">
                        {{ __('Lihat Semua') }}
                        <svg class="btn-morph__icon h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>

                <div
                    x-data="{
                        active: 0,
                        total: {{ $prestasis->count() }},
                        setActive(i) { if (i !== this.active) this.active = i; },
                        next() { this.active = (this.active + 1) % this.total; },
                        prev() { this.active = (this.active - 1 + this.total) % this.total; },
                        cardStyle(i) {
                            const d = i - this.active;
                            if (d === 0) {
                                return 'transform: translateY(0) translateZ(0) scale(1) rotateX(0deg); opacity: 1; z-index: 30;';
                            }
                            if (d > 0) {
                                const k = Math.min(d, 4);
                                return 'transform: translateY(' + (-14 * k) + 'px) translateZ(' + (-70 * k) + 'px) scale(' + (1 - 0.025 * k) + ') rotateX(' + (2.5 * k) + 'deg); opacity: ' + Math.max(0, 1 - 0.24 * k) + '; z-index: ' + (30 - k) + ';';
                            }
                            const k = Math.min(-d, 3);
                            return 'transform: translateY(' + (90 * k) + 'px) translateZ(' + (110 * k) + 'px) scale(' + (1 + 0.07 * k) + ') rotateX(-6deg); opacity: 0; z-index: 40;';
                        }
                    }"
                    data-reveal
                >
                    <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 lg:p-8 dark:border-white/10 dark:bg-[#09090b]">
                        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(2,132,199,0.07),transparent_70%)] dark:bg-[radial-gradient(ellipse_at_center,rgba(255,255,255,0.06),transparent_70%)]"></div>

                        <div class="relative grid grid-cols-1 items-center gap-6 lg:grid-cols-2 lg:gap-10">
                            {{-- Kiri: kartu deskripsi prestasi --}}
                            <div class="grid rounded-xl border border-gray-200 bg-slate-50 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                                @foreach($prestasis as $i => $prestasi)
                                    <div
                                        :class="{ 'is-active': active === {{ $i }} }"
                                        :aria-hidden="active !== {{ $i }}"
                                        class="prestasi-desc-panel col-start-1 row-start-1 flex flex-col p-6 sm:p-7"
                                    >
                                        <p class="type-eyebrow text-gray-500 dark:text-gray-400">
                                            {{ __(ucfirst($prestasi->jenis)) }}@if($prestasi->tingkat) · {{ __(ucfirst($prestasi->tingkat)) }}@endif @if($prestasi->tahun) · {{ $prestasi->tahun }}@endif
                                        </p>

                                        <h3 class="type-subsection-title mt-2 text-gray-900 dark:text-gray-50">{{ $prestasi->judul }}</h3>

                                        <dl class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2">
                                            @if($prestasi->nama_siswa)
                                                <div>
                                                    <dt class="type-meta text-gray-400 dark:text-gray-500">{{ __('Siswa') }}</dt>
                                                    <dd class="type-body-sm text-gray-700 dark:text-gray-200">{{ $prestasi->nama_siswa }}</dd>
                                                </div>
                                            @endif
                                            @if($prestasi->kelas)
                                                <div>
                                                    <dt class="type-meta text-gray-400 dark:text-gray-500">{{ __('Kelas') }}</dt>
                                                    <dd class="type-body-sm text-gray-700 dark:text-gray-200">{{ $prestasi->kelas }}</dd>
                                                </div>
                                            @endif
                                            @if($prestasi->penyelenggara)
                                                <div class="col-span-2">
                                                    <dt class="type-meta text-gray-400 dark:text-gray-500">{{ __('Penyelenggara') }}</dt>
                                                    <dd class="type-body-sm text-gray-700 dark:text-gray-200">{{ $prestasi->penyelenggara }}</dd>
                                                </div>
                                            @endif
                                        </dl>

                                        @if($prestasi->deskripsi)
                                            <p class="type-body-sm mt-4 line-clamp-3 text-gray-500 dark:text-gray-400">{{ html_entity_decode($prestasi->deskripsi_excerpt, ENT_QUOTES, 'UTF-8') }}</p>
                                        @endif

                                        <div class="mt-auto pt-5">
                                            <a href="/prestasi/{{ $prestasi->id }}" class="btn-morph btn-morph--primary type-button inline-flex">
                                                {{ __('Lihat Detail') }}
                                                <svg class="btn-morph__icon h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Kanan: panggung foto 3D + tick + kontrol --}}
                            <div class="relative">
                                <div class="relative flex items-center justify-center" style="perspective: 900px;">
                                    <div class="relative h-[180px] w-[250px] sm:h-[210px] sm:w-[300px]">
                                        @foreach($prestasis as $i => $prestasi)
                                            <div
                                                class="prestasi-3d-card pointer-events-none absolute inset-0 overflow-hidden rounded-2xl border border-white/10 shadow-[0_20px_45px_-20px_rgba(0,0,0,0.9)] transition duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] will-change-transform"
                                                x-cloak
                                                :style="cardStyle({{ $i }})"
                                            >
                                                @if($prestasi->gambar)
                                                    <img src="{{ asset('storage/' . $prestasi->gambar) }}" alt="{{ $prestasi->judul }}" class="h-full w-full object-cover" loading="lazy">
                                                @else
                                                    <div class="grid h-full w-full place-items-center bg-slate-900 text-slate-600">
                                                        <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                                    </div>
                                                @endif
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/5 to-transparent"></div>
                                                <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-3 p-4">
                                                    <p class="line-clamp-2 text-sm font-semibold leading-snug text-white">{{ $prestasi->judul }}</p>
                                                    <span class="shrink-0 text-[11px] font-medium tabular-nums text-white/70">{{ $prestasi->tahun }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Tick selector ala time-machine — hover untuk ganti foto --}}
                                    <div class="absolute right-0 top-1/2 z-50 flex -translate-y-1/2 flex-col items-end gap-1.5" role="tablist" aria-orientation="vertical" aria-label="{{ __('Pilih prestasi') }}">
                                        @foreach($prestasis as $i => $prestasi)
                                            <button
                                                type="button"
                                                @mouseenter="setActive({{ $i }})"
                                                @focus="setActive({{ $i }})"
                                                @click="setActive({{ $i }})"
                                                role="tab"
                                                :aria-selected="active === {{ $i }}"
                                                :tabindex="active === {{ $i }} ? 0 : -1"
                                                aria-label="{{ $prestasi->judul }}"
                                                class="group flex h-4 w-16 cursor-pointer items-center justify-end"
                                            >
                                                <span
                                                    class="h-[3px] origin-right rounded-full transition-all duration-300"
                                                    :class="active === {{ $i }} ? 'w-10 bg-sky-500 dark:bg-sky-400' : 'w-5 bg-gray-300 group-hover:w-7 group-hover:bg-gray-400 dark:bg-white/25 dark:group-hover:bg-white/50'"
                                                ></span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Kontrol bawah --}}
                                <div class="relative z-10 mt-5 flex items-center justify-between gap-4">
                                    <div class="type-meta font-medium tabular-nums text-gray-400 dark:text-white/50">
                                        <span x-text="String(active + 1).padStart(2, '0')"></span>
                                        <span class="text-gray-300 dark:text-white/25">/</span>
                                        <span>{{ str_pad((string) $prestasis->count(), 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="prev()" aria-label="{{ __('Prestasi sebelumnya') }}"
                                            class="icon-pop flex h-9 w-9 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-white/70 dark:hover:bg-white/10 dark:hover:text-white">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                                        </button>
                                        <button type="button" @click="next()" aria-label="{{ __('Prestasi berikutnya') }}"
                                            class="icon-pop flex h-9 w-9 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-white/70 dark:hover:bg-white/10 dark:hover:text-white">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
