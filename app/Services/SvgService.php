<?php

namespace App\Services;

use App\Models\ProfilAset;
use App\Services\Svg\AnimatedSvgAttributes;
use App\Services\Svg\AnimatedSvgTags;
use enshrined\svgSanitize\Sanitizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class SvgService
{
    /**
     * Max SVG file size in bytes (1 MB).
     */
    public const MAX_SIZE = 1024 * 1024;

    /**
     * Max number of SVG assets per student account.
     */
    public const MAX_FILES_PER_USER = 5;

    /**
     * Store an uploaded SVG for a user: validate, sanitize, then save to
     * the public disk under a per-account folder (users/svg/u{id}).
     *
     * @param  UploadedFile  $file  The uploaded SVG file
     * @param  int  $userId  Owner of the asset
     * @param  string|null  $originalName  Optional display name override
     * @return ProfilAset The created asset record
     *
     * @throws RuntimeException When validation or sanitization fails
     */
    public function store(UploadedFile $file, int $userId, ?string $originalName = null): ProfilAset
    {
        // 1. Basic validation
        if (! $file->isValid()) {
            throw new RuntimeException('File upload tidak valid. Coba lagi.');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension !== 'svg') {
            throw new RuntimeException('File harus berekstensi .svg');
        }

        $mime = $file->getMimeType();
        $allowedMimes = ['image/svg+xml', 'image/svg', 'text/xml', 'application/xml', 'text/plain'];
        if (! in_array($mime, $allowedMimes, true)) {
            throw new RuntimeException('Tipe file tidak didukung. Upload file SVG yang valid.');
        }

        if ($file->getSize() > self::MAX_SIZE) {
            throw new RuntimeException('Ukuran SVG maksimal 1 MB.');
        }

        // 2. Content must be parseable XML
        $content = $file->getContent();
        if (trim($content) === '') {
            throw new RuntimeException('File SVG kosong.');
        }

        $doc = @simplexml_load_string($content);
        if ($doc === false) {
            throw new RuntimeException('File bukan SVG yang valid (XML tidak bisa dibaca).');
        }
        if (stripos((string) $doc->getName(), 'svg') === false) {
            throw new RuntimeException('File bukan SVG yang valid (root element bukan <svg>).');
        }

        // 3. Sanitize (removes <script>, on* handlers, foreignObject, external hrefs, etc.)
        $sanitized = $this->sanitize($content);
        if ($sanitized === false) {
            throw new RuntimeException('SVG gagal disanitasi. File mungkin berisi konten berbahaya.');
        }

        if (strlen($sanitized) > self::MAX_SIZE) {
            throw new RuntimeException('Ukuran SVG maksimal 1 MB.');
        }

        // 4. Per-account folder + unique file name
        $name = $originalName ?: $file->getClientOriginalName();
        $base = pathinfo($name, PATHINFO_FILENAME);
        $slug = Str::slug($base) ?: 'aset';
        $fileName = $slug.'-'.Str::lower(Str::random(8)).'.svg';
        $directory = 'users/svg/u'.$userId;

        // 5. Store sanitized content (order: store file first, then DB row)
        $path = $directory.'/'.$fileName;
        if (! Storage::disk('public')->put($path, $sanitized)) {
            throw new RuntimeException('Gagal menyimpan file. Coba lagi.');
        }

        try {
            return ProfilAset::create([
                'user_id' => $userId,
                'nama_file' => $base.'.svg',
                'path' => $path,
                'size' => strlen($sanitized),
            ]);
        } catch (\Throwable $e) {
            // Roll back the stored file if DB insert fails
            Storage::disk('public')->delete($path);
            throw new RuntimeException('Gagal menyimpan data aset. Coba lagi.');
        }
    }

    /**
     * Sanitize SVG content using enshrined/svg-sanitize with an
     * animation-friendly whitelist (SMIL tags & attributes kept),
     * then apply hardening passes that the sanitizer does not cover.
     *
     * @return string|false Sanitized XML string, or false on failure
     */
    public function sanitize(string $content): string|false
    {
        $sanitizer = new Sanitizer;
        $sanitizer->setAllowedTags(new AnimatedSvgTags);
        $sanitizer->setAllowedAttrs(new AnimatedSvgAttributes);

        $clean = $sanitizer->sanitize($content);

        if ($clean === false) {
            return false;
        }

        return $this->harden($clean);
    }

    /**
     * Extra hardening on top of the sanitizer:
     * - strip <script> bodies that survived inside allowed tags
     * - drop SMIL animations that target href/xlink:href (which could
     *   otherwise rewrite a link into a javascript: URL over time)
     */
    protected function harden(string $svg): string
    {
        // Remove any <script> element entirely (belt and suspenders)
        $svg = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $svg);
        $svg = preg_replace('#<script\b[^>]*/?>#i', '', $svg);

        // Remove SMIL animation elements whose attributeName is href-ish
        $svg = preg_replace(
            '#<(animate|set)\b[^>]*attributeName\s*=\s*["\'](?:xlink:)?href["\'][^>]*>(?:.*?</\1>)?#is',
            '',
            $svg
        );

        return $svg;
    }

    /**
     * Delete an asset: remove DB row and the file on disk.
     */
    public function delete(ProfilAset $asset): void
    {
        Storage::disk('public')->delete($asset->path);
        $asset->delete();
    }
}
