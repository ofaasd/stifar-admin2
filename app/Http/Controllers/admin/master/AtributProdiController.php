<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\MasterProdiAtribut;

class AtributProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nama','file', 'url'];
    public function index(Request $request,$id=0)
    {
        //
        $id_prodi = $id;

        if (empty($request->input('length'))) {
            $title = "atribut";
            $title2 = "Atribut Program Studi";
            $prodi = Prodi::all();
            $indexed = $this->indexed;
            return view('admin.master.prodi.atribut.index', compact('prodi','title','indexed','title2','id_prodi'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nama',
                3 => 'file',
                4 => 'url',
            ];

            $search = [];

            $totalData = MasterProdiAtribut::where('id_prodi',$id)->where('keterangan',1)->count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $pt = MasterProdiAtribut::where('id_prodi',$id_prodi)
                    ->where('keterangan',1)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $pt = MasterProdiAtribut::where(['id_prodi'=>$id_prodi,'keterangan'=>1])
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterProdiAtribut::where(['id_prodi'=>$id_prodi,'keterangan'=>1])
                ->orWhere('nama', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($pt)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($pt as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['file'] = $row->file;
                    $nestedData['url'] = $row->url;
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
        //
        $id = $request->id;

        if ($id) {
            $filename = '';
            if ($request->file('file') != null) {
                $file = $request->file('file');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $tujuan_upload = 'assets/file/atribut';
                $file->move($tujuan_upload,$filename);
            }
            if(!empty($filename)){
                $pt = MasterProdiAtribut::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_prodi' => $request->id_prodi,
                        'nama' => $request->nama,
                        'file' => $filename,
                        'url' => $request->url,
                        'keterangan' => 1,
                    ]
                );
            }else{
                $pt = MasterProdiAtribut::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_prodi' => $request->id_prodi,
                        'nama' => $request->nama,
                        'url' => $request->url,
                        'keterangan' => 1,
                    ]
                );
            }


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('file') != null) {
                $file = $request->file('file');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $tujuan_upload = 'assets/file/atribut';
                $file->move($tujuan_upload,$filename);
            }
            $pt = MasterProdiAtribut::updateOrCreate(
                ['id' => $id],
                [
                    'id_prodi' => $request->id_prodi,
                    'nama' => $request->nama,
                    'file' => $filename,
                    'url' => $request->url,
                    'keterangan' => 1,
                ]
            );

            if ($pt) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Academic');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $where = ['id' => $id];

        $pt = MasterProdiAtribut::where($where)->first();

        return response()->json($pt);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $pt = MasterProdiAtribut::where('id', $id)->delete();
    }
}
