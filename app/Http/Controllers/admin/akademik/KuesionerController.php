<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;

class KuesionerController extends Controller
{
    //
    public function index(){
        $ta = TahunAjaran::all();
        $title = "Kuesioner Mahasiswa";
        return view('admin.akademik.kuesioner.index', compact('title','ta'));
    }
}
