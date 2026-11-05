@php
    $currentLocale = app()->getLocale();
@endphp

<div class="inline-flex items-center p-0.5 rounded-xl bg-gray-100 dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 text-xs font-semibold select-none shadow-2xs transition-colors notranslate">
    {{-- Translate Icon --}}
    <span class="pl-1.5 pr-1 text-gray-400 dark:text-gray-400 flex items-center justify-center">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
        </svg>
    </span>

    <div class="inline-flex items-center gap-0.5">
        {{-- ID Option --}}
        <a 
            href="{{ route('locale.switch', 'id') }}" 
            onclick="document.cookie='googtrans=/id/id; path=/;'; document.cookie='googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';"
            class="px-2 py-1 rounded-lg text-xs font-bold transition-all duration-150 {{ $currentLocale === 'id' ? 'bg-white dark:bg-gray-700 text-sky-600 dark:text-sky-400 shadow-2xs' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}"
            title="Bahasa Indonesia (ID)"
            aria-label="Pilih Bahasa Indonesia"
        >
            ID
        </a>

        {{-- Divider --}}
        <span class="text-gray-300 dark:text-gray-600 text-[10px] select-none">/</span>

        {{-- EN Option --}}
        <a 
            href="{{ route('locale.switch', 'en') }}" 
            onclick="document.cookie='googtrans=/id/en; path=/;';"
            class="px-2 py-1 rounded-lg text-xs font-bold transition-all duration-150 {{ $currentLocale === 'en' ? 'bg-white dark:bg-gray-700 text-sky-600 dark:text-sky-400 shadow-2xs' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}"
            title="English (EN)"
            aria-label="Select English"
        >
            EN
        </a>
    </div>
</div>
