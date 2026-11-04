<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                    {{ auth()->user()->role === 'siswa' ? 'Karya Saya' : 'Koleksi Karya Siswa' }}
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Portofolio proyek software, desain, dan aplikasi yang dibuat oleh siswa RPL.
                </p>
            </div>
            @if(in_array(auth()->user()->role, ['admin', 'guru']))
                <div class="flex items-center gap-2.5">
                    <a 
                        href="{{ route('karya-siswa.verifikasi') }}" 
                        wire:navigate
                        class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition shadow-xs"
                    >
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Verifikasi Pengajuan</span>
                    </a>
                </div>
            @endif
        </div>

        {{-- Flash Messages --}}
        @if(session()->has('message'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    'bg-white border-slate-300 ring-2 ring-slate-400 shadow-xs' => $filterStatus === '',
                    'bg-white border-slate-200/90 hover:border-slate-300' => $filterStatus !== '',
                ])
            >
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Semua Karya</span>
                <span class="text-xl font-bold text-slate-900 mt-0.5 block">{{ $stats['total'] }}</span>
            </button>

            <button 
                type="button" 
                wire:click="setStatusFilter('published')"
                @class([
                    'p-3.5 rounded-xl border text-left transition cursor-pointer',
                    'bg-emerald-50/70 border-emerald-300 ring-2 ring-emerald-500 shadow-xs' => $filterStatus === 'published',
                    'bg-white border-slate-200/90 hover:border-emerald-200' => $filterStatus !== 'published',
                ])
            >
                <span class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider block">Terpublikasi</span>
                <span class="text-xl font-bold text-emerald-900 mt-0.5 block">{{ $stats['published'] }}</span>
            </button>

            <button 
                type="button" 
                wire:click="setStatusFilter('review')"
                @class([
                    'p-3.5 rounded-xl border text-left transition cursor-pointer',
                    'bg-amber-50/70 border-amber-300 ring-2 ring-amber-500 shadow-xs' => $filterStatus === 'review',
                    'bg-white border-slate-200/90 hover:border-amber-200' => $filterStatus !== 'review',
                ])
            >
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-amber-700 uppercase tracking-wider block">Butuh Review</span>
                    @if($stats['review'] > 0)
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                    @endif
                </div>
                <span class="text-xl font-bold text-amber-900 mt-0.5 block">{{ $stats['review'] }}</span>
            </button>

            <button 
                type="button" 
                wire:click="setStatusFilter('draft')"
                @class([
                    'p-3.5 rounded-xl border text-left transition cursor-pointer',
                    'bg-slate-100 border-slate-300 ring-2 ring-slate-500 shadow-xs' => $filterStatus === 'draft',
                    'bg-white border-slate-200/90 hover:border-slate-300' => $filterStatus !== 'draft',
                ])
            >
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wider block">Draft</span>
                <span class="text-xl font-bold text-slate-800 mt-0.5 block">{{ $stats['draft'] }}</span>
            </button>
        </div>

        {{-- Penjelasan Singkat Alur Status Karya --}}
        <div class="mb-6 px-4 py-3 bg-white rounded-xl border border-slate-200/90 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-600 shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="leading-relaxed">
                    <strong class="text-slate-800">Status Karya:</strong> 
                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Terpublikasi</span> (sudah diverifikasi & live di web),
                    <span class="inline-flex items-center gap-1 font-semibold text-amber-700 ml-1.5"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Butuh Review</span> (pengajuan butuh kurasi admin),
                    <span class="inline-flex items-center gap-1 font-semibold text-slate-600 ml-1.5"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Draft</span> (masih dikerjakan siswa).
                </span>
            </div>
            @if(in_array(auth()->user()->role, ['admin', 'guru']) && ($stats['review'] ?? 0) > 0)
                <a href="{{ route('karya-siswa.verifikasi') }}" wire:navigate class="inline-flex items-center gap-1 font-semibold text-amber-800 hover:text-amber-900 text-xs bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg border border-amber-200 transition">
                    <span>{{ $stats['review'] }} karya butuh verifikasi</span>
                    <span class="text-sm leading-none">&rarr;</span>
                </a>
            @endif
        </div>

        {{-- Toolbar: Pencarian & Filter --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                {{-- Search Input --}}
                <div class="relative sm:col-span-2 lg:col-span-2">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari judul proyek, nama siswa, atau kelas..." 
                        class="w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                {{-- Filter Kategori --}}
                <div>
                    <select wire:model.live="filterKategori" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="">Semua Kategori</option>
                        <option value="web">Web Development</option>
                        <option value="mobile">Mobile Apps</option>
                        <option value="desktop">Desktop Apps</option>
                        <option value="game">Game Development</option>
                        <option value="iot">IoT & Embedded</option>
                        <option value="ui-ux">UI/UX Design</option>
                        <option value="data">Data Science</option>
                        <option value="jaringan">Jaringan</option>
                        <option value="robotika">Robotika</option>
                        <option value="ai">AI / Machine Learning</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                {{-- Filter Tahun --}}
                <div>
                    <select wire:model.live="filterTahun" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Grid Kartu Portofolio Karya Siswa --}}
        @if($karyaSiswas->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-16 text-center shadow-xs">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-slate-100 text-slate-400 mb-4">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">Tidak ada karya yang ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Coba sesuaikan kata kunci pencarian atau ubah filter kategori dan status.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($karyaSiswas as $karya)
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden flex flex-col justify-between hover:border-slate-300 transition group">
                        <div>
                            {{-- Cover Thumbnail --}}
                            <div class="aspect-video bg-slate-100 relative overflow-hidden border-b border-slate-100">
                                @if($karya->gambar_url)
                                    <img src="{{ $karya->gambar_url }}" alt="{{ $karya->judul }}" class="w-full h-full object-cover group-hover:scale-102 transition duration-300">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                        <svg class="w-10 h-10 stroke-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-[10px] text-slate-400 mt-1">Tanpa cover</span>
                                    </div>
                                @endif

                                {{-- Badges on Cover --}}
                                <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between pointer-events-none">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shadow-2xs backdrop-blur-xs
                                        @if($karya->status === 'published') bg-emerald-600/90 text-white
                                        @elseif($karya->status === 'review') bg-amber-500/95 text-white
                                        @else bg-slate-800/80 text-white @endif"
                                    >
                                        @if($karya->status === 'published') Published
                                        @elseif($karya->status === 'review') Review
                                        @else Draft @endif
                                    </span>

                                    @if($karya->kategori)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/95 text-slate-700 shadow-2xs border border-slate-200/60">
                                            {{ ucfirst($karya->kategori) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4 sm:p-5">
                                <h3 class="text-sm font-bold text-slate-900 leading-snug line-clamp-1 group-hover:text-sky-600 transition" title="{{ $karya->judul }}">
                                    {{ $karya->judul }}
                                </h3>

                                <div class="flex items-center gap-1.5 text-[11px] text-slate-500 mt-1">
                                    <span class="font-medium text-slate-700">{{ $karya->nama_siswa }}</span>
                                    <span>•</span>
                                    <span>{{ $karya->kelas ?: 'RPL' }}</span>
                                    @if($karya->tahun)
                                        <span>•</span>
                                        <span>{{ $karya->tahun }}</span>
                                    @endif
                                </div>

                                @if($karya->deskripsi_singkat)
                                    <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
                                        {{ $karya->deskripsi_singkat }}
                                    </p>
                                @endif

                                {{-- Tag Keahlian --}}
                                @if(!empty($karya->tags) && is_array($karya->tags))
                                    <div class="flex flex-wrap gap-1 mt-3">
                                        @foreach(array_slice($karya->tags, 0, 2) as $tag)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200/60">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Tautan Demo & Repo --}}
                                @if($karya->url_demo || $karya->url_repo)
                                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100 text-[11px]">
                                        @if($karya->url_demo)
                                            <a href="{{ $karya->url_demo }}" target="_blank" class="inline-flex items-center gap-1 text-sky-600 hover:text-sky-800 font-medium">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                Demo
                                            </a>
                                        @endif
                                        @if($karya->url_repo)
                                            <a href="{{ $karya->url_repo }}" target="_blank" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-900 font-medium ml-auto">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                                Repository
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                {{-- Penjelasan Status Karya --}}
                                <div class="mt-3.5 pt-3 border-t border-slate-100">
                                    @if($karya->status === 'published')
                                        <div class="flex items-start gap-2 p-2 rounded-lg bg-emerald-50/80 border border-emerald-200/60">
                                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div class="text-[11px] leading-snug">
                                                <span class="font-bold text-emerald-900 block">Sudah Terpublikasi</span>
                                                <span class="text-emerald-700 block">Karya sudah disetujui & tampil di website publik.</span>
                                            </div>
                                        </div>
                                    @elseif($karya->status === 'review')
                                        <div class="flex items-start gap-2 p-2 rounded-lg bg-amber-50/90 border border-amber-200/70">
                                            <span class="relative flex h-2 w-2 shrink-0 mt-1.5 ml-0.5 mr-0.5">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                            </span>
                                            <div class="text-[11px] leading-snug">
                                                <span class="font-bold text-amber-900 block">Butuh Verifikasi</span>
                                                <span class="text-amber-800 block">Menunggu kurasi admin di menu Verifikasi.</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-start gap-2 p-2 rounded-lg bg-slate-50 border border-slate-200/80">
                                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <div class="text-[11px] leading-snug">
                                                <span class="font-bold text-slate-800 block">Draft Siswa</span>
                                                <span class="text-slate-500 block">Belum diajukan oleh siswa untuk ditinjau.</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer: STRICTLY ONLY "LIHAT DETAIL" & "HAPUS" --}}
                        <div class="px-4 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-2">
                            <button 
                                type="button" 
                                wire:click="openDetailModal({{ $karya->id }})" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 hover:text-slate-900 transition shadow-2xs cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Lihat Detail</span>
                            </button>

                            <button 
                                type="button" 
                                wire:click="confirmDelete({{ $karya->id }})" 
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50/70 border border-rose-200/80 rounded-lg hover:bg-rose-100 hover:text-rose-800 transition cursor-pointer"
                                title="Hapus karya ini"
                            >
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            @if($karyaSiswas->hasPages())
                <div class="mt-6 p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
                    {{ $karyaSiswas->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- MODAL DETAIL KARYA SISWA --}}
    @if($showDetailModal && $detailKarya)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity" wire:click="closeDetailModal"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col border border-slate-200 z-10 overflow-hidden">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                @if($detailKarya->status === 'published') bg-emerald-100 text-emerald-800
                                @elseif($detailKarya->status === 'review') bg-amber-100 text-amber-800
                                @else bg-slate-200 text-slate-700 @endif"
                            >
                                {{ ucfirst($detailKarya->status) }}
                            </span>
                            @if($detailKarya->kategori)
                                <span class="text-xs text-slate-500">
                                    {{ ucfirst($detailKarya->kategori) }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-slate-900 truncate max-w-lg">{{ $detailKarya->judul }}</h3>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closeDetailModal" 
                        class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body (Scrollable) --}}
                <div class="overflow-y-auto p-6 space-y-6 text-xs text-slate-700">
                    {{-- Status Penjelasan Karya Banner --}}
                    @if($detailKarya->status === 'published')
                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3">
                            <div class="h-8 w-8 rounded-lg bg-emerald-100 border border-emerald-200 flex items-center justify-center shrink-0 text-emerald-700">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-emerald-950 text-sm">Status: Karya Sudah Terpublikasi (Live)</h4>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-200 text-emerald-900 uppercase">Aktif</span>
                                </div>
                                <p class="text-xs text-emerald-800 mt-1 leading-relaxed">
                                    Karya ini telah diverifikasi dan disetujui kurator. Karya aktif ditampilkan di etalase web publik dan halaman profil siswa.
                                    @if($detailKarya->published_at)
                                        (Dipublikasikan: {{ $detailKarya->published_at->format('d M Y') }})
                                    @endif
                                </p>
                            </div>
                        </div>
                    @elseif($detailKarya->status === 'review')
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="h-8 w-8 rounded-lg bg-amber-100 border border-amber-200 flex items-center justify-center shrink-0 text-amber-700">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-amber-950 text-sm">Status: Butuh Verifikasi (Dalam Review)</h4>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-200 text-amber-950 uppercase">Menunggu Persetujuan</span>
                                    </div>
                                    <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                                        Karya telah diajukan siswa dan sedang menunggu verifikasi dari Admin/Guru. Karya belum ditampilkan di etalase publik hingga disetujui.
                                    </p>
                                </div>
                            </div>
                            @if(in_array(auth()->user()->role, ['admin', 'guru']))
                                <a 
                                    href="{{ route('karya-siswa.verifikasi') }}" 
                                    wire:navigate
                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-semibold shrink-0 transition shadow-2xs"
                                >
                                    <span>Verifikasi Sekarang</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="p-4 bg-slate-100 border border-slate-200 rounded-xl flex items-start gap-3">
                            <div class="h-8 w-8 rounded-lg bg-slate-200 flex items-center justify-center shrink-0 text-slate-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-slate-900 text-sm">Status: Draft Siswa</h4>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700 uppercase">Belum Diajukan</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                    Karya ini masih berupa draf yang tersimpan di akun siswa dan belum diajukan untuk ditinjau oleh tim kurator sekolah.
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Cover Banner --}}
                    @if($detailKarya->gambar_url)
                        <div class="aspect-video w-full rounded-xl overflow-hidden border border-slate-200 bg-slate-100">
                            <img src="{{ $detailKarya->gambar_url }}" alt="{{ $detailKarya->judul }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    {{-- Info Siswa & Proyek --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200/80">
                        <div>
                            <span class="text-slate-400 block mb-0.5">Siswa Pembuat</span>
                            <span class="font-bold text-slate-900">{{ $detailKarya->nama_siswa }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Kelas</span>
                            <span class="font-semibold text-slate-800">{{ $detailKarya->kelas ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Tahun</span>
                            <span class="font-semibold text-slate-800">{{ $detailKarya->tahun ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Dipublikasikan</span>
                            <span class="font-semibold text-slate-800">{{ $detailKarya->published_at ? $detailKarya->published_at->format('d M Y') : 'Belum' }}</span>
                        </div>
                    </div>

                    {{-- Demo & Repository Links --}}
                    @if($detailKarya->url_demo || $detailKarya->url_repo)
                        <div class="flex flex-wrap gap-2.5">
                            @if($detailKarya->url_demo)
                                <a href="{{ $detailKarya->url_demo }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 text-sky-700 border border-sky-200 rounded-lg font-semibold hover:bg-sky-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    <span>Buka Live Demo</span>
                                    <span class="text-xs">&nearr;</span>
                                </a>
                            @endif
                            @if($detailKarya->url_repo)
                                <a href="{{ $detailKarya->url_repo }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg font-semibold hover:bg-slate-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                    <span>Repositori Kode</span>
                                    <span class="text-xs">&nearr;</span>
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Pesan Admin / Catatan Revisi jika ada --}}
                    @if($detailKarya->catatan_admin)
                        <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl">
                            <p class="font-bold text-amber-800 uppercase tracking-wider text-[10px] mb-1">Catatan Kurasi Admin</p>
                            <p class="text-amber-900 leading-relaxed">{{ $detailKarya->catatan_admin }}</p>
                        </div>
                    @endif

                    {{-- Deskripsi Konten (Rendered Markdown) --}}
                    <div>
                        <h4 class="font-bold text-slate-900 uppercase tracking-wider mb-2">Deskripsi Lengkap</h4>
                        <div class="prose prose-xs max-w-none p-4 rounded-xl border border-slate-200 bg-white">
                            {!! $detailKarya->deskripsi_html !!}
                        </div>
                    </div>

                    {{-- Screenshots Gallery --}}
                    @if(count($detailKarya->screenshots ?? []) > 0)
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase tracking-wider mb-2">Tangkapan Layar ({{ count($detailKarya->screenshots) }})</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                @foreach($detailKarya->screenshots as $path)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($path) }}" target="_blank" class="aspect-video rounded-lg overflow-hidden border border-slate-200 bg-slate-50 hover:opacity-90">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($path) }}" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Bukti Validasi --}}
                    @if(count($detailKarya->bukti ?? []) > 0)
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase tracking-wider mb-2">Berkas Bukti</h4>
                            <div class="space-y-1.5">
                                @foreach($detailKarya->bukti as $b)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($b) }}" target="_blank" class="flex items-center justify-between p-2.5 bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100">
                                        <span class="truncate">{{ basename($b) }}</span>
                                        <span class="text-sky-600 font-semibold shrink-0 ml-2">Buka Berkas ↗</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-3.5 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
                    <button 
                        type="button" 
                        wire:click="confirmDelete({{ $detailKarya->id }})" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-700 bg-white border border-rose-200 rounded-lg hover:bg-rose-50 transition cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Hapus Karya Ini</span>
                    </button>

                    <button 
                        type="button" 
                        wire:click="closeDetailModal" 
                        class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 transition shadow-2xs cursor-pointer"
                    >
                        Tutup
                    </button>
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

            <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-slate-200 z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-10 w-10 rounded-full bg-rose-50 border border-rose-200 flex items-center justify-center flex-shrink-0 text-rose-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Karya</h3>
                        <p class="text-xs text-slate-500">Tindakan ini akan menghapus data karya siswa</p>
                    </div>
                </div>

                <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                    Apakah Anda yakin ingin menghapus karya <strong class="text-slate-900">"{{ $karyaToDelete?->judul }}"</strong> milik siswa <strong class="text-slate-900">{{ $karyaToDelete?->nama_siswa }}</strong>?
                </p>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button 
                        type="button" 
                        wire:click="cancelDelete" 
                        class="px-4 py-2 border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="delete"
                        class="px-5 py-2 bg-rose-600 text-white rounded-lg text-xs font-semibold hover:bg-rose-700 transition shadow-xs cursor-pointer"
                    >
                        Ya, Hapus Karya
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
