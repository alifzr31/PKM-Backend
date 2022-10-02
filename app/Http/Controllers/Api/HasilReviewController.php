<?php

namespace App\Http\Controllers\Api;

use App\Models\HasilReview;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HasilReviewController extends Controller
{
    public function index()
    {
        $hasil = HasilReview::latest()->get();

        return response()->json([
            'data' => $hasil
        ]);
    }

    public function show(HasilReview $hasil)
    {
        return response()->json([
            'data' => $hasil
        ]);
    }
    
    public function hasilbynip($nip)
    {
        $hasil = HasilReview::where('nip', $nip)->get();

        return response()->json([
            'data' => $hasil
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'dana' => 'required',
            'fl_hasilreview' => 'required|mimes:pdf|max:10240',
            'catatan' => 'required',
            'status' => 'required',
            'nip' => 'required',
        ], [
            'fl_hasilreview.required' => 'File tidak boleh kosong',
            'fl_hasilreview.mimes' => 'File harus berupa .pdf',
            'fl_hasilreview.max' => 'Ukuran file maksimal 10MB',

            'catatan.required' => 'Catatan tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fl_hasilreview = $request->file('fl_hasilreview');
        $fileName = $fl_hasilreview->getClientOriginalName();
        $fl_hasilreview->storeAs('public/reviews/hasil', $fileName);

        $hasil = HasilReview::create([
            'judul' => $request->judul,
            'dana' => $request->dana,
            'fl_hasilreview' => $fileName,
            'catatan' => $request->catatan,
            'status' => $request->status,
            'nip' => $request->nip
        ]);

        return response()->json([
            'data' => $hasil
        ], 201);
    }

    public function update(Request $request, HasilReview $hasil, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'dana' => 'required',
            'fl_hasilreview' => 'mimes:pdf|max:10240',
            'catatan' => 'required',
            'nip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $hasil = HasilReview::findOrFail($id);
        
        if ($hasil) {
            if ($request->hasFile('fl_hasilreview')) {
                $fl_hasilreview = $request->file('fl_hasilreview');
                $fl_hasilreview->storeAs('public/reviews/hasil', $fl_hasilreview->hashName());
    
                Storage::delete('public/reviews/'.$hasil->fl_hasilreview);
                
                $hasil->update([
                    'judul' => $request->judul,
                    'dana' => $request->dana,
                    'fl_hasilreview' => $fl_hasilreview->hashName(),
                    'catatan' => $request->catatan,
                    'nip' => $request->nip
                ]);
            } else {
                $hasil->update([
                    'no_surat' => $request->no_surat,
                    'judul' => $request->judul,
                    'dana' => $request->dana,
                    'catatan' => $request->catatan,
                    'nip' => $request->nip
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Edit Data Review',
            'data' => $hasil
        ], 200);
    }

    public function editstatushasilreview(Request $request, HasilReview $hasil, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $hasil = HasilReview::findOrFail($id);

        if ($hasil) {
            $hasil->update([
                'status' => $request->status
            ]);
        }

        return response()->json([
            'data' => $hasil
        ]);
    }

    public function hasilterimadantolak(HasilReview $hasil)
    {
        $status = "Terima";
        $status1 = "Tolak";

        $hasil = HasilReview::where('status', '=', $status, 'OR', 'status', '=', $status1)->get();

        return response()->json([
            'data' => $hasil
        ]);
    }
}
