<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use function GuzzleHttp\Promise\all;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Review',
            'data' => $reviews
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'dana' => 'required',
            'fl_proposal' => 'required|mimes:pdf|max:10240',
            'catatan_uppm' => 'required',
            'status' => 'required',
            'nip' => 'required',
        ], [
            'fl_proposal.required' => 'File tidak boleh kosong', 
            'fl_proposal.mimes' => 'File harus berupa .pdf',
            'fl_proposal.max' => 'Ukuran file maksimal 10MB'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fl_proposal = $request->file('fl_proposal');
        $fileName = $fl_proposal->getClientOriginalName();
        $fl_proposal->storeAs('public/reviews', $fileName);

        $review = Review::create([
            'judul' => $request->judul,
            'dana' => $request->dana,
            'fl_proposal' => $fileName,
            'catatan_uppm' => $request->catatan_uppm,
            'status' => $request->status,
            'nip' => $request->nip
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tambah Data Review',
            'data' => $review
        ], 200);
    }

    public function show(Review $review)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Review',
            'data' => $review
        ], 200);
    }

    public function reviewbynip($nip)
    {
        $review = Review::where('nip', $nip)->get();

        return response()->json([
            'data' => $review
        ], 200);
    }
    
    public function update(Request $request, Review $review, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'dana' => 'required',
            'fl_proposal' => 'mimes:pdf|max:10240',
            'catatan_uppm' => 'required',
            'nip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $review = Review::findOrFail($id);

        if ($review) {
            if ($request->hasFile('fl_proposal')) {
                $fl_proposal = $request->file('fl_proposal');
                $fileName = $fl_proposal->getClientOriginalName();
                
                $review->update([
                    'judul' => $request->judul,
                    'dana' => $request->dana,
                    'fl_proposal' => $fileName,
                    'catatan_uppm' => $request->catatan_uppm,
                    'nip' => $request->nip
                ]);
            } else {
                $review->update([
                    'judul' => $request->judul,
                    'dana' => $request->dana,
                    'catatan_uppm' => $request->catatan_uppm,
                    'nip' => $request->nip
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Edit Data Review',
            'data' => $review
        ], 200);
    }

    public function destroy(Review $review)
    {
        Storage::delete('public/reviews/'.$review->fl_hasilreview);

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hapus Data Review',
            'data' => $review
        ], 200);
    }
    
    public function belumdireview(Review $review)
    {
        $status = "Belum";

        $review = Review::where('status', $status)->get();

        return response()->json([
            'data' => $review
        ]);
    }
    
    public function editstatusreview(Request $request, Review $review, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $review = Review::findOrFail($id);

        if ($review) {
            $review->update([
                'status' => $request->status
            ]);
        }

        return response()->json([
            'data' => $review
        ]);
    }
}
