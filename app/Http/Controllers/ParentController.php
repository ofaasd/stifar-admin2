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

        $title = "Parent | " . $mhs->nama;
        return view('parent.show', compact('mhs', 'getNilai', 'title', 'taAktif', 'krs', 'kualitas'));

    }
}
