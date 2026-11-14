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
