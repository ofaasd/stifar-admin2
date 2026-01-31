<?php

namespace App\Http\Controllers\admin\aset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AsetBarang;
use App\Models\MasterJenisBarang;
use App\Models\MasterKategoriAset;
use App\Models\MasterRuang;
use App\Models\MasterVendor;
use App\Models\PegawaiBiodatum;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AsetBarangController extends Controller
{
    /**
    * menampilkan data aset barang.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public $indexed = ['', 'id', 'namaRuang', 'kodeJenisBarang', 'label', 'nama', 'namaPenanggungJawab', 'pemeriksaanTerakhir'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "barang";
            $title2 = "Aset Barang";
            $indexed = $this->indexed;
            $asetRuang = MasterRuang::orderBy('nama_ruang', 'asc')->get();
            $dataPegawai = PegawaiBiodatum::orderBy('nama_lengkap', 'asc')->get();
            $dataVendor = MasterVendor::orderBy('nama', 'asc')->get();
            $dataJenisBarang = MasterJenisBarang::orderBy('kode', 'asc')->get();
            $dataKategori = MasterKategoriAset::orderBy('nama', 'asc')->get();
            $statsJenisBarang = AsetBarang::select('master_jenis_barang.nama')
                ->leftJoin('master_jenis_barang', 'master_jenis_barang.kode', '=', 'aset_barang.kode_jenis_barang')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'aset_barang.id_penanggung_jawab')
                ->groupBy('master_jenis_barang.nama')
                ->selectRaw('master_jenis_barang.nama, COUNT(*) as jumlah')
                ->pluck('jumlah', 'master_jenis_barang.nama');

            $statsVendorBarang = AsetBarang::select('master_vendor.nama')
                ->leftJoin('master_vendor', 'master_vendor.kode', '=', 'aset_barang.kode_vendor')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'aset_barang.id_penanggung_jawab')
                ->groupBy('master_vendor.nama')
                ->selectRaw('master_vendor.nama, COUNT(*) as jumlah')
                ->pluck('jumlah', 'master_vendor.nama');

            $statsKategoriBarang = AsetBarang::select('master_kategori_aset.nama')
                ->leftJoin('master_kategori_aset', 'master_kategori_aset.kode', '=', 'aset_barang.kode_kategori')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'aset_barang.id_penanggung_jawab')
                ->groupBy('master_kategori_aset.nama')
                ->selectRaw('master_kategori_aset.nama, COUNT(*) as jumlah')
                ->pluck('jumlah', 'master_kategori_aset.nama');

            return view('admin.aset.barang.index', compact('title', 'title2', 'indexed', 'asetRuang', 'dataJenisBarang', 'dataPegawai', 'dataVendor', 'dataKategori', 'statsJenisBarang', 'statsVendorBarang', 'statsKategoriBarang'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'namaRuang',
                3 => 'namaPenanggungJawab',
                4 => 'label',
                5 => 'kodeJenisBarang',
                6 => 'nama',
                7 => 'pemeriksaanTerakhir',
            ];

            $search = [];

            $totalData = AsetBarang::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            
            // base query grouped by aset_barang.id to avoid duplicates caused by joins
            $baseQuery = AsetBarang::select([
                    'aset_barang.*',
                    'master_ruang.nama_ruang AS namaRuang',
                    'pegawai_biodata.nama_lengkap AS namaPenanggungJawab',
                    'master_jenis_barang.kode AS kodeJenisBarang',
                    DB::raw('DATE(aset_barang.pemeriksaan_terakhir) AS pemeriksaanTerakhir')
                ])
                ->leftJoin('master_ruang', DB::raw("REPLACE(master_ruang.nama_ruang, ' ', '')"), '=', 'aset_barang.kode_ruang')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'aset_barang.id_penanggung_jawab')
                ->leftJoin('master_jenis_barang', 'master_jenis_barang.kode', '=', 'aset_barang.kode_jenis_barang')
                ->groupBy('aset_barang.id', 'master_ruang.nama_ruang', 'pegawai_biodata.nama_lengkap', 'master_jenis_barang.kode', DB::raw('DATE(aset_barang.pemeriksaan_terakhir)'));

            if (empty($request->input('search.value'))) {
                $asetBarang = (clone $baseQuery)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            } else {
                $search = $request->input('search.value');

                // clone the base query and apply grouped where conditions to avoid unexpected OR precedence
                $searchQuery = (clone $baseQuery);
                $searchQuery->where(function ($q) use ($search) {
                    $q->where('master_ruang.nama_ruang', 'LIKE', "%{$search}%")
                      ->orWhere('pegawai_biodata.nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('aset_barang.label', 'LIKE', "%{$search}%")
                      ->orWhere('aset_barang.elektronik', 'LIKE', "%{$search}%");
                });

                $asetBarang = (clone $searchQuery)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

                // count distinct grouped results for filtered total
                $totalFiltered = (clone $searchQuery)->get()->count();
            }

            $data = [];
            if (!empty($asetBarang)) {

                foreach ($asetBarang as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['namaRuang'] = $row->namaRuang;
                    $nestedData['namaPenanggungJawab'] = $row->namaPenanggungJawab;
                    $nestedData['kodeJenisBarang'] = $row->kodeJenisBarang;
                    $nestedData['label'] = $row->label;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['pemeriksaanTerakhir'] = $row->pemeriksaanTerakhir;
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
    * menyimpan data aset barang.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function store(Request $request)
    {
        try {
            $id = $request->id;
            
            $validatedData = $request->validate([
                'nama'      => 'required|string',
                'kodeJenisBarang'  => 'required',
                'spesifikasi'      => 'required',
                'anggaran'      => 'required',
                'jumlah'      => 'required',
                'kodeRuang' => 'required',
                'estimasiPemakaian'      => 'required',
                'durasiPemakaian'      => 'required',
                'tanggalPembelian'      => 'required',
                'harga'      => 'required',
                'kodeVendor'      => 'required',
                'kondisiFisik'      => 'required',
                'inventarisLama'      => 'required',
                'inventarisBaru'      => 'nullable',
                'keterangan'      => 'required',
                'idPenanggungJawab'  => 'required',
                'label'      => $id
                                ? ['nullable', 'string', Rule::unique('aset_barang', 'label')->ignore($id)]
                                : 'nullable|string|unique:aset_barang,label',
                'elektronik'      => 'required',
                'pemeriksaanTerakhir'      => 'required',
                'kodeKategori'      => 'required',
            ]);
            
            $kodeRuang = $validatedData['kodeRuang'];
            $kodeJenisBarang = $validatedData['kodeJenisBarang'];

            $nomorUrutTerbesar2 = AsetBarang::where('kode_jenis_barang', 'LIKE', $kodeRuang . '/' . $kodeJenisBarang . '/%')
                ->selectRaw('MAX(CAST(SUBSTRING_INDEX(kode_jenis_barang, "/", -1) AS UNSIGNED)) as max_urut')
                ->value('max_urut') ?? 0;

            $nomorUrutBaru2 = $nomorUrutTerbesar2 + 1;
            $label = $kodeRuang . "/" . $kodeJenisBarang . "/" . $nomorUrutBaru2;
            
            $save = AsetBarang::updateOrCreate(
                ['id' => $id],
                [
                    'nama' => $validatedData['nama'],
                    'kode_jenis_barang' => $kodeJenisBarang,
                    'spesifikasi' => $validatedData['spesifikasi'],
                    'anggaran' => $validatedData['anggaran'],
                    'jumlah' => $validatedData['jumlah'],
                    'kode_ruang' => $kodeRuang,
                    'estimasi_pemakaian' => $validatedData['estimasiPemakaian'],
                    'durasi_pemakaian' => $validatedData['durasiPemakaian'],
                    'tanggal_pembelian' => $validatedData['tanggalPembelian'],
                    'harga' => $validatedData['harga'],
                    'kode_vendor' => $validatedData['kodeVendor'],
                    'kondisi_fisik' => $validatedData['kondisiFisik'],
                    'inventaris_lama' => $validatedData['inventarisLama'],
                    'inventaris_baru' => $validatedData['inventarisBaru'],
                    'keterangan' => $validatedData['keterangan'],
                    'id_penanggung_jawab' => $validatedData['idPenanggungJawab'],
                    'label' => $label,
                    'elektronik' => $validatedData['elektronik'],
                    'pemeriksaan_terakhir' => $validatedData['pemeriksaanTerakhir'],
                    'kode_kategori' => $validatedData['kodeKategori']
                ]
            );

            if ($id) {
                return response()->json('Updated');
            } elseif ($save) {
                return response()->json('Created');
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save Kategori Aset',
                'error' => $e->getMessage(),
            ], 500);
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
    * menampilkan spesifik data aset barang.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function edit(string $id)
    {
        $data = AsetBarang::where("id", $id)->first();

        $data->tanggal_pembelian = \Carbon\Carbon::parse($data->tanggal_pembelian)->format('Y-m-d');
        $data->pemeriksaan_terakhir = \Carbon\Carbon::parse($data->pemeriksaan_terakhir)->format('Y-m-d');


        if ($data) {
            return response()->json($data); // Kembalikan objek KategoriAset langsung
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
    * menghapuskan data aset barang.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function destroy(string $id)
    {
        $data = AsetBarang::where('id', $id)->delete();
    }
}
