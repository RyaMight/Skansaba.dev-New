<?php

namespace App\Livewire;

use App\Models\KaryaLog;
use App\Models\KaryaSiswa;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Koleksi Karya Siswa RPL')]
class KaryaSiswaManager extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';
    public string $activeMenu = ''; // draft, review, published, keterangan
    public string $filterStatus = '';
    public string $filterKategori = '';
    public string $filterTahun = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Detail Modal State
    public bool $showDetailModal = false;
    public ?KaryaSiswa $detailKarya = null;

    // Delete Modal State
    public ?int $confirmingDeleteId = null;

    // Create/Edit Modal State & Form Fields
    public bool $showCreateModal = false;
    public ?int $editingKaryaId = null;
    public string $judul = '';
    public string $deskripsi_singkat = '';
    public string $deskripsi = '';
    public string $kategori = 'web';
    public string $kategori_lainnya = '';
    public string $teknologi = '';
    public string $url_demo = '';
    public string $url_repo = '';

    // 1. Upload Cover / Thumbnail
    public $cover_image = null;
    public ?string $existing_cover = null;

    // 2. Upload Screenshots Aplikasi (Maksimal 7)
    public array $screenshots = [];
    public $new_screenshots = [];
    public array $existing_screenshots = [];

    // 3. Upload Bukti Karya / Orisinalitas (Buktikan karya milik siswa)
    public array $bukti_files = [];
    public $new_bukti_files = [];
    public array $existing_bukti = [];

    // Temporary storage for files to delete upon saving
    public array $filesToDelete = [];

    public function mount(): void
    {
        $this->activeMenu = '';
        $this->filterStatus = '';

        if (request()->query('menu')) {
            $this->setActiveMenu((string) request()->query('menu'));
        }

        if (request()->query('create')) {
            $this->openCreateModal();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    public function updatingFilterTahun()
    {
        $this->resetPage();
    }

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function setActiveMenu(string $menu): void
    {
        if ($this->activeMenu === $menu) {
            $this->activeMenu = '';
            $this->filterStatus = '';
        } else {
            $this->activeMenu = $menu;

            if ($menu === 'draft') {
                $this->filterStatus = 'draft';
            } elseif ($menu === 'review') {
                $this->filterStatus = 'review';
            } elseif ($menu === 'published') {
                $this->filterStatus = 'published';
            } else {
                // keterangan atau menu lain
                $this->filterStatus = '';
            }
        }

        $this->resetPage();
    }

    public function setStatusFilter(string $status): void
    {
        $this->setActiveMenu($status);
    }

    // --- Create & Edit Modal Actions ---
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingKaryaId = null;
        $this->showCreateModal = true;
    }

    public function openEditModal(int $id): void
    {
        $karya = KaryaSiswa::findOrFail($id);

        if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki hak akses untuk mengedit karya ini.');
            return;
        }

        if (Auth::user()->role === 'siswa' && $karya->status === 'review') {
            session()->flash('error', 'Karya yang sedang dalam peninjauan tidak dapat diedit karena sudah diajukan.');
            return;
        }

        $this->resetForm();
        $this->editingKaryaId = $karya->id;
        $this->judul = $karya->judul ?? '';
        $this->deskripsi_singkat = $karya->deskripsi_singkat ?? '';
        $this->deskripsi = $karya->deskripsi ?? '';

        $standardCategories = ['web', 'mobile', 'desktop', 'game', 'iot', 'ui-ux', 'data', 'jaringan', 'robotika', 'ai'];
        if (in_array(strtolower($karya->kategori ?? ''), $standardCategories, true)) {
            $this->kategori = strtolower($karya->kategori);
            $this->kategori_lainnya = '';
        } else {
            $this->kategori = 'lainnya';
            $this->kategori_lainnya = $karya->kategori ?? '';
        }

        $this->teknologi = is_array($karya->teknologi) ? implode(', ', $karya->teknologi) : '';
        $this->url_demo = $karya->url_demo ?? '';
        $this->url_repo = $karya->url_repo ?? '';

        $this->existing_cover = $karya->gambar;
        $this->existing_screenshots = is_array($karya->screenshots) ? array_values($karya->screenshots) : [];
        $this->existing_bukti = is_array($karya->bukti) ? array_values($karya->bukti) : [];

        if ($this->showDetailModal) {
            $this->closeDetailModal();
        }

        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingKaryaId',
            'judul',
            'deskripsi_singkat',
            'deskripsi',
            'kategori',
            'kategori_lainnya',
            'teknologi',
            'cover_image',
            'existing_cover',
            'screenshots',
            'new_screenshots',
            'existing_screenshots',
            'bukti_files',
            'new_bukti_files',
            'existing_bukti',
            'url_demo',
            'url_repo',
            'filesToDelete',
        ]);
        $this->kategori = 'web';
        $this->resetValidation();
    }

    public function updatedNewScreenshots(): void
    {
        $newFiles = is_array($this->new_screenshots) ? $this->new_screenshots : [$this->new_screenshots];
        $added = 0;
        $ignored = 0;

        foreach ($newFiles as $file) {
            if ($file) {
                if (count($this->existing_screenshots) + count($this->screenshots) < 7) {
                    $this->screenshots[] = $file;
                    $added++;
                } else {
                    $ignored++;
                }
            }
        }
        $this->new_screenshots = [];

        if ($ignored > 0) {
            session()->flash('warning', "Maksimal 7 foto. {$ignored} foto tidak ditambahkan karena kuota penuh.");
        }

        $totalScreenshots = count($this->existing_screenshots) + count($this->screenshots);
        if ($totalScreenshots > 7) {
            $this->addError('screenshots', 'Maksimal 7 tangkapan layar (screenshot) aplikasi yang diizinkan.');
        } else {
            $this->resetErrorBag('screenshots');
        }
    }

    public function updatedScreenshots(): void
    {
        $totalScreenshots = count($this->existing_screenshots) + count($this->screenshots);
        if ($totalScreenshots > 7) {
            $this->addError('screenshots', 'Maksimal 7 tangkapan layar (screenshot) aplikasi yang diizinkan.');
        } else {
            $this->resetErrorBag('screenshots');
        }
    }

    public function updatedNewBuktiFiles(): void
    {
        $newFiles = is_array($this->new_bukti_files) ? $this->new_bukti_files : [$this->new_bukti_files];
        foreach ($newFiles as $file) {
            if ($file && count($this->existing_bukti) + count($this->bukti_files) < 5) {
                $this->bukti_files[] = $file;
            }
        }
        $this->new_bukti_files = [];

        $totalBukti = count($this->existing_bukti) + count($this->bukti_files);
        if ($totalBukti > 5) {
            $this->addError('bukti_files', 'Maksimal 5 berkas bukti karya yang diizinkan.');
        } else {
            $this->resetErrorBag('bukti_files');
        }
    }

    public function updatedBuktiFiles(): void
    {
        $totalBukti = count($this->existing_bukti) + count($this->bukti_files);
        if ($totalBukti > 5) {
            $this->addError('bukti_files', 'Maksimal 5 berkas bukti karya yang diizinkan.');
        } else {
            $this->resetErrorBag('bukti_files');
        }
    }

    public function getPreviewUrl($file): ?string
    {
        if (!$file) {
            return null;
        }

        if (is_string($file)) {
            return \Illuminate\Support\Facades\Storage::url($file);
        }

        if (is_object($file) && method_exists($file, 'temporaryUrl')) {
            try {
                $url = $file->temporaryUrl();
                if (request()->hasHeader('host')) {
                    $host = request()->header('host');
                    $parsed = parse_url($url);
                    if (isset($parsed['path'])) {
                        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
                        return request()->getScheme() . '://' . $host . $parsed['path'] . $query;
                    }
                }
                return $url;
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    public function getBuktiFileName($file): string
    {
        if (is_object($file) && method_exists($file, 'getClientOriginalName')) {
            return $file->getClientOriginalName();
        }
        if (is_string($file)) {
            return basename($file);
        }
        return 'Berkas Bukti';
    }

    public function getBuktiFileSize($file): string
    {
        if (is_object($file) && method_exists($file, 'getSize')) {
            $bytes = $file->getSize();
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return '';
    }

    public function removeCover(): void
    {
        if ($this->existing_cover) {
            $this->filesToDelete[] = $this->existing_cover;
            $this->existing_cover = null;
        }
        $this->cover_image = null;
    }

    public function removeScreenshot(int $index): void
    {
        if (isset($this->screenshots[$index])) {
            unset($this->screenshots[$index]);
            $this->screenshots = array_values($this->screenshots);
        }
    }

    public function removeExistingScreenshot(int $index): void
    {
        if (isset($this->existing_screenshots[$index])) {
            $this->filesToDelete[] = $this->existing_screenshots[$index];
            unset($this->existing_screenshots[$index]);
            $this->existing_screenshots = array_values($this->existing_screenshots);
        }
    }

    public function removeBukti(int $index): void
    {
        if (isset($this->bukti_files[$index])) {
            unset($this->bukti_files[$index]);
            $this->bukti_files = array_values($this->bukti_files);
        }
    }

    public function removeExistingBukti(int $index): void
    {
        if (isset($this->existing_bukti[$index])) {
            $this->filesToDelete[] = $this->existing_bukti[$index];
            unset($this->existing_bukti[$index]);
            $this->existing_bukti = array_values($this->existing_bukti);
        }
    }

    /**
     * Langsung kirim / ajukan karya yang berstatus draft untuk diverifikasi
     */
    public function submitDraft(int $id): void
    {
        $karya = KaryaSiswa::findOrFail($id);

        if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki hak akses untuk mengajukan karya ini.');
            return;
        }

        if ($karya->status !== 'draft') {
            session()->flash('error', 'Hanya karya berstatus draft yang dapat diajukan untuk verifikasi.');
            return;
        }

        $karya->update([
            'status' => 'review',
            'updated_at' => now(),
        ]);

        // Catat ke log keterangan karya (maks 5 log, tertua terhapus otomatis)
        KaryaLog::record(
            userId: $karya->user_id,
            judulKarya: $karya->judul,
            status: 'review',
            action: 'submitted',
            catatan: 'Karya diajukan untuk ditinjau dan diverifikasi oleh guru/admin.',
            karyaId: $karya->id,
            adminId: null
        );

        if ($this->detailKarya && $this->detailKarya->id === $karya->id) {
            $this->detailKarya = $karya->fresh(['user']);
        }

        session()->flash('message', "Karya \"{$karya->judul}\" berhasil diajukan untuk verifikasi guru/admin.");
        $this->dispatch('karya-updated');
    }

    public function save(string $targetStatus = 'review'): void
    {
        $isSiswa = Auth::user()->role === 'siswa';

        // Validasi batas maksimal 7 screenshot aplikasi
        $totalScreenshots = count($this->existing_screenshots) + count($this->screenshots);
        if ($totalScreenshots > 7) {
            $this->addError('screenshots', 'Maksimal 7 tangkapan layar (screenshot) aplikasi yang diizinkan.');
            return;
        }

        $this->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'kategori_lainnya' => 'required_if:kategori,lainnya|nullable|string|max:100',
            'deskripsi' => 'required|string|min:15',
            'deskripsi_singkat' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|max:3072|mimes:jpg,jpeg,png,webp',
            'screenshots' => 'array|max:7',
            'screenshots.*' => 'nullable|image|max:3072|mimes:jpg,jpeg,png,webp',
            'bukti_files' => 'array|max:5',
            'bukti_files.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf,zip',
            'url_demo' => 'nullable|url|max:255',
            'url_repo' => 'nullable|url|max:255',
        ], [
            'judul.required' => 'Judul karya wajib diisi.',
            'kategori.required' => 'Pilih kategori karya.',
            'kategori_lainnya.required_if' => 'Tuliskan kategori karya Anda.',
            'deskripsi.required' => 'Deskripsi karya wajib diisi.',
            'deskripsi.min' => 'Deskripsi minimal 15 karakter.',
            'cover_image.max' => 'Ukuran gambar cover maksimal 3 MB.',
            'screenshots.max' => 'Maksimal 7 tangkapan layar (screenshot) aplikasi yang diizinkan.',
            'screenshots.*.image' => 'Tangkapan layar harus berupa berkas gambar.',
            'screenshots.*.max' => 'Ukuran setiap screenshot maksimal 3 MB.',
            'bukti_files.*.max' => 'Ukuran setiap berkas bukti maksimal 5 MB.',
            'bukti_files.*.mimes' => 'Format berkas bukti harus berupa gambar (JPG, PNG, WebP) atau dokumen (PDF, ZIP).',
            'url_demo.url' => 'Format URL Demo tidak valid (gunakan http:// atau https://).',
            'url_repo.url' => 'Format URL Repositori tidak valid (gunakan http:// atau https://).',
        ]);

        try {
            $user = Auth::user();
            $kat = ($this->kategori === 'lainnya' && $this->kategori_lainnya) 
                ? strtolower(trim($this->kategori_lainnya)) 
                : $this->kategori;

            // 1. Simpan Cover Gambar Utama
            $gambarPath = $this->existing_cover;
            if ($this->cover_image) {
                if ($this->existing_cover) {
                    app(ImageService::class)->delete($this->existing_cover);
                }
                $gambarPath = app(ImageService::class)->processAndStore(
                    $this->cover_image,
                    'karya-siswa'
                );
            }

            // 2. Simpan Screenshots Aplikasi (Maksimal 7)
            $screenshotsPaths = $this->existing_screenshots;
            if (!empty($this->screenshots)) {
                foreach ($this->screenshots as $shot) {
                    if ($shot) {
                        $processedShot = app(ImageService::class)->processAndStore(
                            $shot,
                            'karya-screenshots'
                        );
                        if ($processedShot) {
                            $screenshotsPaths[] = $processedShot;
                        }
                    }
                }
            }
            $screenshotsPaths = array_values(array_slice($screenshotsPaths, 0, 7));

            // 3. Simpan Berkas Bukti Kepemilikan Karya
            $buktiPaths = $this->existing_bukti;
            if (!empty($this->bukti_files)) {
                foreach ($this->bukti_files as $file) {
                    if ($file) {
                        $storedPath = $file->store('karya-bukti', 'public');
                        if ($storedPath) {
                            $buktiPaths[] = $storedPath;
                        }
                    }
                }
            }
            $buktiPaths = array_values($buktiPaths);

            // Parse teknologi to array
            $teknologiArray = [];
            if ($this->teknologi) {
                $teknologiArray = array_values(array_filter(array_map('trim', explode(',', $this->teknologi))));
            }

            // Status: draft tetap draft jika disimpan draft, atau review untuk siswa, published untuk admin/guru
            $status = ($targetStatus === 'draft') ? 'draft' : ($isSiswa ? 'review' : 'published');

            $dataPayload = [
                'judul' => $this->judul,
                'deskripsi_singkat' => $this->deskripsi_singkat ?: Str::limit(strip_tags($this->deskripsi), 120),
                'deskripsi' => $this->deskripsi,
                'kategori' => $kat,
                'teknologi' => $teknologiArray,
                'gambar' => $gambarPath,
                'screenshots' => $screenshotsPaths,
                'bukti' => $buktiPaths,
                'url_demo' => $this->url_demo ?: null,
                'url_repo' => $this->url_repo ?: null,
                'status' => $status,
                'published_at' => ($status === 'published') ? now() : null,
            ];

            if ($this->editingKaryaId) {
                $karya = KaryaSiswa::findOrFail($this->editingKaryaId);

                // Authorization check
                if ($isSiswa && $karya->user_id !== $user->id) {
                    session()->flash('error', 'Anda tidak memiliki hak akses untuk mengedit karya ini.');
                    return;
                }

                if ($isSiswa && $karya->status === 'review') {
                    session()->flash('error', 'Karya yang sedang dalam peninjauan tidak dapat diedit karena sudah diajukan.');
                    return;
                }

                $karya->update($dataPayload);
                $actionText = 'diperbarui';
            } else {
                $dataPayload['user_id'] = $user->id;
                $dataPayload['nama_siswa'] = $user->name;
                $dataPayload['kelas'] = $user->kelas ?? 'XI RPL 1';
                $dataPayload['tahun'] = (int) date('Y');

                $karya = KaryaSiswa::create($dataPayload);
                $actionText = 'dibuat';
            }

            // Clean up any files queued for deletion
            if (!empty($this->filesToDelete)) {
                foreach ($this->filesToDelete as $fileToDelete) {
                    if ($fileToDelete) {
                        app(ImageService::class)->delete($fileToDelete);
                    }
                }
                $this->filesToDelete = [];
            }

            // Catat riwayat log karya siswa
            if ($status === 'review') {
                KaryaLog::record(
                    userId: $user->id,
                    judulKarya: $karya->judul,
                    status: 'review',
                    action: 'submitted',
                    catatan: $this->editingKaryaId 
                        ? 'Karya telah diperbarui dan diajukan kembali untuk ditinjau guru/admin.'
                        : 'Karya berhasil diajukan dan sedang menunggu verifikasi guru/admin.',
                    karyaId: $karya->id,
                    adminId: null
                );
            } elseif ($status === 'draft' && !$this->editingKaryaId) {
                KaryaLog::record(
                    userId: $user->id,
                    judulKarya: $karya->judul,
                    status: 'draft',
                    action: 'draft_created',
                    catatan: 'Karya baru disimpan sebagai draft.',
                    karyaId: $karya->id,
                    adminId: null
                );
            }

            $message = ($status === 'review')
                ? ($this->editingKaryaId 
                    ? "Karya \"{$karya->judul}\" berhasil diperbarui dan diajukan untuk verifikasi."
                    : "Karya \"{$karya->judul}\" berhasil diajukan dan sedang menunggu verifikasi guru/admin.")
                : (($status === 'draft')
                    ? "Karya \"{$karya->judul}\" berhasil disimpan sebagai draft."
                    : "Karya \"{$karya->judul}\" berhasil {$actionText} dan terpublikasi.");

            session()->flash('message', $message);
            $this->closeCreateModal();
            $this->dispatch('karya-updated');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyimpan karya: ' . $e->getMessage());
        }
    }

    // --- Detail Modal ---
    public function openDetailModal(int $id): void
    {
        $karya = KaryaSiswa::with('user')->findOrFail($id);

        if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki hak akses untuk melihat detail karya ini.');
            return;
        }

        $this->detailKarya = $karya;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->detailKarya = null;
    }

    // --- Delete Confirmation Modal ---
    public function confirmDelete(int $id): void
    {
        $karya = KaryaSiswa::findOrFail($id);

        if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki hak akses untuk menghapus karya ini.');
            return;
        }

        if (Auth::user()->role === 'siswa' && $karya->status === 'review') {
            session()->flash('error', 'Karya yang sedang dalam peninjauan tidak dapat dihapus karena sudah diajukan.');
            return;
        }

        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function delete(): void
    {
        if (!$this->confirmingDeleteId) {
            return;
        }

        try {
            $karya = KaryaSiswa::findOrFail($this->confirmingDeleteId);

            // Authorization check
            if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
                session()->flash('error', 'Anda tidak memiliki hak akses untuk menghapus karya ini.');
                $this->cancelDelete();
                return;
            }

            if (Auth::user()->role === 'siswa' && $karya->status === 'review') {
                session()->flash('error', 'Karya yang sedang dalam peninjauan tidak dapat dihapus karena sudah diajukan.');
                $this->cancelDelete();
                return;
            }

            $judul = $karya->judul;

            // Delete thumbnail image if present
            if ($karya->gambar) {
                app(ImageService::class)->delete($karya->gambar);
            }

            $karya->delete();

            session()->flash('message', "Karya \"{$judul}\" berhasil dihapus.");
            $this->cancelDelete();

            if ($this->showDetailModal && $this->detailKarya?->id === $karya->id) {
                $this->closeDetailModal();
            }

            $this->dispatch('karya-updated');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function render()
    {
        $user = Auth::user();

        $baseQuery = KaryaSiswa::query()
            ->when($user->role === 'siswa', fn($q) => $q->where('user_id', $user->id));

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'review' => (clone $baseQuery)->where('status', 'review')->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
            'keterangan' => KaryaLog::when($user->role === 'siswa', fn($q) => $q->where('user_id', $user->id))->count(),
        ];

        $karyaSiswas = (clone $baseQuery)
            ->with('user')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_siswa', 'like', '%' . $this->search . '%')
                        ->orWhere('kelas', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterKategori, fn($q) => $q->where('kategori', 'like', '%' . $this->filterKategori . '%'))
            ->when($this->filterTahun, fn($q) => $q->where('tahun', $this->filterTahun))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(12);

        $logs = KaryaLog::with(['admin', 'karyaSiswa'])
            ->when($user->role === 'siswa', fn($q) => $q->where('user_id', $user->id))
            ->latest('id')
            ->take(5)
            ->get();

        $years = KaryaSiswa::query()
            ->whereNotNull('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('livewire.karya-siswa-manager', [
            'karyaSiswas' => $karyaSiswas,
            'stats' => $stats,
            'logs' => $logs,
            'years' => $years,
        ]);
    }
}
