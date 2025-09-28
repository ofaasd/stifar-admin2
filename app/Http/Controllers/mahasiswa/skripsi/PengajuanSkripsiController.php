<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\MasterSkripsi;
use App\Models\PengajuanJudulSkripsi;
use DB;
use App\Models\master_nilai;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

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

    public function index()
    {
        $idUser = Auth::User()->id;
        $mhs = Mahasiswa::where('user_id', $idUser)->first();

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
            ->join('jadwals as a', 'master_nilai.id_jadwal', '=', 'a.id')
            ->join('mata_kuliahs as b', 'a.id_mk', '=', 'b.id')
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

        $mhs->totalSks = $totalSks;
        $mhs->totalIps = $totalIps;
        $mhs->ipk = $totalSks > 0 ? number_format($totalIps / $totalSks, 2) : 0;

        $data = [
            'mhs' => $mhs,
        ];

       return view('mahasiswa.skripsi.pengajuan.skripsi.index', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul1'             => 'required|string|max:200',
            'judulEng1'          => 'required|string|max:200',
            'judul2'            => 'required|string|max:200',
            'judulEng2'         => 'required|string|max:200',
            'abstrak1'           => 'required|string|max:1000',
            'abstrak2'           => 'required|string|max:1000',
        ]);
    
        try {
            \DB::beginTransaction();
    
            $idUser = Auth::User()->id;
            $mhs = Mahasiswa::where('user_id', $idUser)->first();
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
            
            $master = MasterSkripsi::create([
                'nim' => $nim,
                'status' => 0
            ]);
    
            // daftar judul yang akan dimasukkan
            $judulList = [
                [
                    'judul'     => $validated['judul1'],
                    'judul_eng' => $validated['judulEng1'],
                    'abstrak' => $validated['abstrak1'],
                ],
                [
                    'judul'     => $validated['judul2'],
                    'judul_eng' => $validated['judulEng2'],
                    'abstrak' => $validated['abstrak2'],
                ]
            ];
    
            foreach ($judulList as $judul) {
                PengajuanJudulSkripsi::create([
                    'id_master'        => $master->id,
                    'judul'            => $judul['judul'],
                    'judul_eng'        => $judul['judul_eng'],
                    'abstrak'          => $judul['abstrak'],
                    'status'           => 0,
                ]);
            }
    
            DB::commit();
    
            // return redirect()
            //     ->route('mhs.pengajuan.index')
            //     ->with('success', 'Pengajuan judul skripsi berhasil disimpan.');

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan judul skripsi berhasil disimpan.'
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            \Log::error('Gagal menyimpan pengajuan judul skripsi', [
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

}
