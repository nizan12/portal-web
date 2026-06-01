<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinkTagSeeder extends Seeder
{
    /**
     * Seed the t_link_tag pivot table.
     */
    public function run(): void
    {
        DB::table('t_link_tag')->insert([
            ['id_link' => 1, 'id_tag' => 2],
            ['id_link' => 2, 'id_tag' => 2],
            ['id_link' => 5, 'id_tag' => 2],
            ['id_link' => 6, 'id_tag' => 2],
            ['id_link' => 3, 'id_tag' => 2],
            ['id_link' => 4, 'id_tag' => 2],
            ['id_link' => 7, 'id_tag' => 2],
            ['id_link' => 3, 'id_tag' => 10],
            ['id_link' => 1, 'id_tag' => 10],
            ['id_link' => 4, 'id_tag' => 10],
            ['id_link' => 6, 'id_tag' => 10],
            ['id_link' => 7, 'id_tag' => 10],
            ['id_link' => 5, 'id_tag' => 10],
            ['id_link' => 2, 'id_tag' => 10],
            ['id_link' => 15, 'id_tag' => 10],
            ['id_link' => 15, 'id_tag' => 2],
        ]);
    }
}
