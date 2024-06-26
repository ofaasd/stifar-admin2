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
use App\Models\PegawaiPosisi;
use App\Models\Wilayah;
use App\Models\ModelHasRole;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Prodi;

class UserPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $id = Auth::user()->id;
        $title = "Data Pegawai";
        $pegawai = PegawaiBiodatum::where('user_id',$id)->first();
        $jenis_kelamin = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
        ];
        $user = User::find($id);
        $progdi = Prodi::all();
        $jenis_pegawai = PegawaiJenis::all();
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();
        $status = array('aktif','cuti','keluar','meninggal');
        $status_kawin = array("Lajang","Kawin");
        $posisi = [];
        $pos = PegawaiPosisi::all();
        $curr_jenis_pegawai = PegawaiPosisi::where('id',$pegawai->id_posisi_pegawai)->first();
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
        return view("pegawai/profile/index", compact('kota','kecamatan','title','pegawai','posisi','jenis_pegawai','curr_jenis_pegawai','list_jenis','wilayah','status','status_kawin','progdi','jenis_kelamin','id','user'));
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
}
