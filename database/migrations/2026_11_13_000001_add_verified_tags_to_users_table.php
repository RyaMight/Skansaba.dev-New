<?php

use App\Livewire\KaryaSiswaVerifikasi;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('verified_tags')->nullable()->after('keahlian');
        });

        // Pindahkan tag role preset admin yang sebelumnya tersimpan di kolom keahlian ke verified_tags
        $presetsLower = array_map('mb_strtolower', KaryaSiswaVerifikasi::TAG_PRESETS);

        DB::table('users')
            ->whereNotNull('keahlian')
            ->where('keahlian', '!=', '')
            ->orderBy('id')
            ->chunkById(100, function ($users) use ($presetsLower) {
                foreach ($users as $user) {
                    $items = array_values(array_filter(array_map('trim', explode(',', $user->keahlian))));
                    $verified = [];
                    $techStack = [];

                    foreach ($items as $item) {
                        $presetIndex = array_search(mb_strtolower($item), $presetsLower, true);
                        if ($presetIndex !== false) {
                            $verified[] = KaryaSiswaVerifikasi::TAG_PRESETS[$presetIndex];
                        } else {
                            $techStack[] = $item;
                        }
                    }

                    if (! empty($verified)) {
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update([
                                'verified_tags' => implode(', ', array_values(array_unique($verified))),
                                'keahlian' => count($techStack) > 0 ? implode(', ', $techStack) : null,
                            ]);
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('verified_tags');
        });
    }
};
