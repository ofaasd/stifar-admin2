<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbPesertaOnline;
use App\Models\PmbGelombang;
use App\Models\Prodi;
use App\Models\PegawaiBiodatum;

class StatistikController extends Controller
{
    //
    public function index(){
        $ta_max = PmbGelombang::selectRaw('max(ta_awal) as ta_max')->limit(1)->first()->ta_max;
        $curr_ta = $ta_max;
        $gelombang = PmbGelombang::where('ta_awal',$curr_ta)->get();
        $curr_gelombang = PmbGelombang::where('ta_awal',$curr_ta)->limit(1)->first();
        
        $laki_laki = []; 
        $perempuan = []; 
        $nama_gel = [];
        $list_jurusan = [];
        $list_peserta_jurusan = [];
        $list_agama = [];
        $program_studi = Prodi::all();
        $agama = [
            1 => "islam",
            2 => "kristen", 
            3 => "katolik", 
            4 => "hindu", 
            5 => "budha", 
            6 => "konghucu", 
        ];
        $jumlah_prodi = [];
        $ta_awal = date('Y');
        $list_prodi = [];
        foreach($program_studi as $row){
            $jumlah_prodi[$row->id] = PmbPesertaOnline::join('pmb_gelombang','pmb_gelombang.id','=','pmb_peserta_online.gelombang')->where('pmb_gelombang.ta_awal',$ta_awal)->where('pilihan1',$row->id)->count();
            $list_prodi[$row->id] = $row->nama_prodi;
        }
        foreach($gelombang as $row){
            $laki_laki[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('jk',1)->count();
            $perempuan[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('jk',2)->count();
            $nama_gel[$row->id] = $row->nama_gel;
            foreach($agama as $key => $value){
                $list_agama[$key][$row->id] = PmbPesertaOnline::where('agama',$key)->where('gelombang',$row->id)->count();
            }
            foreach($program_studi as $program){
                $list_jurusan[$program->id][$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('pilihan1',$program->id)->count();
            }
        }
        
        foreach($program_studi as $program){
            $list_jurusan[$program->id] = implode("\",\"",$list_jurusan[$program->id]);
            $list_jurusan[$program->id] =  "\"" . $list_jurusan[$program->id] . "\"";
        }
        foreach($agama as $key => $value){
            $list_agama[$key] = implode("\",\"",$list_agama[$key]);
            $list_agama[$key] =  "\"" . $list_agama[$key] . "\"";
        }
        $title = "Statistik Admisi";
        $laki_laki = implode("\",\"",$laki_laki);
        $perempuan = implode("\",\"",$perempuan);
        $nama_gel = implode("\",\"",$nama_gel);
        $laki_laki = "\"" . $laki_laki . "\"";
        $perempuan = "\"" . $perempuan . "\"";
        $nama_gel = "\"" . $nama_gel . "\"";
        $ta_mulai = 2025;
        $gelombang2 = PmbGelombang::where('ta_awal','>=',$ta_mulai)->get();
        $jumlah_pertahun = [];
        $list_tahun = [];
        foreach($gelombang2 as $row){
            $jumlah_pertahun[$row->ta_awal] = 0;
            $list_tahun[$row->ta_awal] = $row->ta_awal . "/" . ((int)$row->ta_awal+1);
        }
        foreach($gelombang2 as $row){
            $jumlah_pertahun[$row->ta_awal] += PmbPesertaOnline::where('gelombang',$row->id)->count();
        }
        $list_tahun = implode("\",\"",$list_tahun);
        $jumlah_pertahun = implode("\",\"",$jumlah_pertahun);
        $list_tahun = "\"" . $list_tahun . "\"";
        $jumlah_pertahun = "\"" . $jumlah_pertahun . "\"";
        $jumlah_prodi = implode("\",\"",$jumlah_prodi);
        $jumlah_prodi = "\"" . $jumlah_prodi . "\"";
        $list_prodi = implode("\",\"",$list_prodi);
        $list_prodi = "\"" . $list_prodi . "\"";
        return view('admin.admisi.statistik.index', compact('agama','list_agama','laki_laki','perempuan','nama_gel','title','curr_gelombang','list_tahun','jumlah_pertahun','list_jurusan','program_studi','jumlah_prodi','list_prodi', 'ta_awal'));
    }
    public function pegawai(){
        $gender = ['Laki-laki','Perempuan'];
        $jumlah_gender = [];
        foreach($gender as $key=>$value){
            if($value == "Laki-laki"){
                $jumlah_gender[$key] = PegawaiBiodatum::where('status_pegawai','aktif')->where('jenis_kelamin','L')->count();
            }else{
                $jumlah_gender[$key] = PegawaiBiodatum::where('status_pegawai','aktif')->where('jenis_kelamin','P')->count();
            }
        }
        $gender = implode("\",\"",$gender);
        $gender = "\"" . $gender . "\"";
        $jumlah_gender = implode("\",\"",$jumlah_gender);
        $jumlah_gender = "\"" . $jumlah_gender . "\"";

        $agama = [
            1 => "islam",
            2 => "kristen", 
            3 => "katolik", 
            4 => "hindu", 
            5 => "budha", 
            6 => "konghucu", 
        ];
        $jumlah_agama = [];
        foreach($agama as $key=>$value){
            $jumlah_agama[$key] = PegawaiBiodatum::where('status_pegawai','aktif')->where('agama',$key)->count();
        }
        $agama = implode("\",\"",$agama);
        $agama = "\"" . $agama . "\"";
        $jumlah_agama = implode("\",\"",$jumlah_agama);
        $jumlah_agama = "\"" . $jumlah_agama . "\"";

        $program_studi = Prodi::all();
        $jumlah_prodi = [];
        $list_prodi = [];
        foreach($program_studi as $row){
            $jumlah_prodi[$row->id] = PegawaiBiodatum::where('id_progdi',$row->id)->count();
            $list_prodi[$row->id] = $row->nama_prodi;
        }
        $list_prodi = implode("\",\"",$list_prodi);
        $list_prodi = "\"" . $list_prodi . "\"";
        $jumlah_prodi = implode("\",\"",$jumlah_prodi);
        $jumlah_prodi = "\"" . $jumlah_prodi . "\"";

        $title = "Statistik Pegawai";
        
        return view('admin.admisi.statistik.pegawai', compact('title','gender','jumlah_gender','agama','jumlah_agama', 'list_prodi','jumlah_prodi'));
    }
}
