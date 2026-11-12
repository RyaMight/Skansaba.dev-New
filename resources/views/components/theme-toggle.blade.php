{{--
    Toggle Dark/Light Mode — tombol kotak gelap berisi ikon sun/moon.
    Glyph berputar 180° (spring) tiap toggle; ikon cross-fade + scale.
    Transisi tema memakai efek reveal lingkaran dari tombol (theme-melt.js).
    Pemakaian: <x-theme-toggle />
--}}
<button
    x-data="{ dark: document.documentElement.classList.contains('dark'), flip: false }"
    x-init="
        dark = document.documentElement.classList.contains('dark');
        $el.setAttribute('aria-checked', dark ? 'true' : 'false');
        const sync = () => {
            dark = document.documentElement.classList.contains('dark');
            $el.setAttribute('aria-checked', dark ? 'true' : 'false');
        };
        window.addEventListener('theme-changed', sync);
        document.addEventListener('livewire:navigated', sync);
    "
    @click="
        const html = document.documentElement;
        const nextDark = !html.classList.contains('dark');
        localStorage.setItem('theme', nextDark ? 'dark' : 'light');
        flip = !flip;
        const apply = () => {
            dark = html.classList.toggle('dark', nextDark);
            html.style.colorScheme = dark ? 'dark' : 'light';
            $el.setAttribute('aria-checked', dark ? 'true' : 'false');
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { dark } }));
        };
        if (window.playThemeWave) {
            window.playThemeWave(nextDark, apply);
        } else {
            apply();
        }
    "
    @keydown.space.prevent="$el.click()"
    type="button"
    role="switch"
    aria-label="Ganti mode gelap/terang"
    class="theme-switch relative h-11 w-11 shrink-0 cursor-pointer select-none grid place-items-center rounded-xl bg-gray-900 dark:bg-gray-100 shadow-sm transition-colors duration-250"
>
    <span class="theme-switch-glyph relative grid h-5 w-5 place-items-center" :class="{ 'is-flipped': flip }">
        {{-- Ikon bulan (muncul saat DARK). --}}
        <span class="theme-switch-icon theme-switch-icon--moon absolute inset-0 grid place-items-center">
            <svg class="h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401"/>
            </svg>
        </span>

        {{-- Ikon matahari (muncul saat LIGHT). --}}
        <span class="theme-switch-icon theme-switch-icon--sun absolute inset-0 grid place-items-center">
            <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="4"/>
                <path d="M12 2v2"/>
                <path d="M12 20v2"/>
                <path d="m4.93 4.93 1.41 1.41"/>
                <path d="m17.66 17.66 1.41 1.41"/>
                <path d="M2 12h2"/>
                <path d="M20 12h2"/>
                <path d="m6.34 17.66-1.41 1.41"/>
                <path d="m19.07 4.93-1.41 1.41"/>
            </svg>
        </span>
    </span>
</button>
