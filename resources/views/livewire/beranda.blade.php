<div class="overflow-x-hidden">
    {{-- ============================================ --}}
    {{-- Hero: Slider foto tunggal ala skansaba.dev --}}
    {{-- 1 foto di tengah, tanpa tetangga, crossfade --}}
    {{-- ============================================ --}}
    <section
        class="pt-4 lg:pt-6 pb-10 lg:pb-14"
        x-data="{
            N: {{ $sliders->count() }},
            index: 0,
            timer: null,

            init() {
                this.start();
            },

            start() {
                this.stop();
                if (this.N < 2) return;
                this.timer = setInterval(() => this.advance(), 5000);
            },

            stop() {
                clearInterval(this.timer);
            },

            advance() {
                this.index = (this.index + 1) % this.N;
            },

            back() {
                this.index = (this.index - 1 + this.N) % this.N;
            },

            go(i) {
                this.index = i;
                this.start();
            },

            isActive(pos) {
                return pos === this.index;
            }
        }"
    >
        <div class="site-container">
            {{-- Judul utama + tagline: rapat, jarak seimbang antara navbar dan slider --}}
            <div class="text-center pt-4 lg:pt-6 mb-4 lg:mb-5" data-reveal>
                <h1 class="text-4xl sm:text-5xl lg:text-[52px] font-extrabold tracking-tight leading-none" style="color: {{ $globalSettings->primary_color_style }}">
                    {{ $globalSettings->site_name ?? 'Skansaba.Dev' }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-base sm:text-lg mt-1 max-w-xl mx-auto leading-snug">
                    {{ $globalSettings->site_tagline ?? 'Rekayasa Perangkat Lunak - Membangun Masa Depan Digital Indonesia' }}
                </p>
            </div>

            {{-- Arrows slider --}}
            @if($sliders->count() > 1)
                <div class="flex justify-end mb-3 lg:mb-4" data-reveal>
                    <div class="hidden sm:flex items-center gap-3">
                        <button
                            @click="back(); start()"
                            class="w-11 h-11 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex items-center justify-center text-gray-700 dark:text-gray-200 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-300"
                            aria-label="Slide sebelumnya"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12l7.5-7.5m0 15L3 12h18"/>
                            </svg>
                        </button>
                        <button
                            @click="advance(); start()"
                            class="w-11 h-11 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex items-center justify-center text-gray-700 dark:text-gray-200 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-300"
                            aria-label="Slide berikutnya"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>

        @if($sliders->isNotEmpty())
            {{-- Frame foto tunggal: hanya 1 foto terlihat, ganti dengan crossfade --}}
            <div class="site-container" data-reveal>
                <div class="relative rounded-3xl overflow-hidden bg-slate-800 h-[300px] sm:h-[360px] lg:h-[480px] shadow-[0_12px_36px_rgba(15,23,42,0.16)]">
                    @foreach($sliders as $index => $slider)
                        <div
                            class="absolute inset-0 transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                            :class="isActive({{ $index }}) ? 'opacity-100 z-10' : 'opacity-0 z-0'"
                        >
                            <img
                                src="{{ asset('storage/' . $slider->image) }}"
                                alt="{{ $slider->title }}"
                                class="w-full h-full object-cover"
                                @if($index !== 0) loading="lazy" @endif
                            >

                            {{-- Keterangan di atas gambar (seperti skansaba.dev) --}}
                            <div class="absolute inset-x-0 bottom-0 p-5 lg:p-7 pointer-events-none">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent pointer-events-none"></div>

                                <div class="relative">
                                    <h3 class="text-white text-lg lg:text-2xl font-bold leading-snug drop-shadow-md">{{ $slider->title }}</h3>
                                    @if($slider->subtitle)
                                        <p class="text-white/75 text-[11px] lg:text-sm leading-relaxed mt-1.5 lg:mt-2.5 line-clamp-2 drop-shadow">
                                            {{ $slider->subtitle }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Progress bar: hanya slide aktif yang beranimasi --}}
                                <div
                                    class="relative h-[3px] bg-white/25 rounded-full overflow-hidden mt-4"
                                    x-show="isActive({{ $index }})"
                                >
                                    <template x-for="tick in [index]" :key="'bar-{{ $index }}-' + tick">
                                        <div
                                            class="h-full bg-white rounded-full"
                                            style="animation: slider-progress 5s linear forwards"
                                            x-data
                                        ></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="site-container">
                <div class="rounded-2xl bg-white border border-gray-100 h-72 flex items-center justify-center text-gray-400 text-sm" data-reveal>
                    Belum ada slide yang ditambahkan
                </div>
            </div>
        @endif
    </section>
    </section>

    {{-- ============================================ --}}
    {{-- Prospek Karir & Mata Pelajaran Unggulan --}}
    {{-- ============================================ --}}
    <section class="py-12 lg:py-16">
        <div class="site-container">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">

                {{-- Kolom kiri: Prospek Karir (list polos seperti desain) --}}
                <div class="lg:col-span-5" data-reveal>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50 mb-6 lg:mb-8">
                        Prospek Karir
                    </h2>

                    @if($prospekKarir->isNotEmpty())
                        <ul class="space-y-3.5">
                            @foreach($prospekKarir as $karir)
                                <li class="group/karir flex items-center gap-3" title="{{ $karir->description_excerpt }}">
                                    <span class="w-1.5 h-1.5 rounded-full flex-none" style="background-color: {{ $globalSettings->primary_color_style }}"></span>
                                    <span class="text-sm sm:text-[15px] text-gray-600 dark:text-gray-300 group-hover/karir:text-gray-900 dark:group-hover/karir:text-white font-medium transition-colors duration-200">
                                        {{ $karir->title }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-400">Belum ada prospek karir</p>
                    @endif
                </div>

                {{-- Kolom kanan: Mata Pelajaran Unggulan (list polos seperti desain) --}}
                <div class="lg:col-span-7" data-reveal>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50 mb-6 lg:mb-8">
                        Mata Pelajaran Unggulan
                    </h2>

                    <div class="space-y-6 lg:space-y-7">
                        @foreach($mataPelajaran as $mapel)
                            <div class="group/mapel flex items-start gap-4">
                                <span
                                    class="w-10 h-10 rounded-lg flex items-center justify-center flex-none transition-transform duration-300 group-hover/mapel:scale-110"
                                    style="background-color: {{ $globalSettings->primary_color }}1a; color: {{ $globalSettings->primary_color_style }}"
                                >
                                    @if($mapel['ikon'] === 'code')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l-3 3 3 3m8-6l3 3-3 3M13 5l-2 14"/></svg>
                                    @elseif($mapel['ikon'] === 'database')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7c0-1.66 3.58-3 8-3s8 1.34 8 3-3.58 3-8 3-8-1.34-8-3zm0 0v10c0 1.66 3.58 3 8 3s8-1.34 8-3V7m-16 5c0 1.66 3.58 3 8 3s8-1.34 8-3"/></svg>
                                    @elseif($mapel['ikon'] === 'palette')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 110-18c4.97 0 9 3.58 9 8 0 2.21-1.79 4-4 4h-2a2 2 0 00-1.6 3.2c.3.4.47.87.47 1.3a1.5 1.5 0 01-1.5 1.5H12zm-4.5-11a1 1 0 100-2 1 1 0 000 2zm4-2a1 1 0 100-2 1 1 0 000 2zm5 0a1 1 0 100-2 1 1 0 000 2zm4 3a1 1 0 100-2 1 1 0 000 2z"/></svg>
                                    @elseif($mapel['ikon'] === 'shield')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.04c-2.1 0-4.15.4-6 1.16v5.63c0 4.62 2.44 8.4 6 10.25 3.56-1.85 6-5.63 6-10.25V5.92a15.4 15.4 0 00-6-1.21z"/></svg>
                                    @elseif($mapel['ikon'] === 'cloud')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 19.5a4.5 4.5 0 01-.42-8.98 6 6 0 0111.7-1.6 4.25 4.25 0 01-.53 8.43v.05H6.75z"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v6.5a3.5 3.5 0 01-3.5 3.5H3m6-10h9a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3v-5"/></svg>
                                    @endif
                                </span>

                                <div class="min-w-0">
                                    <h3 class="font-bold text-gray-900 dark:text-gray-50 text-base leading-snug">{{ $mapel['judul'] }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mt-1.5">{{ $mapel['deskripsi'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- Marquee Logo Mitra (2 baris berlawanan arah) --}}
    {{-- ============================================ --}}
    <section class="py-10 lg:py-14">
        <div class="w-full">
            @if($mitras->isNotEmpty())
                @php
                    $repeat = max(2, (int) ceil(8 / $mitras->count()));
                    $groupOne = collect();
                    for ($i = 0; $i < $repeat; $i++) { $groupOne = $groupOne->merge($mitras); }
                    $groupTwo = $groupOne->reverse()->values();
                @endphp

                <div class="space-y-4 md:space-y-5 marquee-hover-pause"
                     data-reveal>
                    {{-- Baris 1: geser kiri --}}
                    <div class="overflow-hidden marquee-row">
                        <div class="flex w-max animate-marquee-left" style="--marquee-duration: 80s">
                            @foreach([0, 1] as $copy)
                                <div class="flex items-center gap-4 md:gap-5 pr-4 md:pr-5" @if($copy === 1) aria-hidden="true" @endif>
                                    @foreach($groupOne as $mitra)
                                        <div class="mitra-chip group/chip flex h-16 md:h-20 w-40 md:w-48 flex-none items-center justify-center rounded-xl bg-white dark:bg-gray-900 px-1.5 shadow-[0_1px_4px_rgba(15,23,42,0.05)] overflow-hidden">
                                            @if($mitra->logo)
                                                <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->nama_mitra }}" class="max-h-[80%] max-w-[92%] w-auto object-contain mitra-logo" loading="lazy" title="{{ $mitra->nama_mitra }}">
                                            @else
                                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-400 text-center">{{ $mitra->nama_mitra }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Baris 2: geser kanan --}}
                    <div class="overflow-hidden marquee-row">
                        <div class="flex w-max animate-marquee-right" style="--marquee-duration: 80s">
                            @foreach([0, 1] as $copy)
                                <div class="flex items-center gap-4 md:gap-5 pr-4 md:pr-5" @if($copy === 1) aria-hidden="true" @endif>
                                    @foreach($groupTwo as $mitra)
                                        <div class="mitra-chip group/chip flex h-16 md:h-20 w-40 md:w-48 flex-none items-center justify-center rounded-xl bg-white dark:bg-gray-900 px-1.5 shadow-[0_1px_4px_rgba(15,23,42,0.05)] overflow-hidden">
                                            @if($mitra->logo)
                                                <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->nama_mitra }}" class="max-h-[80%] max-w-[92%] w-auto object-contain mitra-logo" loading="lazy" title="{{ $mitra->nama_mitra }}">
                                            @else
                                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-400 text-center">{{ $mitra->nama_mitra }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <p class="text-center text-gray-400 dark:text-gray-400 text-sm" data-reveal>Belum ada mitra yang ditampilkan</p>
            @endif
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- Karya Siswa Carousel --}}
    {{-- ============================================ --}}
    <section class="py-12 lg:py-16">
        <div class="site-container">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50 mb-8 lg:mb-10" data-reveal>
                Karya Siswa
            </h2>

            @if($karyaSiswa->isNotEmpty())
                <div
                    x-data="{
                        autoplay: null,
                        hovering: false,
                        barWidth: 12,
                        barLeft: 0,

                        init() {
                            this.update();
                            this.start();
                            this.$nextTick(() => this.update());
                        },

                        start() {
                            this.stop();
                            this.autoplay = setInterval(() => {
                                if (this.hovering) return;
                                this.step();
                            }, 3200);
                        },

                        stop() {
                            clearInterval(this.autoplay);
                        },

                        step() {
                            const el = this.$refs.track;
                            if (!el) return;
                            const max = el.scrollWidth - el.clientWidth;
                            if (max <= 4) return;

                            // sudah di ujung kanan -> kembali halus ke awal
                            if (el.scrollLeft >= max - 4) {
                                el.scrollTo({ left: 0, behavior: 'smooth' });
                                return;
                            }
                            el.scrollBy({ left: this.cardStep(), behavior: 'smooth' });
                        },

                        cardStep() {
                            const el = this.$refs.track;
                            if (!el || !el.children.length) return 280;
                            const card = el.children[0];
                            const gap = parseFloat(getComputedStyle(el).columnGap || '20') || 20;
                            return card.getBoundingClientRect().width + gap;
                        },

                        scrollCards(dir) {
                            const el = this.$refs.track;
                            if (!el) return;
                            el.scrollBy({ left: dir * this.cardStep(), behavior: 'smooth' });
                            this.start();
                        },

                        update() {
                            const el = this.$refs.track;
                            if (!el) return;
                            const scrollable = el.scrollWidth - el.clientWidth;
                            if (scrollable <= 0) { this.barWidth = 100; this.barLeft = 0; return; }
                            const visible = (el.clientWidth / el.scrollWidth) * 100;
                            this.barWidth = Math.max(12, visible * 0.8);
                            this.barLeft = (el.scrollLeft / scrollable) * (100 - this.barWidth);
                        }
                    }"
                    @mouseenter="hovering = true"
                    @mouseleave="hovering = false"
                    class="relative"
                    data-reveal
                >
                    {{-- Track --}}
                    <div
                        x-ref="track"
                        @scroll.passive="update()"
                        @resize.window.debounce.150="update()"
                        class="flex gap-5 overflow-x-auto no-scrollbar snap-x snap-mandatory scroll-pl-1 py-10 -my-10"
                    >
                        @foreach($karyaSiswa as $item)
                            <a
                                href="/karya/{{ $item->id }}"
                                class="group relative flex-none w-[200px] sm:w-[240px] lg:w-[260px] aspect-[2/3] rounded-2xl overflow-hidden snap-start bg-slate-800
                                       transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]
                                       hover:-translate-y-3 hover:scale-[1.06] hover:shadow-[0_24px_48px_-12px_rgba(15,23,42,0.45)] hover:z-20
                                       hover:ring-1 hover:ring-white/20"
                            >
                                @if($item->gambar)
                                    <img
                                        src="{{ asset('storage/' . $item->gambar) }}"
                                        alt="{{ $item->judul }}"
                                        class="w-full h-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-[1.08]"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-700 to-slate-900 p-6">
                                        <span class="text-white/80 font-bold text-lg text-center">{{ $item->judul }}</span>
                                    </div>
                                @endif

                                {{-- Overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent pointer-events-none transition-opacity duration-500 group-hover:from-black/70"></div>

                                {{-- Info --}}
                                <div class="absolute bottom-0 left-0 right-0 p-4 pointer-events-none">
                                    <h3 class="text-white font-bold text-base lg:text-lg leading-snug drop-shadow">{{ $item->judul }}</h3>
                                    @if($item->nama_siswa)
                                        <p class="text-white/70 text-xs mt-1 translate-y-1 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 delay-75">
                                            {{ $item->nama_siswa }}{{ $item->kategori ? ' ┬╖ ' . $item->kategori : '' }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Arrows --}}
                    <button
                        @click="scrollCards(-1)"
                        class="hidden md:flex absolute -left-5 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-md items-center justify-center text-gray-700 dark:text-gray-200 hover:bg-gray-900 dark:hover:bg-gray-800 hover:text-white dark:hover:text-white hover:border-gray-900 dark:hover:border-gray-600 transition-all duration-300 z-30"
                        aria-label="Geser kiri"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12l7.5-7.5m0 15L3 12h18"/>
                        </svg>
                    </button>
                    <button
                        @click="scrollCards(1)"
                        class="hidden md:flex absolute -right-5 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-md items-center justify-center text-gray-700 dark:text-gray-200 hover:bg-gray-900 dark:hover:bg-gray-800 hover:text-white dark:hover:text-white hover:border-gray-900 dark:hover:border-gray-600 transition-all duration-300 z-30"
                        aria-label="Geser kanan"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>

                    {{-- Progress Bar --}}
                    <div class="mt-8 lg:mt-10 h-[3px] w-full sm:w-2/3 lg:w-1/2 mx-auto bg-gray-300/60 dark:bg-gray-800 rounded-full relative">
                        <div
                            class="absolute top-0 h-full bg-slate-800 dark:bg-sky-400 rounded-full transition-all duration-300 ease-out"
                            :style="`width: ${barWidth}%; left: ${barLeft}%`"
                        ></div>
                    </div>
                </div>
            @else
                <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 h-56 flex items-center justify-center text-gray-400 dark:text-gray-400 text-sm" data-reveal>
                    Belum ada karya siswa yang dipublikasikan
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- Berita Terbaru --}}
    {{-- ============================================ --}}
    <section class="py-12 lg:py-16">
        <div class="site-container">
            <div class="flex items-end justify-between gap-6 mb-8 lg:mb-10" data-reveal>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50">
                    Berita Terbaru
                </h2>
                <a
                    href="/berita"
                    class="hidden sm:flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:gap-3 transition-all"
                >
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            @if($berita->isNotEmpty())
                <div
                    class="berita-cards-section relative"
                    x-data="{
                        raf: null,
                        cards: [],

                        init() {
                            this.$nextTick(() => {
                                this.cards = Array.from(this.$el.querySelectorAll('.berita-card'));
                                const grid = this.$el.querySelector('.berita-grid');
                                const gridRect = grid.getBoundingClientRect();
                                const gridCx = gridRect.left + gridRect.width / 2;
                                const gridCy = gridRect.top + gridRect.height / 2;

                                // Simpan offset asli tiap kartu dari pusat grid SEKALI,
                                // sebelum kartu diberi transform apa pun.
                                this.cards.forEach((c, i) => {
                                    const r = c.getBoundingClientRect();
                                    c.dataset.pullX = String(gridCx - (r.left + r.width / 2));
                                    c.dataset.pullY = String(gridCy - (r.top + r.height / 2));
                                    c.style.transformOrigin = 'center center';
                                    c.style.willChange = 'transform, opacity';
                                    // z-index dijaga di bawah navbar (z-50) agar
                                    // kartu tidak pernah menimpa menu.
                                    c.style.zIndex = String(30 - i);
                                });
                                this.update();
                            });
                            window.addEventListener('scroll', () => this.schedule(), { passive: true });
                            window.addEventListener('resize', () => {
                                // reset transform lalu ukur ulang posisi asli
                                this.cards.forEach(c => { c.style.transform = 'none'; c.style.opacity = '1'; });
                                this.$nextTick(() => {
                                    const grid = this.$el.querySelector('.berita-grid');
                                    const gr = grid.getBoundingClientRect();
                                    const gcx = gr.left + gr.width / 2;
                                    const gcy = gr.top + gr.height / 2;
                                    this.cards.forEach(c => {
                                        const r = c.getBoundingClientRect();
                                        c.dataset.pullX = String(gcx - (r.left + r.width / 2));
                                        c.dataset.pullY = String(gcy - (r.top + r.height / 2));
                                    });
                                    this.schedule();
                                });
                            }, { passive: true });
                        },

                        schedule() {
                            if (this.raf) return;
                            this.raf = requestAnimationFrame(() => {
                                this.raf = null;
                                this.update();
                            });
                        },

                        update() {
                            if (!this.cards.length) return;

                            const section = this.$el;
                            const rect = section.getBoundingClientRect();
                            const vh = window.innerHeight;

                            // 0 = section masih jauh di bawah (kartu menumpuk)
                            // 1 = section sudah masuk nyaman di layar (kartu menyebar)
                            // Rentang dibuat LEBIH PANJANG agar animasi terlihat
                            // gradual saat user scroll.
                            const enter = vh * 1.02;
                            const done  = vh * 0.18;

                            let p = (enter - rect.top) / (enter - done);
                            p = Math.max(0, Math.min(1, p));

                            // easing lebih lembut + bertahap (easeInOutQuad)
                            const e = p < 0.5
                                ? 2 * p * p
                                : 1 - Math.pow(-2 * p + 2, 2) / 2;

                            const mobile = window.innerWidth < 640;
                            const tablet = window.innerWidth < 1024;

                            // Konfigurasi tumpukan: kartu disusun bertingkat
                            // seperti setumpuk kartu, lalu menyebar saat di-scroll.
                            const stack = mobile
                                ? { y: 26, x: 10, r: 3.5, s: 0.055 }
                                : tablet
                                    ? { y: 34, x: 26, r: 4.5, s: 0.05 }
                                    : { y: 40, x: 44, r: 5.5, s: 0.045 };

                            this.cards.forEach((card, i) => {
                                const pullX = parseFloat(card.dataset.pullX || '0');
                                const pullY = parseFloat(card.dataset.pullY || '0');

                                const inv = 1 - e;
                                const depth = i;

                                const tx = pullX * inv + depth * (stack.x * 0.55) * inv;
                                const ty = pullY * inv + depth * (stack.y * 0.5) * inv;
                                const rot = depth * stack.r * inv;
                                const scale = 1 - depth * stack.s * inv;

                                card.style.transform =
                                    'translate3d(' + tx + 'px, ' + ty + 'px, 0) rotate(' + rot + 'deg) scale(' + scale + ')';
                                card.style.opacity = String(
                                    e > 0.98 ? 1 : 0.5 + depth * 0.16 + e * 0.5
                                );
                            });
                        }
                    }"
                >
                    <div class="berita-grid grid sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
                        @foreach($berita as $index => $item)
                            <a
                                href="/berita/{{ $item->slug }}"
                                class="berita-card group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 shadow-[0_8px_24px_rgba(15,23,42,0.10)] hover:shadow-[0_20px_44px_rgba(15,23,42,0.18)] hover:!-translate-y-2 hover:!rotate-0 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] block"
                                style="--card-index: {{ $index }}"
                            >
                            {{-- Thumbnail --}}
                            <div class="relative aspect-video overflow-hidden bg-slate-100">
                                @if($item->thumbnail)
                                    <img
                                        src="{{ asset('storage/' . $item->thumbnail) }}"
                                        alt="{{ $item->title }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    >
                                @endif

                                @if($item->is_highlight)
                                    <span class="absolute top-3 left-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/95 dark:bg-gray-900/90 text-gray-900 dark:text-gray-50 shadow-sm backdrop-blur">
                                        Γ¡É Unggulan
                                    </span>
                                @endif
                            </div>

                            {{-- Body: hanya judul saat normal, sisanya muncul saat hover --}}
                            <div class="p-5 lg:p-6">
                                <h3 class="font-bold text-gray-900 dark:text-gray-50 leading-snug line-clamp-2 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
                                    {{ $item->title }}
                                </h3>

                                <div class="grid grid-rows-[0fr] group-hover:grid-rows-[1fr] transition-[grid-template-rows] duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                                    <div class="overflow-hidden">
                                        <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500 pt-3">
                                            <span>{{ $item->published_at?->format('d M Y') }}</span>
                                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                            <span>{{ $item->views }} views</span>
                                        </div>

                                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mt-2">
                                            {{ $item->excerpt ?? Str::limit(strip_tags($item->content_md), 100) }}
                                        </p>

                                        <span class="inline-flex items-center gap-1.5 mt-3 text-sm font-semibold" style="color: {{ $globalSettings->primary_color_style }}">
                                            Baca Selengkapnya
                                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                </div>

                {{-- Mobile: lihat semua --}}
                <div class="flex sm:hidden justify-center mt-8" data-reveal>
                    <a
                        href="/berita"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gray-900 text-white text-sm font-semibold hover:bg-gray-700 transition"
                    >
                        Lihat Semua Berita
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
            @else
                <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 h-56 flex items-center justify-center text-gray-400 dark:text-gray-400 text-sm" data-reveal>
                    Belum ada berita yang dipublikasikan
                </div>
            @endif
        </div>
    </section>
</div>
