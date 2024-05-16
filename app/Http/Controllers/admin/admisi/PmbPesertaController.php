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

class PmbPesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id','nama' , 'nopen', 'gelombang', 'pilihan1','pilihan2','ttl'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Peserta";
            $indexed = $this->indexed;
            return view('admin.admisi.peserta.index', compact('title','indexed'));
        }else{

            $gelombang = PmbGelombang::all();
            $gel = [];
            foreach($gelombang as $row){
                $gel[$row->id] = $row->nama_gel;
            }

            $prodi = PmbJalurProdi::select('pmb_jalur_prodi.*','program_studi.nama_jurusan')->join('program_studi','program_studi.id','pmb_jalur_prodi.id_program_studi')->get();
            $prod = [];
            foreach($prodi as $row){
                $prod[$row->id] = $row->nama_jurusan . " " . $row->keterangan;
            }

            $columns = [
                1 => 'id',
                2 => 'nama',
                3 => 'nopen',
                4 => 'gelombang',
                5 => 'pilihan1',
                6 => 'pilihan2',
                7 => 'ttl',
            ];

            $search = [];

            $totalData = PmbPesertaOnline::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $peserta = PmbPesertaOnline::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $peserta = PmbPesertaOnline::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nopen', 'LIKE', "%{$search}%")
                    ->orWhere('gelombang', 'LIKE', "%{$search}%")
                    ->orWhere('pilihan1', 'LIKE', "%{$search}%")
                    ->orWhere('pilihan2', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = PmbPesertaOnline::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama', 'LIKE', "%{$search}%")
                ->orWhere('nopen', 'LIKE', "%{$search}%")
                ->orWhere('gelombang', 'LIKE', "%{$search}%")
                ->orWhere('pilihan1', 'LIKE', "%{$search}%")
                ->orWhere('pilihan2', 'LIKE', "%{$search}%")
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = "Tambah Peserta";
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();
        $ta = ta::where('is_active',1)->first();
        $jalur = PmbJalur::all();
        $mapel = ['mtk'=>'Matematika','bing'=>'B. Inggris','kimia'=>'Kimia','biologi'=>'Biologi','fisika'=>'Fisika'];
        return view('admin.admisi.peserta.create', compact('title','wilayah','ta','jalur','mapel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $filename = '';
        $jalur = $request->input('jalur');
        if ($request->file('foto')) {
            $photo = $request->file('foto');
            $filename = date('YmdHi') . $photo->getClientOriginalName();
            $tujuan_upload = 'assets/pdf/pmb';
            $photo->move($tujuan_upload,$filename);
        }
        $data = array(
            //'nopen' => $set_nopen, tidak ada karena blm verifikasi
            'user_id' => 1,
            'nisn' => '',
            'gelombang' => $request->input('gelombang'),
            'noktp' => $request->input('ktp'),
            'nama' => $request->input('nama'),
            'jk' => $request->input('jk'),
            'agama' => $request->input('agama'),
            'nama_ibu' => $request->input('ibu'),
            'nama_ayah' => $request->input('ayah'),
            'hp_ortu' => $request->input('hp_ortu'),
            'tinggi_badan' => $request->input('tb'),
            'berat_badan' => $request->input('bb'),
            'tempat_lahir' => $request->input('tl'),
            'tanggal_lahir' => $request->input('tgl'),
            'alamat' => $request->input('alamat'),
            'rt' => $request->input('rt'),
            'rw' => $request->input('rw'),
            'kelurahan' => $request->input('kelurahan'),
            'kecamatan' => $request->input('kecamatan'),
            'kotakab' => $request->input('kotakab'),
            'provinsi' => $request->input('provinsi'),
            'kodepos' => $request->input('pos'),
            'telpon' => $request->input('telepon'),
            'hp' => $request->input('hp'),
            'ukuran_seragam' => $request->input('ukuran_seragam'),
            'file_pendukung' => $filename,
            //'asal_sekolah' => $request->input('asal_sekolah'),
            'warga_negara' => $request->input('warga_negara'),
            //'peringkat_pmdp' => $pmdp,
            'jalur_pendaftaran' => $jalur,
            //'kelas' => $request->input('kelas'),
            'pilihan1' => $request->input('prodi')[0],
            'pilihan2' => (!empty($request->input('prodi')[1]))?$request->input('prodi')[1]:'0',
            'info_pmb' => $request->input('info_pmb'),
            'is_bayar' => '0',
            'is_online' => '0',
            'admin_input' => 1,
            'angkatan' => $request->input('gel_ta'),
            'ipk' => $request->input('ipk') ?? '0',
            'toefl' => $request->input('toefl') ?? '0',
            'tahun_ajaran' => $request->input('gel_ta'),
            'is_delete' => '0',
            'is_mundur' => '0',
            'admin_input_date' => date('Y-m-d H:i:s')
         );
         $r = PmbPesertaOnline::create($data);
         $id_peserta = $r->id;
         $data2 = [
            'id_peserta' => $id_peserta,
            'asal_sekolah' => $request->input('asal_sekolah'),
            'jurusan' => $request->input('jurusan'),
            'akreditasi' => $request->input('akreditasi'),
            'alamat' => $request->input('alamat_sekolah'),
            'provinsi_id' => $request->input('provinsi_sekolah'),
            'kota_id' => $request->input('kota_sekolah'),
            'updated_at' => date('Y-m-d H:i:s'),
          ];
          $asal_sekolah = PmbAsalSekolah::create($data2);

          if($jalur == 1 || $jalur == 2){
            $data_nilai = [
                'id_user' => 0,
                'id_peserta' => $id_peserta,
            ];
            $nilai_rapor = PmbNilaiRapor::create($data_nilai);
            $id_rapor = $nilai_rapor->id;
            for($i=0; $i < 5; $i++){
                $data_nilai = [
                    'nilai_mtk_smt'. ($i+1) => $request->input('nilai_mtk_smt' . ($i+1)),
                    'nilai_bing_smt'. ($i+1) => $request->input('nilai_bing_smt' . ($i+1)),
                    'nilai_kimia_smt'. ($i+1) => $request->input('nilai_kimia_smt' . ($i+1)),
                    'nilai_biologi_smt'. ($i+1) => $request->input('nilai_biologi_smt' . ($i+1)),
                    'nilai_fisika_smt'. ($i+1) => $request->input('nilai_fisika_smt' . ($i+1)),
                ];
                $update = PmbNilaiRapor::updateOrCreate(
                    ['id' => $id_rapor],
                    $data_nilai
                );
            }
          }
          Session::flash('message', 'Data Berhasil di simpan');
          return redirect('admin/admisi/peserta');
        //$r = $this->db->insert('pmb_peserta', $data);
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
        $action = 'edit';
        $title = "Edit Peserta";
        $peserta = PmbPesertaOnline::find($id);
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();
        $kota = [];
        if($peserta->provinsi != 0 && !empty($peserta->provinsi)){
            $kota = Wilayah::where('id_induk_wilayah', $peserta->provinsi)->get();
        }

        $kecamatan = [];
        if($peserta->kecamatan != 0 && !empty($peserta->kecamatan)){
            $kecamatan = Wilayah::where('id_induk_wilayah', $peserta->kotakab)->get();
        }
        return view('admin.admisi.peserta.edit', compact('title','wilayah','peserta','kota','kecamatan','id','action'));
    }

    public function edit_gelombang(string $id)
    {
        //
        $action = 'edit_gelombang';
        $title = "Tambah Peserta";
        $peserta = PmbPesertaOnline::find($id);
        $ta = ta::where('is_active',1)->first();
        $jalur = PmbJalur::all();
        $gelombang = [];
        $pilihan = 0;
        $prodi = [];
        if($peserta->jalur_pendaftaran != 0 && !empty($peserta->jalur_pendaftaran)){
            $gelombang = PmbGelombang::where('id_jalur', $peserta->jalur_pendaftaran)->get();
            $pilihan = PmbGelombang::where('id',$peserta->gelombang)->first()->pilihan;
            $prodi = PmbJalurProdi::select('pmb_jalur_prodi.*','program_studi.nama_jurusan')->join('program_studi','program_studi.id','pmb_jalur_prodi.id_program_studi')->where('id_jalur',$peserta->jalur_pendaftaran)->get();
        }

        return view('admin.admisi.peserta.edit_gelombang', compact('title','ta','pilihan','prodi','gelombang','jalur','peserta','id','action'));
    }
    public function edit_asal_sekolah(string $id)
    {
        //
        $action = 'edit_asal_sekolah';
        $title = "Edit Peserta";
        $peserta = PmbPesertaOnline::find($id);
        $wilayah = Wilayah::where('id_induk_wilayah','000000')->get();
        $mapel = ['mtk'=>'Matematika','bing'=>'B. Inggris','kimia'=>'Kimia','biologi'=>'Biologi','fisika'=>'Fisika'];
        $asal = PmbAsalSekolah::where('id_peserta',$id)->first();
        $kota = [];
        if(!empty($asal->provinsi_id) && $asal->provinsi_id != 0){
            $kota = Wilayah::where('id_induk_wilayah', $asal->provinsi_id)->get();
        }
        $rapor = PmbNilaiRapor::where('id_peserta',$id)->first();
        if($rapor){
            $rapor = $rapor->toArray();
        }
        return view('admin.admisi.peserta.edit_asal_sekolah', compact('title','kota','rapor','wilayah','peserta','id','action','mapel','asal'));
    }
    public function edit_file_pendukung(string $id)
    {
        //
        $action = 'edit_file_pendukung';
        $title = "Edit Peserta";
        $peserta = PmbPesertaOnline::find($id);
        return view('admin.admisi.peserta.edit_file_pendukung', compact('title','peserta','id','action'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $action = $request->action;
        if($action == "edit"){

            $data = array(
                'nopen' => $request->input('nopen'),
                'noktp' => $request->input('ktp'),
                'nama' => $request->input('nama'),
                'jk' => $request->input('jk'),
                'agama' => $request->input('agama'),
                'nama_ibu' => $request->input('ibu'),
                'nama_ayah' => $request->input('ayah'),
                'hp_ortu' => $request->input('hp_ortu'),
                'tinggi_badan' => $request->input('tb'),
                'berat_badan' => $request->input('bb'),
                'tempat_lahir' => $request->input('tl'),
                'tanggal_lahir' => $request->input('tgl'),
                'alamat' => $request->input('alamat'),
                'rt' => $request->input('rt'),
                'rw' => $request->input('rw'),
                'kelurahan' => $request->input('kelurahan'),
                'kecamatan' => $request->input('kecamatan'),
                'kotakab' => $request->input('kotakab'),
                'provinsi' => $request->input('provinsi'),
                'kodepos' => $request->input('pos'),
                'telpon' => $request->input('telepon'),
                'hp' => $request->input('hp'),
                'warga_negara' => $request->input('warga_negara'),
             );
            $r = PmbPesertaOnline::updateOrCreate(['id'=>$id],$data);
            Session::flash('message', 'Data Berhasil di simpan');
            return redirect('admin/admisi/peserta/' . $id . '/edit');
        }elseif($action == "edit_gelombang"){
            $data = [
                'jalur_pendaftaran' => $request->jalur,
                'gelombang' => $request->gelombang,
                'pilihan1' => $request->prodi[0],
                'pilihan2' => $request->prodi[1] ?? '',
            ];
            $r = PmbPesertaOnline::updateOrCreate(['id'=>$id],$data);
            Session::flash('message', 'Data Berhasil di simpan');
            return redirect('admin/admisi/peserta/' . $id . '/edit_gelombang');
        }elseif($action == "edit_asal_sekolah"){
            $data = [
                'asal_sekolah' => $request->asal_sekolah,
                'jurusan' => $request->jurusan,
                'akreditasi' => $request->akreditasi,
                'alamat' => $request->alamat_sekolah,
                'provinsi_id' => $request->provinsi_sekolah,
                'kota_id' => $request->kota_sekolah,
            ];
            $update = PmbAsalSekolah::where('id_peserta',$id)->update($data);
            $peserta = PmbPesertaOnline::find($id);
            $jalur = $peserta->jalur_pendaftaran;
            if($jalur == 1 || $jalur == 2){
                $nilai_rapor = PmbNilaiRapor::where('id_peserta',$id)->first();
                $id_rapor = $nilai_rapor->id;
                for($i=0; $i < 5; $i++){
                    $data_nilai = [
                        'nilai_mtk_smt'. ($i+1) => $request->input('nilai_mtk_smt' . ($i+1)),
                        'nilai_bing_smt'. ($i+1) => $request->input('nilai_bing_smt' . ($i+1)),
                        'nilai_kimia_smt'. ($i+1) => $request->input('nilai_kimia_smt' . ($i+1)),
                        'nilai_biologi_smt'. ($i+1) => $request->input('nilai_biologi_smt' . ($i+1)),
                        'nilai_fisika_smt'. ($i+1) => $request->input('nilai_fisika_smt' . ($i+1)),
                    ];
                    $update = PmbNilaiRapor::updateOrCreate(
                        ['id' => $id_rapor],
                        $data_nilai
                    );
                }
            }
            Session::flash('message', 'Data Berhasil di simpan');
            return redirect('admin/admisi/peserta/' . $id . '/edit_asal_sekolah');
        }else{
            $filename = '';
            $peserta = PmbPesertaOnline::find($id);
            $data = [];
            if ($request->file('foto')) {
                $photo = $request->file('foto');
                $filename = date('YmdHi') . $photo->getClientOriginalName();
                $tujuan_upload = 'assets/pdf/pmb';
                $photo->move($tujuan_upload,$filename);
                $data = [
                    'info_pmb' => $request->info_pmb,
                    'ukuran_seragam' => $request->ukuran_seragam,
                    'file_pendukung' => $filename
                ];
            }else{
                $data = [
                    'info_pmb' => $request->info_pmb,
                    'ukuran_seragam' => $request->ukuran_seragam
                ];
            }
            $update = PmbPesertaOnline::updateOrCreate(['id'=>$id],$data);
            Session::flash('message', 'Data Berhasil di simpan');
            return redirect('admin/admisi/peserta/' . $id . '/edit_file_pendukung');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $pmb = PmbPesertaOnline::where('id', $id)->delete();

    }

    public function daftar_kota(Request $request){
        $id_provinsi = $request->input('id');
        $wilayah = Wilayah::where('id_induk_wilayah',$id_provinsi)->get();
        echo json_encode($wilayah);
    }
    public function get_gelombang(Request $request){
        $id = $request->input('id');
        $gelombang = PmbGelombang::where("id_jalur",$id)->get();
        echo json_encode($gelombang);
    }
    public function get_jurusan(Request $request){
        $id = $request->input('id');
        $get_gelombang = PmbGelombang::where('id',$id)->first();
        $get_jalur = PmbJalur::where('id',$get_gelombang->id_jalur)->first();
        $data = [];
        for($i=1; $i <= $get_gelombang->pilihan; $i++){
            $data[$i-1] = PmbJalurProdi::select('pmb_jalur_prodi.*','program_studi.nama_jurusan')->join('program_studi','program_studi.id','pmb_jalur_prodi.id_program_studi')->where('id_jalur',$get_gelombang->id_jalur)->get();
        }
        echo json_encode($data);
    }

}
