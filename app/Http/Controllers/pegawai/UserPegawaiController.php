<?php

namespace App\Http\Controllers\pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiBiodatum;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\PegawaiGolongan;
use App\Models\PegawaiJeni as PegawaiJenis;
use App\Models\PegawaiJabatanStruktural;
use App\Models\PegawaiJabatanFungsional;
use App\Models\PegawaiPendidikan;
use App\Models\PegawaiOrganisasi;
use App\Models\PegawaiPekerjaan;
use App\Models\PegawaiMengajar;
use App\Models\PegawaiPosisi;
use App\Models\PegawaiPenelitian;
use App\Models\Wilayah;
use App\Models\ModelHasRole;
use App\Models\JabatanFungsional;
use App\Models\JabatanStruktural;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Prodi;
use PDF;
use Carbon\Carbon; 
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CVExport;

class UserPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $id = Auth::user()->id;
        $jabatan_fungsional = JabatanFungsional::all();
        $jabatan_struktural = JabatanStruktural::all();



        $title = "Data Pegawai";
        $pegawai = PegawaiBiodatum::where('user_id',$id)->first();
        $jenis_kelamin = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
        ];
        $user = User::find($id);

        $id = $pegawai->id;

        $progdi = Prodi::all();
        $jenis_pegawai = PegawaiJenis::all();
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();
        $status = array('aktif','cuti','keluar','meninggal');
        $status_kawin = array("Lajang","Kawin");
        $posisi = [];
        $pos = PegawaiPosisi::all();
        $curr_jenis_pegawai = PegawaiPosisi::where('id',$pegawai->id_posisi_pegawai)->first();
        $curr_jabatan_fungsional = JabatanFungsional::where('id', $pegawai->id_jabfung)->first();
        $curr_jabatan_struktural = JabatanStruktural::where('id', $pegawai->id_jabstruk)->first();
        $list_jenis = PegawaiPosisi::where('id_jenis_pegawai',$curr_jenis_pegawai->id_jenis_pegawai)->get();
        foreach($pos as $row){
            $posisi[$row->id] = $row->nama;
        }
        $kota = [];
        if($pegawai->provinsi != 0 && !empty($pegawai->provinsi)){
            $kota = Wilayah::where('id_induk_wilayah', $pegawai->provinsi)->get();
        }

        $kecamatan = [];
        if($pegawai->kecamatan != 0 && !empty($pegawai->kecamatan)){
            $kecamatan = Wilayah::where('id_induk_wilayah', $pegawai->kotakab)->get();
        }
        return view("pegawai/profile/index", compact('kota','kecamatan','title','pegawai','posisi','jenis_pegawai','curr_jenis_pegawai','curr_jabatan_fungsional','curr_jabatan_struktural','jabatan_fungsional','jabatan_struktural', 'list_jenis','wilayah','status','status_kawin','progdi','jenis_kelamin','id','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function cetak_cv(){
        $id = Auth::user()->id;
        $pegawai = PegawaiBiodatum::where('user_id',$id)->first();
        $jabatan_struktural = JabatanStruktural::where('id_pegawai',$pegawai->id)->first()->jabatan ?? '';
        $jabatan_fungsional = '';
        if(!empty($pegawai->id_jabfung)){
            $jabatan_fungsional = JabatanFungsional::where('id',$pegawai->id_jabfung)->first()->jabatan ?? '';
        }
        $pegawai_pendidikan = PegawaiPendidikan::where('id_pegawai',$pegawai->id)->get();
        $pegawai_organisasi = PegawaiOrganisasi::where('id_pegawai',$pegawai->id)->get();
        $pegawai_pekerjaan = PegawaiPekerjaan::where('id_pegawai',$pegawai->id)->get();
        $pegawai_mengajar = PegawaiMengajar::where('id_pegawai',$pegawai->id)->get();
        $pegawai_penelitian = PegawaiPenelitian::where('id_pegawai',$pegawai->id)->get();
        $bulan = array(
            1=>"Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        );
        $data = [
            'logo' => public_path('/assets/images/logo/logo-icon.png'),
            'pegawai' => $pegawai,
            'bulan' => $bulan,
            'jabatan_struktural' => $jabatan_struktural,
            'jabatan_fungsional' => $jabatan_fungsional,
            'pegawai_pendidikan' => $pegawai_pendidikan,
            'pegawai_organisasi' => $pegawai_organisasi,
            'pegawai_pekerjaan' => $pegawai_pekerjaan,
            'pegawai_mengajar' => $pegawai_mengajar,
            'pegawai_penelitian' => $pegawai_penelitian,
        ];
        $pdf = PDF::loadView('pegawai/profile/cetak_cv', $data)
                    ->setPaper('a4', 'potrait');
        return $pdf->stream('CV-' . $pegawai->id . '-' . date('YmdHis'). '.pdf');
    }
    public function cetak_cv_excel(){
        return Excel::download(new CVExport, 'cv.xlsx');
    }
}
