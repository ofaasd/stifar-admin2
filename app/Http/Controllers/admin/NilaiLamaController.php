<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NilaiLama;
use App\Models\Prodi;
use App\Models\TahunAjaran;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;

class NilaiLamaController extends Controller
{
    //
    public $indexed = ['', 'id', 'ta_id','kd_matkul', 'nim','nama','uts','uas','nilai','nilai_huruf'];
    public function index(Request $request,$id=0,$id_ta=0)
    {
        //
        $id_prodi = $id;

        if (empty($request->input('length'))) {
            $title = "nilai_lama";
            $title2 = "Import Nilai Lama";
            $prodi = Prodi::all();
            $indexed = $this->indexed;
            $ta = TahunAjaran::all();
            $nim = Mahasiswa::where('id_program_studi',$id_prodi)->get();
            $matakuliah = MataKuliah::all();
            return view('admin.nilai_lama.index', compact('matakuliah','ta','prodi','title','indexed','title2','id_prodi','id_ta','nim'));
        }else{
            $columns = [
                1 =>'id', 'ta_id','kd_matkul', 'nim','nama','uts','uas','nilai','nilai_huruf'
            ];

            $search = [];

            $totalData = NilaiLama::where('id_prodi',$id)->count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $pt = NilaiLama::where('id_prodi',$id_prodi)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $pt = NilaiLama::where(['id_prodi'=>$id_prodi])
                    ->orWhere('nim', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = NilaiLama::where(['id_prodi'=>$id_prodi])
                ->orWhere('nim', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($pt)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($pt as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['ta_id'] = $row->ta_id;
                    $nestedData['kd_matkul'] = $row->kode_mk;
                    $nestedData['nim'] = $row->nim;
                    $nestedData['nama'] = $row->nim;
                    $nestedData['uts'] = $row->uts;
                    $nestedData['uas'] = $row->uas;
                    $nestedData['nilai'] = $row->nilai;
                    $nestedData['nilai_huruf'] = $row->nilai_huruf;
                    $data[] = $nestedData;
                }
            }
            if ($data) {
                return response()->json([
                  'draw' => intval($request->input('draw')),
                  'recordsTotal' => intval($totalData),
                  'recordsFiltered' => intval($totalFiltered),
                  'code' => 200,
                  'data' => $data,
                ]);
              } else {
                return response()->json([
                  'message' => 'Internal Server Error',
                  'code' => 500,
                  'data' => [],
                ]);
              }
        }

    }
}
