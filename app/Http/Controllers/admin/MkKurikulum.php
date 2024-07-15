<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\MatakuliahKurikulum;
use App\Models\MataKuliah;
use App\Models\Rumpun;
use Illuminate\Http\Request;

class MkKurikulum extends Controller
{
    public function index(){
        $title = "Matakuliah Kurikulum";
        $kurikulum = Kurikulum::get();
        $no = 1;
        return view('admin.akademik.Matakuliah.matakuliah_kurikulum', compact('title', 'kurikulum'));
    }
    public function daftarKur(Request $request){
        $title = "Matakuliah Kurikulum";
        $list_mk = MatakuliahKurikulum::leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'matakuliah_kurikulums.id_mk')
                                        ->where('matakuliah_kurikulums.id_kurikulum', $request->id_kur)->get();
        $matkul = MataKuliah::get();
        $no = 1;
        return view('admin.akademik.Matakuliah.matakuliah_kurikulum_list',
                    ['title' => $title,
                     'list_mk' => $list_mk,
                     'no' => $no,
                     'matkul' => $matkul,
                     'id_kur' => $request->id_kur
                    ]);
    }
    public function simpandaftarKur(Request $request){
        $cek = MatakuliahKurikulum::where(['id_kurikulum' => $request->id_kur, 'id_mk' => $request->id_mk])->first();
        if (!$cek) {
            MatakuliahKurikulum::create(['id_kurikulum' => $request->id_kur, 'id_mk' => $request->id_mk]);
            return json_encode(['status' => 'ok', 'kode' => 200]);
        }
        return json_encode(['status' => 'sudah ada', 'kode' => 202]);
    }
    public function updateMK(Request $request){
        $kode_matkul = $request->kode_matkul;
        $nama_matkul = $request->nama_matkul;
        $nama_inggris = $request->nama_inggris;
        // $tp = $request->tp;
        $semester = $request->semester;
        $sks_teori = $request->sks_teori;
        $sks_praktek = $request->sks_praktek;
        $status_mk = $request->status_mk;
        $status = $request->status;
        $id = $request->id;
        $update = Matakuliah::where('id', $id)->update([
            'kode_matkul' => $kode_matkul,
            'nama_matkul' => $nama_matkul,
            'nama_matkul_eng' => $nama_inggris,
            // 'tp' => $tp,
            'semester' => $semester,
            'sks_teori' => $sks_teori,
            'sks_praktek' => $sks_praktek,
            'status_mk' => $status_mk,
            'status' => $status
        ]);
        if ($update) {
            return json_encode(['status' => 'ok', 'kode' => 200]);
        }
        return json_encode(['status' => 'gagal', 'kode' => 202]);
    }
    public function destroy(string $id)
    {
        //
        $x = explode('-', $id);
        $mk = MatakuliahKurikulum::where(['id_kurikulum' => $x[1], 'id_mk' => $x[0]])->delete();
        return back();
    }
}
