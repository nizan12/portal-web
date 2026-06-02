<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom username ke t_admin dan t_pengguna
     * agar user bisa login pakai NIK atau username.
     */
    public function up(): void
    {
        Schema::table('t_admin', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('nik_admin');
        });

        Schema::table('t_pengguna', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('nik');
        });
    }

    public function down(): void
    {
        Schema::table('t_admin', function (Blueprint $table) {
            $table->dropColumn('username');
        });

        Schema::table('t_pengguna', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
