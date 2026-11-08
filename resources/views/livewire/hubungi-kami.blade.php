<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30 dark:from-gray-950 dark:via-blue-950/20 dark:to-purple-950/20">
    {{-- Hero Header dengan Gradient & Ilustrasi --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500 dark:from-blue-900 dark:via-purple-900 dark:to-pink-900">
        {{-- Animated Background Shapes --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-blob"></div>
            <div class="absolute -top-10 -right-10 w-96 h-96 bg-purple-300/20 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-20 left-1/3 w-80 h-80 bg-pink-300/20 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
        </div>

        <div class="site-container relative z-10 py-10 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Left: Text Content --}}
                <div class="text-white">
                    {{-- Breadcrumb --}}
                    <div class="flex items-center gap-2 text-sm text-white/80 mb-6">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        <a href="/" class="hover:text-white transition-colors">{{ __('Beranda') }}</a>
                        <span>›</span>
                        <span class="text-white font-medium">{{ __('Hubungi Kami') }}</span>
                    </div>

                    <h1 class="type-page-title mb-6">
                        {{ __('Mari Terhubung') }} 
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-200 via-pink-200 to-purple-200 mt-2">
                            {{ __('Dengan Kami!') }}
                        </span>
                    </h1>
                    <p class="type-lead text-white/90 mb-6 sm:mb-8">
                        {{ __('Kami siap membantu Anda. Pilih cara terbaik untuk menghubungi kami.') }}
                    </p>

                    {{-- Stats Cards --}}
                    <div class="grid grid-cols-3 gap-2 sm:gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-2.5 sm:p-4 border border-white/20">
                            <div class="type-stat text-white mb-1">24/7</div>
                            <div class="text-[11px] sm:text-xs text-white/80">{{ __('Responsif') }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-2.5 sm:p-4 border border-white/20">
                            <div class="type-stat text-white mb-1">6+</div>
                            <div class="text-[11px] sm:text-xs text-white/80">{{ __('Channel') }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-2.5 sm:p-4 border border-white/20">
                            <div class="type-stat text-white mb-1">100%</div>
                            <div class="text-[11px] sm:text-xs text-white/80">{{ __('Profesional') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Right: Ilustrasi Animasi --}}
                <div class="hidden lg:flex items-center justify-center">
                    <div class="relative w-full max-w-md">
                        <svg class="w-full h-auto drop-shadow-2xl" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                            {{-- Phone Device --}}
                            <g class="animate-float">
                                <rect x="100" y="50" width="200" height="350" rx="25" fill="white" stroke="#E5E7EB" stroke-width="2"/>
                                <rect x="110" y="70" width="180" height="280" rx="10" fill="#F3F4F6"/>
                                
                                {{-- Notch --}}
                                <rect x="160" y="55" width="80" height="8" rx="4" fill="#1F2937"/>
                                
                                {{-- Chat Bubbles --}}
                                <rect x="130" y="100" width="120" height="30" rx="15" fill="#3B82F6" class="animate-pulse"/>
                                <rect x="130" y="140" width="90" height="30" rx="15" fill="#8B5CF6" class="animate-pulse animation-delay-1000"/>
                                <rect x="180" y="180" width="80" height="30" rx="15" fill="#EC4899" class="animate-pulse animation-delay-2000"/>
                                
                                {{-- Icons Floating --}}
                                <g class="animate-bounce">
                                    <circle cx="250" cy="120" r="20" fill="#25D366"/>
                                    <path d="M245 120 L250 125 L255 115" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                </g>
                                
                                <g class="animate-bounce animation-delay-1000">
                                    <circle cx="280" cy="180" r="18" fill="#E4405F"/>
                                    <rect x="273" y="173" width="14" height="14" rx="3" stroke="white" stroke-width="2" fill="none"/>
                                </g>
                                
                                <g class="animate-bounce animation-delay-2000">
                                    <circle cx="260" cy="240" r="16" fill="#1877F2"/>
                                    <path d="M265 235 L265 245 L255 240 L258 235 Z" fill="white"/>
                                </g>
                            </g>

                            {{-- Particles --}}
                            <circle cx="80" cy="100" r="4" fill="#FCD34D" class="animate-ping"/>
                            <circle cx="320" cy="150" r="3" fill="#A78BFA" class="animate-ping animation-delay-1000"/>
                            <circle cx="90" cy="250" r="5" fill="#F472B6" class="animate-ping animation-delay-2000"/>
                            <circle cx="340" cy="280" r="4" fill="#60A5FA" class="animate-ping animation-delay-3000"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wave Separator --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="w-full h-12 lg:h-16" viewBox="0 0 1440 120" fill="none" preserveAspectRatio="none">
                <path d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z" 
                      class="fill-gray-50 dark:fill-gray-950"/>
            </svg>
        </div>
    </section>

    <style>
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        .animation-delay-1000 {
            animation-delay: 1s;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-3000 {
            animation-delay: 3s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>

    {{-- Contact Channels dengan Desain Menarik --}}
    <section class="py-16 lg:py-24 relative">
        {{-- Decorative Elements --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-64 h-64 bg-blue-200/20 dark:bg-blue-800/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-80 h-80 bg-purple-200/20 dark:bg-purple-800/10 rounded-full blur-3xl"></div>
        </div>

        <div class="site-container relative z-10">
            <div class="max-w-6xl mx-auto">
                {{-- Section Title dengan Styling Menarik --}}
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-full mb-4">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <span class="text-sm font-semibold text-blue-900 dark:text-blue-100">{{ __('Saluran Kontak Kami') }}</span>
                    </div>
                    <h2 class="type-section-title text-gray-900 dark:text-gray-50 mb-4">
                        {{ __('Pilih platform yang') }} <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
                            {{ __('paling nyaman untuk Anda') }}
                        </span>
                    </h2>
                    <p class="type-lead text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        {{ __('Hubungi kami melalui platform favorit Anda atau isi formulir di bawah') }}
                    </p>
                </div>

                {{-- Contact Cards dengan Hover Effect Menarik --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-20">
                    {{-- WhatsApp --}}
                    @if($globalSettings->whatsapp)
                    <a
                        href="{{ $globalSettings->whatsapp_url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group relative bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-green-500/20 hover:-translate-y-2 border-2 border-transparent hover:border-green-500 overflow-hidden"
                    >
                        {{-- Animated Background --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-green-500/0 via-green-500/0 to-green-500/10 group-hover:from-green-500/10 group-hover:via-green-500/5 group-hover:to-green-500/20 transition-all duration-300"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center">
                            {{-- Icon dengan Ring Animation --}}
                            <div class="relative mb-6">
                                <div class="absolute inset-0 bg-green-500/20 rounded-full animate-ping"></div>
                                <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center transform group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-green-500/50">
                                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="type-subsection-title text-gray-900 dark:text-gray-50 mb-2">WhatsApp</h3>
                            <p class="type-body-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Chat langsung dengan kami') }}</p>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold text-green-600 dark:text-green-400 group-hover:gap-4 transition-all">
                                {{ __('Buka WhatsApp') }}
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                        
                        {{-- Corner Decoration --}}
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-green-500/10 rounded-full blur-2xl group-hover:bg-green-500/20 transition-colors"></div>
                    </a>
                    @endif

                    {{-- Instagram --}}
                    @if($globalSettings->instagram)
                    <a
                        href="{{ $globalSettings->instagram }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group relative bg-gradient-to-br from-pink-50 via-purple-50 to-yellow-50 dark:from-pink-900/20 dark:via-purple-900/20 dark:to-yellow-900/20 rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-pink-500/20 hover:-translate-y-2 border-2 border-transparent hover:border-pink-500 overflow-hidden"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-500/0 via-purple-500/0 to-yellow-500/0 group-hover:from-pink-500/10 group-hover:via-purple-500/10 group-hover:to-yellow-500/10 transition-all duration-300"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center">
                            <div class="relative mb-6">
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-500/20 via-purple-500/20 to-yellow-500/20 rounded-full animate-ping"></div>
                                <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-pink-500 via-purple-500 to-yellow-500 flex items-center justify-center transform group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-pink-500/50">
                                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="type-subsection-title text-gray-900 dark:text-gray-50 mb-2">Instagram</h3>
                            <p class="type-body-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Follow & DM kami') }}</p>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold text-pink-600 dark:text-pink-400 group-hover:gap-4 transition-all">
                                {{ __('Buka Instagram') }}
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-gradient-to-br from-pink-500/10 to-purple-500/10 rounded-full blur-2xl group-hover:from-pink-500/20 group-hover:to-purple-500/20 transition-colors"></div>
                    </a>
                    @endif

                    {{-- Email --}}
                    @if($globalSettings->contact_email)
                    <a
                        href="mailto:{{ $globalSettings->contact_email }}"
                        class="group relative bg-gradient-to-br from-blue-50 to-cyan-100 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/20 hover:-translate-y-2 border-2 border-transparent hover:border-blue-500 overflow-hidden"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-cyan-500/0 group-hover:from-blue-500/10 group-hover:to-cyan-500/10 transition-all duration-300"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center">
                            <div class="relative mb-6">
                                <div class="absolute inset-0 bg-blue-500/20 rounded-full animate-ping"></div>
                                <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center transform group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-blue-500/50">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="type-subsection-title text-gray-900 dark:text-gray-50 mb-2">Email</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 font-mono">{{ $globalSettings->contact_email }}</p>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 group-hover:gap-4 transition-all">
                                {{ __('Kirim Email') }}
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-colors"></div>
                    </a>
                    @endif

                    {{-- Facebook --}}
                    @if($globalSettings->facebook)
                    <a
                        href="{{ $globalSettings->facebook }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group relative bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-600/20 hover:-translate-y-2 border-2 border-transparent hover:border-blue-600 overflow-hidden"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/0 to-indigo-600/0 group-hover:from-blue-600/10 group-hover:to-indigo-600/10 transition-all duration-300"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center">
                            <div class="relative mb-6">
                                <div class="absolute inset-0 bg-blue-600/20 rounded-full animate-ping"></div>
                                <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center transform group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-blue-600/50">
                                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="type-subsection-title text-gray-900 dark:text-gray-50 mb-2">Facebook</h3>
                            <p class="type-body-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Kunjungi halaman kami') }}</p>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 group-hover:gap-4 transition-all">
                                {{ __('Buka Facebook') }}
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-blue-600/10 rounded-full blur-2xl group-hover:bg-blue-600/20 transition-colors"></div>
                    </a>
                    @endif

                    {{-- YouTube --}}
                    @if($globalSettings->youtube)
                    <a
                        href="{{ $globalSettings->youtube }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group relative bg-gradient-to-br from-red-50 to-pink-100 dark:from-red-900/20 dark:to-pink-900/20 rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-red-600/20 hover:-translate-y-2 border-2 border-transparent hover:border-red-600 overflow-hidden"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-red-600/0 to-pink-600/0 group-hover:from-red-600/10 group-hover:to-pink-600/10 transition-all duration-300"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center">
                            <div class="relative mb-6">
                                <div class="absolute inset-0 bg-red-600/20 rounded-full animate-ping"></div>
                                <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-red-600 to-pink-700 flex items-center justify-center transform group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-red-600/50">
                                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="type-subsection-title text-gray-900 dark:text-gray-50 mb-2">YouTube</h3>
                            <p class="type-body-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Subscribe channel kami') }}</p>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold text-red-600 dark:text-red-400 group-hover:gap-4 transition-all">
                                {{ __('Buka YouTube') }}
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-red-600/10 rounded-full blur-2xl group-hover:bg-red-600/20 transition-colors"></div>
                    </a>
                    @endif

                    {{-- TikTok --}}
                    @if($globalSettings->tiktok)
                    <a
                        href="{{ $globalSettings->tiktok }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group relative bg-gradient-to-br from-gray-50 to-slate-100 dark:from-gray-800/50 dark:to-slate-900/50 rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-gray-900/20 hover:-translate-y-2 border-2 border-transparent hover:border-gray-900 dark:hover:border-gray-100 overflow-hidden"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-900/0 to-slate-900/0 group-hover:from-gray-900/10 group-hover:to-slate-900/10 transition-all duration-300"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center">
                            <div class="relative mb-6">
                                <div class="absolute inset-0 bg-gray-900/20 dark:bg-gray-100/20 rounded-full animate-ping"></div>
                                <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-gray-900 to-slate-900 dark:from-gray-100 dark:to-slate-100 flex items-center justify-center transform group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-gray-900/50">
                                    <svg class="w-10 h-10 text-white dark:text-gray-900" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="type-subsection-title text-gray-900 dark:text-gray-50 mb-2">TikTok</h3>
                            <p class="type-body-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Follow TikTok kami') }}</p>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-gray-50 group-hover:gap-4 transition-all">
                                {{ __('Buka TikTok') }}
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-gray-900/10 dark:bg-gray-100/10 rounded-full blur-2xl group-hover:bg-gray-900/20 dark:group-hover:bg-gray-100/20 transition-colors"></div>
                    </a>
                    @endif
                </div>

                {{-- Form Kirim Pesan dengan Desain Lebih Menarik --}}
                <div class="relative bg-gradient-to-br from-white via-blue-50/50 to-purple-50/50 dark:from-gray-900 dark:via-blue-950/20 dark:to-purple-950/20 rounded-3xl border-2 border-gray-200/50 dark:border-gray-800/50 p-8 lg:p-12 shadow-xl overflow-hidden">
                    {{-- Decorative Background --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-br from-pink-400/10 to-purple-400/10 rounded-full blur-3xl"></div>
                    
                    <div class="max-w-3xl mx-auto relative z-10">
                        <div class="text-center mb-10">
                            {{-- Icon --}}
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 mb-4 shadow-lg shadow-blue-500/30">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            
                            <h2 class="type-section-title text-gray-900 dark:text-gray-50 mb-3">
                                {{ __('Kirim Pesan Langsung') }}
                            </h2>
                            <p class="type-lead text-gray-600 dark:text-gray-400">
                                {{ __('Atau isi formulir di bawah ini dan kami akan merespons secepatnya') }}
                            </p>
                        </div>

                        @if($successMessage)
                        <div class="mb-8 p-5 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-2 border-green-200 dark:border-green-800 rounded-2xl">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-xl bg-green-500 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="type-card-title-xs text-green-900 dark:text-green-100 mb-1">{{ __('Berhasil Terkirim!') }}</h4>
                                    <p class="text-sm text-green-800 dark:text-green-200">{{ $successMessage }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @error('form')
                        <div class="mb-8 p-5 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border-2 border-red-200 dark:border-red-800 rounded-2xl">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="type-card-title-xs text-red-900 dark:text-red-100 mb-1">{{ __('Terjadi Kesalahan') }}</h4>
                                    <p class="text-sm text-red-800 dark:text-red-200">{{ $message }}</p>
                                </div>
                            </div>
                        </div>
                        @enderror

                        <form wire:submit="submitMessage" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Nama --}}
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Nama Lengkap') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            id="name"
                                            wire:model="name"
                                            class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-50 transition-all @error('name') border-red-500 @enderror"
                                            placeholder="{{ __('Masukkan nama lengkap Anda') }}"
                                        >
                                    </div>
                                    @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Email') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <input
                                            type="email"
                                            id="email"
                                            wire:model="email"
                                            class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-50 transition-all @error('email') border-red-500 @enderror"
                                            placeholder="{{ __('Masukkan alamat email Anda') }}"
                                        >
                                    </div>
                                    @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Pesan --}}
                            <div>
                                <label for="message" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Pesan') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="message"
                                    wire:model="message"
                                    rows="6"
                                    class="w-full px-4 py-3.5 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-50 resize-none transition-all @error('message') border-red-500 @enderror"
                                    placeholder="{{ __('Tuliskan pesan Anda di sini...') }}"
                                ></textarea>
                                @error('message')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full group relative px-8 py-4 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 hover:from-blue-700 hover:via-purple-700 hover:to-pink-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-purple-500/40 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none overflow-hidden"
                            >
                                {{-- Button Background Animation --}}
                                <div class="absolute inset-0 bg-gradient-to-r from-pink-600 via-purple-600 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                <span class="relative flex items-center justify-center gap-2">
                                    <span wire:loading.remove wire:target="submitMessage">
                                        {{ __('Kirim Pesan') }}
                                        <svg class="inline w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </span>
                                    <span wire:loading wire:target="submitMessage" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ __('Mengirim...') }}
                                    </span>
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Informasi Sekolah & Map dengan Desain Lebih Menarik --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-20">
                    {{-- Info Kontak --}}
                    <div class="relative bg-gradient-to-br from-white via-indigo-50/30 to-purple-50/30 dark:from-gray-900 dark:via-indigo-950/20 dark:to-purple-950/20 rounded-3xl border-2 border-gray-200/50 dark:border-gray-800/50 p-8 shadow-xl overflow-hidden">
                        {{-- Decorative Elements --}}
                        <div class="absolute -top-12 -right-12 w-40 h-40 bg-gradient-to-br from-indigo-400/20 to-purple-400/20 rounded-full blur-3xl"></div>
                        
                        <div class="relative z-10">
                            {{-- Header --}}
                            <div class="mb-8">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 mb-4 shadow-lg shadow-indigo-500/30">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="type-subsection-title text-gray-900 dark:text-gray-50">
                                    {{ __('Informasi Kontak') }}
                                </h3>
                            </div>

                            {{-- Contact Items --}}
                            <div class="space-y-6">
                                @if($globalSettings->address)
                                <div class="group flex items-start gap-4 p-4 rounded-2xl hover:bg-white/50 dark:hover:bg-gray-800/50 transition-all duration-300">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('Alamat') }}</p>
                                        <p class="text-gray-900 dark:text-gray-50 font-medium leading-relaxed">{{ $globalSettings->address }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($globalSettings->contact_phone)
                                <div class="group flex items-start gap-4 p-4 rounded-2xl hover:bg-white/50 dark:hover:bg-gray-800/50 transition-all duration-300">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('Telepon') }}</p>
                                        <a href="tel:{{ $globalSettings->contact_phone }}" class="text-gray-900 dark:text-gray-50 font-medium hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                            {{ $globalSettings->contact_phone }}
                                        </a>
                                    </div>
                                </div>
                                @endif

                                @if($globalSettings->contact_email)
                                <div class="group flex items-start gap-4 p-4 rounded-2xl hover:bg-white/50 dark:hover:bg-gray-800/50 transition-all duration-300">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('Email') }}</p>
                                        <a href="mailto:{{ $globalSettings->contact_email }}" class="text-gray-900 dark:text-gray-50 font-medium hover:text-blue-600 dark:hover:text-blue-400 transition-colors break-all">
                                            {{ $globalSettings->contact_email }}
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <div class="group flex items-start gap-4 p-4 rounded-2xl hover:bg-white/50 dark:hover:bg-gray-800/50 transition-all duration-300">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('Jam Operasional') }}</p>
                                        <div class="space-y-1">
                                            <p class="text-gray-900 dark:text-gray-50 font-medium">{{ __('Senin - Jumat: 07:00 - 15:00 WIB') }}</p>
                                            <p class="text-gray-900 dark:text-gray-50 font-medium">{{ __('Sabtu: 07:00 - 12:00 WIB') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Google Maps --}}
                    <div class="relative bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 rounded-3xl border-2 border-gray-200/50 dark:border-gray-800/50 p-3 shadow-xl overflow-hidden">
                        @if($globalSettings->maps_url)
                        <div class="w-full h-full min-h-[450px] rounded-2xl overflow-hidden relative">
                            {{-- Overlay untuk memberikan frame effect --}}
                            <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-gray-900/10 dark:ring-white/10 pointer-events-none z-10"></div>
                            <div class="w-full h-full">
                                {!! $globalSettings->maps_url !!}
                            </div>
                        </div>
                        @else
                        <div class="w-full h-full min-h-[450px] rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 flex items-center justify-center relative overflow-hidden">
                            {{-- Decorative Grid --}}
                            <div class="absolute inset-0 opacity-10">
                                <div class="absolute inset-0" style="background-image: linear-gradient(to right, #6366f1 1px, transparent 1px), linear-gradient(to bottom, #6366f1 1px, transparent 1px); background-size: 40px 40px;"></div>
                            </div>
                            
                            <div class="text-center relative z-10">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 mb-4 shadow-lg">
                                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">{{ __('Peta belum tersedia') }}</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">{{ __('Google Maps akan ditampilkan di sini') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
