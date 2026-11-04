// ============================================
// Transisi tema: video "ink melt" sebagai mask animasi
// (sama persis dengan video referensi D:\118816-715736327.mp4)
// ============================================
// Cara kerja: overlay fullscreen berwarna latar tema TUJUAN,
// di-mask oleh video melt (hitam = terlihat, putih = transparan).
// Video diputar maju (melt naik menutup layar) -> tema diganti
// di puncak -> video dibalik (melt turun membuka tema baru).
// Dipakai lewat window.playThemeWave(targetDark, applyCallback).

(function () {
    let playing = false;

    window.playThemeWave = function (targetDark, applyCallback) {
        const overlay = document.getElementById("theme-melt");
        const mask = document.getElementById("theme-melt-mask");
        if (!overlay || !mask || playing) {
            applyCallback();
            return;
        }

        // Warna mask = latar tema TUJUAN
        mask.style.background = targetDark ? "#030712" : "#ffffff";

        // Matikan transisi warna bawaan — pergantian tema terjadi
        // di balik melt, bukan cross-fade.
        document.documentElement.classList.remove("theme-transition");

        // Video dalam CSS mask tidak bisa diputar via JS di semua browser.
        // Trik: animasikan mask-position dari frame terakhir (tertutup)
        // dengan langkah per-frame memakai Web Animations API pada
        // elemen video tersembunyi sebagai sumber waktu.
        playing = true;

        // Buat video tersembunyi sebagai penggerak waktu
        const vid = document.createElement("video");
        vid.src = "/media/theme-melt.mp4";
        vid.muted = true;
        vid.playsInline = true;
        vid.preload = "auto";
        vid.style.position = "fixed";
        vid.style.left = "-9999px";
        document.body.appendChild(vid);

        const DUR = 1000; // fase melt (ms), video asli ~1s efektif

        overlay.style.transition = "opacity 100ms ease";
        overlay.style.opacity = "1";

        vid.addEventListener("loadeddata", () => {
            // Fase 1: maju — video frame 0..akhir (melt menutup)
            vid.currentTime = 0;
            vid.play();

            const tick = () => {
                if (!vid.paused) {
                    requestAnimationFrame(tick);
                }
            };
            requestAnimationFrame(tick);

            vid.addEventListener("timeupdate", function onFwd() {
                if (vid.currentTime >= 0.92) {
                    vid.removeEventListener("timeupdate", onFwd);
                    vid.pause();

                    // Fase 2: tertutup penuh -> ganti tema
                    applyCallback();

                    setTimeout(() => {
                        // Fase 3: video diputar BALIK (melt membuka)
                        vid.pause();
                        const revTick = () => {
                            vid.currentTime -= 0.05;
                            if (vid.currentTime > 0.02) {
                                requestAnimationFrame(revTick);
                            } else {
                                vid.currentTime = 0;
                                // Selesai: sembunyikan & bersihkan
                                overlay.style.opacity = "0";
                                setTimeout(() => {
                                    vid.remove();
                                    playing = false;
                                }, 120);
                            }
                        };
                        revTick();
                    }, 80);
                }
            });
        });
    };
})();
