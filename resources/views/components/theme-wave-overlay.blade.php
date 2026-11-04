{{--
    Overlay transisi tema: efek "ink melt" SAMA PERSIS dengan video
    referensi, memakai sprite-sheet PNG hasil konversi video
    (30 frame, diputar dengan CSS steps()). Lintas browser.
    Logika ada di resources/js/theme-melt.js (window.playThemeWave).
--}}
<div
    id="theme-melt"
    class="fixed inset-0 z-[9999] pointer-events-none opacity-0"
    aria-hidden="true"
>
    {{-- Lapisan berwarna tema tujuan, "bentuknya" dikontrol mask sprite --}}
    <div
        id="theme-melt-mask"
        class="absolute inset-0 theme-melt-frame"
        style="background: #030712; --frame: 0;"
    ></div>
</div>
