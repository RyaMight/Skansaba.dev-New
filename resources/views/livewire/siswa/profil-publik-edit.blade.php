<div class="p-6 max-w-5xl mx-auto space-y-6" x-data>
    {{-- Toast Notifikasi --}}
    @if (session()->has('message'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 right-6 z-[60] w-auto max-w-sm"
            role="status"
            aria-live="polite"
        >
            <div class="flex items-center gap-3 bg-emerald-600 text-white rounded-xl shadow-lg px-4 py-3">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/20 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <span class="text-sm font-medium">{{ session('message') }}</span>
                <button type="button" @click="show = false" class="ml-1 text-white/70 hover:text-white shrink-0" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            {{-- Avatar dengan tombol edit --}}
            <div class="relative shrink-0">
                <div class="w-16 h-16 rounded-full overflow-hidden bg-slate-100 ring-2 ring-gray-100">
                    @if($user->photo_url)
                        <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white text-xl font-bold">
                            {{ $user->initials() }}
                        </div>
                    @endif
                </div>
                <button
                    type="button"
                    wire:click="openEditModal"
                    class="absolute -bottom-1 -right-1 w-7 h-7 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-full flex items-center justify-center shadow-md transition cursor-pointer"
                    title="Ganti foto profil"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </button>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Profil Publik</h1>
                <p class="text-sm text-gray-500 mt-0.5">Inilah yang dilihat orang (termasuk HRD) saat membuka profil kamu. Hias se-menarik mungkin!</p>
            </div>
        </div>
        <a
            href="{{ $user->profil_url }}?from=editor"
            target="_blank"
            class="inline-flex items-center gap-1.5 px-4 py-2 border border-sky-300 text-sky-600 bg-sky-50/50 hover:bg-sky-50 rounded-lg text-xs font-semibold transition shrink-0"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span>Preview Profil</span>
        </a>
    </div>

    {{-- Link Profil Publik --}}
    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Bagikan Link Profil Kamu ke HRD</p>
        <div class="flex items-center gap-2">
            <code class="flex-1 text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-gray-700 truncate">{{ $user->profil_url }}</code>
            <button
                type="button"
                x-data
                @click="
                    navigator.clipboard.writeText('{{ $user->profil_url }}');
                    $el.textContent = 'Tersalin!';
                    setTimeout(() => $el.textContent = 'Copy', 2000);
                "
                class="px-4 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg text-xs font-semibold transition cursor-pointer"
            >Copy</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Biodata --}}
        <form wire:submit="saveBiodata" class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 space-y-4">
            <h3 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">Biodata Singkat</h3>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Headline</label>
                <input
                    type="text"
                    wire:model="headline"
                    maxlength="120"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                    placeholder="Contoh: Fullstack Web Developer | Siswa XII RPL 1"
                >
                <p class="text-[11px] text-gray-400 mt-1">Muncul di bawah nama kamu, satu kalimat yang menjelaskan siapa kamu.</p>
                @error('headline') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Bio</label>
                <textarea
                    wire:model="bio"
                    rows="3"
                    maxlength="500"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                    placeholder="Ceritakan singkat tentang minat dan tujuan karier kamu..."
                ></textarea>
                @error('bio') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Keahlian <span class="text-gray-400">(pisahkan dengan koma)</span></label>
                <input
                    type="text"
                    wire:model="keahlian"
                    maxlength="255"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                    placeholder="Contoh: Laravel, React, Figma, Flutter"
                >
                <p class="text-[11px] text-gray-400 mt-1">Akan tampil sebagai badge keahlian di profil publik.</p>
                @error('keahlian') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Username Kustom <span class="text-gray-400">(opsional)</span></label>
                <div class="flex items-center gap-0">
                    <span class="px-3 py-2 text-sm text-gray-500 bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg">/profil/</span>
                    <input
                        type="text"
                        wire:model="username"
                        maxlength="50"
                        class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        placeholder="ahmad-syahputra"
                    >
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Huruf kecil, angka, dan tanda hubung. Kalau kosong, otomatis pakai ID akun.</p>
                @error('username') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg text-sm font-semibold transition cursor-pointer disabled:opacity-50"
                >
                    <span wire:loading wire:target="saveBiodata" class="inline-block animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                    Simpan Biodata
                </button>
            </div>
        </form>

        {{-- README Profil --}}
        <div class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900">README Profil</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Tampilan utama profil publik kamu — hias dengan Markdown seperti README GitHub.</p>
                </div>
                <span class="text-[10px] font-semibold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-full shrink-0">Markdown</span>
            </div>

            @if($user->profil_readme)
                <div class="flex-1 overflow-y-auto max-h-64 bg-gray-50/60 border border-gray-100 rounded-xl p-4 prose prose-sm prose-slate max-w-none">
                    {!! app(\App\Services\MarkdownService::class)->parseReadme($user->profil_readme) !!}
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center py-10 border-2 border-dashed border-gray-100 rounded-xl">
                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-gray-400 font-medium">README masih kosong</p>
                    <p class="text-xs text-gray-400 mt-1 max-w-[240px]">Tulis perkenalan, keahlian, pengalaman proyek, dan kontak agar HRD tertarik merekrut kamu.</p>
                </div>
            @endif

            <button
                type="button"
                wire:click="openEditorModal"
                class="mt-4 w-full px-4 py-2.5 {{ $user->profil_readme ? 'bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700' : 'bg-[#0284c7] hover:bg-[#0369a1] text-white' }} rounded-lg text-sm font-semibold transition cursor-pointer"
            >
                {{ $user->profil_readme ? 'Edit README' : 'Tulis README Sekarang' }}
            </button>
        </div>
    </div>

    {{-- Aset SVG README --}}
    <div class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Aset SVG README</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Upload gambar SVG (infografis, kartu statistik, dll) lalu sisipkan ke README profil kamu.</p>
            </div>
            <span class="text-[10px] font-semibold text-violet-600 bg-violet-50 px-2 py-0.5 rounded-full shrink-0">Maks {{ \App\Services\SvgService::MAX_FILES_PER_USER }} file · 1 MB</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Form upload --}}
            <form wire:submit="uploadSvg" class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Upload File SVG</label>
                    <input
                        type="file"
                        accept=".svg,image/svg+xml"
                        wire:model="fileSvg"
                        class="w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer file:mr-3 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:border-0 file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100 focus:ring-2 focus:ring-sky-500 focus:outline-none"
                    >
                    @error('fileSvg') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    <p class="text-[11px] text-gray-400 mt-1">SVG otomatis disanitasi demi keamanan. Maksimal 1 MB per file.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg text-sm font-semibold transition cursor-pointer disabled:opacity-50"
                    >
                        <span wire:loading wire:target="uploadSvg" class="inline-block animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                        Upload SVG
                    </button>
                </div>
            </form>

            {{-- Daftar aset --}}
            <div>
                <p class="text-xs font-medium text-gray-700 mb-2">Aset Saya ({{ $asetSvgList->count() }}/{{ \App\Services\SvgService::MAX_FILES_PER_USER }})</p>
                @if($asetSvgList->isEmpty())
                    <div class="flex flex-col items-center justify-center text-center py-8 border-2 border-dashed border-gray-100 rounded-xl">
                        <svg class="w-8 h-8 text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-xs text-gray-400">Belum ada aset SVG</p>
                    </div>
                @else
                    <ul class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @foreach($asetSvgList as $aset)
                            <li class="flex items-center gap-3 border border-gray-100 rounded-xl p-2 hover:bg-gray-50 transition">
                                <div class="w-12 h-12 shrink-0 rounded-lg border border-gray-100 bg-white flex items-center justify-center overflow-hidden">
                                    <img src="{{ $aset->url }}" alt="{{ $aset->nama_file }}" class="max-w-full max-h-full object-contain p-1">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-800 truncate" title="{{ $aset->nama_file }}">{{ $aset->nama_file }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $aset->size_human }}</p>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <button
                                        type="button"
                                        x-data
                                        @click="navigator.clipboard.writeText($el.dataset.snippet); $el.textContent = 'Tersalin!'; setTimeout(() => $el.textContent = 'Salin', 2000);"
                                        data-snippet="{{ $aset->snippet }}"
                                        class="px-2.5 py-1.5 text-[11px] font-semibold border border-gray-200 rounded-lg hover:bg-gray-100 transition cursor-pointer"
                                    >Salin</button>
                                    <button
                                        type="button"
                                        wire:click="deleteSvg({{ $aset->id }})"
                                        wire:confirm="Hapus aset '{{ $aset->nama_file }}'? Snippet di README yang memakainya akan rusak."
                                        wire:loading.attr="disabled"
                                        class="px-2.5 py-1.5 text-[11px] font-semibold border border-rose-200 text-rose-600 rounded-lg hover:bg-rose-50 transition cursor-pointer"
                                    >Hapus</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- README Editor Modal --}}
    @if($showEditorModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-3xl w-full shadow-xl border border-gray-100 flex flex-col max-h-[92vh]">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Edit README Profil</h3>
                    <button type="button" wire:click="closeEditorModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-4">
                    <x-markdown-editor
                        modelName="profil_readme"
                        label=""
                        placeholder="<div align=&quot;center&quot;>&#10;&#10;<table>&#10;<tr>&#10;<td valign=&quot;top&quot;><img src=&quot;URL-SVG-KAMU&quot; width=&quot;370&quot; alt=&quot;ASCII portrait&quot; /></td>&#10;<td valign=&quot;top&quot;><img src=&quot;URL-SVG-KAMU&quot; width=&quot;490&quot; alt=&quot;Info card&quot; /></td>&#10;</tr>&#10;</table>&#10;&#10;## Nama Kamu&#10;&#10;**Headline · Keahlian Utama**&#10;&#10;[![Instagram](https://img.shields.io/badge/Instagram-username-E4405F?style=for-the-badge&amp;logo=instagram&amp;logoColor=white)](https://www.instagram.com/username/)&#10;&#10;<br>&#10;&#10;<img src=&quot;URL-SVG-KAMU&quot; width=&quot;860&quot; alt=&quot;Contribution graph&quot; />&#10;&#10;</div>&#10;&#10;Tips: upload SVG di halaman ini, lalu klik aset di panel 'Sisipkan Aset SVG' untuk menaruhnya di sini."
                        :rows="14"
                        :required="false"
                    />
                    @error('profil_readme') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror

                    {{-- Sisipkan aset SVG ke README --}}
                    @if($asetSvgList->isNotEmpty())
                        <div class="border border-gray-100 rounded-xl p-3 bg-gray-50/60">
                            <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Sisipkan Aset SVG</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($asetSvgList as $aset)
                                    <button
                                        type="button"
                                        x-data
                                        @click="
                                            const snippet = {{ Js::from($aset->snippet) }};
                                            const modal = $el.closest('.max-w-3xl');
                                            const ta = modal ? modal.querySelector('textarea') : null;
                                            if (ta) {
                                                const nl = String.fromCharCode(10);
                                                const pos = ta.selectionStart ?? ta.value.length;
                                                ta.setRangeText(nl + snippet + nl, pos, pos, 'end');
                                                ta.dispatchEvent(new Event('input', { bubbles: true }));
                                                ta.focus();
                                            }
                                        "
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-gray-200 rounded-lg hover:bg-sky-50 hover:border-sky-200 transition cursor-pointer"
                                        title="{{ $aset->nama_file }}"
                                    >
                                        <img src="{{ $aset->url }}" alt="" class="w-4 h-4 object-contain">
                                        <span class="text-[11px] font-medium text-gray-700 max-w-[140px] truncate">{{ $aset->nama_file }}</span>
                                    </button>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-gray-400 mt-2">Klik untuk menyisipkan snippet di posisi kursor.</p>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-gray-100">
                    <button type="button" wire:click="closeEditorModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm cursor-pointer">
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="saveReadme"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg font-semibold transition text-sm cursor-pointer disabled:opacity-50"
                    >
                        <span wire:loading wire:target="saveReadme" class="inline-block animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                        Simpan README
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Edit Foto Profil --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-100 space-y-4 max-h-[92vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Ganti Foto Profil</h3>
                    <button type="button" wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form x-on:submit.prevent="const c = window.imageCropper; const save = () => $wire.savePhoto(); (c && c.sendToLivewireAsync) ? c.sendToLivewireAsync().then(save).catch(save) : save();" class="space-y-4 text-xs">
                    {{-- Upload & Adjust Foto Profil (Croppie 1:1) --}}
                    <div>
                        <x-image-cropper
                            modelName="photoBase64"
                            cropDataName="crop_data"
                            label="Foto Profil (Bisa Diatur Posisi, Zoom & Putar)"
                            :aspectRatio="1"
                            aspectRatioLabel="1:1 Bulat/Kotak"
                            :maxWidth="500"
                            :maxHeight="500"
                            :currentImage="$user->photo"
                            :previewSize="160"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                        <button type="button" wire:click="closeEditModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg font-semibold transition cursor-pointer flex items-center gap-1.5"
                        >
                            <span wire:loading wire:target="savePhoto" class="inline-block animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                            <span>Simpan Foto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
