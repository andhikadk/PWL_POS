<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1,
                'kategori_kode' => 'KMR',
                'kategori_nama' => 'Kamera',
            ],
            [
                'kategori_id' => 2,
                'kategori_kode' => 'LEN',
                'kategori_nama' => 'Lensa',
            ],
            [
                'kategori_id' => 3,
                'kategori_kode' => 'KBL',
                'kategori_nama' => 'Kabel',
            ],
            [
                'kategori_id' => 4,
                'kategori_kode' => 'AKS',
                'kategori_nama' => 'Aksesoris',
            ],
            [
                'kategori_id' => 5,
                'kategori_kode' => 'PRL',
                'kategori_nama' => 'Perlengkapan',
            ],
        ];

        DB::table('m_kategori')->insert($data);
    }
}
