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
use Image;
use Session;

class HistoryPmbController extends Controller
{
    //
    public $indexed = ['', 'id','nama' , 'nopen', 'gelombang', 'pilihan1','pilihan2','ttl','admin_input_date','validasi','bayar','lolos'];
    public function index(Request $request,$id_gelombang=0)
    {
        //
        
        $ta_min = PmbGelombang::selectRaw('max(ta_awal) as ta_min')->limit(1)->first()->ta_min;
        $curr_ta = $ta_min;
        $url_params = $request->query();
        $gelombang = [];
        
        if(!empty($request->angkatan)){
            $gelombang = PmbGelombang::where('ta_awal',$request->angkatan)->orderBy('id','desc')->get();
            if(!empty($request->gelombang)){
                $id_gelombang = $request->gelombang;
            }
        }
        if (empty($request->input('length'))) {
            $title = "Peserta";
            $indexed = $this->indexed;
            $ta_max = PmbGelombang::selectRaw('max(ta_awal) as ta_max')->   limit(1)->first()->ta_max;

            return view('admin.admisi.history.index', compact('id_gelombang','curr_ta','ta_max','ta_min','title','indexed','url_params','gelombang'));
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
                7 => 'ttl',
                8 => 'admin_input_date',
                9 => 'validasi',
                10 => 'bayar',
                11 => 'lolos',
            ];

            $search = [];

            $totalData = PmbPesertaOnline::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            
            $order = 'id';
            $dir = 'desc';
            $data = [];
            if(!empty($request->angkatan)){
                $data['angkatan'] = $request->angkatan;
            }
            if(!empty($request->gelombang)){
                $data['gelombang'] = $request->gelombang;
            }
            
            if(!empty($request->nopen)){
                $data['nopen'] = $request->nopen;
            }
            if(!empty($request->is_lolos)){
                $data['is_lolos'] = $request->is_lolos;
            }else{
                if($request->is_lolos === '0'){
                    $data['is_lolos'] = $request->is_lolos;
                }
            }
            if (empty($request->input('search.value'))) {
                
                if(!empty($data)){
                    $peserta = PmbPesertaOnline::where($data)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                }else{
                    $peserta = PmbPesertaOnline::limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                }
            } else {
                $order = $columns[$request->input('order.0.column')];
                $dir = $request->input('order.0.dir');
                $search = $request->input('search.value');

                if(empty($data)){
                    $peserta = PmbPesertaOnline::where(fn($query) =>
                            $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('nama', 'LIKE', "%{$search}%")
                            ->orWhere('nopen', 'LIKE', "%{$search}%")
                            ->orWhere('pilihan1', 'LIKE', "%{$search}%")
                            ->orWhere('pilihan2', 'LIKE', "%{$search}%")
                        )
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();

                    $totalFiltered = PmbPesertaOnline::where(fn($query) =>
                        $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('nama', 'LIKE', "%{$search}%")
                        ->orWhere('nopen', 'LIKE', "%{$search}%")
                        ->orWhere('pilihan1', 'LIKE', "%{$search}%")
                        ->orWhere('pilihan2', 'LIKE', "%{$search}%")
                    )
                    ->count();
                }else{
                   $peserta = PmbPesertaOnline::where($data)
                        ->where(fn($query) =>
                            $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhere('nama', 'LIKE', "%{$search}%")
                            ->orWhere('nopen', 'LIKE', "%{$search}%")
                            ->orWhere('pilihan1', 'LIKE', "%{$search}%")
                            ->orWhere('pilihan2', 'LIKE', "%{$search}%")
                        )
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();

                    $totalFiltered = PmbPesertaOnline::where($data)     
                    ->where(fn($query) =>
                        $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('nama', 'LIKE', "%{$search}%")
                        ->orWhere('nopen', 'LIKE', "%{$search}%")
                        ->orWhere('pilihan1', 'LIKE', "%{$search}%")
                        ->orWhere('pilihan2', 'LIKE', "%{$search}%")
                    )
                    ->count(); 
                }
            }

            $data = [];

            if (!empty($peserta)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($peserta as $row) {
                    $asal_sekolah = PmbAsalSekolah::where('id_peserta',$row->id)->first();
                    $nestedData['id'] =     $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['nopen'] = $row->nopen;
                    $nestedData['is_bayar'] = $row->is_bayar;
                    $nestedData['is_lolos'] = $row->is_lolos;
                    $nestedData['gelombang'] = $gel[$row->gelombang] ?? '';
                    $nestedData['pilihan1'] = $prod[$row->pilihan1] ?? '';
                    $nestedData['pilihan2'] = $prod[$row->pilihan2] ?? '';
                    $nestedData['angkatan'] = $row->angkatan ?? '';
                    $nestedData['asal_sekolah'] = $asal_sekolah->asal_sekolah ?? '';
                    $nestedData['ttl'] = $row->tempat_lahir . ", " . date('d-m-Y', strtotime($row->tanggal_lahir));
                    $nestedData['admin_input_date'] = date('d-m-Y', strtotime($row->admin_input_date));
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
