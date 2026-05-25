<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('t_link', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kategori')->nullable()->after('id_link');
            
            // If you want to add foreign key constraint:
            // $table->foreign('id_kategori')->references('id_kategori')->on('t_kategori')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_link', function (Blueprint $table) {
            $table->dropColumn('id_kategori');
        });
    }
};
