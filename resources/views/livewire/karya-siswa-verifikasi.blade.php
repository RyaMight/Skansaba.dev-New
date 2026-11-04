<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Verifikasi Karya Siswa</h1>
                <p class="text-sm text-slate-500 mt-1">Kurasi karya siswa, validasi orisinalitas, dan berikan 1 tag keahlian terverifikasi untuk akun siswa.</p>
            </div>
            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('siswa-rpl.index') }}" 
                    wire:navigate
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition shadow-xs"
                >
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Kelola Akun Siswa RPL
                </a>
            </div>
        </div>

        {{-- Filter User Banner (jika diarahkan dari shortcut Akun Siswa RPL) --}}
        @if($userId)
            <div class="mb-6 p-4 bg-sky-50 border border-sky-200 rounded-xl flex items-center justify-between text-sm text-sky-800">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-sky-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Menampilkan pengajuan karya khusus untuk siswa yang dipilih.</span>
                </div>
                <button 
                    type="button" 
                    wire:click="clearUserFilter" 
                    class="text-xs font-semibold text-sky-700 hover:text-sky-900 underline"
                >
                    Tampilkan Semua Siswa
                </button>
            </div>
        @endif

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

        @if($karyaList->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-16 text-center shadow-xs">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-emerald-50 border border-emerald-200 mb-4">
                    <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-900">Tidak ada karya yang menunggu verifikasi</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">Semua pengajuan karya siswa saat ini telah dikurasi. Bila siswa mengirim pengajuan baru, notifikasi tanda seru akan muncul di menu.</p>
                @if($userId)
                    <button 
                        type="button" 
                        wire:click="clearUserFilter" 
                        class="mt-4 inline-flex items-center text-xs font-semibold text-sky-600 hover:text-sky-800"
                    >
                        ← Lihat pengajuan siswa lainnya
                    </button>
                @endif
            </div>
        @else
            <div class="space-y-6">
                @foreach($karyaList as $karya)
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden transition-all">
                        <div class="grid grid-cols-1 lg:grid-cols-12 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
                            {{-- Cover + screenshots --}}
                            <div class="lg:col-span-4 bg-slate-50/70 p-5 flex flex-col justify-between">
                                <div>
                                    <div class="aspect-video rounded-xl overflow-hidden border border-slate-200 bg-white flex items-center justify-center relative group">
                                        @if($karya->gambar_url)
                                            <img src="{{ $karya->gambar_url }}" alt="{{ $karya->judul }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <svg class="w-10 h-10 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-xs mt-1">Tanpa cover</span>
                                            </div>
                                        @endif

                                        <button 
                                            type="button" 
                                            wire:click="openPreviewModal({{ $karya->id }})" 
                                            class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium gap-1.5 backdrop-blur-xs"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Buka Preview Penuh
                                        </button>
                                    </div>

                                    @if(count($karya->screenshots ?? []) > 0)
                                        <div class="mt-3">
                                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Screenshots ({{ count($karya->screenshots) }})</p>
                                            <div class="grid grid-cols-4 gap-1.5">
                                                @foreach(array_slice($karya->screenshots, 0, 4) as $shot)
                                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($shot) }}" target="_blank" class="aspect-video rounded-md overflow-hidden border border-slate-200 hover:opacity-80 transition bg-white">
                                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($shot) }}" class="w-full h-full object-cover" alt="Screenshot">
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Bukti Berkas --}}
                                    @if(count($karya->bukti ?? []) > 0)
                                        <div class="mt-4 pt-3 border-t border-slate-200/60">
                                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Bukti Validasi ({{ count($karya->bukti) }})</p>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($karya->bukti as $bukti)
                                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($bukti) }}" target="_blank"
                                                        class="inline-flex items-center gap-1 px-2 py-1 bg-white border border-slate-200 rounded-md text-[11px] text-slate-700 hover:text-sky-600 hover:border-sky-300 transition shadow-2xs">
                                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                        <span class="max-w-[130px] truncate">{{ basename($bukti) }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-200/60">
                                    <button 
                                        type="button" 
                                        wire:click="openPreviewModal({{ $karya->id }})" 
                                        class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 transition shadow-2xs"
                                    >
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat Preview Lengkap
                                    </button>
                                </div>
                            </div>

                            {{-- Detail Konten & Verifikasi --}}
                            <div class="lg:col-span-8 p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                    Menunggu Verifikasi
                                                </span>
                                                @if($karya->kategori)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                                                        {{ ucfirst($karya->kategori) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <h2 class="text-xl font-bold text-slate-900 leading-snug">{{ $karya->judul }}</h2>
                                            <div class="flex flex-wrap items-center gap-y-1 gap-x-2 text-xs text-slate-500 mt-1">
                                                <span class="font-medium text-slate-700">{{ $karya->nama_siswa }}</span>
                                                <span>•</span>
                                                <span>{{ $karya->kelas }}</span>
                                                <span>•</span>
                                                <span>Diajukan {{ $karya->updated_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Link Demo & Repo --}}
                                    @if($karya->url_demo || $karya->url_repo)
                                        <div class="flex items-center gap-3 mt-3">
                                            @if($karya->url_demo)
                                                <a href="{{ $karya->url_demo }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-sky-600 hover:text-sky-800">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    Live Demo
                                                </a>
                                            @endif
                                            @if($karya->url_repo)
                                                <a href="{{ $karya->url_repo }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-900">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                                    Source Code Repository
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Deskripsi Cuplikan --}}
                                    <div class="mt-4 p-3.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-700 max-h-36 overflow-y-auto leading-relaxed">
                                        @if($karya->deskripsi_singkat)
                                            <p class="font-medium text-slate-800 mb-1.5">{{ $karya->deskripsi_singkat }}</p>
                                        @endif
                                        <div class="prose prose-xs max-w-none text-slate-600">
                                            {!! $karya->deskripsi_html !!}
                                        </div>
                                    </div>

                                    {{-- Pemilihan Tag Kategori / Keahlian Akun --}}
                                    <div class="mt-5">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-xs font-semibold text-slate-800 uppercase tracking-wider">
                                                Beri Tag Keahlian Siswa <span class="text-rose-500 font-bold">*</span>
                                            </label>
                                            <span class="text-[11px] text-slate-500">Tag ini akan diberikan ke akun siswa</span>
                                        </div>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach(\App\Livewire\KaryaSiswaVerifikasi::TAG_PRESETS as $tag)
                                                @php
                                                    $isSelected = ($selectedTag[$karya->id] ?? null) === $tag;
                                                @endphp
                                                <button
                                                    type="button"
                                                    wire:click="selectTag({{ $karya->id }}, '{{ $tag }}')"
                                                    @class([
                                                        'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium border transition cursor-pointer',
                                                        'bg-sky-600 border-sky-600 text-white shadow-2xs font-semibold' => $isSelected,
                                                        'bg-white border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50' => !$isSelected,
                                                    ])
                                                >
                                                    @if($isSelected)
                                                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @endif
                                                    <span>{{ $tag }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                        @if(!empty($selectedTag[$karya->id]))
                                            <p class="text-[11px] text-sky-700 mt-2 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>Saat disetujui, tag <strong>{{ $selectedTag[$karya->id] }}</strong> akan diberikan ke profil <strong>{{ $karya->nama_siswa }}</strong>.</span>
                                            </p>
                                        @else
                                            <p class="text-[11px] text-amber-600 mt-2">
                                                Pilih tag di atas untuk diberikan ke akun siswa ini.
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Aksi Keputusan Admin --}}
                                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                                    <button
                                        type="button"
                                        wire:click="openRejectModal({{ $karya->id }})"
                                        class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-50 hover:border-slate-400 transition"
                                    >
                                        Tolak / Minta Revisi
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="openApproveModal({{ $karya->id }})"
                                        class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition flex items-center gap-2 shadow-2xs"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Setujui & Publikasikan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- MODAL CUSTOM 1: Konfirmasi Persetujuan (Tengah Layar) --}}
    @if($approvingId)
        @php
            $karyaApprove = $karyaList->firstWhere('id', $approvingId) ?? \App\Models\KaryaSiswa::with('user')->find($approvingId);
            $chosenTag = $selectedTag[$approvingId] ?? null;
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity" wire:click="closeApproveModal"></div>
            
            <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-slate-200 z-10 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center flex-shrink-0 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Konfirmasi Publikasi Karya</h3>
                        <p class="text-xs text-slate-500">Verifikasi keaslian portofolio siswa</p>
                    </div>
                </div>

                <p class="text-sm text-slate-700 mb-4 leading-relaxed">
                    Apakah Anda yakin karya ini asli dan memenuhi standar untuk dipublikasikan ke halaman publik?
                </p>

                <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs space-y-2 mb-5">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Judul Karya:</span>
                        <span class="font-semibold text-slate-800 text-right max-w-[200px] truncate">{{ $karyaApprove?->judul }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Siswa Pembuat:</span>
                        <span class="font-medium text-slate-800">{{ $karyaApprove?->nama_siswa }} ({{ $karyaApprove?->kelas }})</span>
                    </div>
                    <div class="flex justify-between items-center pt-1 border-t border-slate-200/60">
                        <span class="text-slate-500">Tag yang Diberikan:</span>
                        @if($chosenTag)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                + {{ $chosenTag }}
                            </span>
                        @else
                            <span class="text-slate-400 italic">Tanpa tag tambahan</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button 
                        type="button" 
                        wire:click="closeApproveModal" 
                        class="px-4 py-2 text-xs font-semibold text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="confirmApprove"
                        wire:loading.attr="disabled"
                        wire:target="confirmApprove"
                        class="px-5 py-2 text-xs font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition flex items-center gap-1.5 shadow-xs disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="confirmApprove">Ya, Setujui & Publikasikan</span>
                        <span wire:loading wire:target="confirmApprove">Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL CUSTOM 2: Preview Karya Lengkap (Tengah Layar) --}}
    @if($previewId)
        @php
            $previewItem = \App\Models\KaryaSiswa::with('user')->find($previewId);
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity" wire:click="closePreviewModal"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col border border-slate-200 z-10 overflow-hidden">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                    <div>
                        <span class="text-[10px] font-bold text-sky-600 uppercase tracking-wider">Preview Portofolio</span>
                        <h3 class="text-base font-bold text-slate-900 truncate max-w-lg">{{ $previewItem?->judul }}</h3>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closePreviewModal" 
                        class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body (Scrollable) --}}
                <div class="overflow-y-auto p-6 space-y-6">
                    {{-- Cover Banner --}}
                    @if($previewItem?->gambar_url)
                        <div class="aspect-video w-full rounded-xl overflow-hidden border border-slate-200 bg-slate-100">
                            <img src="{{ $previewItem->gambar_url }}" alt="{{ $previewItem->judul }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    {{-- Meta Info --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200/80 text-xs">
                        <div>
                            <span class="text-slate-400 block mb-0.5">Pembuat</span>
                            <span class="font-semibold text-slate-800">{{ $previewItem?->nama_siswa }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Kelas</span>
                            <span class="font-semibold text-slate-800">{{ $previewItem?->kelas ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Tahun Proyek</span>
                            <span class="font-semibold text-slate-800">{{ $previewItem?->tahun ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Kategori</span>
                            <span class="font-semibold text-slate-800">{{ ucfirst($previewItem?->kategori ?? '-') }}</span>
                        </div>
                    </div>

                    {{-- Tautan URL --}}
                    @if($previewItem?->url_demo || $previewItem?->url_repo)
                        <div class="flex flex-wrap gap-3">
                            @if($previewItem->url_demo)
                                <a href="{{ $previewItem->url_demo }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 text-sky-700 border border-sky-200 rounded-lg text-xs font-semibold hover:bg-sky-100">
                                    🔗 Buka Live Demo ↗
                                </a>
                            @endif
                            @if($previewItem->url_repo)
                                <a href="{{ $previewItem->url_repo }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg text-xs font-semibold hover:bg-slate-200">
                                    📦 Repositori Kode ↗
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Deskripsi Konten Lengkap --}}
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Deskripsi Proyek</h4>
                        <div class="prose prose-sm max-w-none p-4 rounded-xl border border-slate-200 bg-white">
                            {!! $previewItem?->deskripsi_html !!}
                        </div>
                    </div>

                    {{-- Screenshots Lengkap --}}
                    @if(count($previewItem?->screenshots ?? []) > 0)
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Tangkapan Layar (Screenshots)</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($previewItem->screenshots as $s)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($s) }}" target="_blank" class="aspect-video rounded-lg overflow-hidden border border-slate-200 bg-slate-50 hover:opacity-90">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($s) }}" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Berkas Bukti --}}
                    @if(count($previewItem?->bukti ?? []) > 0)
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Berkas Bukti Orisinalitas</h4>
                            <div class="space-y-1.5">
                                @foreach($previewItem->bukti as $b)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($b) }}" target="_blank" class="flex items-center justify-between p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 hover:bg-slate-100">
                                        <span class="truncate">{{ basename($b) }}</span>
                                        <span class="text-sky-600 font-medium shrink-0 ml-2">Unduh / Buka ↗</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-3.5 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
                    <span class="text-xs text-slate-500">Status: <strong class="text-amber-600">Review</strong></span>
                    <button 
                        type="button" 
                        wire:click="closePreviewModal" 
                        class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 transition shadow-2xs"
                    >
                        Tutup Preview
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL CUSTOM 3: Alasan Penolakan --}}
    @if($rejectingId)
        @php($karyaDitolak = \App\Models\KaryaSiswa::find($rejectingId))
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity" wire:click="closeRejectModal"></div>
            <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 border border-slate-200 z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-10 w-10 rounded-full bg-rose-50 border border-rose-200 flex items-center justify-center flex-shrink-0 text-rose-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Tolak Karya / Minta Perbaikan</h3>
                        <p class="text-xs text-slate-500">Siswa akan menerima catatan ini di dashboard miliknya</p>
                    </div>
                </div>

                <p class="text-xs text-slate-600 mb-2">
                    Beritahukan kepada <strong>{{ $karyaDitolak?->nama_siswa }}</strong> apa yang kurang dari pengajuan <strong>"{{ $karyaDitolak?->judul }}"</strong>:
                </p>

                <textarea
                    wire:model="rejectNotes.{{ $rejectingId }}"
                    rows="4"
                    maxlength="1000"
                    placeholder="Contoh: Lampirkan link repository GitHub yang valid dan perjelas teknologi yang digunakan pada deskripsi..."
                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:outline-none"
                ></textarea>
                @error("rejectNotes.$rejectingId") <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror

                <div class="flex items-center justify-end gap-3 mt-4">
                    <button type="button" wire:click="closeRejectModal" class="px-4 py-2 border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="confirmReject"
                        wire:loading.attr="disabled"
                        wire:target="confirmReject"
                        class="px-4 py-2 bg-rose-600 text-white rounded-lg text-xs font-semibold hover:bg-rose-700 transition disabled:opacity-60 shadow-xs"
                    >
                        Kirim Penolakan & Kembalikan ke Draft
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
