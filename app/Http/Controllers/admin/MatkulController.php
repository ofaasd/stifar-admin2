<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\KelompokMataKuliah;
use App\Models\Rumpun;
use App\Models\Prodi;

class MatkulController extends Controller
{
    public $indexed = ['', 'id', 'kode_matkul', 'nama_matkul', 'nama_matkul_eng', 'jumlah_sks', 'semester', 'tp', 'kel_mk', 'rumpun', 'id_prodi', 'status'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Mata Kuliah";
            $url = "matakuliah";
            $indexed = $this->indexed;
            $kelmk = KelompokMatakuliah::orderBy('id')->get();
            $rumpun = Rumpun::orderBy('id')->get();
            $prodi = Prodi::orderBy('id')->get();
            return view('admin.akademik.Matakuliah.index', compact('title','indexed', 'url', 'kelmk', 'rumpun', 'prodi'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode_matkul',
                3 => 'nama_matkul',
                4 => 'nama_matkul_eng',
                5 => 'jumlah_sks',
                6 => 'semester',
                7 => 'tp',
                8 => 'kel_mk',
                9 => 'rumpun',
                10 => 'id_prodi',
                11 => 'status'
            ];

            $search = [];

            $totalData = MataKuliah::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $mk = MataKuliah::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $mk = MataKuliah::select('mata_kuliahs.*, kelompok_mata_kuliahs.nama_kelompok, rumpuns.nama_rumpun, prodis.jenjang, prodis.nama_prodi')
                    ->leftJoin('kelompok_mata_kuliahs', 'mata_kuliahs.kel_mk', '=', 'kelompok_mata_kuliahs.id')
                    ->leftJoin('rumpuns', 'mata_kuliahs.rumpun', '=', 'rumpuns.id')
                    ->leftJoin('prodis', 'mata_kuliahs.id_prodi', '=', 'prodis.id')
                    ->where('mata_kuliahs.id', 'LIKE', "%{$search}%")
                    ->orWhere('mata_kuliahs.kode_matkul', 'LIKE', "%{$search}%")
                    ->orWhere('mata_kuliahs.nama_matkul', 'LIKE', "%{$search}%")
                    ->orWhere('mata_kuliahs.nama_matkul_eng', 'LIKE', "%{$search}%")
                    ->orWhere('mata_kuliahs.jumlah_sks', 'LIKE', "%{$search}%")
                    ->orWhere('mata_kuliahs.semester', 'LIKE', "%{$search}%")
                    ->orWhere('mata_kuliahs.tp', 'LIKE', "%{$search}%")
                    ->orWhere('kelompok_mata_kuliahs.nama_kelompok', 'LIKE', "%{$search}%")
                    ->orWhere('rumpuns.nama_rumpun', 'LIKE', "%{$search}%")
                    ->orWhere('prodis.jenjang', 'LIKE', "%{$search}%")
                    ->orWhere('prodis.nama_prodi', 'LIKE', "%{$search}%")
                    ->orWhere('mata_kuliahs.status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MataKuliah::select('mata_kuliahs.*, kelompok_mata_kuliahs.nama_kelompok, rumpuns.nama_rumpun, prodis.jenjang, prodis.nama_prodi')
                ->leftJoin('kelompok_mata_kuliahs', 'mata_kuliahs.kel_mk', '=', 'kelompok_mata_kuliahs.id')
                ->leftJoin('rumpuns', 'mata_kuliahs.rumpun', '=', 'rumpuns.id')
                ->leftJoin('prodis', 'mata_kuliahs.id_prodi', '=', 'prodis.id')
                ->where('mata_kuliahs.id', 'LIKE', "%{$search}%")
                ->orWhere('mata_kuliahs.kode_matkul', 'LIKE', "%{$search}%")
                ->orWhere('mata_kuliahs.nama_matkul', 'LIKE', "%{$search}%")
                ->orWhere('mata_kuliahs.nama_matkul_eng', 'LIKE', "%{$search}%")
                ->orWhere('mata_kuliahs.jumlah_sks', 'LIKE', "%{$search}%")
                ->orWhere('mata_kuliahs.semester', 'LIKE', "%{$search}%")
                ->orWhere('mata_kuliahs.tp', 'LIKE', "%{$search}%")
                ->orWhere('kelompok_mata_kuliahs.nama_kelompok', 'LIKE', "%{$search}%")
                ->orWhere('rumpuns.nama_rumpun', 'LIKE', "%{$search}%")
                ->orWhere('prodis.jenjang', 'LIKE', "%{$search}%")
                ->orWhere('prodis.nama_prodi', 'LIKE', "%{$search}%")
                ->orWhere('mata_kuliahs.status', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($mk)) {
            // providing a dummy id instead of database ids
                $ids = $start;
                
                foreach ($mk as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['kode_matkul'] = $row->kode_matkul;
                    $nestedData['nama_matkul'] = $row->nama_matkul;
                    $nestedData['nama_matkul_eng'] = $row->nama_matkul;
                    $nestedData['jumlah_sks'] = $row->jumlah_sks;
                    $nestedData['semester'] = $row->semester;
                    $nestedData['tp'] = $row->tp;
                    $nestedData['kel_mk'] = $row->nama_kelompok;
                    $nestedData['rumpun'] = $row->nama_rumpun;
                    $nestedData['id_prodi'] = $row->jenjang.'-'.$row->nama_prodi;
                    $nestedData['status'] = $row->status;
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

    public function store(Request $request)
    {
        //
        $id = $request->id;

        if ($id) {
            $mk = MataKuliah::updateOrCreate(
                ['id' => $id],
                [
                    'kode_matkul' => $request->kode_matkul,
                    'nama_matkul' => $request->nama_matkul,
                    'nama_matkul_eng' => $request->nama_matkul_eng,
                    'jumlah_sks' => $request->jumlah_sks,
                    'semester' => $request->semester,
                    'tp' => $request->tp,
                    'kel_mk' => $request->kel_mk,
                    'rumpun' => $request->rumpun,
                    'id_prodi' => $request->prodi,
                    'status' => $request->status
                ]
            );

            return response()->json('Updated');
        } else {
            $mk = MataKuliah::updateOrCreate(
                ['id' => $id],
                [
                    'kode_matkul' => $request->kode_matkul,
                    'nama_matkul' => $request->nama_matkul,
                    'nama_matkul_eng' => $request->nama_matkul_eng,
                    'jumlah_sks' => $request->jumlah_sks,
                    'semester' => $request->semester,
                    'tp' => $request->tp,
                    'kel_mk' => $request->kel_mk,
                    'rumpun' => $request->rumpun,
                    'id_prodi' => $request->prodi,
                    'status' => $request->status
                ]
            );
            if ($mk) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Academic');
            }
        }
    }
    public function edit(string $id)
    {
        //
        $where = ['id' => $id];

        $mk = MataKuliah::where($where)->first();

        return response()->json($mk);
    }
    public function destroy(string $id)
    {
        //
        $mk = MataKuliah::where('id', $id)->delete();
    }
}
