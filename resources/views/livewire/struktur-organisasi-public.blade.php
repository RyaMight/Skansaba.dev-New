<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- Header --}}
    <section class="bg-white dark:bg-gray-950 py-10 lg:py-14">
        <div class="site-container text-center">
            {{-- Badge kecil di atas judul --}}
            <span
                class="inline-block px-4 py-1.5 rounded-full text-[11px] font-bold tracking-widest uppercase mb-5"
                style="background-color: {{ $globalSettings->primary_color_style }}1a; color: {{ $globalSettings->primary_color_style }}"
            >
                Struktur Organisasi RPL
            </span>

            <h1 class="text-3xl lg:text-[40px] font-extrabold tracking-tight text-gray-900 dark:text-gray-50 mb-4 leading-tight">
                Pendidik & Tenaga Kependidikan Rekayasa Perangkat Lunak
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm sm:text-base max-w-xl mx-auto leading-relaxed">
                Mengenal profil dan kompetensi para guru produktif RPL SMK Negeri 1 Bantul yang berdedikasi membimbing siswa menuju keahlian industri digital.
            </p>
        </div>
    </section>

    {{-- Daftar guru: zig-zag alternating (foto kiri - teks kanan, lalu selang-seling) --}}
    <section class="pb-16 lg:pb-24">
        <div class="site-container">
            @if($members->count() > 0)
                <div class="space-y-14 lg:space-y-20">
                    @foreach($members as $index => $member)
                        @php $flip = $index % 2 === 1; @endphp
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 items-center" data-reveal>

                            {{-- Foto --}}
                            <div class="md:col-span-4 {{ $flip ? 'md:order-2 md:col-start-9' : '' }}">
                                <a href="/struktur-organisasi/{{ $member->id }}" class="block group">
                                    <div class="relative rounded-3xl overflow-hidden aspect-[4/5] max-w-[320px] mx-auto md:max-w-none shadow-[0_10px_30px_rgba(15,23,42,0.12)]">
                                        @if($member->foto_url)
                                            <img
                                                src="{{ $member->foto_url }}"
                                                alt="{{ $member->nama }}"
                                                class="w-full h-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-[1.04]"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-white text-6xl font-bold" style="background: linear-gradient(135deg, {{ $globalSettings->primary_color_style }} 0%, {{ $globalSettings->primary_color_style }}cc 100%)">
                                                {{ strtoupper(substr($member->nama, 0, 2)) }}
                                            </div>
                                        @endif
                                        {{-- Aksen lingkaran dekoratif seperti desain --}}
                                        <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full border-[14px] border-white/30 pointer-events-none"></div>
                                    </div>
                                </a>
                            </div>

                            {{-- Info --}}
                            <div class="md:col-span-7 {{ $flip ? 'md:order-1 md:col-start-1 text-left' : '' }}">
                                <p class="text-xs text-gray-400 dark:text-gray-500 mb-2">
                                    Tenang Kami <span class="mx-1">›</span> {{ $member->jabatan }}
                                </p>

                                <a href="/struktur-organisasi/{{ $member->id }}" class="group/name">
                                    <h2 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50 mb-4 transition-colors group-hover/name:text-gray-600 dark:group-hover/name:text-gray-300">
                                        {{ $member->nama }}
                                    </h2>
                                </a>

                                @if($member->deskripsi_md)
                                    <div class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed max-w-xl strutur-desc">
                                        {!! $member->deskripsi_html !!}
                                    </div>
                                @endif

                                <a
                                    href="/struktur-organisasi/{{ $member->id }}"
                                    class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 rounded-lg text-white text-sm font-semibold transition-all duration-300 hover:gap-3 hover:shadow-lg"
                                    style="background-color: #B91C1C"
                                >
                                    Jelajahi
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
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-50 mb-2">Belum Ada Data</h3>
                    <p class="text-gray-600 dark:text-gray-400">Struktur organisasi belum tersedia saat ini</p>
                </div>
            @endif
        </div>
    </section>
</div>
