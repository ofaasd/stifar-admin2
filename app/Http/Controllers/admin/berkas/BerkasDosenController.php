<?php

namespace App\Http\Controllers\admin\berkas;

use App\Models\Prodi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\PegawaiBiodatum;
use App\Http\Controllers\Controller;

class BerkasDosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawai_biodata = PegawaiBiodatum::all();
        foreach($pegawai_biodata as $row){
            $pegawai = Pegawai::where('nama',$row->nama_lengkap)->first();
            if($pegawai){
                $new_pegawai = PegawaiBiodatum::find($row->id);
                $new_pegawai->id_pegawai = $pegawai->id;
                $new_pegawai->save();
            }
        }
        $title = "Berkas Dosen";
        $pegawai = PegawaiBiodatum::all();
        $programStudi = Prodi::all();
        $fake_id = 0;
        return view('admin.berkas.dosen.index', compact('title','pegawai','fake_id'));
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
