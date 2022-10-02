<?php

namespace App\Http\Controllers\Api;

use App\Models\LapAkhir;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class LapAkhirController extends Controller
{
    public function index()
    {
        $lapakhirs = LapAkhir::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Laporan Akhir',
            'data' => $lapakhirs
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'fl_lapakhir' => 'required|mimes:pdf|max:10240',
            'catatan' => 'required',
            'nip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fl_lapakhir = $request->file('fl_lapakhir');
        $fileName = $fl_lapakhir->getClientOriginalName();
        $fl_lapakhir->storeAs('public/LapAkhirs', $fileName);

        $lapakhir = LapAkhir::create([
            'judul' => $request->judul,
            'fl_lapakhir' => $fileName,
            'catatan' => $request->catatan,
            'status' => $request->status,
            'nip' => $request->nip
        ]);

        if ($lapakhir) {
            return response()->json([
                'success' => true,
                'message' => 'Tambah Data Laporan Akhir',
                'data' => $lapakhir
            ], 201);
        }
    }

    public function show(LapAkhir $lapakhir)
    {
        $tes = date_create($lapakhir->created_at);
        $show = date_format($tes, 'd F Y');
        
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Laporan Akhir',
            'data' => $lapakhir
        ], 200);
    }

    public function lapakhirbynip($nip)
    {
        $lapakhir = LapAkhir::where('nip', $nip)->get();

        return response()->json([
            'data' => $lapakhir
        ], 200);
    }
    
    public function update(Request $request, LapAkhir $lapakhir, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'fl_lapakhir' => 'max:10240',
            'catatan' => 'required',
            'nip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lapakhir = LapAkhir::findOrFail($id);

        if ($lapakhir) {
            if ($request->hasFile('fl_lapakhir')) {
                $fl_lapakhir = $request->file('fl_lapakhir');
                $fileName = $fl_lapakhir->getClientOriginalName();
                $fl_lapakhir->storeAs('public/LapAkhirs', $fileName);
    
                Storage::delete('public/LapAkhirs/'.$lapakhir->fl_lapakhir);
    
                $lapakhir->update([
                    'judul' => $request->judul,
                    'fl_lapakhir' => $fileName,
                    'catatan' => $request->catatan,
                    'nip' => $request->nip
                ]);
            } else {
                $lapakhir->update([
                    'judul' => $request->judul,
                    'catatan' => $request->catatan,
                    'nip' => $request->nip
                ]);
            }
        }
        

        return response()->json([
            'success' => true,
            'message' => 'Edit Data Laporan Akhir',
            'data' => $lapakhir
        ], 200);
    }

    public function destroy(LapAkhir $lapakhir)
    {
        Storage::delete('public/LapAkhirs/'.$lapakhir->fl_lapakhir);

        $lapakhir->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hapus Data Laporan Akhir',
            'data' => $lapakhir
        ], 200);
    }

    public function editstatuslapakhir(Request $request, LapAkhir $lapakhir, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lapakhir = LapAkhir::findOrFail($id);

        if ($lapakhir) {
            $lapakhir->update([
                'status' => $request->status
            ]);
        }

        return response()->json([
            'data' => $lapakhir
        ]);
    }
}
