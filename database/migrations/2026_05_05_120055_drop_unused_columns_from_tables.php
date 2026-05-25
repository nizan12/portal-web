<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop the unused 'tag' column in t_link
        if (Schema::hasColumn('t_link', 'tag')) {
            Schema::table('t_link', function (Blueprint $table) {
                $table->dropColumn('tag');
            });
        }

        // 2. Consolidate NIK columns in t_kategori
        if (Schema::hasColumn('t_kategori', 'nik_pengguna')) {
            // Copy data to 'nik' if 'nik' is currently NULL
            DB::table('t_kategori')
                ->whereNull('nik')
                ->whereNotNull('nik_pengguna')
                ->update(['nik' => DB::raw('nik_pengguna')]);
            
            // Drop foreign key and then drop nik_pengguna
            Schema::table('t_kategori', function (Blueprint $table) {
                // Check if foreign key exists (standard practice)
                $table->dropForeign('fk_kategori_pengguna');
                $table->dropColumn('nik_pengguna');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse this cleanly without specific snapshots
    }
};
