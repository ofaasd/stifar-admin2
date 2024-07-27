<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;
use App\Models\MasterKeuanganMh;

class KeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $ta = TahunAjaran::where('status','Aktif')->first();
        $keuangan = MasterKeuanganMh::where('id_tahun_ajaran',$ta->id);
        $jumlah_keuangan = $keuangan->count();
        $list_keuangan = $keuangan->get();
        $title = "Keuangan Mahasiswa";
        return view('admin.keuangan.index', compact('title','ta', 'list_keuangan', 'jumlah_keuangan'));
    }
    public function generate_mhs(){
        $ta = TahunAjaran::where('status','Aktif')->first();
        $mhs = Mahasiswa::all();
        foreach($mhs as $row){
            MasterKeuanganMh::create([
                'id_mahasiswa' => $row->id,
                'id_tahun_ajaran' => $ta->id,
                'krs' => 1,
                'uts' => 1,
                'uas' => 1,
            ]);
        } 
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
