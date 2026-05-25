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
        // 1. Fill NULL values in t_link
        DB::table('t_link')->whereNull('deskripsi')->orWhere('deskripsi', '')
            ->update(['deskripsi' => 'Layanan website Politeknik Negeri Batam yang tersedia di portal POLTREE.']);
        
        DB::table('t_link')->whereNull('status')->orWhere('status', '')
            ->update(['status' => 'aktif']);
            
        DB::table('t_link')->whereNull('hit_point')
            ->update(['hit_point' => 0]);

        // 2. Migrate tags from t_link.tag to many-to-many relationship
        $linksWithTags = DB::table('t_link')->whereNotNull('tag')->where('tag', '!=', '')->get();
        
        foreach ($linksWithTags as $link) {
            $tagNames = explode(',', $link->tag);
            foreach ($tagNames as $name) {
                $name = trim($name);
                if ($name === '') continue;

                // Find or create tag
                $tagId = DB::table('t_tag')->where('nama_tag', $name)->value('id_tag');
                if (!$tagId) {
                    $tagId = DB::table('t_tag')->insertGetId(['nama_tag' => $name]);
                }

                // Check if already linked
                $exists = DB::table('t_link_tag')
                    ->where('id_link', $link->id_link)
                    ->where('id_tag', $tagId)
                    ->exists();

                if (!$exists) {
                    DB::table('t_link_tag')->insert([
                        'id_link' => $link->id_link,
                        'id_tag' => $tagId
                    ]);
                }
            }
        }

        // 3. Drop unused users table if it's empty
        if (Schema::hasTable('users') && DB::table('users')->count() === 0) {
            Schema::dropIfExists('users');
        }

        // 4. (Optional) You can choose to clear the old tag column here or keep it for safety
        // DB::table('t_link')->update(['tag' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse this cleanly without specific snapshots
    }
};
