<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaporanSeeder extends Seeder
{
    /**
     * Seed the t_laporan table.
     */
    public function run(): void
    {
        DB::table('t_laporan')->insert([
            [
                'id_laporan'    => 1,
                'nik_pelapor'   => 115143,
                'jenis_laporan' => 'Masalah Akun',
                'isi_laporan'   => 'Tidak bisa login ke sistem meskipun username dan password sudah benar.',
            ],
            [
                'id_laporan'    => 2,
                'nik_pelapor'   => 122288,
                'jenis_laporan' => 'Masalah Website',
                'isi_laporan'   => 'Halaman website tidak bisa diakses sejak pagi, muncul error 500.',
            ],
            [
                'id_laporan'    => 3,
                'nik_pelapor'   => 225359,
                'jenis_laporan' => 'Masalah Akun',
                'isi_laporan'   => 'Proses backup data gagal dilakukan.',
            ],
            [
                'id_laporan'    => 4,
                'nik_pelapor'   => 225361,
                'jenis_laporan' => 'Masalah Akun',
                'isi_laporan'   => 'Sinkronisasi data antar sistem tidak berjalan.',
            ],
            [
                'id_laporan'    => 5,
                'nik_pelapor'   => 218292,
                'jenis_laporan' => 'Penambahan Link',
                'isi_laporan'   => 'Minta penambahan website layanan baru untuk administrasi.',
            ],
            [
                'id_laporan'    => 6,
                'nik_pelapor'   => 224345,
                'jenis_laporan' => 'Penambahan Link',
                'isi_laporan'   => 'Mohon tambahkan link portal akademik terbaru.',
            ],
        ]);
    }
}
