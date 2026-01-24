<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\master_nilai;
use App\Models\Prodi;
use App\Models\TahunAjaran;
use DB;

class LedgerController extends Controller
{
    //
    public function index(Request $request)
    {
        $title = "Ledger";
        $var['prodi'] = Prodi::all();
        $var['tahun_ajaran'] = TahunAjaran::orderBy('id','desc')->get();
        $var['tahun_angkatan'] = Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan','desc')->get(); 
        $var['data'] = [];
        if(!empty($request->prodi_id) && !empty($request->tahun_angkatan) && !empty($request->tahun_ajaran)){
            $id_tahun = $request->tahun_ajaran;
            $id_prodi = $request->prodi_id;
            $angkatan = $request->tahun_angkatan;
            $sqlBobot = "CASE 
                WHEN mn.nhuruf = 'A' THEN 4
                WHEN mn.nhuruf = 'AB' THEN 3.5
                WHEN mn.nhuruf = 'B' THEN 3
                WHEN mn.nhuruf = 'BC' THEN 2.5
                WHEN mn.nhuruf = 'C' THEN 2
                WHEN mn.nhuruf = 'D' THEN 1
                WHEN mn.nhuruf = 'E' THEN 0
                ELSE 0 
            END";
            

            $var['header_matkul'] = DB::table('krs')
                            ->join('jadwals', 'krs.id_jadwal', '=', 'jadwals.id')          // Relasi 1: KRS ke Jadwal
                            ->join('mata_kuliahs', 'jadwals.id_mk', '=', 'mata_kuliahs.id') // Relasi 2: Jadwal ke MK
                            ->join('mahasiswa', 'krs.id_mhs', '=', 'mahasiswa.id')          // Relasi 3: Filter Populasi
                            ->select(
                                'mata_kuliahs.id', 
                                'mata_kuliahs.kode_matkul', 
                                'mata_kuliahs.nama_matkul'
                            )
                            ->where('krs.id_tahun', $id_tahun)          // Pastikan ambil KRS tahun ajaran yang diminta
                            ->where('mahasiswa.angkatan', $angkatan)    // Filter Mahasiswa Angkatan X
                            ->where('mahasiswa.id_program_studi', $id_prodi) // Filter Mahasiswa Prodi Y
                            ->where('krs.is_publish', 1)                // (Opsional) Hanya KRS yang sudah disetujui/publish
                            ->distinct()                                // Wajib: Agar MK tidak double jika banyak mhs ambil
                            ->orderBy('mata_kuliahs.kode_matkul', 'asc')
                            ->get();
            $sqlTotalSKS = "(COALESCE(mk.sks_teori, 0) + COALESCE(mk.sks_praktek, 0))";
            $var['data_mahasiswa'] = DB::table('mahasiswa as m')
                                        ->select([
                                            'm.id',
                                            'm.nim',
                                            'm.nama',
                                        ])
                                        
                                        // --- 1. Hitung IPS (Indeks Prestasi Semester Ini) ---
                                        ->addSelect([
                                            'ips' => DB::table('master_nilai as mn')
                                                ->join('jadwals as j', 'mn.id_jadwal', '=', 'j.id')
                                                ->join('mata_kuliahs as mk', 'j.id_mk', '=', 'mk.id')
                                                // Rumus: SUM(SKS * Bobot) / SUM(SKS)
                                                // Dibungkus COALESCE terluar agar jika mhs belum ada nilai sama sekali, hasilnya 0.00
                                                ->selectRaw("COALESCE(ROUND(
                                                    SUM($sqlTotalSKS * ($sqlBobot)) 
                                                    / 
                                                    NULLIF(SUM($sqlTotalSKS), 0)
                                                , 2), 0.00)")
                                                ->whereColumn('mn.id_mhs', 'm.id')
                                                ->where('mn.id_tahun', $id_tahun)
                                                // Hanya hitung jika nilai huruf sudah diinput (agar yang belum dinilai tidak dianggap E/0)
                                                ->whereNotNull('mn.nhuruf')
                                                ->where('mn.nhuruf', '!=', '')
                                        ])
                                        
                                        // --- 2. Hitung SKS Semester Ini ---
                                        ->addSelect([
                                            'sks_sem' => DB::table('master_nilai as mn')
                                                ->join('jadwals as j', 'mn.id_jadwal', '=', 'j.id')
                                                ->join('mata_kuliahs as mk', 'j.id_mk', '=', 'mk.id')
                                                ->selectRaw("COALESCE(SUM($sqlTotalSKS), 0)")
                                                ->whereColumn('mn.id_mhs', 'm.id')
                                                ->where('mn.id_tahun', $id_tahun)
                                        ])
                                        
                                        // --- 3. Hitung IPK (Kumulatif Seluruh Semester) ---
                                        ->addSelect([
                                            'ipk' => DB::table('master_nilai as mn')
                                                ->join('jadwals as j', 'mn.id_jadwal', '=', 'j.id')
                                                ->join('mata_kuliahs as mk', 'j.id_mk', '=', 'mk.id')
                                                ->selectRaw("COALESCE(ROUND(
                                                    SUM($sqlTotalSKS * ($sqlBobot)) 
                                                    / 
                                                    NULLIF(SUM($sqlTotalSKS), 0)
                                                , 2), 0.00)")
                                                ->whereColumn('mn.id_mhs', 'm.id')
                                                // Syarat IPK: Nilai harus ada (NotNull) dan tidak kosong
                                                ->whereNotNull('mn.nhuruf')
                                                ->where('mn.nhuruf', '!=', '')
                                        ])
                                        
