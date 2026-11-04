{{-- Grid karya siswa (dummy) — dipakai di tab "Karya Siswa" --}}
@php
    $karyaDummy = [
        ['judul' => 'Sistem Informasi Perpustakaan', 'siswa' => 'Ahmad Fauzi', 'kategori' => 'Web App', 'emoji' => '📚'],
        ['judul' => 'Aplikasi Kasir UMKM', 'siswa' => 'Rizky Ramadhan', 'kategori' => 'Desktop', 'emoji' => '🧾'],
        ['judul' => 'Game Edukasi Matematika', 'siswa' => 'Siti Nurhaliza', 'kategori' => 'Mobile', 'emoji' => '🎮'],
        ['judul' => 'Website Profil Sekolah', 'siswa' => 'Budi Santoso', 'kategori' => 'Web App', 'emoji' => '🏫'],
        ['judul' => 'Dashboard IoT Smart Home', 'siswa' => 'Dewi Lestari', 'kategori' => 'IoT', 'emoji' => '🏠'],
        ['judul' => 'Chatbot Bantuan Siswa', 'siswa' => 'Alim Rishan', 'kategori' => 'AI', 'emoji' => '🤖'],
    ];
@endphp

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
    @foreach($karyaDummy as $karya)
        <div class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-slate-100 dark:border-gray-800 shadow-xs group hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            {{-- Cover karya --}}
            <div class="w-full aspect-[4/3] bg-gradient-to-br from-slate-100 to-slate-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center text-4xl group-hover:scale-[1.03] transition-transform duration-500">
                {{ $karya['emoji'] }}
            </div>

            <div class="p-3.5">
                <h4 class="font-bold text-slate-900 dark:text-gray-50 text-sm tracking-tight leading-snug">{{ $karya['judul'] }}</h4>
                <p class="text-xs text-slate-400 dark:text-gray-400 font-normal mt-0.5">{{ $karya['siswa'] }}</p>
                <div class="mt-3">
                    <span class="inline-block px-3 py-1 bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-600 dark:text-gray-300 rounded-full text-[10px] font-medium">
                        {{ $karya['kategori'] }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach
</div>
