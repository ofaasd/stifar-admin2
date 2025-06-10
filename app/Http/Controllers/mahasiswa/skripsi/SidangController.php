<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Http\Controllers\Controller;
use App\Models\GelombangSidangSkripsi;
use App\Models\SidangSkripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SidangController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $email = $user->email;

        $nim = explode('@', $email)[0];

        // Ambil data sidang berdasarkan NIM mahasiswa
        $sidang = SidangSkripsi::with(['skripsi', 'gelombang', 'penguji.dosen'])
                    ->whereHas('skripsi', function($query) use ($nim) {
                        $query->where('nim', $nim);
                    })->latest()->first();

        // Ambil semua gelombang sidang
        $gelombang = GelombangSidangSkripsi::with('tahunAjaran')->orderBy('tanggal_mulai_daftar')->get();

        return view('mahasiswa.skripsi.sidang.index', compact('sidang', 'gelombang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gelombang_id' => 'required|exists:gelombang_sidang_skripsi,id',
        ]);
    
        $user = Auth::user();
        $email = $user->email;

        $nim = explode('@', $email)[0];
        // Pastikan mahasiswa memiliki skripsi
        $skripsi = \App\Models\Skripsi::where('nim', $nim)->firstOrFail();
    
    
        if (!$skripsi) {
            return back()->with('error', 'Anda belum memiliki skripsi.');
        }
    
        // Cek apakah sudah daftar sidang
        $existingSidang = SidangSkripsi::where('skripsi_id', $skripsi->id)->first();
        if ($existingSidang) {
            return back()->with('error', 'Anda sudah mendaftar sidang.');
        }
    
        // Ambil gelombang
        $gelombang = GelombangSidangSkripsi::findOrFail($request->gelombang_id);
        $now = now();
    
        // Cek apakah masih dalam masa pendaftaran
        // if (!($now->between($gelombang->tanggal_mulai_daftar, $gelombang->tanggal_selesai_daftar))) {
        //     return back()->with('error', 'Pendaftaran untuk gelombang ini sudah ditutup.');
        // }
    
        // Cek kuota
        $jumlahPendaftar = SidangSkripsi::where('gelombang_id', $gelombang->id)->count();
        if ($jumlahPendaftar >= $gelombang->kuota) {
            return back()->with('error', 'Kuota untuk gelombang ini sudah penuh.');
        }
    
        // Simpan pendaftaran sidang
        SidangSkripsi::create([
            'skripsi_id' => $skripsi->id,
            'gelombang_id' => $gelombang->id,
            'status' => 0,
            'tanggal' => null,
            'waktu_mulai' => null,
            'waktu_selesai' => null,
            'ruangan' => null,
        ]);
    
        return back()->with('success', 'Berhasil mendaftar sidang.');
    }
}
