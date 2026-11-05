@php
    $globalSettings = App\Models\GlobalSetting::instance();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? $globalSettings->site_name ?? 'CMS Jurusan RPL' }}</title>

    {{-- Favicon --}}
    @if($globalSettings->favicon_url)
        <link rel="icon" type="image/webp" href="{{ $globalSettings->favicon_url }}?v={{ file_exists(public_path('storage/' . $globalSettings->favicon)) ? filemtime(public_path('storage/' . $globalSettings->favicon)) : '2' }}">
    @endif
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=2">
    
    {{-- SEO Meta Tags --}}
    <meta name="description" content="{{ $globalSettings->site_tagline ?? 'Sistem Manajemen Konten Jurusan RPL' }}">
    <meta name="author" content="{{ $globalSettings->site_name ?? 'SMK RPL' }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    {{-- Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Dynamic Primary Color CSS Variables & Google Translate UI Overrides --}}
    <style>
        :root {
            --primary-color: {{ $globalSettings->primary_color ?? '#12B4E0' }};
            --secondary-color: {{ $globalSettings->secondary_color ?? '#1E3A8A' }};
        }
        /* Suppress Google Translate top banner, tooltips, and highlighting */
        .goog-te-banner-frame.skiptranslate,
        .goog-te-banner-frame,
        #goog-gt-tt,
        .goog-te-balloon-frame,
        .goog-te-gadget {
            display: none !important;
            visibility: hidden !important;
        }
        body {
            top: 0px !important;
            position: static !important;
        }
        .skiptranslate iframe {
            display: none !important;
            visibility: hidden !important;
        }
        .goog-text-highlight {
            background: transparent !important;
            box-shadow: none !important;
        }
    </style>

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
<body class="antialiased bg-white dark:bg-gray-950 text-slate-800 dark:text-gray-100">
    <x-navbar-guest />
    
    <main>
        {{ $slot }}
    </main>

    <livewire:components.footer />

    {{-- Livewire Scripts --}}
    @livewireScripts

    {{-- Google Translate Integration for Dynamic Database Content (Active in EN mode) --}}
    @if(app()->getLocale() === 'en')
        <div id="google_translate_element" class="notranslate" style="display:none !important;" aria-hidden="true"></div>
        <script>
            function googleTranslateElementInit() {
                if (window.google && window.google.translate) {
                    new google.translate.TranslateElement({
                        pageLanguage: 'id',
                        includedLanguages: 'id,en',
                        autoDisplay: false
                    }, 'google_translate_element');
                }
            }
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" defer></script>
    @else
        <script>
            // Pastikan cookie Google Translate di-reset saat mode default ID aktif
            if (document.cookie.indexOf('googtrans') !== -1 && document.cookie.indexOf('/id/en') !== -1) {
                document.cookie = 'googtrans=/id/id; path=/;';
                document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            }
        </script>
    @endif
</body>
</html>
