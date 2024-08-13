<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){
        return view('index');
    }
    public function mhs(){
        return view('index_mhs');
    }
    public function dosen(){
        return view('index_pegawai');
    }
}
