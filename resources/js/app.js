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

document.addEventListener("DOMContentLoaded", () => {
    const revealEls = document.querySelectorAll("[data-reveal]");

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
});
