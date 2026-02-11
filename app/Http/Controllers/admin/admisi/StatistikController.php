<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbPesertaOnline;
use App\Models\PmbGelombang;
use App\Models\Prodi;
use App\Models\PegawaiBiodatum;
use DB;

class StatistikController extends Controller
{
    //
    public function index(){
        $ta_max = PmbGelombang::selectRaw('max(ta_awal) as ta_max')->limit(1)->first()->ta_max;
        $curr_ta = $ta_max;
        $gelombang = PmbGelombang::where('ta_awal',$curr_ta)->get();
        $curr_gelombang = PmbGelombang::where('ta_awal',$curr_ta)->limit(1)->first();
        
        $laki_laki = []; 
        $perempuan = []; 
        $nama_gel = [];
        $list_jurusan = [];
        $list_peserta_jurusan = [];
        $list_agama = [];
        $program_studi = Prodi::all();
        $agama = [
            1 => "islam",
            2 => "kristen", 
            3 => "katolik", 
            4 => "hindu", 
            5 => "budha", 
            6 => "konghucu", 
        ];
        $jumlah_prodi = [];
        $ta_awal = date('Y');
        $list_prodi = [];
        foreach($program_studi as $row){
            $jumlah_prodi[$row->id] = PmbPesertaOnline::join('pmb_gelombang','pmb_gelombang.id','=','pmb_peserta_online.gelombang')->where('pmb_gelombang.ta_awal',$ta_awal)->where('pilihan1',$row->id)->count();
            $list_prodi[$row->id] = $row->nama_prodi;
        }
        foreach($gelombang as $row){
            $laki_laki[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('jk',1)->count();
            $perempuan[$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('jk',2)->count();
            $nama_gel[$row->id] = $row->nama_gel;
            foreach($agama as $key => $value){
                $list_agama[$key][$row->id] = PmbPesertaOnline::where('agama',$key)->where('gelombang',$row->id)->count();
            }
            foreach($program_studi as $program){
                $list_jurusan[$program->id][$row->id] = PmbPesertaOnline::where('gelombang',$row->id)->where('pilihan1',$program->id)->count();
            }
        }
        
        foreach($program_studi as $program){
            $list_jurusan[$program->id] = implode("\",\"",$list_jurusan[$program->id]);
            $list_jurusan[$program->id] =  "\"" . $list_jurusan[$program->id] . "\"";
        }
        foreach($agama as $key => $value){
            $list_agama[$key] = implode("\",\"",$list_agama[$key]);
            $list_agama[$key] =  "\"" . $list_agama[$key] . "\"";
        }
        $title = "Statistik Admisi";
        $laki_laki = implode("\",\"",$laki_laki);
        $perempuan = implode("\",\"",$perempuan);
        $nama_gel = implode("\",\"",$nama_gel);
        $laki_laki = "\"" . $laki_laki . "\"";
        $perempuan = "\"" . $perempuan . "\"";
        $nama_gel = "\"" . $nama_gel . "\"";
        $ta_mulai = 2025;
        $gelombang2 = PmbGelombang::where('ta_awal','>=',$ta_mulai)->get();
        $jumlah_pertahun = [];
        $list_tahun = [];
        foreach($gelombang2 as $row){
            $jumlah_pertahun[$row->ta_awal] = 0;
            $list_tahun[$row->ta_awal] = $row->ta_awal . "/" . ((int)$row->ta_awal+1);
        }
        foreach($gelombang2 as $row){
            $jumlah_pertahun[$row->ta_awal] += PmbPesertaOnline::where('gelombang',$row->id)->count();
        }
        $list_tahun = implode("\",\"",$list_tahun);
        $jumlah_pertahun = implode("\",\"",$jumlah_pertahun);
        $list_tahun = "\"" . $list_tahun . "\"";
        $jumlah_pertahun = "\"" . $jumlah_pertahun . "\"";
        $jumlah_prodi = implode("\",\"",$jumlah_prodi);
        $jumlah_prodi = "\"" . $jumlah_prodi . "\"";
        $list_prodi = implode("\",\"",$list_prodi);
        $list_prodi = "\"" . $list_prodi . "\"";


        //buat statistik untuk dibandingkan per prodi
        $dataRaw = DB::table('pmb_peserta_online as p')
            ->join('program_studi as ps', 'p.pilihan1', '=', 'ps.id')
            ->join('pmb_gelombang as g', 'p.gelombang', '=', 'g.id')
            ->select(
                // Buat label Tahun Ajaran (contoh: 2024/2025)
                DB::raw("CONCAT(g.ta_awal, '/', g.ta_akhir) as tahun_ajaran"),
                'ps.nama_prodi',
                DB::raw('count(p.id) as total')
            )
            // Filter Soft Deletes (karena pakai DB facade, harus manual cek null)
            ->whereNull('p.deleted_at')
            ->whereNull('ps.deleted_at')
            ->whereNull('g.deleted_at')
            // Grouping
            ->groupBy('g.ta_awal', 'g.ta_akhir', 'ps.nama_prodi')
            // Sorting agar tahun berurutan
            ->orderBy('g.ta_awal', 'asc')
            ->get();

        // 2. Transformasi Data untuk ApexCharts
        
        // Ambil semua tahun unik untuk Sumbu X (Categories)
        $years = $dataRaw->pluck('tahun_ajaran')->unique()->values()->all();
        
        // Ambil semua nama prodi unik untuk Series
        $prodis = $dataRaw->pluck('nama_prodi')->unique()->values()->all();

        // Siapkan struktur data Series
        $series = [];

        foreach ($prodis as $prodi) {
            $dataPerProdi = [];
            
            foreach ($years as $year) {
                // Cari apakah Prodi ini punya data di Tahun ini?
                $found = $dataRaw->first(function ($item) use ($prodi, $year) {
                    return $item->nama_prodi == $prodi && $item->tahun_ajaran == $year;
                });

                // Jika ketemu ambil totalnya, jika tidak isi 0 (PENTING untuk grafik bar)
                $dataPerProdi[] = $found ? $found->total : 0;
            }

            $series[] = [
                'name' => $prodi,
                'data' => $dataPerProdi
            ];
        }
        $list_gelombang = DB::table('pmb_gelombang')
            ->select('id', 'nama_gel', 'ta_awal', 'ta_akhir')
            ->orderBy('id', 'desc')
            ->get();

        $list_ta = DB::table('pmb_gelombang')
            ->select('ta_awal', 'ta_akhir')
            ->distinct()
            ->orderBy('ta_awal', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->ta_awal, // Kita pakai ta_awal sebagai value filter
                    'text' => $item->ta_awal . '/' . $item->ta_akhir
                ];
            });
        return view('admin.admisi.statistik.index', [
            'categories' => $years,
            'series' => $series
        ],compact('agama','list_agama','laki_laki','perempuan','nama_gel','title','curr_gelombang','list_tahun','jumlah_pertahun','list_jurusan','program_studi','jumlah_prodi','list_prodi', 'ta_awal','list_gelombang','list_ta'));
    }
    public function getGenderData(Request $request)
    {
        $gelombangId = $request->input('gelombang_id');

        // Query menghitung jumlah Laki-laki (1) dan Perempuan (2)
        // Berdasarkan gelombang yang dipilih
        $data = DB::table('pmb_peserta_online')
            ->select('jk', DB::raw('count(*) as total'))
            ->where('gelombang', $gelombangId)
            ->whereNull('deleted_at') // Pastikan data tidak terhapus
            ->groupBy('jk')
            ->pluck('total', 'jk'); 
            // Pluck menghasilkan array [ '1' => 50, '2' => 30 ]

        // Mapping data agar formatnya sesuai (jika data kosong, default 0)
        // Index 1 = Laki-laki, Index 2 = Perempuan
        $laki = isset($data[1]) ? $data[1] : 0;
        $perempuan = isset($data[2]) ? $data[2] : 0;

        return response()->json([
            'series' => [$laki, $perempuan],
            'labels' => ['Laki-laki', 'Perempuan']
        ]);
    }
    public function getValidasiData(Request $request)
    {
        $gelombangId = $request->input('gelombang_id');

        // Query menghitung jumlah Laki-laki (1) dan Perempuan (2)
        // Berdasarkan gelombang yang dipilih
        $data = DB::table('pmb_peserta_online')
            ->select('is_verifikasi', DB::raw('count(*) as total'))
            ->where('gelombang', $gelombangId)
            ->whereNull('deleted_at')
            ->groupBy('is_verifikasi')
            ->pluck('total', 'is_verifikasi');
            // Pluck menghasilkan array [ '1' => 50, '2' => 30 ]

        // Mapping data agar formatnya sesuai (jika data kosong, default 0)
        // Index 1 = Sudah Validasi, Index 2 = Belum Validasi
        $belum_validasi = isset($data[0]) ? $data[0] : 0;
        $sudah_validasi = isset($data[1]) ? $data[1] : 0;

        return response()->json([
            'series' => [$sudah_validasi, $belum_validasi],
            'labels' => ['Sudah Validasi', 'Belum Validasi']
        ]);
    }
    public function getLolosData(Request $request)
    {
        $gelombangId = $request->input('gelombang_id');

        // Query menghitung jumlah Laki-laki (1) dan Perempuan (2)
        // Berdasarkan gelombang yang dipilih
        $data = DB::table('pmb_peserta_online')
            ->select('is_lolos', DB::raw('count(*) as total'))
            ->where('gelombang', $gelombangId)
            ->whereNull('deleted_at')
            ->groupBy('is_lolos')
            ->pluck('total', 'is_lolos');
            // Pluck menghasilkan array [ '1' => 50, '2' => 30 ]

        // Mapping data agar formatnya sesuai (jika data kosong, default 0)
        // Index 1 = Sudah Lolos, Index 2 = Belum Lolos
        $belum_lolos = isset($data[0]) ? $data[0] : 0;
        $sudah_lolos = isset($data[1]) ? $data[1] : 0;

        return response()->json([
            'series' => [$sudah_lolos, $belum_lolos],
            'labels' => ['Sudah Lolos', 'Belum Lolos']
        ]);
    }
    public function getTop10Data(Request $request)
    {
        $taAwal = $request->input('ta_awal');

        // 1. Top 10 Asal Sekolah (Berdasarkan Kota Sekolah)
        $topSekolah = DB::table('pmb_asal_sekolah as s')
            ->join('pmb_peserta_online as p', 's.id_peserta', '=', 'p.id')
            ->join('pmb_gelombang as g', 'p.gelombang', '=', 'g.id')
            ->join('wilayah as w', 's.kota_id', '=', 'w.id_wil') // Join ke Master Wilayah
            ->select('w.nm_wil as nama_kota', DB::raw('count(s.id) as total'))
            ->whereNull('s.deleted_at')
            ->whereNull('p.deleted_at')
            ->when($taAwal, function ($q) use ($taAwal) {
                return $q->where('g.ta_awal', $taAwal);
            })
            ->groupBy('w.nm_wil')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // 2. Top 10 Domisili Pendaftar (Berdasarkan Alamat KTP/Tinggal)
        // Asumsi field 'kotakab' di pmb_peserta_online berisi KODE WILAYAH (match dengan wilayah.id_wil)
        $topDomisili = DB::table('pmb_peserta_online as p')
            ->join('pmb_gelombang as g', 'p.gelombang', '=', 'g.id')
            ->join('wilayah as w', 'p.kotakab', '=', 'w.id_wil') // Join ke Master Wilayah
            ->select('w.nm_wil as nama_kota', DB::raw('count(p.id) as total'))
            ->whereNull('p.deleted_at')
            ->when($taAwal, function ($q) use ($taAwal) {
                return $q->where('g.ta_awal', $taAwal);
            })
            ->groupBy('w.nm_wil')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return response()->json([
            'sekolah' => $topSekolah,
            'domisili' => $topDomisili
        ]);
    }
    public function getMapData(Request $request)
    {
        $taAwal = $request->input('ta_awal');
        
        $query = DB::table('pmb_asal_sekolah as s')
            ->join('pmb_peserta_online as p', 's.id_peserta', '=', 'p.id')
            ->join('pmb_gelombang as g', 'p.gelombang', '=', 'g.id')
            ->select('s.provinsi_id', DB::raw('count(s.id) as total'))
            ->whereNull('s.deleted_at')
            ->whereNull('p.deleted_at');

        if ($taAwal) {
            $query->where('g.ta_awal', $taAwal);
        }

        $results = $query->groupBy('s.provinsi_id')->get();
        
        return response()->json($this->mapToHighcharts($results, 'provinsi_id'));
    }

