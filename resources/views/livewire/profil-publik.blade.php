<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    <div class="max-w-5xl mx-auto px-4 py-8 md:py-12">
        {{-- Tombol kembali ke editor (hanya untuk pemilik profil yang datang dari editor) --}}
        @if(auth()->check() && auth()->id() === $profileUser->id && request()->query('from') === 'editor')
            <div class="mb-4 flex justify-end">
                <button
                    type="button"
                    onclick="window.close(); if(!window.closed) window.location.href = '{{ route('siswa.profil-publik') }}';"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg text-xs font-semibold shadow-xs transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>{{ __('Kembali ke Edit Profil') }}</span>
                </button>
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-8">

            {{-- Sidebar: Avatar & Info --}}
            <div class="space-y-5">
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800 text-center md:text-left">
                    <div class="w-32 h-32 mx-auto md:mx-0 rounded-2xl overflow-hidden bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 shadow-xs">
                        @if($profileUser->photo_url)
                            <img src="{{ $profileUser->photo_url }}" alt="{{ $profileUser->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 flex items-center justify-center text-3xl font-bold">
                                {{ $profileUser->initials() }}
                            </div>
                        @endif
                    </div>

                    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-50 mt-4">{{ $profileUser->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ '@' . ($profileUser->username ?: $profileUser->id) }}</p>

                    @if(auth()->check() && auth()->id() === $profileUser->id)
                        <a
                            href="{{ route('siswa.profil-publik') }}"
                            class="mt-4 inline-flex w-full items-center justify-center gap-1.5 px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-semibold shadow-xs transition"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>{{ __('Edit Profil') }}</span>
                        </a>
                    @endif

                    @if($profileUser->bio)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">{{ $profileUser->bio }}</p>
                    @endif

                    {{-- Info sekolah --}}
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 space-y-2 text-xs text-gray-600 dark:text-gray-300">
                        @if($profileUser->kelas)
                            <p class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>{{ __('Kelas :kelas', ['kelas' => $profileUser->kelas]) }}</span>
                            </p>
                        @endif
                        @if($showEmail)
                            <p class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:{{ $profileUser->email }}" class="truncate hover:text-sky-600 transition">{{ $profileUser->email }}</a>
                            </p>
                        @endif
                        <p class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>{{ __('SMK — Jurusan RPL') }}</span>
                        </p>
                    </div>

                    {{-- Keahlian Terverifikasi --}}
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">{{ __('Keahlian & Tech Stack') }}</p>
                            @if(auth()->check() && auth()->id() === $profileUser->id)
                                <a href="{{ route('siswa.profil-publik') }}" class="text-[10px] text-sky-600 dark:text-sky-400 hover:underline">{{ __('Edit') }}</a>
                            @endif
                        </div>
                        @if(count($profileUser->keahlian_list) > 0)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($profileUser->keahlian_list as $skill)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-500/30 rounded-md text-[11px] font-medium">
                                        <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ $skill }}</span>
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">{{ __('Belum ada tag keahlian terdaftar.') }}</p>
                        @endif
                    </div>

                    {{-- Saluran Kontak --}}
                    @php
                        $channels = [
                            'instagram' => ['label' => 'Instagram', 'bg' => 'bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600', 'path' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z'],
                            'linkedin' => ['label' => 'LinkedIn', 'bg' => 'bg-[#0077b5]', 'path' => 'M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z'],
                            'github' => ['label' => 'GitHub', 'bg' => 'bg-[#24292e]', 'path' => 'M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z'],
                            'youtube' => ['label' => 'YouTube', 'bg' => 'bg-red-600', 'path' => 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'],
                            'tiktok' => ['label' => 'TikTok', 'bg' => 'bg-black', 'path' => 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z'],
                            'twitter' => ['label' => 'Twitter/X', 'bg' => 'bg-slate-900', 'path' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
                        ];
                        $activeChannels = collect($channels)->filter(fn ($c, $k) => !empty($saluran[$k]['active']) && !empty($saluran[$k]['url']));
                    @endphp
                    @if($activeChannels->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('Terhubung') }}</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($activeChannels as $key => $ch)
                                    <a
                                        href="{{ $saluran[$key]['url'] }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        title="{{ $ch['label'] }}"
                                        class="w-8 h-8 rounded-lg {{ $ch['bg'] }} text-white flex items-center justify-center hover:opacity-80 transition"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $ch['path'] }}"/></svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Kontak tampil di profil (WhatsApp dkk) --}}
                @if(!empty($saluran['whatsapp']['active']) && !empty($saluran['whatsapp']['url']))
                    <a
                        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $saluran['whatsapp']['url']) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-sm font-semibold shadow-xs transition"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <span>{{ __('Hubungi via WhatsApp') }}</span>
                    </a>
                @endif
            </div>

            {{-- Main Content --}}
            <div class="space-y-6 min-w-0">
                {{-- README Profil (ala GitHub profile README) --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800 overflow-hidden">
                    <div class="flex items-center gap-2 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ ($profileUser->username ?: $profileUser->id) }} / README</span>
                    </div>
                    <div class="p-6">
                        @if($readmeHtml)
                            <div class="prose prose-sm prose-slate max-w-none">{!! $readmeHtml !!}</div>
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-400 italic">{{ __('Siswa ini belum menambahkan README.') }}</p>
                        @endif
                    </div>
                </div>

                {{-- Karya Published --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-bold text-gray-900 dark:text-gray-50">{{ __('Karya & Proyek') }}</h2>
                        <span class="text-xs text-gray-400 dark:text-gray-400">{{ $karya->count() }} {{ __('karya') }}</span>
                    </div>

                    @if($karya->isEmpty())
                        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-8 text-center">
                            <p class="text-sm text-gray-400 dark:text-gray-400">{{ __('Belum ada karya yang dipublikasikan.') }}</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($karya as $item)
                                <a href="{{ route('karya.detail', $item->id) }}" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-md hover:border-sky-200 transition group">
                                    <div class="aspect-video bg-slate-100 overflow-hidden flex items-center justify-center">
                                        @if($item->gambar_url)
                                            <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-300">
                                        @else
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-50 truncate group-hover:text-sky-600 transition">{{ $item->judul }}</h3>
                                        @if(count($item->tags ?? []))
                                            <div class="flex flex-wrap gap-1 mt-1.5">
                                                @foreach(array_slice($item->tags, 0, 3) as $tag)
                                                    <span class="px-1.5 py-0.5 bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-100 dark:border-sky-500/30 rounded text-[10px] font-medium">{{ $tag }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $item->deskripsi_singkat ?: $item->deskripsi_excerpt }}</p>
                                        @if($item->teknologi && is_array($item->teknologi) && count($item->teknologi))
                                            <div class="flex flex-wrap gap-1 mt-2.5">
                                                @foreach(array_slice($item->teknologi, 0, 4) as $tech)
                                                    <span class="inline-flex px-2 py-0.5 bg-sky-50 dark:bg-sky-500/10 text-sky-700 border border-sky-200/60 rounded text-[10px] font-medium">{{ $tech }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Prestasi --}}
                @if($prestasi->isNotEmpty())
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-base font-bold text-gray-900 dark:text-gray-50">{{ __('Prestasi') }}</h2>
                            <span class="text-xs text-gray-400 dark:text-gray-400">{{ $prestasi->count() }} {{ __('prestasi') }}</span>
                        </div>
                        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 divide-y divide-gray-50">
                            @foreach($prestasi as $item)
                                <div class="flex items-start gap-4 p-4">
                                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-500 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-50">{{ $item->judul }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $item->tingkat ?? '-' }}
                                            @if($item->penyelenggara) • {{ $item->penyelenggara }} @endif
                                            @if($item->tanggal_prestasi) • {{ $item->tanggal_prestasi->translatedFormat('M Y') }} @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
