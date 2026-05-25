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
        Schema::create('t_tag', function (Blueprint $table) {
            $table->id('id_tag'); // This creates a bigIncrements (bigint unsigned)
            $table->string('nama_tag')->unique();
        });

        Schema::create('t_link_tag', function (Blueprint $table) {
            $table->id();
            $table->integer('id_link'); // Match t_link.id_link (int)
            $table->unsignedBigInteger('id_tag'); // Match t_tag.id_tag
            $table->foreign('id_link')->references('id_link')->on('t_link')->onDelete('cascade');
            $table->foreign('id_tag')->references('id_tag')->on('t_tag')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_link_tag');
        Schema::dropIfExists('t_tag');
    }
};
