@props([
    'title' => 'Panduan & Contoh',
    'align' => 'left', // 'left' or 'right'
    'example' => null,
    'tips' => null,
])

<span class="relative inline-flex items-center align-middle" x-data="{ open: false }">
    <button
        type="button"
        @click.stop="open = !open"
        class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-sky-100 dark:bg-sky-500/20 hover:bg-sky-200 dark:hover:bg-sky-500/30 text-[#0284c7] dark:text-sky-300 font-bold text-[10px] leading-none transition-transform hover:scale-110 cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-400"
        title="{{ __('Klik untuk melihat panduan & contoh pengisian') }}"
        aria-label="{{ __('Panduan pengisian') }}"
    >
        !
    </button>

    <div
        x-show="open"
        x-cloak
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        @class([
            'absolute z-50 top-6 w-80 sm:w-96 max-w-[calc(100vw-2rem)] p-4 bg-white dark:bg-gray-900 rounded-xl shadow-xl border border-sky-100 dark:border-gray-700 text-left text-xs',
            'left-0 origin-top-left' => $align === 'left',
            'right-0 origin-top-right' => $align === 'right',
        ])
    >
        {{-- Header Popover --}}
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-4 h-4 rounded-full bg-sky-500 text-white font-bold text-[9px]">!</span>
                <span class="font-bold text-gray-900 dark:text-gray-50 text-xs">{{ __($title) }}</span>
            </div>
            <button
                type="button"
                @click="open = false"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-0.5 rounded transition cursor-pointer"
                title="{{ __('Tutup') }}"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Isi Penjelasan --}}
        <div class="space-y-2.5 text-gray-600 dark:text-gray-300 leading-relaxed text-[11px]">
            <div>
                {{ $slot }}
            </div>

            @if($example)
                <div class="p-2.5 bg-slate-50 dark:bg-gray-800/80 border border-slate-200/80 dark:border-gray-700 rounded-lg space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-slate-700 dark:text-gray-200 text-[10px] uppercase tracking-wider">{{ __('Contoh Pengisian:') }}</span>
                        <span class="text-[10px] text-sky-600 dark:text-sky-400">{{ __('Bisa kamu tiru formatnya') }}</span>
                    </div>
                    <div class="font-mono text-slate-800 dark:text-gray-100 text-[11px] leading-snug bg-white dark:bg-gray-900 p-2 rounded border border-slate-200 dark:border-gray-700 select-all">
                        {{ $example }}
                    </div>
                </div>
            @endif

            @if($tips)
                <div class="flex items-start gap-1.5 p-2 bg-sky-50/80 dark:bg-sky-500/15 border border-sky-200/70 dark:border-sky-500/30 rounded-lg text-sky-900 dark:text-sky-200 text-[10px] leading-relaxed">
                    <svg class="w-3.5 h-3.5 text-sky-600 dark:text-sky-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <strong class="font-semibold">{{ __('Tips:') }}</strong> {{ $tips }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</span>
