<div class="grid grid-flow-col auto-cols-[minmax(210px,1fr)] grid-rows-3 gap-4 overflow-x-auto no-scrollbar pb-2 snap-x">
    @foreach($karyaDummy as $karya)
        <div class="snap-start bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-slate-100 dark:border-gray-800 shadow-xs group hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col">
            {{-- Cover karya --}}
            <div class="w-full aspect-[16/10] bg-gradient-to-br from-slate-100 to-slate-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center text-3xl group-hover:scale-[1.04] transition-transform duration-500">
                {{ $karya['emoji'] }}
            </div>

            <div class="p-3 flex flex-col flex-1">
                <h4 class="font-bold text-slate-900 dark:text-gray-50 text-[13px] leading-snug tracking-tight line-clamp-1">{{ $karya['judul'] }}</h4>
                <p class="text-[11px] text-slate-400 dark:text-gray-400 mt-0.5">{{ $karya['siswa'] }}</p>
                <div class="mt-auto pt-2.5">
                    <span class="inline-block px-2.5 py-0.5 bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-600 dark:text-gray-300 rounded-full text-[10px] font-medium">
                        {{ $karya['kategori'] }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach
</div>
