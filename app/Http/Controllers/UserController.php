<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = User::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }
    
    public function show(User $user){
        // $user = user::findOrfail($id);

        return response()->json([
            'data' => $user
        ], 200);
    }

    public function update(Request $request, user $user){
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'name' => 'required',
            'kategori' => 'required',
            'alamat' => 'required',
            'jns_kelamin' => 'required',
            'no_telp' => 'between:12,15',
            'username' => 'required',
        ], [
            'nip.required' => 'NIP tidak boleh kosong',

            'name.required' => 'Nama tidak boleh kosong',
            'kategori.required' => 'Kategori tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'jns_kelamin.required' => 'Silahkan pilih jenis kelamin',
            'no_telp.between' => 'No. Telp harus lebih dari 12 digit',
            
            'username.required' => 'Username tidak boleh kosong',
            'username.unique' => 'Username sudah digunakan',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = user::findOrFail($user->id);
        if ($user) {
            $user->update([
                'nip' => $request->nip,
                'name' => $request->name,
                'kategori' => $request->kategori,
                'alamat' => $request->alamat,
                'jns_kelamin' => $request->jns_kelamin,
                'no_telp' => $request->no_telp,
                'username' => $request->username
            ]);
            return response()->json([
                'success' => true,
                'message' => 'user Updated',
                'data' => $user
            ], 200);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }
}
