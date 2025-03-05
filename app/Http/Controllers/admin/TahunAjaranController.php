<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Jadwal;
use App\Models\Krs;
use App\Models\JadwalsArsip;
use App\Models\KrsArsip;
use App\Models\PertemuanArsip;
use App\Models\MasterNilaiArsip;
use App\Models\AbsensiModelArsip;
use App\Models\KontrakKuliahArsip;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public $indexed = ['', 'id', 'kode_ta', 'tgl_awal','tgl_awal_kuliah', 'tgl_akhir', 'status','keterangan'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Tahun Ajaran";
            $url = "ta";
            $indexed = $this->indexed;
            return view('admin.master.tahun_ajaran.index', compact('title','indexed', 'url'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode_ta',
                3 => 'tgl_awal',
                4 => 'tgl_awal_kuliah',
                5 => 'tgl_akhir',
                6 => 'status',
                7 => 'keterangan'
            ];

            $search = [];

            $totalData = TahunAjaran::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $ta = TahunAjaran::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $ta = TahunAjaran::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode_ta', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_awal', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_akhir', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = TahunAjaran::where('id', 'LIKE', "%{$search}%")
                ->orWhere('kode_ta', 'LIKE', "%{$search}%")
                ->orWhere('tgl_awal', 'LIKE', "%{$search}%")
                ->orWhere('tgl_akhir', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($ta)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($ta as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['kode_ta'] = $row->kode_ta;
                    $nestedData['tgl_awal'] = $row->tgl_awal;
                    $nestedData['tgl_awal_kuliah'] = $row->tgl_awal_kuliah;
                    $nestedData['tgl_akhir'] = $row->tgl_akhir;
                    $nestedData['status'] = $row->status;
                    $nestedData['keterangan'] = $row->keterangan;
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
        DB::beginTransaction();
        if ($id) {
            $ta = TahunAjaran::updateOrCreate(
                ['id' => $id],
                [
                    'kode_ta' => $request->kode_ta,
                    'tgl_awal' => $request->tgl_awal,
                    'tgl_awal_kuliah' => $request->tgl_awal_kuliah,
                    'tgl_akhir' => $request->tgl_akhir,
                    //'status' => $request->status,
                    'keterangan' => $request->keterangan
                ]
            );
            DB::commit();
            return response()->json('Updated');
        } else {
            $ta = TahunAjaran::updateOrCreate(
                ['id' => $id],
                [
                    'kode_ta' => $request->kode_ta,
                    'tgl_awal' => $request->tgl_awal,
                    'tgl_awal_kuliah' => $request->tgl_awal_kuliah,
                    'tgl_akhir' => $request->tgl_akhir,
                    //'status' => $request->status,
                    'status' => 'Aktif',
                    'keterangan' => $request->keterangan
                ]
            );
            $id_new_ta = $ta->id;
            if ($ta) {
                $aktif = TahunAjaran::where('status','Aktif')->first();

                $jadwal = Jadwal::all();
                foreach($jadwal as $row){
                    $krs = Krs::where('id_jadwal',$row->id)->where('id_tahun',$aktif->id)->get();
                    $insert = JadwalsArsip::create(
                        [
                            'kode_jadwal' => $row->kode_jadwal,
                            'id_tahun' => $row->id_tahun,
                            'id_mk' => $row->id_mk,
                            'hari' => $row->hari,
                            'id_sesi' => $row->id_sesi,
                            'id_ruang' => $row->id_ruang,
                            'kel' => $row->kel,
                            'kuota' => $row->kuota,
                            'status' => $row->status,
                            'tp' => $row->tp
                        ]
                    );
                    $new_jadwal_id = $insert->id;
                    foreach($krs as $rows){
                        $insert2 = KrsArsip::create(
                            [
                                'id_jadwal' => $new_jadwal_id,
                                'id_tahun' => $rows->id_tahun,
                                'id_mhs' => $rows->id_mhs,
                                'is_publish' => $rows->is_publish,
                                'is_uts' => $rows->is_uts,
                                'is_uas' => $rows->is_uas
                            ]
                        );
                    }
                    //pointing id_jadwal di master_nilai dari id_jadwal_lama ke id_jadwal-arsip
                }

                $nonaktif = TahunAjaran::query()->update(['status'=>'Tidak Aktif']);
                $new_ta = TahunAjaran::find($id_new_ta);
                $new_ta->status = 'Aktif';
                $new_ta->save();
                DB::commit();
                return response()->json('Created');
            } else {
                DB::rollBack();
                return response()->json('Failed Create Academic');
            }
        }
    }
    public function edit(string $id)
    {
        //
        $where = ['id' => $id];

        $ta = TahunAjaran::where($where)->first();

        return response()->json($ta);
    }
    public function destroy(string $id)
    {
        //
        $ta = TahunAjaran::where('id', $id)->delete();
    }
}
