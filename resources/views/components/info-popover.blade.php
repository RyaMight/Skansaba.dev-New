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
        class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-sky-100 hover:bg-sky-200 text-[#0284c7] font-bold text-[10px] leading-none transition-transform hover:scale-110 cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-400"
        title="Klik untuk melihat panduan & contoh pengisian"
        aria-label="Panduan pengisian"
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
            'absolute z-50 top-6 w-80 sm:w-96 max-w-[calc(100vw-2rem)] p-4 bg-white rounded-xl shadow-xl border border-sky-100 text-left text-xs',
            'left-0 origin-top-left' => $align === 'left',
            'right-0 origin-top-right' => $align === 'right',
        ])
    >
        {{-- Header Popover --}}
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-4 h-4 rounded-full bg-sky-500 text-white font-bold text-[9px]">!</span>
                <span class="font-bold text-gray-900 text-xs">{{ $title }}</span>
            </div>
            <button
                type="button"
                @click="open = false"
                class="text-gray-400 hover:text-gray-600 p-0.5 rounded transition cursor-pointer"
                title="Tutup"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Isi Penjelasan --}}
        <div class="space-y-2.5 text-gray-600 leading-relaxed text-[11px]">
            <div>
                {{ $slot }}
            </div>

            @if($example)
                <div class="p-2.5 bg-slate-50 border border-slate-200/80 rounded-lg space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-slate-700 text-[10px] uppercase tracking-wider">Contoh Pengisian:</span>
                        <span class="text-[10px] text-sky-600">Bisa kamu tiru formatnya</span>
                    </div>
                    <div class="font-mono text-slate-800 text-[11px] leading-snug bg-white p-2 rounded border border-slate-200 select-all">
                        {{ $example }}
                    </div>
                </div>
            @endif

            @if($tips)
                <div class="flex items-start gap-1.5 p-2 bg-sky-50/80 border border-sky-200/70 rounded-lg text-sky-900 text-[10px] leading-relaxed">
                    <svg class="w-3.5 h-3.5 text-sky-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <strong class="font-semibold">Tips:</strong> {{ $tips }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</span>
