<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use Illuminate\Http\Request;

class StokController extends Controller
{
    function index()
    {
        $stok = StokModel::all();
        return view('stok', ['data' => $stok]);
    }
}
