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
        $id = $mhs->id_program_studi;
        $nim = $mhs->nim;
        $ta = TahunAjaran::where("status", "Aktif")->first();
        $upp_bulan = 0;
        $upp_semester = 0;
        $dpp = 0;
        $tagihan_total = Tagihan::where('nim',$nim)->first();
        $total_bayar = $tagihan_total->pembayaran ?? 0;
        //jika prodi D3
        $status_bayar = false;
        $new_total_tagihan = 0;
        $i = 1;
        $pengurangan = 0;
       
        if(!empty($tagihan_total->id)){                        
            if($id == 1 || $id == 2){
                $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
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
                        $pengurangan = ($tagihan_tahun * 12 + $tagihan_bulan) - ($tahun_mhs * 12 + $bulan_mhs) + 1;//ditambah 1 karena julidi hitung
                        $bulanan = $upp_bulan * $pengurangan;
                        $new_total_tagihan += $bulanan;
                        $total_bayar = $total_bayar - $bulanan;
                        if($total_bayar >= 0){
                            $status_bayar = true;
                        }
                        
                    }
                }
            }elseif($id == 5){
                
                $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
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
                        
                        $upp_bulan = $dt->jumlah / 8;
                        
                        $bulan_mhs = $mahasiswa->bulan_awal;
                        $tahun_mhs = $mahasiswa->angkatan;
                        $tagihan_bulan = date('m');
                        $tagihan_tahun = date('Y');
                        $pengurangan = ($tagihan_tahun * 12 + $tagihan_bulan) - ($tahun_mhs * 12 + $bulan_mhs) + 1;//ditambah 1 karena julidi hitung
                        $bulanan = $upp_bulan * $pengurangan;
                        $new_total_tagihan += $bulanan;
                        $total_bayar = $total_bayar - $bulanan;
                        if($total_bayar >= 0){
                            $status_bayar = true;
                        }
                        
                    }
                }
            }else{
                $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
                $i = 0;
                foreach($detail_tagihan as $dt){
                    if($dt->id_jenis == 2 && $i == 0){
                        $new_total_tagihan += $dt->jumlah;
                        $total_bayar = $total_bayar - $dt->jumlah;
                        $upp_semester = $dt->jumlah;
                        $i++;
                    }elseif($dt->id_jenis == 8){
                        $new_total_tagihan += $dt->jumlah;
                        $total_bayar = $total_bayar - $dt->jumlah;
                        if($total_bayar >= 0){
                            $status_bayar = true;
                        }
                    }elseif($dt->id_jenis == 1){
                        $dpp = $dt->jumlah;
                    }
                }
            }
        }
        
        $tagihan_total_bayar = $tagihan_total->pembayaran ?? 0;
        
        $status = ($tagihan_total_bayar >= $new_total_tagihan) ? 1 : 0;
        $bayar_dpp = 0;
        if($status == 1){
            $bayar_dpp = $tagihan_total_bayar  -  $new_total_tagihan;
        }
        
        return view('mahasiswa.tagihan', compact('title','mhs','upp_bulan', 'upp_semester','dpp','new_total_tagihan','tagihan_total_bayar','status','bayar_dpp'));
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
