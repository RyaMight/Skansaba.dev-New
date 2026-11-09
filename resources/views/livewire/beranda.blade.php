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
            heroMax: null,

            init() {
                this.start();
                this.fitHero();
                window.addEventListener('load', () => this.fitHero());
                window.addEventListener('resize', () => this.fitHero());
            },

            fitHero() {
                const el = this.$refs.heroFrame;
                if (!el) return;
                const top = el.getBoundingClientRect().top + window.scrollY;
                this.heroMax = Math.max(220, window.innerHeight - top - 50) + 'px';
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
            {{-- Judul utama: eyebrow + hero title (sesuai design banner) --}}
            <div class="text-center pt-4 lg:pt-6 mb-4 lg:mb-5" data-reveal>
                {{-- Eyebrow (2 warna) --}}
                <p class="type-lead font-semibold text-[#485B60] dark:text-gray-300">
                    {{ __('Selamat Datang di Jurusan') }}
                    <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Rekayasa Perangkat Lunak!') }}</span>
                </p>

                {{-- Hero title (2 baris) --}}
                <h1 class="type-hero-title mt-3 lg:mt-6 max-w-2xl lg:max-w-3xl mx-auto text-[#28374D] dark:text-gray-50">
                    <span class="block">{{ __('Membangun Masa Depan Digital') }}</span>
                    <span class="block">{{ __('bersama') }} <span style="color: {{ $globalSettings->primary_color_style }}">{{ $globalSettings->site_name ?? 'Skansaba.Dev' }}</span></span>
                </h1>
            </div>
        </div>

        @if($sliders->isNotEmpty())
            {{-- Frame foto tunggal: rasio 16/9 (object-cover), tinggi dibatasi dinamis.
                 Lebar sejajar dengan navbar: px-4 sm:px-6 + max-w-6xl (sama seperti header nav). --}}
            <div class="px-4 sm:px-6" data-reveal>
                {{-- Wrapper gambar: lebar penuh mengikuti navbar, proporsional --}}
                <div class="relative w-full max-w-6xl mx-auto">
                    {{-- Container gambar: 16/9, max-height dinamis (viewport - offset atas - 50px) --}}
                    <div class="relative grid w-full aspect-video rounded-3xl overflow-hidden shadow-[0_12px_36px_rgba(15,23,42,0.16)]" x-ref="heroFrame" :style="heroMax ? 'max-height: ' + heroMax : ''">
                        @foreach($sliders as $index => $slider)
                            <div
                                class="col-start-1 row-start-1 h-full transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                :class="isActive({{ $index }}) ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
                            >
                                {{-- Gambar mengisi frame 16/9 (object-cover, tidak gepeng) --}}
                                <img
                                    src="{{ asset('storage/' . $slider->image) }}"
                                    alt="{{ $slider->title }}"
                                    class="w-full h-full block object-cover"
                                    @if($index !== 0) loading="lazy" @endif
                                >

                                {{-- Keterangan di atas gambar (seperti skansaba.dev) --}}
                                <div class="absolute inset-x-0 bottom-0 p-5 lg:p-7 pointer-events-none">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent pointer-events-none"></div>

                                    <div class="relative">
                                        <h3 class="type-card-title text-white drop-shadow-md">{{ $slider->title }}</h3>
                                        @if($slider->subtitle)
                                            <p class="type-body-sm text-white/75 mt-1.5 lg:mt-2.5 line-clamp-2 drop-shadow">
                                                {{ $slider->subtitle }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Progress bar overlay di bawah frame (diluar stacking biar selalu terlihat) --}}
                        <div class="col-start-1 row-start-1 self-end relative z-20 pointer-events-none">
                            <div class="h-[3px] bg-white/25 rounded-full overflow-hidden mx-5 lg:mx-7 mb-4 lg:mb-5" x-show="N > 1">
                                <template x-for="tick in [index]" :key="'bar-' + tick">
                                    <div
                                        class="h-full bg-white rounded-full"
                                        style="animation: slider-progress 5s linear forwards"
                                    ></div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Desktop Navigation Arrows - Di samping kiri dan kanan gambar --}}
                    @if($sliders->count() > 1)
                        {{-- Tombol Kiri --}}
                        <button
                            @click="back(); start()"
                            class="hidden lg:flex absolute left-4 top-1/2 -translate-y-1/2 z-30 w-11 h-11 rounded-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 items-center justify-center text-gray-700 dark:text-gray-200 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-300 shadow-xl"
                            aria-label="Slide sebelumnya"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                            </svg>
                        </button>

                        {{-- Tombol Kanan --}}
                        <button
                            @click="advance(); start()"
                            class="hidden lg:flex absolute right-4 top-1/2 -translate-y-1/2 z-30 w-11 h-11 rounded-full border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 items-center justify-center text-gray-700 dark:text-gray-200 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all duration-300 shadow-xl"
                            aria-label="Slide berikutnya"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </button>
                    @endif
                </div>

                {{-- Mobile arrows: tampil di bawah gambar (tengah, berdekatan) --}}
                @if($sliders->count() > 1)
                    <div class="lg:hidden flex justify-center items-center gap-4 mt-5" data-reveal>
                        <button
                            @click="back(); start()"
                            class="w-11 h-11 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex items-center justify-center text-gray-700 dark:text-gray-200 active:bg-gray-900 active:text-white active:border-gray-900 transition-all duration-300 shadow-md"
                            aria-label="Slide sebelumnya"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                            </svg>
                        </button>
                        <button
                            @click="advance(); start()"
                            class="w-11 h-11 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex items-center justify-center text-gray-700 dark:text-gray-200 active:bg-gray-900 active:text-white active:border-gray-900 transition-all duration-300 shadow-md"
                            aria-label="Slide berikutnya"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        @else
            <div class="px-4 sm:px-6">
                <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 h-72 max-w-6xl mx-auto flex items-center justify-center text-gray-400 dark:text-gray-400 text-sm" data-reveal>
                    {{ __('Belum ada slide yang ditambahkan') }}
                </div>
            </div>
        @endif
    </section>

    {{-- ============================================ --}}
    {{-- Sekilas RPL: statistik ringkas + alumni sorotan --}}
    {{-- ============================================ --}}
    <section class="py-16 lg:py-24">
        <div class="site-container">
            {{-- Heading --}}
            <div class="text-center max-w-2xl mx-auto mb-10 lg:mb-14" data-reveal>
                <h2 class="type-section-title text-gray-900 dark:text-gray-50">{{ __('Sekilas RPL') }}</h2>
                <p class="type-lead text-gray-600 dark:text-gray-300 mt-3">
                    {{ __('Rekayasa Perangkat Lunak mencetak talenta digital yang siap bersaing di industri maupun dunia kampus.') }}
                </p>
            </div>

            {{-- Baris statistik: 3 persegi miring ke kiri (paralelogram),
                 latar warna brand Skansaba.dev tanpa border, teks tetap tegak. --}}
            @php
                $sekilasStats = [
                    ['value' => $totalSiswaAktif, 'label' => __('Siswa Aktif')],
                    ['value' => $totalAlumni, 'label' => __('Alumni')],
                    ['value' => $totalMitra, 'label' => __('Mitra')],
                ];
            @endphp

            <div class="grid grid-cols-3 gap-5 sm:gap-8 px-4 sm:px-10" data-reveal>
                @foreach($sekilasStats as $stat)
                    <div
                        class="relative skew-x-[8deg] sm:skew-x-[12deg] rounded-xl px-1.5 py-6 sm:px-6 sm:py-10"
                        style="background-color: {{ $globalSettings->primary_color_style }}"
                    >
                        <div class="-skew-x-[8deg] sm:-skew-x-[12deg] text-center">
                            <div class="type-stat text-gray-950">
                                {{ number_format($stat['value'], 0, ',', '.') }}
                            </div>
                            <div class="type-meta mt-1.5 text-gray-900 dark:text-gray-900">{{ $stat['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Alumni sorotan: 3 kolom — Profil, Foto, Kutipan --}}
            @if($featuredAlumni)
                <div class="mt-12 lg:mt-16" data-reveal>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10 lg:h-[440px] items-stretch">

                        {{-- 1. Profil --}}
                        <div class="order-2 lg:order-1 flex flex-col justify-center gap-5">
                            <div>
                                <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-50 mt-1">{{ $featuredAlumni->nama }}</p>
                            </div>
                            <div>
                                <p class="type-meta uppercase tracking-wider text-gray-400">{{ __('Tahun Lulus') }}</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-gray-50 mt-1">{{ $featuredAlumni->tahun_lulus }}</p>
                            </div>
                            <div>
                                <p class="type-meta uppercase tracking-wider text-gray-400">{{ __('Status') }}</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-gray-50 mt-1">{{ __($featuredAlumni->status_alumni_label) }}</p>
                            </div>
                            <div>
                                <p class="type-meta uppercase tracking-wider text-gray-400">{{ __('Institusi') }}</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-gray-50 mt-1">{{ $featuredAlumni->institusi ?: '—' }}</p>
                            </div>
                            <div>
                                <p class="type-meta uppercase tracking-wider text-gray-400">{{ __('Bidang') }}</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-gray-50 mt-1">{{ $featuredAlumni->bidang ?: '—' }}</p>
                            </div>
                        </div>

                        {{-- 2. Foto --}}
                        <div class="order-1 lg:order-2 h-72 sm:h-96 lg:h-full">
                            @if($featuredAlumni->foto_url)
                                <img
                                    src="{{ $featuredAlumni->foto_url }}"
                                    alt="{{ $featuredAlumni->nama }}"
                                    class="w-full h-full object-cover rounded-2xl"
                                    loading="lazy"
                                >
                            @else
                                <div class="w-full h-full rounded-2xl flex items-center justify-center text-white text-5xl font-bold" style="background-color: {{ $globalSettings->primary_color_style }}">
                                    {{ strtoupper(substr($featuredAlumni->nama, 0, 2)) }}
                                </div>
                            @endif
                        </div>

                        {{-- 3. Kutipan (kartu aksen kiri; kabut + Selengkapnya bila melebihi tinggi kolom) --}}
                        <div
                            class="order-3 lg:order-3 flex flex-col h-72 sm:h-96 lg:h-[440px] rounded-2xl border border-gray-200/80 dark:border-gray-800 border-l-[3px] pl-5 pr-5 py-5 sm:pl-6 sm:pr-6 sm:py-6"
                            style="border-left-color: {{ $globalSettings->primary_color_style }}"
                            x-data="{
                                truncated: false,
                                measure() {
                                    const el = this.$refs.quote;
                                    if (!el) return;
                                    this.truncated = el.scrollHeight > el.clientHeight + 4;
                                }
                            }"
                            x-init="$nextTick(() => measure()); window.addEventListener('load', () => measure()); window.addEventListener('resize', () => measure())"
                        >
                            <svg
                                class="w-7 h-7 shrink-0 opacity-30"
                                style="color: {{ $globalSettings->primary_color_style }}"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path d="M7.5 6C5 6 3 8 3 10.5S5 15 7.5 15c0 2-1.5 3-3 3.5l.5 1.5C8.5 19 10 16.5 10 13V10.5C10 8 8.8 6 7.5 6zm11 0C16 6 14 8 14 10.5s2 4.5 4.5 4.5c0 2-1.5 3-3 3.5l.5 1.5C19.5 19 21 16.5 21 13V10.5C21 8 19.8 6 18.5 6z"/>
                            </svg>

                            <div
                                x-ref="quote"
                                :class="truncated ? 'sekilas-quote' : ''"
                                class="mt-3 flex-1 min-h-0 overflow-hidden prose prose-sm max-w-none text-gray-600 dark:text-gray-300"
                            >
                                {!! $featuredAlumni->quote_html !!}
                            </div>

                            <div class="mt-auto flex items-center justify-end gap-3 pt-4">
                                <a
                                    x-show="truncated"
                                    x-cloak
                                    href="/alumni/{{ $featuredAlumni->id }}"
                                    class="type-button inline-flex items-center gap-1.5 text-sm font-semibold whitespace-nowrap transition-opacity hover:opacity-80"
                                    style="color: {{ $globalSettings->primary_color_style }}"
                                >
                                    <span>{{ __('Selengkapnya') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- Marquee Logo Mitra (2 baris berlawanan arah) --}}
    {{-- ============================================ --}}
    <section class="py-16 lg:py-20">
        <div class="w-full">
            @if($mitras->isNotEmpty())
                @php
                    $repeat = max(2, (int) ceil(8 / $mitras->count()));
                    $groupOne = collect();
                    for ($i = 0; $i < $repeat; $i++) { $groupOne = $groupOne->merge($mitras); }
                    $groupTwo = $groupOne->reverse()->values();
                @endphp

                <div class="space-y-6 md:space-y-8 marquee-hover-pause marquee-fade"
                     data-reveal>
                    {{-- Baris 1: geser kiri --}}
                    <div class="overflow-hidden py-2 marquee-row">
                        <div class="flex w-max animate-marquee-left" style="--marquee-duration: 80s">
                            @foreach([0, 1] as $copy)
                                <div class="flex items-center gap-[83px] md:gap-[91px] pr-[83px] md:pr-[91px]" @if($copy === 1) aria-hidden="true" @endif>
                                    @foreach($groupOne as $mitra)
                                        <div class="mitra-chip group/chip flex h-16 md:h-20 w-32 md:w-36 flex-none items-center justify-center rounded-xl border border-slate-100 bg-white px-1.5 shadow-[0_1px_4px_rgba(15,23,42,0.05)] overflow-hidden dark:h-20 dark:md:h-24 dark:w-auto dark:bg-transparent dark:px-0 dark:shadow-none dark:overflow-visible">
                                            @if($mitra->logo)
                                                <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->nama_mitra }}" class="max-h-[85%] max-w-[85%] w-auto object-contain mitra-logo dark:h-16 dark:max-h-full dark:max-w-full md:dark:h-20" loading="lazy" title="{{ $mitra->nama_mitra }}">
                                            @else
                                                <span class="text-xs font-semibold text-gray-500 dark:text-gray-300 text-center">{{ $mitra->nama_mitra }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Baris 2: geser kanan --}}
                    <div class="overflow-hidden py-2 marquee-row">
                        <div class="flex w-max animate-marquee-right" style="--marquee-duration: 80s">
                            @foreach([0, 1] as $copy)
                                <div class="flex items-center gap-[83px] md:gap-[91px] pr-[83px] md:pr-[91px]" @if($copy === 1) aria-hidden="true" @endif>
                                    @foreach($groupTwo as $mitra)
                                        <div class="mitra-chip group/chip flex h-16 md:h-20 w-32 md:w-36 flex-none items-center justify-center rounded-xl border border-slate-100 bg-white px-1.5 shadow-[0_1px_4px_rgba(15,23,42,0.05)] overflow-hidden dark:h-20 dark:md:h-24 dark:w-auto dark:bg-transparent dark:px-0 dark:shadow-none dark:overflow-visible">
                                            @if($mitra->logo)
                                                <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->nama_mitra }}" class="max-h-[85%] max-w-[85%] w-auto object-contain mitra-logo dark:h-16 dark:max-h-full dark:max-w-full md:dark:h-20" loading="lazy" title="{{ $mitra->nama_mitra }}">
                                            @else
                                                <span class="text-xs font-semibold text-gray-500 dark:text-gray-300 text-center">{{ $mitra->nama_mitra }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <p class="text-center text-gray-400 dark:text-gray-400 text-sm" data-reveal>{{ __('Belum ada mitra yang ditampilkan') }}</p>
            @endif
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- Prospek Karir & Mata Pelajaran Unggulan --}}
    {{-- ============================================ --}}
    <section class="py-16 lg:py-24">
        <div class="site-container" x-data="{ aktif: 'mapel' }">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16" data-reveal>

                {{-- SISI KIRI: Sidebar navigasi (kedua judul sebagai tab) --}}
                <nav class="lg:col-span-4 space-y-2" aria-label="{{ __('Prospek Karir') }} {{ __('Mata Pelajaran Unggulan') }}">
                    <button
                        type="button"
                        @click="aktif = 'karir'"
                        class="flex w-full items-center border-l-2 pl-4 py-2 text-left transition-all duration-300"
                        :class="aktif === 'karir'
                            ? 'type-tab-title-active border-gray-900 dark:border-gray-50 text-gray-900 dark:text-gray-50'
                            : 'type-tab-title border-transparent text-gray-300 hover:text-gray-500 dark:text-gray-700 dark:hover:text-gray-400'"
                    >
                        {{ __('Prospek Karir') }}
                    </button>

                    <button
                        type="button"
                        @click="aktif = 'mapel'"
                        class="flex w-full items-center border-l-2 pl-4 py-2 text-left transition-all duration-300"
                        :class="aktif === 'mapel'
                            ? 'type-tab-title-active border-gray-900 dark:border-gray-50 text-gray-900 dark:text-gray-50'
                            : 'type-tab-title border-transparent text-gray-300 hover:text-gray-500 dark:text-gray-700 dark:hover:text-gray-400'"
                    >
                        {{ __('Mata Pelajaran Unggulan') }}
                    </button>
                </nav>

                {{-- SISI KANAN: Konten sesuai judul yang aktif --}}
                <div class="lg:col-span-8">

                    {{-- Panel: Prospek Karir --}}
                    <div x-show="aktif === 'karir'" x-cloak x-transition.opacity.duration.200ms>

                        @if($prospekKarir->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @foreach($prospekKarir as $karir)
                                    <div class="group/karir flex flex-col gap-2" title="{{ $karir->description_excerpt }}">
                                        <span class="w-1.5 h-1.5 rounded-full flex-none" style="background-color: {{ $globalSettings->primary_color_style }}"></span>
                                        <span class="type-body-sm font-medium text-gray-600 dark:text-gray-300 group-hover/karir:text-gray-900 dark:group-hover/karir:text-white transition-colors duration-200">
                                            {{ $karir->title }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400">{{ __('Belum ada prospek karir') }}</p>
                        @endif
                    </div>

                    {{-- Panel: Mata Pelajaran Unggulan --}}
                    <div x-show="aktif === 'mapel'" x-cloak x-transition.opacity.duration.200ms>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($mataPelajaran as $mapel)
                            <div class="group/mapel flex flex-col gap-4">
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
                                    <h3 class="type-card-title-sm text-gray-900 dark:text-gray-50">{{ __($mapel['judul']) }}</h3>
                                    <p class="type-body-sm text-gray-500 dark:text-gray-400 mt-1.5">{{ __($mapel['deskripsi']) }}</p>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- Karya Siswa Carousel --}}
    {{-- ============================================ --}}
    <section class="py-16 lg:py-24">
        <div class="site-container">
            <div class="flex items-center justify-between gap-4 mb-6 sm:mb-16" data-reveal>
                <h2 class="type-section-title text-gray-900 dark:text-gray-50">
                    {{ __('Karya Siswa') }}
                </h2>
                <a
                    href="/karya"
                    class="type-button group/link inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors"
                >
                    <span>{{ __('Lihat Semua') }}</span>
                    <svg class="w-4 h-4 transition-transform group-hover/link:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            @if($karyaSiswa->isNotEmpty())
                <div
                    x-data="{
                        autoplay: null,
                        hovering: false,
                        barWidth: 12,
                        barLeft: 0,
                        canScroll: false,
                        page: 0,
                        animating: null,

                        init() {
                            this.update();
                            this.start();
                            this.$nextTick(() => this.update());
                        },

                        start() {
                            this.stop();
                            this.autoplay = setInterval(() => {
                                if (this.hovering) return;
                                this.move(1);
                            }, 3600);
                        },

                        stop() {
                            clearInterval(this.autoplay);
                        },

                        cardStep() {
                            const el = this.$refs.track;
                            if (!el || !el.children.length) return 280;
                            const gap = parseFloat(getComputedStyle(el).columnGap || '20') || 20;
                            return el.children[0].getBoundingClientRect().width + gap;
                        },

                        perPage() {
                            const el = this.$refs.track;
                            if (!el) return 1;
                            const cs = getComputedStyle(el);
                            const inner = el.clientWidth - parseFloat(cs.paddingLeft) - parseFloat(cs.paddingRight);
                            return Math.max(1, Math.round(inner / this.cardStep()));
                        },

                        pageCount() {
                            const el = this.$refs.track;
                            if (!el || !el.children.length) return 1;
                            return Math.max(1, Math.ceil(el.children.length / this.perPage()));
                        },

                        pageTarget(p) {
                            const el = this.$refs.track;
                            if (!el || !el.children.length) return 0;
                            const per = this.perPage();
                            const start = Math.min(p * per, Math.max(0, el.children.length - per));
                            return Math.min(start * this.cardStep(), el.scrollWidth - el.clientWidth);
                        },

                        move(dir) {
                            const total = this.pageCount();
                            let p = this.page + dir;
                            if (p >= total) p = 0;
                            if (p < 0) p = total - 1;
                            this.goto(p);
                        },

                        // Geser halus per halaman (4 kartu) dengan easing
                        // easeInOutCubic: mulai perlahan, meluncur, lalu
                        // berhenti lembut — bukan lompat instan.
                        animateTo(target) {
                            const el = this.$refs.track;
                            if (!el) return;
                            if (this.animating) cancelAnimationFrame(this.animating);
                            const start = el.scrollLeft;
                            const delta = target - start;
                            if (Math.abs(delta) < 1) { el.scrollLeft = target; return; }
                            const duration = 900;
                            const t0 = performance.now();
                            const ease = (t) => t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
                            const step = (now) => {
                                const p = Math.min(1, (now - t0) / duration);
                                el.scrollLeft = start + delta * ease(p);
                                if (p < 1) {
                                    this.animating = requestAnimationFrame(step);
                                } else {
                                    this.animating = null;
                                }
                            };
                            this.animating = requestAnimationFrame(step);
                        },

                        goto(p) {
                            const el = this.$refs.track;
                            if (!el) return;
                            this.page = p;
                            this.animateTo(this.pageTarget(p));
                        },

                        scrollCards(dir) {
                            this.move(dir);
                            this.start();
                        },

                        update() {
                            const el = this.$refs.track;
                            if (!el) return;
                            const total = this.pageCount();
                            let best = 0;
                            let bestDist = Infinity;
                            for (let i = 0; i < total; i++) {
                                const d = Math.abs(this.pageTarget(i) - el.scrollLeft);
                                if (d < bestDist) { bestDist = d; best = i; }
                            }
                            this.page = best;
                            this.canScroll = total > 1;
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
                    {{-- Track (frame menutup kartu yang mengintip di zona padding) --}}
                    <div class="relative -mx-1.5 sm:-mx-[4.5rem] -my-4 sm:-my-20">
                        <div
                            x-ref="track"
                            @scroll.passive="update()"
                            @resize.window.debounce.150="update()"
                            class="flex gap-4 sm:gap-5 overflow-x-hidden touch-pan-y py-4 sm:py-20 pl-1.5 sm:pl-[4.5rem] pr-4 sm:pr-[4.5rem]"
                        >
                        @foreach($karyaSiswa as $item)
                            <a
                                href="/karya/{{ $item->id }}"
                                class="group relative flex-none w-[200px] sm:w-[calc((100%_-_2.5rem)/3)] lg:w-[calc((100%_-_3.75rem)/4)] aspect-[2/3] rounded-2xl overflow-hidden snap-start
                                       transition-all duration-300 ease-out
                                       sm:hover:-translate-y-2 sm:hover:rotate-[5deg] sm:hover:scale-[1.20] sm:hover:shadow-[0_20px_40px_-12px_rgba(15,23,42,0.4)] sm:hover:z-20
                                       sm:hover:ring-1 sm:hover:ring-white/20 active:scale-[0.98]"
                            >
                                <div class="absolute inset-0 karya-fade bg-slate-200 dark:bg-slate-800">
                                    @if($item->gambar)
                                        <img
                                            src="{{ asset('storage/' . $item->gambar) }}"
                                            alt="{{ $item->judul }}"
                                            class="w-full h-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-[1.08]"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-200 to-slate-400 dark:from-slate-700 dark:to-slate-900 p-6">
                                            <span class="text-slate-600 dark:text-white/80 font-semibold text-lg text-center">{{ $item->judul }}</span>
                                        </div>
                                    @endif

                                    {{-- Overlay --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-white/25 to-transparent dark:from-black/80 dark:via-black/10 dark:to-transparent pointer-events-none transition-opacity duration-500 group-hover:from-white/75 dark:group-hover:from-black/70"></div>
                                </div>

                                {{-- Info --}}
                                <div class="absolute bottom-0 left-0 right-0 p-4 pointer-events-none">
                                    <h3 class="type-card-title-sm text-gray-950 dark:text-white">{{ $item->judul }}</h3>
                                    @if($item->nama_siswa)
                                        <p class="type-meta text-slate-600 dark:text-white/80 mt-1 min-h-8 opacity-90 sm:opacity-0 sm:group-hover:opacity-100 sm:translate-y-1 sm:group-hover:translate-y-0 transition-all duration-300">
                                            {{ $item->nama_siswa }}{{ $item->kategori ? ' · ' . $item->kategori : '' }}
                                        </p>
                                    @else
                                        {{-- Penyeimbang: kartu tanpa nama tetap punya dua baris
                                             ruang meta, supaya judul sejajar dengan kartu lain. --}}
                                        <p aria-hidden="true" class="type-meta mt-1 min-h-8 select-none invisible">&nbsp;</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                        </div>

                        {{-- Penutup kartu yang mengintip di zona padding (52px).
                             pointer-events aktif agar kartu tersembunyi tidak bisa di-hover. --}}
                        <div aria-hidden="true" class="absolute inset-y-0 left-0 z-10 hidden sm:block w-[3.5rem] bg-white dark:bg-gray-950"></div>
                        <div aria-hidden="true" class="absolute inset-y-0 right-0 z-10 hidden sm:block w-[3.5rem] bg-white dark:bg-gray-950"></div>
                    </div>

                    {{-- Desktop Arrows (Hanya di layar desktop, di luar kartu agar tidak menempel) --}}
                    <button
                        x-show="canScroll"
                        @click="scrollCards(-1)"
                        class="hidden xl:flex absolute -left-14 xl:-left-16 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-sm items-center justify-center text-gray-700 dark:text-gray-200 hover:bg-gray-900 dark:hover:bg-gray-800 hover:text-white dark:hover:text-white hover:border-gray-900 dark:hover:border-gray-600 transition-all duration-200 z-30"
                        aria-label="{{ __('Geser kiri') }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12l7.5-7.5m0 15L3 12h18"/>
                        </svg>
                    </button>
                    <button
                        x-show="canScroll"
                        @click="scrollCards(1)"
                        class="hidden xl:flex absolute -right-14 xl:-right-16 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-sm items-center justify-center text-gray-700 dark:text-gray-200 hover:bg-gray-900 dark:hover:bg-gray-800 hover:text-white dark:hover:text-white hover:border-gray-900 dark:hover:border-gray-600 transition-all duration-200 z-30"
                        aria-label="{{ __('Geser kanan') }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>

                    {{-- Mobile Controls: Tombol panah di bawah slider untuk layar mobile & tablet --}}
                    <div x-show="canScroll" class="xl:hidden flex items-center justify-center gap-3 mt-5" data-reveal>
                        <button
                            @click="scrollCards(-1)"
                            class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex items-center justify-center text-gray-700 dark:text-gray-200 active:bg-gray-900 active:text-white active:border-gray-900 transition-all duration-150 shadow-sm"
                            aria-label="{{ __('Geser kiri') }}"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12l7.5-7.5m0 15L3 12h18"/>
                            </svg>
                        </button>
                        <button
                            @click="scrollCards(1)"
                            class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex items-center justify-center text-gray-700 dark:text-gray-200 active:bg-gray-900 active:text-white active:border-gray-900 transition-all duration-150 shadow-sm"
                            aria-label="{{ __('Geser kanan') }}"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Progress Bar (hanya tampil jika bisa digeser) --}}
                    <div x-show="canScroll" class="mt-5 lg:mt-10 h-[3px] w-3/4 sm:w-2/3 lg:w-1/2 mx-auto bg-gray-200 dark:bg-gray-800 rounded-full relative overflow-hidden">
                        <div
                            class="absolute top-0 h-full bg-slate-800 dark:bg-sky-400 rounded-full transition-all duration-300 ease-out"
                            :style="`width: ${barWidth}%; left: ${barLeft}%`"
                        ></div>
                    </div>
                </div>
            @else
                <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 h-56 flex items-center justify-center text-gray-400 dark:text-gray-400 text-sm" data-reveal>
                    {{ __('Belum ada karya siswa yang dipublikasikan') }}
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- Berita Terbaru --}}
    {{-- ============================================ --}}
    <section class="pt-16 pb-24 lg:pt-24 lg:pb-32">
        <div class="site-container">
            <div class="flex items-end justify-between gap-6 mb-8 lg:mb-10" data-reveal>
                <h2 class="type-section-title text-gray-900 dark:text-gray-50">
                    {{ __('Berita Terbaru') }}
                </h2>
                <a
                    href="/berita"
                    class="type-button hidden sm:flex items-center gap-2 text-gray-700 dark:text-gray-200 hover:gap-3 transition-all"
                >
                    {{ __('Lihat Semua') }}
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
                                        ⭐ {{ __('Unggulan') }}
                                    </span>
                                @endif
                            </div>

                            {{-- Body: hanya judul saat normal, sisanya muncul saat hover --}}
                            <div class="p-5 lg:p-6">
                                <h3 class="type-card-title-sm text-gray-900 dark:text-gray-50 line-clamp-2 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
                                    {{ $item->title }}
                                </h3>

                                <div class="grid grid-rows-[1fr] sm:grid-rows-[0fr] sm:group-hover:grid-rows-[1fr] transition-[grid-template-rows] duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                                    <div class="overflow-hidden">
                                        <div class="type-meta flex items-center gap-2 text-gray-400 dark:text-gray-500 pt-3">
                                            <span>{{ $item->published_at?->translatedFormat('d M Y') }}</span>
                                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                            <span>{{ $item->views }} {{ __('views') }}</span>
                                        </div>

                                        <p class="type-body-sm text-gray-500 dark:text-gray-400 line-clamp-2 mt-2">
                                            {{ $item->excerpt ?? Str::limit(strip_tags($item->content_md), 100) }}
                                        </p>

                                        <span class="type-button inline-flex items-center gap-1.5 mt-3" style="color: {{ $globalSettings->primary_color_style }}">
                                            {{ __('Baca Selengkapnya') }}
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
                        class="type-button inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gray-900 text-white hover:bg-gray-700 transition"
                    >
                        {{ __('Lihat Semua Berita') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
            @else
                <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 h-56 flex items-center justify-center text-gray-400 dark:text-gray-400 text-sm" data-reveal>
                    {{ __('Belum ada berita yang dipublikasikan') }}
                </div>
            @endif
        </div>
    </section>
</div>
