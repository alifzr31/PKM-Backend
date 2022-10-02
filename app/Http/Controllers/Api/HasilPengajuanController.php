<?php

namespace App\Http\Controllers\Api;

use App\Models\HasilPengajuan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HasilPengajuanController extends Controller
{
    public function index()
    {
        $hasilpengajuan = HasilPengajuan::latest()->get();
        
        return response()->json([
            'data' => $hasilpengajuan
        ]);
    }

    public function show(HasilPengajuan $hasilpengajuan)
    {
        return response()->json([
            'data' => $hasilpengajuan
        ]);
    }

    public function hasilpengajuanbystatus(HasilPengajuan $hasilpengajuan)
    {
        $status = "Belum";
        
        $hasilpengajuan = HasilPengajuan::where('status', $status)->get();

        return response()->json([
            'success' => true,
            'data' => $hasilpengajuan
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'dana' => 'required',
            'fl_proposal' => 'required|mimes:pdf|max:10240',
            'catatan' => 'required',
            'status' => 'required',
            'nip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fl_proposal = $request->file('fl_proposal');
        $fileName = $fl_proposal->getClientOriginalName();
        $fl_proposal->storeAs('public/hasil_pengajuan', $fileName);

        $hasilpengajuan = HasilPengajuan::create([
            'judul' => $request->judul,
            'dana' => $request->dana,
            'fl_proposal' => $fileName,
            'catatan' => $request->catatan,
            'status' => $request->status,
            'nip' => $request->nip
        ]);

        return response()->json([
            'data' => $hasilpengajuan
        ]);
    }

    public function editstatushasilpengajuan(Request $request, HasilPengajuan $hasilpengajuan, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $hasilpengajuan = HasilPengajuan::findOrFail($id);

        if ($hasilpengajuan) {
            $hasilpengajuan->update([
                'status' => $request->status
            ]);
        }

        return response()->json([
            'data' => $hasilpengajuan
        ]);
    }
}
