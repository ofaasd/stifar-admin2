<?php

namespace App\Http\Controllers;

use App\helpers;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use App\Models\master_nilai;
use Illuminate\Http\Request;

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
            'pegawai_biodata.nama_lengkap as dosenWali',
        ])
        ->where('mahasiswa.nim', $nim)
        ->leftJoin('program_studi', 'mahasiswa.id_program_studi', '=', 'program_studi.id')
        ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali');

        if ($tglLahir != "1945-08-17") {
            $query->where('mahasiswa.tgl_lahir', $tglLahir);
        }

        $mhs = $query->first();

        if (!$mhs) {
            return redirect()->back()->withErrors(['msg' => 'Data mahasiswa tidak ditemukan. Silakan periksa NIM dan Tanggal Lahir.'])->withInput();
        } 

        $angkatanTa = (int)($mhs->angkatan . "1");
        $tahunAjaran = TahunAjaran::where('status','Tidak Aktif')->where('kode_ta', '>=', $angkatanTa)->get();

        $taAktif = TahunAjaran::where('status','Aktif')->first();

        $getNilai = $this->helpers->getDaftarNilaiMhs($mhs->nim);

        $totalSks = 0;
        $totalIps = 0;
        foreach ($getNilai as $row) {
            $sks = ($row->sks_teori + $row->sks_praktek);
            $totalSks += $sks;
            if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1)
            {
                $totalIps +=  ($row->sks_teori+$row->sks_praktek) * $this->helpers->getKualitas($row['nhuruf']);
            }
        }
        $mhs->totalSks = $totalSks;
        $mhs->totalIps = $totalIps;
        $mhs->ipk = $totalSks > 0 ? number_format($totalIps / $totalSks, 2) : 0;

        $krsNow = [];
        $nilai = [];
        $jumlahMatkul=0;
        $jumlahValid = 0;
        foreach($tahunAjaran as $taRow){
            $ta = $taRow->id;
            $krsNow = $this->helpers->GetKrsMhs($mhs, $ta);
            
            foreach($krsNow as $row){
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = '-';
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = '-';
                $jumlahMatkul++;
            }

            $getNilai = $this->helpers->getAllNilai($mhs->nim, $ta);

            foreach($getNilai as $row){
                if($row->publish_tugas == 1){
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = $row->ntugas;
                    // $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] = "-";
                }

                if($row->publish_uts == 1){
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = $row->nuts;
                    // $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uts'] = "-";
                }
                if($row->publish_uas == 1){
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = $row->nuas;
                    // $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_uas'] = '-';
                }
                    
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['sks_teori'] = $row->sks_teori;
                $nilai[$row->id_jadwal][$ta][$mhs->nim]['sks_praktek'] = $row->sks_praktek;
                // if($row->publish_tugas == 1 && $row->publish_uts == 1 && $row->publish_uas == 1){
                //     $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = $row->nakhir;
                //     $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = $row->nhuruf;
                // }
                if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1){
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_akhir'] = $row->nakhir;
                    $nilai[$row->id_jadwal][$ta][$mhs->nim]['nilai_huruf'] = $row->nhuruf;
                    $jumlahValid++;
                }

            }
        }

        $title = "Parent | " . $mhs->nama;
        return view('parent.show', compact('mhs', 'tahunAjaran', 'krsNow', 'nilai','jumlahMatkul','jumlahValid', 'title', 'taAktif'));

    }
}
