<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AsalSekolah;

class AsalSekolahController extends Controller
{
    //
    public $indexed = ['', 'id', 'npsn', 'nama', 'alamat', 'telepon', 'email'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Sekolah";
            $indexed = $this->indexed;
            return view('admin.master.asal_sekolah.index', compact('title','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'npsn',
                3 => 'nama',
                4 => 'alamat',
                5 => 'telepon',
                6 => 'email',
            ];

            $search = [];

            $totalData = AsalSekolah::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $ruang = AsalSekolah::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $ruang = AsalSekolah::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('npsn', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('alamat', 'LIKE', "%{$search}%")
                    ->orWhere('telepon', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = AsalSekolah::where('id', 'LIKE', "%{$search}%")
                ->orWhere('npsn', 'LIKE', "%{$search}%")
                ->orWhere('nama', 'LIKE', "%{$search}%")
                ->orWhere('alamat', 'LIKE', "%{$search}%")
                ->orWhere('telepon', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($ruang)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($ruang as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['npsn'] = $row->npsn;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['alamat'] = $row->alamat;
                    $nestedData['telepon'] = $row->telepon;
                    $nestedData['email'] = $row->email;
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
            $asalsekolah = AsalSekolah::updateOrCreate(
                ['id' => $id],
                ['npsn' => $request->npsn, 'nama' => $request->nama, 'alamat' => $request->alamat, 'telepon' => $request->telepon, 'email' => $request->email]
            );

            return response()->json('Updated');
        } else {
            $asalsekolah = AsalSekolah::updateOrCreate(
                ['id' => $id],
                ['npsn' => $request->npsn, 'nama' => $request->nama, 'alamat' => $request->alamat, 'telepon' => $request->telepon, 'email' => $request->email]
            );
            if ($asalsekolah) {
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

        $asalsekolah = AsalSekolah::where($where)->first();

        return response()->json($asalsekolah);
    }
    public function destroy(string $id)
    {
        //
        $asalsekolah = AsalSekolah::where('id', $id)->delete();
    }
}
