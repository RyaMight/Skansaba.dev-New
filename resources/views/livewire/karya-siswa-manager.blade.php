<div class="min-h-screen bg-slate-50 dark:bg-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-gray-50 tracking-tight">
                    {{ auth()->user()->role === 'siswa' ? __('Karya Saya') : __('Koleksi Karya Siswa') }}
                </h1>
                <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">
                    {{ __('Portofolio proyek software, desain, dan aplikasi yang dibuat oleh siswa RPL.') }}
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                @if(auth()->user()->role === 'siswa')
                    <button 
                        type="button" 
                        wire:click="openCreateModal"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-white bg-sky-600 hover:bg-sky-700 active:bg-sky-800 rounded-xl transition shadow-xs cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>{{ __('Buat Karya') }}</span>
                    </button>
                @endif
                @if(in_array(auth()->user()->role, ['admin', 'guru']))
                    <a 
                        href="{{ route('karya-siswa.verifikasi') }}" 
                        wire:navigate
                        class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-gray-200 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-700 rounded-lg hover:bg-slate-50 dark:hover:bg-gray-800 transition shadow-xs"
                    >
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ __('Verifikasi Pengajuan') }}</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session()->has('message'))
            <div class="mb-6 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="mb-6 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-800 dark:text-rose-300 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Quick Stats Pills (Clickable to Filter) --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <button 
                type="button" 
                wire:click="setStatusFilter('')"
                @class([
                    'p-3.5 rounded-xl border text-left transition cursor-pointer',
                    'bg-white dark:bg-gray-900 border-slate-300 dark:border-gray-600 ring-2 ring-slate-400 dark:ring-gray-500 shadow-xs' => $filterStatus === '',
                    'bg-white dark:bg-gray-900 border-slate-200/90 dark:border-gray-800 hover:border-slate-300 dark:hover:border-gray-700' => $filterStatus !== '',
                ])
            >
                <span class="text-[11px] font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider block">{{ __('Semua Karya') }}</span>
                <span class="text-xl font-bold text-slate-900 dark:text-gray-50 mt-0.5 block">{{ $stats['total'] }}</span>
            </button>

            <button 
                type="button" 
                wire:click="setStatusFilter('published')"
                @class([
                    'p-3.5 rounded-xl border text-left transition cursor-pointer',
                    'bg-emerald-50/70 dark:bg-emerald-500/10 border-emerald-300 dark:border-emerald-500/40 ring-2 ring-emerald-500 shadow-xs' => $filterStatus === 'published',
                    'bg-white dark:bg-gray-900 border-slate-200/90 dark:border-gray-800 hover:border-emerald-200 dark:hover:border-emerald-500/30' => $filterStatus !== 'published',
                ])
            >
                <span class="text-[11px] font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider block">{{ __('Terpublikasi') }}</span>
                <span class="text-xl font-bold text-emerald-900 dark:text-emerald-300 mt-0.5 block">{{ $stats['published'] }}</span>
            </button>

            <button 
                type="button" 
                wire:click="setStatusFilter('review')"
                @class([
                    'p-3.5 rounded-xl border text-left transition cursor-pointer',
                    'bg-amber-50/70 dark:bg-amber-500/10 border-amber-300 dark:border-amber-500/40 ring-2 ring-amber-500 shadow-xs' => $filterStatus === 'review',
                    'bg-white dark:bg-gray-900 border-slate-200/90 dark:border-gray-800 hover:border-amber-200 dark:hover:border-amber-500/30' => $filterStatus !== 'review',
                ])
            >
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-amber-700 dark:text-amber-400 uppercase tracking-wider block">{{ __('Butuh Review') }}</span>
                    @if($stats['review'] > 0)
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                    @endif
                </div>
                <span class="text-xl font-bold text-amber-900 dark:text-amber-300 mt-0.5 block">{{ $stats['review'] }}</span>
            </button>

            <button 
                type="button" 
                wire:click="setStatusFilter('draft')"
                @class([
                    'p-3.5 rounded-xl border text-left transition cursor-pointer',
                    'bg-slate-100 dark:bg-gray-800 border-slate-300 dark:border-gray-600 ring-2 ring-slate-500 shadow-xs' => $filterStatus === 'draft',
                    'bg-white dark:bg-gray-900 border-slate-200/90 dark:border-gray-800 hover:border-slate-300 dark:hover:border-gray-700' => $filterStatus !== 'draft',
                ])
            >
                <span class="text-[11px] font-semibold text-slate-600 dark:text-gray-400 uppercase tracking-wider block">{{ __('Draft') }}</span>
                <span class="text-xl font-bold text-slate-800 dark:text-gray-100 mt-0.5 block">{{ $stats['draft'] }}</span>
            </button>
        </div>


        {{-- Toolbar: Pencarian & Filter --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 p-4 mb-6 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                {{-- Search Input --}}
                <div class="relative sm:col-span-2 lg:col-span-2">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="{{ __('Cari judul proyek, nama siswa, atau kelas...') }}" 
                        class="w-full pl-9 pr-3 py-2 text-xs border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                {{-- Filter Kategori --}}
                <div>
                    <select wire:model.live="filterKategori" class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="">{{ __('Semua Kategori') }}</option>
                        <option value="web">Web Development</option>
                        <option value="mobile">Mobile Apps</option>
                        <option value="desktop">Desktop Apps</option>
                        <option value="game">Game Development</option>
                        <option value="iot">IoT & Embedded</option>
                        <option value="ui-ux">UI/UX Design</option>
                        <option value="data">Data Science</option>
                        <option value="jaringan">{{ __('Jaringan') }}</option>
                        <option value="robotika">{{ __('Robotika') }}</option>
                        <option value="ai">AI / Machine Learning</option>
                        <option value="lainnya">{{ __('Lainnya') }}</option>
                    </select>
                </div>

                {{-- Filter Tahun --}}
                <div>
                    <select wire:model.live="filterTahun" class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="">{{ __('Semua Tahun') }}</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Grid Kartu Portofolio Karya Siswa --}}
        @if($karyaSiswas->isEmpty())
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 p-16 text-center shadow-xs">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-slate-100 dark:bg-gray-800 text-slate-400 mb-4">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800 dark:text-gray-100">{{ __('Tidak ada karya yang ditemukan') }}</h3>
                <p class="text-xs text-slate-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                    {{ auth()->user()->role === 'siswa' ? __('Kamu belum memiliki karya atau tidak sesuai filter. Buat karya pertamamu sekarang!') : __('Coba sesuaikan kata kunci pencarian atau ubah filter kategori dan status.') }}
                </p>
                @if(auth()->user()->role === 'siswa')
                    <div class="mt-4">
                        <button 
                            type="button" 
                            wire:click="openCreateModal"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-xl transition shadow-xs cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>{{ __('Buat Karya Sekarang') }}</span>
                        </button>
                    </div>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($karyaSiswas as $karya)
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/90 dark:border-gray-800 shadow-2xs overflow-hidden flex flex-col justify-between hover:border-slate-300 dark:hover:border-gray-700 transition group h-full">
                        <div class="flex-1 flex flex-col justify-between">
                            {{-- Cover Thumbnail --}}
                            <div class="aspect-video bg-slate-100 dark:bg-gray-800 relative overflow-hidden border-b border-slate-100 dark:border-gray-800">
                                @if($karya->gambar_url)
                                    <img src="{{ $karya->gambar_url }}" alt="{{ $karya->judul }}" class="w-full h-full object-cover group-hover:scale-102 transition duration-300">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 dark:text-gray-600">
                                        <svg class="w-10 h-10 stroke-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-[10px] text-slate-400 dark:text-gray-500 mt-1">{{ __('Tanpa cover') }}</span>
                                    </div>
                                @endif

                                {{-- Badges on Cover --}}
                                <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between pointer-events-none">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shadow-2xs backdrop-blur-xs
                                        @if($karya->status === 'published') bg-emerald-600/90 text-white
                                        @elseif($karya->status === 'review') bg-amber-500/95 text-white
                                        @else bg-slate-800/80 text-white @endif"
                                    >
                                        @if($karya->status === 'published') {{ __('Terpublikasi') }}
                                        @elseif($karya->status === 'review') {{ __('Butuh Review') }}
                                        @else {{ __('Draft') }} @endif
                                    </span>

                                    @if($karya->kategori)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/95 dark:bg-gray-900/90 text-slate-700 dark:text-gray-200 shadow-2xs border border-slate-200/60 dark:border-gray-700">
                                            {{ ucfirst($karya->kategori) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4 sm:p-5">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-gray-50 leading-snug line-clamp-1 group-hover:text-sky-600 transition" title="{{ $karya->judul }}">
                                    {{ $karya->judul }}
                                </h3>

                                <div class="flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-gray-400 mt-1">
                                    <span class="font-medium text-slate-700 dark:text-gray-200">{{ $karya->nama_siswa }}</span>
                                    <span>•</span>
                                    <span>{{ $karya->kelas ?: 'RPL' }}</span>
                                    @if($karya->tahun)
                                        <span>•</span>
                                        <span>{{ $karya->tahun }}</span>
                                    @endif
                                </div>

                                @if($karya->deskripsi_singkat)
                                    <p class="text-xs text-slate-600 dark:text-gray-300 mt-2 line-clamp-2 leading-relaxed">
                                        {{ $karya->deskripsi_singkat }}
                                    </p>
                                @endif

                                {{-- Tag Keahlian --}}
                                @if(!empty($karya->tags) && is_array($karya->tags))
                                    <div class="flex flex-wrap gap-1 mt-3">
                                        @foreach(array_slice($karya->tags, 0, 2) as $tag)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200/60 dark:border-gray-700">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Tautan Demo & Repo --}}
                                @if($karya->url_demo || $karya->url_repo)
                                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100 dark:border-gray-800 text-[11px]">
                                        @if($karya->url_demo)
                                            <a href="{{ $karya->url_demo }}" target="_blank" class="inline-flex items-center gap-1 text-sky-600 dark:text-sky-400 hover:text-sky-800 font-medium">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                {{ __('Demo') }}
                                            </a>
                                        @endif
                                        @if($karya->url_repo)
                                            <a href="{{ $karya->url_repo }}" target="_blank" class="inline-flex items-center gap-1 text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-gray-200 font-medium ml-auto">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                                {{ __('Repository') }}
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                {{-- Penjelasan Status Karya --}}
                                <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-gray-800">
                                    @if($karya->status === 'published')
                                        <div class="flex items-start gap-2 p-2 rounded-lg bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-200/60 dark:border-emerald-500/20">
                                            <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div class="text-[11px] leading-snug">
                                                <span class="font-bold text-emerald-900 dark:text-emerald-300 block">{{ __('Sudah Terpublikasi') }}</span>
                                                <span class="text-emerald-700 dark:text-emerald-400 block">{{ __('Karya sudah disetujui & tampil di website publik.') }}</span>
                                            </div>
                                        </div>
                                    @elseif($karya->status === 'review')
                                        <div class="flex items-start gap-2 p-2 rounded-lg bg-amber-50/90 dark:bg-amber-500/10 border border-amber-200/70 dark:border-amber-500/20">
                                            <span class="relative flex h-2 w-2 shrink-0 mt-1.5 ml-0.5 mr-0.5">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                            </span>
                                            <div class="text-[11px] leading-snug">
                                                <span class="font-bold text-amber-900 dark:text-amber-300 block">{{ __('Butuh Verifikasi') }}</span>
                                                <span class="text-amber-800 dark:text-amber-400 block">
                                                    @if(auth()->user()->role === 'siswa')
                                                        {{ __('Karya sudah diajukan & terkunci sementara menunggu proses verifikasi guru/admin.') }}
                                                    @else
                                                        {{ __('Menunggu kurasi admin di menu Verifikasi.') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-start gap-2 p-2 rounded-lg bg-slate-50 dark:bg-gray-800 border border-slate-200/80 dark:border-gray-700">
                                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <div class="text-[11px] leading-snug">
                                                <span class="font-bold text-slate-800 dark:text-gray-200 block">{{ __('Draft Siswa') }}</span>
                                                <span class="text-slate-500 dark:text-gray-400 block">{{ __('Belum diajukan oleh siswa untuk ditinjau.') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer: Aksi Karya Siswa --}}
                        <div class="px-4 sm:px-5 py-3.5 bg-slate-50 dark:bg-gray-800/80 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between gap-3 mt-auto">
                            @if(auth()->user()->role === 'siswa')
                                @if($karya->status === 'draft')
                                    <div class="flex items-center gap-2 shrink-0">
                                        <button 
                                            type="button" 
                                            wire:click="submitDraft({{ $karya->id }})" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-sky-600 hover:bg-sky-700 active:bg-sky-800 rounded-lg transition shadow-2xs cursor-pointer shrink-0"
                                            title="{{ __('Ajukan karya ini untuk diverifikasi guru/admin') }}"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>{{ __('Ajukan Verifikasi') }}</span>
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="openEditModal({{ $karya->id }})" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-slate-700 dark:text-gray-200 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-700 transition shadow-2xs cursor-pointer shrink-0"
                                            title="{{ __('Edit draf karya') }}"
                                        >
                                            <svg class="w-3 h-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>{{ __('Edit') }}</span>
                                        </button>
                                    </div>
                                @elseif($karya->status === 'review')
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-medium text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-500/10 border border-amber-200/80 dark:border-amber-500/20 rounded-lg shrink-0" title="{{ __('Karya sedang dalam proses verifikasi guru/admin dan terkunci dari perubahan atau penghapusan.') }}">
                                        <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <span>{{ __('Terkunci (Sedang Ditinjau)') }}</span>
                                    </div>
                                @else
                                    <a 
                                        href="{{ route('karya.detail', $karya->id) }}" 
                                        target="_blank"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-500/10 border border-sky-200/80 dark:border-sky-500/20 rounded-lg hover:bg-sky-100 dark:hover:bg-sky-500/20 transition cursor-pointer shrink-0"
                                    >
                                        <span>{{ __('Lihat Publik') }}</span>
                                        <span class="text-[10px]">&nearr;</span>
                                    </a>
                                @endif

                                <div class="flex items-center gap-1.5 ml-auto shrink-0">
                                    <button 
                                        type="button" 
                                        wire:click="openDetailModal({{ $karya->id }})" 
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-gray-200 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-white transition shadow-2xs cursor-pointer shrink-0"
                                    >
                                        <svg class="w-3.5 h-3.5 text-slate-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>{{ __('Detail') }}</span>
                                    </button>

                                    @if($karya->status !== 'review')
                                        <button 
                                            type="button" 
                                            wire:click="confirmDelete({{ $karya->id }})" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-rose-700 dark:text-rose-400 bg-rose-50/70 dark:bg-rose-500/10 border border-rose-200/80 dark:border-rose-500/20 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-500/20 hover:text-rose-800 transition cursor-pointer shrink-0"
                                            title="{{ __('Hapus karya ini') }}"
                                        >
                                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>{{ __('Hapus') }}</span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <button 
                                    type="button" 
                                    wire:click="openDetailModal({{ $karya->id }})" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-gray-200 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-700 hover:text-slate-900 dark:hover:text-white transition shadow-2xs cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5 text-slate-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>{{ __('Lihat Detail') }}</span>
                                </button>

                                <button 
                                    type="button" 
                                    wire:click="confirmDelete({{ $karya->id }})" 
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-rose-700 dark:text-rose-400 bg-rose-50/70 dark:bg-rose-500/10 border border-rose-200/80 dark:border-rose-500/20 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-500/20 hover:text-rose-800 transition cursor-pointer"
                                    title="{{ __('Hapus karya ini') }}"
                                >
                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>{{ __('Hapus') }}</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            @if($karyaSiswas->hasPages())
                <div class="mt-6 p-4 bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 shadow-xs">
                    {{ $karyaSiswas->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- MODAL DETAIL KARYA SISWA --}}
    @if($showDetailModal && $detailKarya)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-hidden">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity" wire:click="closeDetailModal"></div>

            <div 
                wire:key="modal-detail-karya-dialog"
                x-data 
                x-init="$el.scrollTop = 0" 
                @scroll.self="$el.scrollTop = 0"
                class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-3xl w-full h-[90vh] max-h-[90vh] flex flex-col border border-slate-200 dark:border-gray-800 z-10 overflow-hidden"
            >
                {{-- Modal Header --}}
                <div wire:key="modal-detail-karya-header" class="shrink-0 flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-gray-800 bg-slate-50 dark:bg-gray-800/80">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                @if($detailKarya->status === 'published') bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300
                                @elseif($detailKarya->status === 'review') bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300
                                @else bg-slate-200 dark:bg-gray-700 text-slate-700 dark:text-gray-200 @endif"
                            >
                                @if($detailKarya->status === 'published') {{ __('Terpublikasi') }}
                                @elseif($detailKarya->status === 'review') {{ __('Butuh Review') }}
                                @else {{ __('Draft') }} @endif
                            </span>
                            @if($detailKarya->kategori)
                                <span class="text-xs text-slate-500 dark:text-gray-400">
                                    {{ ucfirst($detailKarya->kategori) }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-gray-50 truncate max-w-lg">{{ $detailKarya->judul }}</h3>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closeDetailModal" 
                        class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-gray-200 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-700 transition"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body (Scrollable) --}}
                <div wire:key="modal-detail-karya-body" class="flex-1 min-h-0 overflow-y-auto p-6 space-y-6 text-xs text-slate-700 dark:text-gray-200">
                    {{-- Status Penjelasan Karya Banner --}}
                    @if($detailKarya->status === 'published')
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl flex items-start gap-3">
                            <div class="h-8 w-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/30 flex items-center justify-center shrink-0 text-emerald-700 dark:text-emerald-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-emerald-950 dark:text-emerald-200 text-sm">{{ __('Status: Karya Sudah Terpublikasi (Live)') }}</h4>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-200 dark:bg-emerald-500/30 text-emerald-900 dark:text-emerald-200 uppercase">{{ __('Aktif') }}</span>
                                </div>
                                <p class="text-xs text-emerald-800 dark:text-emerald-300 mt-1 leading-relaxed">
                                    {{ __('Karya ini telah diverifikasi dan disetujui kurator. Karya aktif ditampilkan di etalase web publik dan halaman profil siswa.') }}
                                    @if($detailKarya->published_at)
                                        ({{ __('Dipublikasikan') }}: {{ $detailKarya->published_at->translatedFormat('d M Y') }})
                                    @endif
                                </p>
                            </div>
                        </div>
                    @elseif($detailKarya->status === 'review')
                        <div class="p-4 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="h-8 w-8 rounded-lg bg-amber-100 dark:bg-amber-500/20 border border-amber-200 dark:border-amber-500/30 flex items-center justify-center shrink-0 text-amber-700 dark:text-amber-400">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-amber-950 dark:text-amber-200 text-sm">{{ __('Status: Butuh Verifikasi (Dalam Review)') }}</h4>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-200 dark:bg-amber-500/30 text-amber-950 dark:text-amber-200 uppercase">{{ __('Menunggu Persetujuan') }}</span>
                                    </div>
                                    <p class="text-xs text-amber-800 dark:text-amber-300 mt-1 leading-relaxed">
                                        @if(auth()->user()->role === 'siswa')
                                            {{ __('Karya telah Anda ajukan dan sedang dalam proses verifikasi oleh tim kurator sekolah. Selama tahap peninjauan, karya tidak dapat diedit atau dihapus.') }}
                                        @else
                                            {{ __('Karya telah diajukan siswa dan sedang menunggu verifikasi dari Admin/Guru. Karya belum ditampilkan di etalase publik hingga disetujui.') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if(in_array(auth()->user()->role, ['admin', 'guru']))
                                <a 
                                    href="{{ route('karya-siswa.verifikasi') }}" 
                                    wire:navigate
                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-semibold shrink-0 transition shadow-2xs"
                                >
                                    <span>{{ __('Verifikasi Sekarang') }}</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="p-4 bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="h-8 w-8 rounded-lg bg-slate-200 dark:bg-gray-700 flex items-center justify-center shrink-0 text-slate-600 dark:text-gray-300">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-slate-900 dark:text-gray-100 text-sm">{{ __('Status: Draft Siswa') }}</h4>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 dark:bg-gray-700 text-slate-700 dark:text-gray-300 uppercase">{{ __('Belum Diajukan') }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-gray-400 mt-1 leading-relaxed">
                                        {{ __('Karya ini masih berupa draf yang tersimpan di akun Anda dan belum diajukan untuk ditinjau oleh tim kurator sekolah.') }}
                                    </p>
                                </div>
                            </div>
                            @if(auth()->user()->role === 'siswa' && $detailKarya->user_id === auth()->id())
                                <button
                                    type="button"
                                    wire:click="submitDraft({{ $detailKarya->id }})"
                                    class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white rounded-lg text-xs font-semibold shrink-0 transition shadow-2xs cursor-pointer"
                                    title="{{ __('Kirim karya ini sekarang untuk diverifikasi') }}"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ __('Ajukan Verifikasi Sekarang') }}</span>
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- Cover Banner --}}
                    @if($detailKarya->gambar_url)
                        <div class="aspect-video w-full rounded-xl overflow-hidden border border-slate-200 dark:border-gray-700 bg-slate-100 dark:bg-gray-800">
                            <img src="{{ $detailKarya->gambar_url }}" alt="{{ $detailKarya->judul }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    {{-- Info Siswa & Proyek --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 bg-slate-50 dark:bg-gray-800/80 rounded-xl border border-slate-200/80 dark:border-gray-700">
                        <div>
                            <span class="text-slate-400 block mb-0.5">{{ __('Siswa Pembuat') }}</span>
                            <span class="font-bold text-slate-900 dark:text-gray-50">{{ $detailKarya->nama_siswa }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">{{ __('Kelas') }}</span>
                            <span class="font-semibold text-slate-800 dark:text-gray-200">{{ $detailKarya->kelas ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">{{ __('Tahun') }}</span>
                            <span class="font-semibold text-slate-800 dark:text-gray-200">{{ $detailKarya->tahun ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">{{ __('Dipublikasikan') }}</span>
                            <span class="font-semibold text-slate-800 dark:text-gray-200">{{ $detailKarya->published_at ? $detailKarya->published_at->translatedFormat('d M Y') : __('Belum') }}</span>
                        </div>
                    </div>

                    {{-- Demo & Repository Links --}}
                    @if($detailKarya->url_demo || $detailKarya->url_repo)
                        <div class="flex flex-wrap gap-2.5">
                            @if($detailKarya->url_demo)
                                <a href="{{ $detailKarya->url_demo }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-200 dark:border-sky-500/20 rounded-lg font-semibold hover:bg-sky-100 dark:hover:bg-sky-500/20 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    <span>{{ __('Buka Live Demo') }}</span>
                                    <span class="text-xs">&nearr;</span>
                                </a>
                            @endif
                            @if($detailKarya->url_repo)
                                <a href="{{ $detailKarya->url_repo }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-200 border border-slate-200 dark:border-gray-700 rounded-lg font-semibold hover:bg-slate-200 dark:hover:bg-gray-700 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                    <span>{{ __('Repositori Kode') }}</span>
                                    <span class="text-xs">&nearr;</span>
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Pesan Admin / Catatan Revisi jika ada --}}
                    @if($detailKarya->catatan_admin)
                        <div class="p-3.5 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl">
                            <p class="font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider text-[10px] mb-1">{{ __('Catatan Kurasi Admin') }}</p>
                            <p class="text-amber-900 dark:text-amber-200 leading-relaxed">{{ $detailKarya->catatan_admin }}</p>
                        </div>
                    @endif

                    {{-- Deskripsi Konten (Rendered Markdown) --}}
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-gray-100 uppercase tracking-wider mb-2">{{ __('Deskripsi Lengkap') }}</h4>
                        <div class="prose prose-xs dark:prose-invert max-w-none p-4 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800/50">
                            {!! $detailKarya->deskripsi_html !!}
                        </div>
                    </div>

                    {{-- Screenshots Gallery --}}
                    @if(count($detailKarya->screenshots ?? []) > 0)
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-gray-100 uppercase tracking-wider mb-2">{{ __('Tangkapan Layar') }} ({{ count($detailKarya->screenshots) }})</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                @foreach($detailKarya->screenshots as $path)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($path) }}" target="_blank" class="aspect-video rounded-lg overflow-hidden border border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 hover:opacity-90">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($path) }}" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Bukti Validasi --}}
                    @if(count($detailKarya->bukti ?? []) > 0)
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-gray-100 uppercase tracking-wider mb-2">{{ __('Berkas Bukti') }}</h4>
                            <div class="space-y-1.5">
                                @foreach($detailKarya->bukti as $b)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($b) }}" target="_blank" class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-700">
                                        <span class="truncate">{{ basename($b) }}</span>
                                        <span class="text-sky-600 dark:text-sky-400 font-semibold shrink-0 ml-2">{{ __('Buka Berkas ↗') }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Modal Footer --}}
                <div wire:key="modal-detail-karya-footer" class="shrink-0 mt-auto px-6 py-3.5 border-t border-slate-200 dark:border-gray-800 bg-slate-50 dark:bg-gray-800/80 flex flex-wrap items-center justify-between gap-2">
                    @if(auth()->user()->role !== 'siswa' || $detailKarya->status !== 'review')
                        <button 
                            type="button" 
                            wire:click="confirmDelete({{ $detailKarya->id }})" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-700 dark:text-rose-400 bg-white dark:bg-gray-900 border border-rose-200 dark:border-rose-500/30 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-500/10 transition cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>{{ __('Hapus Karya Ini') }}</span>
                        </button>
                    @else
                        <div class="inline-flex items-center gap-1.5 text-xs text-amber-700 dark:text-amber-400 font-medium">
                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>{{ __('Terkunci dari perubahan selama proses verifikasi') }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        @if(auth()->user()->role === 'siswa' && $detailKarya->user_id === auth()->id() && $detailKarya->status === 'draft')
                            <button
                                type="button"
                                wire:click="openEditModal({{ $detailKarya->id }})"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-gray-200 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-700 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-700 transition shadow-2xs cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>{{ __('Edit Karya') }}</span>
                            </button>

                            <button
                                type="button"
                                wire:click="submitDraft({{ $detailKarya->id }})"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white rounded-lg text-xs font-semibold transition shadow-2xs cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ __('Ajukan Verifikasi') }}</span>
                            </button>
                        @endif

                        <button 
                            type="button" 
                            wire:click="closeDetailModal" 
                            class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-gray-200 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-700 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-700 transition shadow-2xs cursor-pointer"
                        >
                            {{ __('Tutup') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL KONFIRMASI HAPUS CUSTOM (TENGAH LAYAR) --}}
    @if($confirmingDeleteId)
        @php
            $karyaToDelete = \App\Models\KaryaSiswa::find($confirmingDeleteId);
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity" wire:click="cancelDelete"></div>

            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-xl max-w-md w-full p-6 border border-slate-200 dark:border-gray-800 z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-10 w-10 rounded-full bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 flex items-center justify-center flex-shrink-0 text-rose-600 dark:text-rose-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-gray-50">{{ __('Konfirmasi Hapus Karya') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-gray-400">{{ __('Tindakan ini akan menghapus data karya siswa') }}</p>
                    </div>
                </div>

                <p class="text-xs text-slate-600 dark:text-gray-300 mb-4 leading-relaxed">
                    {!! __('Apakah Anda yakin ingin menghapus karya :judul milik siswa :siswa?', [
                        'judul' => '<strong class="text-slate-900 dark:text-gray-100">"' . e($karyaToDelete?->judul) . '"</strong>',
                        'siswa' => '<strong class="text-slate-900 dark:text-gray-100">' . e($karyaToDelete?->nama_siswa) . '</strong>'
                    ]) !!}
                </p>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-gray-800">
                    <button 
                        type="button" 
                        wire:click="cancelDelete" 
                        class="px-4 py-2 border border-slate-300 dark:border-gray-700 rounded-lg text-xs font-semibold text-slate-700 dark:text-gray-200 hover:bg-slate-50 dark:hover:bg-gray-800 transition cursor-pointer"
                    >
                        {{ __('Batal') }}
                    </button>
                    <button
                        type="button"
                        wire:click="delete"
                        class="px-5 py-2 bg-rose-600 text-white rounded-lg text-xs font-semibold hover:bg-rose-700 transition shadow-xs cursor-pointer"
                    >
                        {{ __('Ya, Hapus Karya') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL BUAT KARYA SISWA --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-hidden">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity" wire:click="closeCreateModal"></div>

            <div 
                wire:key="modal-buat-karya-dialog"
                x-data 
                x-init="$el.scrollTop = 0" 
                @scroll.self="$el.scrollTop = 0"
                class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-3xl w-full h-[90vh] max-h-[90vh] flex flex-col border border-slate-200 dark:border-gray-800 z-10 overflow-hidden"
            >
                {{-- Modal Header --}}
                <div wire:key="modal-buat-karya-header" class="shrink-0 flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-gray-800 bg-slate-50 dark:bg-gray-800/80">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-gray-50">
                            {{ $editingKaryaId ? __('Edit Karya Siswa') : __('Buat Karya Baru') }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-gray-400">
                            {{ $editingKaryaId ? __('Perbarui informasi portofolio dan berkas karya Anda') : __('Unggah portofolio proyek perangkat lunak atau desain Anda') }}
                        </p>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closeCreateModal" 
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-700 transition cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body Form --}}
                <div wire:key="modal-buat-karya-body" class="flex-1 min-h-0 p-6 overflow-y-auto space-y-5">
                    {{-- Judul Karya --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-gray-200 uppercase tracking-wider mb-1.5">
                            {{ __('Judul Karya / Proyek') }} <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="judul" 
                            placeholder="{{ __('Contoh: Skansaba Pay - Sistem Pembayaran Kantin Digital') }}" 
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        >
                        @error('judul') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kategori & Kategori Lainnya --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-gray-200 uppercase tracking-wider mb-1.5">
                                {{ __('Kategori') }} <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                wire:model.live="kategori" 
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white"
                            >
                                <option value="web">Web Development</option>
                                <option value="mobile">Mobile Apps</option>
                                <option value="desktop">Desktop Apps</option>
                                <option value="game">Game Development</option>
                                <option value="iot">IoT & Embedded</option>
                                <option value="ui-ux">UI/UX Design</option>
                                <option value="data">Data Science</option>
                                <option value="jaringan">{{ __('Jaringan') }}</option>
                                <option value="robotika">{{ __('Robotika') }}</option>
                                <option value="ai">AI / Machine Learning</option>
                                <option value="lainnya">{{ __('Lainnya (Tulis Sendiri)') }}</option>
                            </select>
                            @error('kategori') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if($kategori === 'lainnya')
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-gray-200 uppercase tracking-wider mb-1.5">
                                    {{ __('Nama Kategori Lainnya') }} <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="kategori_lainnya" 
                                    placeholder="{{ __('Contoh: Cybersecurity, Blockchain') }}" 
                                    class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none"
                                >
                                @error('kategori_lainnya') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        @else
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-gray-200 uppercase tracking-wider mb-1.5">
                                    {{ __('Teknologi / Tech Stack') }}
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="teknologi" 
                                    placeholder="{{ __('Pisahkan koma: Laravel, Tailwind CSS, Vue') }}" 
                                    class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none"
                                >
                            </div>
                        @endif
                    </div>

                    {{-- Deskripsi Singkat --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-gray-200 uppercase tracking-wider mb-1.5">
                            {{ __('Deskripsi Singkat (Ringkasan)') }}
                        </label>
                        <input 
                            type="text" 
                            wire:model="deskripsi_singkat" 
                            placeholder="{{ __('Ringkasan 1-2 kalimat yang tampil di kartu portofolio...') }}" 
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        >
                        @error('deskripsi_singkat') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Deskripsi Lengkap --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-gray-200 uppercase tracking-wider mb-1.5">
                            {{ __('Deskripsi Lengkap Proyek') }} <span class="text-rose-500">*</span>
                        </label>
                        <textarea 
                            wire:model="deskripsi" 
                            rows="5" 
                            placeholder="{{ __('Ceritakan latar belakang masalah, fitur-fitur utama, alur kerja sistem, dan peran kamu dalam pembuatan karya ini...') }}" 
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        ></textarea>
                        @error('deskripsi') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- SEKSI BERKAS & LAMPIRAN KARYA (3 LANGKAH UNGGAHAN RESMI) --}}
                    <div class="pt-3 border-t border-slate-200 dark:border-gray-800 space-y-5">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-gray-100 uppercase tracking-wider">
                                {{ __('Berkas & Lampiran Karya') }}
                            </h4>
                            <p class="text-[11px] text-slate-500 dark:text-gray-400 mt-0.5">
                                {{ __('Unggah berkas portofolio Anda secara terstruktur sesuai 3 tahapan berkas di bawah ini:') }}
                            </p>
                        </div>

                        {{-- 1. COVER / THUMBNAIL UTAMA --}}
                        <div wire:key="cover-section-container" class="p-4 rounded-2xl border border-slate-200 dark:border-gray-800 bg-slate-50/60 dark:bg-gray-800/40 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 dark:text-gray-200">
                                        {{ __('1. Cover / Thumbnail Utama Proyek') }} <span class="text-rose-500">*</span> <span class="text-slate-400 font-normal">({{ __('Disarankan Rasio 16:9') }})</span>
                                    </label>
                                    <p class="text-[11px] text-slate-500 dark:text-gray-400 mt-0.5">
                                        {{ __('Gambar sampul utama yang akan tampil di kartu portofolio dan katalog publik (Maks. 3 MB, JPG/PNG/WebP).') }}
                                    </p>
                                </div>
                            </div>

                            @php
                                $coverPreview = $this->getPreviewUrl($cover_image ?: $existing_cover);
                            @endphp

                            @if($coverPreview)
                                <div wire:key="cover-preview-card" class="relative rounded-xl overflow-hidden border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-3 sm:p-4 flex flex-col sm:flex-row items-center gap-4 shadow-2xs">
                                    <div class="w-full sm:w-44 aspect-video rounded-lg overflow-hidden border border-slate-200 dark:border-gray-700 bg-slate-100 dark:bg-gray-900 shrink-0 shadow-2xs">
                                        <img src="{{ $coverPreview }}" class="w-full h-full object-cover" alt="Preview Cover">
                                    </div>
                                    <div class="flex-1 min-w-0 text-center sm:text-left">
                                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $cover_image ? 'bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300' }} mb-1">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $cover_image ? 'bg-sky-500' : 'bg-emerald-500' }}"></span>
                                            <span>{{ $cover_image ? __('Cover Baru Terpilih') : __('Cover Tersimpan') }}</span>
                                        </div>
                                        <p class="text-xs text-slate-700 dark:text-gray-200 font-medium">{{ __('Gambar Sampul Proyek') }}</p>
                                        <p class="text-[11px] text-slate-400 dark:text-gray-400 mt-0.5">{{ __('Rasio 16:9 • Tampil di kartu publik') }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <label class="relative px-3 py-1.5 bg-slate-100 dark:bg-gray-700 hover:bg-slate-200 dark:hover:bg-gray-600 border border-slate-300 dark:border-gray-600 rounded-lg text-xs font-semibold text-slate-700 dark:text-gray-200 transition cursor-pointer">
                                            {{ __('Ganti Cover') }}
                                            <input type="file" wire:model="cover_image" accept="image/png,image/jpeg,image/webp" class="sr-only">
                                        </label>
                                        <button 
                                            type="button" 
                                            wire:click="removeCover" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition cursor-pointer"
                                            title="{{ __('Hapus cover') }}"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>{{ __('Hapus') }}</span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                {{-- Dropzone Cover --}}
                                <div wire:key="cover-dropzone-wrapper">
                                    <label
                                        x-data="{ isDragging: false }"
                                        @dragover.prevent="isDragging = true"
                                        @dragleave.prevent="isDragging = false"
                                        @drop.prevent="isDragging = false; if ($event.dataTransfer.files.length) { $refs.coverInput.files = $event.dataTransfer.files; $refs.coverInput.dispatchEvent(new Event('change', { bubbles: true })); }"
                                        :class="{ 'border-sky-500 bg-sky-50/40 dark:bg-sky-500/10 ring-2 ring-sky-500/20': isDragging }"
                                        class="relative border-2 border-dashed border-slate-300 dark:border-gray-700 hover:border-sky-500 dark:hover:border-sky-400 bg-white dark:bg-gray-800/60 rounded-2xl p-6 text-center transition-colors cursor-pointer group flex flex-col items-center justify-center shadow-2xs"
                                    >
                                        <div class="w-12 h-12 rounded-full bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-700 dark:text-gray-200 font-semibold">
                                            <span class="text-sky-600 dark:text-sky-400 underline underline-offset-2">{{ __('Pilih file foto') }}</span> {{ __('atau tarik & lepas ke sini') }}
                                        </p>
                                        <p class="text-[11px] text-slate-400 dark:text-gray-400 mt-1">
                                            {{ __('Format JPG, PNG, atau WebP (Disarankan Rasio 16:9, Maks. 3MB)') }}
                                        </p>
                                        <input 
                                            type="file" 
                                            x-ref="coverInput"
                                            wire:model="cover_image" 
                                            accept="image/png,image/jpeg,image/webp" 
                                            class="sr-only"
                                        >
                                    </label>
                                </div>
                            @endif

                            <div wire:loading wire:target="cover_image" class="p-2.5 bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 rounded-xl text-xs text-sky-700 dark:text-sky-300 font-medium flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4 text-sky-600 dark:text-sky-400 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>{{ __('Mengunggah dan memproses gambar cover...') }}</span>
                            </div>
                            @error('cover_image') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- 2. TANGKAPAN LAYAR (SCREENSHOTS) APLIKASI - MAKSIMAL 7 --}}
                        <div class="p-4 rounded-2xl border border-slate-200 dark:border-gray-800 bg-slate-50/60 dark:bg-gray-800/40 space-y-3">
                            @php
                                $totalScreenshots = count($existing_screenshots) + count($screenshots);
                            @endphp
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <label class="block text-xs font-bold text-slate-800 dark:text-gray-200">
                                            {{ __('2. Tangkapan Layar (Screenshots) Aplikasi') }}
                                        </label>
                                        <span class="text-[11px] px-2.5 py-0.5 rounded-full font-bold {{ $totalScreenshots >= 7 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-sky-100 text-sky-800 dark:bg-sky-500/20 dark:text-sky-300' }}">
                                            {{ $totalScreenshots }} / 7 {{ __('Foto Terisi') }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 dark:text-gray-400 mt-0.5">
                                        {{ __('Unggah foto fitur dan antarmuka aplikasi Anda (maksimal 7 foto, JPG/PNG/WebP, maks. 3 MB per foto).') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Visual 7-Slot Photo Gallery Grid --}}
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 pt-1">
                                {{-- Existing Screenshots --}}
                                @foreach($existing_screenshots as $index => $path)
                                    @php
                                        $url = $this->getPreviewUrl($path);
                                    @endphp
                                    <div wire:key="existing-shot-{{ $index }}" class="relative group rounded-xl overflow-hidden border border-slate-200 dark:border-gray-700 bg-slate-100 dark:bg-gray-800 aspect-video shadow-2xs">
                                        @if($url)
                                            <img src="{{ $url }}" class="w-full h-full object-cover" alt="Foto {{ $index + 1 }}">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 dark:bg-gray-800 text-slate-400 p-2 text-center text-[10px]">
                                                <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span>{{ __('Foto') }} {{ $index + 1 }}</span>
                                            </div>
                                        @endif
                                        <button 
                                            type="button" 
                                            wire:click="removeExistingScreenshot({{ $index }})" 
                                            class="absolute top-1.5 right-1.5 w-6 h-6 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-full shadow-md transition flex items-center justify-center cursor-pointer z-10"
                                            title="{{ __('Hapus foto ini') }}"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                        <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 text-[9px] font-bold bg-slate-900/80 text-white rounded">
                                            {{ __('Foto') }} {{ $index + 1 }}
                                        </span>
                                        <span class="absolute bottom-1.5 left-1.5 px-1.5 py-0.5 text-[9px] font-semibold bg-slate-900/80 text-white rounded">
                                            {{ __('Tersimpan') }}
                                        </span>
                                    </div>
                                @endforeach

                                {{-- New Temporary Screenshots --}}
                                @foreach($screenshots as $index => $shot)
                                    @if($shot)
                                        @php
                                            $url = $this->getPreviewUrl($shot);
                                            $photoNumber = count($existing_screenshots) + $index + 1;
                                        @endphp
                                        <div wire:key="new-shot-{{ $index }}" class="relative group rounded-xl overflow-hidden border border-sky-400 dark:border-sky-600 bg-slate-100 dark:bg-gray-800 aspect-video shadow-2xs">
                                            @if($url)
                                                <img src="{{ $url }}" class="w-full h-full object-cover" alt="Foto {{ $photoNumber }}">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 p-2 text-center text-[10px]">
                                                    <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    <span>{{ __('Foto') }} {{ $photoNumber }}</span>
                                                </div>
                                            @endif
                                            <button 
                                                type="button" 
                                                wire:click="removeScreenshot({{ $index }})" 
                                                class="absolute top-1.5 right-1.5 w-6 h-6 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-full shadow-md transition flex items-center justify-center cursor-pointer z-10"
                                                title="{{ __('Hapus foto ini') }}"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                            <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 text-[9px] font-bold bg-sky-700 text-white rounded">
                                                {{ __('Foto') }} {{ $photoNumber }}
                                            </span>
                                            <span class="absolute bottom-1.5 left-1.5 px-1.5 py-0.5 text-[9px] font-semibold bg-sky-600 text-white rounded">
                                                {{ __('Baru') }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach

                                {{-- Next Upload Slot / Add Button if < 7 --}}
                                @if($totalScreenshots < 7)
                                    <div wire:key="shot-add-slot-wrapper">
                                        <label
                                            x-data="{ isDragging: false }"
                                            @dragover.prevent="isDragging = true"
                                            @dragleave.prevent="isDragging = false"
                                            @drop.prevent="isDragging = false; if ($event.dataTransfer.files.length) { $refs.screenshotInput.files = $event.dataTransfer.files; $refs.screenshotInput.dispatchEvent(new Event('change', { bubbles: true })); }"
                                            :class="{ 'border-sky-500 bg-sky-50 dark:bg-sky-500/20 ring-2 ring-sky-500/20': isDragging }"
                                            class="relative aspect-video rounded-xl border-2 border-dashed border-sky-300 dark:border-sky-600 hover:border-sky-500 dark:hover:border-sky-400 bg-sky-50/40 dark:bg-sky-500/5 hover:bg-sky-50 dark:hover:bg-sky-500/10 transition flex flex-col items-center justify-center p-3 text-center cursor-pointer group shadow-2xs"
                                        >
                                            <div class="w-8 h-8 rounded-full bg-sky-100 dark:bg-sky-500/20 text-sky-600 dark:text-sky-400 flex items-center justify-center mb-1 group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </div>
                                            <span class="text-xs font-bold text-sky-700 dark:text-sky-300 group-hover:underline">
                                                + {{ __('Tambah Foto') }}
                                            </span>
                                            <span class="text-[10px] text-slate-500 dark:text-gray-400 mt-0.5">
                                                {{ __('Sisa :sisa foto', ['sisa' => 7 - $totalScreenshots]) }}
                                            </span>
                                            <input 
                                                type="file" 
                                                x-ref="screenshotInput"
                                                wire:model="new_screenshots" 
                                                multiple 
                                                accept="image/png,image/jpeg,image/webp" 
                                                class="sr-only"
                                            >
                                        </label>
                                    </div>
                                @endif
                            </div>

                            @if($totalScreenshots >= 7)
                                <div wire:key="screenshots-full-msg" class="p-3 bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl text-xs text-emerald-800 dark:text-emerald-300 flex items-center gap-2.5">
                                    <svg class="w-4 h-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ __('Semua 7 slot foto telah terisi lengkap. Klik tanda silang (×) pada foto jika ingin mengganti.') }}</span>
                                </div>
                            @endif

                            <div wire:loading wire:target="new_screenshots" class="p-2.5 bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 rounded-xl text-xs text-sky-700 dark:text-sky-300 font-medium flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4 text-sky-600 dark:text-sky-400 shrink-0" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>{{ __('Sedang mengunggah dan memproses foto tangkapan layar...') }}</span>
                            </div>
                            @error('screenshots') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                            @error('new_screenshots.*') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                            @error('screenshots.*') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- 3. BUKTI KEPEMILIKAN & ORISINALITAS KARYA --}}
                        <div wire:key="bukti-section-container" class="p-4 rounded-2xl border border-slate-200 dark:border-gray-800 bg-slate-50/60 dark:bg-gray-800/40 space-y-3">
                            @php
                                $totalBukti = count($existing_bukti) + count($bukti_files);
                            @endphp
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <label class="block text-xs font-bold text-slate-800 dark:text-gray-200">
                                            {{ __('3. Bukti Kepemilikan & Orisinalitas Karya') }}
                                        </label>
                                        @if($totalBukti > 0)
                                            <span class="text-[11px] px-2 py-0.5 rounded-full font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300">
                                                {{ $totalBukti }} {{ __('Berkas') }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-500 dark:text-gray-400 mt-0.5">
                                        {{ __('Unggah bukti bahwa karya ini dibuat oleh Anda (misal: screenshot workspace IDE / source code dengan nama Anda, log git commit, surat pengantar/tugas, atau dokumen PDF dokumentasi). Maks. 5 MB per berkas (JPG, PNG, WebP, PDF, ZIP).') }}
                                    </p>
                                </div>
                            </div>

                            @if($totalBukti < 5)
                                {{-- Dropzone Bukti (Sesuai Gambar 2) --}}
                                <div wire:key="bukti-dropzone-wrapper">
                                    <label
                                        x-data="{ isDragging: false }"
                                        @dragover.prevent="isDragging = true"
                                        @dragleave.prevent="isDragging = false"
                                        @drop.prevent="isDragging = false; if ($event.dataTransfer.files.length) { $refs.buktiInput.files = $event.dataTransfer.files; $refs.buktiInput.dispatchEvent(new Event('change', { bubbles: true })); }"
                                        :class="{ 'border-sky-500 bg-sky-50/40 dark:bg-sky-500/10 ring-2 ring-sky-500/20': isDragging }"
                                        class="relative border-2 border-dashed border-slate-300 dark:border-gray-700 hover:border-sky-500 dark:hover:border-sky-400 bg-white dark:bg-gray-800/60 rounded-2xl p-6 text-center transition-colors cursor-pointer group flex flex-col items-center justify-center shadow-2xs"
                                    >
                                        <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-500/20 transition-colors">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-700 dark:text-gray-200 font-medium">
                                            <span class="text-sky-600 dark:text-sky-400 font-semibold underline underline-offset-2">{{ __('Pilih berkas dokumen / bukti') }}</span> {{ __('atau tarik & lepas ke sini') }}
                                        </p>
                                        <p class="text-[11px] text-slate-400 dark:text-gray-400 mt-1">
                                            {{ __('Format Dokumen (PDF, ZIP) atau Gambar (JPG, PNG, WebP), Maks. 5MB per berkas') }}
                                        </p>
                                        <input 
                                            type="file" 
                                            x-ref="buktiInput"
                                            wire:model="new_bukti_files" 
                                            multiple 
                                            accept="image/png,image/jpeg,image/webp,application/pdf,application/zip" 
                                            class="sr-only"
                                        >
                                    </label>
                                </div>
                            @else
                                <div wire:key="bukti-max-reached" class="p-3 bg-slate-50 dark:bg-gray-800/70 border border-slate-200 dark:border-gray-700 rounded-xl text-xs text-slate-600 dark:text-gray-300 flex items-center gap-2.5">
                                    <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ __('Kuota maksimal 5 berkas bukti telah terpenuhi. Klik tombol hapus pada berkas di bawah jika ingin mengganti.') }}</span>
                                </div>
                            @endif

                            <div wire:loading wire:target="new_bukti_files" class="text-xs text-sky-600 dark:text-sky-400 font-medium flex items-center gap-1.5">
                                <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>{{ __('Mengunggah berkas bukti validasi...') }}</span>
                            </div>
                            @error('bukti_files') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                            @error('new_bukti_files.*') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                            @error('bukti_files.*') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror

                            {{-- Daftar Berkas Bukti --}}
                            @if($totalBukti > 0)
                                <div wire:key="bukti-files-list" class="space-y-2 pt-2">
                                    {{-- Existing Bukti --}}
                                    @foreach($existing_bukti as $index => $path)
                                        <div wire:key="existing-bukti-{{ $index }}" class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs shadow-2xs">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span class="truncate font-medium text-slate-700 dark:text-gray-200">{{ basename($path) }}</span>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 font-semibold shrink-0">{{ __('Tersimpan') }}</span>
                                            </div>
                                            <div class="flex items-center gap-1 shrink-0 ml-2">
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($path) }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline p-1 text-[11px] font-semibold">
                                                    {{ __('Lihat') }}
                                                </a>
                                                <button 
                                                    type="button" 
                                                    wire:click="removeExistingBukti({{ $index }})" 
                                                    class="text-rose-500 hover:text-rose-700 p-1 rounded-md hover:bg-rose-50 dark:hover:bg-rose-500/10 transition cursor-pointer" 
                                                    title="{{ __('Hapus bukti tersimpan') }}"
                                                >
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- New Bukti Files --}}
                                    @foreach($bukti_files as $index => $file)
                                        @if($file)
                                            <div wire:key="new-bukti-{{ $index }}" class="flex items-center justify-between p-2.5 rounded-xl border border-sky-200 dark:border-sky-800 bg-sky-50/50 dark:bg-sky-500/10 text-xs shadow-2xs">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    <span class="truncate font-medium text-slate-800 dark:text-gray-100">{{ $this->getBuktiFileName($file) }}</span>
                                                    @if($size = $this->getBuktiFileSize($file))
                                                        <span class="text-[10px] text-slate-400 shrink-0">({{ $size }})</span>
                                                    @endif
                                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300 font-semibold shrink-0">{{ __('Baru') }}</span>
                                                </div>
                                                <button 
                                                    type="button" 
                                                    wire:click="removeBukti({{ $index }})" 
                                                    class="text-rose-500 hover:text-rose-700 p-1 rounded-md hover:bg-rose-50 dark:hover:bg-rose-500/10 transition cursor-pointer ml-2 shrink-0" 
                                                    title="{{ __('Hapus berkas baru ini') }}"
                                                >
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- URL Demo & URL Repo --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-gray-200 uppercase tracking-wider mb-1.5">
                                {{ __('Tautan Demo / Live Website (Opsional)') }}
                            </label>
                            <input 
                                type="url" 
                                wire:model="url_demo" 
                                placeholder="https://proyek-kamu.vercel.app" 
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none"
                            >
                            @error('url_demo') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-gray-200 uppercase tracking-wider mb-1.5">
                                {{ __('Tautan Repositori GitHub / GitLab (Opsional)') }}
                            </label>
                            <input 
                                type="url" 
                                wire:model="url_repo" 
                                placeholder="https://github.com/username/repo" 
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none"
                            >
                            @error('url_repo') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Modal Footer Actions --}}
                <div wire:key="modal-buat-karya-footer" class="shrink-0 mt-auto flex items-center justify-between px-6 py-4 border-t border-slate-200 dark:border-gray-800 bg-slate-50 dark:bg-gray-800/80">
                    <button 
                        type="button" 
                        wire:click="closeCreateModal" 
                        class="px-4 py-2 border border-slate-300 dark:border-gray-700 text-slate-700 dark:text-gray-200 rounded-xl hover:bg-slate-100 dark:hover:bg-gray-700 text-xs font-semibold transition cursor-pointer"
                    >
                        {{ __('Batal') }}
                    </button>
                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            wire:click="save('draft')" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-200 rounded-xl hover:bg-slate-200 dark:hover:bg-gray-600 text-xs font-semibold transition cursor-pointer"
                        >
                            {{ $editingKaryaId ? __('Simpan Sebagai Draft') : __('Simpan Draft') }}
                        </button>
                        <button 
                            type="button" 
                            wire:click="save('review')" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-semibold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ __('Simpan Perubahan') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
