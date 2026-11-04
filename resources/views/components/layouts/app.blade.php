<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'CMS Jurusan RPL' }}</title>

    {{-- Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Theme init & SPA navigation sync: apply saved/dark theme BEFORE paint
         and preserve it across Livewire wire:navigate, popstate, and DOM morphs. --}}
    <script>
        (function () {
            window.isDarkTheme = function () {
                var theme = localStorage.getItem('theme');
                return theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches);
            };
            window.applyTheme = function () {
                var dark = window.isDarkTheme();
                var html = document.documentElement;
                html.classList.toggle('dark', dark);
                html.style.colorScheme = dark ? 'dark' : 'light';
                return dark;
            };
            window.applyTheme();

            if (!window.__themeSyncInitialized) {
                window.__themeSyncInitialized = true;
                document.addEventListener('livewire:navigating', window.applyTheme);
                document.addEventListener('livewire:navigated', window.applyTheme);
                document.addEventListener('DOMContentLoaded', window.applyTheme);
                window.addEventListener('popstate', window.applyTheme);
                window.addEventListener('storage', function (e) {
                    if (e.key === 'theme') window.applyTheme();
                });

                new MutationObserver(function () {
                    var shouldBeDark = window.isDarkTheme();
                    var isDarkNow = document.documentElement.classList.contains('dark');
                    if (shouldBeDark !== isDarkNow) {
                        document.documentElement.classList.toggle('dark', shouldBeDark);
                        document.documentElement.style.colorScheme = shouldBeDark ? 'dark' : 'light';
                    }
                }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            }
        })();
    </script>

    {{-- Livewire Styles --}}
    @livewireStyles
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-950 text-slate-800 dark:text-gray-100 transition-colors duration-200">
    <div class="flex h-screen overflow-hidden">
        @auth
            {{-- Sidebar Component --}}
            <livewire:components.sidebar-menu />

            {{-- Main Content --}}
            <div class="flex flex-col flex-1 overflow-hidden">
                {{-- Mobile Header --}}
                <header class="flex items-center justify-between px-4 py-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 md:hidden">
                    <span class="text-sm font-bold text-gray-900 dark:text-gray-50">Menu</span>
                    <div class="flex items-center gap-3">
                        <x-theme-toggle />
                        <button type="button" @click="$dispatch('toggle-mobile-sidebar')" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </header>

                {{-- Banner & Popup Peringatan Password Default Siswa --}}
                @if(auth()->user()->isUsingDefaultPassword() && !request()->routeIs('settings'))
                    <div 
                        x-data="{ showModal: !sessionStorage.getItem('dismissed_default_pw_{{ auth()->id() }}') }"
                        class="shrink-0"
                    >
                        {{-- Sticky Top Warning Bar --}}
                        <div class="px-4 py-2.5 flex flex-wrap items-center justify-between gap-3 text-xs" style="background-color: #fef3c7 !important; border-bottom: 1px solid #fcd34d !important; color: #000000 !important;">
                            <div class="flex items-center gap-2.5" style="color: #000000 !important;">
                                <div class="w-5 h-5 rounded-md flex items-center justify-center shrink-0" style="background-color: #fde68a !important; color: #b45309 !important;">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <span class="leading-normal" style="color: #000000 !important;">
                                    <strong class="font-bold" style="color: #000000 !important;">Keamanan Akun:</strong> Anda masih menggunakan password bawaan (<code class="font-mono font-bold px-1.5 py-0.5 rounded border" style="background-color: #fde68a !important; color: #000000 !important; border-color: #f59e0b !important;">{{ \App\Models\User::DEFAULT_SISWA_PASSWORD }}</code>). Segera ganti password Anda.
                                </span>
                            </div>
                            <a 
                                href="{{ route('settings') }}" 
                                wire:navigate
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-white font-semibold transition shadow-xs shrink-0 hover:opacity-90 cursor-pointer"
                                style="background-color: #d97706 !important; color: #ffffff !important;"
                            >
                                <span>Ganti Password Sekarang</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                        {{-- Centered Modal Popup --}}
                        <div 
                            x-show="showModal" 
                            x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-2xs"
                        >
                            <div 
                                @click.outside="sessionStorage.setItem('dismissed_default_pw_{{ auth()->id() }}', '1'); showModal = false"
                                class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-slate-200 dark:border-gray-800 max-w-md w-full p-6 space-y-4"
                            >
                                <div class="flex items-start gap-3.5">
                                    <div class="w-11 h-11 rounded-xl bg-amber-100 dark:bg-amber-500/20 border border-amber-200 dark:border-amber-500/30 flex items-center justify-center shrink-0 text-amber-600 dark:text-amber-400">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-gray-50">Segera Ganti Password Anda</h3>
                                        <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Peringatan keamanan akun Siswa RPL</p>
                                    </div>
                                </div>

                                <div class="text-xs text-slate-700 dark:text-gray-200 leading-relaxed space-y-2 bg-slate-50 dark:bg-gray-800/60 p-4 rounded-xl border border-slate-200/90 dark:border-gray-800">
                                    <p>
                                        Halo <strong class="text-slate-900 dark:text-white font-semibold">{{ auth()->user()->name }}</strong>, sistem mendeteksi bahwa akun Anda masih menggunakan password default bawaan:
                                    </p>
                                    <div class="px-3.5 py-2 rounded-lg text-center font-mono font-bold text-sm tracking-wider border" style="background-color: #fde68a !important; color: #000000 !important; border-color: #f59e0b !important;">
                                        {{ \App\Models\User::DEFAULT_SISWA_PASSWORD }}
                                    </div>
                                    <p class="text-slate-600 dark:text-gray-300">
                                        Agar akun dan karya portofolio Anda tidak disalahgunakan oleh orang lain, silakan segera mengganti password Anda sekarang.
                                    </p>
                                </div>

                                <div class="flex items-center justify-end gap-2.5 pt-2">
                                    <button 
                                        type="button"
                                        @click="sessionStorage.setItem('dismissed_default_pw_{{ auth()->id() }}', '1'); showModal = false"
                                        class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-gray-300 border border-slate-300 dark:border-gray-700 rounded-lg hover:bg-slate-50 dark:hover:bg-gray-800 transition cursor-pointer"
                                    >
                                        Nanti Saja
                                    </button>
                                    <a 
                                        href="{{ route('settings') }}"
                                        wire:navigate
                                        @click="sessionStorage.setItem('dismissed_default_pw_{{ auth()->id() }}', '1'); showModal = false"
                                        class="px-4 py-2 text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition shadow-xs cursor-pointer inline-flex items-center gap-1.5"
                                    >
                                        <span>Ganti Password Sekarang</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Page Content --}}
                <main class="flex-1 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        @else
            {{-- Login Layout --}}
            <div class="w-full flex items-center justify-center bg-gray-50 dark:bg-gray-950">
                {{ $slot }}
            </div>
        @endauth
    </div>

    {{-- Livewire Scripts --}}
    @livewireScripts
</body>
</html>
