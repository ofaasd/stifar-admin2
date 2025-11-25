<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use DB;
use App\Models\Mahasiswa;
use App\Models\master_nilai;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\RefPembimbing;
use App\Http\Controllers\Controller;
use App\Models\MasterBidangMinat;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanJudulSkripsi;
use App\helpers;
use App\Models\PegawaiBiodatum;

class PengajuanSkripsiController extends Controller
{
    protected $kualitas = [
        'A' => 4,
        'AB' => 3.5,
        'B' => 3,
        'BC' => 2.5,
        'C' => 2,
        'CD' => 1.5,
        'D' => 1,
        'ED' => 0.5,
        'E' => 0
    ];

    /**
    * menampilkan halaman dan data pengajuan skripsi.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function index()
    {
        $idUser = Auth::User()->id;
        $mhs = Mahasiswa::select([
            'mahasiswa.*',
            'program_studi.jml_sks AS minSks',
            'program_studi.max_c AS maxC',
            'program_studi.max_d AS maxD',
            'program_studi.max_e AS maxE',
        ])
        ->where('user_id', $idUser)
        ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
        ->first();

        if(!$mhs)
        {
            return redirect()->back()->with('error','Data mahasiswa tidak ditemukan.');
        }

        $getNilai = master_nilai::select(
            'master_nilai.*',
            'a.hari',
            'a.kel',
            'b.nama_matkul',
            'b.sks_teori',
            'b.sks_praktek',
            'b.kode_matkul'
        )
        ->leftJoin('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
        ->join('mata_kuliahs as b', function($join) {
            $join->on('a.id_mk', '=', 'b.id')
                    ->orOn('master_nilai.id_matkul', '=', 'b.id');
        })
        ->where('nim', $mhs->nim)
        ->whereNotNull('master_nilai.nakhir')
        ->get();

        $totalSks = 0;
        $totalIps = 0;
        foreach ($getNilai as $row) {
            $sks = ($row->sks_teori + $row->sks_praktek);
            $totalSks += $sks;
            if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1)
            {
                $totalIps +=  ($row->sks_teori+$row->sks_praktek) * $this->kualitas[$row['nhuruf']];
            }
        }

        $cekMetopen = master_nilai::join('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
                ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                ->where('b.nama_matkul', 'like', '%metodologi penelitian%')
                ->where('nim', $mhs->nim)->count();

        $mhs->isMetopen = $cekMetopen > 0 ? true : false;

        $mhs->totalSks = $totalSks;
        $mhs->totalIps = $totalIps;
        $mhs->ipk = $totalSks > 0 ? number_format($totalIps / $totalSks, 2) : 0;

        $dosen = RefPembimbing::leftJoin('pegawai_biodata as pegawai', 'pegawai.npp', '=', 'ref_pembimbing_skripsi.nip')
        ->select('pegawai.nama_lengkap AS nama', 'pegawai.npp', 'ref_pembimbing_skripsi.kuota', 'ref_pembimbing_skripsi.id_progdi', 'ref_pembimbing_skripsi.id_bidang_minat')
        ->get();

        $listBidangMinat = MasterBidangMinat::all();

        $data = [
            'mhs' => $mhs,
            'pembimbing' => $dosen,
            'listBidangMinat' => $listBidangMinat,
        ];

       return view('mahasiswa.skripsi.pengajuan.skripsi.index', $data);
    }

    /**
    * menyimpan data pengajuan skripsi.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bidang1'             => 'required',
            'judul1'             => 'required|string|max:200',
            'judulEng1'          => 'required|string|max:200',
            'bidang2'             => 'required',
            'judul2'            => 'required|string|max:200',
            'judulEng2'         => 'required|string|max:200',
            'pembimbing1'         => 'required',
            'pembimbing1_2'         => 'required',
            'pembimbing2'         => 'required',
            'pembimbing2_2'         => 'required',
            'abstrak'           => 'required|string|max:1000',
        ]);
    
        try {
            \DB::beginTransaction();
    
            $idUser = Auth::User()->id;
            $mhs = Mahasiswa::select([
                'mahasiswa.*',
                'program_studi.jml_sks AS minSks',
                'program_studi.max_c AS maxC',
                'program_studi.max_d AS maxD',
                'program_studi.max_e AS maxE',
            ])
            ->where('user_id', $idUser)
            ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
            ->first();

            $nim   = $mhs->nim;
    
            // buat master skripsi
            $cekMaster = MasterSkripsi::where('nim', $nim)
                ->whereIn('status', [1, 2])
                ->first();

            if($cekMaster) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah memiliki pengajuan skripsi yang sedang diproses atau telah disetujui.'
                ], 400);
            }

            $idUser = Auth::User()->id;

            if(!$mhs)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data mahasiswa tidak ditemukan.'
                ], 404);
                return redirect()->back()->with('error','Data mahasiswa tidak ditemukan.');
            }

            $getNilai = master_nilai::select(
                'master_nilai.*',
                'a.hari',
                'a.kel',
                'b.nama_matkul',
                'b.sks_teori',
                'b.sks_praktek',
                'b.kode_matkul'
            )
            ->leftJoin('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
            ->join('mata_kuliahs as b', function($join) {
                $join->on('a.id_mk', '=', 'b.id')
                        ->orOn('master_nilai.id_matkul', '=', 'b.id');
            })
            ->where('nim', $mhs->nim)
            ->whereNotNull('master_nilai.nakhir')
            ->get();

            $totalSks = 0;
            $totalIps = 0;
            foreach ($getNilai as $row) {
                $sks = ($row->sks_teori + $row->sks_praktek);
                $totalSks += $sks;
                if($row->validasi_tugas == 1 && $row->validasi_uts == 1 && $row->validasi_uas == 1)
                {
                    $totalIps +=  ($row->sks_teori+$row->sks_praktek) * $this->kualitas[$row['nhuruf']];
                }
            }
            $cekMetopen = master_nilai::join('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
                    ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
                    ->where('b.nama_matkul', 'like', '%metodologi penelitian%')
                    ->where('nim', $mhs->nim)
                    ->count();

            $isMetopen = $cekMetopen > 0 ? true : false;

            $mhs->totalIps = $totalIps;
            $mhs->ipk = $totalSks > 0 ? number_format($totalIps / $totalSks, 2) : 0;

            // if ($mhs->ipk < 2.00) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Syarat IPK tidak memenuhi. Minimal IPK adalah 2.00.'
            //     ], 400);
            //     return redirect()->back()->with('error','Syarat IPK tidak memenuhi. Minimal IPK adalah 2.00.');
            // }
            // if ($totalSks < 112) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Syarat SKS tidak memenuhi. Minimal SKS adalah 112 SKS.'
            //     ], 400);
            //     return redirect()->back()->with('error','Syarat SKS tidak memenuhi. Minimal SKS adalah 112 SKS.');
            // }

            // if (!$isMetopen) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Anda belum mengambil mata kuliah Metodologi Penelitian.'
            //     ], 400);
            //     return redirect()->back()->with('error','Anda belum mengambil mata kuliah Metodologi Penelitian.');
            // }

            $bidangMinat = MasterBidangMinat::all();
            $teksBidangMinat1 = $bidangMinat->where('id', $request->bidang1)->first()->nama ?? '';
            $teksBidangMinat2 = $bidangMinat->where('id', $request->bidang2)->first()->nama ?? '';

            $pembimbing = PegawaiBiodatum::all();
            $teksPembimbing1_1 = $pembimbing->where('npp', $request->pembimbing1)->first()->nama_lengkap ?? '';
            $teksPembimbing1_2 = $pembimbing->where('npp', $request->pembimbing1_2)->first()->nama_lengkap ?? '';
            $teksPembimbing2_1 = $pembimbing->where('npp', $request->pembimbing2)->first()->nama_lengkap ?? '';
            $teksPembimbing2_2 = $pembimbing->where('npp', $request->pembimbing2_2)->first()->nama_lengkap ?? '';
            
            $pengajuanData = [
                [
                    'pembimbing1' => $request->pembimbing1,
                    'pembimbing2' => $request->pembimbing1_2,
                    'judul' => $request->judul1,
                    'judulEng' => $request->judulEng1,
                    'bidangMinat' => $request->bidang1,
                ],
                [
                    'pembimbing1' => $request->pembimbing2,
                    'pembimbing2' => $request->pembimbing2_2,
                    'judul' => $request->judul2,
                    'judulEng' => $request->judulEng2,
                    'bidangMinat' => $request->bidang2,
                ]
            ];

            $save = 0;
            foreach ($pengajuanData as $data) {
                $master = MasterSkripsi::create([
                    'nim' => $nim,
                    'pembimbing_1' => $data['pembimbing1'],
                    'pembimbing_2' => $data['pembimbing2'],
                    'pembimbing_1_pengajuan' => $data['pembimbing1'],
                    'pembimbing2_pengajuan' => $data['pembimbing2'],
                    'status' => 0
                ]);

                PengajuanJudulSkripsi::create([
                    'id_master' => $master->id,
                    'judul' => $data['judul'],
                    'judul_eng' => $data['judulEng'],
                    'abstrak' => $request->abstrak,
                    'id_bidang_minat' => $data['bidangMinat'],
                    'status' => 0,
                ]);

                $save++;
            }

            if($save == 2)
            {
                $dataWa['no_wa'] = $mhs->hp ?? '';
                $abstrak = $validated['abstrak'] ?? '';
                $shortAbstrak = mb_strlen($abstrak) > 350 ? mb_substr($abstrak, 0, 350) . '...' : $abstrak;

                $message = "*MYSTIFAR - Pengajuan Judul*\n\n"
                    . "Halo, " . $mhs->nama . ",\n\n"
                    . "Pengajuan skripsi Anda telah berhasil disimpan dengan rincian berikut:\n\n"
                    . "Judul Pilihan 1:\n"
                    . "- Bidang Minat: " . ($teksBidangMinat1 ?? '-') . "\n"
                    . "- Judul: " . ($request->judul1 ?? '-') . "\n"
                    . "- Judul (EN): " . ($request->judulEng1 ?? '-') . "\n"
                    . "- Pembimbing 1: " . ($teksPembimbing1_1 ?? '-') . "\n"
                    . "- Pembimbing 2: " . ($teksPembimbing1_2 ?? '-') . "\n\n"
                    . "Judul Pilihan 2:\n"
                    . "- Bidang Minat: " . ($teksBidangMinat2 ?? '-') . "\n"
                    . "- Judul: " . ($request->judul2 ?? '-') . "\n"
                    . "- Judul (EN): " . ($request->judulEng2 ?? '-') . "\n"
                    . "- Pembimbing 1: " . ($teksPembimbing2_1 ?? '-') . "\n"
                    . "- Pembimbing 2: " . ($teksPembimbing2_2 ?? '-') . "\n\n"
                    . "Abstrak (ringkasan):\n" . $shortAbstrak . "\n\n"
                    . "Status: Pengajuan\n\n"
                    . "Jika ada koreksi atau pertanyaan, silakan hubungi admin skripsi.\n\n"
                    . "Terima kasih,\nAdmin Skripsi STIFAR";
                $dataWa['pesan'] = $message;
                
                $pesan = helpers::send_wa($dataWa);
            }
    
            DB::commit();
    
            return redirect()
                ->route('mhs.skripsi.daftar.index')
                ->with('success', 'Pengajuan judul berhasil disimpan.');

            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Pengajuan judul berhasil disimpan.'
            // ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            \Log::error('Gagal menyimpan pengajuan judul', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'user'    => Auth::id()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
    
            // return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
    * pengecekan kelulusan bidang minat.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function cekBidangMinat(Request $request)
    {
        try {
            $idBidangMinat = $request->input('idBidangMinat');
            $arrKodeMatkul = MataKuliah::where('id_bidang_minat', $idBidangMinat)->pluck('kode_matkul')->toArray();

            $idUser = Auth::User()->id;
            $mhs = Mahasiswa::where('user_id', $idUser)
            ->first();

            if(!$mhs)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data mahasiswa tidak ditemukan.'
                ], 404);
            }

            $getNilai = master_nilai::select(
                'master_nilai.*',
                'a.hari',
                'a.kel',
                'b.nama_matkul',
                'b.sks_teori',
                'b.sks_praktek',
                'b.kode_matkul'
            )
            ->leftJoin('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
            ->join('mata_kuliahs as b', function($join) {
                $join->on('a.id_mk', '=', 'b.id')
                        ->orOn('master_nilai.id_matkul', '=', 'b.id');
            })
            ->where('nim', $mhs->nim)
            ->whereIn('b.kode_matkul', $arrKodeMatkul)
            ->whereNotNull('master_nilai.nakhir')
            ->get();

            if($getNilai->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'isValid' => false,
                    'message' => 'Anda belum mengambil mata kuliah pada bidang minat ini.'
                ], 200);
            }else{
                foreach ($getNilai as $row) {
                    if (!isset($this->kualitas[$row['nhuruf']]) || $this->kualitas[$row['nhuruf']] < 2) {
                        return response()->json([
                            'status' => 'error',
                            'isValid' => false,
                            'message' => 'Terdapat mata kuliah dengan nilai di bawah C. Minimal nilai adalah C untuk bidang minat ini.',
                            'matkul' => $row->nama_matkul,
                            'nilai' => $row['nakhir']
                        ], 200);
                    }
                }
                
                return response()->json([
                    'status' => 'success',
                    'isValid' => true,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Gagal mengecek bidang minat', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'user'    => Auth::id()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengecek bidang minat.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
