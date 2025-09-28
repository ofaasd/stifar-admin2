<?php
namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Http\Controllers\Controller;
use App\Models\MasterSkripsi;
use App\Models\PengajuanBerkasSkripsi;
use App\Models\PengajuanJudulSkripsi;
use Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $email = $user->email;
        // $nim   = explode('@', $email)[0];
            $nim   = 'A11.2022.14777';
            // ambil data master
        $dataDosbim = MasterSkripsi::where('nim', $nim)
        ->leftJoin('pegawai_biodata AS pegawai1', 'pegawai1.npp', '=', 'master_skripsi.pembimbing_1')
        ->leftJoin('pegawai_biodata AS pegawai2', 'pegawai2.npp', '=', 'master_skripsi.pembimbing_2')
        ->select(
            'master_skripsi.*',
            'pegawai1.nama_lengkap as nama_pembimbing1',
            'pegawai1.npp as npp_pembimbing1',
            'pegawai2.nama_lengkap as nama_pembimbing2',
            'pegawai2.npp as npp_pembimbing2'
        )
        ->first();
        $dataJudul = PengajuanJudulSkripsi::where('id_master', $dataDosbim->id)
    ->latest() // otomatis pakai created_at desc
    ->take(2)
    ->get();

        
    
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

        return view('mahasiswa.skripsi.pengajuan.index',[
            'dataDosbim' => $dataDosbim,
            'dataJudul' => $dataJudul
        ]);
    }
   
}
