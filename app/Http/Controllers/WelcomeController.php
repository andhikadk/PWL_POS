<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\BarangModel;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        $total_barang = BarangModel::count();
        $total_transaksi = PenjualanDetailModel::selectRaw('sum(harga * jumlah) as total_transaksi')->first();
        $jumlah_transaksi = PenjualanModel::count();
        $total_aset = StokModel::join('m_barang', 'm_barang.barang_id', '=', 't_stok.barang_id')
            ->selectRaw('sum(t_stok.stok_jumlah * m_barang.harga_beli) as total_aset')
            ->first();

        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'total_barang' => $total_barang,
            'total_transaksi' => $total_transaksi->total_transaksi,
            'jumlah_transaksi' => $jumlah_transaksi,
            'total_aset' => $total_aset->total_aset
        ]);
    }
}
