<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\helpers;
use App\Helpers\HelperSkripsi\SkripsiHelper;
use App\Http\Controllers\Controller;
use App\Models\MasterSkripsi;
use App\Models\PengajuanJudulSkripsi;
use Illuminate\Http\Request;

class PengajuanSkripsiController extends Controller
{
    public function index(){
       return view('mahasiswa.skripsi.pengajuan.skripsi.index');
    }

    public function store(Request $request)
{
    $request->validate([
        'judul'             => 'required|string|max:200',
        'judulEng'             => 'required|string|max:200',
        'abstrak'           => 'required|string|max:1000',
        'latar_belakang'    => 'required|string|max:2000',
        'rumusan_masalah'   => 'required|string|max:1500',
        'tujuan'            => 'required|string|max:1500',
        'metodologi'        => 'required|string|max:2000',
        'jenis_penelitian'  => 'required|in:kualitatif,kuantitatif,mixed,eksperimen,studi_kasus',
    ]);

    try {
        $idMaster = SkripsiHelper::getIdMasterSkripsi();
        $pengajuan = PengajuanJudulSkripsi::create([
            'id_master'         => $idMaster,
            'judul'             => $request->judul,
            'judul_eng'             => $request->judulEng,
            'abstrak'           => $request->abstrak,
            'latar_belakang'    => $request->latar_belakang,
            'rumusan_masalah'   => $request->rumusan_masalah,
            'tujuan'            => $request->tujuan,
            'metodologi'        => $request->metodologi,
            'jenis_penelitian'  => $request->jenis_penelitian,
            'status'  => 0,
        ]);

        return redirect()
            ->route('mhs.pengajuan.index')
            ->with('success', 'Pengajuan judul skripsi berhasil disimpan.');
    } catch (\Exception $e) {
        \Log::error('Gagal menyimpan pengajuan judul', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
    }
}

}
