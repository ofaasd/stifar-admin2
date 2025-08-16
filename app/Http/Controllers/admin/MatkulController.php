<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\KelompokMataKuliah;
use App\Models\Rumpun;
use App\Models\Prodi;
use Auth;

class MatkulController extends Controller
{
    // public $indexed = ['', 'id', 'kode_matkul', 'nama_matkul', 'nama_matkul_eng', 'jumlah_sks', 'semester', 'tp', 'kel_mk', 'rumpun', 'id_prodi', 'status'];
    public function index(Request $request)
    {
        //
        $title = "Master Matakuliah";
        $mk = MataKuliah::select('mata_kuliahs.*')->leftJoin('kelompok_mata_kuliahs', 'kelompok_mata_kuliahs.id','=','mata_kuliahs.kel_mk')
                          ->leftJoin('rumpuns', 'rumpuns.id','=','mata_kuliahs.rumpun')->get();
        $list_mk = [];
        foreach($mk as $row){
            $list_mk[$row->id] = $row->nama_matkul;
        }
        $kelompok = KelompokMatakuliah::get();
        $rumpun = Rumpun::get();
        $no = 1;
        return view('admin.akademik.Matakuliah.index', compact('title', 'mk', 'kelompok', 'rumpun', 'no', 'list_mk'));
    }

    public function simpanMK(Request $request)
    {
        $filename = '';
        if ($request->file('rps') != null) {
            $file = $request->file('rps');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $tujuan_upload = 'assets/file/rps';
            $file->move($tujuan_upload,$filename);
        }
        if(!empty($filename)){
            $mk = MataKuliah::create(
                [
                    'kode_matkul' => $request->kode_matkul,
                    'nama_matkul' => $request->nama_matkul,
                    'nama_matkul_eng' => $request->nama_inggris,
                    'kel_mk' => $request->kelompok,
                    'semester' => $request->semester,
                    'sks_teori' => $request->sks_teori,
                    'sks_praktek' => $request->sks_praktek,
                    'status_mk' => $request->status_mk,
                    'rumpun' => $request->rumpun,
                    'prasyarat1' => $request->prasyarat1,
                    'rps' => $filename,
                    'rps_log' => date('Y-m-d H:i:s'),
                    'status' => $request->status
                ]
            );
        }else{
            $mk = MataKuliah::create(
                [
                    'kode_matkul' => $request->kode_matkul,
                    'nama_matkul' => $request->nama_matkul,
                    'nama_matkul_eng' => $request->nama_inggris,
                    'kel_mk' => $request->kelompok,
                    'semester' => $request->semester,
                    'sks_teori' => $request->sks_teori,
                    'sks_praktek' => $request->sks_praktek,
                    'status_mk' => $request->status_mk,
                    'prasyarat1' => $request->prasyarat1,
                    'rumpun' => $request->rumpun,
                    'status' => $request->status
                ]
            );
        }
        if ($mk) {
            return json_encode(['status' => 'ok', 'kode' => 200]);
        } else {
            return json_encode(['status' => 'fail', 'kode' => 201]);
        }
    }
    public function updateMK(Request $request){
        $filename = '';
        if ($request->file('rps') != null) {
            $file = $request->file('rps');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $tujuan_upload = 'assets/file/rps';
            $file->move($tujuan_upload,$filename);
        }
        if(!empty($filename)){
            $mk = MataKuliah::where('id', $request->id)->update([
                'kode_matkul' => $request->kode_matkul,
                'nama_matkul' => $request->nama_matkul,
                'nama_matkul_eng' => $request->nama_inggris,
                'kel_mk' => $request->kelompok,
                'semester' => $request->semester,
                'sks_teori' => $request->sks_teori,
                'sks_praktek' => $request->sks_praktek,
                'status_mk' => $request->status_mk,
                'rumpun' => $request->rumpun,
                'rps' => $filename,
                'rps_log' => date('Y-m-d H:i:s'),
                'prasyarat1' => $request->prasyarat1,
                'status' => $request->status
            ]);
        }else{
            $mk = MataKuliah::where('id', $request->id)->update([
                'kode_matkul' => $request->kode_matkul,
                'nama_matkul' => $request->nama_matkul,
                'nama_matkul_eng' => $request->nama_inggris,
                'kel_mk' => $request->kelompok,
                'semester' => $request->semester,
                'sks_teori' => $request->sks_teori,
                'sks_praktek' => $request->sks_praktek,
                'status_mk' => $request->status_mk,
                'rumpun' => $request->rumpun,
                'prasyarat1' => $request->prasyarat1,
                'status' => $request->status
            ]);
        }
        if ($mk) {
            return json_encode(['status' => 'ok', 'kode' => 200]);
        } else {
            return json_encode(['status' => $mk, 'kode' => 201]);
        }
    }
    public function destroy(string $id)
    {

        $mk = MataKuliah::where('id', $id)->delete();

        redirect('admin/masterdata/matakuliah');
    }
}
