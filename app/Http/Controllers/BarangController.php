<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    function index()
    {
        $barang = BarangModel::all();
        return view('barang', ['data' => $barang]);
    }
}
