<div class="p-6 max-w-5xl mx-auto space-y-6" x-data>
    {{-- Toast Notifikasi Sukses --}}
    @if (session()->has('message'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 right-6 z-[60] w-auto max-w-sm"
            role="status"
            aria-live="polite"
        >
            <div class="flex items-center gap-3 bg-emerald-600 text-white rounded-xl shadow-lg px-4 py-3">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/20 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <span class="text-sm font-medium">{{ session('message') }}</span>
                <button type="button" @click="show = false" class="ml-1 text-white/70 hover:text-white shrink-0 cursor-pointer" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Toast Error Notifikasi --}}
    @if (session()->has('error'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 right-6 z-[60] w-auto max-w-sm"
            role="alert"
        >
            <div class="flex items-center gap-3 bg-rose-600 text-white rounded-xl shadow-lg px-4 py-3">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/20 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
                <span class="text-sm font-medium">{{ session('error') }}</span>
                <button type="button" @click="show = false" class="ml-1 text-white/70 hover:text-white shrink-0 cursor-pointer" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            {{-- Avatar dengan tombol edit --}}
            <div class="relative shrink-0 group">
                <div class="w-16 h-16 rounded-full overflow-hidden bg-slate-100 dark:bg-gray-800 ring-2 ring-gray-200 dark:ring-gray-700 shadow-xs">
                    @if($user->photo_url)
                        <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white text-xl font-bold">
                            {{ $user->initials() }}
                        </div>
                    @endif
                </div>
                <button
                    type="button"
                    wire:click="openEditModal"
                    class="absolute -bottom-1 -right-1 w-7 h-7 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-full flex items-center justify-center shadow-md transition cursor-pointer"
                    title="Ganti foto profil"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </button>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-50 tracking-tight">{{ __('Hias Profil Publik') }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-200/70 dark:border-sky-500/20">
                        {{ __('Siswa RPL') }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Kelola tampilan portofolio kamu agar menarik dan memikat HRD serta dunia industri.') }}</p>

                {{-- Keahlian / Tech Stack Badges --}}
                @if(count($user->keahlian_list) > 0)
                    <div class="flex flex-wrap items-center gap-1.5 mt-2">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mr-0.5 flex items-center gap-1">
                            <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                            <span>Tech Stack:</span>
                        </span>
                        @foreach($user->keahlian_list as $skill)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-200/70 dark:border-sky-500/20 rounded-md text-[11px] font-medium">
                                <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $skill }}</span>
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a
                href="{{ $user->profil_url }}?from=editor"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg text-xs font-semibold transition shrink-0 shadow-xs"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>{{ __('Lihat Profil Publik') }}</span>
            </a>
        </div>
    </div>

    {{-- Indikator Kelengkapan Profil (Progress Tracker) --}}
    @php
        $completion = $this->profileCompletion;
        $pct = $completion['percentage'];
    @endphp
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-7 h-7 rounded-xl {{ $pct >= 80 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : ($pct >= 50 ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300') }} font-bold text-xs">
                    {{ $pct }}%
                </span>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-50">{{ __('Tingkat Kelengkapan Profil') }}</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-400">{{ $completion['completed'] }} {{ __('dari') }} {{ $completion['total'] }} {{ __('bagian profil sudah kamu lengkapi.') }}</p>
                </div>
            </div>
            <div class="text-[11px] font-medium text-gray-500 dark:text-gray-400">
                @if($pct === 100)
                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold inline-flex items-center gap-1">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Luar biasa! Profilmu sudah 100% lengkap & siap dilirik industri.') }}
                    </span>
                @else
                    <span>{{ __('Lengkapi semua bagian untuk portofolio magang/PKL yang maksimal!') }}</span>
                @endif
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden mb-3.5">
            <div
                class="h-full rounded-full transition-all duration-500 {{ $pct >= 80 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-500' : 'bg-[#0284c7]') }}"
                style="width: {{ $pct }}%"
            ></div>
        </div>

        {{-- Checklist Badges --}}
        <div class="flex flex-wrap items-center gap-2">
            @foreach($completion['items'] as $label => $isDone)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium border {{ $isDone ? 'bg-emerald-50/70 dark:bg-emerald-500/10 border-emerald-200/80 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-300' : 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400' }}">
                    @if($isDone)
                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __($label) }}</span>
                    @else
                        <span class="w-3 h-3 rounded-full border border-gray-300 dark:border-gray-600 inline-block shrink-0"></span>
                        <span class="text-gray-600 dark:text-gray-300">{{ __($label) }}</span>
                    @endif
                </span>
            @endforeach
        </div>
    </div>

    {{-- Link Profil Publik Card --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-1.5">
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">{{ __('Link Profil Publik Portofolio Kamu') }}</span>
                <x-info-popover
                    title="{{ __('Tentang Link Profil Publik') }}"
                    example="{{ $user->profil_url }}"
                    tips="{{ __('Cantumkan link ini di surat lamaran PKL, CV resume, LinkedIn, atau bio media sosial kamu agar guru pembimbing dan HRD perusahaan langsung bisa melihat karyamu.') }}"
                >
                    {{ __('Ini adalah tautan web resmi profil portofolio kamu di CMS Jurusan RPL SMKN 1 Bantul. Tautan ini bersifat publik dan bisa dibuka oleh siapa saja tanpa harus login.') }}
                </x-info-popover>
            </div>
            <span class="text-[11px] text-gray-400 dark:text-gray-400">{{ __('Siap dibagikan ke HRD & industri') }}</span>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <div class="flex-1 flex items-center bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-200 min-w-0">
                <svg class="w-4 h-4 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                <code class="text-xs sm:text-sm font-mono truncate select-all">{{ $user->profil_url }}</code>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button
                    type="button"
                    x-data
                    @click="
                        navigator.clipboard.writeText('{{ $user->profil_url }}');
                        $el.textContent = '{{ __('Tersalin!') }}';
                        setTimeout(() => $el.textContent = '{{ __('Salin Link') }}', 2000);
                    "
                    class="flex-1 sm:flex-none px-4 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg text-xs font-semibold transition cursor-pointer"
                >{{ __('Salin Link') }}</button>
                <a
                    href="{{ $user->profil_url }}"
                    target="_blank"
                    class="px-3 py-2.5 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold transition"
                    title="{{ __('Buka di tab baru') }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Main Grid: Biodata & README --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Kolom Kiri: Form Biodata --}}
        <form wire:submit="saveBiodata" class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800 flex flex-col justify-between space-y-4">
            <div class="space-y-4">
                <div class="pb-3 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-50">{{ __('Biodata Singkat') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Informasi utama yang langsung terlihat di bagian atas kartu profil kamu.') }}</p>
                </div>

                {{-- Bio Field --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-1.5">
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200">{{ __('Bio Singkat') }}</label>
                            <x-info-popover
                                title="Panduan Menulis Bio Singkat"
                                example="Siswa Rekayasa Perangkat Lunak SMKN 1 Bantul. Fokus mendalami Frontend Web Development dan UI Design. Terbiasa membangun antarmuka web dengan Tailwind CSS dan Vue.js. Siap berkontribusi dalam tim magang industri."
                                tips="Tulis ringkasan 2–3 kalimat tentang minat teknologimu, apa yang sedang kamu pelajari, dan kesiapanmu untuk magang atau berkarier di bidang IT."
                            >
                                Bio adalah perkenalan singkat yang pertama kali dibaca pengunjung. Buatlah bernada positif, percaya diri, dan mencerminkan bidang yang kamu sukai di RPL.
                            </x-info-popover>
                        </div>
                        <span class="text-[10px] text-gray-400">{{ __('Maks. 500 karakter') }}</span>
                    </div>
                    <textarea
                        wire:model="bio"
                        rows="3"
                        maxlength="500"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none leading-relaxed"
                        placeholder="{{ __('Ceritakan minat belajar, teknologi favorit, dan tujuan karier kamu...') }}"
                    ></textarea>
                    @error('bio') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                </div>

                {{-- Keahlian (Skills) Field with Live Badge Preview --}}
                <div x-data="{
                    get tags() {
                        const val = $wire.keahlian || '';
                        return val.split(',').map(t => t.trim()).filter(t => t.length > 0);
                    },
                    addTag(tag) {
                        let current = ($wire.keahlian || '').split(',').map(s => s.trim()).filter(Boolean);
                        if (!current.includes(tag)) {
                            current.push(tag);
                            $wire.keahlian = current.join(', ');
                        }
                    }
                }">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-1.5">
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200">{{ __('Keahlian / Tech Stack') }}</label>
                            <x-info-popover
                                title="Panduan Menulis Keahlian (Skills)"
                                example="HTML5, CSS3, JavaScript, PHP, Laravel, Tailwind CSS, Figma, Git, MySQL"
                                tips="Tuliskan bahasa pemrograman, framework, atau tools desain yang pernah kamu pelajari di sekolah maupun proyek mandiri. Pisahkan setiap keahlian dengan tanda koma."
                            >
                                Setiap kata yang kamu pisahkan dengan tanda koma (,) akan otomatis diubah menjadi badge lencana keahlian yang rapi di profil publikmu.
                            </x-info-popover>
                        </div>
                        <span class="text-[10px] text-gray-400">{{ __('Pisahkan dengan koma (,)') }}</span>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.250ms="keahlian"
                        maxlength="255"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        placeholder="Contoh: Laravel, Tailwind CSS, Vue.js, Figma, Git"
                    >
                    @error('keahlian') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror

                    {{-- Quick Presets --}}
                    <div class="mt-2 flex flex-wrap items-center gap-1">
                        <span class="text-[10px] text-gray-400">{{ __('Pilihan cepat:') }}</span>
                        @foreach(['PHP', 'Laravel', 'Tailwind CSS', 'JavaScript', 'Vue.js', 'MySQL', 'Flutter', 'Figma', 'Python'] as $preset)
                            <button 
                                type="button" 
                                @click="addTag('{{ $preset }}')"
                                class="px-2 py-0.5 rounded text-[10px] bg-slate-100 dark:bg-gray-800 hover:bg-sky-50 dark:hover:bg-sky-500/10 hover:text-sky-700 dark:hover:text-sky-400 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 transition cursor-pointer"
                            >
                                + {{ $preset }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Live Tags Preview --}}
                    <div class="mt-2.5 p-3 bg-gray-50/80 dark:bg-gray-800/60 rounded-xl border border-gray-200/70 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Preview Badge di Profil Publik:') }}</span>
                            <span class="text-[10px] text-gray-400" x-text="tags.length + ' ' + @js(__('keahlian terdeteksi'))"></span>
                        </div>
                        <template x-if="tags.length > 0">
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="(tag, idx) in tags" :key="idx">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-200 dark:border-sky-500/20">
                                        <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="tag"></span>
                                    </span>
                                </template>
                            </div>
                        </template>
                        <template x-if="tags.length === 0">
                            <p class="text-xs text-gray-400 italic">{{ __('Ketik keahlian di atas (pisahkan koma) untuk melihat lencana preview di sini.') }}</p>
                        </template>
                    </div>
                </div>

                {{-- Username Kustom Field --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-1.5">
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200">{{ __('Username Kustom') }}</label>
                            <x-info-popover
                                title="Aturan & Batasan Username Kustom"
                                example="ahmad-syahputra"
                                tips="Gunakan nama lengkap atau nama profesionalmu. Hindari nama samaran yang tidak resmi karena profil ini akan dilihat oleh HRD industri dan guru pembimbing."
                            >
                                Username dipakai untuk alamat link web portofoliomu (<span class="font-mono text-gray-800 dark:text-gray-200">skansaba.dev/profil/username</span>). Hanya boleh huruf kecil, angka, dan tanda hubung (-).
                                <br><br>
                                <strong>Ketentuan Penting:</strong> Demi menjaga agar link yang sudah kamu sebarkan di CV tidak rusak, username hanya bisa diubah <strong>1 kali setiap 7 hari</strong>.
                            </x-info-popover>
                        </div>
                        @if(! $user->canChangeUsername())
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 rounded-full border border-amber-200 dark:border-amber-500/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                {{ __('Terkunci (Cooldown 7 hari)') }}
                            </span>
                        @else
                            <span class="text-[10px] text-gray-400">{{ __('Bisa diubah sekarang') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-0">
                        <span class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-lg select-none">/profil/</span>
                        <input
                            type="text"
                            wire:model="username"
                            maxlength="50"
                            @class([
                                'flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-r-lg focus:outline-none',
                                'bg-gray-100 dark:bg-gray-800/80 text-gray-500 cursor-not-allowed' => ! $user->canChangeUsername(),
                                'dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-sky-500' => $user->canChangeUsername(),
                            ])
                            placeholder="ahmad-syahputra"
                            @disabled(! $user->canChangeUsername())
                            @readonly(! $user->canChangeUsername())
                        >
                    </div>

                    @if(! $user->canChangeUsername())
                        <div class="mt-2 flex items-start gap-2 p-2.5 bg-amber-50/80 dark:bg-amber-500/10 border border-amber-200/70 dark:border-amber-500/20 rounded-lg text-amber-800 dark:text-amber-300 text-xs">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <div>
                                <p class="font-medium">{{ __('Username terkunci sementara') }}</p>
                                <p class="text-[11px] text-amber-700 dark:text-amber-400 mt-0.5 leading-relaxed">
                                    {{ __('Terakhir diubah pada :date.', ['date' => $user->username_changed_at->translatedFormat('d M Y, H:i')]) }}
                                    {{ __('Kamu bisa menggantinya lagi dalam :days hari (:target).', ['days' => $user->daysUntilNextUsernameChange(), 'target' => $user->usernameAvailableAgainAt()->translatedFormat('d F Y')]) }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-[11px] text-gray-400 mt-1">{{ __('Gunakan huruf kecil, angka, dan tanda hubung (-). Setiap kali diganti, akan terkunci 7 hari.') }}</p>
                    @endif
                    @error('username') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <span class="text-xs text-gray-400">{{ __('Klik simpan untuk memperbarui') }}</span>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="px-5 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg text-xs font-semibold transition cursor-pointer flex items-center gap-1.5 shadow-xs disabled:opacity-50"
                >
                    <span wire:loading wire:target="saveBiodata" class="inline-block animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full"></span>
                    <span>{{ __('Simpan Biodata') }}</span>
                </button>
            </div>
        </form>

        {{-- Kolom Kanan: README Profil --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
            <div class="flex-1 flex flex-col min-h-0">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800 mb-4">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-50">{{ __('README Profil') }}</h3>
                        <x-info-popover
                            title="Panduan README Profil Portofolio"
                            tips="Gunakan tombol template di dalam editor README untuk memuat struktur perkenalan, daftar keahlian, proyek sekolah, dan kontak secara instan!"
                        >
                            README adalah lembar portofolio utama tempat kamu bebas menceritakan riwayat belajar, pengalaman proyek, dan keterampilan diri dengan format Markdown (seperti profil GitHub developer).
                            <br><br>
                            Kamu juga bisa memasukkan gambar SVG, tabel tech stack, tautan media sosial, hingga kartu statistik profil.
                        </x-info-popover>
                    </div>
                    <span class="text-[10px] font-semibold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-500/10 px-2 py-0.5 rounded-full border border-sky-200 dark:border-sky-500/20 shrink-0">
                        Markdown GitHub
                    </span>
                </div>

                {{-- Live README Preview Box (Diperluas kebawah mengisi ruang kosong) --}}
                @if($user->profil_readme)
                    <div class="flex-1 min-h-[380px] md:min-h-[460px] max-h-[640px] overflow-y-auto bg-gray-50/60 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800 rounded-xl p-5 prose prose-sm prose-slate dark:prose-invert max-w-none shadow-2xs">
                        {!! app(\App\Services\MarkdownService::class)->parseReadme($user->profil_readme) !!}
                    </div>
                    <p class="text-[11px] text-gray-400 mt-2 flex items-center gap-1 shrink-0">
                        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __('README aktif (:count karakter). Tampil di halaman profil publikmu.', ['count' => strlen($user->profil_readme)]) }}</span>
                    </p>
                @else
                    <div class="flex-1 min-h-[380px] md:min-h-[460px] flex flex-col items-center justify-center text-center p-8 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-xl bg-slate-50/50 dark:bg-gray-800/40">
                        <div class="w-12 h-12 rounded-full bg-sky-50 dark:bg-sky-500/10 text-sky-500 flex items-center justify-center mb-2.5 border border-sky-100 dark:border-sky-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-200 font-semibold">{{ __('README Profil Masih Kosong') }}</p>
                        <p class="text-xs text-gray-400 mt-1 max-w-[280px] leading-relaxed">
                            {{ __('Belum tahu cara menulis README? Tenang, klik tombol di bawah untuk langsung menggunakan template siap pakai!') }}
                        </p>
                    </div>
                @endif
            </div>

            <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-800 shrink-0">
                <button
                    type="button"
                    wire:click="openEditorModal"
                    class="w-full px-4 py-2.5 {{ $user->profil_readme ? 'bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200' : 'bg-[#0284c7] hover:bg-[#0369a1] text-white shadow-xs' }} rounded-lg text-xs font-semibold transition cursor-pointer flex items-center justify-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>{{ $user->profil_readme ? __('Edit & Perbarui README') : __('Tulis README dengan Template') }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Aset SVG README Section --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800 mb-5 gap-2">
            <div class="flex items-center gap-1.5">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-50">{{ __('Aset Gambar SVG README') }}</h3>
                <x-info-popover
                    title="Panduan Aset SVG & Cara Memakainya"
                    example='<img src="URL-SVG-KAMU" alt="Badge Tech" />'
                    tips="Gunakan gambar SVG untuk kartu profil ASCII, contribution graph, atau logo teknologi. File SVG tajam dan ukurannya sangat hemat kuota."
                >
                    SVG (Scalable Vector Graphics) adalah format gambar vektor yang tidak akan pecah saat diperbesar.
                    <br><br>
                    <strong>Cara Pakai:</strong>
                    <ol class="list-decimal list-inside space-y-1 mt-1 text-[10px]">
                        <li>Tarik file .svg ke area upload di sebelah kiri.</li>
                        <li>Klik tombol <strong>Salin</strong> pada daftar aset yang muncul.</li>
                        <li>Buka editor README profil kamu, lalu tempel (Paste) snippet tersebut.</li>
                    </ol>
                </x-info-popover>
            </div>
            <span class="text-[10px] font-semibold text-violet-700 dark:text-violet-400 bg-violet-50 dark:bg-violet-500/10 px-2.5 py-1 rounded-full border border-violet-200/70 dark:border-violet-500/20 shrink-0">
                {{ __('Maksimal :max File · 1 MB per file', ['max' => \App\Services\SvgService::MAX_FILES_PER_USER]) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Dropzone Upload Interaktif --}}
            <div
                x-data="{ isDropping: false }"
                class="space-y-3"
            >
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200">{{ __('Tarik & Lepas File SVG ke Sini') }}</label>

                <div
                    @dragover.prevent="isDropping = true"
                    @dragleave.prevent="isDropping = false"
                    @drop.prevent="
                        isDropping = false;
                        const files = $event.dataTransfer.files;
                        if (files.length > 0) {
                            const file = files[0];
                            if (file.name.toLowerCase().endsWith('.svg') || file.type.includes('svg')) {
                                $wire.upload('fileSvg', file);
                            } else {
                                alert('Hanya file berekstensi .svg yang didukung.');
                            }
                        }
                    "
                    @click="$refs.svgInput.click()"
                    :class="isDropping ? 'border-sky-500 bg-sky-50/80 dark:bg-sky-500/10 ring-2 ring-sky-400/30' : 'border-gray-200 dark:border-gray-700 hover:border-sky-400 bg-slate-50/60 dark:bg-gray-800/50 hover:bg-sky-50/20 dark:hover:bg-sky-500/5'"
                    class="border-2 border-dashed rounded-2xl p-6 text-center transition cursor-pointer flex flex-col items-center justify-center min-h-[170px]"
                >
                    <input
                        type="file"
                        x-ref="svgInput"
                        accept=".svg,image/svg+xml"
                        wire:model="fileSvg"
                        class="hidden"
                    >

                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-800 shadow-xs border border-gray-100 dark:border-gray-700 flex items-center justify-center text-sky-600 dark:text-sky-400 mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>

                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                        <span class="text-sky-600 dark:text-sky-400 underline underline-offset-2">{{ __('Klik untuk memilih file') }}</span> {{ __('atau seret file SVG ke sini') }}
                    </p>
                    <p class="text-[11px] text-gray-400 mt-1">
                        {{ __('Hanya file format .svg (Maksimal 1 MB)') }}
                    </p>
                </div>

                {{-- Status Uploading & Tombol Konfirmasi --}}
                <div wire:loading wire:target="fileSvg" class="flex items-center gap-2 p-2.5 bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 rounded-lg text-sky-700 dark:text-sky-400 text-xs">
                    <span class="inline-block animate-spin w-3.5 h-3.5 border-2 border-sky-600 border-t-transparent rounded-full"></span>
                    <span>{{ __('Memproses & memvalidasi file SVG...') }}</span>
                </div>

                @if($fileSvg)
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-xs font-semibold text-emerald-900 dark:text-emerald-300 truncate">{{ $fileSvg->getClientOriginalName() }}</span>
                        </div>
                        <button
                            type="button"
                            wire:click="uploadSvg"
                            wire:loading.attr="disabled"
                            class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition cursor-pointer shrink-0"
                        >
                            <span wire:loading wire:target="uploadSvg">{{ __('Mengunggah...') }}</span>
                            <span wire:loading.remove wire:target="uploadSvg">{{ __('Simpan SVG') }}</span>
                        </button>
                    </div>
                @endif
                @error('fileSvg') <span class="text-rose-500 text-[11px] block">{{ $message }}</span> @enderror
            </div>

            {{-- Daftar Aset Siswa --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-200">{{ __('Daftar Aset SVG Saya') }} ({{ $asetSvgList->count() }}/{{ \App\Services\SvgService::MAX_FILES_PER_USER }})</p>
                    <span class="text-[10px] text-gray-400">{{ __('Klik "Salin" untuk ambil kodenya') }}</span>
                </div>

                @if($asetSvgList->isEmpty())
                    <div class="flex flex-col items-center justify-center text-center py-10 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-xl bg-slate-50/50 dark:bg-gray-800/40">
                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 flex items-center justify-center mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-300">{{ __('Belum ada aset SVG yang diunggah') }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ __('Tarik file SVG ke kotak sebelah kiri untuk menambahkan aset pertamamu.') }}</p>
                    </div>
                @else
                    <ul class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @foreach($asetSvgList as $aset)
                            <li class="flex items-center gap-3 border border-gray-100 dark:border-gray-800 rounded-xl p-2.5 bg-white dark:bg-gray-900 hover:bg-gray-50/70 dark:hover:bg-gray-800 transition">
                                <div class="w-12 h-12 shrink-0 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center justify-center overflow-hidden p-1 shadow-2xs">
                                    <img src="{{ $aset->url }}" alt="{{ $aset->nama_file }}" class="max-w-full max-h-full object-contain">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-100 truncate" title="{{ $aset->nama_file }}">{{ $aset->nama_file }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $aset->size_human }}</p>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button
                                        type="button"
                                        x-data
                                        @click="
                                            navigator.clipboard.writeText($el.dataset.snippet);
                                            $el.textContent = @js(__('Tersalin!'));
                                            setTimeout(() => $el.textContent = @js(__('Salin')), 2000);
                                        "
                                        data-snippet="{{ $aset->snippet }}"
                                        class="px-3 py-1.5 text-xs font-semibold bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 text-sky-700 dark:text-sky-400 rounded-lg hover:bg-sky-100 dark:hover:bg-sky-500/20 transition cursor-pointer"
                                        title="{{ __('Salin snippet HTML untuk README') }}"
                                    >{{ __('Salin') }}</button>
                                    <button
                                        type="button"
                                        wire:click="deleteSvg({{ $aset->id }})"
                                        wire:confirm="{{ __('Hapus aset ini? Snippet di README yang memakainya akan rusak.') }}"
                                        wire:loading.attr="disabled"
                                        class="px-2.5 py-1.5 text-xs font-semibold border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-500/10 transition cursor-pointer"
                                        title="{{ __('Hapus aset') }}"
                                    >{{ __('Hapus') }}</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- README Editor Modal dengan Pilihan Template Siap Pakai --}}
    @if($showEditorModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-3xl w-full shadow-xl border border-gray-100 dark:border-gray-800 flex flex-col max-h-[92vh]">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50">{{ __('Editor README Profil') }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Tulis perkenalan dan portofoliomu menggunakan format Markdown') }}</p>
                    </div>
                    <button type="button" wire:click="closeEditorModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-4">
                    {{-- Alert Notifikasi Template Dimuat --}}
                    @if (session()->has('template_message'))
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-300 rounded-xl text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('template_message') }}</span>
                        </div>
                    @endif

                    {{-- Fitur Pilihan Template Siap Pakai --}}
                    <div class="p-3.5 bg-slate-50 dark:bg-gray-800 border border-slate-200/80 dark:border-gray-700 rounded-xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-800 dark:text-gray-200 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                <span>{{ __('Pilihan Template Instan (Cocok untuk Pemula):') }}</span>
                            </span>
                            <span class="text-[10px] text-slate-400">{{ __('Klik untuk memuat format otomatis') }}</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                wire:click="loadReadmeTemplate('magang')"
                                wire:confirm="{{ __('Muat Template Magang? Teks editor yang ada saat ini akan diganti dengan format template baru.') }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-600 hover:border-sky-400 hover:bg-sky-50 dark:hover:bg-sky-500/10 text-slate-700 dark:text-gray-200 rounded-lg text-xs font-medium transition cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>{{ __('Template Siswa Magang / PKL') }}</span>
                            </button>
                            <button
                                type="button"
                                wire:click="loadReadmeTemplate('developer')"
                                wire:confirm="{{ __('Muat Template Developer? Teks editor yang ada saat ini akan diganti dengan format template baru.') }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-600 hover:border-sky-400 hover:bg-sky-50 dark:hover:bg-sky-500/10 text-slate-700 dark:text-gray-200 rounded-lg text-xs font-medium transition cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                <span>{{ __('Template Developer GitHub') }}</span>
                            </button>
                        </div>
                    </div>

                    {{-- Markdown Editor Component --}}
                    <x-markdown-editor
                        modelName="profil_readme"
                        label="{{ __('Isi Markdown README') }}"
                        placeholder="{{ __('# Tuliskan judul portofoliomu di sini...') }}"
                        :rows="13"
                        :required="false"
                    />
                    @error('profil_readme') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror

                    {{-- Sisipkan Aset SVG ke README --}}
                    @if($asetSvgList->isNotEmpty())
                        <div class="border border-gray-100 dark:border-gray-800 rounded-xl p-3 bg-gray-50/60 dark:bg-gray-800/60">
                            <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('Sisipkan Aset SVG Kamu ke Kursor Editor') }}</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($asetSvgList as $aset)
                                    <button
                                        type="button"
                                        x-data
                                        @click="
                                            const snippet = {{ Js::from($aset->snippet) }};
                                            const modal = $el.closest('.max-w-3xl');
                                            const ta = modal ? modal.querySelector('textarea') : null;
                                            if (ta) {
                                                const nl = String.fromCharCode(10);
                                                const pos = ta.selectionStart ?? ta.value.length;
                                                ta.setRangeText(nl + snippet + nl, pos, pos, 'end');
                                                ta.dispatchEvent(new Event('input', { bubbles: true }));
                                                ta.focus();
                                            }
                                        "
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-500/10 hover:border-sky-200 transition cursor-pointer"
                                        title="{{ __('Klik untuk menyisipkan ke README') }}"
                                    >
                                        <img src="{{ $aset->url }}" alt="" class="w-4 h-4 object-contain">
                                        <span class="text-[11px] font-medium text-gray-700 dark:text-gray-200 max-w-[140px] truncate">{{ $aset->nama_file }}</span>
                                    </button>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-gray-400 mt-2">{{ __('Klik tombol di atas untuk menyisipkan kode gambar ke dalam posisi kursor.') }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="button" wire:click="closeEditorModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition text-sm cursor-pointer">
                        {{ __('Batal') }}
                    </button>
                    <button
                        type="button"
                        wire:click="saveReadme"
                        wire:loading.attr="disabled"
                        class="px-5 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg font-semibold transition text-sm cursor-pointer disabled:opacity-50"
                    >
                        <span wire:loading wire:target="saveReadme" class="inline-block animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                        {{ __('Simpan README') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Edit Foto Profil (Croppie) --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-3xl w-full p-6 shadow-xl border border-gray-100 dark:border-gray-800 space-y-4 max-h-[92vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-50">{{ __('Perbarui Foto Profil') }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Sesuaikan posisi, perbesar, dan atur bingkai foto profil') }}</p>
                    </div>
                    <button type="button" wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form 
                    x-data="{ isSaving: false, errorMessage: '' }"
                    x-on:submit.prevent="
                        if (isSaving) return;
                        errorMessage = '';
                        const form = $el;
                        const cropper = window.imageCropper || form.querySelector('[x-data]')?._cropperInstance;
                        
                        if (!cropper) {
                            errorMessage = @js(__('Komponen foto belum siap. Silakan muat ulang halaman.'));
                            return;
                        }

                        isSaving = true;
                        cropper.getCropResultAsync().then(result => {
                            if (result && typeof result === 'string' && result.startsWith('data:image')) {
                                $wire.savePhoto(result)
                                    .catch(err => {
                                        console.error('Gagal menyimpan foto:', err);
                                        errorMessage = @js(__('Terjadi kesalahan saat menyimpan foto. Silakan coba lagi.'));
                                    })
                                    .finally(() => {
                                        isSaving = false;
                                    });
                            } else {
                                isSaving = false;
                                errorMessage = @js(__('Silakan pilih atau unggah berkas foto profil terlebih dahulu sebelum menyimpan.'));
                            }
                        }).catch(err => {
                            console.error('Gagal mengambil crop:', err);
                            isSaving = false;
                            errorMessage = @js(__('Gagal memproses crop foto. Silakan coba lagi.'));
                        });
                    " 
                    class="space-y-4 text-xs"
                >
                    <template x-if="errorMessage">
                        <div class="p-3 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 rounded-xl text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span x-text="errorMessage"></span>
                        </div>
                    </template>

                    <div>
                        <x-image-cropper
                            modelName="photoBase64"
                            cropDataName="crop_data"
                            label="{{ __('Pilih & Atur Foto Profil') }}"
                            :aspectRatio="1"
                            aspectRatioLabel="1:1 Bulat/Kotak"
                            :maxWidth="500"
                            :maxHeight="500"
                            :currentImage="$user->photo"
                            :previewSize="200"
                            :isAvatar="true"
                            :avatarName="$user->name"
                            :avatarSubtitle="'Siswa RPL • @' . ($user->username ?: $user->id)"
                            :avatarSkills="$user->keahlian_list"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <button type="button" wire:click="closeEditModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition font-medium cursor-pointer">
                            {{ __('Batal') }}
                        </button>
                        <button
                            type="submit"
                            :disabled="isSaving"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg font-semibold transition cursor-pointer flex items-center gap-1.5 shadow-xs disabled:opacity-50"
                        >
                            <span x-show="isSaving" class="inline-block animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                            <span x-text="isSaving ? @js(__('Menyimpan Foto...')) : @js(__('Simpan Foto Profil'))"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Konfirmasi Ganti Username (7 Hari Cooldown) --}}
    @if($showUsernameConfirmModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 dark:border-gray-800 space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-100 dark:border-gray-800">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200/80 dark:border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-50">{{ __('Konfirmasi Ganti Username') }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Perhatikan batasan pergantian username') }}</p>
                    </div>
                </div>

                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                    <p class="font-medium text-gray-800 dark:text-gray-100">
                        {{ __('Kamu yakin ingin pakai username ini? Username tidak akan bisa diganti setelah ini, kamu harus nunggu 7 hari.') }}
                    </p>

                    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 space-y-1 text-xs">
                        <p class="text-gray-500 dark:text-gray-400">{{ __('Username baru:') }}</p>
                        <p class="font-mono font-bold text-sky-700 dark:text-sky-400 break-all">
                            {{ $username ? '@' . $username : __('(Kosong - otomatis pakai ID akun)') }}
                        </p>
                        @if($username)
                            <p class="text-[11px] text-gray-400">{{ __('URL profil:') }} <span class="font-mono text-gray-600 dark:text-gray-300">{{ url('/profil/' . $username) }}</span></p>
                        @endif
                    </div>

                    <p class="text-xs text-amber-800 dark:text-amber-300 bg-amber-50 dark:bg-amber-500/10 p-2.5 rounded-lg border border-amber-200/60 dark:border-amber-500/20 leading-relaxed">
                        {!! __('Setelah disimpan, kamu baru bisa menggantinya lagi pada :date.', ['date' => '<strong>' . now()->addDays(7)->translatedFormat('d F Y') . '</strong>']) !!}
                    </p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                    <button
                        type="button"
                        wire:click="closeUsernameConfirmModal"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition text-sm cursor-pointer"
                    >
                        {{ __('Batal') }}
                    </button>
                    <button
                        type="button"
                        wire:click="confirmSaveBiodata"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg text-sm font-semibold transition cursor-pointer flex items-center gap-1.5 disabled:opacity-50"
                    >
                        <span wire:loading wire:target="confirmSaveBiodata" class="inline-block animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                        <span>{{ __('Ya, Simpan Username') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
