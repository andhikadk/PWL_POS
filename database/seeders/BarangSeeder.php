<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat 10 barang berbeda tanpa looping
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 'KMR001',
                'barang_nama' => 'Kamera Canon 1000D',
                'harga_beli' => 10000000,
                'harga_jual' => 12000000,
                'stok' => 10,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => 'KMR002',
                'barang_nama' => 'Kamera Nikon D90',
                'harga_beli' => 11000000,
                'harga_jual' => 13000000,
                'stok' => 10,
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 2,
                'barang_kode' => 'LEN001',
                'barang_nama' => 'Lensa Canon 50mm',
                'harga_beli' => 2000000,
                'harga_jual' => 2500000,
                'stok' => 10,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 2,
                'barang_kode' => 'LEN002',
                'barang_nama' => 'Lensa Nikon 50mm',
                'harga_beli' => 2100000,
                'harga_jual' => 2600000,
                'stok' => 10,
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 3,
                'barang_kode' => 'KBL001',
                'barang_nama' => 'Kabel HDMI 1.5m',
                'harga_beli' => 50000,
                'harga_jual' => 60000,
                'stok' => 10,
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 3,
                'barang_kode' => 'KBL002',
                'barang_nama' => 'Kabel USB 2.0 1.5m',
                'harga_beli' => 30000,
                'harga_jual' => 35000,
                'stok' => 10,
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 4,
                'barang_kode' => 'AKS001',
                'barang_nama' => 'Memory Card 16GB',
                'harga_beli' => 150000,
                'harga_jual' => 180000,
                'stok' => 10,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 4,
                'barang_kode' => 'AKS002',
                'barang_nama' => 'Tripod 1.5m',
                'harga_beli' => 200000,
                'harga_jual' => 250000,
                'stok' => 10,
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 5,
                'barang_kode' => 'PRL001',
                'barang_nama' => 'Tas Kamera',
                'harga_beli' => 100000,
                'harga_jual' => 120000,
                'stok' => 10,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 5,
                'barang_kode' => 'PRL002',
                'barang_nama' => 'Rain Cover Kamera',
                'harga_beli' => 50000,
                'harga_jual' => 60000,
                'stok' => 10,
            ],
        ];

        DB::table('m_barang')->insert($data);
    }
}
