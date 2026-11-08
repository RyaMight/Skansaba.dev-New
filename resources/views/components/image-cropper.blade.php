@props([
    'modelName' => 'image',
    'cropDataName' => 'crop_data',
    'label' => 'Pilih Gambar',
    'aspectRatio' => 1,
    'aspectRatioLabel' => '1:1',
    'maxWidth' => null,
    'maxHeight' => null,
    'required' => false,
    'currentImage' => null,
    'previewSize' => 200,
    'isAvatar' => null,
    'avatarName' => null,
    'avatarSubtitle' => null,
    'avatarSkills' => null,
    'defaultFrameShape' => 'circle'
])

@php
    $resolvedAspectRatio = (float) $aspectRatio;
    if ($resolvedAspectRatio <= 0) {
        $resolvedAspectRatio = 1;
    }

    $resolvedMaxWidth = $maxWidth ?? 1200;
    $resolvedMaxHeight = $maxHeight ?? max(1, (int) round($resolvedMaxWidth / $resolvedAspectRatio));

    $resolvedViewportWidth = $previewSize;
    $resolvedViewportHeight = max(1, (int) round($resolvedViewportWidth / $resolvedAspectRatio));

    $resolvedAvatarSkills = is_array($avatarSkills)
        ? array_values(array_filter($avatarSkills))
        : ($avatarSkills ? array_values(array_filter(array_map('trim', explode(',', (string) $avatarSkills)))) : []);

    // Auto-detect avatar mode if aspectRatio is 1:1 unless explicitly specified
    $isAvatarMode = $isAvatar ?? (abs($resolvedAspectRatio - 1.0) < 0.0001);
    $croppieType = $isAvatarMode ? ($defaultFrameShape === 'squircle' ? 'square' : 'circle') : (abs($resolvedAspectRatio - 1.0) < 0.0001 ? 'square' : null);

    // Resolusi URL Foto Profil Saat Ini (hanya jika filenya valid dan ada di storage)
    $resolvedCurrentImageUrl = null;
    if ($currentImage) {
        if (str_starts_with($currentImage, 'http://') || str_starts_with($currentImage, 'https://')) {
            $resolvedCurrentImageUrl = $currentImage;
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($currentImage)) {
            $resolvedCurrentImageUrl = \Illuminate\Support\Facades\Storage::url($currentImage);
        }
    }
@endphp

