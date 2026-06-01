<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Seed the t_tag table.
     */
    public function run(): void
    {
        DB::table('t_tag')->insert([
            [
                'id_tag'   => 2,
                'nama_tag' => 'Utama',
            ],
            [
                'id_tag'   => 10,
                'nama_tag' => 'baru',
            ],
        ]);
    }
}
