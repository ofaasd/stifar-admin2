<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbGelombang;
use App\Models\PmbPesertaOnline;
use App\Models\PmbJalurProdi;
use App\Models\Prodi;
use App\Models\TahunAjaran;
use App\helpers;

class PengumumanController extends Controller
{
    //
    public $indexed = ['', 'id','nama' , 'nopen','pilihan1','pilihan2','verifikasi','pembayaran','diterima'];
    public function index(Request $request){
        $title = "Surat Pengumuman Peserta";
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
        return view('admin.admisi.pengumuman.index',compact('title','date','gelombang','jumlah_diterima','jumlah_verifikasi','jumlah_bayar','jumlah_pendaftar'));
    }
    public function peserta(Request $request, string $id){
        if (empty($request->input('length'))) {
            $gelombang = PmbGelombang::find($id);
            $title2 = "Peserta Gelombang " . $gelombang->nama_gel;
            $title = "Peserta";
            $indexed = $this->indexed;
            return view('admin.admisi.pengumuman.peserta', compact('title','title2','indexed','id'));
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
                4 => 'pilihan1',
                5 => 'pilihan2',
                6 => 'verifikasi',
                7 => 'pembayaran',
                8 => 'diterima',
            ];

            $search = [];

            $totalData = PmbPesertaOnline::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $peserta = PmbPesertaOnline::where('gelombang',$id)->whereRaw('nopen <> ""')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $peserta = PmbPesertaOnline::where('gelombang',$id)->whereRaw('nopen <> ""')
                    ->where(function($query) use ($search){
                        $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('nama', 'LIKE', "%{$search}%")
                        ->orWhere('nopen', 'LIKE', "%{$search}%");
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = PmbPesertaOnline::where('gelombang',$id)->whereRaw('nopen <> ""')
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
                    $nestedData['pilihan1'] = $prod[$row->pilihan1] ?? '';
                    $nestedData['pilihan2'] = $prod[$row->pilihan2] ?? '';
                    $nestedData['verifikasi'] = $row->is_verifikasi;
                    $nestedData['pembayaran'] = $row->is_bayar;
                    $nestedData['diterima'] = $row->is_lolos;
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
    public function edit_peserta($id){
        $peserta[0] = PmbPesertaOnline::find($id);
        $peserta[1] = Prodi::find($peserta[0]->pilihan1);
        $peserta[2] = Prodi::find($peserta[0]->pilihan2);
        return response()->json($peserta);
    }
    public function simpan_peserta(Request $request){
        $peserta = PmbPesertaOnline::find($request->id);
        $peserta->is_lolos = $request->is_lolos;
        $peserta->final_prodi = $request->final_prodi;
        $peserta->pilihan1 = $request->final_prodi;
        $peserta->save();
        if($request->is_lolos == 1){
            $data['no_wa'] = $peserta->hp;
            $message = "*Pesan ini otomatis dikirim dari sistem* \n\n Halo, " . $peserta->nama . ", \n\n Selamat !! anda dinyatakan *LULUS* dan resmi diterima sebagai mahasiswa baru STIFAR,\n\nUntuk informasi selanjutnya akan disampaikan melalui WAG (Whatsapp Group). Jika kamu blm bergabung kamu bisa login kembali melalui \n https://pendaftaran.stifar.ac.id/ \n dan masuk ke menu *pengumuman* untuk dapat bergabung ke WAG admisi STIFAR \n\n jika terdapat kendala dapat menghubungi no. 081393111171 \n sebagai media center PMB STIFAR 2025 \n\n Terimakasih, \n Admin PMB STIFAR";
            $data['pesan'] = $message;

            $nohp = $peserta->hp;
            $pesan = helpers::send_wa($data);
            return response()->json("Sended");
        }else{
            return response()->json("Saved");
        }


    }
}
