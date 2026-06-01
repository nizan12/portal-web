<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_link', function (Blueprint $table) {
            if (! Schema::hasColumn('t_link', 'status_link')) {
                $table->string('status_link', 50)->default('belum dicek')->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('t_link', function (Blueprint $table) {
            if (Schema::hasColumn('t_link', 'status_link')) {
                $table->dropColumn('status_link');
            }
        });
    }
};
