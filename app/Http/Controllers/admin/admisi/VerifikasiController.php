<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbPesertaOnline;
use App\Models\PmbJalur;
use App\Models\PmbJalurProdi;
use App\Models\Wilayah;
use App\Models\PmbTum as ta;
use App\Models\PmbGelombang;
use App\Models\PmbAsalSekolah;
use App\Models\PmbNilaiRapor;
use App\Models\Prodi;
use App\Models\BuktiRegistrasi;
use App\Models\BankDataVa;
use App\helpers;

class VerifikasiController extends Controller
{
    //
    public $indexed = ['', 'id','nama' , 'nopen', 'gelombang', 'pilihan1','pilihan2','ttl','is_verifikasi', 'no_va','status'];
    public $indexed2 = ['', 'id','nama' , 'nopen', 'gelombang', 'pilihan1','pilihan2','ttl','bukti_bayar','is_bayar'];
    public function index(Request $request,$id_gelombang=0)
    {
        //

        $ta_min = PmbGelombang::selectRaw('min(ta_awal) as ta_min')->limit(1)->first()->ta_min;
        $ta_max = PmbGelombang::selectRaw('max(ta_awal) as ta_max')->limit(1)->first()->ta_max;
        $curr_ta = $ta_max;
        if($id_gelombang == 0){
            $curr_gelombang = PmbGelombang::where('ta_awal',$curr_ta)->orderBy('id','desc')->limit(1)->first();
            $id_gelombang = $curr_gelombang->id;
            $gelombang = PmbGelombang::where('ta_awal',$curr_ta)->orderBy('id','desc')->get();
        }else{
            $curr_ta = PmbGelombang::where('id',$id_gelombang)->orderBy('id','desc')->first()->ta_awal;
            $gelombang = PmbGelombang::where('ta_awal',$curr_ta)->orderBy('id','desc')->get();
            $request->session()->put('gelombang', $id_gelombang);
        }

        if (empty($request->input('length'))) {
            $title = "Verifikasi";
            $title2 = "Verifikasi Peserta";
            $indexed = $this->indexed;
            $bank = BankDataVa::all();

            return view('admin.admisi.verifikasi.index', compact('id_gelombang','curr_ta','gelombang','ta_max','ta_min','title','title2','indexed','bank'));
        }else{

            $gelombang = PmbGelombang::all();
            $gel = [];
            foreach($gelombang as $row){
                $gel[$row->id] = $row->nama_gel;
            }

            $prodi = PmbJalurProdi::select('pmb_jalur_prodi.*','program_studi.nama_prodi')->join('program_studi','program_studi.id','pmb_jalur_prodi.id_program_studi')->get();
            $prod = [];
            $all_prodi = Prodi::all();
            foreach($all_prodi as $row){
                $prod[$row->id] = $row->nama_prodi . " " . $row->keterangan;
            }

            $columns = [
                1 => 'id',
                2 => 'nama',
                3 => 'nopen',
                4 => 'gelombang',
                5 => 'pilihan1',
                6 => 'pilihan2',
                7 => 'is_verifikasi',
                8 => 'ttl',
                9=>'no_va',
                10=>'status'
            ];

            $search = [];

            $totalData = PmbPesertaOnline::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $peserta = PmbPesertaOnline::where('gelombang',$id_gelombang)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $peserta = PmbPesertaOnline::select('pmb_peserta_online.*','pmb_gelombang.nama_gel')
                    ->join('pmb_gelombang','pmb_gelombang.id','=','pmb_peserta_online.gelombang')
                    ->where('gelombang',$id_gelombang)
                    ->where(function ($query) use ($search) {
                    $query
                      ->where('pmb_peserta_online.id', 'LIKE', "%{$search}%")
                      ->orWhere('nama', 'LIKE', "%{$search}%")
                      ->orWhere('nama_gel', 'LIKE', "%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = PmbPesertaOnline::select('pmb_peserta_online.*','pmb_gelombang.nama_gel')
                    ->join('pmb_gelombang','pmb_gelombang.id','=','pmb_peserta_online.gelombang')
                    ->where('gelombang',$id_gelombang)
                    ->where(function ($query) use ($search) {
                    $query
                      ->where('pmb_peserta_online.id', 'LIKE', "%{$search}%")
                      ->orWhere('nama_gel', 'LIKE', "%{$search}%")
                      ->orWhere('nama', 'LIKE', "%{$search}%");
                    })
                ->count();
            }

            $data = [];

            if (!empty($peserta)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($peserta as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['nopen'] = $row->nopen;
                    $nestedData['gelombang'] = $gel[$row->gelombang];
                    $nestedData['list_gelombang'] = $gel;
                    $nestedData['is_verifikasi'] = $row->is_verifikasi ?? '0';
                    $nestedData['is_bayar'] = $row->is_bayar;
                    $nestedData['is_lolos'] = $row->is_lolos;
                    $nestedData['pilihan1'] = $prod[$row->pilihan1] ?? '';
                    $nestedData['pilihan2'] = $prod[$row->pilihan2] ?? '';
                    $nestedData['ttl'] = $row->tempat_lahir . ", " . date('d-m-Y', strtotime($row->tanggal_lahir));
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
    public function edit_verifikasi($id){
        $where = ['id' => $id];
        $hasil = [];

        $hasil[0] = PmbPesertaOnline::where($where)->first();
        if(!empty($hasil[0]->nopen)){
            //$cek_va = BankDataVa::where('no_va','like','%'. $hasil[0]->nopen . '%')->where('status',0)->count();
            $pmb = PmbPesertaOnline::where("nopen",$hasil[0]->nopen)->count();
            if($pmb < 2){
                $hasil[1] = 1; //tersedia
            }else{
                $hasil[1] = 0; //tidak tersedia
            }
        }


        return response()->json($hasil);

    }
    public function edit_pembayaran($id){
        $where = ['id' => $id];

        $peserta[0] = PmbPesertaOnline::where($where)->first();
        $bukti = BuktiRegistrasi::where('nopen',$peserta[0]->nopen)->first();

        if($bukti){
            $peserta[1]['tgl_tf'] = date('Y-m-d',strtotime($bukti->tgl_tf));
            $peserta[1]['bukti'] = $bukti->bukti;
        }
        return response()->json($peserta);

    }
    public function update_verifikasi(Request $request){
        $id = $request->id;
        //$bank = BankDataVa::where('no_va','like','%' . $request->nopen . '%')->where('status',0)->count();
        $pmb = PmbPesertaOnline::where("nopen",$request->nopen)->count();
        if($pmb == 1 && $request->is_verifikasi == 1){
            $bank = BankDataVa::where('no_va','like','%' . $request->nopen . '%')->count();
            if($bank > 0){
                $peserta = PmbPesertaOnline::find($id);
                $peserta->nopen = $request->nopen;
                $peserta->is_verifikasi = $request->is_verifikasi;
                $array = [
                    'status' => 1,
                    'nopen' => $request->nopen,
                ];

                if($peserta->save()){
                    //disini tambahkan wa ke nomor mahasiswa

                    $data['no_wa'] = $peserta->hp;
                    $message = "*Pesan ini otomatis dikirim dari MyStifar* \n\n Halo, " . $peserta->nama . ", \n\n *Verifikasi Berhasil*,\n\nBerikut no pendaftaran kamu : *" . $request->nopen . "*. \nSilahkan Login kembali melalui link berikut \n https://pendaftaran.stifar.ac.id/ \n untuk melengkapi berkas registrasi pendaftaran \n\n jika terdapat kendala dapat menghubungi no. 081393111171 \n sebagai media center PMB STIFAR 2025 \n\n Terimakasih, \n Admin PMB STIFAR";
                    $data['pesan'] = $message;

                    $nohp = $peserta->hp;
                    $pesan = helpers::send_wa($data);
                    $bank = BankDataVa::where('no_va','like','%' . $request->nopen . '%')->where('status',0)->update($array);
                    return response()->json('Updated');
                }else{
                    return response()->json('Error');
                }
            }else{
                return response()->json('Error | No VA tidak ada dalam bank data VA');
            }
        }elseif($request->is_verifikasi != 1){
            $peserta = PmbPesertaOnline::find($id);
            $peserta->nopen = $request->nopen;
            $peserta->is_verifikasi = $request->is_verifikasi;
            if($peserta->save()){
                return response()->json('Updated');
            }else{
                return response()->json('Error');
            }
        }else{
            return response()->json('Error | va sudah digunakan atau tidak terdaftar');
        }




    }
    public function pembayaran(Request $request,int $id_gelombang=0){
        $ta_min = PmbGelombang::selectRaw('min(ta_awal) as ta_min')->limit(1)->first()->ta_min;
        $ta_max = PmbGelombang::selectRaw('max(ta_awal) as ta_max')->limit(1)->first()->ta_max;
        $curr_ta = $ta_max;
        if($id_gelombang == 0){
            $curr_gelombang = PmbGelombang::where('ta_awal',$curr_ta)->limit(1)->first();
            $id_gelombang = $curr_gelombang->id;
            $gelombang = PmbGelombang::where('ta_awal',$curr_ta)->get();
        }else{
            $curr_ta = PmbGelombang::where('id',$id_gelombang)->first()->ta_awal;
            $gelombang = PmbGelombang::where('ta_awal',$curr_ta)->get();
            $request->session()->put('gelombang', $id_gelombang);
        }
        if (empty($request->input('length'))) {
            $title = "verifikasi/pembayaran";
            $title2 = "Verifikasi Pembayaran Peserta";
            $indexed = $this->indexed2;
            return view('admin.admisi.verifikasi.pembayaran', compact('id_gelombang','curr_ta','gelombang','ta_max','ta_min','title','title2','indexed'));
        }else{

            $gelombang = PmbGelombang::all();
            $gel = [];
            foreach($gelombang as $row){
                $gel[$row->id] = $row->nama_gel;
            }

            $prodi = PmbJalurProdi::select('pmb_jalur_prodi.*','program_studi.nama_prodi')->join('program_studi','program_studi.id','pmb_jalur_prodi.id_program_studi')->get();
            $list_prodi = Prodi::all();
            $prod = [];
            foreach($list_prodi as $row){
                $prod[$row->id] = $row->nama_prodi . " " . $row->keterangan;
            }

            $columns = [
                1 => 'id',
                2 => 'nama',
                3 => 'nopen',
                4 => 'gelombang',
                5 => 'pilihan1',
                6 => 'pilihan2',
                7 => 'ttl',
                8 => 'bukti_bayar',
                9 => 'is_bayar',
            ];

            $search = [];

            $totalData = PmbPesertaOnline::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $peserta = PmbPesertaOnline::where('gelombang',$id_gelombang)
                    ->where('is_verifikasi',1)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $peserta = PmbPesertaOnline::select('pmb_peserta_online.*','pmb_gelombang.nama_gel')
                    ->join('pmb_gelombang','pmb_gelombang.id','=','pmb_peserta_online.gelombang')
                    ->where('gelombang',$id_gelombang)
                    ->where('is_verifikasi',1)
                    ->where(function ($query) use ($search) {
                    $query
                      ->where('pmb_peserta_online.id', 'LIKE', "%{$search}%")
                      ->orWhere('nama', 'LIKE', "%{$search}%")
                      ->orWhere('nama_gel', 'LIKE', "%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = PmbPesertaOnline::select('pmb_peserta_online.*','pmb_gelombang.nama_gel')
                    ->join('pmb_gelombang','pmb_gelombang.id','=','pmb_peserta_online.gelombang')
                    ->where('gelombang',$id_gelombang)
                    ->where('is_verifikasi',1)
                    ->where(function ($query) use ($search) {
                    $query
                      ->where('pmb_peserta_online.id', 'LIKE', "%{$search}%")
                      ->orWhere('nama_gel', 'LIKE', "%{$search}%")
                      ->orWhere('nama', 'LIKE', "%{$search}%");
                    })
                ->count();
            }

            $data = [];

            if (!empty($peserta)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($peserta as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['nopen'] = $row->nopen;
                    $nestedData['gelombang'] = $gel[$row->gelombang];
                    $nestedData['list_gelombang'] = $gel;
                    $nestedData['is_bayar'] = $row->is_bayar ?? '0';
                    $nestedData['pilihan1'] = $prod[$row->pilihan1] ?? '';
                    $nestedData['pilihan2'] = $prod[$row->pilihan2] ?? '';
                    $nestedData['bukti_bayar'] = BuktiRegistrasi::where('nopen',$row->nopen)->count();
                    $nestedData['ttl'] = $row->tempat_lahir . ", " . date('d-m-Y', strtotime($row->tanggal_lahir));
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
    public function update_pembayaran(Request $request){
        $id = $request->id;
        $peserta = PmbPesertaOnline::find($id);
        $peserta->no_refrensi = $request->no_refrensi;
        $peserta->is_bayar = $request->is_bayar;
        if($peserta->save()){
            $bukti = BuktiRegistrasi::where('nopen',$peserta->nopen)->first();
            if($bukti){
                if($request->is_bayar == 1){
                    $data['no_wa'] = $peserta->hp;
                    $message = "*Pesan ini otomatis dikirim dari MyStifar* \n\n Halo, " . $peserta->nama . ", \n\n *Pembayaran Pendaftaran Terverifikasi*,\n\nSilahkan Login kembali melalui link berikut \n https://pendaftaran.stifar.ac.id/ \n untuk melanjutkan proses pendaftaran \n\n jika terdapat kendala dapat menghubungi no. 081393111171 \n sebagai media center PMB STIFAR 2025 \n\n Terimakasih, \n Admin PMB STIFAR";
                    $data['pesan'] = $message;

                    $nohp = $peserta->hp;
                    $pesan = helpers::send_wa($data);
                }
                $update = BuktiRegistrasi::find($bukti->id);
                $update->no_refrensi = $request->no_refrensi;
                $update->verifikasi = $request->is_bayar;
                $update->save();
            }
            return response()->json('Updated');
        }else{
            return response()->json('Error');
        }

    }
    public function show(String $id){
        $where = ['id' => $id];
        $select = [
            'nopen',
            'noktp',
            'nama',
            'nama_ibu',
            'nama_ayah',
            'hp_ortu',
            'tempat_lahir',
            'tanggal_lahir',
            'alamat',
        ];
        $peserta = PmbPesertaOnline::select($select)->where($where)->first();

        return response()->json($peserta);
    }
}
