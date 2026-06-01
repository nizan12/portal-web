<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create t_pengguna
        if (!Schema::hasTable('t_pengguna')) {
            Schema::create('t_pengguna', function (Blueprint $table) {
                $table->integer('nik')->primary();
                $table->string('nama_user', 150);
                $table->string('password', 255);
                $table->string('email', 150)->unique();
                $table->string('jabatan', 50);
            });
        }

        // 2. Create t_admin
        if (!Schema::hasTable('t_admin')) {
            Schema::create('t_admin', function (Blueprint $table) {
                $table->integer('nik_admin')->primary();
                $table->string('nama', 150);
                $table->string('email', 255)->nullable();
                $table->string('password', 255);
            });
        }

        // 3. Create t_kategori
        if (!Schema::hasTable('t_kategori')) {
            Schema::create('t_kategori', function (Blueprint $table) {
                $table->integer('id_kategori')->autoIncrement()->primary();
                $table->integer('nik_pengguna');
                $table->string('nama_kategori', 100);
                $table->string('icon', 100)->nullable();
            });
        }

        // 4. Create t_link
        if (!Schema::hasTable('t_link')) {
            Schema::create('t_link', function (Blueprint $table) {
                $table->integer('id_link')->autoIncrement()->primary();
                $table->string('nama_web', 150);
                $table->string('url', 255);
                $table->text('deskripsi')->nullable();
                $table->string('tag', 255)->nullable();
                $table->string('status', 50)->default('aktif');
                $table->integer('hit_point')->default(0);
            });
        }

        // 5. Create t_laporan
        if (!Schema::hasTable('t_laporan')) {
            Schema::create('t_laporan', function (Blueprint $table) {
                $table->integer('id_laporan')->autoIncrement()->primary();
                $table->integer('nik_pelapor');
                $table->enum('jenis_laporan', ['Penambahan Link', 'Masalah Website', 'Masalah Akun', 'Lainnya']);
                $table->text('isi_laporan');
                
                $table->foreign('nik_pelapor')->references('nik')->on('t_pengguna')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        // 6. Create t_terdaftar
        if (!Schema::hasTable('t_terdaftar')) {
            Schema::create('t_terdaftar', function (Blueprint $table) {
                $table->integer('id')->autoIncrement()->primary();
                $table->integer('id_kategori');
                $table->integer('id_link');
                
                $table->foreign('id_kategori')->references('id_kategori')->on('t_kategori')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('id_link')->references('id_link')->on('t_link')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('t_terdaftar');
        Schema::dropIfExists('t_laporan');
        Schema::dropIfExists('t_link');
        Schema::dropIfExists('t_kategori');
        Schema::dropIfExists('t_admin');
        Schema::dropIfExists('t_pengguna');
    }
};
