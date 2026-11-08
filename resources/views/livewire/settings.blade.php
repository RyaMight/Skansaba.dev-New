<div class="p-6 max-w-3xl mx-auto space-y-6">
    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- Banner Peringatan Password Default --}}
    @if(!empty($isDefaultPassword))
        <div class="p-4 rounded-xl flex items-start gap-3.5 border" style="background-color: #fef3c7 !important; border-color: #fcd34d !important; color: #000000 !important;">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5" style="background-color: #fde68a !important; color: #b45309 !important;">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="type-card-title-xs" style="color: #000000 !important;">{{ __('Password Anda Masih Default') }}</h3>
                <p class="text-xs mt-0.5 leading-relaxed" style="color: #000000 !important;">
                    {!! __('Demi keamanan akun dan portofolio karya Anda, segera ganti password bawaan sekolah (:code) menggunakan formulir di bawah ini.', ['code' => '<code class="px-1.5 py-0.5 rounded font-mono font-bold border" style="background-color: #fde68a !important; color: #000000 !important; border-color: #f59e0b !important;">' . \App\Models\User::DEFAULT_SISWA_PASSWORD . '</code>']) !!}
                </p>
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <div>
        <h1 class="type-section-title text-gray-900 dark:text-gray-50">{{ __('Pengaturan') }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Kelola akun dan keamanan Anda di sini.') }}</p>
    </div>

    {{-- Info Akun --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800">
        <h2 class="type-card-title-sm text-gray-900 dark:text-gray-50 mb-4 pb-2 border-b border-gray-100 dark:border-gray-800">{{ __('Informasi Akun') }}</h2>
        <div class="flex items-center gap-4">
            @if($user->photo_url)
                <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
            @else
                <div class="w-14 h-14 rounded-full flex items-center justify-center text-white text-lg font-semibold" style="background-color: {{ $globalSettings->primary_color_style }}">
                    {{ $user->initials() }}
                </div>
            @endif
            <div class="min-w-0">
                <p class="text-sm font-bold text-gray-900 dark:text-gray-50 truncate">{{ $user->name }}</p>
                @if($user->nisn)
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">NISN: {{ $user->nisn }}{{ $user->kelas ? ' • ' . $user->kelas : '' }}</p>
                @endif
                @if($user->email)
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                @endif
                <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $user->role_badge_color }}">
                    {{ $user->role_label }}
                </span>
            </div>
        </div>
    </div>

    {{-- Ubah Password --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-800">
        <h2 class="type-card-title-sm text-gray-900 dark:text-gray-50 mb-1">{{ __('Ubah Password') }}</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">{{ __('Gunakan password minimal 8 karakter yang kuat dan mudah Anda ingat.') }}</p>

        <form wire:submit.prevent="updateUserPassword" class="space-y-4">
            {{-- Password Saat Ini --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('Password Saat Ini') }}</label>
                <div class="relative" x-data="{ show: false }">
                    <input 
                        type="password" 
                        x-ref="input"
                        :type="show ? 'text' : 'password'"
                        wire:model="current_password" 
                        class="w-full px-3 py-2 pr-10 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none" 
                        placeholder="{{ __('Masukkan password saat ini') }}"
                        autocomplete="current-password"
                    >
                    <button 
                        type="button" 
                        @click="show = !show" 
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none cursor-pointer"
                        :aria-label="show ? 'Sembunyikan password' : 'Lihat password'"
                        tabindex="-1"
                    >
                        <svg x-show="show" x-cloak class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                        <svg x-show="!show" x-cloak class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('current_password') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Password Baru --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('Password Baru') }}</label>
                <div class="relative" x-data="{ show: false }">
                    <input 
                        type="password" 
                        x-ref="input"
                        :type="show ? 'text' : 'password'"
                        wire:model="password" 
                        class="w-full px-3 py-2 pr-10 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none" 
                        placeholder="{{ __('Minimal 8 karakter (bukan password default)') }}"
                        autocomplete="new-password"
                    >
                    <button 
                        type="button" 
                        @click="show = !show" 
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none cursor-pointer"
                        :aria-label="show ? 'Sembunyikan password' : 'Lihat password'"
                        tabindex="-1"
                    >
                        <svg x-show="show" x-cloak class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                        <svg x-show="!show" x-cloak class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('password') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">{{ __('Konfirmasi Password Baru') }}</label>
                <div class="relative" x-data="{ show: false }">
                    <input 
                        type="password" 
                        x-ref="input"
                        :type="show ? 'text' : 'password'"
                        wire:model="password_confirmation" 
                        class="w-full px-3 py-2 pr-10 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none" 
                        placeholder="{{ __('Ulangi password baru') }}"
                        autocomplete="new-password"
                    >
                    <button 
                        type="button" 
                        @click="show = !show" 
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none cursor-pointer"
                        :aria-label="show ? 'Sembunyikan password' : 'Lihat password'"
                        tabindex="-1"
                    >
                        <svg x-show="show" x-cloak class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                        <svg x-show="!show" x-cloak class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end pt-2 border-t border-gray-100 dark:border-gray-800">
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg text-sm font-bold transition cursor-pointer flex items-center gap-2 disabled:opacity-50"
                >
                    <span wire:loading wire:target="updateUserPassword" class="inline-block animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full"></span>
                    {{ __('Simpan Password') }}
                </button>
                <button 
                    type="button" 
                    wire:click="$refresh" 
                    class="ml-2 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer"
                >
                    {{ __('Reset') }}
                </button>
            </div>
        </form>
    </div>
</div>
