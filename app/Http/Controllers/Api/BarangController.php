<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangModel;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        return BarangModel::all();
    }

    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $request->image->store('public/barang');
        }
        $barang = BarangModel::create(
            array_merge(
                $request->all(),
                ['image' => $request->image->hashName()]
            )
        );
        return response()->json($barang, 201);
    }

    public function show(BarangModel $barang)
    {
        return BarangModel::find($barang->barang_id);
    }

    public function update(Request $request, BarangModel $barang)
    {
        $barang->update($request->all());
        return BarangModel::find($barang->barang_id);
    }

    public function destroy(BarangModel $barang)
    {
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data terhapus'
        ]);
    }
}
