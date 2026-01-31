<?php

namespace App\Http\Controllers\admin\aset;

use App\Http\Controllers\Controller;
use App\Models\AsetTanah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsetTanahController extends Controller
{
    /**
    * menampilkan data aset tanah.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public $indexed = ['', 'id', 'kode', 'nama', 'alamat', 'luas'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "tanah";
            $title2 = "Aset Tanah";
            $indexed = $this->indexed;
            $asetTanah = AsetTanah::all();
            $statsJenisTanah = AsetTanah::select('status_tanah')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('status_tanah')
                ->pluck('total', 'status_tanah')
                ->toArray();
            $statsLuasTanah = AsetTanah::selectRaw('
                CASE
                    WHEN luas < 100 THEN "Kecil (< 100 m²)"
                    WHEN luas BETWEEN 100 AND 500 THEN "Sedang (100-500 m²)"
                    WHEN luas > 500 THEN "Besar (> 500 m²)"
                    ELSE "Tidak Diketahui"
                END AS kategori_luas,
                COUNT(*) as total
            ')
            ->groupBy('kategori_luas')
            ->pluck('total', 'kategori_luas')
            ->toArray();
            $totalLuas = AsetTanah::sum('luas');

            return view('admin.aset.tanah.index', compact('title', 'title2', 'indexed', 'asetTanah', 'statsJenisTanah', 'statsLuasTanah', 'totalLuas'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode',
                3 => 'nama',
                4 => 'alamat',
                5 => 'luas',
            ];

            $search = [];

            $totalData = AsetTanah::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $asetTanah = AsetTanah::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            } else {
                $search = $request->input('search.value');

                $asetTanah = AsetTanah::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('alamat', 'LIKE', "%{$search}%")
                    ->orWhere('luas', 'LIKE', "%{$search}%")
                    ->orWhere('tanggal_perolehan', 'LIKE', "%{$search}%")
                    ->orWhere('no_sertifikat', 'LIKE', "%{$search}%")
                    ->orWhere('status_tanah', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = AsetTanah::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('alamat', 'LIKE', "%{$search}%")
                    ->orWhere('luas', 'LIKE', "%{$search}%")
                    ->orWhere('tanggal_perolehan', 'LIKE', "%{$search}%")
                    ->orWhere('no_sertifikat', 'LIKE', "%{$search}%")
                    ->orWhere('status_tanah', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%")
                    ->count();
            }

            $data = [];
            if (!empty($asetTanah)) {

                foreach ($asetTanah as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['kode'] = $row->kode;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['alamat'] = $row->alamat;
                    $nestedData['luas'] = $row->luas;
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
    * menyimpan data aset tanah.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function store(Request $request)
    {
        $id = $request->id;

        $tujuan_upload = 'assets/images/aset/tanah';

        if (!file_exists($tujuan_upload)) {
            mkdir($tujuan_upload, 0777, true);
        }

        if ($id) 
        {
            if($request->hasFile('buktiFisik')){
                $file = $request->file('buktiFisik');
                $cutter = time() . '-' . $request->kode . '-' . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $cutter);

                $asetTanah = AsetTanah::updateOrCreate(
                    ['id' => $id],
                    [
                        'kode' => $request->kode, 
                        'nama' => $request->nama, 
                        'alamat' => $request->alamat, 
                        'luas' => $request->luas, 
                        'tanggal_perolehan' => $request->tanggalPerolehan, 
                        'no_sertifikat' => $request->noSertifikat, 
                        'status_tanah' => $request->statusTanah, 
                        'keterangan' => $request->keterangan, 
                        'bukti_fisik' => $fileName
                    ]
                );
                $file->move($tujuan_upload, $fileName);
            }else{
                $asetTanah = AsetTanah::updateOrCreate(
                    ['id' => $id],
                    [
                        'kode' => $request->kode, 
                        'nama' => $request->nama, 
                        'alamat' => $request->alamat, 
                        'luas' => $request->luas, 
                        'tanggal_perolehan' => $request->tanggalPerolehan, 
                        'no_sertifikat' => $request->noSertifikat, 
                        'status_tanah' => $request->statusTanah, 
                        'keterangan' => $request->keterangan, 
                    ]
                );
            }
            return response()->json('Updated');
        } else 
        {
            $request->validate([
                'kode' => 'required|string|unique:aset_tanah,kode',
            ]);

            if($request->hasFile('buktiFisik'))
            {
                $file = $request->file('buktiFisik');
                $cutter = time() . '-' . $request->kode . '-' . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $cutter);

                $asetTanah = AsetTanah::updateOrCreate(
                    ['id' => $id],
                    [
                        'kode' => $request->kode, 
                        'nama' => $request->nama, 
                        'alamat' => $request->alamat, 
                        'luas' => $request->luas, 
                        'tanggal_perolehan' => $request->tanggalPerolehan, 
                        'no_sertifikat' => $request->noSertifikat, 
                        'status_tanah' => $request->statusTanah, 
                        'keterangan' => $request->keterangan, 
                        'bukti_fisik' => $fileName
                    ]
                );
                $file->move($tujuan_upload, $fileName);
            }else
            {
                $asetTanah = AsetTanah::updateOrCreate(
                    ['id' => $id],
                    [
                        'kode' => $request->kode, 
                        'nama' => $request->nama, 
                        'alamat' => $request->alamat, 
                        'luas' => $request->luas, 
                        'tanggal_perolehan' => $request->tanggalPerolehan, 
                        'no_sertifikat' => $request->noSertifikat, 
                        'status_tanah' => $request->statusTanah, 
                        'keterangan' => $request->keterangan, 
                    ]
                );
            }

            if ($asetTanah) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Academic');
            }
        }
    }

    /**
    * menampilkan spesifik data aset tanah.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function show(string $id)
    {
        $where = ['id' => $id];

        $asetTanah = AsetTanah::where($where)->first();

        if ($asetTanah) {
            $asetTanah->tanggal_perolehan = $asetTanah->tanggal_perolehan ? Carbon::parse($asetTanah->tanggal_perolehan)
                ->locale('id')
                ->translatedFormat('d F Y') : '';
        }

        return response()->json($asetTanah);
    }

    /**
    * menampilkan spesifik data aset tanah.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function edit(string $id)
    {
        $where = ['id' => $id];

        $asetTanah = AsetTanah::where($where)->first();

        return response()->json($asetTanah);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
    * menghapus data aset tanah.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function destroy(string $id)
    {
        $asetTanah = AsetTanah::where('id', $id)->first();

        if (!$asetTanah) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $lokasiBukti = 'assets/images/aset/tanah/' . $asetTanah->buktifisik;

        if (file_exists($lokasiBukti) && is_file($lokasiBukti)) {
            unlink($lokasiBukti);
        }

        $asetTanah->delete();

        return response()->json(['message' => 'Data berhasil dihapus.'], 200);
    }

}