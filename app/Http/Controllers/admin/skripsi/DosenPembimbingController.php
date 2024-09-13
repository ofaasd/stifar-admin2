<?php

namespace App\Http\Controllers\admin\skripsi;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\RefPembimbing;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DosenPembimbingController extends Controller
{
    public function index(){
        
        
        return view('admin.skripsi.dosbim.index');
    }

    public function getListDosen()
    {
        // Mengambil data dari model RefPembimbing dengan sisa kuota yang tidak 0
        $data = RefPembimbing::where('kuota', '!=', 0)->get();

        // Jika data kosong, kirim response dengan pesan khusus
        if ($data->isEmpty()) {
            return response()->json([
                'draw' => 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        return \DataTables::of($data)
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex
            ->addColumn('button', function ($row) {
                return '<button class="btn btn-primary btn-sm edit-btn text-light" data-id="'.$row->nip.'">Edit Kuota</button>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    
    public function getData() {
        $data = Pegawai::get(); // Mengambil data dari model Pegawai
    
        return \DataTables::of($data)
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex
            ->addColumn('button', function ($row) {
                return '<button class="btn btn-warning btn-sm edit-btn text-light" data-id="'.$row->nip.'">Acc</button>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    
    public function accDosen(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|string|max:255',
            'sisa_kuota' => 'required|integer|min:0', // Tambahkan validasi untuk sisa kuota
        ]);
    
        try {
            // Menggunakan updateOrCreate untuk menyederhanakan logika penyimpanan
            RefPembimbing::updateOrCreate(
                ['nip' => $request->input('nip')],
                ['sisa_kuota' => $request->input('sisa_kuota')]
            );
    
            return response()->json(['success' => 'Dosen Pembimbing berhasil ditambahkan/diupdate']);
        } catch (\Exception $e) {
            // Log jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat Menambahkan Dosen Pembimbing.', [
                'nip' => $request->input('nip'),
                'error' => $e->getMessage()
            ]);
    
            return response()->json(['message' => 'Terjadi kesalahan, silakan coba lagi.'], 500);
        }
    }
    
    public function edit($nip)
    {
        // Ambil data dosen pembimbing berdasarkan nip
        $dosen = RefPembimbing::where('nip', $nip)->firstOrFail();
    
        return response()->json([
            'nip' => $dosen->nip,
            'kuota' => $dosen->sisa_kuota
        ]);
    }
    
    public function updateKuota(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|string|max:255',
            'kuota' => 'required|integer|min:0'
        ]);
    
        try {
            // Menggunakan updateOrCreate untuk menyederhanakan logika penyimpanan
            RefPembimbing::updateOrCreate(
                ['nip' => $request->input('nip')],
                ['sisa_kuota' => $request->input('kuota')]
            );
    
            return response()->json(['success' => 'Kuota berhasil diperbarui']);
        } catch (\Exception $e) {
            // Log jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat memperbarui kuota.', [
                'nip' => $request->input('nip'),
                'error' => $e->getMessage()
            ]);
    
            return response()->json(['message' => 'Terjadi kesalahan, silakan coba lagi.'], 500);
        }
    }
    
}
