<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagihanKeuangan;
use App\Models\Tagihan;
use App\Models\DetailTagihanMh as DetailTagihanKeuangan;
use App\Models\DetailTagihanKeuangan as DetailTagihanKeuanganTotal;
use App\Models\TahunAjaran;
use App\Models\JenisKeuangan;
use App\Models\Mahasiswa;
use App\Models\TbPembayaran;
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
        
        $tagihan_total = Tagihan::where('nim',$nim)->first();
        $total_bayar = $tagihan_total->pembayaran ?? 0;
        if(!empty($tagihan_total)){
            $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
            
            //jika prodi D3
            $status_bayar = false;
            $new_total_tagihan = 0;
            $i = 1;
            
            foreach($detail_tagihan as $dt){
                if($dt->id_jenis == 8){
                    $total_bayar = $total_bayar - $dt->jumlah;
                    $new_total_tagihan += $dt->jumlah;
                    
                }elseif($dt->id_jenis == 2 && $i == 1){
                    $total_bayar = $total_bayar - $dt->jumlah;
                    $new_total_tagihan += $dt->jumlah;
                    $i++;
                    
                }elseif($dt->id_jenis == 2 && $i > 1){
                    //dipecah UPP per bulan
                    $mahasiswa = Mahasiswa::where('nim',$nim)->first();
                    $upp_bulan = $dt->jumlah / 30;
                    $bulan_mhs = $mahasiswa->bulan_awal;
                    $tahun_mhs = $mahasiswa->angkatan;
                    $tagihan_bulan = date('m');
                    $tagihan_tahun = date('Y');
                    $pengurangan = ($tagihan_tahun * 12 + $tagihan_bulan) - ($tahun_mhs * 12 + $bulan_mhs);
                    $bulanan = $upp_bulan * $pengurangan;
                    $new_total_tagihan += $bulanan;
                    $total_bayar = $total_bayar - $bulanan;
                    if($total_bayar >= 0){
                        $status_bayar = true;
                    }
                }
            }
        }else{
            $new_total_tagihan = 0;
            $status_bayar = true;
        }
        return view('mahasiswa.tagihan', compact('title','mhs','tagihan', 'jenis','new_total_tagihan','status_bayar','tagihan_total'));
    }
    public function riwayat(){
        $title = "Riwayat Tagihan Mahasiswa";
        $mhs = Mahasiswa::where('user_id',Auth::id())->first();
        $nim = $mhs->nim;
        $pembayaran = TbPembayaran::where('nim',$nim)->get();
        $jenis = JenisKeuangan::all();
        $list_jenis = [];
        foreach($jenis as $j){
            $list_jenis[$j->kode] = $j->nama;
        }
        return view('mahasiswa.tagihan_riwayat', compact('title','mhs','pembayaran', 'list_jenis'));
    }
}
