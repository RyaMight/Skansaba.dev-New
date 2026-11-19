<button
    type="button"
    wire:click="toggle"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-70"
    aria-pressed="{{ $liked ? 'true' : 'false' }}"
    aria-label="{{ ($liked ? __('Batalkan suka') : __('Sukai karya ini')) . ' (' . $count . ')' }}"
    title="{{ $liked ? __('Batalkan suka') : __('Sukai karya ini') }}"
    @class([
        'group inline-flex items-center rounded-full border font-semibold transition-all duration-200 cursor-pointer disabled:cursor-wait hover:-translate-y-0.5 active:translate-y-0 active:scale-95',
        'gap-2 px-4 py-2 text-sm shadow-sm' => ! $overlay,
        'gap-2 px-3.5 py-2 text-sm shadow-lg backdrop-blur-md hover:scale-105' => $overlay,
        'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-400' => $liked,
        'border-slate-200 bg-white text-slate-600 hover:border-rose-200 hover:text-rose-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-rose-500/40 dark:hover:text-rose-400' => ! $liked && ! $overlay,
        'border-white/70 bg-white/95 text-slate-800 hover:border-rose-200 hover:text-rose-600 dark:border-gray-600 dark:bg-gray-900/90 dark:text-gray-100 dark:hover:border-rose-500/40 dark:hover:text-rose-400' => ! $liked && $overlay,
    ])
>
    <svg class="h-5 w-5 transition-transform duration-200 {{ $liked ? 'scale-110' : 'group-hover:scale-110' }}" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
    </svg>
    <span>{{ __('Suka') }}</span>
    <span class="tabular-nums">{{ $count }}</span>
</button>