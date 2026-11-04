# AI Agent Guidelines & Token Optimization (Codebase Memory)

Sistem ini menggunakan **Codebase Memory Bank** di direktori `memory-bank/` untuk menghemat penggunaan token dan menjaga kesinambungan konteks antar sesi.

## Aturan Konsumsi Token & Memori
1. **Baca Memori Dulu Sebelum Scan Kode**:
   - Di awal sesi atau saat memahami status proyek, **JANGAN** memindai/membaca seluruh file codebase sekaligus.
   - Cukup baca `memory-bank/activeContext.md` dan `memory-bank/progress.md` untuk memahami fokus saat ini.
   - Baca `memory-bank/systemPatterns.md` dan `memory-bank/techContext.md` hanya jika membutuhkan detail arsitektur atau dependensi spesifik.
2. **Targeted Reading**:
   - Hanya buka file kode yang berhubungan langsung dengan tugas yang sedang dikerjakan.
3. **Pembaruan Memori Berkala**:
   - Setiap kali menyelesaikan tugas besar, implementasi fitur, atau refactoring penting, perbarui `memory-bank/activeContext.md` dan `memory-bank/progress.md` agar sesi berikutnya tetap sinkron tanpa perlu analisis ulang dari nol.

## Aturan Khusus Kode Skansaba.dev
- Ikuti standar Laravel 12 + Livewire 4.
- Gunakan Tailwind CSS 4 untuk komponen tampilan (ikuti standar skill `anti-ai-slop`: desain bersih, border halus daripada shadow tebal, densitas data pas, tanpa gradient/emoji alay).
- Patuhi aturan role middleware (`admin`, `guru`, `siswa`) dan audit log.
