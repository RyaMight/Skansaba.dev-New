@php
    $currentLocale = app()->getLocale();
@endphp

<div class="inline-flex items-center h-9 select-none notranslate">

    <div class="inline-flex items-stretch h-full gap-1">
        {{-- ID Option --}}
        <a 
            href="{{ route('locale.switch', 'id') }}" 
            onclick="document.cookie='googtrans=/id/id; path=/;'; document.cookie='googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';"
            class="inline-flex items-center justify-center h-full px-3 rounded-lg text-xs font-bold transition-all duration-200 {{ $currentLocale === 'id' ? 'text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}"
            @if($currentLocale === 'id') style="background-color: {{ $globalSettings->primary_color_style }}" @endif
            title="Bahasa Indonesia (ID)"
            aria-label="Pilih Bahasa Indonesia"
        >
            ID
        </a>

        {{-- EN Option --}}
        <a 
            href="{{ route('locale.switch', 'en') }}" 
            onclick="document.cookie='googtrans=/id/en; path=/;';"
            class="inline-flex items-center justify-center h-full px-3 rounded-lg text-xs font-bold transition-all duration-200 {{ $currentLocale === 'en' ? 'text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}"
            @if($currentLocale === 'en') style="background-color: {{ $globalSettings->primary_color_style }}" @endif
            title="English (EN)"
            aria-label="Select English"
        >
            EN
        </a>
    </div>
</div>
