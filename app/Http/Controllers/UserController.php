<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('user.index');
    }

    public function create()
    {
        $level = LevelModel::all();
        return view('user.create', compact('level'));
    }

    public function store(Request $request)
    {
        UserModel::create([
            'level_id' => $request->level_id,
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
        ]);
        return redirect('/user');
    }

    public function show($id)
    {
        $user = UserModel::find($id);
        return view('user.show', compact('user'));
    }

    public function edit($id)
    {
        $user = UserModel::find($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = UserModel::find($id);
        $user->level_id = $request->level_id;
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect('/user');
    }

    public function destroy($id)
    {
        UserModel::destroy($id);
        return redirect('/user');
    }
}
