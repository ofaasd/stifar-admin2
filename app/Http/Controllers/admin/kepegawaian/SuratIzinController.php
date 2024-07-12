<?php

namespace App\Http\Controllers\admin\kepegawaian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuratIzin;
use App\Models\RefKategoriSurat;
use App\Models\PegawaiBiodatum;

class SuratIzinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'id_pegawai','tgl_surat', 'perihal', 'keterangan', 'id_kategori','izin_mgr_sdm', 'izin_ka_jenjang'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "surat_izin";
            $title2 = "Surat Izin";
            $indexed = $this->indexed;
            $kategori = RefKategoriSurat::all();
            $pegawai = PegawaiBiodatum::all();
            return view('admin.kepegawaian.surat_izin.index', compact('kategori','pegawai','title','indexed','title2'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'id_pegawai',
                3 => 'tgl_surat',
                4 => 'perihal',
                5 => 'keterangan',
                6 => 'id_kategori',
                7 => 'izin_mgr_sdm',
                8 => 'izin_ka_jenjang',
            ];

            $search = [];

            $totalData = SuratIzin::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $surat = SuratIzin::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $surat = SuratIzin::Where('tgl_surat', 'LIKE', "%{$search}%")
                    ->orWhere('perihal', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = SuratIzin::Where('tgl_surat', 'LIKE', "%{$search}%")
                ->orWhere('perihal', 'LIKE', "%{$search}%")
                ->orWhere('keterangan', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($surat)) {
            // providing a dummy id instead of database ids
                $ids = $start;
                $list_pegawai = PegawaiBiodatum::all();
                $pegawai = [];
                foreach($list_pegawai as $row){
                    $pegawai[$row->id] = $row->nama_lengkap;
                }
                $list_kategori = RefKategoriSurat::all();
                $kategori = [];
                foreach($list_kategori as $row){
                    $kategori[$row->id] = $row->nama;
                }
                foreach ($surat as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['id_pegawai'] = $pegawai[$row->id_pegawai];
                    $nestedData['tgl_surat'] = $row->tgl_surat;
                    $nestedData['perihal'] = $row->perihal;
                    $nestedData['keterangan'] = $row->keterangan;
                    $nestedData['id_kategori'] = $kategori[$row->id_kategori];
                    $nestedData['izin_mgr_sdm'] = ($row->izin_mgr_sdm == 0)?"Tidak Disetujui":"Disetujui";
                    $nestedData['izin_ka_jenjang'] = ($row->izin_ka_jenjang == 0)?"Tidak Disetujui":"Disetujui";
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $id = $request->id;

        if ($id) {
            $filename = '';
            if ($request->file('dokumen') != null) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/surat_izin';
                $dokumen->move($tujuan_upload,$filename);
            }
            if(!empty($filename)){
                $jabatan = SuratIzin::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_pegawai' => $request->id_pegawai,
                        'tgl_surat' => $request->tgl_surat,
                        'perihal' => $request->perihal,
                        'keterangan' => $request->keterangan,
                        'izin_mgr_sdm' => $request->izin_mgr_sdm ?? 0,
                        'izin_ka_jenjang' => $request->izin_ka_jenjang ?? 0,
                        'id_kategori' => $request->id_kategori,
                        'dokumen' => $filename
                    ]
                );
            }else{
                $jabatan = SuratIzin::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_pegawai' => $request->id_pegawai,
                        'tgl_surat' => $request->tgl_surat,
                        'perihal' => $request->perihal,
                        'keterangan' => $request->keterangan,
                        'izin_mgr_sdm' => $request->izin_mgr_sdm ?? 0,
                        'izin_ka_jenjang' => $request->izin_ka_jenjang ?? 0,
                        'id_kategori' => $request->id_kategori,
                    ]
                );
            }


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('dokumen')) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/surat_izin';
                $dokumen->move($tujuan_upload,$filename);
            }
            $jabatan = SuratIzin::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'tgl_surat' => $request->tgl_surat,
                    'perihal' => $request->perihal,
                    'keterangan' => $request->keterangan,
                    'izin_mgr_sdm' => $request->izin_mgr_sdm ?? 0,
                    'izin_ka_jenjang' => $request->izin_ka_jenjang ?? 0,
                    'id_kategori' => $request->id_kategori,
                    'dokumen' => $filename
                ]
            );

            if ($jabatan) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create PT');
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
        $where = ['id' => $id];

        $surat = SuratIzin::where($where)->first();
        return response()->json($surat);
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
        $surat = SuratIzin::where('id', $id)->delete();
    }
}
