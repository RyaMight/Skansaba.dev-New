<div class="p-6 max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </span>
                <div>
                    <h1 class="type-section-title text-gray-900 dark:text-gray-50">Keamanan & Audit</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Pantau percobaan login dan kelola keamanan akun siswa.</p>
                </div>
            </div>
        </div>
        <a href="{{ route('siswa.profil') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg text-xs font-bold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session()->has('message'))
        <div class="bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200 dark:border-emerald-500/30 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-xl flex items-center justify-between text-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="bg-rose-50 dark:bg-rose-500/15 border border-rose-200 dark:border-rose-500/30 text-rose-800 dark:text-rose-200 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Gagal Login (24 jam)</p>
            <p class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1">{{ $stats['failed_24h'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Terkunci Sementara</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['locked_accounts'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Akun Nonaktif</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 mt-1">{{ $stats['inactive_students'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Login Sukses (24 jam)</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['success_24h'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Audit Login --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-800">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="type-card-title-sm text-gray-900 dark:text-gray-50">Audit Login</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Riwayat percobaan login terbaru.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" wire:model.live.debounce.300ms="auditSearch" placeholder="Cari email / IP..." class="w-40 px-3 py-1.5 text-xs bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none">
                        <select wire:model.live="auditStatus" class="px-3 py-1.5 text-xs bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none">
                            <option value="failed">Gagal</option>
                            <option value="success">Sukses</option>
                            <option value="all">Semua</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-800/70 text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-2.5 px-4">Identitas</th>
                            <th class="py-2.5 px-4">Status</th>
                            <th class="py-2.5 px-4">IP</th>
                            <th class="py-2.5 px-4">Keterangan</th>
                            <th class="py-2.5 px-4 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($audits as $audit)
                            <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-800/50 transition">
                                <td class="py-2.5 px-4 font-medium text-gray-800 dark:text-gray-100 max-w-[180px] truncate">{{ $audit->email }}</td>
                                <td class="py-2.5 px-4">
                                    @if($audit->isSuccessful())
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">Sukses</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30">Gagal</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 text-gray-500 dark:text-gray-400">{{ $audit->ip_address ?: '-' }}</td>
                                <td class="py-2.5 px-4 text-gray-500 dark:text-gray-400 max-w-[180px] truncate">{{ $audit->failure_reason ?: '-' }}</td>
                                <td class="py-2.5 px-4 text-right text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $audit->attempted_at?->diffForHumans() ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-gray-400 dark:text-gray-500">Belum ada data audit login.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($audits->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $audits->links() }}
                </div>
            @endif
        </div>

        {{-- Riwayat Tindakan Moderator --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800">
            <div class="p-5 border-b border-gray-100 dark:border-gray-800">
                <h2 class="type-card-title-sm text-gray-900 dark:text-gray-50">Riwayat Moderasi</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tindakan terbaru yang tercatat.</p>
            </div>
            <div class="p-4 space-y-3">
                @forelse($recentLogs as $log)
                    <div class="flex items-start gap-2.5">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-100">{{ $log->action_label }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">{{ $log->description }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                {{ $log->actor?->name ?? 'Sistem' }} · {{ $log->created_at?->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 dark:text-gray-500 text-center py-6">Belum ada tindakan moderasi.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Manajemen Akun Siswa --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="type-card-title-sm text-gray-900 dark:text-gray-50">Keamanan Akun Siswa</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Nonaktifkan akun mencurigakan atau buka blokir login sementara.</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama / NISN / email..." class="w-48 px-3 py-1.5 text-xs bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none">
                    <select wire:model.live="filterStatus" class="px-3 py-1.5 text-xs bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 dark:bg-gray-800/70 text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="py-2.5 px-4">Siswa</th>
                        <th class="py-2.5 px-4">Kelas / NISN</th>
                        <th class="py-2.5 px-4">Gagal Login (15 mnt)</th>
                        <th class="py-2.5 px-4">Status</th>
                        <th class="py-2.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($students as $student)
                        @php
                            $recentFailures = max(
                                (int) ($failedByEmail[$student->email] ?? 0),
                                (int) ($failedByEmail[$student->nisn] ?? 0)
                            );
                            $isSelf = $student->id === auth()->id();
                        @endphp
                        <tr wire:key="student-{{ $student->id }}" class="hover:bg-gray-50/70 dark:hover:bg-gray-800/50 transition">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5">
                                    @if($student->photo_url)
                                        <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="w-8 h-8 {{ $student->photoFrameClass('rounded-lg') }} object-cover border border-gray-200 dark:border-gray-700 shrink-0">
                                    @else
                                        <div class="w-8 h-8 {{ $student->photoFrameClass('rounded-lg') }} bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 font-bold flex items-center justify-center text-[11px] shrink-0">
                                            {{ $student->initials() }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $student->name }}</p>
                                            @if($student->isModerator())
                                                <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 shrink-0">Moderator</span>
                                            @endif
                                            @if($isSelf)
                                                <span class="text-[10px] text-sky-600 dark:text-sky-400 font-semibold">(Anda)</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ $student->email ?: '@'.($student->username ?: 'siswa-'.$student->id) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $student->kelas ?: '-' }}</p>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">NISN: {{ $student->nisn ?: '-' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                @if($recentFailures >= 5)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30">
                                        {{ $recentFailures }}× terkunci
                                    </span>
                                @elseif($recentFailures > 0)
                                    <span class="text-amber-600 dark:text-amber-400 font-semibold">{{ $recentFailures }}×</span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $student->status === 'active' ? 'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">
                                    {{ $student->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if($isSelf)
                                    <span class="text-[11px] text-gray-400 dark:text-gray-500 italic">Akun sendiri</span>
                                @else
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($recentFailures >= 5)
                                            <button
                                                type="button"
                                                wire:click="clearLoginLock({{ $student->id }})"
                                                wire:confirm="Buka blokir login sementara untuk {{ $student->name }}?"
                                                class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-500/15 border border-amber-200 dark:border-amber-500/30 hover:bg-amber-100 dark:hover:bg-amber-500/25 transition"
                                            >
                                                Buka Blokir
                                            </button>
                                        @endif
                                        @if($student->status === 'active')
                                            <button
                                                type="button"
                                                wire:click="lockAccount({{ $student->id }})"
                                                wire:confirm="Nonaktifkan akun {{ $student->name }}? Siswa tidak akan bisa login."
                                                class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-500/15 border border-rose-200 dark:border-rose-500/30 hover:bg-rose-100 dark:hover:bg-rose-500/25 transition"
                                            >
                                                Nonaktifkan
                                            </button>
                                        @else
                                            <button
                                                type="button"
                                                wire:click="unlockAccount({{ $student->id }})"
                                                wire:confirm="Aktifkan kembali akun {{ $student->name }}?"
                                                class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200 dark:border-emerald-500/30 hover:bg-emerald-100 dark:hover:bg-emerald-500/25 transition"
                                            >
                                                Aktifkan
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-400 dark:text-gray-500">Tidak ada akun siswa yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
