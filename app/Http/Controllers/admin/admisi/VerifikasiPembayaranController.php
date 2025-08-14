<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbGelombang;
use App\Models\PmbPesertaOnline;
use App\Models\PmbJalurProdi;
use App\Models\Prodi;
use App\Models\TahunAjaran;
use App\Models\BiayaPendaftaran;

class VerifikasiPembayaranController extends Controller
{
    public $indexed = ['', 'id','nama' , 'nopen','prodi','registrasi_awal'];
    public function index(Request $request){
        $title = "Verifikasi Pembayaran Peserta";
        $date = date('Y-m-d');
        // $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        // $tahun = (int)substr($tahun_ajaran->kode_ta,0,4);
        // $tahun_awal = $tahun+1;
        $tahun_ajaran = PmbGelombang::orderBy('id','desc')->limit(1)->first();
        $gelombang = PmbGelombang::where('ta_awal',$tahun_ajaran->ta_awal)->get();
        $jumlah_diterima = [];
        $jumlah_pendaftar = [];
        $jumlah_verifikasi = [];
        $jumlah_bayar = [];
        foreach($gelombang as $row){
            $jumlah_pendaftar[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->count();
            $jumlah_verifikasi[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('is_verifikasi',1)->count();
            $jumlah_bayar[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('is_bayar',1)->count();
            $jumlah_diterima[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('is_lolos',1)->count();
        }
        return view('admin.admisi.verifikasi_pembayaran.index',compact('title','date','gelombang','jumlah_diterima','jumlah_verifikasi','jumlah_bayar','jumlah_pendaftar'));
    }
    public function show(Request $request, string $id){
        if (empty($request->input('length'))) {
            $gelombang = PmbGelombang::find($id);
            $title2 = "Calon Mahasiswa Gelombang " . $gelombang->nama_gel;
            $title = "Verifikasi_Pembayaran";
            $indexed = $this->indexed;
            return view('admin.admisi.verifikasi_pembayaran.peserta', compact('title','title2','indexed','id'));
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
                4 => 'prodi',
                5 => 'registrasi_awal',
            ];

            $search = [];

            $totalData = PmbPesertaOnline::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $peserta = PmbPesertaOnline::where('gelombang',$id)->where('is_lolos',1)->whereRaw('nopen <> ""')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $peserta = PmbPesertaOnline::where('gelombang',$id)->where('is_lolos',1)->whereRaw('nopen <> ""')
                    ->where(function($query) use ($search){
                        $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('nama', 'LIKE', "%{$search}%")
                        ->orWhere('nopen', 'LIKE', "%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = PmbPesertaOnline::where('gelombang',$id)->where('is_lolos',1)->whereRaw('nopen <> ""')
                ->where(function($query) use ($search){
                    $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nopen', 'LIKE', "%{$search}%");
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
                    $nestedData['prodi'] = $prod[$row->pilihan1] ?? '';
                    $nestedData['registrasi_awal'] = $row->registrasi_awal;
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
    public function edit(String $id){
        $peserta[0] = PmbPesertaOnline::select('pmb_peserta_online.*','program_studi.nama_prodi')->join('program_studi','program_studi.id','=','pmb_peserta_online.pilihan1')->where('pmb_peserta_online.id',$id)->first();
        $peserta[1] = BiayaPendaftaran::where('id_prodi',$peserta[0]->pilihan1)->where('tahun_ajaran',date('Y'))->whereNotNull('registrasi')->orderBy('id','desc')->first();
        return response()->json($peserta);
    }
    public function store(Request $request){
        $pmb = PmbPesertaOnline::find($request->id);
        $pmb->registrasi_awal = $request->registrasi_awal;
        $pmb->save();
        return response()->json('Updated');
    }
}
