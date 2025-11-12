<?php

namespace App\Http\Controllers\admin\skripsi;

use App\Http\Controllers\Controller;
use App\Models\MasterSkripsi;
use App\Models\PengajuanBerkasSkripsi;
use App\Models\PengajuanJudulSkripsi;
use Auth;
use Illuminate\Http\Request;

class PengajuanMahasiswaController extends Controller
{
    /**
    * menampilkan data pengajuan mahasiswa (sidang dan skripsi).
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function index()
    {
        // ambil data master
        $dataDosbim = MasterSkripsi::leftJoin('pegawai_biodata AS pegawai1', 'pegawai1.npp', '=', 'master_skripsi.pembimbing_1')
        ->leftJoin('pegawai_biodata AS pegawai2', 'pegawai2.npp', '=', 'master_skripsi.pembimbing_2')
        ->select(
            'master_skripsi.*',
            'pegawai1.nama_lengkap as nama_pembimbing1',
            'pegawai1.npp as npp_pembimbing1',
            'pegawai2.nama_lengkap as nama_pembimbing2',
            'pegawai2.npp as npp_pembimbing2'
        )
        ->get();

        $dataJudul = PengajuanJudulSkripsi::where('id_master', $dataDosbim->id)->get();
    
    if ($dataDosbim) {
        // ambil semua berkas terkait
        $berkas = PengajuanBerkasSkripsi::where('id_master', $dataDosbim->id)->get();
    
        // masukkan ke array dalam 1 row
        $dataDosbim->berkas = $berkas->mapWithKeys(function ($b) {
            return [
                strtolower($b->kategori) => $b->nama_file
            ];
        });
    }

        return view('admin.skripsi.pengajuan.index',[
            'dataDosbim' => $dataDosbim,
            'dataJudul' => $dataJudul
        ]);
    }
}
