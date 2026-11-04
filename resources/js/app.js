// Livewire already includes Alpine.js and persist plugin
// No need to import Alpine separately

// Import Croppie for image cropping component
import Croppie from "croppie";

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

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    io.unobserve(entry.target);
                }
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
