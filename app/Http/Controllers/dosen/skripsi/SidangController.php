<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\Http\Controllers\Controller;
use App\Models\GelombangSidangSkripsi;
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

        GelombangSidangSkripsi::create([
            'nama' => $request->nama,
            'periode' => $periode,
            'kuota' => $request->kuota,
            'tanggal_mulai_daftar' => $request->tanggal_mulai_daftar,
            'tanggal_selesai_daftar' => $request->tanggal_selesai_daftar,
            'tanggal_mulai_pelaksanaan' => $request->tanggal_mulai_pelaksanaan,
            'tanggal_selesai_pelaksanaan' => $request->tanggal_selesai_pelaksanaan,
            'id_tahun_ajaran' => $request->id_tahun_ajaran
        ]);

        return redirect()->route('sidang.index')->with('success', 'Gelombang berhasil ditambahkan');
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
