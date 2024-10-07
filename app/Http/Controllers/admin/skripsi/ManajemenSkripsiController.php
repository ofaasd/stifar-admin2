<?php

namespace App\Http\Controllers\admin\skripsi;

use App\Models\Rumpun;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use App\Models\PegawaiBiodatum;
use App\Models\MasterPembimbing;
use App\Models\KelompokMataKuliah;
use App\Http\Controllers\Controller;
use App\Models\RefPembimbing;

class ManajemenSkripsiController extends Controller
{
    private $title;

    public function __construct()
    {
        $this->title = 'Manajemen Skripsi';
    }
    public function index()
    {
        $mk = MataKuliah::select('mata_kuliahs.*')->leftJoin('kelompok_mata_kuliahs', 'kelompok_mata_kuliahs.id', '=', 'mata_kuliahs.kel_mk')
            ->leftJoin('rumpuns', 'rumpuns.id', '=', 'mata_kuliahs.rumpun')->get();
        $kelompok = KelompokMatakuliah::get();
        $pembimbing = RefPembimbing::select('pegawai.nama_lengkap AS nama','ref_pembimbing_skripsi.nip AS nip','ref_pembimbing_skripsi.kuota AS kuota')->join('pegawai_biodata as pegawai','pegawai.npp' , 'ref_pembimbing_skripsi.nip')->get();
        // dd($pembimbing);
        $rumpun = Rumpun::get();
        $no = 1;
        return view('admin.skripsi.manajemen.pembimbing.index',[
            'title' =>$this->title, 
            'mk' =>$mk,
             'kelompok' =>$kelompok,
              'rumpun'=>$rumpun,
               'no'=>$no,
               'pembimbing'=>$pembimbing
            ]);
    }

    public function index_sidang(){
        $pembimbing = RefPembimbing::select('pegawai.nama_lengkap AS nama','ref_pembimbing_skripsi.nip AS nip','ref_pembimbing_skripsi.kuota AS kuota')->join('pegawai_biodata as pegawai','pegawai.npp' , 'ref_pembimbing_skripsi.nip')->get();
        return view('admin.skripsi.manajemen.sidang.index',[
            'title' =>$this->title, 
               'pembimbing'=>$pembimbing
            ]);
    }

    public function index_bimbingan(){
        $pembimbing = RefPembimbing::select('pegawai.nama_lengkap AS nama','ref_pembimbing_skripsi.nip AS nip','ref_pembimbing_skripsi.kuota AS kuota')->join('pegawai_biodata as pegawai','pegawai.npp' , 'ref_pembimbing_skripsi.nip')->get();
        return view('admin.skripsi.manajemen.bimbingan.index',[
            'title' =>$this->title, 
               'pembimbing'=>$pembimbing
            ]);

    }
    public function ListMahasiswaTa()
    {

    }

    public function detailBimbingan($nip){

        $data = MasterPembimbing::where('nip', $nip)->join('mahasiswa', 'mahasiswa.nim', 'master_pembimbing_skripsi.nim')->select('mahasiswa.nama', 'mahasiswa.nim', 'master_pembimbing_skripsi.topik_judul AS judul', 'master_pembimbing_skripsi.nip AS nip')->get();

        return view('dosen.skripsi.manajemen.pembimbing.detail', [
            'data' => $data,
            // 'totalPengajuan' => $totalPengajuan
        ]);
    }
}
