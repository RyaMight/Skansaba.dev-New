// ============================================
// Transisi tema: efek "ink melt" SAMA PERSIS dengan video
// referensi, via sprite-sheet 30 frame (konversi video).
// Sprite: public/media/theme-melt-sprite.png (tile vertikal)
//   frame 0  = melt menutup penuh (hitam)
//   frame 29 = terbuka penuh (putih)
// Alur: frame berjalan 29 -> 0 (melt naik menutup, SEARAH video)
//   -> tema diganti -> frame 0 -> 29 (melt turun membuka).
// Dipakai via window.playThemeWave(targetDark, applyCallback).
// ============================================

(function () {
    const FRAMES = 30;
    let playing = false;

    function setFrame(maskEl, n) {
        maskEl.style.setProperty('--frame', String(n));
    }

    // Jalankan frame dari `from` ke `to` (inklusif) dengan interval tetap.
    // resolve(frameAkhir) dipanggil setelah frame terakhir tercapai.
    function animateFrames(maskEl, from, to, msPerFrame, resolve) {
        let cur = from;
        setFrame(maskEl, cur);
        const step = from < to ? 1 : -1;

        const timer = setInterval(() => {
            cur += step;
            const done = step > 0 ? cur >= to : cur <= to;
            setFrame(maskEl, done ? to : cur);
            if (done) {
                clearInterval(timer);
                resolve(to);
            }
        }, msPerFrame);
    }

    window.playThemeWave = function (targetDark, applyCallback) {
        const overlay = document.getElementById("theme-melt");
        const mask = document.getElementById("theme-melt-mask");

        if (!overlay || !mask || playing) {
            applyCallback();
            return;
        }
        playing = true;

        // Warna melt = latar tema TUJUAN
        mask.style.background = targetDark ? "#030712" : "#ffffff";

        // Pergantian tema terjadi di balik melt — tanpa cross-fade
        document.documentElement.classList.remove("theme-transition");

        overlay.style.transition = "opacity 80ms ease";
        overlay.style.opacity = "1";

        // Fase 1: melt naik menutup layar (frame 29 -> 0), ~550ms total
        animateFrames(mask, FRAMES - 1, 0, 18, () => {
            // Fase 2: layar tertutup -> ganti tema
            applyCallback();

            setTimeout(() => {
                // Fase 3: melt turun membuka (frame 0 -> 29)
                animateFrames(mask, 0, FRAMES - 1, 18, () => {
                    overlay.style.opacity = "0";
                    setTimeout(() => {
                        setFrame(mask, FRAMES - 1);
                        playing = false;
                    }, 120);
                });
            }, 100);
        });
    };
})();
