@php
    $currentLocale = app()->getLocale();
@endphp

{{-- Gaya "ID / EN" (teks + slash, tanpa pill) sesuai design hero-Lock.
     Aktif = warna primer, non-aktif = abu #99A1AF. Dipakai di semua navbar. --}}
<div class="inline-flex items-center h-9 select-none notranslate text-sm font-bold leading-none">
    <a
        href="{{ route('locale.switch', 'id') }}"
        onclick="document.cookie='googtrans=/id/id; path=/;'; document.cookie='googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';"
        class="w-10 text-center transition-colors {{ $currentLocale === 'id' ? '' : 'text-[#99A1AF] hover:text-gray-700 dark:hover:text-gray-200' }}"
        @if($currentLocale === 'id') style="color: {{ $globalSettings->primary_color_style }}" @endif
        title="Bahasa Indonesia (ID)"
        aria-label="Pilih Bahasa Indonesia"
    >ID</a>

    <span class="w-2 text-center text-[#99A1AF] select-none" aria-hidden="true">/</span>

    <a
        href="{{ route('locale.switch', 'en') }}"
        onclick="document.cookie='googtrans=/id/en; path=/;';"
        class="w-10 text-center transition-colors {{ $currentLocale === 'en' ? '' : 'text-[#99A1AF] hover:text-gray-700 dark:hover:text-gray-200' }}"
        @if($currentLocale === 'en') style="color: {{ $globalSettings->primary_color_style }}" @endif
        title="English (EN)"
        aria-label="Select English"
    >EN</a>
</div>
