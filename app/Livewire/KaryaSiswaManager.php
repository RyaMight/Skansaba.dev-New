<?php

namespace App\Livewire;

use App\Models\KaryaSiswa;
use App\Services\ImageService;
use App\Services\MarkdownService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Kelola Karya Siswa')]
class KaryaSiswaManager extends Component
{
    use WithFileUploads;

    // List properties
    public $karyaSiswas = [];
    public $search = '';
    public $filterStatus = '';
    public $filterKategori = '';
    public $filterTahun = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Form properties
    public $showModal = false;
    public $showDetailModal = false;
    public $isEditMode = false;
    public $karyaSiswaId = null;
    public $detailKarya = null;

    // Form fields
    public $judul = '';
    public $deskripsi = '';
    public $kategori = '';
    public $kategori_lainnya = '';
    public $gambar = null;
    public $currentGambar = null;
    public $url_demo = '';
    public $url_repo = '';
    public $status = 'draft';
    public $bukti = []; // uploaded files (Livewire TemporaryUploadedFile[])
    public $existingBukti = []; // paths already stored on the model
    public $screenshots = []; // new screenshot uploads (max 7)
    public $existingScreenshots = []; // paths already stored on the model
    public $showSubmitInfoModal = false;

    protected $listeners = ['refreshKaryaSiswas' => 'loadKaryaSiswas'];

    public function mount()
    {
        $this->loadKaryaSiswas();
    }

    public function loadKaryaSiswas()
    {
        $user = Auth::user();
        $query = KaryaSiswa::with('user')
            ->when($user->role === 'siswa', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_siswa', 'like', '%' . $this->search . '%')
                        ->orWhere('kelas', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
            ->when($this->filterTahun, fn($q) => $q->where('tahun', $this->filterTahun))
            ->orderBy($this->sortField, $this->sortDirection);

        $this->karyaSiswas = $query->get();
    }

    public function updatedSearch()
    {
        $this->loadKaryaSiswas();
    }

    public function updatedFilterStatus()
    {
        $this->loadKaryaSiswas();
    }

    public function updatedFilterKategori()
    {
        $this->loadKaryaSiswas();
    }

    public function updatedFilterTahun()
    {
        $this->loadKaryaSiswas();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->loadKaryaSiswas();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $karya = KaryaSiswa::findOrFail($id);

        // Authorization check
        if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk mengubah karya ini.');
            return;
        }

        $this->karyaSiswaId = $karya->id;
        $this->judul = $karya->judul;
        $this->deskripsi = $karya->deskripsi;
        $this->kategori = $karya->kategori;
        $this->currentGambar = $karya->gambar;
        $this->url_demo = $karya->url_demo ?? '';
        $this->url_repo = $karya->url_repo ?? '';
        $this->status = $karya->status;
        $this->existingBukti = $karya->bukti ?? [];
        $this->existingScreenshots = $karya->screenshots ?? [];

        // "Lainnya" category: prefill the free-text input
        if (!in_array($karya->kategori, $this->kategoriOptions())) {
            $this->kategori_lainnya = $karya->kategori;
            $this->kategori = 'lainnya';
        }
        
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function updatedKategori($value)
    {
        // Clear free-text when user re-picks a fixed category
        if ($value !== 'lainnya') {
            $this->kategori_lainnya = '';
        }
    }

    public function kategoriOptions(): array
    {
        return [
            'web', 'mobile', 'desktop', 'game', 'iot',
            'ui-ux', 'jaringan', 'data', 'animasi-3d',
            'video', 'desain-grafis', 'robotika', 'ai',
            'lainnya',
        ];
    }

    public function save($confirmSubmit = false)
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10',
            'kategori' => 'required|string|max:255',
            'kategori_lainnya' => 'required_if:kategori,lainnya|nullable|string|max:255',
            'gambar' => 'nullable|string',
            'url_demo' => 'nullable|url|max:255',
            'url_repo' => 'nullable|url|max:255',
            'bukti.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf',
            'screenshots.*' => 'nullable|image|max:5120|mimes:jpg,jpeg,png,webp',
        ]);

        $isSiswa = Auth::user()->role === 'siswa';

