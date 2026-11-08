<div class="py-6">
    <div class="site-container">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="type-section-title text-gray-900 dark:text-gray-50">{{ __('Pesan Kontak') }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Kelola pesan dari formulir kontak website') }}</p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
        </div>
        @endif

        {{-- Filters & Search --}}
        <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                {{-- Search --}}
                <div class="flex-1">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="{{ __('Cari nama, email, atau isi pesan...') }}"
                        class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm"
                    >
                </div>

                {{-- Filter --}}
                <div class="flex gap-2">
                    <button
                        wire:click="$set('filter', 'all')"
                        class="px-4 py-2 text-sm font-bold rounded-lg transition-colors {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}"
                    >
                        {{ __('Semua') }} ({{ $messages->total() }})
                    </button>
                    <button
                        wire:click="$set('filter', 'unread')"
                        class="px-4 py-2 text-sm font-bold rounded-lg transition-colors {{ $filter === 'unread' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}"
                    >
                        {{ __('Belum Dibaca') }} ({{ $unreadCount }})
                    </button>
                    <button
                        wire:click="$set('filter', 'read')"
                        class="px-4 py-2 text-sm font-bold rounded-lg transition-colors {{ $filter === 'read' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}"
                    >
                        {{ __('Sudah Dibaca') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Messages List --}}
        <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden">
            @forelse($messages as $message)
            <div
                wire:click="viewMessage({{ $message->id }})"
                class="p-4 border-b border-gray-200 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition-colors {{ !$message->is_read ? 'bg-blue-50 dark:bg-blue-900/10' : '' }}"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            @if(!$message->is_read)
                            <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                            @endif
                            <h3 class="type-card-title-xs text-gray-900 dark:text-gray-50">{{ $message->name }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $message->email }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ $message->message }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->diffForHumans() }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">{{ __('Tidak ada pesan ditemukan') }}</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($messages->hasPages())
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
        @endif
    </div>

    {{-- Message Detail Modal --}}
    @if($selectedMessage)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" wire:click.self="closeDetail">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-800">
                <h2 class="type-subsection-title text-gray-900 dark:text-gray-50">{{ __('Detail Pesan') }}</h2>
                <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Nama') }}</label>
                    <p class="text-gray-900 dark:text-gray-50 mt-1">{{ $selectedMessage->name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</label>
                    <a href="mailto:{{ $selectedMessage->email }}" class="text-blue-600 dark:text-blue-400 hover:underline mt-1 block">
                        {{ $selectedMessage->email }}
                    </a>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Pesan') }}</label>
                    <p class="text-gray-900 dark:text-gray-50 mt-1 whitespace-pre-wrap">{{ $selectedMessage->message }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-800">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Waktu Kirim') }}</label>
                        <p class="text-sm text-gray-900 dark:text-gray-50 mt-1">{{ $selectedMessage->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</label>
                        <p class="text-sm text-gray-900 dark:text-gray-50 mt-1">
                            @if($selectedMessage->is_read)
                            <span class="text-green-600">{{ __('Sudah Dibaca') }}</span>
                            @else
                            <span class="text-blue-600">{{ __('Belum Dibaca') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between p-6 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800">
                <button
                    wire:click="deleteMessage({{ $selectedMessage->id }})"
                    wire:confirm="{{ __('Yakin ingin menghapus pesan ini?') }}"
                    class="px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                >
                    {{ __('Hapus Pesan') }}
                </button>
                <div class="flex gap-2">
                    <a
                        href="mailto:{{ $selectedMessage->email }}"
                        class="px-4 py-2 text-sm font-bold bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        {{ __('Balas via Email') }}
                    </a>
                    <button
                        wire:click="closeDetail"
                        class="px-4 py-2 text-sm font-bold bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-50 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                    >
                        {{ __('Tutup') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
