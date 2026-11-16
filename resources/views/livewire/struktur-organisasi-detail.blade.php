<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    {{-- Header --}}
    <section class="bg-white dark:bg-gray-950 py-8 lg:py-10">
        <div class="site-container">
            
            {{-- Back Button --}}
            <div class="flex justify-center">
                <a href="/struktur-organisasi" class="btn-morph btn-morph--primary type-button inline-flex">
                    <svg class="btn-morph__icon btn-morph__icon--back w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Kembali ke Struktur Organisasi') }}
                </a>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-14 lg:py-20">
        <div class="site-container">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm overflow-hidden">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8">
                        {{-- Photo --}}
                        <div class="md:w-1/3">
                            @if($member->foto_url)
                                <img src="{{ $member->foto_url }}" alt="{{ $member->nama }}" class="w-full aspect-square object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full aspect-square flex items-center justify-center text-white text-6xl font-bold rounded-lg shadow-md" style="background: linear-gradient(135deg, {{ $globalSettings->primary_color_style }} 0%, {{ $globalSettings->primary_color_style }}cc 100%)">
                                    {{ strtoupper(substr($member->nama, 0, 2)) }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="md:w-2/3">
                            <h1 class="type-page-title text-gray-900 dark:text-gray-50 mb-2">{{ $member->nama }}</h1>
                            <p class="type-lead font-medium mb-6" style="color: {{ $globalSettings->primary_color_style }}">{{ $member->jabatan }}</p>

                            {{-- Description --}}
                            @if($member->deskripsi_md)
                                <div class="prose prose-lg max-w-none text-gray-600 dark:text-gray-300">
                                    {!! $member->getDeskripsiHtmlAttribute() !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Related Members --}}
            @if($relatedMembers->count() > 0)
                <div class="mt-12">
                    <h2 class="type-section-title text-gray-900 dark:text-gray-50 mb-6">{{ __('Anggota Lainnya') }}</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($relatedMembers as $related)
                            <a href="/struktur-organisasi/{{ $related->id }}" class="bg-white dark:bg-gray-900 rounded-xl overflow-hidden hover:shadow-lg transition-shadow group">
                                @if($related->foto_url)
                                    <img src="{{ $related->foto_url }}" alt="{{ $related->nama }}" class="w-full aspect-video object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full aspect-video flex items-center justify-center text-white text-4xl font-bold" style="background: linear-gradient(135deg, {{ $globalSettings->primary_color_style }} 0%, {{ $globalSettings->primary_color_style }}cc 100%)">
                                        {{ strtoupper(substr($related->nama, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="type-card-title-sm text-gray-900 dark:text-gray-50 mb-1">{{ $related->nama }}</h3>
                                    <p class="type-body-sm" style="color: {{ $globalSettings->primary_color_style }}">{{ $related->jabatan }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
