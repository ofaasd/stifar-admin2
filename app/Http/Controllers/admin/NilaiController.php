<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NilaiLama;
use App\Models\Prodi;
use App\Models\TahunAjaran;
use App\Models\MataKuliah;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

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

    public function nilai_lama(Request $request){
        $id_prodi = 1;
        $prodi = Prodi::all();
        $nilai = NilaiLama::all();
        $ta = TahunAjaran::all();
        $matakuliah = MataKuliah::all();
        $list_ta = [];
        foreach($ta as $row){
            $list_ta[$row->id] = $row->kode_ta;
        }

        $title = "Import Nilai Lama";

        return view('admin.nilai_lama.index',compact('title','matakuliah','prodi','ta','nilai'));
    }
}
