<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\Http\Controllers\Controller;
use App\Models\GelombangSidangSkripsi;
use App\Models\SidangSkripsi;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class SidangController extends Controller
{
    public function index()
    {
        $gelombang = GelombangSidangSkripsi::with('tahunAjaran')->get();
        $tahunAjaran = TahunAjaran::all();

        return view('dosen.skripsi.sidang.index', compact('gelombang', 'tahunAjaran'));
    }
    public function store(Request $request)
    {
        dd($request->all());
        $validated = $request->validate([
            'tanggal'       => 'required|date',
            'waktu_mulai'   => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'ruangan'       => 'required|string|max:50',
            'status'        => 'nullable|string'
        ]);
    
        $sidang = SidangSkripsi::create([
            'skripsi_id'    => 5,   // sementara fix, bisa diganti dari input
            'gelombang_id'  => 1,   // sementara fix, bisa diganti dari input
            'tanggal'       => $validated['tanggal'],
            'waktu_mulai'   => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'ruangan'       => $validated['ruangan'],
            'status'        => 2,
        ]);
        // Simpan log
        \Log::create([
            'user_id'    => auth()->id(),  // kalau ada sistem login
            'action'     => 'Tambah Jadwal Sidang',
            'description'=> "User ".auth()->user()->name." menambahkan jadwal sidang ID: {$sidang->id} untuk skripsi ID: {$sidang->skripsi_id} pada tanggal {$sidang->tanggal}."
        ]);
    
        return redirect()->back()->with('success', 'Jadwal sidang berhasil ditambahkan.');
    }
    
    

    public function update(Request $request, GelombangSidangSkripsi $gelombang)
    {
        $request->validate([
            'nama' => 'required|string',
            'id_tahun_ajaran' => 'required|exists:tahun_ajarans,id',
            'kuota' => 'required|kuota',
            'tanggal_mulai_daftar' => 'required|date',
            'tanggal_selesai_daftar' => 'required|date',
            'tanggal_mulai_pelaksanaan' => 'required|date',
            'tanggal_selesai_pelaksanaan' => 'required|date',
        ]);

        $ta = TahunAjaran::findOrFail($request->id_tahun_ajaran);
        $periode = $ta->periode_formatted;

        $gelombang->update([
            'nama' => $request->nama,
            'periode' => $periode,
            'kuota' => $request->kuota,
            'tanggal_mulai_daftar' => $request->tanggal_mulai_daftar,
            'tanggal_selesai_daftar' => $request->tanggal_selesai_daftar,
            'tanggal_mulai_pelaksanaan' => $request->tanggal_mulai_pelaksanaan,
            'tanggal_selesai_pelaksanaan' => $request->tanggal_selesai_pelaksanaan,
            'id_tahun_ajaran' => $request->id_tahun_ajaran
        ]);

        return redirect()->route('sidang.index')->with('success', 'Gelombang berhasil diperbarui');
    }

    public function destroy(GelombangSidangSkripsi $gelombang)
    {
        $gelombang->delete();
        return redirect()->route('sidang.index')->with('success', 'Gelombang berhasil dihapus');
    }
}
