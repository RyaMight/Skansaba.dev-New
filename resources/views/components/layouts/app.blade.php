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
