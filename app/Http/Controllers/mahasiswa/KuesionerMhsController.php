<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblSoalKuesioner;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\TblNilaiKuesioner;
use Auth;

class KuesionerMhsController extends Controller
{
    //
    public function index(){

        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $id = $mhs->id ?? 0;
        $idmhs = $mhs->id ?? 0;
        if($idmhs == 0){
            dd('User not found');
        }
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $krs_now = Krs::select('krs.*', 'a.hari', 'a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul')
                    ->join('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
                    ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->where('krs.id_tahun', $ta)
                    ->where('id_mhs',$idmhs)
                    ->get();

        //cej kuesioner aktif
        if($tahun_ajaran->kuesioner == 1){
            $total_pertanyaan = count($krs_now);
            $nilai_kuesioner = TblNilaiKuesioner::select('id_jadwal')->where('id_ta',$ta)->where('nim',$mhs->nim)->groupBy('id_jadwal')->get();

            if(count($nilai_kuesioner) < $total_pertanyaan){
                //harus isi kuesioner
                //ambil data dari krs dulu dan di masukan ke dalam array untuk di cocokan
                $no = 1;
			    $array_matkul = [];
                $array = [];
                foreach($krs_now as $row){
                    $array[$no] = $row->id_jadwal;
                    $array_matkul[$row->id_jadwal] = $row->nama_matkul;
                    $no++;
                }
                //print_r($array);
                //ambil data nilai
                $array_nilai = [];
                $no = 1;
                foreach($nilai_kuesioner as $row){
                    $array_nilai[$no] = $row->id_jadwal;
                    $no++;
                }

                $diff = array_diff($array, $array_nilai);
                //print_r($diff);
                $reset = reset($diff);
                $new_jadwal = $reset;
                $matkul = $array_matkul[$new_jadwal];
                $pertanyaan = TblSoalKuesioner::where('id_ta',$ta)->get();
                $kuesioner_terisi = count($nilai_kuesioner);
                $kuesioner_total = $total_pertanyaan;
                $title = "Kuesioner Mahasiswa";
                $nim = $mhs->nim;
                return view('mahasiswa.kuesioner.index', compact('nim','new_jadwal','matkul','pertanyaan','title','kuesioner_terisi','kuesioner_total','ta'));
            }else{
                //kuesioner sudah selesai diisi
                return redirect('/mhs/khs');
            }
        }else{
            return redirect('/mhs/khs');
        }
    }
    public function store(Request $request){
        $nim = $request->nim;
        $id_jadwal = $request->id_jadwal;
        $id_ta = $request->id_ta;
        $soal = $request->soal;
        foreach($soal as $key=>$value){
            $data = [
                'nim' => $nim,
                'id_jadwal' => $id_jadwal,
                'id_kuesioner' => $key,
                'id_ta' => $id_ta,
                'nilai' => $value
            ];
            $simpan = TblNilaiKuesioner::create($data);
        }
        return redirect('/mhs/kuesioner_mhs');
    }
}
