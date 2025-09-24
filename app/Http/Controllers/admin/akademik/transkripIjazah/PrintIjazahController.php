<?php

namespace App\Http\Controllers\admin\akademik\transkripIjazah;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Prodi;
use Illuminate\Http\Request;

class PrintIjazahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Print Ijazah.";
        $mhs = Alumni::get();
        $no = 1;
        $prodi = Prodi::all();
        $jumlah = [];
        $nama = [];

        foreach($prodi as $row){
            $jumlah[$row->id] = Alumni::where('id_program_studi',$row->id)->count();
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        $isAlumni = true;
        return view('mahasiswa.daftar', compact('title', 'mhs', 'no', 'prodi','jumlah','nama','isAlumni'));
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
