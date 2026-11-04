<div class="grid grid-flow-col auto-cols-[minmax(210px,1fr)] grid-rows-3 gap-4 overflow-x-auto no-scrollbar pb-2 snap-x">
    @foreach($profilDummy as $dev)
        <div class="snap-start bg-white dark:bg-gray-900 rounded-2xl p-3 border border-slate-100 dark:border-gray-800 shadow-xs flex flex-col hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="flex-1">
                {{-- Avatar Placeholder --}}
                <div class="w-full aspect-[16/10] bg-[#d3d9e2] dark:bg-gray-800 rounded-xl flex items-center justify-center overflow-hidden">
                    <svg class="w-9 h-9 text-[#9aa6b8] dark:text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 18a7 7 0 0114 0H5z" clip-rule="evenodd" />
                    </svg>
                </div>

                {{-- Student Info --}}
                <h4 class="font-bold text-slate-900 dark:text-gray-50 text-[13px] mt-2.5 tracking-tight line-clamp-1">{{ $dev['nama'] }}</h4>
                <p class="text-[11px] text-slate-400 dark:text-gray-400 mt-0.5">{{ $dev['role'] }}</p>
                <p class="text-[10.5px] text-slate-500 dark:text-gray-400 mt-1 leading-snug line-clamp-2">{{ $dev['desc'] }}</p>
            </div>

            {{-- Tag Badge --}}
            <div class="mt-2.5">
                <span class="inline-block px-2.5 py-0.5 bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-600 dark:text-gray-300 rounded-full text-[10px] font-medium">
                    {{ $dev['badge'] }}
                </span>
            </div>
        </div>
    @endforeach
</div>
