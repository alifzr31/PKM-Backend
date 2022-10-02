<?php

namespace App\Http\Controllers\Api;

use App\Models\Luaran;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class LuaranController extends Controller
{
    public function luaranbynip($nip)
    {
        $luaran = Luaran::where('nip', $nip)->get();

        return response()->json([
            'data' => $luaran
        ]);
    }

    public function index()
    {
        $luaran = Luaran::latest()->get();

        return response()->json([
            'data' => $luaran
        ], 200);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|unique:luarans',
            'publikasi' => 'required|url|unique:luarans',
            'fl_luaran' => 'required|mimes:pdf|max:10240',
            'artikel' => 'required|url|unique:luarans',
            'nip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fl_luaran = $request->file('fl_luaran');
        $fileName = $fl_luaran->getClientOriginalName();
        $fl_luaran->storeAs('public/luarans', $fileName);

        $luaran = Luaran::create([
            'judul' => $request->judul,
            'publikasi' => $request->publikasi,
            'fl_luaran' => $fileName,
            'artikel' => $request->artikel,
            'status' => $request->status,
            'nip' => $request->nip,
        ]);

        return response()->json([
            'data' => $luaran
        ], 200);
    }

    public function show(Luaran $luaran)
    {
        return response()->json([
            'data' => $luaran
        ], 200);
    }
    
    public function update(Request $request, Luaran $luaran, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'publikasi' => 'required|url',
            'fl_luaran' => 'max:10240',
            'artikel' => 'required|url',
            'nip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $luaran = Luaran::findOrFail($id);

        if ($luaran) {
            if ($request->hasFile('fl_luaran')) {
                $fl_luaran = $request->file('fl_luaran');
                $fileName = $fl_luaran->getClientOriginalName();
                $fl_luaran->storeAs('public/luarans', $fileName);
    
                Storage::delete('public/luarans/'.$luaran->fl_luaran);

                $luaran->update([
                    'judul' => $request->judul,
                    'publikasi' => $request->publikasi,
                    'fl_luaran' => $fileName,
                    'artikel' => $request->artikel,
                    'nip' => $request->nip
                ]);
            } else {
                $luaran->update([
                    'judul' => $request->judul,
                    'publikasi' => $request->publikasi,
                    'artikel' => $request->artikel,
                    'nip' => $request->nip
                ]);
            }
        }

        return response()->json([
            'data' => $luaran
        ], 200);
    }

    public function destroy(Luaran $luaran)
    {
        Storage::delete('public/luarans/'.$luaran->fl_luaran);

        $luaran->delete();

        return response()->json([
            'data' => $luaran
        ]);
    }

    public function editstatusluaran(Request $request, Luaran $luaran, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $luaran = Luaran::findOrFail($id);

        if ($luaran) {
            $luaran->update([
                'status' => $request->status
            ]);
        }

        return response()->json([
            'data' => $luaran
        ]);
    }
}
