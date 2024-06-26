<?php

namespace App\Http\Controllers\pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PegawaiBiodatum;

class RiwayatPegawaiController extends Controller
{
    //
    public function index(){
        $user_id = Auth::user()->id;
        $pegawai = PegawaiBiodatum::where('user_id',$user_id)->first();
        $id = $pegawai->id;
        $title = "Riwayat Pegawai";
        return view("pegawai/riwayat/index", compact('title','id'));
    }
}
