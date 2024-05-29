<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = PenjualanModel::with('user')->get();

        $penjualan->map(function ($item) {
            $item->total_penjualan = PenjualanDetailModel::where('penjualan_id', $item->penjualan_id)->sum('harga');
            $item->barang = PenjualanDetailModel::where('penjualan_id', $item->penjualan_id)->with('barang')->get();
        });

        return response()->json($penjualan);
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

        $kode_penjualan = !$kode_penjualan_terakhir ? 'JL0001' : 'JL' . sprintf('%05d', substr($kode_penjualan_terakhir->penjualan_kode, 2) + 1);

        DB::transaction(function () use ($request, $kode_penjualan) {
            $penjualan = PenjualanModel::create([
                'user_id' => $request->user_id,
                'penjualan_kode' => $kode_penjualan,
                'penjualan_tanggal' => $request->penjualan_tanggal,
                'pembeli' => $request->pembeli,
            ]);

            for ($i = 0; $i < count($request->barang); $i++) {
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $request->barang[$i],
                    'jumlah' => $request->jumlah[$i],
                    'harga' => $request->harga[$i]
                ]);
                BarangModel::where('barang_id', $request->barang[$i])->decrement('stok', $request->jumlah[$i]);
            }
        });

        return response()->json(['success' => 'Data penjualan berhasil ditambahkan']);
    }

    public function show($id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);
        $penjualan_detail = PenjualanDetailModel::where('penjualan_id', $id)->with(['barang', 'penjualan'])->get();

        $total_penjualan = PenjualanDetailModel::where('penjualan_id', $id)->sum('harga');

        return response()->json([
            'penjualan' => $penjualan,
            'penjualan_detail' => $penjualan_detail,
            'total_penjualan' => $total_penjualan
        ]);
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

        $penjualan_detail = PenjualanDetailModel::where('penjualan_id', $id);

        DB::transaction(function () use ($request, $id, $penjualan_detail) {
            foreach ($penjualan_detail->get() as $detail) {
                BarangModel::where('barang_id', $detail->barang_id)->increment('stok', $detail->jumlah);
                $detail->delete();
            }

            PenjualanModel::find($id)->update([
                'user_id' => $request->user_id,
                'penjualan_tanggal' => $request->penjualan_tanggal,
                'pembeli' => $request->pembeli,
            ]);

            $penjualan_id = $id;

            for ($i = 0; $i < count($request->barang); $i++) {
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan_id,
                    'barang_id' => $request->barang[$i],
                    'jumlah' => $request->jumlah[$i],
                    'harga' => $request->harga[$i]
                ]);
                BarangModel::where('barang_id', $request->barang[$i])->decrement('stok', $request->jumlah[$i]);
            }
        });

        return response()->json(['success' => 'Data penjualan berhasil diubah']);
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
            return response()->json(['error' => 'Data penjualan tidak ditemukan'], 404);
        }

        try {
            PenjualanModel::destroy($id);
            return response()->json(['success' => 'Data penjualan berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data penjualan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'], 400);
        }
    }

    public function editPenjualan($id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);
        $penjualan_detail = PenjualanDetailModel::where('penjualan_id', $id)->with(['barang', 'penjualan'])->get();

        return response()->json(['penjualan' => $penjualan, 'penjualan_detail' => $penjualan_detail]);
    }
}
