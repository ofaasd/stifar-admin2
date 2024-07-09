<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TbJadwalAbsensi;
use App\Models\PegawaiBiodatum;
use App\Models\TahunAjaran;

class JamkerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'id_pegawai','jam_masuk', 'jam_keluar'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "jamkerja";
            $title2 = "Jam Kerja Dosen";
            $indexed = $this->indexed;
            $ta = TahunAjaran::all();
            $pegawai = PegawaiBiodatum::all();
            return view('admin.kepegawaian.jamkerja.index', compact('ta','pegawai','title','indexed','title2'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'id_pegawai',
                3 => 'jam_masuk',
                4 => 'jam_keluar',
            ];

            $search = [];

            $totalData = TbJadwalAbsensi::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $jadwal = TbJadwalAbsensi::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $jadwal = TbJadwalAbsensi::Where('jam_masuk', 'LIKE', "%{$search}%")
                    ->orWhere('jam_keluar', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = TbJadwalAbsensi::Where('jam_masuk', 'LIKE', "%{$search}%")
                ->orWhere('jam_keluar', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($jadwal)) {
            // providing a dummy id instead of database ids
                $ids = $start;
                $list_pegawai = PegawaiBiodatum::all();
                $pegawai = [];
                foreach($list_pegawai as $row){
                    $pegawai[$row->id] = $row->nama_lengkap;
                }
                foreach ($jadwal as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['id_pegawai'] = $pegawai[$row->id_pegawai];
                    $nestedData['jam_masuk'] = $row->jam_masuk;
                    $nestedData['jam_keluar'] = $row->jam_keluar;
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
            $jamkerja = TbJadwalAbsensi::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'jam_masuk' => $request->jam_masuk,
                    'jam_keluar' => $request->jam_keluar,
                    'id_fingerprint' => $request->id_fingerprint,
                    'id_ta' => $request->id_ta
                ]
            );

            return response()->json('Updated');
        } else {
            $jamkerja = TbJadwalAbsensi::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'jam_masuk' => $request->jam_masuk,
                    'jam_keluar' => $request->jam_keluar,
                    'id_fingerprint' => $request->id_fingerprint,
                    'id_ta' => $request->id_ta
                ]
            );
            if ($jamkerja) {
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

        $jadwal = TbJadwalAbsensi::where($where)->first();

        return response()->json($jadwal);
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
        $jadwal = TbJadwalAbsensi::where('id', $id)->delete();
    }
}
