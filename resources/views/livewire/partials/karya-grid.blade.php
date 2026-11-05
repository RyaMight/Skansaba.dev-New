{{-- Grid Karya Siswa: menampilkan Karya Siswa Asli (Published di Database) + Katalog Dummy yang dipertahankan --}}
<div class="space-y-6">
    {{-- Filter Kategori --}}
    @if($categories->isNotEmpty())
        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                wire:click="$set('filterKategori', '')"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors cursor-pointer {{ $filterKategori === '' ? 'bg-sky-600 text-white border-sky-600 shadow-xs dark:bg-sky-600 dark:border-sky-600 dark:text-white' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800' }}"
            >
                {{ __('Semua Kategori') }}
            </button>
            @foreach($categories as $cat)
                <button
                    type="button"
                    wire:click="$set('filterKategori', '{{ $cat }}')"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors cursor-pointer {{ $filterKategori === $cat ? 'bg-sky-600 text-white border-sky-600 shadow-xs dark:bg-sky-600 dark:border-sky-600 dark:text-white' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-800 hover:bg-slate-50 dark:hover:bg-gray-800' }}"
                >
                    {{ __($cat) }}
                </button>
            @endforeach

            @if($search !== '' || $filterKategori !== '')
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="ml-auto inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-rose-600 dark:text-rose-400 hover:underline cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>{{ __('Reset Filter') }}</span>
                </button>
            @endif
        </div>
    @endif

    @if($karyas->isEmpty() && $dummyKaryas->isEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-slate-200 dark:border-gray-800 p-12 text-center">
            <div class="w-11 h-11 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-gray-100">{{ __('Tidak ada karya siswa yang cocok') }}</h3>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">{{ __('Coba gunakan kata kunci pencarian atau pilih kategori lain.') }}</p>
            <button
                type="button"
                wire:click="resetFilters"
                class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold bg-slate-900 dark:bg-sky-600 text-white hover:opacity-90 transition cursor-pointer"
            >
                {{ __('Tampilkan Semua Karya') }}
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-left">
            {{-- ================================================================ --}}
            {{-- 1. DATA ASLI: Karya Siswa Terpublikasi dari Database ($karyas) --}}
            {{-- ================================================================ --}}
            @foreach($karyas as $item)
                @php
                    $creatorUser = ($item->user && $item->user->isSiswa())
                        ? $item->user
                        : ($siswaLookup[mb_strtolower(trim($item->nama_siswa ?? ''))] ?? null);
                    $creatorName = $creatorUser?->name ?? $item->nama_siswa ?? 'Siswa RPL';
                    $creatorClass = $item->kelas ?: ($creatorUser?->kelas ?: 'RPL');
                    $creatorInitials = $creatorUser
                        ? $creatorUser->initials()
                        : \Illuminate\Support\Str::of($creatorName)->explode(' ')->take(2)->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('');
                    $techList = is_array($item->teknologi) ? $item->teknologi : [];
                @endphp

                <a
                    href="{{ route('karya.detail', $item->id) }}"
                    class="group h-full bg-white dark:bg-gray-900 rounded-xl border border-slate-200/90 dark:border-gray-800 hover:border-sky-500/70 dark:hover:border-sky-500/60 shadow-xs transition-colors duration-150 overflow-hidden flex flex-col justify-between"
                >
                    <div class="flex-1 flex flex-col">
                        {{-- Cover Area --}}
                        <div class="relative aspect-[16/10] bg-slate-100 dark:bg-gray-950 border-b border-slate-200/80 dark:border-gray-800 overflow-hidden">
                            @if($item->gambar_url)
                                <img
                                    src="{{ $item->gambar_url }}"
                                    alt="{{ $item->judul }}"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
                                    loading="lazy"
                                >
                            @else
                                {{-- Clean Architectural Software Blueprint Cover (No AI-Slop Emoji) --}}
                                <div class="w-full h-full p-4 flex flex-col justify-between bg-slate-100/90 dark:bg-[#111622] text-slate-700 dark:text-slate-200 select-none">
                                    {{-- Top Window Chrome --}}
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                            <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                            <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                        </div>
                                        <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 truncate max-w-[150px]">
                                            {{ \Illuminate\Support\Str::slug($item->judul) }}.app
                                        </span>
                                    </div>

                                    {{-- Center Icon & Project Type --}}
                                    <div class="my-auto flex items-center gap-3 py-1">
                                        <div class="w-10 h-10 rounded-lg bg-white dark:bg-sky-500/15 border border-slate-200/90 dark:border-sky-400/25 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 shadow-xs">
                                            @if(str_contains(strtolower($item->kategori ?? ''), 'mobile'))
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <rect x="6" y="2" width="12" height="20" rx="2"/>
                                                    <line x1="12" y1="18" x2="12.01" y2="18"/>
                                                </svg>
                                            @elseif(str_contains(strtolower($item->kategori ?? ''), 'game'))
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-semibold text-slate-800 dark:text-white truncate">{{ $item->judul }}</div>
                                            <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ $item->kategori ?: 'Rekayasa Perangkat Lunak' }}</div>
                                        </div>
                                    </div>

                                    {{-- Bottom Blueprint Bar --}}
                                    <div class="flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-200 dark:border-slate-800/90">
                                        <span class="inline-flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                            {{ __('Terpublikasi') }}
                                        </span>
                                        <span class="font-mono text-slate-400 dark:text-slate-400">{{ $item->tahun ?: date('Y') }}</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Category Pill Overlay when Image is Present --}}
                            @if($item->gambar_url && $item->kategori)
                                <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-slate-900/85 text-white border border-white/15">
                                    {{ __($item->kategori) }}
                                </span>
                            @endif
                        </div>

                        {{-- Card Body --}}
                        <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <span class="text-[11px] font-semibold text-sky-600 dark:text-sky-400 truncate">
                                        {{ $item->kategori ? __($item->kategori) : __('Proyek RPL') }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 dark:text-gray-500 font-mono shrink-0">
                                        {{ $item->tahun ?: date('Y') }}
                                    </span>
                                </div>

                                <h3 class="text-sm font-bold text-slate-900 dark:text-gray-50 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors line-clamp-1">
                                    {{ $item->judul }}
                                </h3>

                                <p class="text-xs text-slate-500 dark:text-gray-400 mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $item->deskripsi_singkat ?: $item->deskripsi_excerpt }}
                                </p>
                            </div>

                            {{-- Tech Stack Pills --}}
                            @if(count($techList) > 0)
                                <div class="flex flex-wrap gap-1.5 mt-3">
                                    @foreach(array_slice($techList, 0, 3) as $tech)
                                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border border-slate-200/80 dark:border-gray-700 rounded text-[10px] font-medium">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                    @if(count($techList) > 3)
                                        <span class="px-1.5 py-0.5 text-[10px] font-medium text-slate-400 dark:text-gray-500">
                                            +{{ count($techList) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card Footer: Creator & Link --}}
                    <div class="px-4 py-3 border-t border-slate-100 dark:border-gray-800/80 bg-slate-50/50 dark:bg-gray-900/50 flex items-center justify-between gap-2 mt-auto">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-6 h-6 rounded-full overflow-hidden bg-sky-50 dark:bg-sky-500/15 border border-sky-200/70 dark:border-sky-500/30 text-sky-700 dark:text-sky-300 flex items-center justify-center text-[10px] font-bold shrink-0">
                                @if($creatorUser && $creatorUser->photo_url)
                                    <img src="{{ $creatorUser->photo_url }}" alt="{{ $creatorName }}" class="w-full h-full object-cover">
                                @else
                                    {{ $creatorInitials }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-700 dark:text-gray-200 truncate">{{ $creatorName }}</p>
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-600 dark:text-sky-400 shrink-0 group-hover:translate-x-0.5 transition-transform">
                            <span>{{ __('Detail') }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </a>
            @endforeach

            {{-- ================================================================ --}}
            {{-- 2. DATA DUMMY PELENGKAP: Dipertahankan dengan UI/UX Konsisten --}}
            {{-- ================================================================ --}}
            @foreach($dummyKaryas as $karya)
                @php
                    $dummyStudent = $siswaLookup[mb_strtolower(trim($karya['siswa']))] ?? null;
                    $dummyInitials = $dummyStudent
                        ? $dummyStudent->initials()
                        : \Illuminate\Support\Str::of($karya['siswa'])->explode(' ')->take(2)->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('');
                    $cardHref = $dummyStudent ? $dummyStudent->profil_url : '#';
                @endphp

                <a
                    href="{{ $cardHref }}"
                    class="group h-full bg-white dark:bg-gray-900 rounded-xl border border-slate-200/90 dark:border-gray-800 hover:border-slate-300 dark:hover:border-gray-700 shadow-xs transition-colors duration-150 overflow-hidden flex flex-col justify-between"
                >
                    <div class="flex-1 flex flex-col">
                        {{-- Clean Blueprint Cover (Tanpa Emoji AI-Slop) --}}
                        <div class="relative aspect-[16/10] bg-slate-100/90 dark:bg-[#141926] border-b border-slate-200/80 dark:border-gray-800 p-4 flex flex-col justify-between text-slate-700 dark:text-slate-200 select-none">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                    <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                    <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                </div>
                                <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 truncate max-w-[150px]">
                                    {{ \Illuminate\Support\Str::slug($karya['judul']) }}.dev
                                </span>
                            </div>

                            <div class="my-auto flex items-center gap-3 py-1">
                                <div class="w-10 h-10 rounded-lg bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 shadow-xs">
                                    @switch($karya['icon'] ?? 'code')
                                        @case('book')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            @break
                                        @case('receipt')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                            </svg>
                                            @break
                                        @case('gamepad')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            @break
                                        @case('building')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            @break
                                        @case('cpu')
                                        @case('sensor')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                            </svg>
                                            @break
                                        @case('sparkles')
                                        @case('leaf')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            @break
                                        @case('qrcode')
                                        @case('phone')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <rect x="6" y="2" width="12" height="20" rx="2"/>
                                                <line x1="12" y1="18" x2="12.01" y2="18"/>
                                            </svg>
                                            @break
                                        @case('cart')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                            @break
                                        @default
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                            </svg>
                                    @endswitch
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold text-slate-800 dark:text-white truncate">{{ $karya['judul'] }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ __($karya['kategori']) }}</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-200 dark:border-slate-800/90">
                                <span class="inline-flex items-center gap-1.5 text-slate-600 dark:text-slate-300 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500 dark:bg-sky-400"></span>
                                    {{ __('Katalog Portofolio') }}
                                </span>
                                <span class="font-mono text-slate-400 dark:text-slate-400">{{ $karya['tahun'] ?? 2026 }}</span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <span class="text-[11px] font-semibold text-slate-600 dark:text-gray-300 truncate">
                                        {{ __($karya['kategori']) }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 dark:text-gray-500 font-mono shrink-0">
                                        {{ $karya['tahun'] ?? 2026 }}
                                    </span>
                                </div>

                                <h3 class="text-sm font-bold text-slate-900 dark:text-gray-50 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors line-clamp-1">
                                    {{ $karya['judul'] }}
                                </h3>

                                <p class="text-xs text-slate-500 dark:text-gray-400 mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $karya['deskripsi'] }}
                                </p>
                            </div>

                            @if(!empty($karya['teknologi']))
                                <div class="flex flex-wrap gap-1.5 mt-3">
                                    @foreach(array_slice($karya['teknologi'], 0, 3) as $tech)
                                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border border-slate-200/80 dark:border-gray-700 rounded text-[10px] font-medium">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="px-4 py-3 border-t border-slate-100 dark:border-gray-800/80 bg-slate-50/50 dark:bg-gray-900/50 flex items-center justify-between gap-2 mt-auto">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-6 h-6 rounded-full overflow-hidden bg-slate-200/80 dark:bg-gray-800 border border-slate-300/70 dark:border-gray-700 text-slate-700 dark:text-gray-300 flex items-center justify-center text-[10px] font-bold shrink-0">
                                @if($dummyStudent && $dummyStudent->photo_url)
                                    <img src="{{ $dummyStudent->photo_url }}" alt="{{ $karya['siswa'] }}" class="w-full h-full object-cover">
                                @else
                                    {{ $dummyInitials }}
                                @endif
                            </div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-gray-200 truncate">{{ $karya['siswa'] }}</p>
                        </div>

                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-500 dark:text-gray-400 group-hover:text-sky-600 dark:group-hover:text-sky-400 shrink-0 transition-colors">
                            <span>{{ __('Profil') }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
