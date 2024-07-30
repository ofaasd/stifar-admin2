<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterProdiAkreditasi;
use App\Models\MasterPt;
use App\Models\Prodi;

class ProdiAkreditasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nilai','status', 'capaian', 'lembaga', 'file','tahun'];
    public function index(Request $request,$id=0)
    {
        //
        $id_prodi = $id;

        if (empty($request->input('length'))) {
            $title = "akreditasi";
            $title2 = "Akreditasi Program Studi";
            $prodi = Prodi::all();
            $indexed = $this->indexed;
            return view('admin.master.prodi.akreditasi.index', compact('prodi','title','indexed','title2','id_prodi'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nilai',
                3 => 'status',
                4 => 'capaian',
                5 => 'lembaga',
                6 => 'file',
                7 => 'tahun',
            ];

            $search = [];

            $totalData = MasterProdiAkreditasi::where('id_prodi',$id)->count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $pt = MasterProdiAkreditasi::where('id_prodi',$id_prodi)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $pt = MasterProdiAkreditasi::where(['id_prodi'=>$id_prodi])
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterProdiAkreditasi::where(['id_prodi'=>$id_prodi])
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
                    $nestedData['nilai'] = $row->nilai;
                    $nestedData['status'] = $row->status;
                    $nestedData['capaian'] = $row->capaian;
                    $nestedData['lembaga'] = $row->lembaga;
                    $nestedData['file'] = $row->file;
                    $nestedData['tahun'] = $row->tahun;
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
                $tujuan_upload = 'assets/file/akreditasi';
                $file->move($tujuan_upload,$filename);
            }
            if(!empty($filename)){
                $pt = MasterProdiAkreditasi::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_prodi' => $request->id_prodi,
                        'nilai' => $request->nilai,
                        'status' => $request->status,
                        'capaian' => $request->capaian,
                        'lembaga' => $request->lembaga,
                        'tahun' => $request->tahun,
                        'file' => $filename,
                    ]
                );
            }else{
                $pt = MasterProdiAkreditasi::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_prodi' => $request->id_prodi,
                        'nilai' => $request->nilai,
                        'status' => $request->status,
                        'capaian' => $request->capaian,
                        'lembaga' => $request->lembaga,
                        'tahun' => $request->tahun
                    ]
                );
            }


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('file') != null) {
                $file = $request->file('file');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $tujuan_upload = 'assets/file/akreditasi';
                $file->move($tujuan_upload,$filename);
            }
            $pt = MasterProdiAkreditasi::updateOrCreate(
                ['id' => $id],
                [
                    'id_prodi' => $request->id_prodi,
                    'nilai' => $request->nilai,
                    'status' => $request->status,
                    'capaian' => $request->capaian,
                    'lembaga' => $request->lembaga,
                    'tahun' => $request->tahun,
                    'url' => $request->url,
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

        $pt = MasterProdiAkreditasi::where($where)->first();

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
        $pt = MasterProdiAkreditasi::where('id', $id)->delete();
    }
}
