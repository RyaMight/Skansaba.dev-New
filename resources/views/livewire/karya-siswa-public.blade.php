<div class="min-h-screen bg-gray-50 dark:bg-gray-800">
    {{-- Header dengan Breadcrumb --}}
    <section class="bg-white dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-6 text-center">
            {{-- Breadcrumb --}}
            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-300 mb-4">
                <svg class="w-5 h-5" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <span class="hover:opacity-80 cursor-pointer">Beranda</span>
                <span>›</span>
                <span style="color: {{ $globalSettings->primary_color_style }}">Karya Siswa</span>
            </div>
            
            <h1 class="text-3xl lg:text-5xl font-bold text-gray-900 dark:text-gray-50 mb-3">
                Karya <span style="color: {{ $globalSettings->primary_color_style }}">Siswa</span>
            </h1>
            <p class="text-gray-600 dark:text-gray-300 text-lg max-w-4xl mx-auto">Portofolio dan proyek karya siswa RPL</p>
        </div>
    </section>

    {{-- Search Only --}}
    <section class="py-6 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari karya atau nama siswa..."
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:border-transparent text-gray-700 dark:text-gray-200"
                        style="--tw-ring-color: {{ $globalSettings->primary_color_style }}"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- Gambar 1 Showcase & Karya Siswa UI --}}
    <section class="py-10 bg-[#eef2f6]/70 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            {{-- Top Showcase (2 Featured Projects) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Showcase 1: Sistem Informasi Manajemen Sekolah --}}
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 lg:p-7 shadow-xs border border-slate-100 flex flex-col md:flex-row items-center gap-6">
                    {{-- Web Browser Mockup --}}
                    <div class="w-full md:w-[320px] bg-[#161a23] rounded-2xl p-2.5 shadow-md border border-slate-800 text-white shrink-0 select-none">
                        {{-- Browser Window Top Bar --}}
                        <div class="flex items-center gap-2 mb-2 px-1">
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="w-2 h-2 rounded-full bg-[#ff5f56]"></span>
                                <span class="w-2 h-2 rounded-full bg-[#ffbd2e]"></span>
                                <span class="w-2 h-2 rounded-full bg-[#27c93f]"></span>
                            </div>
                            <div class="bg-[#242b3a] text-slate-400 text-[9px] px-2.5 py-0.5 rounded flex-1 text-center font-mono truncate">
                                https://school-sims.edu/portal
                            </div>
                        </div>

                        {{-- Browser Body Content --}}
                        <div class="flex bg-[#161a23] rounded-lg overflow-hidden border border-slate-800/80">
                            {{-- Mini Sidebar --}}
                            <div class="w-22 bg-[#11141c] p-2 flex flex-col gap-1.5 shrink-0 border-r border-slate-800/60">
                                <div class="text-[9px] font-bold text-sky-400 truncate">SMK EduTech</div>
                                <div class="bg-blue-600 text-[8px] text-white px-1.5 py-1 rounded font-medium mt-1">
                                    Dashboard
                                </div>
                                <div class="space-y-1.5 mt-1">
                                    <div class="h-1 bg-slate-700/60 rounded w-10"></div>
                                    <div class="h-1 bg-slate-700/60 rounded w-14"></div>
                                    <div class="h-1 bg-slate-700/60 rounded w-8"></div>
                                </div>
                            </div>

                            {{-- Mini Dashboard (Light background) --}}
                            <div class="flex-1 bg-white dark:bg-gray-900 p-2.5 text-slate-800">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-[9px] font-bold text-slate-800 leading-tight">Dashboard Ringkasan</div>
                                    <span class="text-[7px] text-slate-500 bg-slate-100 border border-slate-200 px-1 py-0.5 rounded leading-none">2024 / Genap</span>
                                </div>

                                {{-- 4 Metric Cards --}}
                                <div class="grid grid-cols-4 gap-1 mb-2">
                                    <div class="bg-slate-50 border border-slate-100 p-1 rounded text-center">
                                        <div class="text-[6px] text-slate-400 leading-none">Siswa Aktif</div>
                                        <div class="text-[8px] font-bold text-slate-800 leading-tight mt-0.5">34,578</div>
                                    </div>
                                    <div class="bg-slate-50 border border-slate-100 p-1 rounded text-center">
                                        <div class="text-[6px] text-slate-400 leading-none">Guru/Staff</div>
                                        <div class="text-[8px] font-bold text-slate-800 leading-tight mt-0.5">634</div>
                                    </div>
                                    <div class="bg-slate-50 border border-slate-100 p-1 rounded text-center">
                                        <div class="text-[6px] text-slate-400 leading-none">Kelas</div>
                                        <div class="text-[8px] font-bold text-slate-800 leading-tight mt-0.5">3,250</div>
                                    </div>
                                    <div class="bg-slate-50 border border-slate-100 p-1 rounded text-center">
                                        <div class="text-[6px] text-slate-400 leading-none">Kelulusan</div>
                                        <div class="text-[8px] font-bold text-emerald-500 leading-tight mt-0.5">52.54%</div>
                                    </div>
                                </div>

                                {{-- Chart Visuals --}}
                                <div class="flex items-end justify-between gap-2 pt-1.5 border-t border-slate-100">
                                    <div class="flex items-end gap-1 h-9">
                                        <div class="w-1.5 bg-sky-400 rounded-t h-4"></div>
                                        <div class="w-1.5 bg-sky-50 dark:$110 rounded-t h-6"></div>
                                        <div class="w-1.5 bg-sky-400 rounded-t h-3"></div>
                                        <div class="w-1.5 bg-sky-600 rounded-t h-8"></div>
                                        <div class="w-1.5 bg-sky-50 dark:$110 rounded-t h-5"></div>
                                        <div class="w-1.5 bg-sky-600 rounded-t h-7"></div>
                                    </div>
                                    <div class="relative w-8 h-8 flex items-center justify-center shrink-0">
                                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                            <circle cx="18" cy="18" r="14" fill="none" stroke="#e2e8f0" stroke-width="4"></circle>
                                            <circle cx="18" cy="18" r="14" fill="none" stroke="#0ea5e9" stroke-width="4" stroke-dasharray="88" stroke-dashoffset="20" stroke-linecap="round"></circle>
                                        </svg>
                                        <span class="absolute text-[8px] font-bold text-slate-700">78%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Project Details Kanan --}}
                    <div class="flex-1 flex flex-col justify-center items-start">
                        <div class="text-rose-500 mb-1">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.29 7 12 12 20.71 7"></polyline>
                                <line x1="12" y1="22" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 tracking-tight leading-snug">
                            Sistem Informasi Manajemen Sekolah
                        </h3>
                        <p class="text-xs text-slate-400 font-medium mt-1">Laravel, React, Next.js</p>
                        <p class="text-xs text-slate-500 leading-relaxed mt-3">
                            Damaged m:pang sistem Informasi Manajemen and lensing setortion mobie dashboard.
                        </p>
                        <div class="mt-4">
                            <span class="inline-block px-3.5 py-1.5 bg-slate-100 border border-slate-200 text-slate-700 rounded-full text-xs font-normal">
                                Digunakan oleh Sekolah Lain
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Showcase 2: Aplikasi E-Commerce Lokal --}}
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 lg:p-7 shadow-xs border border-slate-100 flex flex-col md:flex-row items-center gap-6">
                    {{-- 2 Smartphone Mockup --}}
                    <div class="w-full md:w-[320px] flex items-center justify-center gap-3 shrink-0 select-none">
                        {{-- Phone 1 --}}
                        <div class="w-[125px] bg-[#141822] rounded-[1.8rem] p-1.5 shadow-md border-2 border-slate-800 shrink-0">
                            <div class="bg-white dark:bg-gray-900 rounded-[1.4rem] p-2 flex flex-col justify-between h-[180px] overflow-hidden">
                                <div>
                                    <div class="flex items-center justify-between pb-1 border-b border-slate-100">
                                        <span class="text-[8px] font-bold text-slate-800">E-Market</span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-50 dark:$110"></span>
                                    </div>
                                    <div class="bg-sky-100 dark:bg-sky-50 dark:bg-sky-500//20 text-sky-700 text-center py-2 rounded-md font-bold text-[8px] my-2">
                                        Promo 50%
                                    </div>
                                    <div class="grid grid-cols-2 gap-1">
                                        <div class="bg-slate-100 rounded p-1.5 h-10 flex items-center justify-center text-[7px] text-slate-600 font-medium">
                                            Produk A
                                        </div>
                                        <div class="bg-slate-100 rounded p-1.5 h-10 flex items-center justify-center text-[7px] text-slate-600 font-medium">
                                            Produk B
                                        </div>
                                    </div>
                                </div>
                                <div class="w-6 h-0.5 bg-slate-300 rounded-full mx-auto"></div>
                            </div>
                        </div>

                        {{-- Phone 2 --}}
                        <div class="w-[125px] bg-[#141822] rounded-[1.8rem] p-1.5 shadow-md border-2 border-slate-800 shrink-0">
                            <div class="bg-white dark:bg-gray-900 rounded-[1.4rem] p-2 flex flex-col justify-between h-[180px] overflow-hidden">
                                <div>
                                    <div class="flex items-center justify-between pb-1 border-b border-slate-100">
                                        <span class="text-[8px] font-bold text-slate-800">Katalog Toko</span>
                                        <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <div class="grid grid-cols-2 gap-1 my-2">
                                        <div class="bg-slate-50 border border-slate-100 rounded p-1 text-center">
                                            <div class="w-full h-6 bg-slate-200 rounded mb-0.5"></div>
                                            <div class="text-[6px] text-slate-700 leading-tight">Sepatu</div>
                                            <div class="text-[6px] text-slate-400 font-medium">Rp 120k</div>
                                        </div>
                                        <div class="bg-slate-50 border border-slate-100 rounded p-1 text-center">
                                            <div class="w-full h-6 bg-slate-200 rounded mb-0.5"></div>
                                            <div class="text-[6px] text-slate-700 leading-tight">Tas Kulit</div>
                                            <div class="text-[6px] text-slate-400 font-medium">Rp 85k</div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="bg-slate-900 text-white text-center py-1 rounded-full text-[7px] font-medium mb-1">
                                        Beli Sekarang
                                    </div>
                                    <div class="w-6 h-0.5 bg-slate-300 rounded-full mx-auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Project Details Kanan --}}
                    <div class="flex-1 flex flex-col justify-center items-start">
                        <div class="text-rose-500 mb-1">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.29 7 12 12 20.71 7"></polyline>
                                <line x1="12" y1="22" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 tracking-tight leading-snug">
                            Aplikasi E-Commerce Lokal
                        </h3>
                        <p class="text-xs text-slate-400 font-medium mt-1">Laravel, React, Next.js</p>
                        <p class="text-xs text-slate-500 leading-relaxed mt-3">
                            Aplikasi E-Commerce lokal is a E-Commerce platform, profens, apps, enorping and constoring er-commerce.
                        </p>
                        <div class="mt-4">
                            <span class="inline-block px-3.5 py-1.5 bg-slate-100 border border-slate-200 text-slate-700 rounded-full text-xs font-normal">
                                Proyek Komersial
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Middle Heading: Karya Siswa Skills Filter --}}
            <div class="text-center mt-14 mb-8">
                <h2 class="text-2xl lg:text-3xl font-extrabold text-slate-900 inline-flex items-center gap-3">
                    Karya Siswa <span class="font-normal text-slate-400">Skills Filter</span>
                </h2>
                <div class="mt-5 flex justify-center">
                    <div x-data="{ activeSkill: 'Frontend' }" class="inline-flex items-center bg-white dark:bg-gray-900 p-1 rounded-full border border-slate-200 shadow-xs">
                        <button 
                            type="button" 
                            @click="activeSkill = 'Frontend'" 
                            :class="activeSkill === 'Frontend' ? 'bg-[#141822] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-5 py-2 rounded-full text-xs font-medium transition cursor-pointer">
                            Frontend
                        </button>
                        <button 
                            type="button" 
                            @click="activeSkill = 'Backend'" 
                            :class="activeSkill === 'Backend' ? 'bg-[#141822] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-5 py-2 rounded-full text-xs font-medium transition cursor-pointer">
                            Backend
                        </button>
                        <button 
                            type="button" 
                            @click="activeSkill = 'Cyber Security'" 
                            :class="activeSkill === 'Cyber Security' ? 'bg-[#141822] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-5 py-2 rounded-full text-xs font-medium transition cursor-pointer">
                            Cyber Security
                        </button>
                        <button 
                            type="button" 
                            @click="activeSkill = 'UI/UX'" 
                            :class="activeSkill === 'UI/UX' ? 'bg-[#141822] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-5 py-2 rounded-full text-xs font-medium transition cursor-pointer">
                            UI/UX
                        </button>
                    </div>
                </div>
            </div>

            {{-- Bottom Grid: 6 Developer / Siswa Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $developers = [
                        ['name' => 'Ahmad Fauzi', 'role' => 'Fullstack Developer', 'desc' => 'Expert in PHP, JS, AWS, & Cloud Architecture', 'badge' => 'Tech Lead'],
                        ['name' => 'Antrat Olsi', 'role' => 'Fullstack Developer', 'desc' => 'Expert in PHP, JS, AWS, & Cloud Architecture', 'badge' => 'Tech Lead'],
                        ['name' => 'Alim Rishan', 'role' => 'Fullstack Developer', 'desc' => 'Expert in PHP, JS, AWS, & Cloud Architecture', 'badge' => 'Tech Lead'],
                        ['name' => 'Jaman Fauzi', 'role' => 'Fullstack Developer', 'desc' => 'Expert in PHP, JS, AWS, & Cloud Architecture', 'badge' => 'Tech Lead'],
                        ['name' => 'Ahneni Palan', 'role' => 'Fullstack Developer', 'desc' => 'Expert in PHP, JS, AWS, & Cloud Architecture', 'badge' => 'Tech Lead'],
                        ['name' => 'Ahmad Fauzi', 'role' => 'Fullstack Developer', 'desc' => 'Expert in PHP, JS, AWS, & Cloud Architecture', 'badge' => 'Tech Lead'],
                    ];
                @endphp

                @foreach($developers as $dev)
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-3.5 border border-slate-100 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            {{-- Avatar Placeholder --}}
                            <div class="w-full aspect-[4/3.5] bg-[#d3d9e2] rounded-xl flex items-center justify-center overflow-hidden">
                                <svg class="w-12 h-12 text-[#9aa6b8]" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 18a7 7 0 0114 0H5z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            {{-- Student Info --}}
                            <h4 class="font-bold text-slate-900 text-sm mt-3 tracking-tight">{{ $dev['name'] }}</h4>
                            <p class="text-xs text-slate-400 font-normal mt-0.5">{{ $dev['role'] }}</p>
                            <p class="text-[11px] text-slate-500 mt-1 leading-snug">{{ $dev['desc'] }}</p>
                        </div>

                        {{-- Tag Badge --}}
                        <div class="mt-4">
                            <span class="inline-block px-3 py-1 bg-slate-100 border border-slate-200 text-slate-600 rounded-full text-[10px] font-medium">
                                {{ $dev['badge'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
