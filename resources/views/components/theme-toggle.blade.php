{{--
    Toggle Dark/Light Mode — pil geser dengan ikon bulan/matahari.
    Terhubung ke script theme-init di layouts (localStorage 'theme').
    Pemakaian: <x-theme-toggle />
--}}
<button
    x-data
    x-init="$el.setAttribute('aria-checked', document.documentElement.classList.contains('dark') ? 'true' : 'false')"
    @click="
        const html = document.documentElement;
        html.classList.add('theme-transition');
        const dark = html.classList.toggle('dark');
        localStorage.setItem('theme', dark ? 'dark' : 'light');
        $el.setAttribute('aria-checked', dark ? 'true' : 'false');
        setTimeout(() => html.classList.remove('theme-transition'), 350);
    "
    type="button"
    role="switch"
    aria-label="Ganti mode gelap/terang"
    class="relative inline-flex h-9 w-[68px] shrink-0 items-center rounded-full border-2 border-gray-200 bg-sky-500 transition-colors duration-300 cursor-pointer dark:bg-slate-950 dark:border-gray-700"
>
    {{-- Ikon matahari (mode terang aktif) --}}
    <span class="absolute left-3 flex items-center justify-center text-white transition-opacity duration-300 dark:opacity-0">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 17a5 5 0 100-10 5 5 0 000 10zm0 2a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm0-16a1 1 0 011 1v1a1 1 0 11-2 0V4a1 1 0 011-1zm8 8a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM6 12a1 1 0 01-1 1H4a1 1 0 110-2h1a1 1 0 011 1zm10.657-5.657a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zm-8.485 8.485a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zm9.192 1.414a1 1 0 01-1.414 0l-.707-.707a1 1 0 111.414-1.414l.707.707a1 1 0 010 1.414zM7.828 7.757a1 1 0 01-1.414 0l-.707-.707A1 1 0 117.121 5.636l.707.707a1 1 0 010 1.414z"/>
        </svg>
    </span>

    {{-- Ikon bulan + bintang (mode gelap aktif) --}}
    <span class="absolute right-3 flex items-center justify-center text-white opacity-0 transition-opacity duration-300 dark:opacity-100">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
            <path d="M17 5.5l.53 1.07 1.18.17-.85.83.2 1.17L17 8.18l-1.06.56.2-1.17-.85-.83 1.18-.17L17 5.5z"/>
            <path d="M20 9l.35.72.79.11-.57.56.13.79L20 10.8l-.7.38.13-.79-.57-.56.79-.11L20 9z"/>
        </svg>
    </span>

    {{-- Knob geser: kanan saat terang, kiri saat gelap --}}
    <span class="absolute right-1 h-6 w-6 rounded-full bg-white shadow-md transition-transform duration-300 dark:-translate-x-[36px]"></span>
</button>
