<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblSoalPmb;
use App\Models\TblPilihanSoal;
use App\Models\TblKunci;

class DaftarSoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'soal', 'status'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "daftar_soal";
            $title2 = "Daftar Soal";
            $indexed = $this->indexed;
            return view('admin.admisi.daftar_soal.index',compact('title','title2','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'soal',
                3 => 'status'
            ];

            $search = [];

            $totalData = TblSoalPmb::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $soal = TblSoalPmb::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $soal = TblSoalPmb::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('soal', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = TblSoalPmb::where('id', 'LIKE', "%{$search}%")
                ->orWhere('soal', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($soal)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($soal as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['soal'] = $row->soal;
                    $nestedData['status'] = $row->is_aktif;
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

        $title = "Create Daftar Soal";
        return view('admin.admisi.daftar_soal.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $id = $request->id;

        if ($id) {
            $soal = TblSoalPmb::updateOrCreate(
                ['id' => $id],
                [
                    'soal' => $request->soal,
                    'is_aktif' => 1,
                ]
            );
            $id_soal = $soal->id;
            $array_huruf = [1=>'A','B','C','D','E'];
            for($i = 1; $i <= 5; $i++){
                $where = [
                    'id_soal'=>$id_soal,
                    'huruf' => $array_huruf[$i],
                ];
                $data = [
                    'pilihan'=>$request->input('pilihan' . $i),
                ];
                $pilihan = TblPilihanSoal::where($where)->update($data);
            }

            $data2 = [
                'id_soal' => $id_soal,
            ];
            $where2 = [
                'kunci' => $request->kunci,
            ];
            $kunci = TblKunci::where($where2)->update($data2);
            return response()->json('Updated');
        } else {
            $soal = TblSoalPmb::create(
                [
                    'soal' => $request->soal,
                    'is_aktif' => 1,
                ]
            );
            $id_soal = $soal->id;
            $array_huruf = [1=>'A','B','C','D','E'];
            for($i = 1; $i <= 5; $i++){
                $data = [
                    'id_soal'=>$id_soal,
                    'huruf' => $array_huruf[$i],
                    'pilihan'=>$request->input('pilihan' . $i),
                ];
                $pilihan = TblPilihanSoal::create($data);
            }
            $kunci = TblKunci::create([
                'id_soal' => $id_soal,
                'kunci' => $request->kunci,
            ]);
            if ($soal) {
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
        $title = "Edit Soal";
        $soal = TblSoalPmb::find($id);
        $qpilihan = TblPilihanSoal::where('id_soal',$id)->get();
        $pilihan = [];
        $key = 1;
        for($i=1; $i <= 5; $i++){
            $pilihan[$i] = '';
        }

        foreach($qpilihan as $row){
            $pilihan[$key] = $row->pilihan;
            $key++;
        }

        $kunci = TblKunci::where('id_soal',$id)->first();
        return view('admin.admisi.daftar_soal.create',compact('title','soal','pilihan','kunci','id'));
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
        $soal = TblSoalPmb::where('id', $id)->delete();
        return $soal;
    }
}
