<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karya_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('karya_siswa_id')->nullable()->constrained('karya_siswas')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('judul_karya');
            $table->string('status'); // draft, review, published, rejected
            $table->string('action'); // submitted, rejected, approved, draft_saved
            $table->text('catatan')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        // Backfill initial logs from existing karya_siswas
        try {
            $existingKarya = \Illuminate\Support\Facades\DB::table('karya_siswas')->get();
            foreach ($existingKarya as $karya) {
                if (!empty($karya->catatan_admin)) {
                    \Illuminate\Support\Facades\DB::table('karya_logs')->insert([
                        'user_id' => $karya->user_id,
                        'karya_siswa_id' => $karya->id,
                        'judul_karya' => $karya->judul ?? 'Karya Siswa',
                        'status' => 'rejected',
                        'action' => 'rejected',
                        'catatan' => $karya->catatan_admin,
                        'created_at' => $karya->updated_at ?? now(),
                        'updated_at' => $karya->updated_at ?? now(),
                    ]);
                } elseif ($karya->status === 'published') {
                    \Illuminate\Support\Facades\DB::table('karya_logs')->insert([
                        'user_id' => $karya->user_id,
                        'karya_siswa_id' => $karya->id,
                        'judul_karya' => $karya->judul ?? 'Karya Siswa',
                        'status' => 'published',
                        'action' => 'approved',
                        'catatan' => 'Karya telah disetujui dan resmi dipublikasikan.',
                        'created_at' => $karya->published_at ?? $karya->updated_at ?? now(),
                        'updated_at' => $karya->published_at ?? $karya->updated_at ?? now(),
                    ]);
                } elseif ($karya->status === 'review') {
                    \Illuminate\Support\Facades\DB::table('karya_logs')->insert([
                        'user_id' => $karya->user_id,
                        'karya_siswa_id' => $karya->id,
                        'judul_karya' => $karya->judul ?? 'Karya Siswa',
                        'status' => 'review',
                        'action' => 'submitted',
                        'catatan' => 'Karya diajukan untuk ditinjau oleh guru/admin.',
                        'created_at' => $karya->updated_at ?? now(),
                        'updated_at' => $karya->updated_at ?? now(),
                    ]);
                }
            }

            // Prune to max 5 per user
            $userIds = \Illuminate\Support\Facades\DB::table('karya_logs')->distinct()->pluck('user_id');
            foreach ($userIds as $uid) {
                $total = \Illuminate\Support\Facades\DB::table('karya_logs')->where('user_id', $uid)->count();
                if ($total > 5) {
                    $excessIds = \Illuminate\Support\Facades\DB::table('karya_logs')
                        ->where('user_id', $uid)
                        ->orderBy('id', 'asc')
                        ->limit($total - 5)
                        ->pluck('id');
                    \Illuminate\Support\Facades\DB::table('karya_logs')->whereIn('id', $excessIds)->delete();
                }
            }
        } catch (\Throwable $e) {
            // Silently continue if tables are empty during clean migrations
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('karya_logs');
    }
};
