@php
    $currentLocale = app()->getLocale();
@endphp

<div class="inline-flex items-center p-0.5 rounded-xl bg-gray-100 dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 text-xs font-semibold select-none shadow-2xs transition-colors notranslate">


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
