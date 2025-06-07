<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Krs;
use App\Models\TahunAjaran;

class MonitoringKrsController extends Controller
{
    //
    public function index(){
        $ta = TahunAjaran::all();
        $title = "Monitoring KRS";
        return view('admin.akademik.monitoring.index', compact('title','ta'));
    }
}
