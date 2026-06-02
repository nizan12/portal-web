<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Seed the t_pengguna table.
     */
    public function run(): void
    {
        $password = Hash::make('123456#');

        DB::table('t_pengguna')->insert([
            [
                'nik'       => 113103,
                'username'  => 'maria.ipp',
                'nama_user' => 'Ir. Maria, S.ST., M.Sn., IPP',
                'password'  => $password,
                'email'     => 'maria.ipp@polibatam.ac.id',
                'jabatan'   => 'Dosen',
            ],
            [
                'nik'       => 115143,
                'username'  => 'ahmad.thohari',
                'nama_user' => 'Ahmad Hamim Thohari, S.S.T., M.T.',
                'password'  => $password,
                'email'     => 'ahmad.thohari@polibatam.ac.id',
                'jabatan'   => 'Dosen',
            ],
            [
                'nik'       => 122288,
                'username'  => 'festy.winda',
                'nama_user' => 'Festy Winda Sari, S.Tr. Kom., M.Sc',
                'password'  => $password,
                'email'     => 'festy.winda@polibatam.ac.id',
                'jabatan'   => 'Dosen',
            ],
            [
                'nik'       => 218292,
                'username'  => 'dede.nurdiansyah',
                'nama_user' => 'Dede Nurdiansyah, S.Sos',
                'password'  => $password,
                'email'     => 'dede.nurdiansyah@polibatam.ac.id',
                'jabatan'   => 'Tata Usaha',
            ],
            [
                'nik'       => 224345,
                'username'  => 'rhanna.mawira',
                'nama_user' => 'Rhanna Mawira, S.E',
                'password'  => $password,
                'email'     => 'rhanna.mawira@polibatam.ac.id',
                'jabatan'   => 'Tata Usaha',
            ],
            [
                'nik'       => 225359,
                'username'  => 'miftahul.husna',
                'nama_user' => 'Miftahul Husna Ghawa, S.Tr.Kom',
                'password'  => $password,
                'email'     => 'miftahul.husna@polibatam.ac.id',
                'jabatan'   => 'Laboran',
            ],
            [
                'nik'       => 225361,
                'username'  => 'yogi.ilhami',
                'nama_user' => 'Yogi Ilhami, S.Tr.T',
                'password'  => $password,
                'email'     => 'yogi.ilhami@polibatam.ac.id',
                'jabatan'   => 'Laboran',
            ],
        ]);
    }
}
