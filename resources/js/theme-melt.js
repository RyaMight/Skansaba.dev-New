// ============================================
// Transisi tema: "smooth reveal" — lingkaran cahaya meluas
// dari tombol toggle, menyapu seluruh layar dengan easing
// lembut. Tanpa overlay tambahan: pakai View Transitions API
// jika tersedia (Chrome/Edge), fallback fade halus.
// Dipanggil dari <x-theme-toggle> via window.playThemeWave.
// ============================================

(function () {
    // Simpan posisi klik terakhir dari tombol toggle
    let origin = { x: window.innerWidth - 60, y: 40 };

    document.addEventListener("click", (e) => {
        const btn = e.target.closest(".theme-switch");
        if (btn) {
            const r = btn.getBoundingClientRect();
            origin = { x: r.left + r.width / 2, y: r.top + r.height / 2 };
        }
    }, true);

    window.playThemeWave = function (targetDark, applyCallback) {
        const html = document.documentElement;
        const maxR = Math.hypot(
            Math.max(origin.x, window.innerWidth - origin.x),
            Math.max(origin.y, window.innerHeight - origin.y)
        ) * 1.1;

        // View Transitions API (Chrome 111+, Edge) — reveal lingkaran
        // mulus dari titik tombol, native & sangat halus.
        if (document.startViewTransition) {
            const vt = document.startViewTransition(() => {
                applyCallback();
            });

            vt.ready.then(() => {
                document.documentElement.animate(
                    {
                        clipPath: [
                            `circle(0px at ${origin.x}px ${origin.y}px)`,
                            `circle(${maxR}px at ${origin.x}px ${origin.y}px)`,
                        ],
                    },
                    {
                        duration: 650,
                        easing: "cubic-bezier(0.22, 1, 0.36, 1)",
                        pseudoElement: "::view-transition-new(root)",
                    }
                );
            });

            vt.finished.finally(() => {
                html.classList.remove("theme-transition");
            });
            return;
        }

        // Fallback: crossfade lembut (Firefox/Safari)
        html.classList.add("theme-transition");
        applyCallback();
        setTimeout(() => html.classList.remove("theme-transition"), 450);
    };
})();