        // First save press: show the confirmation popup for students instead of saving.
        // Validation already passed above, so the popup only appears for a complete form.
        if ($isSiswa && $confirmSubmit === false) {
            $this->showSubmitInfoModal = true;
            return;
        }

        // Siswa submits for approval: status choice is not theirs to make.
        if ($isSiswa) {
            $this->status = 'review';
        }

        try {
            $kategori = $this->kategori === 'lainnya' && $this->kategori_lainnya
                ? strtolower(trim($this->kategori_lainnya))
                : $this->kategori;

            $user = Auth::user();
            $data = [
                'user_id' => $user->id,
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'kategori' => $kategori,
                'tahun' => date('Y'),
                'url_demo' => $this->url_demo ?: null,
                'url_repo' => $this->url_repo ?: null,
                'status' => $this->status,
                'bukti' => $this->existingBukti,
                'screenshots' => $this->existingScreenshots,
                // Nama siswa & kelas diambil dari akun, bukan input manual
                'nama_siswa' => $user->name,
                'kelas' => $user->kelas ?? '-',
            ];

            // Store newly uploaded bukti files
            foreach ($this->bukti as $file) {
                $data['bukti'][] = $file->store('karya-siswa/bukti', 'public');
            }
            $this->bukti = [];

            // Store new screenshots (max 7 total)
            foreach ($this->screenshots as $file) {
                if (count($data['screenshots']) >= 7) {
                    break;
                }
                $data['screenshots'][] = $file->store('karya-siswa/screenshots', 'public');
            }
            $this->screenshots = [];

            // First screenshot becomes the cover for public display
            if (empty($data['gambar']) && !empty($data['screenshots'])) {
                $data['gambar'] = $data['screenshots'][0];
            }

            // Handle image upload via ImageService
            if ($this->gambar && str_starts_with($this->gambar, 'data:image')) {
                // Delete old image if editing
                if ($this->isEditMode && $this->currentGambar) {
                    $oldKarya = KaryaSiswa::find((int)$this->karyaSiswaId);
                    if ($oldKarya && $oldKarya->gambar) {
                        $imageService = app(ImageService::class);
                        $imageService->delete($oldKarya->gambar);
                    }
                }

                // Decode base64 to image
                $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $this->gambar);
                $imageData = base64_decode($base64);
                
                // Create temporary file
                $tempPath = storage_path('app/temp');
                if (!file_exists($tempPath)) {
                    mkdir($tempPath, 0755, true);
                }
                
                $tempFile = $tempPath . '/' . uniqid('crop_') . '.jpg';
                file_put_contents($tempFile, $imageData);
                
                // Create UploadedFile instance
                $uploadedFile = new \Illuminate\Http\UploadedFile(
                    $tempFile,
                    uniqid() . '.jpg',
                    'image/jpeg',
                    null,
                    true
                );

                // Process: compress → WebP (already cropped by Croppie)
                $imageService = app(ImageService::class);
                $data['gambar'] = $imageService->processAndStore(
                    $uploadedFile,
                    'karya-siswa'
                );
                
                // Cleanup temp file
                @unlink($tempFile);

                $this->gambar = null;
            }

            // Handle published_at timestamp
            if ($this->status === 'published') {
                $data['published_at'] = now();
            }

            if ($this->isEditMode) {
                $karya = KaryaSiswa::findOrFail((int)$this->karyaSiswaId);
                $karya->fill($data)->save();
                
                session()->flash('message', 'Karya siswa berhasil diperbarui!');
            } else {
                KaryaSiswa::create($data);
                session()->flash('message', 'Karya siswa berhasil ditambahkan!');
            }

            $this->closeModal();
            $this->loadKaryaSiswas();

            if ($isSiswa) {
                session()->flash('message', 'Karya berhasil diajukan! Menunggu persetujuan admin sebelum dipublikasikan.');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeSubmitInfoModal()
    {
        $this->showSubmitInfoModal = false;
    }

    public function delete($id)
    {
        try {
            $karya = KaryaSiswa::findOrFail((int)$id);

            // Authorization check
            if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
                session()->flash('error', 'Anda tidak memiliki akses untuk menghapus karya ini.');
                return;
            }

            // Delete image
            if ($karya->gambar) {
                app(ImageService::class)->delete($karya->gambar);
            }

            $karya->delete();

            session()->flash('message', 'Karya siswa berhasil dihapus!');
            $this->loadKaryaSiswas();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin/guru approves a submitted (review) karya → published.
     */
    public function approve($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'guru'])) {
            session()->flash('error', 'Hanya admin/guru yang dapat menyetujui karya.');
            return;
        }

