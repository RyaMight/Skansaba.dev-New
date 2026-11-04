<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- Header dengan Breadcrumb --}}
    <section class="bg-white dark:bg-gray-900 py-8 border-b border-gray-100 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-6">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-4">
                <svg class="w-5 h-5" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <a href="/" class="hover:opacity-80">Beranda</a>
                <span>›</span>
                <span style="color: {{ $globalSettings->primary_color_style }}">Unit Produksi</span>
            </div>

            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-50 mb-2">
                Harga Jasa Pembuatan Website <span style="color: {{ $globalSettings->primary_color_style }}">Skansaba.Dev</span>
            </h1>
            <p class="text-gray-600 dark:text-gray-300">Pilih paket website yang sesuai dengan kebutuhan Anda.</p>
        </div>
    </section>

    {{-- Pricing Grid --}}
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 items-start">

                @php
                    $paket = [
                        [
                            'nama' => 'UMKM', 'harga' => '499.000', 'halaman' => '3 halaman',
                            'waktu' => '7 hari kerja', 'disk' => '1 GB', 'email' => '2',
                            'subdomain' => 3, 'database' => 3, 'setup' => 'Rp99.000',
                            'highlight' => false,
                        ],
                        [
                            'nama' => 'Basic', 'harga' => '750.000', 'halaman' => '5 halaman',
                            'waktu' => '14 hari kerja', 'disk' => '1 GB', 'email' => '2',
                            'subdomain' => 3, 'database' => 3, 'setup' => 'Rp99.000',
                            'highlight' => false,
                        ],
                        [
                            'nama' => 'Pro', 'harga' => '1.500.000', 'halaman' => '10 halaman',
                            'waktu' => '21 hari kerja', 'disk' => '5 GB', 'email' => '7',
                            'subdomain' => 5, 'database' => 5, 'setup' => 'Rp198.000',
                            'highlight' => true,
                        ],
                        [
                            'nama' => 'Business', 'harga' => '2.250.000', 'halaman' => '15 halaman',
                            'waktu' => '30 hari kerja', 'disk' => '10 GB', 'email' => '20',
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
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Mulai Dari</p>
                        </div>

                        {{-- Harga --}}
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                            <p class="flex items-baseline gap-1">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Rp</span>
                                <span class="text-2xl font-bold text-gray-900 dark:text-gray-50 tracking-tight">{{ $p['harga'] }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">/thn</span>
                            </p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">*Minimal kontrak 1 Tahun</p>
                        </div>

                        {{-- Fitur --}}
                        <ul class="px-6 py-5 space-y-3 text-sm flex-1">
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">Pembuatan Halaman Website: <strong class="font-semibold">{{ $p['halaman'] }}</strong></span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">Waktu Pengerjaan: <strong class="font-semibold">{{ $p['waktu'] }}</strong></span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">Hosting: <strong class="font-semibold">{{ $p['disk'] }}</strong> Disk Space</span>
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
                                <span class="text-gray-700 dark:text-gray-200">Domain <strong class="font-semibold">1</strong> Tahun</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">Sertifikat <strong class="font-semibold">SSL</strong></span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">Logo Image Editing: 5 gambar + 1 logo</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">Header Slideshow</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-200">Pemeliharaan</span>
                            </li>
                        </ul>

                        {{-- CTA --}}
                        <div class="px-6 pb-6">
                            <a
                                href="{{ route('beranda') }}#kontak"
                                class="block w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90"
                                style="background-color: {{ $globalSettings->primary_color_style }}"
                            >
                                Lihat Paket
                            </a>
                            <p class="mt-3 text-center text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-lg py-2">
                                Biaya Setup: {{ $p['setup'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Catatan kaki --}}
            <p class="mt-8 text-xs text-gray-500 dark:text-gray-400 text-center max-w-2xl mx-auto">
                Cocok untuk anda yang sudah memiliki konten (foto/teks). Jika tidak, anda bisa meminta
                jasa pembuatan konten. Harga dapat berubah sewaktu-waktu tanpa pemberitahuan.
            </p>
        </div>
    </section>
</div>
