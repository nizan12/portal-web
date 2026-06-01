<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Seed the t_kategori table.
     */
    public function run(): void
    {
        DB::table('t_kategori')->insert([
            [
                'id_kategori'   => 1,
                'nik'           => null,
                'nama_kategori' => 'Akademik',
                'icon'          => null,
            ],
            [
                'id_kategori'   => 9,
                'nik'           => null,
                'nama_kategori' => 'Umum',
                'icon'          => 'grid',
            ],
        ]);
    }
}
