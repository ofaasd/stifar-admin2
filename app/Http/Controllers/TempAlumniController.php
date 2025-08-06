<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TempAlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Cetak Ijazah.";
        $mhs = Mahasiswa::get();
        $no = 1;
        $prodi = Prodi::all();
        $jumlah = [];
        $nama = [];

        foreach($prodi as $row){
            $jumlah[$row->id] = Mahasiswa::where('id_program_studi',$row->id)->count();
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
        }
        $isAlumni = true;
        return view('mahasiswa.daftar', compact('title', 'mhs', 'no', 'prodi','jumlah','nama','isAlumni'));
    }

    public function get_mhs(Request $request)
    {
        $id = $request->id;
        if($id == 0){
            $ta = TahunAjaran::where("status", "Aktif")->first();
            $mhs = Mahasiswa::select(
                'mahasiswa.*',
                'pegawai_biodata.nama_lengkap as dosenWali',
                'mahasiswa_berkas_pendukung.foto_sistem'
            )
            ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
            ->leftJoin('mahasiswa_berkas_pendukung', function($join) use ($ta) {
                $join->on('mahasiswa_berkas_pendukung.nim', '=', 'mahasiswa.nim')
                    ->where('mahasiswa_berkas_pendukung.id_ta', '=', $ta->id);
            })
            ->get()
            ->map(function ($item) {
                $item->nimEnkripsi = Crypt::encryptString($item->nim . "stifar");
                return $item;
            });

            $no = 1;
            $prodi = Prodi::all();
            $jumlah = [];
            $nama = [];

            foreach($prodi as $row){
                $jumlah[$row->id] = Mahasiswa::where('id_program_studi',$row->id)->count();
                $nama_prodi = explode(' ',$row->nama_prodi);
                $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
            }
            $isAlumni = true;
            return view('mahasiswa._table_alumni_mhs', compact('mhs', 'no', 'prodi', 'jumlah', 'nama', 'isAlumni'));
        }else{
            $mhs = Mahasiswa::where('id_program_studi',$id)->get()->map(function ($item) {
                $item->nimEnkripsi = Crypt::encryptString($item->nim . "stifar");
                return $item;
            });

            $no = 1;
            $prodi = Prodi::all();
            $jumlah = [];
            $nama = [];

            foreach($prodi as $row){
                $jumlah[$row->id] = Mahasiswa::where('id_program_studi',$row->id)->count();
                $nama_prodi = explode(' ',$row->nama_prodi);
                $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
            }
            $isAlumni = true;
            return view('mahasiswa._table_alumni_mhs', compact('mhs', 'no', 'prodi', 'jumlah', 'nama', 'isAlumni'));
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
