<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- Header dengan Breadcrumb --}}
    <section class="bg-white dark:bg-gray-950 py-8 lg:py-10">
        <div class="site-container text-center">
            {{-- Breadcrumb --}}
            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-4" data-reveal>
                <svg class="w-5 h-5" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <a href="/" class="hover:opacity-80 cursor-pointer">{{ __('Beranda') }}</a>
                <span>›</span>
                <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Struktur Organisasi') }}</span>
            </div>

            <h1 class="type-page-title text-gray-900 dark:text-gray-50 leading-tight" data-reveal>
                {{ __('Guru') }} <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('RPL') }}</span> {{ __('SMK Negeri 1 Bantul') }}
            </h1>
        </div>
    </section>

    {{-- Daftar guru: zig-zag alternating (foto kiri - teks kanan, lalu selang-seling) --}}
    <section class="pb-16 lg:pb-24">
        <div class="site-container">
            @if($members->count() > 0)
                <div class="space-y-10 lg:space-y-20">
                    @foreach($members as $index => $member)
                        @php
                            $flip = $index % 2 === 1;
                            $isKaproli = str_contains(strtolower($member->jabatan), 'ketua program') || str_contains(strtolower($member->jabatan), 'kaproli');
                        @endphp
                        <div
                            class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 items-center strutur-row {{ $flip ? 'strutur-row--right' : 'strutur-row--left' }}"
                        >

                            {{-- Foto --}}
                            <div class="md:col-span-4 strutur-foto {{ $flip ? 'md:order-2 md:col-start-9' : '' }}">
                                <a href="/struktur-organisasi/{{ $member->id }}" class="block group">
                                    <div class="relative rounded-3xl overflow-hidden aspect-[4/5] max-w-[320px] mx-auto md:max-w-none shadow-[0_10px_30px_rgba(15,23,42,0.12)] transition-all duration-500 group-hover:shadow-[0_20px_44px_rgba(15,23,42,0.2)] group-hover:-translate-y-1.5">
                                        @if($member->foto_url)
                                            <img
                                                src="{{ $member->foto_url }}"
                                                alt="{{ $member->nama }}"
                                                class="w-full h-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-[1.06]"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-white text-6xl font-bold" style="background: linear-gradient(135deg, {{ $globalSettings->primary_color_style }} 0%, {{ $globalSettings->primary_color_style }}cc 100%)">
                                                {{ strtoupper(substr($member->nama, 0, 2)) }}
                                            </div>
                                        @endif
                                        {{-- Aksen lingkaran dekoratif: berputar & membesar saat hover --}}
                                        <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full border-[14px] border-white/30 pointer-events-none transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:rotate-45 group-hover:scale-110"></div>

                                        {{-- Bintang aksen di atas foto (tanpa container) --}}
                                        @if($isKaproli)
                                            <div class="absolute top-4 left-4 text-white drop-shadow-md transition-transform duration-500 group-hover:scale-110">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 1l2.928 5.932 6.544.951-4.736 4.617 1.118 6.516L10 15.897l-5.854 3.078 1.118-6.516L.528 7.883l6.544-.951L10 1z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </div>

                            {{-- Info --}}
                            <div class="md:col-span-7 strutur-info {{ $flip ? 'md:order-1 md:col-start-1 text-left' : '' }}">
                                @if($isKaproli)
                                    <span
                                        class="inline-flex items-center py-1 rounded-full text-sm font-bold mb-3 strutur-badge-pop"
                                        style="background-color: {{ $globalSettings->primary_color_style }}1a; color: {{ $globalSettings->primary_color_style }}"
                                    >
                                        {{ __('Ketua Program Keahlian PPLG') }}
                                    </span>
                                @endif

                                <a href="/struktur-organisasi/{{ $member->id }}" class="group/name block">
                                    <h2 class="type-subsection-title text-gray-900 dark:text-gray-50 mb-4 transition-all duration-300 group-hover/name:translate-x-1.5 group-hover/name:text-gray-600 dark:group-hover/name:text-gray-300">
                                        {{ $member->nama }}
                                    </h2>
                                </a>

                                @if($member->deskripsi_md)
                                    <div class="type-body-sm text-gray-600 dark:text-gray-300 max-w-xl strutur-desc">
                                        {!! $member->deskripsi_html !!}
                                    </div>
                                @endif

                                <a
                                    href="/struktur-organisasi/{{ $member->id }}"
                                    class="strutur-btn type-button inline-flex items-center gap-2 mt-6 px-5 py-2.5 rounded-lg text-white transition-all duration-300 hover:gap-3 hover:shadow-lg hover:-translate-y-0.5"
                                    style="background-color: {{ $globalSettings->primary_color_style }}"
                                >
                                    {{ __('Selengkapnya') }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background-color: {{ $globalSettings->primary_color_style }}22">
                        <svg class="w-8 h-8" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="type-subsection-title text-gray-900 dark:text-gray-50 mb-2">{{ __('Belum Ada Data') }}</h3>
                    <p class="type-body text-gray-600 dark:text-gray-400">{{ __('Struktur organisasi belum tersedia saat ini') }}</p>
                </div>
            @endif
        </div>
    </section>
</div>
