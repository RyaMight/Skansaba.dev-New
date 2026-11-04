<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'CMS Jurusan RPL' }}</title>

    {{-- Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Theme init: apply saved/dark theme BEFORE paint to avoid flash --}}
    <script>
        (function () {
            var theme = localStorage.getItem('theme');
            var dark = theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (dark) document.documentElement.classList.add('dark');
        })();
    </script>

    {{-- Livewire Styles --}}
    @livewireStyles
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-950">
    <div class="flex h-screen overflow-hidden">
        @auth
            {{-- Sidebar Component --}}
            <livewire:components.sidebar-menu />

            {{-- Main Content --}}
            <div class="flex flex-col flex-1 overflow-hidden">
                {{-- Mobile Header --}}
                <header class="flex items-center justify-between px-4 py-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 md:hidden">
                    <span class="text-sm font-bold text-gray-900 dark:text-gray-50">Menu</span>
                    <button class="text-gray-500 dark:text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
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
