<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagihanKeuangan;
use App\Models\DetailTagihanKeuangan;
use App\Models\TahunAjaran;
use App\Models\JenisKeuangan;
use App\Models\Mahasiswa;
use Auth;


class TagihanMhsController extends Controller
{
    //
    public function index(){
        $title = "Info Tagihan Mahasiswa";
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $nim = $mhs->nim;
        $ta = TahunAjaran::where("status", "Aktif")->first();
        $tagihan = TagihanKeuangan::where('id_tahun',$ta->id)->where('nim',$nim)->first();
        $jenis = JenisKeuangan::all();
        $list_total = [];
        foreach($jenis as $row){
            $list_total[$row->id] = DetailTagihanKeuangan::where('id_tagihan',$tagihan->id)->where('id_jenis',$row->id)->first()->jumlah;
        }
        return view('mahasiswa.tagihan', compact('title','mhs','tagihan', 'jenis','list_total'));
    }
}
