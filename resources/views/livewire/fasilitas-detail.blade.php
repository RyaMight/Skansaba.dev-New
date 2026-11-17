<div class="min-h-screen bg-gray-50 dark:bg-slate-900">
    {{-- Header --}}
    <section class="bg-white dark:bg-slate-900 py-8 lg:py-10">
        <div class="site-container">
            
            {{-- Back Button --}}
            <div class="flex justify-center">
                <a href="/fasilitas" class="btn-morph btn-morph--primary type-button inline-flex">
                    <svg class="btn-morph__icon btn-morph__icon--back w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Kembali ke Fasilitas') }}
                </a>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-14 lg:py-20">
        <div class="site-container">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm overflow-hidden">
                {{-- Featured Image --}}
                @if($fasilitas->gambar_url)
                    <img src="{{ $fasilitas->gambar_url }}" alt="{{ $fasilitas->tempat }}" class="w-full aspect-video object-cover">
                @else
                    <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300"></div>
                @endif

                <div class="p-8">
                    {{-- Title --}}
                    <h1 class="type-page-title text-gray-900 dark:text-gray-50 mb-6">{{ $fasilitas->tempat }}</h1>

                    {{-- Description --}}
                    @if($fasilitas->deskripsi)
                        <div class="prose prose-lg max-w-none mb-8">
                            {!! $fasilitas->deskripsi_html !!}
                        </div>
                    @endif

                    {{-- Fasilitas List --}}
                    @if($fasilitas->fasilitas && count($fasilitas->fasilitas) > 0)
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 border-2" style="border-color: {{ $globalSettings->primary_color_style }}22">
                            <h3 class="type-subsection-title text-gray-900 dark:text-gray-50 mb-4">{{ __('Fasilitas yang Tersedia') }}</h3>
                            <div class="grid md:grid-cols-2 gap-3">
                                @foreach($fasilitas->fasilitas as $facility)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 shrink-0" style="color: {{ $globalSettings->primary_color_style }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 dark:text-gray-200">{{ $facility }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Related Fasilitas --}}
            @if($relatedFasilitas->count() > 0)
                <div class="mt-12">
                    <h2 class="type-section-title text-gray-900 dark:text-gray-50 mb-6">{{ __('Fasilitas Lainnya') }}</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($relatedFasilitas as $related)
                            <a href="/fasilitas/{{ $related->id }}" class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition group">
                                @if($related->gambar_url)
                                    <img src="{{ $related->gambar_url }}" alt="{{ $related->tempat }}" class="w-full aspect-video object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full aspect-video bg-gradient-to-br from-gray-200 to-gray-300"></div>
                                @endif
                                <div class="p-4">
                                    <h3 class="type-card-title-sm text-gray-900 dark:text-gray-50 line-clamp-2">{{ $related->tempat }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
