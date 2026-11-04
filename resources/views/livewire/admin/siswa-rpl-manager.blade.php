<div class="min-h-screen bg-slate-50 dark:bg-gray-950 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-gray-50 tracking-tight">Akun Siswa RPL</h1>
                <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Daftar akun siswa Jurusan RPL, status verifikasi keahlian, dan kurasi portofolio karya.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <button 
                    type="button" 
                    wire:click="openCreateModal"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-slate-900 dark:bg-sky-600 rounded-lg hover:bg-slate-800 dark:hover:bg-sky-500 transition shadow-xs cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Akun Siswa
                </button>
                <a 
                    href="{{ route('karya-siswa.verifikasi') }}" 
                    wire:navigate
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-sky-700 dark:text-sky-300 bg-sky-50 dark:bg-sky-500/20 border border-sky-200 dark:border-sky-500/30 rounded-lg hover:bg-sky-100 dark:hover:bg-sky-500/30 transition shadow-xs"
                >
                    <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verifikasi Karya
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session()->has('message'))
            <div class="mb-6 bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200 dark:border-emerald-500/30 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-xl flex items-center justify-between text-sm">
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
            <div class="mb-6 bg-rose-50 dark:bg-rose-500/15 border border-rose-200 dark:border-rose-500/30 text-rose-800 dark:text-rose-200 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Toolbar: Pencarian & Filter Keahlian/Status --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 p-4 mb-6 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                {{-- Search --}}
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nama, NISN, username, email..." 
                        class="w-full pl-9 pr-3 py-2 text-xs bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-100 border border-slate-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                    >
                    <svg class="w-4 h-4 text-slate-400 dark:text-gray-500 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                {{-- Filter Keahlian --}}
                <div>
                    <select wire:model.live="filterKeahlian" class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-100">
                        <option value="">Semua Tag Keahlian</option>
                        @foreach($tagPresets as $tag)
                            <option value="{{ $tag }}">{{ $tag }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Status --}}
                <div>
                    <select wire:model.live="filterStatus" class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-100">
                        <option value="">Semua Status Akun</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Table Siswa RPL --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-gray-800/70 border-b border-slate-200 dark:border-gray-800 text-slate-500 dark:text-gray-400 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Siswa</th>
                            <th class="py-3 px-4">Kelas / NISN</th>
                            <th class="py-3 px-4">Tag Keahlian</th>
                            <th class="py-3 px-4">Portofolio Karya</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-gray-800">
                        @forelse($students as $student)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-gray-800/50 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($student->photo_url)
                                            <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-gray-700 flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-gray-800 text-slate-600 dark:text-gray-300 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                                {{ $student->initials() }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900 dark:text-gray-100 truncate">{{ $student->name }}</p>
                                            <p class="text-[11px] text-slate-500 dark:text-gray-400 truncate">
                                                {{ '@' . ($student->username ?: 'siswa-' . $student->id) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-medium text-slate-800 dark:text-gray-200">{{ $student->kelas ?: '-' }}</p>
                                    <p class="text-[11px] text-slate-400 dark:text-gray-500">NISN: {{ $student->nisn ?: '-' }}</p>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-wrap items-center gap-1 max-w-[280px]">
                                        @forelse($student->keahlian_list as $tag)
                                            <span class="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded-md text-[11px] font-medium bg-emerald-50 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                                                <span>{{ $tag }}</span>
                                                <button 
                                                    type="button" 
                                                    wire:click="revokeTag({{ $student->id }}, '{{ $tag }}')"
                                                    wire:confirm="Cabut tag '{{ $tag }}' dari {{ $student->name }}?"
                                                    class="p-0.5 text-emerald-600 dark:text-emerald-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/20 rounded transition cursor-pointer"
                                                    title="Cabut tag {{ $tag }}"
                                                >
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </span>
                                        @empty
                                            <span class="text-slate-400 dark:text-gray-500 italic text-[11px]">Belum ada tag</span>
                                        @endforelse

                                        <button 
                                            type="button" 
                                            wire:click="openTagModal({{ $student->id }})" 
                                            class="inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[10px] font-semibold text-sky-700 dark:text-sky-300 bg-sky-50 dark:bg-sky-500/20 border border-sky-200 dark:border-sky-500/30 rounded hover:bg-sky-100 dark:hover:bg-sky-500/30 transition cursor-pointer"
                                            title="Beri / Cabut Tag"
                                        >
                                            + Tag
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300">
                                            {{ $student->published_karya }} Publik
                                        </span>
                                        @if($student->pending_karya > 0)
                                            <a 
                                                href="{{ route('karya-siswa.verifikasi', ['userId' => $student->id]) }}" 
                                                wire:navigate
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-500/30 hover:bg-amber-100 transition shadow-2xs"
                                                title="Siswa ini punya karya menunggu verifikasi"
                                            >
                                                <span>!</span>
                                                <span>{{ $student->pending_karya }} review</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <button 
                                        type="button" 
                                        wire:click="toggleStatus({{ $student->id }})"
                                        @class([
                                            'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border transition cursor-pointer',
                                            'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-500/30 hover:bg-emerald-100' => $student->status === 'active',
                                            'bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 border-slate-200 dark:border-gray-700 hover:bg-slate-200' => $student->status !== 'active',
                                        ])
                                    >
                                        {{ $student->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button 
                                            type="button" 
                                            wire:click="openTagModal({{ $student->id }})" 
                                            class="p-1.5 text-slate-500 dark:text-gray-400 hover:text-sky-600 dark:hover:text-sky-400 hover:bg-sky-50 dark:hover:bg-sky-500/15 rounded-lg transition"
                                            title="Atur Tag Keahlian"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="openViewModal({{ $student->id }})" 
                                            class="p-1.5 text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-800 rounded-lg transition"
                                            title="Lihat Detail & Portofolio"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 dark:text-gray-500">
                                    Tidak ada data siswa yang cocok dengan pencarian / filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-gray-800">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL TAMBAH AKUN SISWA RPL (STRICTLY ROLE SISWA) --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity" wire:click="closeCreateModal"></div>
            
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-xl max-w-xl w-full p-6 border border-slate-200 dark:border-gray-700 z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-gray-800">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-gray-50">Tambah Akun Siswa RPL</h3>
                        <p class="text-xs text-slate-500 dark:text-gray-400">Mendaftarkan akun siswa baru ke jurusan RPL.</p>
                    </div>
                    <button type="button" wire:click="closeCreateModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="createStudent" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-gray-200 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="Contoh: Muhammad Rizky Pratama" class="w-full px-3 py-2 bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-100 border border-slate-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none">
                        @error('name') <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-gray-200 mb-1">NISN (Nomor Induk Siswa Nasional)</label>
                            <input type="text" wire:model="nisn" placeholder="Contoh: 0061234567" class="w-full px-3 py-2 bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-100 border border-slate-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none">
                            @error('nisn') <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-gray-200 mb-1">Kelas</label>
                            <input type="text" wire:model="kelas" placeholder="Contoh: XII RPL 1" class="w-full px-3 py-2 bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-100 border border-slate-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none">
                            @error('kelas') <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-gray-200 mb-1">Email <span class="text-rose-500">*</span></label>
                            <input type="email" wire:model="email" placeholder="siswa@skansaba.sch.id" class="w-full px-3 py-2 bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-100 border border-slate-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none">
                            @error('email') <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-gray-200 mb-1">Password Awal <span class="text-rose-500">*</span></label>
                            <input type="password" wire:model="password" placeholder="Minimal 6 karakter" class="w-full px-3 py-2 bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-100 border border-slate-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none">
                            @error('password') <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-gray-200 mb-1">Status Akun <span class="text-rose-500">*</span></label>
                            <select wire:model="status" class="w-full px-3 py-2 border border-slate-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-100">
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                            @error('status') <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-gray-200 mb-1">Tag Keahlian Awal (Opsional)</label>
                            <select wire:model="keahlian" class="w-full px-3 py-2 border border-slate-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-100">
                                <option value="">-- Belum ada tag (verifikasi nanti via karya) --</option>
                                @foreach($tagPresets as $tag)
                                    <option value="{{ $tag }}">{{ $tag }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-gray-800">
                        <button type="button" wire:click="closeCreateModal" class="px-4 py-2 border border-slate-300 dark:border-gray-700 rounded-lg font-semibold text-slate-700 dark:text-gray-200 hover:bg-slate-50 dark:hover:bg-gray-800">
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 bg-slate-900 dark:bg-sky-600 text-white rounded-lg font-semibold hover:bg-slate-800 dark:hover:bg-sky-500 transition shadow-xs disabled:opacity-60 cursor-pointer"
                        >
                            <span wire:loading.remove>Simpan Akun Siswa</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- MODAL KELOLA TAG KEAHLIAN SISWA (BERI & CABUT TAG) --}}
    @if($editingTagStudentId)
        @php
            $targetStudent = \App\Models\User::find($editingTagStudentId);
            $currentTags = $targetStudent?->keahlian_list ?? [];
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity" wire:click="closeTagModal"></div>
            
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-xl max-w-lg w-full p-6 border border-slate-200 dark:border-gray-700 z-10">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-gray-800">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-gray-50">Kelola Tag Keahlian Siswa</h3>
                        <p class="text-xs text-slate-500 dark:text-gray-400">Siswa: <strong class="text-slate-800 dark:text-gray-100">{{ $targetStudent?->name }}</strong> ({{ $targetStudent?->kelas ?? '-' }})</p>
                    </div>
                    <button type="button" wire:click="closeTagModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Tag Terverifikasi Saat Ini --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold text-slate-800 dark:text-gray-100 uppercase tracking-wider">
                            Tag Terverifikasi Saat Ini ({{ count($currentTags) }})
                        </label>
                        <span class="text-[11px] text-slate-400 dark:text-gray-500">Klik "Cabut" untuk menghapus tag</span>
                    </div>

                    @if(count($currentTags) > 0)
                        <div class="flex flex-wrap gap-2 p-3 bg-slate-50 dark:bg-gray-800 border border-slate-200/80 dark:border-gray-700 rounded-xl">
                            @foreach($currentTags as $tag)
                                <span class="inline-flex items-center gap-1.5 pl-3 pr-2 py-1 rounded-lg text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                                    <span>✓ {{ $tag }}</span>
                                    <button 
                                        type="button" 
                                        wire:click="revokeTag({{ $targetStudent->id }}, '{{ $tag }}')"
                                        class="inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[10px] font-bold text-rose-700 dark:text-rose-300 bg-white dark:bg-gray-900 hover:bg-rose-50 dark:hover:bg-rose-500/20 border border-rose-200 dark:border-rose-500/30 rounded transition cursor-pointer"
                                        title="Cabut tag {{ $tag }}"
                                    >
                                        Cabut &times;
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 bg-slate-50 dark:bg-gray-800 border border-slate-200/80 dark:border-gray-700 rounded-xl text-center text-xs text-slate-400 dark:text-gray-500">
                            Siswa ini belum memiliki tag keahlian terverifikasi.
                        </div>
                    @endif
                </div>

                {{-- Berikan Tag Tambahan --}}
                <div>
                    <label class="text-xs font-bold text-slate-800 dark:text-gray-100 uppercase tracking-wider block mb-1">
                        Berikan Tag Keahlian Baru
                    </label>
                    <p class="text-xs text-slate-500 dark:text-gray-400 mb-3">
                        Klik tag di bawah ini untuk memberikannya ke akun siswa. Satu akun dapat memiliki lebih dari satu tag:
                    </p>

                    <div class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto p-1 mb-2">
                        @foreach($tagPresets as $tag)
                            @php
                                $alreadyHas = in_array($tag, $currentTags, true);
                            @endphp
                            @if(!$alreadyHas)
                                <button
                                    type="button"
                                    wire:click="giveTag({{ $targetStudent->id }}, '{{ $tag }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-gray-200 hover:border-sky-400 hover:bg-sky-50 dark:hover:bg-sky-500/20 hover:text-sky-700 dark:hover:text-sky-300 transition cursor-pointer"
                                >
                                    <span class="text-sky-600 dark:text-sky-400 font-bold">+</span>
                                    <span>{{ $tag }}</span>
                                </button>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-400 dark:text-gray-500 opacity-60 cursor-not-allowed">
                                    <span>✓ {{ $tag }}</span>
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-slate-100 dark:border-gray-800 mt-5">
                    <button 
                        type="button" 
                        wire:click="closeTagModal" 
                        class="px-5 py-2 bg-slate-900 dark:bg-sky-600 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 dark:hover:bg-sky-500 transition shadow-xs cursor-pointer"
                    >
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL DETAIL SISWA & PORTOFOLIO (PRIVACY-SAFE) --}}
    @if($viewingStudentId && $viewingStudent)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity" wire:click="closeViewModal"></div>

            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-xl max-w-2xl w-full max-h-[85vh] flex flex-col border border-slate-200 dark:border-gray-700 z-10 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-gray-800 bg-slate-50 dark:bg-gray-800/50">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-gray-50">Profil & Portofolio Siswa</h3>
                        <p class="text-xs text-slate-500 dark:text-gray-400">Informasi akademik & kurasi karya</p>
                    </div>
                    <button type="button" wire:click="closeViewModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-6">
                    {{-- Student Header Info --}}
                    <div class="flex items-start gap-4">
                        @if($viewingStudent->photo_url)
                            <img src="{{ $viewingStudent->photo_url }}" class="w-14 h-14 rounded-full object-cover border border-slate-200 dark:border-gray-700 flex-shrink-0">
                        @else
                            <div class="w-14 h-14 rounded-full bg-slate-200 dark:bg-gray-800 text-slate-600 dark:text-gray-300 font-bold text-lg flex items-center justify-center flex-shrink-0">
                                {{ $viewingStudent->initials() }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-gray-50">{{ $viewingStudent->name }}</h4>
                            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">
                                {{ $viewingStudent->kelas ?: 'Belum diisi kelas' }} • NISN: {{ $viewingStudent->nisn ?: '-' }}
                            </p>
                            <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                @forelse($viewingStudent->keahlian_list as $tag)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                                        ✓ {{ $tag }}
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-400 dark:text-gray-500 italic">Belum ada tag terverifikasi</span>
                                @endforelse
                                <a 
                                    href="{{ $viewingStudent->profil_url }}" 
                                    target="_blank" 
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-sky-600 dark:text-sky-400 hover:underline ml-2"
                                >
                                    Buka Profil Publik ↗
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Bio --}}
                    @if($viewingStudent->bio)
                        <div class="p-3.5 bg-slate-50 dark:bg-gray-800 rounded-xl border border-slate-200/80 dark:border-gray-700 text-xs">
                            <p class="text-slate-600 dark:text-gray-300 leading-relaxed">{{ $viewingStudent->bio }}</p>
                        </div>
                    @endif

                    {{-- Daftar Karya --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-xs font-bold text-slate-900 dark:text-gray-50 uppercase tracking-wider">
                                Daftar Karya ({{ $viewingStudent->karyaSiswas->count() }})
                            </h5>
                            @php
                                $hasReview = $viewingStudent->karyaSiswas->where('status', 'review')->count() > 0;
                            @endphp
                            @if($hasReview)
                                <a 
                                    href="{{ route('karya-siswa.verifikasi', ['userId' => $viewingStudent->id]) }}" 
                                    wire:navigate
                                    class="text-xs font-bold text-amber-700 dark:text-amber-400 hover:text-amber-900 underline flex items-center gap-1"
                                >
                                    <span>⚠️ Verifikasi Karya Menunggu Review</span>
                                </a>
                            @endif
                        </div>

                        <div class="space-y-2">
                            @forelse($viewingStudent->karyaSiswas as $karya)
                                <div class="p-3 border border-slate-200 dark:border-gray-700 rounded-xl flex items-center justify-between gap-3 text-xs bg-white dark:bg-gray-800/70">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900 dark:text-gray-100 truncate">{{ $karya->judul }}</p>
                                        <p class="text-[11px] text-slate-500 dark:text-gray-400">{{ ucfirst($karya->kategori ?? 'Umum') }} • {{ $karya->tahun ?? '-' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if($karya->status === 'published')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                                                Published
                                            </span>
                                        @elseif($karya->status === 'review')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30">
                                                Review
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300">
                                                Draft
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 dark:text-gray-500 italic py-2">Siswa belum memiliki karya.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="px-6 py-3 border-t border-slate-200 dark:border-gray-800 bg-slate-50 dark:bg-gray-800/50 flex items-center justify-end">
                    <button type="button" wire:click="closeViewModal" class="px-4 py-2 border border-slate-300 dark:border-gray-700 rounded-lg text-xs font-semibold text-slate-700 dark:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-800">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
