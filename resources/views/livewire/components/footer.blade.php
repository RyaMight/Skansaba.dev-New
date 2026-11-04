<footer class="bg-gray-50 pt-4 pb-0 px-4 sm:px-6">
    <div class="w-full bg-white rounded-t-3xl rounded-b-none shadow-[0_2px_24px_rgba(15,23,42,0.06)] border border-gray-100 border-b-0">
        <div class="site-container pt-10 lg:pt-14 pb-10 lg:pb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8">
            {{-- Brand & Contact --}}
            <div class="lg:col-span-5" data-reveal>
                <div class="flex items-center gap-2.5 mb-5">
                    @if($globalSettings->logo_primary_url)
                        <img src="{{ $globalSettings->logo_primary_url }}" alt="{{ $globalSettings->site_name }}" class="h-10 w-auto">
                    @else
                        <svg class="w-9 h-9" style="color: {{ $globalSettings->primary_color_style }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25-9 5.25m18 0l-9 5.25m9-5.25v9l-9 5.25m-9-14.25l9 5.25m-9-5.25v9l9 5.25" />
                        </svg>
                    @endif
                    <span class="text-xl font-bold" style="color: {{ $globalSettings->primary_color_style }}">{{ $globalSettings->site_name }}</span>
                </div>

                <p class="text-sm text-gray-600 leading-relaxed mb-6 max-w-sm">
                    {{ $globalSettings->site_tagline ?? 'Rekayasa Perangkat Lunak - Membangun Masa Depan Digital Indonesia' }}
                </p>

                <ul class="space-y-3 text-sm text-gray-600">
                    @if($globalSettings->contact_email)
                        <li class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $globalSettings->primary_color_style }}1a; color: {{ $globalSettings->primary_color_style }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            </span>
                            <a href="mailto:{{ $globalSettings->contact_email }}" class="pt-1.5 hover-primary transition">{{ $globalSettings->contact_email }}</a>
                        </li>
                    @endif
                    @if($globalSettings->contact_phone)
                        <li class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $globalSettings->primary_color_style }}1a; color: {{ $globalSettings->primary_color_style }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            </span>
                            <a href="tel:{{ $globalSettings->contact_phone }}" class="pt-1.5 hover-primary transition">{{ $globalSettings->contact_phone }}</a>
                        </li>
                    @endif
                    @if($globalSettings->address)
                        <li class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $globalSettings->primary_color_style }}1a; color: {{ $globalSettings->primary_color_style }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </span>
                            <span class="pt-1.5 leading-relaxed">{{ $globalSettings->address }}</span>
                        </li>
                    @endif
                </ul>

                {{-- Social Media --}}
                <div class="flex items-center gap-3 mt-6">
                    @if($globalSettings->facebook)
                        <a href="{{ $globalSettings->facebook }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover-primary transition hover:-translate-y-0.5" title="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    @endif
                    @if($globalSettings->instagram)
                        <a href="{{ $globalSettings->instagram }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover-primary transition hover:-translate-y-0.5" title="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    @endif
                    @if($globalSettings->youtube)
                        <a href="{{ $globalSettings->youtube }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover-primary transition hover:-translate-y-0.5" title="YouTube">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    @endif
                    @if($globalSettings->tiktok)
                        <a href="{{ $globalSettings->tiktok }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover-primary transition hover:-translate-y-0.5" title="TikTok">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        </a>
                    @endif
                </div>

                <p class="text-xs text-gray-400 mt-8">
                    Copyright &copy; {{ date('Y') }} {{ $globalSettings->site_name }}. All right reserved.
                </p>
            </div>

            {{-- Menu Columns --}}
            <div class="lg:col-span-4 grid grid-cols-2 gap-8" data-reveal>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 text-[15px]">Menu Utama</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="/" class="text-gray-500 hover-primary transition">Beranda</a></li>
                        <li><a href="/struktur-organisasi" class="text-gray-500 hover-primary transition">Struktur Organisasi</a></li>
                        <li><a href="/fasilitas" class="text-gray-500 hover-primary transition">Fasilitas</a></li>
                        <li><a href="/unit-produksi" class="text-gray-500 hover-primary transition">Unit Produksi</a></li>
                    </ul>

                    <h4 class="font-bold text-gray-900 mb-4 mt-8 text-[15px]">Siswa</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="/prestasi" class="text-gray-500 hover-primary transition">Prestasi</a></li>
                        <li><a href="/karya" class="text-gray-500 hover-primary transition">Karya Siswa</a></li>
                        <li><a href="/alumni" class="text-gray-500 hover-primary transition">Alumni</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 text-[15px]">Informasi</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="/berita" class="text-gray-500 hover-primary transition">Berita</a></li>
                        <li><a href="/dokumen" class="text-gray-500 hover-primary transition">Dokumen</a></li>
                        <li><a href="/gallery" class="text-gray-500 hover-primary transition">Gallery</a></li>
                    </ul>
                </div>
            </div>

            {{-- Map --}}
            <div class="lg:col-span-3" data-reveal>
                <h4 class="font-bold text-gray-900 mb-4 text-[15px]">Lokasi Sekolah</h4>
                @if($globalSettings->maps_url)
                    <div class="rounded-2xl overflow-hidden aspect-[4/3] border border-gray-100 shadow-sm">
                        {!! $globalSettings->maps_url !!}
                    </div>
                @else
                    <div class="rounded-2xl aspect-[4/3] border border-gray-100 bg-gray-50 flex items-center justify-center text-sm text-gray-400">
                        Peta belum tersedia
                    </div>
                @endif
            </div>
        </div>
        </div>
    </div>
</footer>