<div 
    x-data="{
        croppie: null,
        showCropper: false,
        fileName: '',
        fileSizeFormatted: '',
        originalFile: null,
        cropResult: null,
        isAvatar: {{ $isAvatarMode ? 'true' : 'false' }},
        avatarSkills: {{ Js::from($resolvedAvatarSkills) }},
        frameShape: '{{ $defaultFrameShape }}',
        dragOver: false,
        imageSrc: null,
        currentZoom: 1,
        zoomMin: 0.2,
        zoomMax: 2.5,
        zoomStep: 0.01,
        currentOrientation: 1,
        horizontalShift: 0,
        prevShift: 0,
        avatarName: {{ Js::from($avatarName ?? '') }},
        avatarSubtitle: {{ Js::from($avatarSubtitle ?? '') }},
        viewportWidth: {{ $resolvedViewportWidth }},
        viewportHeight: {{ $resolvedViewportHeight }},
        maxWidth: {{ $resolvedMaxWidth }},
        maxHeight: {{ $resolvedMaxHeight }},
        modelName: '{{ $modelName }}',
        cropDataName: '{{ $cropDataName }}',
        croppieType: '{{ $croppieType ?? 'square' }}',
        
        init() {
            window.imageCropper = this;
            this.$el._cropperInstance = this;

            @if($resolvedCurrentImageUrl)
                // Otomatis muat dan pusatkan foto aktif ke tengah saat menu edit dibuka
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.editExistingPhoto('{{ $resolvedCurrentImageUrl }}');
                    }, 50);
                });
            @endif
        },
        
        async ensureCroppieLoaded() {
            if (typeof Croppie !== 'undefined') return true;
            if (window._croppieLoadingPromise) return window._croppieLoadingPromise;

            window._croppieLoadingPromise = new Promise((resolve, reject) => {
                if (!document.querySelector('link[href*=croppie]')) {
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = 'https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css';
                    document.head.appendChild(link);
                }
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js';
                script.onload = () => resolve(true);
                script.onerror = () => reject(new Error('Gagal memuat Croppie'));
                document.head.appendChild(script);
            });
            return window._croppieLoadingPromise;
        },
        
        initCroppie() {
            if (typeof Croppie === 'undefined') {
                console.error('Croppie library tidak ditemukan!');
                return;
            }
            
            const el = this.$refs.croppieContainer;
            if (!el) return;

            if (this.croppie) {
                this.croppie.destroy();
                this.croppie = null;
            }
            
            const viewportWidth = this.viewportWidth;
            const viewportHeight = this.viewportHeight;
            const boundaryHeight = Math.max(viewportHeight + 80, 280);

            const viewportType = this.isAvatar ? (this.frameShape === 'squircle' ? 'square' : 'circle') : (this.croppieType || 'square');

            const viewportConfig = { 
                width: viewportWidth, 
                height: viewportHeight,
                type: viewportType
            };
            
            this.croppie = new Croppie(el, {
                viewport: viewportConfig,
                boundary: { width: '100%', height: boundaryHeight },
                showZoomer: false,
                enableOrientation: true,
                enableResize: false,
                mouseWheelZoom: true,
                enableKeyMovement: true,
                enforceBoundary: false
            });

            if (this.isAvatar && this.frameShape === 'squircle' && this.croppie.elements && this.croppie.elements.viewport) {
                this.croppie.elements.viewport.classList.add('cr-vp-squircle');
            }
            
            el.addEventListener('update', () => {
                this.updateCrop();
                if (this.croppie && this.croppie.elements && this.croppie.elements.zoomer) {
                    this.currentZoom = parseFloat(this.croppie.elements.zoomer.value);
                }
            });
        },

        bindCenteredImage(imageSrc) {
            if (!imageSrc) return;
            this.imageSrc = imageSrc;
            this.showCropper = true;
            this.horizontalShift = 0;
            this.prevShift = 0;

            this.$nextTick(() => {
                this.initCroppie();

                const img = new Image();
                img.onload = () => {
                    const natW = img.naturalWidth || 500;
                    const natH = img.naturalHeight || 500;
                    const vpW = this.viewportWidth || 200;
                    const vpH = this.viewportHeight || 200;

                    // Skala minimal agar foto menutup seluruh area viewport tanpa celah
                    const minScale = Math.max(vpW / natW, vpH / natH);

                    // Titik crop tepat di tengah-tengah gambar asli
                    const cropW = vpW / minScale;
                    const cropH = vpH / minScale;
                    const x1 = Math.max(0, Math.round((natW - cropW) / 2));
                    const y1 = Math.max(0, Math.round((natH - cropH) / 2));
                    const x2 = Math.min(natW, Math.round(x1 + cropW));
                    const y2 = Math.min(natH, Math.round(y1 + cropH));

                    const doBind = () => {
                        if (!this.croppie) return;
                        this.croppie.bind({
                            url: imageSrc,
                            points: [x1, y1, x2, y2],
                            orientation: this.currentOrientation || 1
                        }).then(() => {
                            this.currentOrientation = 1;
                            if (this.croppie && this.croppie.elements && this.croppie.elements.zoomer) {
                                this.zoomMin = parseFloat(this.croppie.elements.zoomer.min) || minScale;
                                this.zoomMax = Math.max(this.zoomMin * 3, 2.5);
                                this.zoomStep = (this.zoomMax - this.zoomMin) / 100;
                                this.currentZoom = parseFloat(this.croppie.elements.zoomer.value) || minScale;
                            }
                            this.horizontalShift = 0;
                            this.prevShift = 0;
                            this.applyDirectCenter(natW, natH, this.currentZoom || minScale);
                            this.updateCrop();

                            // Verifikasi kedua setelah rendering/transisi modal selesai
                            setTimeout(() => {
                                if (this.croppie) {
                                    this.applyDirectCenter(natW, natH, this.currentZoom || minScale);
                                    this.updateCrop();
                                }
                            }, 150);
                        }).catch(err => {
                            console.error('Gagal bind croppie berpusat:', err);
                        });
                    };

                    setTimeout(doBind, 60);
                };
                img.onerror = () => {
                    setTimeout(() => {
                        if (!this.croppie) return;
                        this.croppie.bind({
                            url: imageSrc,
                            orientation: 1
                        }).then(() => {
                            this.updateCrop();
                        });
                    }, 60);
                };
                img.src = imageSrc;
            });
        },

        applyDirectCenter(natW, natH, scale) {
            if (!this.croppie || !this.croppie.elements) return;
            const boundary = this.croppie.elements.boundary;
            const viewport = this.croppie.elements.viewport;
            const preview = this.croppie.elements.preview;
            if (!boundary || !viewport || !preview) return;

            const boundRect = boundary.getBoundingClientRect();
            const vpRect = viewport.getBoundingClientRect();
            const s = scale || this.currentZoom || 1;

            if (boundRect.width <= 0 || vpRect.width <= 0) return;

            const vpCenterX = vpRect.left + vpRect.width / 2;
            const vpCenterY = vpRect.top + vpRect.height / 2;

            const targetX = Math.round(vpCenterX - boundRect.left - (natW * s) / 2);
            const targetY = Math.round(vpCenterY - boundRect.top - (natH * s) / 2);

            preview.style.transformOrigin = '0px 0px';
            preview.style.transform = `translate3d(${targetX}px, ${targetY}px, 0px) scale(${s})`;
            this.horizontalShift = 0;
            this.prevShift = 0;
        },
        
        async processFile(file) {
            if (!file || !file.type.startsWith('image/')) return;
            
            await this.ensureCroppieLoaded();

            const fileName = file.name.toLowerCase();
            if (fileName.endsWith('.heic') || fileName.endsWith('.heif')) {
                alert('Format HEIC tidak didukung. Gunakan format JPG, PNG, atau WebP.');
                if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                return;
            }

            const sizeKb = file.size / 1024;
            this.fileSizeFormatted = sizeKb > 1024 
                ? (sizeKb / 1024).toFixed(2) + ' MB' 
                : Math.round(sizeKb) + ' KB';

            this.fileName = file.name;
            this.originalFile = file;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                this.bindCenteredImage(e.target.result);
            };
            reader.readAsDataURL(file);
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            this.processFile(file);
        },

        handleDrop(event) {
            this.dragOver = false;
            if (event.dataTransfer && event.dataTransfer.files.length > 0) {
                const file = event.dataTransfer.files[0];
                this.processFile(file);
            }
        },

        async editExistingPhoto(url) {
            if (!url) return;
            await this.ensureCroppieLoaded();
            this.fileName = 'Foto Profil Saat Ini';
            this.fileSizeFormatted = 'Foto aktif';
            
            try {
                const res = await fetch(url);
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const blob = await res.blob();
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.bindCenteredImage(e.target.result);
                };
                reader.readAsDataURL(blob);
            } catch (err) {
                console.warn('Fallback ke URL foto langsung:', err);
                this.bindCenteredImage(url);
            }
        },
        
        setFrameShape(shape) {
            this.frameShape = shape;
            if (!this.croppie || !this.croppie.elements || !this.croppie.elements.viewport) return;
            const vp = this.croppie.elements.viewport;
            if (shape === 'circle') {
                vp.classList.add('cr-vp-circle');
                vp.classList.remove('cr-vp-squircle', 'cr-vp-square');
            } else if (shape === 'squircle') {
                vp.classList.add('cr-vp-squircle');
                vp.classList.remove('cr-vp-circle', 'cr-vp-square');
            } else {
                vp.classList.add('cr-vp-square');
                vp.classList.remove('cr-vp-circle', 'cr-vp-squircle');
            }
            this.updateCrop();
        },

        pan(deltaX, deltaY) {
            if (!this.croppie || !this.croppie.elements || !this.croppie.elements.overlay) return;
            const overlay = this.croppie.elements.overlay;
            const rect = overlay.getBoundingClientRect();
            const startX = rect.left + rect.width / 2;
            const startY = rect.top + rect.height / 2;

            overlay.dispatchEvent(new MouseEvent('mousedown', {
                clientX: startX,
                clientY: startY,
                bubbles: true,
                button: 0
            }));

            window.dispatchEvent(new MouseEvent('mousemove', {
                clientX: startX + deltaX,
                clientY: startY + deltaY,
                bubbles: true
            }));

            window.dispatchEvent(new MouseEvent('mouseup', {
                clientX: startX + deltaX,
                clientY: startY + deltaY,
                bubbles: true
            }));
        },

        onHorizontalSliderChange(val) {
            const current = parseInt(val, 10);
            const diff = current - this.prevShift;
            if (diff !== 0) {
                this.pan(diff * 2, 0);
                this.prevShift = current;
            }
        },

        centerImage() {
            if (!this.croppie || !this.imageSrc) return;
            this.bindCenteredImage(this.imageSrc);
        },

        setZoomLevel(val) {
            if (!this.croppie) return;
            const v = Math.min(Math.max(parseFloat(val), this.zoomMin), this.zoomMax);
            this.currentZoom = v;
            this.croppie.setZoom(v);
        },

        zoomIn() {
            const step = (this.zoomMax - this.zoomMin) / 10;
            this.setZoomLevel(this.currentZoom + step);
        },

        zoomOut() {
            const step = (this.zoomMax - this.zoomMin) / 10;
            this.setZoomLevel(this.currentZoom - step);
        },

        get zoomPercent() {
            if (!this.zoomMax || this.zoomMax === this.zoomMin) return 100;
            const pct = Math.round(((this.currentZoom - this.zoomMin) / (this.zoomMax - this.zoomMin)) * 100);
            return Math.max(0, Math.min(100, pct));
        },
        
        updateCrop() {
            if (!this.croppie) return;
            
            this.croppie.result({
                type: 'base64',
                size: { width: this.maxWidth, height: this.maxHeight },
                format: 'png',
                quality: 1
            }).then((result) => {
                this.cropResult = result;
            }).catch((e) => {
                console.error('Croppie update error:', e);
            });
        },
        
        async getCropResultAsync() {
            if (!this.croppie) {
                return this.cropResult;
            }
            try {
                const result = await this.croppie.result({
                    type: 'base64',
                    size: { width: this.maxWidth, height: this.maxHeight },
                    format: 'png',
                    quality: 1
                });
                this.cropResult = result;
                return result;
            } catch (e) {
                console.error('Gagal mengambil hasil crop:', e);
                return this.cropResult;
            }
        },

        sendToLivewire(targetWire) {
            if (!this.cropResult) return;
            
            const lw = targetWire 
                || (typeof this.$wire !== 'undefined' ? this.$wire : null)
                || (this.$el ? window.Livewire?.find(this.$el.closest('[wire\\:id]')?.getAttribute('wire:id')) : null);

            if (lw) {
                if (typeof lw.set === 'function') {
                    lw.set(this.modelName, this.cropResult);
                    lw.set(this.cropDataName, this.cropResult);
                } else {
                    lw[this.modelName] = this.cropResult;
                }
            }
        },
        
        async sendToLivewireAsync(targetWire) {
            const result = await this.getCropResultAsync();
            this.sendToLivewire(targetWire);
            return result;
        },

        hasNewImage() {
            return this.cropResult !== null;
        },
        
        async updateCropAsync() {
            return await this.getCropResultAsync();
        },
        
        rotate(degrees) {
            if (this.croppie) {
                this.croppie.rotate(degrees);
                setTimeout(() => this.updateCrop(), 100);
            }
        },
        
        clearImage() {
            if (this.croppie) {
                this.croppie.destroy();
                this.croppie = null;
            }
            this.showCropper = false;
            this.cropResult = null;
            this.imageSrc = null;
            this.fileName = '';
            this.fileSizeFormatted = '';
            this.horizontalShift = 0;
            this.prevShift = 0;
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
            if (this.$wire) {
                this.$wire.set(this.modelName, null);
                this.$wire.set(this.cropDataName, null);
            }
        }
    }" 
    @croppie-update.window="updateCrop()" 
    class="space-y-3"
