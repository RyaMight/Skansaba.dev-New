@props(['size' => 'sm', 'label' => 'Moderator'])

@php
    $sizeClasses = match ($size) {
        'xs' => 'px-1.5 py-0.5 text-[10px] gap-1',
        'md' => 'px-2.5 py-1 text-xs gap-1.5',
        default => 'px-2 py-0.5 text-[11px] gap-1',
    };

    $iconClasses = match ($size) {
        'xs' => 'w-2.5 h-2.5',
        'md' => 'w-3.5 h-3.5',
        default => 'w-3 h-3',
    };
@endphp

<span
    {{ $attributes->merge([
        'class' => "inline-flex items-center rounded-md font-bold bg-amber-50 dark:bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 {$sizeClasses}",
        'title' => 'Moderator siswa — membantu verifikasi karya dan keamanan akun',
    ]) }}
>
    <svg class="{{ $iconClasses }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
    </svg>
    <span>{{ $label }}</span>
</span>
