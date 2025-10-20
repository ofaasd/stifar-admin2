<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\PegawaiBiodatum as PegawaiBiodata;
use App\Models\Krs;
use App\Models\Prodi;
use App\Models\Kurikulum;
use App\Models\Jadwal;
use App\Models\MatakuliahKurikulum;
use App\Models\master_nilai;
use App\Models\LogKr as LogKrs;
use Barryvdh\DomPDF\Facade\Pdf;
use Auth;
use Session;

class AdminUjianController extends Controller
{
    //
    public function index(Request $request)
    {
        $curr_prodi = "";
        if(Auth::user()->hasRole('admin-prodi')){
            $pegawai = PegawaiBiodata::where('user_id',Auth::user()->id)->first();
            $curr_prodi = Prodi::find($pegawai->id_progdi);
        }
        $title = "List Mahasiswa";
        $tahun_ajaran = TahunAjaran::get();
        $prodi = Prodi::get();
        $angkatan = Mahasiswa::select("angkatan")->distinct()->orderBy('angkatan','desc')->get();
        return view('admin.akademik.ujian.mahasiswa', compact('title', 'tahun_ajaran','prodi','curr_prodi','angkatan'));
    }
}
