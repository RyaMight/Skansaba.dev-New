{{--
    Toggle Dark/Light Mode — replika uiverse "slippery-ape-47" (ZenonAiden):
    tombol lingkaran berisi ikon bulan/matahari yang berputar + scale saat berganti.
    - Mode terang: lingkaran terang (#e8e8e8) berisi bulan gelap, sedikit translate.
    - Mode gelap: lingkaran gelap (#212121) berisi matahari terang, translate berlawanan.
    Terhubung ke script theme-init di layouts (localStorage 'theme').
    Pemakaian: <x-theme-toggle />
--}}
<button
    x-data="{ dark: document.documentElement.classList.contains('dark') }"
    x-init="
        $el.setAttribute('aria-checked', dark ? 'true' : 'false');
        // sinkron kalau tema diubah dari tab/driver lain
        new MutationObserver(() => { dark = document.documentElement.classList.contains('dark'); })
            .observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    "
    @click="
        const html = document.documentElement;
        html.classList.add('theme-transition');
        dark = html.classList.toggle('dark');
        localStorage.setItem('theme', dark ? 'dark' : 'light');
        $el.setAttribute('aria-checked', dark ? 'true' : 'false');
        setTimeout(() => html.classList.remove('theme-transition'), 350);
    "
    @keydown.space.prevent="$el.click()"
    type="button"
    role="switch"
    aria-label="Ganti mode gelap/terang"
    class="theme-switch relative h-9 w-9 shrink-0 rounded-full cursor-pointer select-none grid place-items-center active:scale-90 transition-transform"
>
    {{-- Ikon bulan: lingkaran terang + bulan gelap. Muncul saat DARK. --}}
    <span class="theme-switch-icon theme-switch-icon--moon absolute inset-0 rounded-full grid place-items-center bg-[#e8e8e8] text-[#212121] z-10">
        <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd"/>
        </svg>
    </span>

    {{-- Ikon matahari: lingkaran gelap + matahari terang. Muncul saat LIGHT. --}}
    <span class="theme-switch-icon theme-switch-icon--sun absolute inset-0 rounded-full grid place-items-center bg-[#212121] text-[#e8e8e8] z-10">
        <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z"/>
        </svg>
    </span>
</button>

<style>
    /* Dua ikon bertumpuk di grid yang sama; hanya satu yang terlihat.
       Transisi: berputar + scale, persis seperti uiverse slippery-ape. */
    .theme-switch .theme-switch-icon {
        transform-origin: center center;
        transition:
            transform 500ms ease,
            opacity 300ms ease,
            translate 400ms ease;
    }

    /* Terang (default): matahari tampil, bulan sembunyi */
    .theme-switch .theme-switch-icon--sun {
        transform: rotate(360deg) scale(1);
        opacity: 1;
    }
    .theme-switch .theme-switch-icon--moon {
        transform: rotate(0deg) scale(0);
        opacity: 0;
    }

    /* Gelap (.dark di <html>): bulan tampil berputar, matahari menyusut.
       translate memberi efek "bergeser" seperti slider pada desain aslinya. */
    .dark .theme-switch .theme-switch-icon--moon {
        transform: rotate(360deg) scale(1);
        opacity: 1;
        translate: 0 0;
    }
    .dark .theme-switch .theme-switch-icon--sun {
        transform: rotate(0deg) scale(0);
        opacity: 0;
        translate: 0 0;
    }
</style>
