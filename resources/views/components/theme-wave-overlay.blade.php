{{--
    Overlay transisi tema: MEMAKAI VIDEO "ink melt" referensi.
    Saat toggle diklik: video diputar (melt menutup layar) ->
    tema diganti di puncak -> video diputar balik/lanjut membuka tema baru.
    Video: public/media/theme-melt.mp4 (alpha hanya jika webm; mp4 ini
    hitam-putih sehingga dipakai sebagai MASK via CSS mask-image).
--}}
<div
    id="theme-melt"
    class="fixed inset-0 z-[9999] pointer-events-none opacity-0"
    aria-hidden="true"
>
    {{-- Latar tema tujuan, ter-mask oleh video melt --}}
    <div
        id="theme-melt-mask"
        class="absolute inset-0"
        style="
            background: #030712;
            -webkit-mask-image: url('/media/theme-melt-mask.mp4');
            mask-image: url('/media/theme-melt-mask.mp4');
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
        "
    ></div>
</div>
