{{-- Grid profil siswa (dummy) — dipakai di tab "Profil Siswa" --}}
@php
    $profilDummy = [
        ['nama' => 'Ahmad Fauzi', 'role' => 'Fullstack Developer', 'desc' => 'Expert in PHP, JS, AWS, & Cloud Architecture', 'badge' => 'Tech Lead'],
        ['nama' => 'Antrat Olsi', 'role' => 'Backend Developer', 'desc' => 'Expert in Laravel, MySQL, & REST API Design', 'badge' => 'Backend'],
        ['nama' => 'Alim Rishan', 'role' => 'Mobile Developer', 'desc' => 'Expert in Flutter, Dart, & Firebase Integration', 'badge' => 'Mobile Dev'],
        ['nama' => 'Jaman Fauzi', 'role' => 'Frontend Developer', 'desc' => 'Expert in React, Next.js, & Tailwind CSS', 'badge' => 'Frontend'],
        ['nama' => 'Ahneni Palan', 'role' => 'UI/UX Designer', 'desc' => 'Expert in Figma, Design System, & Prototyping', 'badge' => 'UI/UX'],
        ['nama' => 'Siti Nurhaliza', 'role' => 'Cyber Security', 'desc' => 'Expert in Network Security, Python, & Pentesting', 'badge' => 'Security'],
        ['nama' => 'Rizky Ramadhan', 'role' => 'Fullstack Developer', 'desc' => 'Expert in Node.js, Express, & GraphQL', 'badge' => 'Fullstack'],
        ['nama' => 'Budi Santoso', 'role' => 'DevOps Engineer', 'desc' => 'Expert in Docker, CI/CD, & Cloud Infrastructure', 'badge' => 'DevOps'],
        ['nama' => 'Dewi Lestari', 'role' => 'Data Scientist', 'desc' => 'Expert in Python, Machine Learning, & Analytics', 'badge' => 'Data Sci'],
        ['nama' => 'Fajar Nugroho', 'role' => 'Backend Developer', 'desc' => 'Expert in Go, Microservices, & PostgreSQL', 'badge' => 'Backend'],
        ['nama' => 'Nadia Putri', 'role' => 'Frontend Developer', 'desc' => 'Expert in Vue.js, Nuxt, & Web Animation', 'badge' => 'Frontend'],
        ['nama' => 'Laras Ayu', 'role' => 'Mobile Developer', 'desc' => 'Expert in React Native, iOS, & Android', 'badge' => 'Mobile Dev'],
        ['nama' => 'Dimas Prakoso', 'role' => 'Game Developer', 'desc' => 'Expert in Unity, C#, & 3D Game Design', 'badge' => 'Game Dev'],
        ['nama' => 'Putri Maharani', 'role' => 'UI/UX Designer', 'desc' => 'Expert in User Research, Wireframing, & Usability', 'badge' => 'UI/UX'],
        ['nama' => 'Bayu Setiawan', 'role' => 'Cyber Security', 'desc' => 'Expert in Ethical Hacking, Forensik, & OSINT', 'badge' => 'Security'],
        ['nama' => 'Alya Rahma', 'role' => 'Fullstack Developer', 'desc' => 'Expert in Laravel, Livewire, & Alpine.js', 'badge' => 'Fullstack'],
        ['nama' => 'Raka Aditya', 'role' => 'IoT Engineer', 'desc' => 'Expert in Arduino, Raspberry Pi, & Embedded Systems', 'badge' => 'IoT'],
        ['nama' => 'Intan Permata', 'role' => 'Data Scientist', 'desc' => 'Expert in Data Visualization, SQL, & Big Data', 'badge' => 'Data Sci'],
    ];
@endphp

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
    @foreach($profilDummy as $dev)
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-3.5 border border-slate-100 dark:border-gray-800 shadow-xs flex flex-col justify-between hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div>
                {{-- Avatar Placeholder --}}
                <div class="w-full aspect-[4/3.5] bg-[#d3d9e2] dark:bg-gray-800 rounded-xl flex items-center justify-center overflow-hidden">
                    <svg class="w-12 h-12 text-[#9aa6b8] dark:text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 18a7 7 0 0114 0H5z" clip-rule="evenodd" />
                    </svg>
                </div>

                {{-- Student Info --}}
                <h4 class="font-bold text-slate-900 dark:text-gray-50 text-sm mt-3 tracking-tight">{{ $dev['nama'] }}</h4>
                <p class="text-xs text-slate-400 dark:text-gray-400 font-normal mt-0.5">{{ $dev['role'] }}</p>
                <p class="text-[11px] text-slate-500 dark:text-gray-400 mt-1 leading-snug">{{ $dev['desc'] }}</p>
            </div>

            {{-- Tag Badge --}}
            <div class="mt-4">
                <span class="inline-block px-3 py-1 bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-600 dark:text-gray-300 rounded-full text-[10px] font-medium">
                    {{ $dev['badge'] }}
                </span>
            </div>
        </div>
    @endforeach
</div>
