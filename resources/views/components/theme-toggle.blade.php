{{--
    Toggle Dark/Light Mode — gaya uiverse "slippery-ape-47" (Creatlydev):
    track abu-abu, knob bulat meluncur menutupi ikon nonaktif.
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
    type="button"
    role="switch"
    aria-label="Ganti mode gelap/terang"
    class="theme-switch relative inline-flex h-8 w-16 shrink-0 items-center rounded-full cursor-pointer select-none"
>
    {{-- Track --}}
    <span class="absolute inset-0 rounded-full bg-gray-300 dark:bg-gray-600 transition-colors duration-300"></span>

    {{-- Bulan (kiri, terlihat saat gelap) & Matahari (kanan, terlihat saat terang).
         Keduanya selalu terlihat; knob yang menutup ikon di bawahnya. --}}
    <span class="absolute left-2.5 flex items-center justify-center text-slate-100 dark:text-amber-300 z-0 transition-colors duration-300">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
        </svg>
    </span>
    <span class="absolute right-2.5 flex items-center justify-center text-amber-500 dark:text-slate-100 z-0 transition-colors duration-300">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="12" r="4"/>
            <path stroke-linecap="round" d="M12 2v2m0 16v2M2 12h2m16 0h2m-3.5-6.5L17 6M6 18l-1.5-1.5m0-9L6 6m12 12l1.5 1.5"/>
        </svg>
    </span>

    {{-- Knob: kiri saat terang, luncur ke kanan saat gelap --}}
    <span
        class="theme-switch-knob absolute left-1 h-6 w-6 rounded-full bg-white shadow-md ring-1 ring-black/5 z-10"
        :class="dark ? 'translate-x-8' : ''"
    ></span>
</button>

<style>
    .theme-switch-knob {
        transition: transform 400ms cubic-bezier(0.34, 1.56, 0.64, 1);
    }
</style>
