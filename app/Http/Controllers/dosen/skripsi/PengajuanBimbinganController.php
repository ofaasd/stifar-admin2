<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\MasterPembimbing;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengajuanBimbinganController extends Controller
{

    public function index(){
        $user = Auth::user();
        // $dosen = Pegawai::where('id',$user->id)->select('npp')->first();

        // $npp = $dosen->npp;
        // $data = MasterPembimbing::where('npp', $npp)->where('status',0)->get();

        dd($user);
        // $totalPengajuan = MasterPembimbing::where('npp', $npp)->where('status',0)->count();
        return view('mahasiswa.skripsi.pembimbing.index', [
            'data' => $data,
            'totalPengajuan' => $totalPengajuan
        ]);

    }
}
