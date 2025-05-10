<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\master_nilai;
use Illuminate\Support\Facades\Auth;

class DaftarNilaiController extends Controller
{
    //
    public function index(int $idmhs=0){
        $kualitas = [
            'A' => 4,
            'AB' => 3.5,
            'B' => 3,
            'BC' => 2.5,
            'C' => 2,
            'CD' => 1.5,
            'D' => 1,
            'ED' => 0.5,
            'E' => 0
        ];
        if($idmhs == 0){
            $mhs = Mahasiswa::where('user_id',Auth::id())->first();
            $id = $mhs->id ?? 0;
            $idmhs = $mhs->id ?? 0;
            if($idmhs == 0){
                dd('User not found');
            }
        }else{
            $mhs = Mahasiswa::find($id);
        }

        $title = "Daftar Nilai Mahasiswa";
        $get_nilai = master_nilai::select('master_nilai.*','a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul')
                                    ->join('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
                                    ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                                    ->where(['nim'=>$mhs->nim])
                                    ->get();
        return view('mahasiswa.daftar_nilai', compact('mhs','title', 'get_nilai','kualitas'));
    }
}
