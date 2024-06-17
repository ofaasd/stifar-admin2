<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterPt;
use URL;

class PTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'logo','nama', 'alamat', 'notelp', 'email'];
    public $indexed2 = ['', 'id', 'nama','file', 'url'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "pt";
            $title2 = "Perguruan Tinggi";
            $indexed = $this->indexed;
            return view('admin.master.pt.index', compact('title','indexed','title2'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'logo',
                3 => 'nama',
                4 => 'alamat',
                5 => 'notelp',
                6 => 'email',
            ];

            $search = [];

            $totalData = MasterPt::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $pt = MasterPt::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $pt = MasterPt::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('alamat', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterPt::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama', 'LIKE', "%{$search}%")
                ->orWhere('alamat', 'LIKE', "%{$search}%")
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
                    $nestedData['alamat'] = $row->alamat;
                    $nestedData['logo'] = $row->logo;
                    $nestedData['notelp'] = $row->notelp;
                    $nestedData['email'] = $row->email;
                    $nestedData['url'] = '';
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
            if ($request->file('logo') != null) {
                $photo = $request->file('logo');
                $filename = date('YmdHi') . $photo->getClientOriginalName();
                $tujuan_upload = 'assets/images/logo/upload';
                $photo->move($tujuan_upload,$filename);
            }
            if(!empty($filename)){
                $pt = MasterPt::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'alamat' => $request->alamat,
                        'lat' => $request->lat,
                        'lng' => $request->lng,
                        'notelp' => $request->notelp,
                        'email' => $request->email,
                        'deskripsi' => $request->deskripsi,
                        'logo' => $filename
                    ]
                );
            }else{
                $pt = MasterPt::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'alamat' => $request->alamat,
                        'lat' => $request->lat,
                        'lng' => $request->lng,
                        'notelp' => $request->notelp,
                        'email' => $request->email,
                        'deskripsi' => $request->deskripsi
                    ]
                );
            }


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('logo')) {
                $photo = $request->file('logo');
                $filename = date('YmdHi') . $photo->getClientOriginalName();
                $tujuan_upload = 'assets/images/logo/upload';
                $photo->move($tujuan_upload,$filename);
            }
            $pt = MasterPt::updateOrCreate(
                ['id' => $id],
                [
                    'nama' => $request->nama,
                    'alamat' => $request->alamat,
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                    'notelp' => $request->notelp,
                    'email' => $request->email,
                    'deskripsi' => $request->deskripsi,
                    'logo' => $filename,
                ]
            );

            if ($pt) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create PT');
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

        $pt = MasterPt::where($where)->first();

        return response()->json($pt);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $pt = MasterPt::where('id', $id)->delete();
    }

    public function atribut(Request $request){
        if (empty($request->input('length'))) {
            $title = "pt/atribut";
            $title2 = "Perguruan Tinggi";
            $indexed = $this->indexed;
            $link = 'atribut';
            return view('admin.master.pt.index', compact('title','indexed','title2','link'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'logo',
                3 => 'nama',
                4 => 'alamat',
                5 => 'notelp',
                6 => 'email',
            ];

            $search = [];

            $totalData = MasterPt::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $pt = MasterPt::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $pt = MasterPt::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('alamat', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterPt::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama', 'LIKE', "%{$search}%")
                ->orWhere('alamat', 'LIKE', "%{$search}%")
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
                    $nestedData['alamat'] = $row->alamat;
                    $nestedData['logo'] = $row->logo;
                    $nestedData['notelp'] = $row->notelp;
                    $nestedData['email'] = $row->email;
                    $nestedData['url'] = URL::to('admin/masterdata/pt/atribut/detail/' . $row->id);
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
    public function renstra(Request $request){
        if (empty($request->input('length'))) {
            $title = "pt";
            $title2 = "Perguruan Tinggi";
            $indexed = $this->indexed;
            $link = 'renstra';
            return view('admin.master.pt.index', compact('title','indexed','title2','link'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'logo',
                3 => 'nama',
                4 => 'alamat',
                5 => 'notelp',
                6 => 'email',
            ];

            $search = [];

            $totalData = MasterPt::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $pt = MasterPt::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $pt = MasterPt::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('alamat', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterPt::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama', 'LIKE', "%{$search}%")
                ->orWhere('alamat', 'LIKE', "%{$search}%")
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
                    $nestedData['alamat'] = $row->alamat;
                    $nestedData['logo'] = $row->logo;
                    $nestedData['notelp'] = $row->notelp;
                    $nestedData['email'] = $row->email;
                    $nestedData['url'] = URL::to('masterdata/pt/renstra/' . $row->id);
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
