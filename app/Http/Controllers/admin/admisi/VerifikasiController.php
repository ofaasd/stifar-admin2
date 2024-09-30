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

class VerifikasiController extends Controller
{
    //
    public $indexed = ['', 'id','nama' , 'nopen', 'gelombang', 'pilihan1','pilihan2','ttl','is_verifikasi'];
    public $indexed2 = ['', 'id','nama' , 'nopen', 'gelombang', 'pilihan1','pilihan2','ttl','is_bayar'];
    public function index(Request $request,$id_gelombang=0)
    {
        //

        $ta_min = PmbGelombang::selectRaw('min(ta_awal) as ta_min')->limit(1)->first()->ta_min;
        $curr_ta = $ta_min;
        if($id_gelombang == 0){
            $curr_gelombang = PmbGelombang::where('ta_awal',$ta_min)->limit(1)->first();
            $id_gelombang = $curr_gelombang->id;
            $gelombang = PmbGelombang::where('ta_awal',$ta_min)->get();
        }else{
            $curr_ta = PmbGelombang::where('id',$id_gelombang)->first()->ta_awal;
            $gelombang = PmbGelombang::where('ta_awal',$curr_ta)->get();
            $request->session()->put('gelombang', $id_gelombang);
        }

        if (empty($request->input('length'))) {
            $title = "Verifikasi";
            $title2 = "Verifikasi Peserta";
            $indexed = $this->indexed;
            $ta_max = PmbGelombang::selectRaw('max(ta_awal) as ta_max')->limit(1)->first()->ta_max;
            return view('admin.admisi.verifikasi.index', compact('id_gelombang','curr_ta','gelombang','ta_max','ta_min','title','title2','indexed'));
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

        $peserta = PmbPesertaOnline::where($where)->first();

        return response()->json($peserta);

    }
    public function update_verifikasi(Request $request){
        $id = $request->id;
        $peserta = PmbPesertaOnline::find($id);
        $peserta->nopen = $request->nopen;
        $peserta->is_verifikasi = $request->is_verifikasi;
        if($peserta->save()){
            return response()->json('Updated');
        }else{
            return response()->json('Error');
        }

    }
    public function pembayaran(Request $request){
        if (empty($request->input('length'))) {
            $title = "Verifikasi";
            $title2 = "Verifikasi Pembayaran Peserta";
            $indexed = $this->indexed2;
            return view('admin.admisi.verifikasi.pembayaran', compact('title','title2','indexed'));
        }else{

            $gelombang = PmbGelombang::all();
            $gel = [];
            foreach($gelombang as $row){
                $gel[$row->id] = $row->nama_gel;
            }

            $prodi = PmbJalurProdi::select('pmb_jalur_prodi.*','program_studi.nama_prodi')->join('program_studi','program_studi.id','pmb_jalur_prodi.id_program_studi')->get();
            $prod = [];
            foreach($prodi as $row){
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
                8 => 'is_bayar',
            ];

            $search = [];

            $totalData = PmbPesertaOnline::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $peserta = PmbPesertaOnline::whereRaw('nopen <> ""')
                    ->where('is_verifikasi',1)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $peserta = PmbPesertaOnline::select('pmb_peserta_online.*','pmb_gelombang.nama_gel')
                    ->join('pmb_gelombang','pmb_gelombang.id','=','pmb_peserta_online.gelombang')
                    ->whereRaw('nopen <> ""')
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
                    ->whereRaw('nopen <> ""')
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
            return response()->json('Updated');
        }else{
            return response()->json('Error');
        }

    }
}
