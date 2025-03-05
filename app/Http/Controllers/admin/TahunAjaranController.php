<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Jadwal;
use App\Models\Krs;
use App\Models\Pertemuan;
use App\Models\master_nilai as MasterNilai;
use App\Models\AbsensiModel;
use App\Models\KontrakKuliahModel as KontrakKuliah;
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

        if ($id) {

                // DB::beginTransaction();
            $nonaktif = TahunAjaran::query()->update(['status'=>'Tidak Aktif']);
            $ta = TahunAjaran::updateOrCreate(
                ['id' => $id],
                [
                    'kode_ta' => $request->kode_ta,
                    'tgl_awal' => $request->tgl_awal,
                    'tgl_awal_kuliah' => $request->tgl_awal_kuliah,
                    'tgl_akhir' => $request->tgl_akhir,
                    'status' => 'Aktif',
                    'keterangan' => $request->keterangan
                ]
            );
            // DB::commit();
            return response()->json('Updated');

        } else {

                //DB::beginTransaction();
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
                //$id_new_ta = $ta->id;

                $id_new_ta = $ta->id;
                if ($ta) {
                    $aktif = TahunAjaran::where('status','Aktif')->first();

                    $jadwal = Jadwal::all();
                    //adding nonaktif
                    $nonaktif = TahunAjaran::query()->update(['status'=>'Tidak Aktif']);
                    $new_ta = TahunAjaran::find($id_new_ta);
                    $new_ta->status = 'Aktif';
                    $new_ta->save();
                    // foreach($jadwal as $row){
                    //     $krs = Krs::where('id_jadwal',$row->id)->where('id_tahun',$aktif->id)->get();
                    //     $pertemuan = Pertemuan::where('id_jadwal',$row->id)->get();
                    //     $absensi_model = AbsensiModel::where('id_jadwal',$row->id)->get();
                    //     $master_nilai = MasterNilai::where('id_jadwal',$row->id)->where('id_tahun',$aktif->id)->get();
                    //     $kontrak_kuliah = KontrakKuliah::where('id_jadwal',$row->id)->get();
                    //     $insert = JadwalsArsip::create(
                    //         [
                    //             'kode_jadwal' => $row->kode_jadwal,
                    //             'id_tahun' => $row->id_tahun,
                    //             'id_mk' => $row->id_mk,
                    //             'hari' => $row->hari,
                    //             'id_sesi' => $row->id_sesi,
                    //             'id_ruang' => $row->id_ruang,
                    //             'kel' => $row->kel,
                    //             'kuota' => $row->kuota,
                    //             'status' => $row->status,
                    //             'tp' => $row->tp
                    //         ]
                    //     );
                    //     $new_jadwal_id = $insert->id;
                    //     foreach($krs as $rows){
                    //         $insert2 = KrsArsip::create(
                    //             [
                    //                 'id_jadwal' => $new_jadwal_id,
                    //                 'id_tahun' => $rows->id_tahun,
                    //                 'id_mhs' => $rows->id_mhs,
                    //                 'is_publish' => $rows->is_publish,
                    //                 'is_uts' => $rows->is_uts,
                    //                 'is_uas' => $rows->is_uas
                    //             ]
                    //         );
                    //     }
                    //     foreach($absensi_model as $rows){
                    //         $insert2 = AbsensiModelArsip::create(
                    //             [
                    //                 'id_jadwal' => $new_jadwal_id,
                    //                 'id_pertemuan' => $rows->id_pertemuan,
                    //                 'id_mhs' => $rows->id_mhs,
                    //                 'type' => $rows->type,
                    //             ]
                    //         );
                    //     }
                    //     foreach($kontrak_kuliah as $rows){
                    //         $insert2 = KontrakKuliahArsip::create(
                    //             [
                    //                 'id_jadwal' => $new_jadwal_id,
                    //                 'tugas' => $rows->tugas,
                    //                 'uts' => $rows->uts,
                    //                 'uas' => $rows->uas,
                    //             ]
                    //         );
                    //     }
                    //     foreach($master_nilai as $rows){
                    //         $insert2 = MasterNilaiArsip::create(
                    //             [
                    //                 'id_jadwal' => $new_jadwal_id,
                    //                 'id_tahun' => $rows->id_tahun,
                    //                 'nim' => $rows->nim,
                    //                 'ntugas' => $rows->ntugas,
                    //                 'nuts' => $rows->nuts,
                    //                 'nuas' => $rows->nuas,
                    //                 'nakhir' => $rows->nakhir,
                    //                 'nhuruf' => $rows->nhuruf,
                    //                 'kualitas' => $rows->kualitas,
                    //                 'ndosen' => $rows->ndosen,
                    //                 'is_krs' => $rows->is_krs,
                    //                 'publish_tugas' => $rows->publish_tugas,
                    //                 'publish_uts' => $rows->publish_uts,
                    //                 'publish_uas' => $rows->publish_uas,
                    //                 'validasi_tugas' => $rows->validasi_tugas,
                    //                 'validasi_uts' => $rows->validasi_uts,
                    //                 'validasi_uas' => $rows->validasi_uas,
                    //                 'log_date' => $rows->log_date,
                    //                 'id_mhs' => $rows->id_mhs,
                    //             ]
                    //         );
                    //     }
                    //     foreach($pertemuan as $rows){
                    //         $insert2 = PertemuanArsip::create(
                    //             [
                    //                 'id_jadwal' => $new_jadwal_id,
                    //                 'no_pertemuan' => $rows->no_pertemuan,
                    //                 'capaian' => $rows->capaian,
                    //                 'tgl_pertemuan' => $rows->tgl_pertemuan,
                    //                 'id_dsn' => $rows->id_dsn,
                    //             ]
                    //         );
                    //     }
                    //     //pointing id_jadwal di master_nilai dari id_jadwal_lama ke id_jadwal-arsip
                    // }
                    // //adding nonaktif
                    // $nonaktif = TahunAjaran::query()->update(['status'=>'Tidak Aktif']);
                    // $new_ta = TahunAjaran::find($id_new_ta);
                    // $new_ta->status = 'Aktif';
                    // $new_ta->save();
                    //hapus record exists
                    // $jadwal = Jadwal::truncate();
                    // $krs = Krs::truncate();
                    // $pertemuan = Pertemuan::truncate();
                    // $absensi_model = AbsensiModel::truncate();
                    // $master_nilai = MasterNilai::truncate();
                    // $kontrak_kuliah = KontrakKuliah::truncate();
                    //DB::commit();
                    return response()->json('Created');
                } else {
                    //DB::rollBack();
                    return response()->json('Failed Create Academic');
                }
                //DB::commit();

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
