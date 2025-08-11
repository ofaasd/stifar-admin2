<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\MatakuliahKurikulum;
use App\Models\MataKuliah;
use App\Models\Rumpun;
use App\Models\TahunAjaran;
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
    public function get_table(Request $request){
        $list_mk = MatakuliahKurikulum::leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'matakuliah_kurikulums.id_mk')
                                        ->where('matakuliah_kurikulums.id_kurikulum', $request->id_kur)->get();
        $matkul = MataKuliah::get();
        $no = 1;
        return view('admin.akademik.Matakuliah._table_mk_kurikulum',
                    [   'list_mk' => $list_mk,
                        'no' => $no,
                        'matkul' => $matkul,
                        'id_kur' => $request->id_kur
                    ]);
    }
    public function copy_mk($id){
        $curr_kurikulum = Kurikulum::find($id);
        // echo $mk->thn_ajar;
        $ta = TahunAjaran::find($curr_kurikulum->thn_ajar);
        $kode_ta = (string)$ta->kode_ta;
        $kode_periode = $kode_ta[-1];
        $ta_number = (int)substr($kode_ta,0,4);
        $ta_before = (string)($ta_number-1) . $kode_periode;
        $latest_ta = TahunAjaran::where('kode_ta',$ta_before)->first();
        $kurikulum = Kurikulum::where('thn_ajar',$latest_ta->id)->where('progdi',$curr_kurikulum->progdi)->where('status','Aktif')->first();
        echo $kurikulum->id;
        echo "<br />";
        $delete_all = MatakuliahKurikulum::where('matakuliah_kurikulums.id_kurikulum', $id)->delete();
        $list_mk = MatakuliahKurikulum::where('matakuliah_kurikulums.id_kurikulum', $kurikulum->id)->get();
        foreach($list_mk as $row){
            $data = [
                'id_kurikulum' => $id,
                'id_mk' => $row->id_mk,
            ];
            MatakuliahKurikulum::create($data);
        }
        return back();
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
