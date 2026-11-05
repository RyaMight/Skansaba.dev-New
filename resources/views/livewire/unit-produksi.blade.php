<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- Header dengan Breadcrumb --}}
    <section class="bg-white dark:bg-gray-900 py-8 lg:py-10 border-b border-gray-100 dark:border-gray-800">
        <div class="site-container text-center">
            {{-- Breadcrumb --}}
            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-4" data-reveal>
                <svg class="w-5 h-5" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <a href="/" class="hover:opacity-80 cursor-pointer">{{ __('Beranda') }}</a>
                <span>›</span>
                <span style="color: {{ $globalSettings->primary_color_style }}">{{ __('Unit Produksi') }}</span>
            </div>

            <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50 mb-2" data-reveal>
                {{ __('Harga Jasa Pembuatan Website') }} <span style="color: {{ $globalSettings->primary_color_style }}">Skansaba.Dev</span>
            </h1>
            <p class="text-gray-600 dark:text-gray-300 text-sm sm:text-base max-w-2xl mx-auto" data-reveal>{{ __('Pilih paket website yang sesuai dengan kebutuhan Anda.') }}</p>
        </div>
    </section>

    {{-- Pricing Grid --}}
    <section class="py-12">
        <div class="site-container">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 items-start">

                @php
                    $paket = [
                        [
                            'nama' => 'UMKM', 'harga' => '499.000', 'halaman' => '3 ' . __('halaman'),
                            'waktu' => '7 ' . __('hari kerja'), 'disk' => '1 GB', 'email' => '2',
                            'subdomain' => 3, 'database' => 3, 'setup' => 'Rp99.000',
                            'highlight' => false,
                        ],
                        [
                            'nama' => 'Basic', 'harga' => '750.000', 'halaman' => '5 ' . __('halaman'),
                            'waktu' => '14 ' . __('hari kerja'), 'disk' => '1 GB', 'email' => '2',
                            'subdomain' => 3, 'database' => 3, 'setup' => 'Rp99.000',
                            'highlight' => false,
                        ],
                        [
                            'nama' => 'Pro', 'harga' => '1.500.000', 'halaman' => '10 ' . __('halaman'),
                            'waktu' => '21 ' . __('hari kerja'), 'disk' => '5 GB', 'email' => '7',
                            'subdomain' => 5, 'database' => 5, 'setup' => 'Rp198.000',
                            'highlight' => true,
                        ],
                        [
                            'nama' => 'Business', 'harga' => '2.250.000', 'halaman' => '15 ' . __('halaman'),
                            'waktu' => '30 ' . __('hari kerja'), 'disk' => '10 GB', 'email' => '20',
                            'subdomain' => 7, 'database' => 7, 'setup' => 'Rp297.000',
                            'highlight' => false,
                        ],
                    ];
                @endphp

                @foreach($paket as $p)
                    <div class="group relative bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-xs overflow-hidden flex flex-col transition-colors duration-150">
                        {{-- Ring highlight primary: muncul saat kartu di-hover --}}
                        <div class="pointer-events-none absolute inset-0 rounded-xl ring-2 ring-inset opacity-0 group-hover:opacity-100 transition-opacity duration-150"
                             style="--tw-ring-color: {{ $globalSettings->primary_color_style }}"></div>
                        {{-- Header paket --}}
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-50">{{ $p['nama'] }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Mulai Dari') }}</p>
                        </div>

                        {{-- Harga --}}
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                            <p class="flex items-baseline gap-1">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Rp</span>
                                <span class="text-2xl font-bold text-gray-900 dark:text-gray-50 tracking-tight">{{ $p['harga'] }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">/{{ __('thn') }}</span>
                            </p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">*{{ __('Minimal kontrak 1 Tahun') }}</p>
                        </div>

                        {{-- Fitur --}}
                        <ul class="px-6 py-5 space-y-3 text-sm flex-1">
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">{{ __('Pembuatan Halaman Website:') }} <strong class="font-semibold">{{ $p['halaman'] }}</strong></span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">{{ __('Waktu Pengerjaan:') }} <strong class="font-semibold">{{ $p['waktu'] }}</strong></span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">{{ __('Hosting:') }} <strong class="font-semibold">{{ $p['disk'] }}</strong> Disk Space</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <div class="text-gray-700 dark:text-gray-200">
                                    <strong class="font-semibold">{{ $p['email'] }} Email</strong>
                                    <ul class="mt-1.5 space-y-1 text-xs text-gray-500 dark:text-gray-400">
                                        <li>- Domain: 1</li>
                                        <li>- Subdomain: {{ $p['subdomain'] }}</li>
                                        <li>- Database: {{ $p['database'] }}</li>
                                        <li>- Bandwidth: Unlimited</li>
                                        <li>- Anti DDOS Protection</li>
                                    </ul>
                                </div>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">Domain <strong class="font-semibold">1</strong> {{ __('Tahun') }}</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">{{ __('Sertifikat') }} <strong class="font-semibold">SSL</strong></span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">{{ __('Logo Image Editing: 5 gambar + 1 logo') }}</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">{{ __('Header Slideshow') }}</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">{{ __('Pemeliharaan') }}</span>
                            </li>
                        </ul>

                        {{-- CTA --}}
                        <div class="px-6 pb-6">
                            <a
                                href="{{ route('beranda') }}#kontak"
                                class="block w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90"
                                style="background-color: {{ $globalSettings->primary_color_style }}"
                            >
                                {{ __('Lihat Paket') }}
                            </a>
                            <p class="mt-3 text-center text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-lg py-2">
                                {{ __('Biaya Setup:') }} {{ $p['setup'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Catatan kaki --}}
            <p class="mt-8 text-xs text-gray-500 dark:text-gray-400 text-center max-w-2xl mx-auto">
                {{ __('Cocok untuk anda yang sudah memiliki konten (foto/teks). Jika tidak, anda bisa meminta jasa pembuatan konten. Harga dapat berubah sewaktu-waktu tanpa pemberitahuan.') }}
            </p>
        </div>
    </section>

    {{-- Mengapa Harus Membuat Website di Skansaba.Dev? --}}
    <section class="pt-16 sm:pt-24 lg:pt-32 pb-36 sm:pb-48 lg:pb-60">
        <div class="site-container">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-stretch">

                {{-- Kolom Kiri: Kartu Biru Skansaba.Dev + Ilustrasi & Keunggulan (Sejajar Tinggi Penuh) --}}
                <div class="lg:col-span-5 w-full max-w-md mx-auto lg:max-w-none flex flex-col h-full">
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-800 text-white p-7 sm:p-9 shadow-xl shadow-blue-500/10 border border-blue-400/20 flex-1 flex flex-col justify-center">
                        {{-- Background blur accents --}}
                        <div class="absolute -top-12 -right-12 w-44 h-44 bg-cyan-400/20 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="absolute -bottom-12 -left-12 w-44 h-44 bg-indigo-500/30 rounded-full blur-2xl pointer-events-none"></div>

                        {{-- Konten terpusat vertikal & horizontal di tengah kotak --}}
                        <div class="relative z-10 w-full flex flex-col items-center justify-center my-auto">
                            {{-- Nama Brand --}}
                            <div class="text-center mb-5">
                                <span class="text-xl sm:text-2xl font-bold tracking-tight text-white drop-shadow-xs">Skansaba.Dev</span>
                            </div>

                            {{-- Ilustrasi Isometric: Platform, Rocket, Target, Chart, Gear --}}
                            <div class="relative w-full aspect-[4/3] max-w-[280px] sm:max-w-[320px] mx-auto flex items-center justify-center my-1">
                                <svg class="w-full h-full drop-shadow-md" viewBox="0 0 320 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    {{-- Base Isometric Platform --}}
                                    <ellipse cx="160" cy="195" rx="100" ry="24" fill="#1e3a8a" fill-opacity="0.4" />
                                    <polygon points="160,215 250,175 160,135 70,175" fill="#1d4ed8" fill-opacity="0.5" />
                                    <polygon points="160,225 250,175 250,183 160,233 70,183 70,175" fill="#1e40af" fill-opacity="0.7" />
                                    <polygon points="160,205 240,170 160,138 80,170" fill="#3b82f6" fill-opacity="0.35" />

                                    {{-- Chart Plate (Kiri) --}}
                                    <g transform="translate(68, 115)">
                                        <polygon points="0,35 45,15 45,55 0,75" fill="#1e40af" fill-opacity="0.8" />
                                        <polygon points="0,35 45,15 75,28 30,48" fill="#60a5fa" fill-opacity="0.4" />
                                        {{-- Bar charts --}}
                                        <rect x="8" y="42" width="6" height="18" rx="2" fill="#38bdf8" />
                                        <rect x="18" y="32" width="6" height="28" rx="2" fill="#60a5fa" />
                                        <rect x="28" y="24" width="6" height="36" rx="2" fill="#a5f3fc" />
                                        {{-- Upward trend line --}}
                                        <polyline points="10,40 21,30 31,20" stroke="#fef08a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="31" cy="20" r="2.5" fill="#facc15" />
                                    </g>

                                    {{-- Cogwheel / Gear (Kanan Atas) --}}
                                    <g transform="translate(210, 85)" fill="#60a5fa" fill-opacity="0.35">
                                        <circle cx="18" cy="18" r="14" />
                                        <circle cx="18" cy="18" r="6" fill="#1e3a8a" />
                                        <rect x="15" y="0" width="6" height="36" rx="2" />
                                        <rect x="0" y="15" width="36" height="6" rx="2" />
                                        <rect x="5" y="5" width="26" height="6" rx="2" transform="rotate(45 18 18)" />
                                        <rect x="5" y="5" width="26" height="6" rx="2" transform="rotate(-45 18 18)" />
                                    </g>

                                    {{-- Target / Dartboard (Kanan) --}}
                                    <g transform="translate(202, 108)">
                                        <ellipse cx="26" cy="26" rx="24" ry="20" fill="#0284c7" stroke="#bae6fd" stroke-width="2" />
                                        <ellipse cx="26" cy="26" rx="16" ry="13" fill="#ffffff" />
                                        <ellipse cx="26" cy="26" rx="9" ry="7" fill="#ef4444" />
                                        <ellipse cx="26" cy="26" rx="3.5" ry="3" fill="#ffffff" />
                                        {{-- Arrow hitting bullseye --}}
                                        <path d="M48 6 L27 24" stroke="#f8fafc" stroke-width="3" stroke-linecap="round" />
                                        <polygon points="46,3 52,6 48,11" fill="#f59e0b" />
                                    </g>

                                    {{-- Rocket Exhaust Smoke & Flame --}}
                                    <ellipse cx="160" cy="168" rx="18" ry="7" fill="#ffffff" fill-opacity="0.35" />
                                    <ellipse cx="160" cy="164" rx="12" ry="5" fill="#fef08a" fill-opacity="0.6" />
                                    <path d="M152 142 Q160 170 160 178 Q160 170 168 142 Z" fill="url(#flameGrad)" />

                                    {{-- Rocket Body --}}
                                    <g transform="translate(160, 118) rotate(-28)">
                                        {{-- Rocket Left & Right Fins --}}
                                        <path d="M-14 18 L-22 36 L-7 32 Z" fill="#ef4444" />
                                        <path d="M14 18 L22 36 L7 32 Z" fill="#dc2626" />
                                        {{-- Thruster nozzle --}}
                                        <rect x="-6" y="28" width="12" height="5" rx="1.5" fill="#64748b" />
                                        {{-- Fuselage --}}
                                        <path d="M0 -34 C14 -14 14 18 10 28 L-10 28 C-14 18 -14 -14 0 -34 Z" fill="#ffffff" />
                                        {{-- Fuselage Highlight & Shadow --}}
                                        <path d="M0 -34 C-14 -14 -14 18 -10 28 L0 28 Z" fill="#f1f5f9" />
                                        {{-- Nose Cone Tip --}}
                                        <path d="M0 -34 C7 -24 9 -14 9 -8 L-9 -8 C-9 -14 -7 -24 0 -34 Z" fill="#ef4444" />
                                        {{-- Center Fin --}}
                                        <path d="M-1.5 -6 L1.5 -6 L2 28 L-2 28 Z" fill="#38bdf8" />
                                        {{-- Window Porthole --}}
                                        <circle cx="0" cy="2" r="6" fill="#0284c7" stroke="#e2e8f0" stroke-width="2" />
                                        <circle cx="1.5" cy="0.5" r="2" fill="#ffffff" fill-opacity="0.8" />
                                    </g>

                                    {{-- Little sparkle stars --}}
                                    <circle cx="108" cy="78" r="2" fill="#fef08a" />
                                    <circle cx="230" cy="62" r="1.5" fill="#ffffff" />
                                    <circle cx="132" cy="56" r="2.5" fill="#ffffff" />
                                    <circle cx="190" cy="42" r="2" fill="#67e8f9" />

                                    <defs>
                                        <linearGradient id="flameGrad" x1="160" y1="142" x2="160" y2="178" gradientUnits="userSpaceOnUse">
                                            <stop offset="0%" stop-color="#f59e0b" />
                                            <stop offset="60%" stop-color="#ef4444" />
                                            <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>

                            {{-- 4 Poin Keunggulan (2 Kolom) Posisi Tengah --}}
                            <div class="w-full max-w-xs sm:max-w-sm mx-auto mt-6 pt-6 border-t border-white/15">
                                <div class="grid grid-cols-2 gap-x-4 sm:gap-x-6 gap-y-3.5 text-xs sm:text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-cyan-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                        <span>{{ __('Terjangkau') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-cyan-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                        <span>{{ __('Pengerjaan Cepat') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-cyan-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                        <span>{{ __('Profesional') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-cyan-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                        <span>{{ __('Gratis Maintenance') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Judul, Narasi Persuasif & CTA --}}
                <div class="lg:col-span-7 flex flex-col justify-between h-full">
                    <div class="space-y-6">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-50 tracking-tight leading-tight">
                            {{ __('Mengapa Harus Membuat Website di') }} <span style="color: {{ $globalSettings->primary_color_style }}">Skansaba.Dev</span>?
                        </h2>

                        <div class="space-y-4 text-sm sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                            <p>
                                {{ __('Banyak pemilik usaha, sekolah, maupun instansi ragu saat ingin membuat website karena tarif agensi software house yang sering kali selangit. Di sisi lain, mencoba buat sendiri memakai template instan gratisan sering berakhir bikin pusing: tampilannya kaku, lambat dibuka dari ponsel, dan tidak ada yang membantu saat terjadi kendala teknis. Kami hadir untuk menepis kekhawatiran tersebut. Website profesional untuk bisnis Anda seharusnya menjadi sarana membangun kepercayaan pelanggan—bukan beban pengeluaran yang memberatkan.') }}
                            </p>
                            <p>
                                {{ __('Sebagai unit produksi resmi jurusan Rekayasa Perangkat Lunak (RPL) SMKN 1 Bantul, setiap website kami bangun dengan standar kode yang bersih, responsif di semua ukuran layar, dan dalam supervisi langsung guru pembimbing serta praktisi berpengalaman. Anda mendapatkan paket lengkap yang siap online: domain aktif, sertifikat keamanan SSL, server cepat, hingga garansi pemeliharaan dan pendampingan penggunaan. Anda bisa fokus mengembangkan bisnis, sementara urusan teknis website biar kami yang tangani.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Tombol CTA Konsultasi --}}
                    <div class="pt-6 lg:pt-8">
                        @php
                            $waUrl = $globalSettings->whatsapp_url ?? 'https://wa.me/6285111345644';
                            $pesanWa = urlencode(__('Halo Skansaba.Dev, saya tertarik ingin konsultasi mengenai pembuatan website untuk kebutuhan saya.'));
                            $konsultasiHref = $waUrl . (str_contains($waUrl, '?') ? '&' : '?') . 'text=' . $pesanWa;
                        @endphp
                        <a
                            href="{{ $konsultasiHref }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl text-sm sm:text-base font-semibold text-white bg-amber-500 hover:bg-amber-600 active:bg-amber-700 shadow-md shadow-amber-500/20 transition-all duration-150 transform hover:-translate-y-0.5"
                        >
                            {{-- Chat Bubble Icon --}}
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/>
                                <path d="M7 9h10v2H7zm0-3h10v2H7z"/>
                            </svg>
                            <span>{{ __('Konsultasi Sekarang') }}</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