                                        // --- 4. Hitung Total SKS Lulus (Kumulatif) ---
                                        ->addSelect([
                                            'total_sks' => DB::table('master_nilai as mn')
                                                ->join('jadwals as j', 'mn.id_jadwal', '=', 'j.id')
                                                ->join('mata_kuliahs as mk', 'j.id_mk', '=', 'mk.id')
                                                ->selectRaw("COALESCE(SUM($sqlTotalSKS), 0)")
                                                ->whereColumn('mn.id_mhs', 'm.id')
                                                ->whereNotNull('mn.nhuruf')
                                                // SKS Lulus biasanya tidak menghitung nilai E
                                                ->where('mn.nhuruf', '!=', 'E')
                                        ])
                                        
                                        // --- 5. Ambil List Nilai JSON (Untuk Kolom-Kolom Mata Kuliah) ---
                                        ->addSelect([
                                            'raw_nilai_json' => DB::table('master_nilai as mn')
                                                ->join('jadwals as j', 'mn.id_jadwal', '=', 'j.id')
                                                ->join('mata_kuliahs as mk', 'j.id_mk', '=', 'mk.id')
                                                ->whereColumn('mn.id_mhs', 'm.id')
                                                ->where('mn.id_tahun', $id_tahun)
                                                // Penting: Ambil mk.id sebagai referensi key
                                                ->selectRaw("JSON_ARRAYAGG(JSON_OBJECT('id_mk', mk.id, 'huruf', mn.nhuruf))")
                                        ])
                                        ->where('m.angkatan', $angkatan)
                                        ->where('m.id_program_studi', $id_prodi)
                                        ->where('m.status', 1)
                                        ->orderBy('m.nim', 'asc')
                                        ->get();

            // ---------------------------------------------------------
            // DATA TRANSFORMATION (PHP Side)
            // ---------------------------------------------------------
            // Ubah format JSON menjadi Key-Value Array agar mudah dipanggil di Blade
            // Contoh hasil: $mhs->nilai_map = [ 101 => 'A', 102 => 'BC' ]
            $var['data_mahasiswa']->transform(function($mhs) {
                $decoded = json_decode($mhs->raw_nilai_json, true);
                
                // Jika null (mahasiswa belum punya nilai sama sekali), set array kosong
                if (!$decoded) {
                    $mhs->nilai_map = [];
                } else {
                    // Mapping: Key = ID Matkul, Value = Nilai Huruf
                    $mhs->nilai_map = array_column($decoded, 'huruf', 'id_mk');
                }
                return $mhs;
            });
        } 
        // dd($var['data']);
        return view('admin.ledger.index', compact('title'), $var);
    }   
}
