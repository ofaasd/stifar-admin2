<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbGelombang;
use App\Models\PmbPesertaOnline;
use App\Models\PmbJalurProdi;
use App\Models\Prodi;
use App\Models\TahunAjaran;
use App\Models\BiayaPendaftaran;

class GenerateNimController extends Controller
{
    //
    public $indexed = ['', 'id','nama' , 'nopen','prodi','nim'];
    public function index(Request $request){
        $title = "Generate NIM";
        $date = date('Y-m-d');
        // $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        // $tahun = (int)substr($tahun_ajaran->kode_ta,0,4);
        // $tahun_awal = $tahun+1;
        $tahun_ajaran = PmbGelombang::orderBy('id','desc')->limit(1)->first();
        $gelombang = PmbGelombang::where('ta_awal',$tahun_ajaran->ta_awal)->get();
        $jumlah_diterima = [];
        $jumlah_pendaftar = [];
        $jumlah_verifikasi = [];
        $jumlah_bayar = [];
        foreach($gelombang as $row){
            $jumlah_pendaftar[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->count();
            $jumlah_verifikasi[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('is_verifikasi',1)->count();
            $jumlah_bayar[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('is_bayar',1)->count();
            $jumlah_diterima[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('is_lolos',1)->count();
        }
        return view('admin.admisi.generate_nim.index',compact('title','date','gelombang','jumlah_diterima','jumlah_verifikasi','jumlah_bayar','jumlah_pendaftar'));
    }
}
