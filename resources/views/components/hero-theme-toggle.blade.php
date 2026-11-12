{{--
    Toggle tema khusus hero-lock (beranda desktop):
    - Hero-lock (--hl-p = 0): teks "mode" di kiri tombol.
    - Bentuk navbar normal (--hl-p = 1): teks "mode" ter-wipe habis; hanya tombol.
    - Mobile / halaman lain: hanya tombol (sama dengan <x-theme-toggle />).
    Pemakaian: <x-hero-theme-toggle />
--}}
<div class="hl-theme inline-flex items-center">
    <span class="hl-theme-mode" aria-hidden="true">mode</span>
    <x-theme-toggle />
</div>
