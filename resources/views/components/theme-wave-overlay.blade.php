{{--
    Overlay transisi tema: gelombang cair naik dari bawah layar
    (mengikuti video referensi "ink drip"). Dipanggil dari theme-toggle
    via window.playThemeWave(targetDark, applyCallback).
    Warna gelombang = warna latar tema TUJUAN.
--}}
<div
    id="theme-wave"
    class="fixed inset-0 z-[9999] pointer-events-none opacity-0"
    aria-hidden="true"
>
    <div
        id="theme-wave-body"
        class="absolute inset-x-0 bottom-0"
        style="height: 300%; transform: translateY(70%); color: #030712;"
    >
        {{-- Bibir gelombang: beranimasi terus agar terasa seperti cairan --}}
        <svg
            class="absolute left-[-50%] w-[200%] h-[130px]"
            style="top: -129px"
            viewBox="0 0 2880 260"
            preserveAspectRatio="none"
            fill="currentColor"
        >
            <path d="M0,130 C120,60 240,60 360,130 C480,200 600,200 720,130 C840,60 960,60 1080,130 C1200,200 1320,200 1440,130 L1440,260 L0,260 Z">
                <animate
                    attributeName="d"
                    dur="5s"
                    repeatCount="indefinite"
                    values="M0,130 C120,60 240,60 360,130 C480,200 600,200 720,130 C840,60 960,60 1080,130 C1200,200 1320,200 1440,130 L1440,260 L0,260 Z;
                            M0,150 C120,210 240,210 360,150 C480,90 600,90 720,150 C840,210 960,210 1080,150 C1200,90 1320,90 1440,150 L1440,260 L0,260 Z;
                            M0,130 C120,60 240,60 360,130 C480,200 600,200 720,130 C840,60 960,60 1080,130 C1200,200 1320,200 1440,130 L1440,260 L0,260 Z"
                />
            </path>
            <path opacity="0.55" d="M0,160 C160,90 320,90 480,160 C640,230 800,230 960,160 C1120,90 1280,90 1440,160 L1440,260 L0,260 Z">
                <animate
                    attributeName="d"
                    dur="7s"
                    repeatCount="indefinite"
                    values="M0,160 C160,90 320,90 480,160 C640,230 800,230 960,160 C1120,90 1280,90 1440,160 L1440,260 L0,260 Z;
                            M0,110 C160,180 320,180 480,110 C640,40 800,40 960,110 C1120,180 1280,180 1440,110 L1440,260 L0,260 Z;
                            M0,160 C160,90 320,90 480,160 C640,230 800,230 960,160 C1120,90 1280,90 1440,160 L1440,260 L0,260 Z"
                />
            </path>
        </svg>

        {{-- Badan gelombang (solid) --}}
        <div class="w-full h-full" style="background: currentColor"></div>
    </div>
</div>