>

    {{-- Header Label & Aspect Ratio Badge --}}
    <div class="flex items-center justify-between">
        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-200">
            {{ __($label) }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
        <div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-500 dark:text-slate-400">
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-sky-500"></span>
            <span>{{ __('Rasio') }} {{ $aspectRatioLabel }}</span>
        </div>
    </div>

    {{-- Hidden File Input --}}
    <input 
        type="file" 
        x-ref="fileInput"
        @change="handleFileChange($event)"
        accept="image/jpeg,image/jpg,image/png,image/webp"
        class="hidden"
    >

    {{-- State 1: Dropzone Upload (Ketika belum memilih foto baru) --}}
    <div x-show="!showCropper" class="space-y-3">
        @if($resolvedCurrentImageUrl)
            {{-- Tampilan Foto Profil Saat Ini --}}
            <div class="p-3.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-xl flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative shrink-0">
                        <img 
                            src="{{ $resolvedCurrentImageUrl }}" 
                            alt="{{ __('Foto Saat Ini') }}" 
                            class="w-12 h-12 object-cover border border-slate-200 dark:border-gray-600 {{ $isAvatarMode ? 'rounded-full ring-2 ring-white dark:ring-gray-700 shadow-xs' : 'rounded-lg' }}"
                        >
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-900 dark:text-gray-100 truncate">{{ __('Foto Aktif Digunakan') }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-gray-400">{{ __('Bisa diedit ulang posisinya atau diganti dengan foto baru') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    {{-- Tombol Edit Ulang Posisi (di samping tombol ganti foto) --}}
                    <button
                        type="button"
                        @click="editExistingPhoto('{{ $resolvedCurrentImageUrl }}')"
                        class="px-3 py-1.5 bg-sky-50 dark:bg-sky-500/15 hover:bg-sky-100 dark:hover:bg-sky-500/25 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-500/30 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
                        title="{{ __('Atur ulang posisi, zoom, atau geser foto profil ini') }}"
                    >
                        <svg class="w-3.5 h-3.5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>{{ __('Edit Ulang Posisi') }}</span>
                    </button>

                    {{-- Tombol Ganti Foto --}}
                    <button
                        type="button"
                        @click="$refs.fileInput.click()"
                        class="px-3 py-1.5 bg-white dark:bg-gray-900 hover:bg-slate-100 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-lg text-xs font-bold text-slate-700 dark:text-gray-200 transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
                        title="{{ __('Pilih berkas foto baru dari perangkat') }}"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span>{{ __('Ganti Foto') }}</span>
                    </button>
                </div>
            </div>
        @endif

        {{-- Dropzone Area --}}
        <div 
            @dragover.prevent="dragOver = true"
            @dragleave.prevent="dragOver = false"
            @drop.prevent="handleDrop($event)"
            @click="$refs.fileInput.click()"
            :class="dragOver ? 'border-sky-500 bg-sky-50/60 dark:bg-sky-500/10 ring-2 ring-sky-500/20' : 'border-slate-300 dark:border-gray-600 hover:border-slate-400 dark:hover:border-gray-500 bg-white dark:bg-gray-800/60 hover:bg-slate-50/60 dark:hover:bg-gray-800'"
            class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer transition group"
        >
            <div class="max-w-xs mx-auto space-y-2">
                <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 group-hover:text-sky-600 group-hover:bg-sky-50 dark:group-hover:bg-sky-500/15 flex items-center justify-center transition border border-slate-200/80 dark:border-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-800 dark:text-gray-200">
                        <span class="text-sky-600 dark:text-sky-400 underline underline-offset-2">{{ __('Pilih file foto') }}</span> {{ __('atau tarik & lepas ke sini') }}
                    </p>
                    <p class="text-[11px] text-slate-500 dark:text-gray-400 mt-1">
                        {{ __('Format JPG, PNG, atau WebP (Maks. 5MB)') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Error Message --}}
    @error($modelName) <p class="text-xs text-rose-600 dark:text-rose-400 font-medium">{{ $message }}</p> @enderror

    {{-- State 2: Studio Cropper & Adjuster (Aktif saat foto dipilih) --}}
    <div x-show="showCropper" x-cloak class="space-y-4">
        {{-- Toolbar Atas: Informasi File & Aksi Cepat --}}
        <div class="flex items-center justify-between gap-3 p-2.5 bg-slate-100/80 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-xl text-xs">
            <div class="flex items-center gap-2 min-w-0">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                <span class="font-mono text-slate-700 dark:text-gray-200 truncate font-medium text-[11px]" x-text="fileName"></span>
                <span class="text-slate-400 text-[10px]" x-text="fileSizeFormatted ? '(' + fileSizeFormatted + ')' : ''"></span>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                @if($resolvedCurrentImageUrl)
                    <button 
                        type="button" 
                        @click="editExistingPhoto('{{ $resolvedCurrentImageUrl }}')" 
                        class="px-2.5 py-1 bg-white dark:bg-gray-900 hover:bg-slate-50 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-lg text-slate-700 dark:text-gray-200 text-[11px] font-bold transition cursor-pointer shadow-2xs"
                        title="{{ __('Muat ulang foto profil aktif') }}"
                    >
                        {{ __('Foto Aktif') }}
                    </button>
                @endif
                <button 
                    type="button" 
                    @click="$refs.fileInput.click()" 
                    class="px-2.5 py-1 bg-white dark:bg-gray-900 hover:bg-slate-50 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-lg text-slate-700 dark:text-gray-200 text-[11px] font-bold transition cursor-pointer shadow-2xs"
                >
                    {{ __('Ganti File') }}
                </button>
                <button 
                    type="button" 
                    @click="clearImage()" 
                    class="px-2.5 py-1 bg-rose-50 dark:bg-rose-500/15 hover:bg-rose-100 dark:hover:bg-rose-500/25 border border-rose-200 dark:border-rose-500/30 text-rose-700 dark:text-rose-300 rounded-lg text-[11px] font-bold transition cursor-pointer shadow-2xs"
                >
                    {{ __('Batal') }}
                </button>
            </div>
        </div>

        {{-- Grid Utama: Workspace Cropper (Kiri) & Live Preview Mockup (Kanan) --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
            {{-- Kolom Kiri: Canvas Cropper & Tombol Kontrol --}}
            <div class="md:col-span-7 space-y-3">
                {{-- Croppie Container --}}
                <div class="relative rounded-xl overflow-hidden border border-slate-700 shadow-inner bg-slate-900">
                    <div x-ref="croppieContainer" class="w-full overflow-hidden"></div>
                    
                    {{-- Petunjuk Floating Di Bawah Canvas --}}
                    <div class="absolute bottom-2 left-0 right-0 pointer-events-none flex justify-center">
                        <span class="px-2.5 py-1 bg-slate-950/80 backdrop-blur-xs text-[10px] text-slate-200 rounded-full border border-white/10 shadow-xs flex items-center gap-1">
                            <svg class="w-3 h-3 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                            </svg>
                            <span>{{ __('Geser foto untuk menyesuaikan posisi') }}</span>
                        </span>
                    </div>
                </div>

                {{-- Kotak Kontrol Penyesuaian Foto --}}
                <div class="p-3 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-xl space-y-3">
                    {{-- 1. Kontrol Geser Posisi (Horizontal / Kanan-Kiri & Vertikal) --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-[11px] font-semibold text-slate-700 dark:text-gray-200 uppercase tracking-wider flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                <span>{{ __('Geser Foto (Kanan - Kiri)') }}</span>
                            </label>
                            <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono" x-text="horizontalShift > 0 ? '+' + horizontalShift : horizontalShift"></span>
                        </div>
                        
                        {{-- Slider Geser Horizontal --}}
                        <div class="flex items-center gap-2">
                            <button 
                                type="button" 
                                @click="pan(-20, 0)" 
                                title="{{ __('Geser ke kiri') }}"
                                class="shrink-0 p-1.5 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-600 hover:bg-slate-100 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 rounded-lg text-xs transition cursor-pointer shadow-2xs"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <input 
                                type="range" 
                                min="-100" 
                                max="100" 
                                step="1" 
                                x-model="horizontalShift" 
                                @input="onHorizontalSliderChange($event.target.value)"
                                class="w-full h-1.5 bg-slate-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer accent-sky-600 focus:outline-none"
                                title="{{ __('Geser posisi horizontal foto') }}"
                            >
                            <button 
                                type="button" 
                                @click="pan(20, 0)" 
                                title="{{ __('Geser ke kanan') }}"
                                class="shrink-0 p-1.5 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-600 hover:bg-slate-100 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 rounded-lg text-xs transition cursor-pointer shadow-2xs"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Tombol Geser Presisi & Pusatkan --}}
                        <div class="flex items-center justify-between gap-1 mt-2 pt-2 border-t border-slate-200/60 dark:border-gray-700 text-[11px]">
                            <div class="flex items-center gap-1">
                                <button type="button" @click="pan(0, -15)" class="px-2 py-1 bg-white dark:bg-gray-900 hover:bg-slate-100 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-md text-slate-700 dark:text-gray-200 font-bold transition cursor-pointer shadow-2xs" title="{{ __('Geser ke Atas') }}">▲ {{ __('Atas') }}</button>
                                <button type="button" @click="pan(0, 15)" class="px-2 py-1 bg-white dark:bg-gray-900 hover:bg-slate-100 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-md text-slate-700 dark:text-gray-200 font-bold transition cursor-pointer shadow-2xs" title="{{ __('Geser ke Bawah') }}">▼ {{ __('Bawah') }}</button>
                            </div>
                            <button 
                                type="button" 
                                @click="centerImage()" 
                                class="px-2.5 py-1 bg-white dark:bg-gray-900 hover:bg-slate-100 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-md text-slate-700 dark:text-gray-200 font-bold transition cursor-pointer flex items-center gap-1 shadow-2xs"
                                title="{{ __('Kembalikan posisi foto ke tengah') }}"
                            >
                                <svg class="w-3 h-3 text-slate-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                </svg>
                                <span>{{ __('Pusatkan') }}</span>
                            </button>
                        </div>
                    </div>

                    {{-- 2. Kontrol Zoom & Putar (Rotate) --}}
                    <div class="pt-2.5 border-t border-slate-200/80 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-[11px] font-semibold text-slate-700 dark:text-gray-200 uppercase tracking-wider flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                </svg>
                                <span>{{ __('Perbesar (Zoom) & Putar') }}</span>
                            </label>
                            <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono" x-text="zoomPercent + '%'"></span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <button 
                                type="button" 
                                @click="zoomOut()" 
                                title="{{ __('Perkecil foto') }}"
                                class="shrink-0 p-1.5 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-600 hover:bg-slate-100 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 rounded-lg text-xs transition cursor-pointer shadow-2xs"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                            </button>
                            <input 
                                type="range" 
                                :min="zoomMin" 
                                :max="zoomMax" 
                                :step="zoomStep" 
                                :value="currentZoom" 
                                @input="setZoomLevel($event.target.value)"
                                class="w-full h-1.5 bg-slate-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer accent-sky-600 focus:outline-none"
                                title="{{ __('Zoom foto') }}"
                            >
                            <button 
                                type="button" 
                                @click="zoomIn()" 
                                title="{{ __('Perbesar foto') }}"
                                class="shrink-0 p-1.5 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-600 hover:bg-slate-100 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 rounded-lg text-xs transition cursor-pointer shadow-2xs"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>

                            {{-- Tombol Putar (Rotate) --}}
                            <div class="flex items-center gap-1 border-l border-slate-200 dark:border-gray-700 pl-2 ml-1 shrink-0">
                                <button 
                                    type="button" 
                                    @click="rotate(-90)" 
                                    title="{{ __('Putar 90° ke kiri') }}"
                                    class="p-1.5 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-600 hover:bg-slate-100 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 rounded-lg text-xs transition cursor-pointer shadow-2xs"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m0 0l-3-3m3 3l3-3M3 10l3-3m-3 3l3 3"/>
                                    </svg>
                                </button>
                                <button 
                                    type="button" 
                                    @click="rotate(90)" 
                                    title="{{ __('Putar 90° ke kanan') }}"
                                    class="p-1.5 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-600 hover:bg-slate-100 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 rounded-lg text-xs transition cursor-pointer shadow-2xs"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m0 0l3-3m-3 3l-3-3m17-4l-3-3m3 3l-3 3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Pilihan Bentuk Bingkai (Khusus Mode Foto Profil) --}}
                    @if($isAvatarMode)
                        <div class="pt-2.5 border-t border-slate-200/80 dark:border-gray-700">
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-200 uppercase tracking-wider mb-1.5">
                                {{ __('Bentuk Bingkai Foto Profil') }}
                            </label>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <button
                                    type="button"
                                    @click="setFrameShape('circle')"
                                    :class="frameShape === 'circle' ? 'bg-sky-50 dark:bg-sky-500/20 border-sky-500 text-sky-700 dark:text-sky-300 font-semibold ring-1 ring-sky-500/20' : 'bg-white dark:bg-gray-900 border-slate-200 dark:border-gray-600 text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-700'"
                                    class="p-2 border rounded-lg transition cursor-pointer flex items-center justify-center gap-1.5 text-center shadow-2xs"
                                >
                                    <span class="w-3.5 h-3.5 rounded-full border-2 border-current"></span>
                                    <span>{{ __('Lingkaran (Bulat)') }}</span>
                                </button>
                                <button
                                    type="button"
                                    @click="setFrameShape('squircle')"
                                    :class="frameShape === 'squircle' ? 'bg-sky-50 dark:bg-sky-500/20 border-sky-500 text-sky-700 dark:text-sky-300 font-semibold ring-1 ring-sky-500/20' : 'bg-white dark:bg-gray-900 border-slate-200 dark:border-gray-600 text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-700'"
                                    class="p-2 border rounded-lg transition cursor-pointer flex items-center justify-center gap-1.5 text-center shadow-2xs"
                                >
                                    <span class="w-3.5 h-3.5 rounded-sm border-2 border-current"></span>
                                    <span>{{ __('Kotak Melengkung') }}</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan: Pratinjau Gambaran Nyata (Live Mockup Frame Preview) --}}
            <div class="md:col-span-5 space-y-3">
                <div class="p-3 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-xl space-y-3 shadow-2xs">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-gray-700">
                        <span class="text-xs font-semibold text-slate-800 dark:text-gray-100 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>{{ __('Gambaran Foto Profil') }}</span>
                        </span>
                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-500/15 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-500/30">
                            Live Update
                        </span>
                    </div>

                    @if($isAvatarMode)
                        {{-- Mockup 1: Gambaran di Halaman Profil Siswa (Besar) --}}
                        <div class="p-3 bg-slate-50 dark:bg-gray-900 border border-slate-200/80 dark:border-gray-700 rounded-xl space-y-2 text-center">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-gray-400">
                                {{ __('Tampilan di Halaman Profil') }}
                            </p>
                            
                            {{-- Avatar Frame Mockup --}}
                            <div class="relative inline-block mx-auto mt-1">
                                <div 
                                    :class="frameShape === 'circle' ? 'rounded-full' : 'rounded-2xl'" 
                                    class="w-20 h-20 overflow-hidden bg-slate-200 dark:bg-gray-800 ring-3 ring-sky-100 dark:ring-sky-500/20 border border-slate-300 dark:border-gray-600 shadow-sm mx-auto transition-all"
                                >
                                    <template x-if="cropResult">
                                        <img :src="cropResult" alt="Avatar Preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!cropResult">
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    </template>
                                </div>
                                {{-- Online Status Dot --}}
                                <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-white dark:border-gray-900 rounded-full shadow-2xs"></span>
                            </div>

                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-900 dark:text-gray-100 truncate" x-text="avatarName || @js(__('Nama Siswa'))"></p>
                                <p class="text-[11px] text-slate-500 dark:text-gray-400 truncate" x-text="avatarSubtitle || @js(__('Siswa RPL • Skansaba'))"></p>
                                <template x-if="avatarSkills && avatarSkills.length > 0">
                                    <div class="flex flex-wrap items-center justify-center gap-1 pt-1">
                                        <template x-for="(skill, sIdx) in avatarSkills.slice(0, 3)" :key="sIdx">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-200/60 dark:border-sky-500/30" x-text="skill"></span>
                                        </template>
                                        <template x-if="avatarSkills.length > 3">
                                            <span class="text-[9px] text-slate-400" x-text="'+' + (avatarSkills.length - 3)"></span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Mockup 2: Gambaran di Header / Navbar & Komentar (Kecil) --}}
                        <div class="p-2.5 bg-slate-50 dark:bg-gray-900 border border-slate-200/80 dark:border-gray-700 rounded-xl space-y-1.5">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-gray-400">
                                {{ __('Tampilan di Bilah Menu (Ukuran Mini)') }}
                            </p>
                            <div class="flex items-center gap-2 p-1.5 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg shadow-2xs">
                                <div 
                                    :class="frameShape === 'circle' ? 'rounded-full' : 'rounded-md'" 
                                    class="w-8 h-8 overflow-hidden bg-slate-200 dark:bg-gray-700 ring-1 ring-slate-200 dark:ring-gray-600 shrink-0"
                                >
                                    <template x-if="cropResult">
                                        <img :src="cropResult" alt="Mini Avatar" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!cropResult">
                                        <div class="w-full h-full bg-slate-300 dark:bg-gray-600"></div>
                                    </template>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] font-semibold text-slate-800 dark:text-gray-100 truncate" x-text="avatarName ? avatarName.split(' ')[0] : @js(__('Siswa'))"></p>
                                    <p class="text-[10px] text-slate-400">{{ __('Aktif') }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Non-Avatar Preview (16:9 atau Custom Rasio) --}}
                        <div class="p-2 bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl text-center">
                            <div class="crop-preview-box mx-auto w-full aspect-video h-auto max-h-48 overflow-hidden rounded-lg border border-slate-200 dark:border-gray-700 bg-slate-100 dark:bg-gray-800 flex items-center justify-center">
                                <template x-if="cropResult">
                                    <img :src="cropResult" alt="Preview" class="max-w-full max-h-full object-contain">
                                </template>
                            </div>
                        </div>
                    @endif

                    {{-- Info Spesifikasi Output --}}
                    <div class="p-2.5 bg-slate-50 dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-700 space-y-1 text-[11px] text-slate-600 dark:text-gray-300">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-gray-400">{{ __('Resolusi Output:') }}</span>
                            <span class="font-mono font-medium text-slate-800 dark:text-gray-100">{{ $resolvedMaxWidth }} × {{ $resolvedMaxHeight }} px</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-gray-400">{{ __('Format Simpan:') }}</span>
                            <span class="font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>{{ __('WebP Optimal') }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
