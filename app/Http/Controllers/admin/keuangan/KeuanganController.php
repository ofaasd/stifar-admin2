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
use App\Models\Prodi;

class KeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nim', 'nama', 'prodi','angkatan', 'status', 'krs','uts','uas'];
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
            $prodi = Prodi::all();
            return view('admin.keuangan.index', compact('prodi','indexed','title','title2','ta','jumlah_keuangan','jumlah_mhs'));

        }else{
            $columns = [
                1 => 'id',
                2 => 'nim',
                3 => 'nama',
                4 => 'prodi',
                5 => 'angkatan',
                6 => 'status',
                7 => 'krs',
                8 => 'uts',
                9 => 'uas',
            ];

            $search = [];

            $totalData = MasterKeuanganMh::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $keuangan = MasterKeuanganMh::select('master_keuangan_mhs.*','program_studi.nama_prodi','mahasiswa.nim','mahasiswa.nama','mahasiswa.angkatan','mahasiswa.status','tahun_ajarans.kode_ta as ta')
                    ->join('mahasiswa', 'mahasiswa.id', '=', 'master_keuangan_mhs.id_mahasiswa')
                    ->join('tahun_ajarans', 'tahun_ajarans.id', '=', 'master_keuangan_mhs.id_tahun_ajaran')
                    ->join('program_studi','program_studi.id','=','mahasiswa.id_program_studi')
                    ->where('id_tahun_ajaran',$ta->id)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $keuangan = MasterKeuanganMh::select('master_keuangan_mhs.*','program_studi.nama_prodi','mahasiswa.nim','mahasiswa.nama','mahasiswa.angkatan','mahasiswa.status','tahun_ajarans.kode_ta as ta')
                    ->join('mahasiswa', 'mahasiswa.id', '=', 'master_keuangan_mhs.id_mahasiswa')
                    ->join('tahun_ajarans', 'tahun_ajarans.id', '=', 'master_keuangan_mhs.id_tahun_ajaran')
                    ->join('program_studi','program_studi.id','=','mahasiswa.id_program_studi')
                    ->where('id_tahun_ajaran',$ta->id)
                    //->where('mahasiswa.nim', 'LIKE', "%{$search}%")
                    ->where(function($query) use ($search) {
                        $query->where('mahasiswa.nama', 'LIKE', "%{$search}%")
                              ->orWhere('tahun_ajarans.kode_ta', 'LIKE', "%{$search}%")
                              ->orWhere('mahasiswa.nim', 'LIKE', "%{$search}%")
                              ->orWhere('program_studi.nama_prodi', 'LIKE', "%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterKeuanganMh::select('master_keuangan_mhs.*','program_studi.nama_prodi','mahasiswa.nim','mahasiswa.nama','mahasiswa.angkatan','mahasiswa.status','tahun_ajarans.kode_ta as ta')
                ->join('mahasiswa', 'mahasiswa.id', '=', 'master_keuangan_mhs.id_mahasiswa')
                ->join('tahun_ajarans', 'tahun_ajarans.id', '=', 'master_keuangan_mhs.id_tahun_ajaran')
                ->join('program_studi','program_studi.id','=','mahasiswa.id_program_studi')
                ->where('id_tahun_ajaran',$ta->id)
                
                ->where(function($query) use ($search) {
                    $query->where('mahasiswa.nama', 'LIKE', "%{$search}%")
                    ->orWhere('tahun_ajarans.kode_ta', 'LIKE', "%{$search}%")
                    ->orWhere('mahasiswa.nim', 'LIKE', "%{$search}%")
                    ->orWhere('program_studi.nama_prodi', 'LIKE', "%{$search}%");
                })
                ->count();
            }

            $data = [];

            if (!empty($keuangan)) {
            // providing a dummy id instead of database ids
                $ids = $start;
                $status = [
                    1 => 'Aktif',
                    'Cuti',
                    'Keluar',
                    'Lulus',
                    'Meninggal',
                    'Do'
                ];
                foreach ($keuangan as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nim'] = $row->nim;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['angkatan'] = $row->angkatan;
                    $nestedData['status'] = $status[$row->status];
                    $nestedData['prodi'] = $row->nama_prodi;
                    $nestedData['krs'] = $row->krs;
                    $nestedData['uts'] = $row->uts;
                    $nestedData['uas'] = $row->uas;
                    // $nestedData['status'] = $row->status;
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
    public function bulk_action(Request $request){
        $prodi = $request->prodi;
        $angkatan = $request->angkatan;
        $kegiatan = $request->kegiatan;
        $action = $request->action;
        if($prodi == 0 && $angkatan == 0){
            $mahasiswa = Mahasiswa::all();
        }else if($prodi == 0){
            $mahasiswa = Mahasiswa::where('angkatan',$angkatan)->get();
        }else if($angkatan == 0){
            $mahasiswa = Mahasiswa::where('id_program_studi',$prodi)->get();
        }else{
            $mahasiswa = Mahasiswa::where('id_program_studi',$prodi)->where('angkatan',$angkatan)->get();
        }
        foreach($mahasiswa as $row){
            if($kegiatan == 1){
                $u_mahasiswa = MasterKeuanganMh::where('id_mahasiswa',$row->id)->update([
                    'krs' => $action,
                ]);
            }else if($kegiatan == 2){
                $u_mahasiswa = MasterKeuanganMh::where('id_mahasiswa',$row->id)->update([
                    'uts' => $action,
                ]);
            }else if($kegiatan == 3){
                $u_mahasiswa = MasterKeuanganMh::where('id_mahasiswa',$row->id)->update([
                    'uas' => $action,
                ]);
            }
        }
        return response()->json('Updated');
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
    public function generate_angkatan(){
        $mhs = Mahasiswa::whereNull("angkatan")->get();
        foreach($mhs as $row){
            echo $row->nim;
            echo "<br />";
            echo "Angkatan : " . substr($row->nim,3,2);
            echo "<br/>";
            echo "<br/>";
            $new_mhs = Mahasiswa::find($row->id);
            $new_mhs->angkatan = "20" . substr($row->nim,3,2);
            $new_mhs->save();
            // MasterKeuanganMh::create([
            //     'id_mahasiswa' => $row->id,
            //     'id_tahun_ajaran' => $ta->id,
            //     'krs' => 1,
            //     'uts' => 1,
            //     'uas' => 1,
            // ]);
        }
        return back();
    }
    public function generate_user_mhs(){

        $mhs = Mahasiswa::where('user_id',0)->orderBy('id','asc')->limit(1)->first();
        $start = Mahasiswa::where('id','>=',$mhs->id)->get();
        foreach($start as $row){
            $user = User::where('email',$row->nim . "@mhs.stifar.id")->first();
            if($user){
                continue;
            }else{
                //
                $user = User::create([
                    'name' => $row->nama,
                    'email' => $row->nim . "@mhs.stifar.id",
                    'password' => Hash::make($row->nim . "stifar")
                ]);
            }
            
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
        return back();
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
    public function dashboard()
    {
        //hitung mahasiswa yang diijinkan KRS, UTS dan UAS
        $ta = TahunAjaran::where('status','Aktif')->first();
        $jumlah_krs = MasterKeuanganMh::where('id_tahun_ajaran',$ta->id)->where('krs',1)->join('mahasiswa','mahasiswa.id','=','master_keuangan_mhs.id_mahasiswa')->where('mahasiswa.status',1)->count();
        $jumlah_uts = MasterKeuanganMh::where('id_tahun_ajaran',$ta->id)->where('uts',1)->join('mahasiswa','mahasiswa.id','=','master_keuangan_mhs.id_mahasiswa')->where('mahasiswa.status',1)->count();
        $jumlah_uas = MasterKeuanganMh::where('id_tahun_ajaran',$ta->id)->where('uas',1)->join('mahasiswa','mahasiswa.id','=','master_keuangan_mhs.id_mahasiswa')->where('mahasiswa.status',1)->count();
        $jumlah_mhs = MasterKeuanganMh::where('id_tahun_ajaran',$ta->id)->join('mahasiswa','mahasiswa.id','=','master_keuangan_mhs.id_mahasiswa')->where('mahasiswa.status',1)->count();

        return view('admin.keuangan.dashboard',compact('jumlah_krs','jumlah_uts','jumlah_uas','jumlah_mhs','ta'));
    }
}