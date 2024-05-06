<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelompokMataKuliah;

class KelompokMatkulController extends Controller
{
    public $indexed = ['', 'id', 'nama_kelompok', 'nama_kelompok_eng', 'kode'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Kelompok Mata Kuliah";
            $url = "kelompok-mk";
            $indexed = $this->indexed;
            return view('admin.akademik.kelompokMatakuliah.index', compact('title','indexed', 'url'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nama_kelompok',
                3 => 'nama_kelompok_eng',
                4 => 'kode'
            ];

            $search = [];

            $totalData = KelompokMataKuliah::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $kelmk = KelompokMataKuliah::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $kelmk = KelompokMataKuliah::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama_kelompok', 'LIKE', "%{$search}%")
                    ->orWhere('nama_kelompok_eng', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = KelompokMataKuliah::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama_kelompok', 'LIKE', "%{$search}%")
                ->orWhere('nama_kelompok_eng', 'LIKE', "%{$search}%")
                ->orWhere('kode', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($kelmk)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($kelmk as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nama_kelompok'] = $row->nama_kelompok;
                    $nestedData['nama_kelompok_eng'] = $row->nama_kelompok_eng;
                    $nestedData['kode'] = $row->kode;
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
            $kelmk = KelompokMataKuliah::updateOrCreate(
                ['id' => $id],
                [
                    'nama_kelompok' => $request->nama_kelompok,
                    'nama_kelompok_eng' => $request->nama_kelompok_eng,
                    'kode' => $request->kode
                ]
            );

            return response()->json('Updated');
        } else {
            $kelmk = KelompokMataKuliah::updateOrCreate(
                ['id' => $id],
                [
                    'nama_kelompok' => $request->nama_kelompok,
                    'nama_kelompok_eng' => $request->nama_kelompok_eng,
                    'kode' => $request->kode
                ]
            );
            if ($kelmk) {
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

        $kelmk = KelompokMataKuliah::where($where)->first();

        return response()->json($kelmk);
    }
    public function destroy(string $id)
    {
        //
        $kelmk = KelompokMataKuliah::where('id', $id)->delete();
    }
}
