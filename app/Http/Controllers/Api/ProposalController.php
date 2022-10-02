<?php

namespace App\Http\Controllers\Api;

use App\Models\Proposal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    public function index()
    {
        $proposals = Proposal::latest()->get();
        
        return response()->json([
            'success' => true,
            'message' => 'List Data Proposal',
            'data' => $proposals
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|unique:proposals',
            'dana' => 'required',
            'fl_proposal' => 'required|mimes:pdf|max:10240',
            // 'fl_proposal_external' => 'required|mimes:pdf|max:10240',
            'status' => 'required',
            'nip' => 'required',
        ], [
            'judul.required' => 'Judul tidak boleh kosong',
            'judul.unique' => 'Judul sudah digunakan',

            'dana.required' => 'Dana tidak boleh kosong',

            'fl_proposal.required' => 'File proposal tidak boleh kosong',
            'fl_proposal.mimes' => 'Eksensi file proposal harus .pdf',
            'fl_proposal.max' => 'File proposal tidak boleh lebih dari 10 MB',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fl_proposal = $request->file('fl_proposal');
        $fileName = $fl_proposal->getClientOriginalName();
        $fl_proposal->storeAs('public/proposals', $fileName);

        // $fl_proposal_external = $request->file('fl_proposal_external');
        // $fileName2 = $fl_proposal_external->getClientOriginalName();
        // $fl_proposal_external->storeAs('public/proposals/external', $fileName2);

        $proposal = Proposal::create([
            'no_surat' => $request->no_surat,
            'judul' => $request->judul,
            'dana' => $request->dana,
            'fl_proposal' => $fileName,
            // 'fl_proposal_external' => $fileName2,
            'status' => $request->status,
            'nip' => $request->nip
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tambah Data Proposal',
            'data' => $proposal
        ], 201);
    }

    public function show(Proposal $proposal)
    {   
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Proposal',
            'data' => $proposal
        ], 200);
    }

    public function proposalbynip($nip)
    {
        $prop = Proposal::where('nip', $nip)->get();
        
        return response()->json([
            'data' => $prop
        ], 200);
    }

    
    
    public function update(Request $request, Proposal $proposal, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'dana' => 'required',
            'fl_proposal' => 'max:10240',
            // 'fl_proposal_external' => 'mimes:pdf|max:10240',
            'status' => 'required',
            'nip' => 'required',
        ], [
            'judul.required' => 'Judul tidak boleh kosong',
            'dana.required' => 'Dana tidak boleh kosong',
            'fl_proposal.max' => 'Ukuran file maksimal 10MB'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $proposal = Proposal::findOrFail($id);

        if ($proposal) {
            if ($request->hasFile('fl_proposal')) {
                $fl_proposal = $request->file('fl_proposal');
                $fileName = $fl_proposal->getClientOriginalName();
                $fl_proposal->storeAs('public/proposals', $fileName);

                // $fl_proposal_external = $request->file('fl_proposal_external');
                // $fileName2 = $fl_proposal_external->getClientOriginalName();
                // $fl_proposal_external->storeAs('public/proposals/external', $fileName2);

                Storage::delete('public/proposals/'.$proposal->fl_proposal);
                // Storage::delete('public/proposals/external/'.$proposal->fl_proposal_external);

                $proposal->update([
                    'judul' => $request->judul,
                    'dana' => $request->dana,
                    'fl_proposal' => $fileName,
                    // 'fl_proposal_external' => $fileName2,
                    'status' => $request->status,
                    'nip' => $request->nip
                ]);
            } else {
                $proposal->update([
                    'judul' => $request->judul,
                    'dana' => $request->dana,
                    'status' => $request->status,
                    'nip' => $request->nip
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Edit Data',
            'data' => $proposal
        ], 200);
    }

    public function destroy(Proposal $proposal)
    {
        Storage::delete('public/proposals/'.$proposal->fl_proposal);
        // Storage::delete('public/luarans/'.$proposal->fl_proposal_external);

        $proposal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hapus Data Proposal',
            'data' => $proposal
        ], 200);
    }

    public function siapreview(Proposal $proposal)
    {
        $status = "Proses Review";
        
        $proposal = Proposal::where('status', $status)->get();

        return response()->json([
            'data' => $proposal
        ], 200);
    }

    public function seluruh(Proposal $proposal)
    {
        // $status = "Diterima";
        // $status2 = "Ditolak";
        // $status3 = "Selesai";

        // $proposal = Proposal::where('status', '!=', $status, 'AND', 'status', '!=', $status2, 'AND', 'status', '!=', $status3)->get();

        $status = "Sedang diproses";
        $status1 = "Proses Review";

        $proposal = Proposal::where('status', '=', $status, 'OR', 'status', '=', $status1)->get();

        return response()->json([
            'data' => $proposal
        ], 200);
    }

    public function acc(Proposal $proposal)
    {
        $status = "Diterima";

        $proposal = Proposal::where('status', $status)->get();

        return response()->json([
            'data' => $proposal
        ]);
    }

    public function dcc(Proposal $proposal)
    {
        $status = "Ditolak";

        $proposal = Proposal::where('status', $status)->get();

        return response()->json([
            'data' => $proposal
        ]);
    }

    public function selesai(Proposal $proposal)
    {
        $status = "Selesai";

        $proposal = Proposal::where('status', $status)->get();

        return response()->json([
            'data' => $proposal
        ]);
    }

    public function diterima(Proposal $proposal, $nip)
    {
        $status = "Diterima";

        $proposal = Proposal::where('nip', $nip)->where('status', $status)->get();
        // $date = date_format($proposal->created_at, 'Y-m-d');

        return response()->json([
            'data' => $proposal, 
            // 'date' => $date
        ], 200);
    }
    
    public function updatestatus(Request $request, Proposal $proposal, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $proposal = Proposal::findOrFail($id);
        
        if ($proposal) {
            $proposal->update([
                'status' => $request->status
            ]);    
        }

        return response()->json([
            'data' => $proposal
        ], 200);
    }

    public function catatan_revisi(Request $request, Proposal $proposal, $id)
    {
        $validator = Validator::make($request->all(), [
            'catatan_revisi' => 'required',
        ], [
            'catatan_revisi.required' => 'Catatan revisi untuk dosen wajib diisi'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $proposal = Proposal::findOrFail($id);
        
        if ($proposal) {
            $proposal->update([
                'catatan_revisi' => $request->catatan_revisi
            ]);
        }

        return response()->json([
            'data' => $proposal
        ], 200);
    }
}
