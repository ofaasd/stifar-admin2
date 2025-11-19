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
    public function index()
    {
        $title = "Statistik Keuangan";
        $tagihan = Tagihan::all();
        $prodi = Prodi::all();
        $nama = [];
        foreach($prodi as $row){
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
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
        $id = 0;
        return view('admin.keuangan.statistik.index', compact('title','data','no','prodi','id','nama'));
    }
    public function show($id){
        $title = "Statistik Keuangan";
        $tagihan = Tagihan::join('mahasiswa','mahasiswa.nim','=','tagihan.nim')
                    ->where('mahasiswa.id_program_studi',$id)
                    ->select('tagihan.*')
                    ->get();
        $prodi = Prodi::all();
        $nama = [];
        foreach($prodi as $row){
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
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
        return view('admin.keuangan.statistik.index', compact('title','data','no','prodi','id','nama'));
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