    // --- API 2: Peta Domisili Pendaftar (BARU) ---
    public function getDomisiliMapData(Request $request)
    {
        $taAwal = $request->input('ta_awal');

        // Query ke tabel pmb_peserta_online langsung
        $query = DB::table('pmb_peserta_online as p')
            ->join('pmb_gelombang as g', 'p.gelombang', '=', 'g.id')
            ->select('p.provinsi', DB::raw('count(p.id) as total')) // Asumsi kolom 'provinsi' menyimpan Kode Wilayah
            ->whereNull('p.deleted_at');

        if ($taAwal) {
            $query->where('g.ta_awal', $taAwal);
        }

        $results = $query->groupBy('p.provinsi')->get();

        return response()->json($this->mapToHighcharts($results, 'provinsi'));
    }

    // --- Helper: Mapping Logic (Agar tidak duplikat) ---
    private function mapToHighcharts($data, $columnName)
    {
        // Mapping Kode Wilayah (Table Wilayah) -> Key Highcharts
        $mapping = [
            '060000' => 'id-ac', '070000' => 'id-su', '080000' => 'id-sb', '090000' => 'id-ri',
            '100000' => 'id-ja', '110000' => 'id-sl', '260000' => 'id-be', '120000' => 'id-1024',
            '290000' => 'id-bb', '310000' => 'id-kr', '010000' => 'id-jk', '020000' => 'id-jb',
            '030000' => 'id-jt', '040000' => 'id-yo', '050000' => 'id-ji', '280000' => 'id-bt',
            '220000' => 'id-ba', '230000' => 'id-nb', '240000' => 'id-nt', '130000' => 'id-kb',
            '140000' => 'id-kt', '150000' => 'id-ks', '160000' => 'id-ki', '340000' => 'id-ku',
            '170000' => 'id-sw', '180000' => 'id-st', '190000' => 'id-se', '200000' => 'id-sg',
            '300000' => 'id-go', '330000' => 'id-sr', '210000' => 'id-ma', '270000' => 'id-mu',
            '320000' => 'id-pb', '250000' => 'id-pa'
        ];

        $chartData = [];
        foreach ($data as $row) {
            $dbCode = trim($row->$columnName);
            if (isset($mapping[$dbCode])) {
                $chartData[] = [
                    'hc-key' => $mapping[$dbCode],
                    'value'  => $row->total
                ];
            }
        }
        return $chartData;
    }
    public function pegawai(){
       $dataRaw = DB::table('pegawai_biodata')
            ->select('jenis_kelamin', DB::raw('count(*) as total'))
            ->whereNull('deleted_at')
            ->where('status_pegawai', 'aktif') 
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin'); // Hasil: ['L' => 10, 'P' => 5]

        // 2. Format Data untuk ApexCharts
        // Pastikan urutannya konsisten. Jika data kosong, beri nilai 0.
        $totalLaki = $dataRaw['L'] ?? 0;
        $totalPerempuan = $dataRaw['P'] ?? 0;

        $series_gender = [$totalLaki, $totalPerempuan];
        $labels_gender = ['Laki-laki', 'Perempuan'];

        $program_studi = Prodi::all();
        $jumlah_prodi = [];
        $list_prodi = [];
        foreach($program_studi as $row){
            $jumlah_prodi[$row->id] = PegawaiBiodatum::where('id_progdi',$row->id)->where('status_pegawai', 'aktif')->count();
            $list_prodi[$row->id] = $row->nama_prodi;
        }
        $list_prodi = implode("\",\"",$list_prodi);
        $list_prodi = "\"" . $list_prodi . "\"";
        $jumlah_prodi = implode("\",\"",$jumlah_prodi);
        $jumlah_prodi = "\"" . $jumlah_prodi . "\"";

        $title = "Statistik Pegawai";

        $prodiList = DB::table('program_studi')
            ->select('id', 'nama_prodi')
            ->whereNull('deleted_at')
            ->orderBy('nama_prodi', 'asc')
            ->get();

        // 2. Ambil Data Master Jabfung (Untuk Series)
        $jabfungList = DB::table('jabatan_fungsional')
            ->orderBy('id', 'asc')
            ->get();

        // 3. Ambil Data Transaksi (Jumlah Pegawai)
        // Kita Group berdasarkan Prodi dan Jabfung
        $dataRaw = DB::table('pegawai_biodata')
            ->select('id_progdi', 'id_jabfung', DB::raw('count(*) as total'))
            ->where('status_pegawai', 'aktif') // Hanya pegawai aktif
            ->whereNull('deleted_at')
            ->groupBy('id_progdi', 'id_jabfung')
            ->get();

        // 4. Transformasi Data untuk ApexCharts
        // Format: Series A (Jabfung A) -> [Jumlah di Prodi 1, Jumlah di Prodi 2, ...]
        
        $categories = $prodiList->pluck('nama_prodi')->toArray(); // X-Axis Labels
        $series = [];

        // Loop 1: Untuk setiap Jabatan Fungsional yang ada di database
        foreach ($jabfungList as $jf) {
            $dataPerProdi = [];
            
            foreach ($prodiList as $prodi) {
                // Cari data yang cocok (Prodi X dan Jabfung Y)
                $found = $dataRaw->first(function($item) use ($prodi, $jf) {
                    return $item->id_progdi == $prodi->id && $item->id_jabfung == $jf->id;
                });
                
                $dataPerProdi[] = $found ? (int)$found->total : 0;
            }

            $series[] = [
                'name' => $jf->jabatan,
                'data' => $dataPerProdi
            ];
        }

        // Loop 2: KHUSUS untuk yang Tidak Punya Jabfung (NULL)
        // Agar data pegawai lengkap, kita masukkan juga yang NULL sebagai "Tanpa Jabatan"
        $dataNull = [];
        foreach ($prodiList as $prodi) {
            $found = $dataRaw->first(function($item) use ($prodi) {
                return $item->id_progdi == $prodi->id && $item->id_jabfung == 0;
            });
            $dataNull[] = $found ? (int)$found->total : 0;
        }

        // Masukkan ke series sebagai "Belum Ada / TP"
        $series[] = [
            'name' => 'Belum Ada / Tenaga Pengajar',
            'data' => $dataNull
        ];

        $posisiRaw = DB::table('pegawai_biodata as pb')
            ->join('pegawai_posisi as pp', 'pb.id_posisi_pegawai', '=', 'pp.id')
            ->join('program_studi as ps', 'pb.id_progdi', '=', 'ps.id') // Pastikan table program_studi ada
            ->select('ps.nama_prodi', 'pp.nama as nama_posisi', DB::raw('count(pb.id) as total'))
            ->where('pb.status_pegawai', 'aktif')
            ->whereNull('pb.deleted_at')
            ->groupBy('ps.nama_prodi', 'pp.nama')
            ->get();

        // Transformasi Data untuk ApexCharts Bar
        // Ambil semua nama prodi unik untuk Sumbu X
        $prodiCategories = $posisiRaw->pluck('nama_prodi')->unique()->values()->all();
        
        // Ambil semua jenis posisi unik (Dosen Tetap, Staff, dll) untuk Series
        $posisiTypes = $posisiRaw->pluck('nama_posisi')->unique()->values()->all();

        $barSeries = [];
        
        foreach ($posisiTypes as $posisi) {
            $dataPerProdi = [];
            
            foreach ($prodiCategories as $prodiName) {
                // Cari total pegawai dengan posisi X di prodi Y
                $found = $posisiRaw->first(function($item) use ($posisi, $prodiName) {
                    return $item->nama_posisi == $posisi && $item->nama_prodi == $prodiName;
                });

                $dataPerProdi[] = $found ? (int)$found->total : 0;
            }

            $barSeries[] = [
                'name' => $posisi,
                'data' => $dataPerProdi
            ];
        }
        
        
        return view('admin.admisi.statistik.pegawai', compact('title','series_gender','labels_gender', 'list_prodi','jumlah_prodi','series','categories','barSeries', 'prodiCategories'));
    }
}