        try {
            $karya = KaryaSiswa::findOrFail((int)$id);

            if ($karya->status !== 'review') {
                session()->flash('error', 'Hanya karya berstatus review yang dapat disetujui.');
                return;
            }

            $karya->status = 'published';
            $karya->published_at = now();
            $karya->save();

            session()->flash('message', 'Karya berhasil disetujui dan dipublikasikan.');
            $this->loadKaryaSiswas();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin/guru rejects a submitted (review) karya → draft for revision.
     */
    public function reject($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'guru'])) {
            session()->flash('error', 'Hanya admin/guru yang dapat menolak karya.');
            return;
        }

        try {
            $karya = KaryaSiswa::findOrFail((int)$id);

            if ($karya->status !== 'review') {
                session()->flash('error', 'Hanya karya berstatus review yang dapat ditolak.');
                return;
            }

            $karya->status = 'draft';
            $karya->save();

            session()->flash('message', 'Karya ditolak dan dikembalikan ke siswa sebagai draft.');
            $this->loadKaryaSiswas();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function removeBukti($path)
    {
        $karya = KaryaSiswa::findOrFail((int)$this->karyaSiswaId);

        if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki akses.');
            return;
        }

        Storage::disk('public')->delete($path);

        $bukti = collect($karya->bukti ?? [])
            ->reject(fn($p) => $p === $path)
            ->values()
            ->all();

        $karya->update(['bukti' => $bukti]);
        $this->existingBukti = $bukti;
    }

    public function removeScreenshot($path)
    {
        $karya = KaryaSiswa::findOrFail((int)$this->karyaSiswaId);

        if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki akses.');
            return;
        }

        Storage::disk('public')->delete($path);

        $screenshots = collect($karya->screenshots ?? [])
            ->reject(fn($p) => $p === $path)
            ->values()
            ->all();

        $karya->update(['screenshots' => $screenshots]);

        // Cover (gambar) removed → promote first screenshot
        if ($karya->gambar === $path) {
            $karya->update(['gambar' => $screenshots[0] ?? null]);
        }

        $this->existingScreenshots = $screenshots;
        $this->currentGambar = $karya->gambar;
    }

    public function forceDelete($id)
    {
        try {
            $karya = KaryaSiswa::withTrashed()->findOrFail((int)$id);

            // Authorization check
            if (Auth::user()->role === 'siswa' && $karya->user_id !== Auth::id()) {
                session()->flash('error', 'Anda tidak memiliki akses untuk menghapus karya ini.');
                return;
            }

            // Delete image permanently
            if ($karya->gambar) {
                app(ImageService::class)->delete($karya->gambar);
            }

            // Delete bukti & screenshot files permanently
            foreach (array_merge($karya->bukti ?? [], $karya->screenshots ?? []) as $path) {
                Storage::disk('public')->delete($path);
            }

            $karya->forceDelete();

            session()->flash('message', 'Karya siswa berhasil dihapus permanen!');
            $this->loadKaryaSiswas();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            KaryaSiswa::withTrashed()->findOrFail($id)->restore();
            session()->flash('message', 'Karya siswa berhasil dipulihkan!');
            $this->loadKaryaSiswas();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function openDetailModal($id)
    {
        $this->detailKarya = KaryaSiswa::with('user')->findOrFail((int)$id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->detailKarya = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showSubmitInfoModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'karyaSiswaId',
            'judul',
            'deskripsi',
            'kategori',
            'kategori_lainnya',
            'gambar',
            'currentGambar',
            'url_demo',
            'url_repo',
            'status',
            'bukti',
            'existingBukti',
            'screenshots',
            'existingScreenshots',
        ]);
        $this->status = 'draft';
    }

    public function render()
    {
        return view('livewire.karya-siswa-manager', [
            'years' => KaryaSiswa::query()
                ->selectRaw('DISTINCT tahun')
                ->orderByDesc('tahun')
                ->pluck('tahun'),
        ]);
    }
}
