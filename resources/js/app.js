// Livewire already includes Alpine.js and persist plugin
// No need to import Alpine separately

// Import Croppie for image cropping component
import Croppie from "croppie";
import "./theme-melt";

// Make Croppie globally available for Alpine components
window.Croppie = Croppie;

// Debug: Verify Croppie is loaded
console.log("Croppie loaded:", typeof Croppie !== "undefined");

// ============================================
// Reveal on scroll (landing page animasi)
// ============================================
// Catatan: komponen carousel landing page (landingSlider, karyaCarousel)
// sengaja ditulis inline via x-data di beranda.blade.php, karena Alpine
// (dari Livewire) start sebelum module app.js dieksekusi — registrasi
// via Alpine.data() tidak akan pernah terpanggil.

function initRevealOnScroll() {
    const revealEls = document.querySelectorAll("[data-reveal]:not(.is-visible)");

    if (!revealEls.length) return;

    // Urutan reveal per section dipakai untuk stagger: blok yang masuk
    // viewport bersama-sama muncul berurutan, bukan serentak.
    const revealedCount = new WeakMap();

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                const el = entry.target;

                // Tampilkan saat masuk viewport. Bila elemen sudah terlewati
                // ke atas viewport (scroll cepat / lompat anchor), langsung
                // tampilkan agar tidak "nyangkut" transparan.
                const passedAbove = entry.boundingClientRect.top < 0;
                if (!entry.isIntersecting && !passedAbove) return;

                const scope = el.closest("section, footer") || document.body;
                const index = revealedCount.get(scope) || 0;
                revealedCount.set(scope, index + 1);

                // Naik bertahap, dibatasi agar tidak terasa lambat.
                el.style.setProperty(
                    "--reveal-delay",
                    `${Math.min(index * 70, 280)}ms`
                );

                el.classList.add("is-visible");
                io.unobserve(el);

                // Lepas will-change setelah animasi selesai (hemat GPU).
                el.addEventListener(
                    "transitionend",
                    () => {
                        el.style.willChange = "auto";
                    },
                    { once: true }
                );
            });
        },
        { threshold: 0.12, rootMargin: "0px 0px -40px 0px" }
    );

    revealEls.forEach((el) => io.observe(el));
}

document.addEventListener("DOMContentLoaded", initRevealOnScroll);
document.addEventListener("livewire:navigated", initRevealOnScroll);

// ============================================
// Struktur organisasi: slide-in dari kiri/kanan
// ============================================
// Berbeda dari reveal biasa: elemen BISA keluar lagi saat
// scroll naik melewati batas, sehingga animasi terasa
// "hidup" setiap kali user scroll ulang (bi-directional).
function initStrukturScroll() {
    const rows = document.querySelectorAll(".strutur-row");

    if (!rows.length) return;

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    // Masuk: slide-in ke posisi normal
                    entry.target.classList.add("is-visible");
                } else if (
                    entry.boundingClientRect.top > 0
                ) {
                    // Keluar: hanya saat elemen kembali di bawah viewport
                    // (scroll naik) — biarkan animasi replay.
                    entry.target.classList.remove("is-visible");
                }
            });
        },
        { threshold: 0.18, rootMargin: "0px 0px -60px 0px" }
    );

    rows.forEach((el) => io.observe(el));
}

document.addEventListener("DOMContentLoaded", initStrukturScroll);
document.addEventListener("livewire:navigated", initStrukturScroll);

// ============================================
// Hero lock (beranda): navbar full-bleed -> navbar normal
// ============================================
// Header + hero = satu viewport penuh. Progress scroll p (0..1)
// menggerakkan morph navbar lewat variabel CSS --hl-p:
//   p = 0 -> navbar hero lock (menyebar, tanpa card)
//   p = 1 -> navbar normal (card, menyempit ke tengah)
// Hanya berjalan bila halaman memuat header bertanda
// [data-hero-lock] (saat ini hanya beranda).
//
// Gating lebar/tinggi: hero lock (layout horizontal + navbar hero) hanya
// aktif bila viewport cukup lebar DAN cukup tinggi. Kalau tidak, class
// `hero-lock-on` dilepas dari <html> sehingga CSS menyusun hero secara
// vertikal (logo/info card/kartu kontrol slider disembunyikan, kontrol
// slider kembali menempel di dalam media). Ubah dua konstanta ini untuk
// menyetel ambangnya.
function initHeroLock() {
    if (window.__heroLockInit) return;
    window.__heroLockInit = true;

    // Ambang minimal hero lock. Navbar hero (mode melebar penuh) butuh
    // sekitar 1030px termasuk padding 48px, jadi 1040px dipakai sebagai
    // batas aman agar tidak muncul overflow horizontal tipis. 720px =
    // tinggi minimal agar kolom kiri (logo + judul + kartu info) tidak
    // berdesakan / terpotong.
    const MIN_W = 1040;
    const MIN_H = 720;
    window.__heroLockConfig = { MIN_W, MIN_H };

    const root = document.documentElement;
    let raf = null;

    const update = () => {
        raf = null;
        const header = document.querySelector("[data-hero-lock]");
        const hero = document.querySelector("[data-hero-lock-hero]");

        if (!header || !hero) {
            root.classList.remove("hero-lock-on");
            if (header) header.style.setProperty("--hl-p", "0");
            return;
        }

        const enabled = window.innerWidth >= MIN_W && window.innerHeight >= MIN_H;

        if (!enabled) {
            root.classList.remove("hero-lock-on");
            header.style.setProperty("--hl-p", "0");
            return;
        }

        root.classList.add("hero-lock-on");

        const pin = document.querySelector("[data-hero-lock-pin]");
        // Jarak transisi = tinggi ekstra pin di luar hero (stage sticky).
        // Di desktop = D; di mobile pin tidak menambah tinggi (dist ~ 0).
        const dist = pin && hero ? pin.offsetHeight - hero.offsetHeight : 0;
        const p = dist > 1
            ? Math.min(1, Math.max(0, window.scrollY / dist))
            : (window.scrollY > 0 ? 1 : 0);

        header.style.setProperty("--hl-p", String(p));
    };

    const schedule = () => {
        if (raf == null) raf = requestAnimationFrame(update);
    };

    window.addEventListener("scroll", schedule, { passive: true });
    window.addEventListener("resize", schedule);
    document.addEventListener("livewire:navigated", schedule);
    window.addEventListener("pageshow", schedule);

    schedule();
}

initHeroLock();
