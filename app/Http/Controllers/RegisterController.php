<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:users',
            'name' => 'required',
            'kategori' => 'required',
            'no_telp' => 'between:12,15',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ], [
            'nip.required' => 'NIP tidak boleh kosong',
            'nip.unique' => 'NIP sudah terdaftar',

            'name.required' => 'Nama tidak boleh kosong',
            'kategori.required' => 'Kategori tidak boleh kosong',
            'no_telp.between' => 'No. Telp harus lebih dari 12 digit',
            
            'username.required' => 'Username tidak boleh kosong',
            'username.unique' => 'Username sudah digunakan',

            'email.required' => 'Email tidak boleh kosong',
            'email.unique' => 'Email sudah digunakan',

            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password harus lebih dari 8 Karakter',
            'password.confirmed' => 'Konfirmasi Password tidak sesuai',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'nip' => $request->nip,
            'name' => $request->name,
            'kategori' => $request->kategori,
            'alamat' => $request->alamat,
            'jns_kelamin' => $request->jns_kelamin,
            'no_telp' => $request->no_telp,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Register Berhasil',
            'data' => $user
        ]);
    }
}
