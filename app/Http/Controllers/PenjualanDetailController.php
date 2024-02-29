<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanDetailModel;

class PenjualanDetailController extends Controller
{
    function index()
    {
        $penjualan_detail = PenjualanDetailModel::all();
        return view('penjualan_detail', ['data' => $penjualan_detail]);
    }
}
