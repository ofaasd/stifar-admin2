<?php

namespace App\Http\Controllers\admin\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;
use App\Models\MasterKeuanganMh;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ModelHasRole;

class KeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nim', 'nama', 'ta', 'krs','uts','uas'];
    public function index(Request $request)
    {
        //
        $ta = TahunAjaran::where('status','Aktif')->first();
        $keuangan = MasterKeuanganMh::where('id_tahun_ajaran',$ta->id);
        $mhs = Mahasiswa::all();
        if (empty($request->input('length'))) {
            $title2 = "Keuangan Mahasiswa";
            $indexed = $this->indexed;
            $jumlah_keuangan = $keuangan->count();
            $jumlah_mhs = count($mhs);
            $title =  "keuangan";
            $indexed = $this->indexed;
            return view('admin.keuangan.index', compact('indexed','title','title2','ta','jumlah_keuangan','jumlah_mhs'));

        }else{
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'nama',
                4 => 'ta',
                5 => 'krs',
                6 => 'uts',
                7 => 'uas',
            ];

            $search = [];

            $totalData = MasterKeuanganMh::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $keuangan = MasterKeuanganMh::select('master_keuangan_mhs.*','mahasiswa.nim','mahasiswa.nama','tahun_ajarans.kode_ta as ta')
                    ->join('mahasiswa', 'mahasiswa.id', '=', 'master_keuangan_mhs.id_mahasiswa')
                    ->join('tahun_ajarans', 'tahun_ajarans.id', '=', 'master_keuangan_mhs.id_tahun_ajaran')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $keuangan = MasterKeuanganMh::select('master_keuangan_mhs.*','mahasiswa.nim','mahasiswa.nama','tahun_ajarans.kode_ta as ta')
                    ->join('mahasiswa', 'mahasiswa.id', '=', 'master_keuangan_mhs.id_mahasiswa')
                    ->join('tahun_ajarans', 'tahun_ajarans.id', '=', 'master_keuangan_mhs.id_tahun_ajaran')
                    ->where('mahasiswa.nim', 'LIKE', "%{$search}%")
                    ->orWhere('mahasiswa.nama', 'LIKE', "%{$search}%")
                    ->orWhere('tahun_ajarans.kode_ta', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterKeuanganMh::select('master_keuangan_mhs.*','mahasiswa.nim','mahasiswa.nama','tahun_ajarans.kode_ta')
                ->join('mahasiswa', 'mahasiswa.id', '=', 'master_keuangan_mhs.id_mahasiswa')
                ->join('tahun_ajarans', 'tahun_ajarans.id', '=', 'master_keuangan_mhs.id_tahun_ajaran')
                ->where('mahasiswa.nim', 'LIKE', "%{$search}%")
                ->orWhere('mahasiswa.nama', 'LIKE', "%{$search}%")
                ->orWhere('tahun_ajarans.kode_ta', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($keuangan)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($keuangan as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nim'] = $row->nim;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['ta'] = $row->ta;
                    $nestedData['krs'] = $row->krs;
                    $nestedData['uts'] = $row->uts;
                    $nestedData['uas'] = $row->uas;
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



        $list_mhs = [];
        $list_nim = [];
        foreach($mhs as $row){
            $list_mhs[$row->id] = $row->nama;
            $list_nim[$row->id] = $row->nim;
        }
        $ta_all = TahunAjaran::all();
        $list_ta = [];
        foreach($ta_all as $row){
            $list_ta[$row->id] = $row->kode_ta;
        }

    }
    public function generate_mhs(){
        $ta = TahunAjaran::where('status','Aktif')->first();
        //last mhs
        $keuangan = MasterKeuanganMh::where('id_tahun_ajaran',$ta->id)->orderBy('id','desc')->limit(1)->first();
        $jumlah_id = 0;
        if($keuangan){
            $jumlah_id = $keuangan->id_mahasiswa;
        }
        $mhs = Mahasiswa::where("id",">",$jumlah_id)->get();
        foreach($mhs as $row){
            MasterKeuanganMh::create([
                'id_mahasiswa' => $row->id,
                'id_tahun_ajaran' => $ta->id,
                'krs' => 1,
                'uts' => 1,
                'uas' => 1,
            ]);
        }
        return back();
    }
    public function generate_user_mhs(){

        $mhs = Mahasiswa::where('user_id',0)->orderBy('id','asc')->limit(1)->first();
        $start = Mahasiswa::where('id','>=',$mhs->id)->get();
        foreach($start as $row){
            $user = User::create([
                'name' => $row->nama,
                'email' => $row->nim . "@mhs.stifar.id",
                'password' => Hash::make($row->nim . "stifar")
            ]);
            $id = $user->id;
            $update_mahasiswa = Mahasiswa::find($row->id);
            $update_mahasiswa->user_id = $id;
            $update_mahasiswa->save();

            $role = ModelHasRole::create(
                [
                    'role_id' => 4,
                    'model_type' => 'App\Models\User',
                    'model_id' => $id,
                ]
            );
        }

        // $user = User::create([
        //     'name' => $new_pegawai->nama_lengkap,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password)
        // ]);
        // $id = $user->id;
        // $update_pegawai = PegawaiBiodatum::find($new_pegawai->id);
        // $update_pegawai->user_id = $id;
        // $update_pegawai->save();

        // $role = ModelHasRole::create(
        //     [
        //         'role_id' => 3,
        //         'model_type' => 'App\Models\User',
        //         'model_id' => $id,
        //     ]
        // );




        // //last mhs
        // $keuangan = MasterKeuanganMh::where('id_tahun_ajaran',$ta->id)->orderBy('id','desc')->limit(1)->first();
        // $jumlah_id = 0;
        // if($keuangan){
        //     $jumlah_id = $keuangan->id_mahasiswa;
        // }
        // $mhs = Mahasiswa::where("id",">",$jumlah_id)->get();
        // foreach($mhs as $row){
        //     MasterKeuanganMh::create([
        //         'id_mahasiswa' => $row->id,
        //         'id_tahun_ajaran' => $ta->id,
        //         'krs' => 1,
        //         'uts' => 1,
        //         'uas' => 1,
        //     ]);
        // }
        // return back();
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
            $sesi = MasterKeuanganMh::updateOrCreate(
                ['id' => $id],
                [
                    'krs' => $request->krs_value,
                    'uts' => $request->uts_value,
                    'uas' => $request->uas_value,
                ]
            );

            return response()->json('Updated');
        } else {
            $sesi = MasterKeuanganMh::updateOrCreate(
                ['id' => $id],
                [
                    'krs' => $request->krs_value,
                    'uts' => $request->uts_value,
                    'uas' => $request->uas_value,
                ]
            );
            if ($sesi) {
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
        $keuangan = MasterKeuanganMh::select('master_keuangan_mhs.*','mahasiswa.nim','mahasiswa.nama','tahun_ajarans.kode_ta as ta')
                ->join('mahasiswa', 'mahasiswa.id', '=', 'master_keuangan_mhs.id_mahasiswa')
                ->join('tahun_ajarans', 'tahun_ajarans.id', '=', 'master_keuangan_mhs.id_tahun_ajaran')
                ->where('master_keuangan_mhs.id',$id)
                ->first();
        return response()->json($keuangan);
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
    }
}
