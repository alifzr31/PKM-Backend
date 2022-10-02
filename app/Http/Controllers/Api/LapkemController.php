<?php

namespace App\Http\Controllers\Api;

use App\Models\LapKemajuan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LapkemController extends Controller
{
    public function index()
    {
        $lapkems = LapKemajuan::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Laporan Kemajuan',
            'data' => $lapkems
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'fl_lapkem' => 'required|mimes:pdf|max:10240',
            'catatan' => 'required',
            'nip' => 'required',
        ], [
            'judul.required' => 'Judul tidak boleh kosong',
            'fl_lapkem.required' => 'File tidak boleh kosong',
            'fl_lapkem.mimes' => 'File harus berupa .pdf',
            'fl_lapkem.max' => 'Ukuran Maksimal File 10MB',
            'catatan.required' => 'Catatan tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fl_lapkem = $request->file('fl_lapkem');
        $fileName = $fl_lapkem->getClientOriginalName();
        $fl_lapkem->storeAs('public/LapKemajuans', $fileName);

        $lapkem = LapKemajuan::create([
            'judul' => $request->judul,
            'fl_lapkem' => $fileName,
            'catatan' => $request->catatan,
            'status' => $request->status,
            'nip' => $request->nip
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tambah Data Laporan Kemajuan',
            'data' => $lapkem
        ], 201);
    }

    public function show(LapKemajuan $lapkem)
    {
        return response()->json([
            'data' => $lapkem
        ], 200);
    }

    public function lapkembynip($nip)
    {
        $lapkem = LapKemajuan::where('nip', $nip)->get();

        return response()->json([
            'data' => $lapkem
        ], 200);
    }
    
    public function update(Request $request, LapKemajuan $lapkem, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'fl_lapkem' => 'max:10240',
            'catatan' => 'required',
            'nip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lapkem = LapKemajuan::findOrFail($id);

        if ($lapkem) {
            if ($request->hasFile('fl_lapkem')) {
                $fl_lapkem = $request->file('fl_lapkem');
                $fileName = $fl_lapkem->getClientOriginalName();
                $fl_lapkem->storeAs('public/LapKemajuans', $fileName);
    
                Storage::delete('public/LapKemajuans/'.$lapkem->fl_lapkem);
    
                $lapkem->update([
                    'judul' => $request->judul,
                    'fl_lapkem' => $fileName,
                    'catatan' => $request->catatan,
                    'nip' => $request->nip
                ]);
            } else {
                $lapkem->update([
                    'judul' => $request->judul,
                    'catatan' => $request->catatan,
                    'nip' => $request->nip
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Edit Data Laporan Kemajuan',
            'data' => $lapkem
        ], 200);
    }

    public function destroy(LapKemajuan $lapkem)
    {
        Storage::delete('public/LapKemajuans/'.$lapkem->fl_lapkem);

        $lapkem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hapus Data Laporan Kemajuan',
            'data' => $lapkem
        ], 200);
    }

    public function editstatuslapkem(Request $request, LapKemajuan $lapkem, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lapkem = LapKemajuan::findOrFail($id);

        if ($lapkem) {
            $lapkem->update([
                'status' => $request->status
            ]);
        }

        return response()->json([
            'data' => $lapkem
        ]);
    }
}
