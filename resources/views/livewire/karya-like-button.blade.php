<button
    type="button"
    wire:click="toggle"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-70"
    aria-pressed="{{ $liked ? 'true' : 'false' }}"
    title="{{ $liked ? __('Batalkan suka') : __('Sukai karya ini') }}"
    class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-semibold transition-colors cursor-pointer disabled:cursor-wait {{ $liked ? 'border-rose-200 dark:border-rose-500/40 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400' : 'border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-500 dark:text-gray-400 hover:border-rose-200 dark:hover:border-rose-500/40 hover:text-rose-600 dark:hover:text-rose-400' }}"
>
    <svg class="w-3.5 h-3.5 {{ $liked ? 'scale-110' : '' }} transition-transform duration-200" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
    </svg>
    <span>{{ $count }}</span>
    <span class="sr-only">{{ $liked ? __('Suka') : __('Sukai') }}</span>
</button>