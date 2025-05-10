<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;

class NilaiSusulanController extends Controller
{
    //
    public function index(Request $request){
        $ta = TahunAjaran::where('status','Tidak Aktif')->get();
        $mhs = Mahasiswa::all();
        $title = "Kuesioner Mahasiswa";
        return view('admin.akademik.nilai_susulan.index', compact('title','ta','mhs'));
    }
}
