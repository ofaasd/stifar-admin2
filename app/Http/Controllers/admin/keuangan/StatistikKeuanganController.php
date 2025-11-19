<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan; 
use App\Models\DetailTagihanKeuangan; 
use App\Models\Mahasiswa; 
use App\Models\JenisKeuangan;
use App\Models\Prodi;
use App\Models\TbPembayaran; 


class StatistikKeuanganController extends Controller
{
    //
    public function index(Int $id=0)
    {
        $title = "Statistik Keuangan";
        $tagihan = Tagihan::all();
        foreach ($tagihan as $index => $row) {
            $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
            $pembayaran = TbPembayaran::where('nim',$row->nim);
            $last_pay = $pembayaran->orderBy('id','desc')->first()->tanggal_bayar ?? '';
            $total_bayar = $pembayaran->sum('jumlah');
            $nestedData = [];
            $nestedData['id'] = $row->id;
            $nestedData['gelombang'] = $row->gelombang ?? "";
            $nestedData['nim'] = $row->nim ?? "";
            $nestedData['nama'] = $mahasiswa->nama ?? "";
            $nestedData['pembayaran'] = number_format($total_bayar ?? 0,0,",",".");
            $nestedData['last_pay'] = date('d-m-Y', strtotime($last_pay)) ?? "";
            $sisa_bayar =  ((int) $row->total_bayar - (int) $total_bayar);
            $nestedData['sisa_bayar'] = number_format($sisa_bayar ?? 0,0,",",".");
            $nestedData['total_bayar'] =  number_format($row->total_bayar ?? 0,0,",",".");
            $nestedData['status'] =  $sisa_bayar <= 0 ? 'Lunas' : 'Belum Lunas' ;
            
            $data[] = $nestedData;
        }
        $no = 1;
        return view('admin.keuangan.statistik.index', compact('title','data','no'));
    }
    public function update_total_tagihan(){
        $tagihan = Tagihan::all();
        foreach ($tagihan as $row) {
            $total = DetailTagihanKeuangan::where('id_tagihan', $row->id)->sum('jumlah');
            $row->total_bayar = $total;
            $row->save();
        }
        return redirect()->back()->with('success', 'Total tagihan berhasil diupdate');
    }
}
