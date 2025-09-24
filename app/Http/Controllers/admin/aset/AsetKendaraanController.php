<?php

namespace App\Http\Controllers\admin\aset;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\AsetKendaraan;
use App\Models\PegawaiBiodatum;
use App\Models\MasterJenisKendaaran;
use App\Models\MerkKendaraan;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AsetKendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'kode', 'nomorPolisi', 'namaKendaraan', 'transmisi', 'namaPenanggungJawab'];
    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "kendaraan";
            $title2 = "Aset Kendaraan";
            $indexed = $this->indexed;
            $dataJenisKendaraan = MasterJenisKendaaran::orderBy('kode', 'asc')->get();
            $dataMerkKendaraan = MerkKendaraan::orderBy('kode', 'asc')->get();
            $dataPegawai = PegawaiBiodatum::orderBy('nama_lengkap', 'asc')->get();
            return view('admin.aset.kendaraan.index', compact('title', 'title2', 'indexed', 'dataJenisKendaraan', 'dataMerkKendaraan', 'dataPegawai'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'kode',
                3 => 'nomorPolisi',
                4 => 'namaKendaraan',
                5 => 'transmisi',
                6 => 'namaPenanggungJawab',
            ];

            $search = [];

            $totalData = AsetKendaraan::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $getData = AsetKendaraan::select([
                    'aset_kendaraan.*',
                    'aset_kendaraan.nama AS namaKendaraan',
                    'aset_kendaraan.nomor_polisi AS nomorPolisi',
                    'master_jenis_kendaraan.kode AS kodeJenisKendaraan',
                    'pegawai_biodata.nama_lengkap AS namaPenanggungJawab',
                    'master_merk_kendaraan.kode AS kodeMerkKendaraan',
                    DB::raw('DATE(aset_kendaraan.pemeriksaan_terakhir) AS pemeriksaanTerakhir')
                ])
                ->leftJoin('master_jenis_kendaraan', 'master_jenis_kendaraan.kode', '=', 'aset_kendaraan.kode_jenis_kendaraan')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'aset_kendaraan.id_penanggung_jawab')
                ->leftJoin('master_merk_kendaraan', 'master_merk_kendaraan.kode', '=', 'aset_kendaraan.kode_merek_kendaraan')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            } else {
                $search = $request->input('search.value');

                $getData = AsetKendaraan::select([
                    'aset_kendaraan.*',
                    'master_jenis_kendaraan.kode AS kodeJenisKendaraan',
                    'pegawai_biodata.nama_lengkap AS namaPenanggungJawab',
                    'master_merk_kendaraan.kode AS kodeMerkKendaraan',
                    DB::raw('DATE(aset_kendaraan.pemeriksaan_terakhir) AS pemeriksaanTerakhir')
                ])
                ->leftJoin('master_jenis_kendaraan', 'master_jenis_kendaraan.kode', '=', 'aset_kendaraan.kode_jenis_kendaraan')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'aset_kendaraan.id_penanggung_jawab')
                ->leftJoin('master_merk_kendaraan', 'master_merk_kendaraan.kode', '=', 'aset_kendaraan.kode_merek_kendaraan')
                ->where('aset_kendaraan.id', 'LIKE', "%{$search}%")
                ->orWhere('namaRuang', 'LIKE', "%{$search}%")
                ->orWhere('namaPenanggungJawab', 'LIKE', "%{$search}%")
                ->orWhere('kodeJenisKendaraan', 'LIKE', "%{$search}%")
                ->orWhere('kodeMerkKendaraan', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.kode', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.nama', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.nomor_polisi', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.tanggal_perolehan', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.harga_perolehan', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.harga_penyusutan', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.nomor_rangka', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.bahan_bakar', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.transmisi', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.kapasitas_mesin', 'LIKE', "%{$search}%")
                ->orWhere('pemeriksaanTerakhir', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

               $getData = AsetKendaraan::select([
                    'aset_kendaraan.*',
                    'master_jenis_kendaraan.kode AS kodeJenisKendaraan',
                    'pegawai_biodata.nama_lengkap AS namaPenanggungJawab',
                    'master_merk_kendaraan.kode AS kodeMerkKendaraan',
                    DB::raw('DATE(aset_kendaraan.pemeriksaan_terakhir) AS pemeriksaanTerakhir')
                ])
                ->leftJoin('master_jenis_kendaraan', 'master_jenis_kendaraan.kode', '=', 'aset_kendaraan.kode_jenis_kendaraan')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'aset_kendaraan.id_penanggung_jawab')
                ->leftJoin('master_merk_kendaraan', 'master_merk_kendaraan.kode', '=', 'aset_kendaraan.kode_merk_kendaraan')
                ->where('aset_kendaraan.id', 'LIKE', "%{$search}%")
                ->orWhere('namaRuang', 'LIKE', "%{$search}%")
                ->orWhere('namaPenanggungJawab', 'LIKE', "%{$search}%")
                ->orWhere('kodeJenisKendaraan', 'LIKE', "%{$search}%")
                ->orWhere('kodeMerkKendaraan', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.kode', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.nama', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.nomor_polisi', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.tanggal_perolehan', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.harga_perolehan', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.harga_penyusutan', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.nomor_rangka', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.bahan_bakar', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.transmisi', 'LIKE', "%{$search}%")
                ->orWhere('aset_kendaraan.kapasitas_mesin', 'LIKE', "%{$search}%")
                ->orWhere('pemeriksaanTerakhir', 'LIKE', "%{$search}%")
                ->count();
            }

            $getData->transform(function ($item) {
                $item->transmisi = $item->transmisi ? ucwords($item->transmisi) : '';
                return $item;
            });

            $data = [];
            if (!empty($getData)) {

                foreach ($getData as $index => $row) {
                    $nestedData = [];
                    $nestedData['fake_id'] = $start + $index + 1;
                    $nestedData['id'] = $row->id;
                    $nestedData['kode'] = $row->kode;
                    $nestedData['nomorPolisi'] = $row->nomorPolisi;
                    $nestedData['namaKendaraan'] = $row->namaKendaraan;
                    $nestedData['transmisi'] = $row->transmisi;
                    $nestedData['namaPenanggungJawab'] = $row->namaPenanggungJawab;
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
        try {
            $id = $request->id;
            
            $validatedData = $request->validate([
                'kode'      => $id
                                ? ['nullable', 'string', Rule::unique('aset_kendaraan', 'label')->ignore($id)]
                                : 'nullable|string|unique:aset_kendaraan,label',
                'kodeJenisKendaraan'  => 'required',
                'kodeMerkKendaraan'  => 'required',
                'idPenanggungJawab'  => 'required',
                'nama'      => 'required|string',
                'nomorPolisi'  => 'required',
                'tanggalPerolehan'  => 'required',
                'hargaPerolehan'      => 'required',
                'hargaPenyusutan'      => 'nullable',
                'nomorRangka'      => 'required',
                'bahanBakar'      => 'required',
                'transmisi'      => 'required',
                'kapasitasMesin'      => 'required',
                'pemeriksaanTerakhir'      => 'required',
            ]);

            $kodeJenisKendaraan = $validatedData['kodeJenisKendaraan'];
            $kodeMerkKendaraan = $validatedData['kodeMerkKendaraan'];

            $nomorUrutTerbesar2 = AsetKendaraan::where('kode_jenis_kendaraan', 'LIKE', $kodeJenisKendaraan . '/' . $kodeMerkKendaraan . '/%')
                ->selectRaw('MAX(CAST(SUBSTRING_INDEX(kode_jenis_kendaraan, "/", -1) AS UNSIGNED)) as max_urut')
                ->value('max_urut') ?? 0;

            $nomorUrutBaru2 = $nomorUrutTerbesar2 + 1;
            $kode = $kodeJenisKendaraan . "/" . $kodeMerkKendaraan . "/" . $nomorUrutBaru2;
            
            $save = AsetKendaraan::updateOrCreate(
                ['id' => $id],
                [
                    'kode' => $kode,
                    'kode_jenis_kendaraan' => $validatedData['kodeJenisKendaraan'],
                    'kode_merek_kendaraan' => $validatedData['kodeMerkKendaraan'],
                    'id_penanggung_jawab' => $validatedData['idPenanggungJawab'],
                    'nama' => $validatedData['nama'],
                    'nomor_polisi' => $validatedData['nomorPolisi'],
                    'tanggal_perolehan' => $validatedData['tanggalPerolehan'],
                    'harga_perolehan' => $validatedData['hargaPerolehan'],
                    'harga_penyusutan' => $validatedData['hargaPenyusutan'],
                    'nomor_rangka' => $validatedData['nomorRangka'],
                    'bahan_bakar' => $validatedData['bahanBakar'],
                    'transmisi' => $validatedData['transmisi'],
                    'kapasitas_mesin' => $validatedData['kapasitasMesin'],
                    'pemeriksaan_terakhir' => $validatedData['pemeriksaanTerakhir'],
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
        $where = ['aset_kendaraan.id' => $id];

        $data = AsetKendaraan::select([
                    'aset_kendaraan.*',
                    'aset_kendaraan.nama AS nama_kendaraan',
                    'master_jenis_kendaraan.nama AS jenis',
                    'pegawai_biodata.nama_lengkap AS penanggung_jawab',
                    'master_merk_kendaraan.nama AS merek',
                ])
                ->leftJoin('master_jenis_kendaraan', 'master_jenis_kendaraan.kode', '=', 'aset_kendaraan.kode_jenis_kendaraan')
                ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'aset_kendaraan.id_penanggung_jawab')
                ->leftJoin('master_merk_kendaraan', 'master_merk_kendaraan.kode', '=', 'aset_kendaraan.kode_merek_kendaraan')
                ->where($where)
                ->first();

        if ($data) {
            $data->tanggal_perolehan = $data->tanggal_perolehan ? Carbon::parse($data->tanggal_perolehan)
                ->locale('id')
                ->translatedFormat('d F Y') : '';

            $data->transmisi = $data->transmisi ? ucwords($data->transmisi) : '';

            $data->bahan_bakar = $data->bahan_bakar ? ucwords($data->bahan_bakar) : '';

            $data->pemeriksaan_terakhir = $data->pemeriksaan_terakhir ? Carbon::parse($data->pemeriksaan_terakhir)
                ->locale('id')
                ->translatedFormat('d F Y') : '';
        }

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = AsetKendaraan::where("id", $id)->first();

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = AsetKendaraan::where('id', $id)->delete();
    }
}
