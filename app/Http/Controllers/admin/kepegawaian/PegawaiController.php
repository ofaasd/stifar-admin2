<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\PegawaiBiodatum;
use App\Models\PegawaiGolongan;
use App\Models\PegawaiJeni as PegawaiJenis;
use App\Models\PegawaiJabatanStruktural;
use App\Models\PegawaiJabatanFungsional;
use App\Models\PegawaiPosisi;
use App\Models\Wilayah;

use App\Models\Prodi;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $pegawai_biodata = PegawaiBiodatum::all();
        foreach($pegawai_biodata as $row){
            $pegawai = Pegawai::where('nama',$row->nama_lengkap)->first();
            if($pegawai){
                $new_pegawai = PegawaiBiodatum::find($row->id);
                $new_pegawai->id_pegawai = $pegawai->id;
                $new_pegawai->save();
            }
        }
        $title = "Data Pegawai";
        $pegawai = PegawaiBiodatum::all();
        $programStudi = Prodi::all();
        $homebase = [];
        foreach($programStudi as $row){
            $homebase[$row->id] = $row->nama_jurusan;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/index', compact('title','pegawai','homebase','fake_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = "Tambah Pegawai";
        $jenis_kelamin = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
        ];
        $progdi = Prodi::all();
        $jenis_pegawai = PegawaiJenis::all();
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();
        $status = array('aktif','cuti','keluar','meninggal');
        $status_kawin = array("Lajang","Kawin");
        return view("admin/kepegawaian/pegawai/create2", compact('title','jenis_kelamin','progdi','jenis_pegawai','wilayah','status','status_kawin'));
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
        // /$pegawai = Pegawai::where()
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

    public function get_status(Request $request){
        $id = $request->id;
        $status = PegawaiPosisi::where('id_jenis_pegawai',$id)->get();
        return response()->json($status);
    }
}
