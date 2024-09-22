<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use Illuminate\Http\Request;
use App\Models\MasterBimbingan;
use App\Models\LogbookBimbingan;
use App\Models\MasterPembimbing;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class BimbinganController extends Controller
{
    public function index(){
        $user = Auth::user();
        $mhs = Mahasiswa::where('id',$user->id)->select('nim')->first();
        $nim = $mhs->nim;
        $data = MasterPembimbing::where('nim', $nim)->select('id')->first();
// dd($data);
    return view('mahasiswa.skripsi.bimbingan.index', compact('data')); // Gunakan 'data' sebagai string
    }

    public function UploadBimbingan(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_pembimbing' => 'required|integer',
            'judul' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'kategori' => 'nullable|string',
            'file' => 'nullable|mimes:pdf,doc,docx|max:2048', // validasi file PDF/Word, max 2MB
        ]);
    
        // Simpan data ke dalam tabel MasterBimbingan
        $bimbingan = MasterBimbingan::create([
            'id_pembimbing' => $request->id_pembimbing,
            'judul' => $request->judul,
            'nama_file' => $request->file ? $request->file('file')->getClientOriginalName() : null,
            'kategori' => $request->kategori,
            'status' => 0,
        ]);
    
        // Simpan data ke dalam tabel LogbookBimbingan
        LogbookBimbingan::create([
            'id_bimbingan' => $bimbingan->id,
            'keterangan' => $request->keterangan ?$request->keterangan : null,
            'nama_file' => $request->file ? $request->file('file')->getClientOriginalName() : null,
            'status' => 0,
            'tgl_pengajuan' => now(),
            'komentar' => null,
        ]);
    
        return response()->json(['message' => 'Bimbingan berhasil diupload']);
    }
    
}
