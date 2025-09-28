<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingKeuangan;
use App\Models\JenisKeuangan;
use App\Models\TahunAjaran;
use App\Models\Prodi;

class SettingKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $title = "setting_keuangan";
        $title2 = "Setting Keuangan";
        $ta = TahunAjaran::where('status','Aktif')->first();
        $ta_all = TahunAjaran::orderBy('id','desc')->get();
        $jenis = JenisKeuangan::all();
        $prodi = Prodi::all();
        $setting_keuangan = [];
        $tahun_ajaran = $ta->id;
        $angkatan = date('Y');
        $gelombang = 1;
        $alumni = 1;
        foreach($prodi as $pro){
            foreach($jenis as $jen){
                $setting_keuangan[$pro->id][$jen->id] = 0;
            }
        }
        if(empty($request->tahun_ajaran)){
            $SettingKeuangan = SettingKeuangan::where('id_tahun',$ta->id);
        }else{            
            $tahun_ajaran = $request->tahun_ajaran;
            $alumni = $request->alumni;
            $gelombang = $request->gelombang;
            $angkatan = $request->angkatan;
            $ta = TahunAjaran::where('id',$tahun_ajaran)->first();
            $SettingKeuangan = SettingKeuangan::where([
                'id_tahun'=>$ta->id,
                'alumni' => $alumni,
                'gelombang' => $gelombang,
                'angkatan' => $angkatan,
            ]);
        }
        if($SettingKeuangan->count() > 0){
            foreach($prodi as $pro){
                foreach($jenis as $jen){
                    $setting_keuangan[$pro->id][$jen->id] = SettingKeuangan::where(['id_tahun'=>$ta->id,'id_prodi'=>$pro->id,'id_jenis'=>$jen->id,'gelombang'=>$gelombang,'alumni'=>$alumni,'angkatan'=>$angkatan])->first()->jumlah ?? 0;
                }
            }
        }
        return view('admin.keuangan.setting_keuangan.index', compact('title', 'title2', 'SettingKeuangan','setting_keuangan', 'prodi','ta','jenis','ta_all','tahun_ajaran','gelombang','alumni','angkatan'));
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
        // Validasi data
        $prodi = $request->prodi;
        $jenis = $request->jenis;
        $jumlah = $request->setting_keuangan;
    
        $ta = $request->tahun_ajaran;
        $alumni = $request->alumni;
        $angkatan = $request->angkatan;
        $gelombang = $request->gelombang;
        foreach($prodi as $key=>$value){
            $cek = SettingKeuangan::where("id_prodi",$value)->where('id_jenis',$jenis[$key])->where('id_tahun',$ta)->where('alumni',$alumni)->where('gelombang',$gelombang)->where('angkatan',$angkatan);
            if($cek->count() == 0){
                $create = SettingKeuangan::create(
                    [
                        'id_prodi' => $value,
                        'id_jenis' => $jenis[$key],
                        'id_tahun' => $ta,
                        'alumni' => $alumni,
                        'gelombang' => $gelombang,
                        'jumlah' => $jumlah[$key] ?? 0,
                        'angkatan' => $angkatan
                    ]
                );
            }else{
                $set = $cek->first();
                $update = SettingKeuangan::find($set->id);
                $update->jumlah = $jumlah[$key] ?? 0;
                $update->save();
            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(SettingKeuangan $SettingKeuangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gedung = SettingKeuangan::find($id);

        if ($gedung) {
            return response()->json($gedung);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Aset Gedung not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SettingKeuangan $SettingKeuangan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gedung = SettingKeuangan::where('id', $id)->delete();
    }
}
