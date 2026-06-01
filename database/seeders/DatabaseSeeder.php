<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Urutan seeder disesuaikan dengan dependensi foreign key:
     * 1. Admin       → t_admin (tidak ada FK)
     * 2. Pengguna    → t_pengguna (tidak ada FK)
     * 3. Kategori    → t_kategori (tidak ada FK)
     * 4. Tag         → t_tag (tidak ada FK)
     * 5. Link        → t_link (FK ke t_kategori)
     * 6. LinkTag     → t_link_tag (FK ke t_link & t_tag)
     * 7. Laporan     → t_laporan (FK ke t_pengguna)
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            PenggunaSeeder::class,
            KategoriSeeder::class,
            TagSeeder::class,
            LinkSeeder::class,
            LinkTagSeeder::class,
            LaporanSeeder::class,
        ]);
    }
}
