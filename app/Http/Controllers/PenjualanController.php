<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\UserModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object)[
            'title' => 'Daftar penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan';

        $user = UserModel::all();

        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $penjualan = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with('user');

        if ($request->user_id) {
            $penjualan->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_barang', function ($penjualan) {
                $total_barang = PenjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)
                    ->sum('jumlah');
                return $total_barang;
            })
            ->addColumn('total_harga', function ($penjualan) {
                $total_harga = PenjualanDetailModel::select('penjualan_id')
                    ->where('penjualan_id', $penjualan->penjualan_id)
                    ->selectRaw('sum(harga * jumlah) as total_harga')
                    ->groupBy('penjualan_id')
                    ->first();
                return $total_harga ? $total_harga->total_harga : 0;
            })
            ->addColumn('action', function ($penjualan) {
                $btn  = '<a href="' . url('/penjualan/' . $penjualan->penjualan_id) . '"class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/penjualan/' . $penjualan->penjualan_id . '/edit') . '"class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url('/penjualan/' . $penjualan->penjualan_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function barang()
    {
        $barang = BarangModel::all();

        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('action', function ($barang) {
                $btn = '<a id="' . $barang->barang_id . '" href="javascript:void(0)"' .
                    ' data-nama="' . $barang->barang_nama . '" data-harga="' . $barang->harga_jual . '"' . '" data-stok="' . $barang->stok . '"' .
                    ' data-barang="' . $barang->barang_id . '" class="btn btn-primary btn-sm">Tambah</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Penjualan',
            'list' => ['Home', 'Penjualan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah penjualan baru'
        ];

        $user = UserModel::all();
        $activeMenu = 'penjualan';

        return view('penjualan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:50',
            'penjualan_tanggal' => 'required|date',
            'barang' => 'required|array',
            'jumlah' => 'required|array',
            'harga' => 'required|array',
        ]);

        $kode_penjualan_terakhir = PenjualanModel::select('penjualan_kode')
            ->orderBy('penjualan_id', 'desc')
            ->first();

        if (!$kode_penjualan_terakhir) {
            $kode_penjualan = 'JL0001';
        } else {
            $kode_penjualan = 'JL' . sprintf('%05d', substr($kode_penjualan_terakhir->penjualan_kode, 2) + 1);
        }

        PenjualanModel::create([
            'user_id' => $request->user_id,
            'penjualan_kode' => $kode_penjualan,
            'penjualan_tanggal' => $request->penjualan_tanggal,
            'pembeli' => $request->pembeli,
        ]);

        $penjualan_id = PenjualanModel::select('penjualan_id')
            ->where('penjualan_kode', $kode_penjualan)
            ->first();

        for ($i = 0; $i < count($request->barang); $i++) {
            PenjualanDetailModel::create([
                'penjualan_id' => $penjualan_id->penjualan_id,
                'barang_id' => $request->barang[$i],
                'jumlah' => $request->jumlah[$i],
                'harga' => $request->harga[$i]
            ]);
            BarangModel::where('barang_id', $request->barang[$i])->decrement('stok', $request->jumlah[$i]);
        }

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil ditambahkan');
    }

    public function show($id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);
        $penjualan_detail = PenjualanDetailModel::where('penjualan_id', $id)->with(['barang', 'penjualan'])->get();

        $total_penjualan = PenjualanDetailModel::where('penjualan_id', $id)->sum('harga');

        $breadcrumb = (object) [
            'title' => 'Detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan' => $penjualan, 'penjualan_detail' => $penjualan_detail, 'total_penjualan' => $total_penjualan, 'activeMenu' => $activeMenu]);
    }

    public function edit($id)
    {
        $penjualan = PenjualanModel::find($id);
        $penjualan_detail = PenjualanDetailModel::where('penjualan_id', $id)->with(['barang', 'penjualan'])->get();

        $breadcrumb = (object) [
            'title' => 'Edit Penjualan',
            'list' => ['Home', 'Penjualan', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit penjualan'
        ];

        $user = UserModel::all();
        $barang = BarangModel::all();

        $activeMenu = 'penjualan';

        return view('penjualan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan' => $penjualan, 'penjualan_detail' => $penjualan_detail, 'user' => $user, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:50',
            'penjualan_tanggal' => 'required|date',
            'barang' => 'required|array',
            'jumlah' => 'required|array',
            'harga' => 'required|array',
        ]);

        $kode_penjualan_terakhir = PenjualanModel::select('penjualan_kode')
            ->orderBy('penjualan_id', 'desc')
            ->first();

        if (!$kode_penjualan_terakhir) {
            $kode_penjualan = 'JL0001';
        } else {
            $kode_penjualan = 'JL' . sprintf('%05d', substr($kode_penjualan_terakhir->penjualan_kode, 2) + 1);
        }

        $penjualan_detail = PenjualanDetailModel::where('penjualan_id', $id);

        foreach ($penjualan_detail->get() as $detail) {
            BarangModel::where('barang_id', $detail->barang_id)->increment('stok', $detail->jumlah);
        }

        PenjualanModel::find($id)->update([
            'user_id' => $request->user_id,
            'penjualan_kode' => $kode_penjualan,
            'penjualan_tanggal' => $request->penjualan_tanggal,
            'pembeli' => $request->pembeli,
        ]);

        $penjualan_id = PenjualanModel::select('penjualan_id')
            ->where('penjualan_kode', $kode_penjualan)
            ->first();

        $penjualan_detail = PenjualanDetailModel::where('penjualan_id', $id);

        foreach ($penjualan_detail->get() as $detail) {
            $detail->delete();
        }

        for ($i = 0; $i < count($request->barang); $i++) {
            PenjualanDetailModel::create([
                'penjualan_id' => $penjualan_id->penjualan_id,
                'barang_id' => $request->barang[$i],
                'jumlah' => $request->jumlah[$i],
                'harga' => $request->harga[$i]
            ]);
            BarangModel::where('barang_id', $request->barang[$i])->decrement('stok', $request->jumlah[$i]);
        }

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil diubah');
    }

    public function destroy($id)
    {
        $penjualan_detail = PenjualanDetailModel::where('penjualan_id', $id);

        foreach ($penjualan_detail->get() as $detail) {
            BarangModel::where('barang_id', $detail->barang_id)->increment('stok', $detail->jumlah);
            $detail->delete();
        }

        $check = PenjualanModel::find($id);

        if (!$check) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        try {
            PenjualanModel::destroy($id);

            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/penjualan')->with('error', 'Data penjualan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function editPenjualan($id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);
        $penjualan_detail = PenjualanDetailModel::where('penjualan_id', $id)->with(['barang', 'penjualan'])->get();

        return response()->json(['penjualan' => $penjualan, 'penjualan_detail' => $penjualan_detail]);
    }
}
