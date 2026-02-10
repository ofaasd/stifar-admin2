<?php

namespace App\Http\Controllers;

use App\helpers;
use App\Models\Krs;
use App\Models\Tagihan;
use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use App\Models\DetailTagihanKeuangan as DetailTagihanKeuanganTotal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    protected $helpers;

    public function __construct()
    {
        $this->helpers = new helpers();
    }

    public function index()
    {
        return view('parent.index');
    }

    public function show(Request $request)
    {
        $nim = $request->nim;
        $tglLahir = $request->tglLahir;

        $query = Mahasiswa::select([
            'mahasiswa.*',
            'program_studi.nama_prodi AS prodi',
            DB::raw("CONCAT(pegawai_biodata.gelar_depan, ' ', pegawai_biodata.nama_lengkap, ' ', pegawai_biodata.gelar_belakang) as dosenWali"),
            'pegawai_biodata.nohp as noHpDosenWali',
            'pegawai_biodata.email1 as emailDosenWali',
        ])
        ->where('mahasiswa.nim', $nim)
        ->leftJoin('program_studi', 'mahasiswa.id_program_studi', '=', 'program_studi.id')
        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali');

        if ($tglLahir != "1945-08-17") {
            $query->where('mahasiswa.tgl_lahir', $tglLahir)
            ->orWhere('mahasiswa.tgl_lahir_ibu', $tglLahir)
            ->orWhere('mahasiswa.tgl_lahir_ayah', $tglLahir);
        }

        $mhs = $query->first();

        if (!$mhs) {
            return redirect()->back()->withErrors(['msg' => 'Data mahasiswa tidak ditemukan. Silakan periksa NIM dan Tanggal Lahir.'])->withInput();
        }

        $idProgramStudi = $mhs->id_program_studi;

        $angkatanTa = (int)($mhs->angkatan . "1");
        $tahunAjaran = TahunAjaran::where('status','Tidak Aktif')->where('kode_ta', '>=', $angkatanTa)->get();

        $taAktif = $this->helpers->getTahunAjaranAktif();

        $kualitas = $this->helpers->getArrKualitas();
        $getNilai = $this->helpers->getDaftarNilaiMhs($mhs->nim);

        $totalSks = 0;
        $totalIps = 0;
        foreach ($getNilai as $row) {
            $sks = ($row->sks_teori + $row->sks_praktek);
            $totalSks += $sks;
            if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1)
            {
                $totalIps +=  ($row->sks_teori+$row->sks_praktek) * $this->helpers->getKualitas($row->nhuruf);
            }
            
        }
        $mhs->totalSks = $totalSks;
        $mhs->totalIps = $totalIps;
        $mhs->ipk = $totalSks > 0 ? number_format($totalIps / $totalSks, 2) : 0;

        $krs = Krs::select('krs.*', 'a.hari','a.kode_jadwal','a.kel', 'b.nama_matkul', 'b.sks_teori', 'b.sks_praktek','b.kode_matkul', 'c.nama_sesi', 'd.nama_ruang')
            ->join('jadwals as a', 'krs.id_jadwal', '=', 'a.id')
            ->leftJoin('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
            ->leftJoin('waktus as c', 'a.id_sesi', '=', 'c.id')
            ->leftJoin('master_ruang as d', 'a.id_ruang', '=', 'd.id')
            ->where('krs.id_tahun', $taAktif->id)
            ->where('id_mhs',$mhs->id)
            ->orderByRaw("CASE a.hari 
                WHEN 'Senin' THEN 1 
                WHEN 'Selasa' THEN 2 
                WHEN 'Rabu' THEN 3 
                WHEN 'Kamis' THEN 4 
                WHEN 'Jumat' THEN 5 
                WHEN 'Sabtu' THEN 6 
                WHEN 'Minggu' THEN 7 
                ELSE 8 END")
            ->get();

        $uppBulan = 0;
        $uppSemester = 0;
        $dpp = 0;
        $tagihanTotal = Tagihan::where('nim',$nim)->first();
        $totalBayar = $tagihanTotal->pembayaran ?? 0;
        //jika prodi D3
        $statusBayar = false;
        $newTotalTagihan = 0;
        $i = 1;
        $pengurangan = 0;

        if(!empty($tagihanTotal->id)){                        
            if($idProgramStudi == 1 || $idProgramStudi == 2){
                $detailTagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihanTotal->id)->get();
                foreach($detailTagihan as $dt){
                    if($dt->id_jenis == 8){
                        $totalBayar = $totalBayar - $dt->jumlah;
                        $newTotalTagihan += $dt->jumlah;
                        
                    }elseif($dt->id_jenis == 2 && $i == 1){
                        $totalBayar = $totalBayar - $dt->jumlah;
                        $newTotalTagihan += $dt->jumlah;
                        $i++;
                        
                    }elseif($dt->id_jenis == 2 && $i > 1){
                        //dipecah UPP per bulan
                        $mahasiswa = Mahasiswa::where('nim',$nim)->first();                        
                        $uppBulan = $dt->jumlah / 30;                       
                        $bulanMhs = $mahasiswa->bulan_awal;
                        $tahunMhs = $mahasiswa->angkatan;
                        $tagihanBulan = date('m');
                        $tagihanTahun = date('Y');
                        $pengurangan = ($tagihanTahun * 12 + $tagihanBulan) - ($tahunMhs * 12 + $bulanMhs) + 1;//ditambah 1 karena julidi hitung
                        $bulanan = $uppBulan * $pengurangan;
                        $newTotalTagihan += $bulanan;
                        $totalBayar = $totalBayar - $bulanan;
                        if($totalBayar >= 0){
                            $statusBayar = true;
                        }
                        
                    }
                }
            }elseif($idProgramStudi == 5){
                
                $detailTagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihanTotal->id)->get();
                foreach($detailTagihan as $dt){
                    
                    
                    if($dt->id_jenis == 8){
                        $totalBayar = $totalBayar - $dt->jumlah;
                        $newTotalTagihan += $dt->jumlah;
                        
                    }elseif($dt->id_jenis == 2 && $i == 1){
                        $totalBayar = $totalBayar - $dt->jumlah;
                        $newTotalTagihan += $dt->jumlah;
                        $i++;
                        
                    }elseif($dt->id_jenis == 2 && $i > 1){
                        //dipecah UPP per bulan
                        $mahasiswa = Mahasiswa::where('nim',$nim)->first();
                        
                        $uppBulan = $dt->jumlah / 8;
                        
                        $bulanMhs = $mahasiswa->bulan_awal;
                        $tahunMhs = $mahasiswa->angkatan;
                        $tagihanBulan = date('m');
                        $tagihanTahun = date('Y');
                        $pengurangan = ($tagihanTahun * 12 + $tagihanBulan) - ($tahunMhs * 12 + $bulanMhs) + 1;//ditambah 1 karena julidi hitung
                        $bulanan = $uppBulan * $pengurangan;
                        $newTotalTagihan += $bulanan;
                        $totalBayar = $totalBayar - $bulanan;
                        if($totalBayar >= 0){
                            $statusBayar = true;
                        }
                        
                    }
                }
            }else{
                $detailTagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihanTotal->id)->get();
                $i = 0;
                foreach($detailTagihan as $dt){
                    if($dt->id_jenis == 2 && $i == 0){
                        $newTotalTagihan += $dt->jumlah;
                        $totalBayar = $totalBayar - $dt->jumlah;
                        $uppSemester = $dt->jumlah;
                        $i++;
                    }elseif($dt->id_jenis == 8){
                        $newTotalTagihan += $dt->jumlah;
                        $totalBayar = $totalBayar - $dt->jumlah;
                        if($totalBayar >= 0){
                            $statusBayar = true;
                        }
                    }elseif($dt->id_jenis == 1){
                        $dpp = $dt->jumlah;
                    }
                }
            }
        }
        
        $tagihanTotalBayar = $tagihanTotal->pembayaran ?? 0;
        
        $statusBayarBayar = ($tagihanTotalBayar >= $newTotalTagihan) ? 1 : 0;
        $bayarDpp = 0;
        if($statusBayarBayar == 1){
            $bayarDpp = $tagihanTotalBayar  -  $newTotalTagihan;
        }
        

        $title = "Parent | " . $mhs->nama;
        return view('parent.show', compact('mhs', 'getNilai', 'title', 'taAktif', 'krs', 'kualitas','uppBulan', 'uppSemester','dpp','newTotalTagihan','tagihanTotalBayar','statusBayarBayar','bayarDpp'));

    }
}
