<div class="p-6 max-w-7xl mx-auto space-y-6">
    {{-- Toast Notifikasi (fixed di layar, selalu terlihat meskipun halaman di-scroll) --}}
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
                <button type="button" @click="show = false" class="ml-1 text-white/70 hover:text-white shrink-0" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <div>
        <h1 class="type-section-title text-gray-900 dark:text-gray-50">{{ __('Saluran & Kontak') }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Kelola email aktif dan akun media sosial untuk halaman profil Anda.') }}</p>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        {{-- Banner indikasi ada perubahan yang belum disimpan --}}
        @if($hasUnsavedChanges)
            <div
                class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-2.5"
                role="status"
            >
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.741-1.19l3-6a2 2 0 00-2.503-2.684L19 9.184V6a2 2 0 00-2-2H5a2 2 0 00-2 2v13a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs font-medium">{{ __('Ada perubahan yang belum disimpan. Jangan lupa klik "Simpan Perubahan".') }}</span>
            </div>
        @endif

        {{-- Email Aktif --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#EA4335] flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                            <path d="M22 6.5c0-1.38-1.12-2.5-2.5-2.5h-15C3.12 4 2 5.12 2 6.5v11C2 18.88 3.12 20 4.5 20h15c1.38 0 2.5-1.12 2.5-2.5v-11zM20 6.5v.31l-8 5.11-8-5.11V6.5l8 5.11 8-5.11z" fill="#fff"/>
                        </svg>
                    </div>
                    <span class="text-base font-bold text-gray-900 dark:text-gray-50">{{ __('Email Aktif') }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-700 dark:text-gray-200 font-medium">{{ __('Tampilkan di Profil') }}</span>
                    <button
                        type="button"
                        wire:click="toggleActive('email')"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $saluran['email']['active'] ? 'bg-[#0284c7]' : 'bg-gray-300' }}"
                    >
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out {{ $saluran['email']['active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('Alamat Email') }}</label>
                <input
                    type="email"
                    wire:model.live.debounce.300ms="email"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none"
                    placeholder="nama@email.com"
                >
                <p class="text-[11px] text-gray-400 dark:text-gray-400 mt-1">{{ __('Email ini juga dipakai untuk login. Aktifkan toggle di atas untuk menampilkannya di profil publik.') }}</p>
                @error('email') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Cards Grid (2 Kolom) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- 1. Instagram --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 flex items-center justify-center text-white shrink-0 shadow-xs">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </div>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-50">Instagram</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-700 dark:text-gray-200 font-medium">{{ __('Tampilkan di Profil') }}</span>
                        <button 
                            type="button" 
                            wire:click="toggleActive('instagram')" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $saluran['instagram']['active'] ? 'bg-[#0284c7]' : 'bg-gray-300' }}"
                        >
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white dark:bg-gray-900 shadow-sm ring-0 transition duration-200 ease-in-out {{ $saluran['instagram']['active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('URL Profil') }}</label>
                    <input 
                        type="text" 
                        wire:model="saluran.instagram.url" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        placeholder="https://instagram.com/username"
                    >
                </div>
            </div>

            {{-- 2. TikTok --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-black flex items-center justify-center text-white shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                            </svg>
                        </div>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-50">TikTok</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-700 dark:text-gray-200 font-medium">{{ __('Tampilkan di Profil') }}</span>
                        <button 
                            type="button" 
                            wire:click="toggleActive('tiktok')" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $saluran['tiktok']['active'] ? 'bg-[#0284c7]' : 'bg-gray-300' }}"
                        >
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white dark:bg-gray-900 shadow-sm ring-0 transition duration-200 ease-in-out {{ $saluran['tiktok']['active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('URL Profil') }}</label>
                    <input 
                        type="text" 
                        wire:model="saluran.tiktok.url" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        placeholder="https://tiktok.com/@username"
                    >
                </div>
            </div>

            {{-- 3. WhatsApp (Nomor Telepon) --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shrink-0 shadow-xs">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm.01 16.59c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31c-.82-1.31-1.26-2.83-1.26-4.38 0-4.54 3.7-8.24 8.24-8.24 2.2 0 4.27.86 5.82 2.42 1.56 1.55 2.41 3.63 2.41 5.84 0 4.55-3.7 8.22-8.22 8.22zm4.52-6.17c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.44.06-.66.31-.23.25-.88.86-.88 2.09 0 1.23.9 2.42 1.02 2.59.12.17 1.77 2.7 4.28 3.79.6.26 1.07.41 1.43.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.07-.12-.23-.19-.48-.31z"/>
                            </svg>
                        </div>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-50">{{ __('WhatsApp (Nomor Telepon)') }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-700 dark:text-gray-200 font-medium">{{ __('Tampilkan di Profil') }}</span>
                        <button 
                            type="button" 
                            wire:click="toggleActive('whatsapp')" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $saluran['whatsapp']['active'] ? 'bg-[#0284c7]' : 'bg-gray-300' }}"
                        >
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white dark:bg-gray-900 shadow-sm ring-0 transition duration-200 ease-in-out {{ $saluran['whatsapp']['active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('No. Telepon / Link') }}</label>
                    <input 
                        type="text" 
                        wire:model="saluran.whatsapp.url" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        placeholder="+6281234567890 atau https://wa.me/..."
                    >
                </div>
            </div>

            {{-- 4. YouTube --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center text-white shrink-0 shadow-xs">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </div>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-50">YouTube</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-700 dark:text-gray-200 font-medium">{{ __('Tampilkan di Profil') }}</span>
                        <button 
                            type="button" 
                            wire:click="toggleActive('youtube')" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $saluran['youtube']['active'] ? 'bg-[#0284c7]' : 'bg-gray-300' }}"
                        >
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white dark:bg-gray-900 shadow-sm ring-0 transition duration-200 ease-in-out {{ $saluran['youtube']['active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('URL Channel') }}</label>
                    <input 
                        type="text" 
                        wire:model="saluran.youtube.url" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        placeholder="https://youtube.com/c/nama_channel"
                    >
                </div>
            </div>

            {{-- 5. LinkedIn --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#0077b5] flex items-center justify-center text-white shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </div>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-50">LinkedIn</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-700 dark:text-gray-200 font-medium">{{ __('Tampilkan di Profil') }}</span>
                        <button 
                            type="button" 
                            wire:click="toggleActive('linkedin')" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $saluran['linkedin']['active'] ? 'bg-[#0284c7]' : 'bg-gray-300' }}"
                        >
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white dark:bg-gray-900 shadow-sm ring-0 transition duration-200 ease-in-out {{ $saluran['linkedin']['active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('URL Profil') }}</label>
                    <input 
                        type="text" 
                        wire:model="saluran.linkedin.url" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        placeholder="https://linkedin.com/in/username"
                    >
                </div>
            </div>

            {{-- 6. Twitter / X --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </div>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-50">Twitter / X</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-700 dark:text-gray-200 font-medium">{{ __('Tampilkan di Profil') }}</span>
                        <button 
                            type="button" 
                            wire:click="toggleActive('twitter')" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $saluran['twitter']['active'] ? 'bg-[#0284c7]' : 'bg-gray-300' }}"
                        >
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white dark:bg-gray-900 shadow-sm ring-0 transition duration-200 ease-in-out {{ $saluran['twitter']['active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('URL Profil') }}</label>
                    <input 
                        type="text" 
                        wire:model="saluran.twitter.url" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        placeholder="https://x.com/username"
                    >
                </div>
            </div>

            {{-- 7. GitHub --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#24292e] flex items-center justify-center text-white shrink-0 shadow-xs">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                            </svg>
                        </div>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-50">GitHub</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-700 dark:text-gray-200 font-medium">{{ __('Tampilkan di Profil') }}</span>
                        <button 
                            type="button" 
                            wire:click="toggleActive('github')" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $saluran['github']['active'] ? 'bg-[#0284c7]' : 'bg-gray-300' }}"
                        >
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white dark:bg-gray-900 shadow-sm ring-0 transition duration-200 ease-in-out {{ $saluran['github']['active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('URL Profil') }}</label>
                    <input 
                        type="text" 
                        wire:model="saluran.github.url" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        placeholder="https://github.com/username"
                    >
                </div>
            </div>
        </div>

        {{-- Action Button Simpan Perubahan --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            @if($hasUnsavedChanges)
                <span class="text-[11px] text-amber-600 font-medium">• {{ __('Belum disimpan') }}</span>
            @endif
            <button
                type="submit"
                class="px-5 py-2.5 {{ $hasUnsavedChanges ? 'bg-amber-500 hover:bg-amber-600 ring-2 ring-amber-300' : 'bg-[#0284c7] hover:bg-[#0369a1]' }} text-white rounded-lg text-sm font-bold shadow-xs transition cursor-pointer"
            >
                {{ __('Simpan Perubahan') }}
            </button>
        </div>
    </form>
</div>
