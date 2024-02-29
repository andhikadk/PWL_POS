<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanModel;

class PenjualanController extends Controller
{
    function index()
    {
        $penjualan = PenjualanModel::all();
        return view('penjualan', ['data' => $penjualan]);
    }
}
