<div class="min-h-screen bg-gray-50 dark:bg-gray-800">
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
                    <span>Kembali ke Edit Profil</span>
                </button>
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-8">

            {{-- Sidebar: Avatar & Info --}}
            <div class="space-y-5">
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800 text-center md:text-left">
                    <div class="w-32 h-32 mx-auto md:mx-0 rounded-full overflow-hidden bg-slate-100 ring-4 ring-white shadow-md">
                        @if($profileUser->photo_url)
                            <img src="{{ $profileUser->photo_url }}" alt="{{ $profileUser->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold">
                                {{ $profileUser->initials() }}
                            </div>
                        @endif
                    </div>

                    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-50 mt-4">{{ $profileUser->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ '@' . ($profileUser->username ?: $profileUser->id) }}</p>

                    @if($profileUser->headline)
                        <p class="text-sm text-gray-700 dark:text-gray-200 mt-3 leading-relaxed">{{ $profileUser->headline }}</p>
                    @endif

                    @if($profileUser->bio)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">{{ $profileUser->bio }}</p>
                    @endif

                    {{-- Keahlian --}}
                    @if(count($keahlianList))
                        <div class="mt-4">
                            <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider mb-2">Keahlian</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($keahlianList as $skill)
                                    <span class="inline-flex items-center px-2.5 py-1 bg-sky-50 dark:bg-sky-500/10 text-sky-700 border border-sky-200/60 rounded-md text-[11px] font-medium">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Info sekolah --}}
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 space-y-2 text-xs text-gray-600 dark:text-gray-300">
                        @if($profileUser->kelas)
                            <p class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>Kelas {{ $profileUser->kelas }}</span>
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
                            <span>SMK — Jurusan RPL</span>
                        </p>
                    </div>

                    {{-- Saluran Kontak --}}
                    @php
                        $channels = [
                            'instagram' => ['label' => 'Instagram', 'bg' => 'bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600'],
                            'linkedin' => ['label' => 'LinkedIn', 'bg' => 'bg-[#0077b5]'],
                            'github' => ['label' => 'GitHub', 'bg' => 'bg-[#24292e]'],
                            'youtube' => ['label' => 'YouTube', 'bg' => 'bg-red-600'],
                            'tiktok' => ['label' => 'TikTok', 'bg' => 'bg-black'],
                            'twitter' => ['label' => 'Twitter/X', 'bg' => 'bg-slate-900'],
                        ];
                        $activeChannels = collect($channels)->filter(fn ($c, $k) => !empty($saluran[$k]['active']) && !empty($saluran[$k]['url']));
                    @endphp
                    @if($activeChannels->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider mb-2">Terhubung</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($activeChannels as $key => $ch)
                                    <a
                                        href="{{ $saluran[$key]['url'] }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        title="{{ $ch['label'] }}"
                                        class="w-8 h-8 rounded-lg {{ $ch['bg'] }} text-white flex items-center justify-center text-[9px] font-bold hover:opacity-80 transition"
                                    >{{ strtoupper(substr($ch['label'], 0, 2)) }}</a>
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
                        <span>Hubungi via WhatsApp</span>
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
                            <p class="text-sm text-gray-400 dark:text-gray-400 italic">Siswa ini belum menambahkan README.</p>
                        @endif
                    </div>
                </div>

                {{-- Karya Published --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-bold text-gray-900 dark:text-gray-50">Karya & Proyek</h2>
                        <span class="text-xs text-gray-400 dark:text-gray-400">{{ $karya->count() }} karya</span>
                    </div>

                    @if($karya->isEmpty())
                        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-8 text-center">
                            <p class="text-sm text-gray-400 dark:text-gray-400">Belum ada karya yang dipublikasikan.</p>
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
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $item->deskripsi_excerpt }}</p>
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
                            <h2 class="text-base font-bold text-gray-900 dark:text-gray-50">Prestasi</h2>
                            <span class="text-xs text-gray-400 dark:text-gray-400">{{ $prestasi->count() }} prestasi</span>
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
                                            @if($item->tanggal_prestasi) • {{ $item->tanggal_prestasi->format('M Y') }} @endif
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
