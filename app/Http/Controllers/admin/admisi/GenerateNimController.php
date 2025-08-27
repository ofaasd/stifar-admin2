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
use App\Models\Mahasiswa;
use App\Models\MahasiswaTemp;

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
    public function show(String $id, Request $request){
        $gelombang = PmbGelombang::find($id);
        $title = "Generate NIM";
        $date = date('Y-m-d');
        $status = 0;

        //status = 0 tampilkan mhs registrasi awal sudah masuk
        //status = 1 tampilkan mhs registrasi awal belum masuk

        $prod = [];
        $all_prodi = Prodi::all();
        foreach($all_prodi as $row){
            $prod[$row->id] = $row->nama_prodi . " " . $row->keterangan;
        }




        if(!empty($request->id_gelombang)){
            $status = $request->status;
            $id = $request->id_gelombang;
            if($status == 0){
                $peserta = PmbPesertaOnline::where('gelombang',$id)
                            ->where('is_lolos',1)
                            ->whereRaw('nopen <> ""')
                            ->whereRaw('pilihan1 <> ""')
                            ->whereNull('nim')
                            ->where('registrasi_awal','>',0)
                            ->orderBy('pilihan1','asc')
                              ->orderBy('nama','asc')
                            ->get();
            }else{
                $peserta = PmbPesertaOnline::where('gelombang',$id)
                            ->where('is_lolos',1)
                            ->whereRaw('nopen <> ""')
                            ->whereRaw('pilihan1 <> ""')
                            ->whereNull('nim')
                            ->orderBy('pilihan1','asc')
                            ->orderBy('nama','asc')
                            ->get();
            }
        }else{
            $peserta = PmbPesertaOnline::where('gelombang',$id)
                        ->where('is_lolos',1)
                        ->whereRaw('nopen <> ""')
                        ->whereRaw('pilihan1 <> ""')
                        ->where('registrasi_awal','>',0)
                        ->whereNull('nim')
                        ->orderBy('pilihan1','asc')
                        ->orderBy('nama','asc')
                        ->get();
        }

        return view('admin.admisi.generate_nim.peserta',compact('title','date','id','status','gelombang','peserta','prod'));

    }
    public function generate_preview(Request $request){
        $status = $request->status;
        $id = $request->id_gelombang;
        if(!empty($request->id_gelombang)){
            $status = $request->status;
            $id = $request->id_gelombang;
            if($status == 0){
                $peserta = PmbPesertaOnline::where('gelombang',$id)
                            ->where('is_lolos',1)
                            ->whereRaw('nopen <> ""')
                            ->whereRaw('pilihan1 <> ""')
                            ->whereNull('nim')
                            ->where('registrasi_awal','>',0)
                            ->orderBy('pilihan1','asc')
                            ->orderBy('nama','asc')
                            ->get();
            }else{
                $peserta = PmbPesertaOnline::where('gelombang',$id)
                            ->where('is_lolos',1)
                            ->whereRaw('nopen <> ""')
                            ->whereRaw('pilihan1 <> ""')
                            ->whereNull('nim')
                            ->orderBy('pilihan1','asc')
                            ->orderBy('nama','asc')
                            ->get();
            }
        }else{
            $peserta = PmbPesertaOnline::where('gelombang',$id)
                        ->where('is_lolos',1)
                        ->whereRaw('nopen <> ""')
                        ->whereRaw('pilihan1 <> ""')
                        ->where('registrasi_awal','>',0)
                        ->whereNull('nim')
                        ->orderBy('pilihan1','asc')
                        ->orderBy('nama','asc')
                        ->get();
        }
        $peserta2 = PmbPesertaOnline::where('gelombang', $id)->distinct()->get(['pilihan1']);
        $no_urutan_nim = [];
        foreach($peserta2 as $rows){
            echo $rows->pilihan1;
            echo "<br />";
            $no_urutan_nim[$rows->pilihan1] = 0;
        }
        foreach($peserta as $row){

            $angkatan = date('y');
            $nim_awal = '';
            $prodi = Prodi::find($row->pilihan1);
            $kode_nim = $prodi->kode_nim;
            $kode_asal = '11';
            $gabung = $kode_nim . $angkatan . $kode_asal;

            if($no_urutan_nim[$row->pilihan1] == 0){
                $last_nim_mhs = Mahasiswa::where('nim','like','%' . $gabung . '%')->orderBy('nim','desc')->limit(1)->first()->nim ?? '000';
                $last_nim_mhs2 = MahasiswaTemp::where('nim','like','%' . $gabung . '%')->orderBy('nim','desc')->limit(1)->first()->nim ?? '000';

                $last_num1 = (int)substr($last_nim_mhs,-3);
                $last_num2 = (int)substr($last_nim_mhs2,-3);
                if($last_num1 == 0 && $last_num2 == 0){
                    $no_urutan_nim[$row->pilihan1] = 1;
                }elseif($last_num1 > $last_num2){
                    $no_urutan_nim[$row->pilihan1] = $last_num1+1;
                }else{
                    $no_urutan_nim[$row->pilihan1] = $last_num2+1;
                }
            }

            $angka = '';
            if(strlen($no_urutan_nim[$row->pilihan1]) == 1){
                $angka = '00' . (string)$no_urutan_nim[$row->pilihan1];
            }elseif(strlen($no_urutan_nim[$row->pilihan1]) == 2){
                $angka = '0' . (string)$no_urutan_nim[$row->pilihan1];
            }else{
                $angka = (string)$no_urutan_nim[$row->pilihan1];
            }
            $new_nim = $gabung . $angka;
            $data = [
                'nisn' => $row->nisn,
                'nim' => $new_nim  ,
                'nama' => $row->nama,
                'no_ktp' => $row->no_ktp,
                'jk' => $row->jk,
                'agama' => $row->agama,
                'tempat_lahir' => $row->tempat_lahir,
                'tgl_lahir' => $row->tanggal_lahir,
                'nama_ibu' => $row->nama_ibu,
                'nama_ayah' => $row->nama_ayah,
                'hp_ortu' => $row->hp_ortu,
                'alamat' => $row->alamat,
                'rt' => $row->rt,
                'rw' => $row->rw    ,
                'kelurahan' => $row->kelurahan,
                'kecamatan' => $row->kecamatan,
                'kokab' => $row->kotakab,
                'provinsi' => $row->provinsi,
                'telp' => $row->telpon,
                'hp' => $row->hp,
                'status' => 0,
                'angkatan' => $row->angkatan,
                'nopen' => $row->nopen,
                'id_program_studi' => $row->pilihan1,
            ];
            $simpan=MahasiswaTemp::create($data);
            $pmb_peserta=PmbPesertaOnline::find($row->id);
            $pmb_peserta->nim = $new_nim;
            $pmb_peserta->save();
            $no_urutan_nim[$row->pilihan1]++;

        }
        return redirect('/admin/admisi/generate_nim/preview');
    }
    public function preview(Request $request){
        $title = "Preview NIM Mahasiswa";
        $mhs = MahasiswaTemp::select('mahasiswa_temp.*','program_studi.nama_prodi')
                ->join('program_studi','program_studi.id','=','mahasiswa_temp.id_program_studi')
                ->orderBy('nim','asc')
                ->get();
        return view('admin.admisi.generate_nim.preview',compact('title','mhs'));
    }
    public function save_temp(Request $request){
        $id = $request->id_mhs;
        $nim = $request->nim;
       // var_dump($id);
        foreach($id as $key=>$value){
            $mhs = MahasiswaTemp::find($value);
            $mhs->nim = $nim[$key];
            $mhs->save();

            PmbPesertaOnline::where('nopen',$mhs->nopen)->update(['nim'=>$nim[$key]]);
        }
        return redirect()->back();
    }
    public function delete_temp(String $id){
        $temp = MahasiswaTemp::find($id);
        $pmb = PmbPesertaOnline::where('nopen',$temp->nopen)->update(['nim'=>NULL]);
        $temp = MahasiswaTemp::where('id', $id)->delete();
    }
    public function regenerate(){
        $temp1 = MahasiswaTemp::distinct()->get(['id_program_studi']);
        $no_urutan_nim = [];
        foreach($temp1 as $rows){
            $no_urutan_nim[$rows->id_program_studi] = 0;
        }
        $peserta = MahasiswaTemp::orderBy('nama','asc')->get();
        foreach($peserta as $row){

            $angkatan = date('y');
            $nim_awal = '';
            $prodi = Prodi::find($row->id_program_studi);
            $kode_nim = $prodi->kode_nim;
            $kode_asal = '11';
            $gabung = $kode_nim . $angkatan . $kode_asal;

            if($no_urutan_nim[$row->id_program_studi] == 0){
                $last_nim_mhs = Mahasiswa::where('nim','like','%' . $gabung . '%')->orderBy('nim','desc')->limit(1)->first()->nim ?? '000';
                $last_nim_mhs2 = MahasiswaTemp::where('nim','like','%' . $gabung . '%')->orderBy('nim','asc')->limit(1)->first()->nim ?? '000';

                $last_num1 = (int)substr($last_nim_mhs,-3);
                $last_num2 = (int)substr($last_nim_mhs2,-3);

                if($last_num1 == 0 && $last_num2 == 0){
                    $no_urutan_nim[$row->id_program_studi] = 1;
                }elseif($last_num1 > $last_num2){
                    $no_urutan_nim[$row->id_program_studi] = $last_num1+1;
                }else{
                    $no_urutan_nim[$row->id_program_studi] = $last_num2;
                }
            }

            $angka = '';
            if(strlen($no_urutan_nim[$row->id_program_studi]) == 1){
                $angka = '00' . (string)$no_urutan_nim[$row->id_program_studi];
            }elseif(strlen($no_urutan_nim[$row->id_program_studi]) == 2){
                $angka = '0' . (string)$no_urutan_nim[$row->id_program_studi];
            }else{
                $angka = (string)$no_urutan_nim[$row->id_program_studi];
            }
            $new_nim = $gabung . $angka;
            $data = [
                'nim' => $new_nim,
            ];
            $simpan=MahasiswaTemp::UpdateorCreate([
                'id' => $row->id,
            ],$data);
            $pmb_peserta=PmbPesertaOnline::where('nopen',$row->nopen)->update(['nim'=>$new_nim]);
            $no_urutan_nim[$row->id_program_studi]++;

        }
        return redirect('/admin/admisi/generate_nim/preview');
    }
    public function generate(){
        $temp = MahasiswaTemp::all();
        foreach($temp as $row){
            $data = [
                'nisn' => $row->nisn,
                'nim' => $row->nim  ,
                'nama' => $row->nama,
                'no_ktp' => $row->no_ktp,
                'jk' => $row->jk,
                'agama' => $row->agama,
                'tempat_lahir' => $row->tempat_lahir,
                'tgl_lahir' => $row->tgl_lahir,
                'nama_ibu' => $row->nama_ibu,
                'nama_ayah' => $row->nama_ayah,
                'hp_ortu' => $row->hp_ortu,
                'alamat' => $row->alamat,
                'rt' => $row->rt,
                'rw' => $row->rw    ,
                'kelurahan' => $row->kelurahan,
                'kecamatan' => $row->kecamatan,
                'kokab' => $row->kokab,
                'provinsi' => $row->provinsi,
                'telp' => $row->telp,
                'hp' => $row->hp,
                'status' => 1,
                'angkatan' => $row->angkatan,
                'nopen' => $row->nopen,
                'id_program_studi' => $row->id_program_studi,
            ];
            $simpan=Mahasiswa::create($data);
        }
        $hapus=MahasiswaTemp::truncate();
        return redirect('mahasiswa');
    }

}
