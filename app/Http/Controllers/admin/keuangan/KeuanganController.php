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
use App\Models\TagihanKeuangan;
use App\Models\JenisKeuangan;
use App\Models\Tagihan;
use App\Models\DetailTagihanKeuangan as DetailTagihanKeuanganTotal;
use App\Models\TbPembayaran; 

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
        $total_bayar_statistik = [];
        $total_tagihan_statistik = [];
        //hitung jumlah keuangan yang masuk dan berapa yang harus dibbayarkan 
        $angkatan = 2025;
        $tagihan = Mahasiswa::where('angkatan','>=',$angkatan)->where('status',1)
                            ->get();
        $nama = []; 
        $prodi = Prodi::all(); 
        foreach($prodi as $row){
            $nama_prodi = explode(' ',$row->nama_prodi);
            $nama[$row->id] = $nama_prodi[0] . " " . $nama_prodi[1];
            $total_bayar_statistik[$row->id] = 0;
            $total_tagihan_statistik[$row->id] = 0;
        }                          
        $data = [];
        $list_keu = [];
        $list_tagihan = [];
        
        $tahun = TahunAjaran::where('status','Aktif')->first();
        $jenis = JenisKeuangan::whereIn('id',[1,2,6,9,10,11,12])->get();
        foreach($tagihan as $tag){
            $id = $tag->id_program_studi;
            
            if($id == 1|| $id == 2 || $id == 5){
                $real_tagihan = TagihanKeuangan::where('nim',$tag->nim)->where('id_tahun',$tahun->id)->where('periode',date('m'))->where('tahun',date('Y'));
            }elseif($id == 3 || $id == 4){
                $real_tagihan = TagihanKeuangan::where('nim',$tag->nim)->where('id_tahun',$tahun->id);
            }
            if($real_tagihan->count() > 0){
                $list_tagihan[$tag->id] = $real_tagihan->first();
                $id_tagihan = $list_tagihan[$tag->id]->id;
                foreach($jenis as $jen){
                    $list_keu[$tag->id][$jen->id] = DetailTagihanKeuangan::where('id_tagihan',$id_tagihan)->where('id_jenis',$jen->id)->first()->jumlah ?? 0;
                }
            }else{
                foreach($jenis as $jen){
                    $list_keu[$tag->id][$jen->id] = 0;
                }
            }
        }
        // dd($list_tagihan);
        foreach ($tagihan as $index => $row) {
            $upp_bulan = 0;
            $upp_semester = 0;
            $dpp = 0;
            
            $tagihan_total = Tagihan::where('nim',$row->nim)->first();
            $total_bayar = $tagihan_total->pembayaran ?? 0;
            //jika prodi D3
            $status_bayar = false;
            $new_total_tagihan = 0;
            $i = 1;
            $pengurangan = 0;        
            if(!empty($tagihan_total->id)){                        
                if($id == 1 || $id == 2){
                    $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
                    foreach($detail_tagihan as $dt){
                        
                        
                        if($dt->id_jenis == 8){
                            $total_bayar = $total_bayar - $dt->jumlah;
                            $new_total_tagihan += $dt->jumlah;
                            
                        }elseif($dt->id_jenis == 2 && $i == 1){
                            $total_bayar = $total_bayar - $dt->jumlah;
                            $new_total_tagihan += $dt->jumlah;
                            $i++;
                            
                        }elseif($dt->id_jenis == 2 && $i > 1){
                            //dipecah UPP per bulan
                            $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
                            
                            $upp_bulan = $dt->jumlah / 30;
                        
                            
                            $bulan_mhs = $mahasiswa->bulan_awal;
                            $tahun_mhs = $mahasiswa->angkatan;
                            $tagihan_bulan = date('m');
                            $tagihan_tahun = date('Y');
                            $pengurangan = ($tagihan_tahun * 12 + $tagihan_bulan) - ($tahun_mhs * 12 + $bulan_mhs) + 1;//ditambah 1 karena julidi hitung
                            $bulanan = $upp_bulan * $pengurangan;
                            $new_total_tagihan += $bulanan;
                            $total_bayar = $total_bayar - $bulanan;
                            if($total_bayar >= 0){
                                $status_bayar = true;
                            }
                            
                        }
                    }
                }elseif($id == 5){
                    
                    $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
                    foreach($detail_tagihan as $dt){
                        
                        
                        if($dt->id_jenis == 8){
                            $total_bayar = $total_bayar - $dt->jumlah;
                            $new_total_tagihan += $dt->jumlah;
                            
                        }elseif($dt->id_jenis == 2 && $i == 1){
                            $total_bayar = $total_bayar - $dt->jumlah;
                            $new_total_tagihan += $dt->jumlah;
                            $i++;
                            
                        }elseif($dt->id_jenis == 2 && $i > 1){
                            //dipecah UPP per bulan
                            $mahasiswa = Mahasiswa::where('nim',$row->nim)->first();
                            
                            $upp_bulan = $dt->jumlah / 8;
                            
                            $bulan_mhs = $mahasiswa->bulan_awal;
                            $tahun_mhs = $mahasiswa->angkatan;
                            $tagihan_bulan = date('m');
                            $tagihan_tahun = date('Y');
                            $pengurangan = ($tagihan_tahun * 12 + $tagihan_bulan) - ($tahun_mhs * 12 + $bulan_mhs) + 1;//ditambah 1 karena julidi hitung
                            $bulanan = $upp_bulan * $pengurangan;
                            $new_total_tagihan += $bulanan;
                            $total_bayar = $total_bayar - $bulanan;
                            if($total_bayar >= 0){
                                $status_bayar = true;
                            }
                            
                        }
                    }
                }else{
                    $detail_tagihan = DetailTagihanKeuanganTotal::where('id_tagihan',$tagihan_total->id)->get();
                    $i = 0;
                    foreach($detail_tagihan as $dt){
                        if($dt->id_jenis == 2 && $i == 0){
                            $new_total_tagihan += $dt->jumlah;
                            $total_bayar = $total_bayar - $dt->jumlah;
                            $upp_semester = $dt->jumlah;
                            $i++;
                        }elseif($dt->id_jenis == 8){
                            $new_total_tagihan += $dt->jumlah;
                            $total_bayar = $total_bayar - $dt->jumlah;
                            if($total_bayar >= 0){
                                $status_bayar = true;
                            }
                        }elseif($dt->id_jenis == 1){
                            $dpp = $dt->jumlah;
                        }
                    }
                }
            }
                                    
            $nestedData = [];
            $nestedData['id'] = $row->id;
            $nestedData['nim'] = $row->nim;
            $nestedData['nama'] = $row->nama;
            $nestedData['prodi'] = $nama[$row->id_program_studi];
            foreach($jenis as $jen){
                if($jen->id == 1){
                    $nestedData[str_replace(' ', '', $jen->nama)] = "RP. " . number_format($dpp, 0, ',', '.');
                }elseif($jen->id == 2){
                    $nestedData[str_replace(' ', '', $jen->nama)] = "RP. " . number_format($upp_semester, 0, ',', '.');
                }elseif($jen->id == 6){
                    $nestedData[str_replace(' ', '', $jen->nama)] = "RP. " . number_format($upp_bulan, 0, ',', '.');
                }else{
                    $nestedData[str_replace(' ', '', $jen->nama)] = "RP. " . number_format($list_keu[$row->id][$jen->id], 0, ',', '.');
                }
            }
            $tagihan_total_bayar = $tagihan_total->pembayaran ?? 0;
            $status = ($tagihan_total_bayar >= $new_total_tagihan) ? 1 : 0;
            // $nestedData['total'] = $list_tagihan[$row->id]->total ?? 0;
            $nestedData['total'] = $new_total_tagihan ?? 0;
            
            $total_bayar_statistik[$row->id_program_studi] += $new_total_tagihan ?? 0;
            // $nestedData['total_bayar'] = $list_tagihan[$row->id]->total_bayar ?? 0;
            $nestedData['total_bayar'] = $tagihan_total_bayar ?? 0;
            $total_tagihan_statistik[$row->id_program_studi] += $tagihan_total_bayar ?? 0;
            $nestedData['status'] = $status ?? 0;
            $nestedData['id_tagihan'] = $row->id ?? 0;
            $nestedData['is_publish'] = $row->is_publish_keuangan ?? 0;
            $data[] = $nestedData;
        }
        // $program_studi = $prodi;
        $title = "Keuangan Mahasiswa";
        $pembayaran_terakhir = TbPembayaran::orderBy('tanggal_bayar','desc')->limit(1)->first()->tanggal_bayar ?? '-';
        
        return view('admin.keuangan.dashboard',compact('jumlah_krs','jumlah_uts','jumlah_uas','jumlah_mhs','ta','total_bayar_statistik','total_tagihan_statistik','prodi','pembayaran_terakhir','title'));
    }
}