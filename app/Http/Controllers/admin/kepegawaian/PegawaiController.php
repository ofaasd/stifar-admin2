<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\PegawaiBiodatum;
use App\Models\PegawaiGolongan;
use App\Models\PegawaiJabatanStruktural;
use App\Models\PegawaiJabatanFungsional;
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
                $new_pegawai->Save();
            }
        }
        // $title = "Data Pegawai";
        // $pegawai = PegawaiBiodatum::all();
        // $programStudi = Prodi::all();
        // $homebase = [];
        // foreach($programStudi as $row){
        //     $homebase[$row->id] = $row->nama_jurusan;
        // }
        // $fake_id = 0;
        // return view('admin/kepegawaian/pegawai/index', compact('title','pegawai','homebase','fake_id'));
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
}
