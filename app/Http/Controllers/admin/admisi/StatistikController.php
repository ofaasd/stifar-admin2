<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbPesertaOnline;
use App\Models\PmbGelombang;

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
        foreach($gelombang as $row){
            $laki_laki[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('jk',1)->count();
            $perempuan[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('jk',2)->count();
            $nama_gel[$row->id] = $row->nama_gel;
        }
        $title = "Statistik Admisi";
        $laki_laki = implode("\",\"",$laki_laki);
        $perempuan = implode("\",\"",$perempuan);
        $nama_gel = implode("\",\"",$nama_gel);
        $laki_laki = "\"" . $laki_laki . "\"";
        $perempuan = "\"" . $perempuan . "\"";
        $nama_gel = "\"" . $nama_gel . "\"";
        return view('admin.admisi.statistik.index', compact('laki_laki','perempuan','nama_gel','title','curr_gelombang'));
    }
}
