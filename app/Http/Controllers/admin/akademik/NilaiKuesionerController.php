<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\TblSoalKuesioner;
use App\Models\TblNilaiKuesioner;

class NilaiKuesionerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $id, Request $request)
    {
        //
        $krs_now = Jadwal::select('jadwals.*', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul')
                    ->join('mata_kuliahs as b', 'id_mk', '=', 'b.id')
                    ->where('jadwals.id_tahun', $id)
                    ->get();
        $title = "Jawaban Kuesioner";
        $jadwal = 0;
        $jawaban_detail = [];
        $jadwal_jawaban = [];
        $result = [];
        if(!empty($request->jadwal)){
            $id_ta = $request->id_ta;
            $jadwal  = $request->jadwal;
            $list_pertanyaan = TblSoalKuesioner::where('id_ta',$id_ta)->where('tipe_soal',1)->orderBy('no_soal','asc')->get();
            foreach($list_pertanyaan as $row){
				$result[$row->id] = $row->soal;
				for($i =4; $i >= 1; $i--){
                    $where = ['id_kuesioner'=>$row->id, 'id_jadwal'=>$jadwal,'id_ta'=>$id_ta, 'nilai'=>$i];
					$jawaban_detail[$row->id][$i] = TblNilaiKuesioner::where($where)->count();
				}
			}
            $jadwal_jawaban = Jadwal::select('jadwals.*', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul')
            ->join('mata_kuliahs as b', 'id_mk', '=', 'b.id')
            ->where('jadwals.id', $request->jadwal)
            ->first();
        }
        return view('admin.akademik.kuesioner.jawaban', compact('krs_now','title','id','jadwal','jawaban_detail','jadwal_jawaban','result'));


    }

    /**
     * Show the form for creating a new resource.
     */

}
