<?php

namespace App\Http\Controllers\dosen\akademik\skripsi;

use App\helpers;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\PegawaiBiodatum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanJudulSkripsi;
use App\Models\AktorSidang;
use App\Models\SidangSkripsi;

class DosenPengujiController extends Controller
{

    protected $helpers;

    public function __construct()
    {
        $this->helpers = new helpers();
    }

    public function index()
    {
        $data = [
            'title' => 'Penguji',
        ];
        return view('dosen.akademik.skripsi.penguji.index', $data);
    }
    public function getData()
    {
        $cekUser = PegawaiBiodatum::where('user_id', Auth::id())->first();
        if(!$cekUser){
            return response()->json(["message"  => "Data tidak ditemukan"]);
        }
        $dosen = PegawaiBiodatum::where('pegawai_biodata.id', $cekUser->id)
        ->first();
        if(!$dosen){
            return response()->json(["message"  => "Data tidak ditemukan"]);
        }

        $nppDosen = $dosen->npp;

        $aktorSidang = AktorSidang::where('npp', $nppDosen)->where('as', 'penguji')->get();

        $data = MasterSkripsi::select([
            'master_skripsi.id',
            'mahasiswa.nim',
            'mahasiswa.nama',
            'pengajuan_judul_skripsi.judul',
            'master_ruang.nama_ruang AS namaRuang',
            'sidang.id AS sidangId',
            'sidang.tanggal',
            'sidang.jenis',
            'sidang.waktu_mulai AS waktuMulai',
            'sidang.waktu_selesai AS waktuSelesai',
        ])
        ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
        ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
        ->leftJoin('sidang', 'master_skripsi.id', '=', 'sidang.skripsi_id')
        ->leftJoin('master_ruang', 'sidang.ruang_id', '=', 'master_ruang.id')
        ->whereIn('master_skripsi.status', [1, 2])
        ->where('pengajuan_judul_skripsi.status', '=', 1)
        ->whereNotNull('sidang.tanggal')
        ->whereRaw('FIND_IN_SET(?, sidang.penguji)', [$nppDosen])
        ->orderBy('sidang.created_at', 'desc')
        ->get()
        ->map(function ($item) {
            $item->idEnkripsi = $this->helpers->encryptId($item->id);
            $item->idSidangEnkripsi = $this->helpers->encryptId($item->sidangId);
            return $item;
        });

        if (empty($data)) {
            return response()->json([
                'draw' => request('draw'), // draw dari DataTables request
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        return \DataTables::of($data)
            ->addColumn('mahasiswa', function($row) {
                return $row->nim . ' - ' . $row->nama;
            })
            ->addColumn('pelaksanaan', function($row) {
                if ($row->tanggal && $row->waktuMulai && $row->waktuSelesai) {
                    $carbonTanggal = \Carbon\Carbon::parse($row->tanggal);
                    $hari = $carbonTanggal->translatedFormat('l'); // Nama hari
                    $tanggal = $carbonTanggal->translatedFormat('d F Y');
                    return $hari . ', ' . $tanggal . ' | ' . $row->waktuMulai . ' - ' . $row->waktuSelesai;
                }
                return '-';
            })
            ->addColumn('status', function($row) use ($aktorSidang, $nppDosen) {
                // Cek apakah sudah ada data penguji untuk sidang dan npp dosen ini
                $isAcc = $aktorSidang->where('sidang_id', $row->sidangId)->where('npp', $nppDosen)->first();

                if ($isAcc) {
                    return '<span class="badge bg-success">Menguji</span>';
                } else {
                    return '<span class="badge bg-warning">Pengajuan</span>';
                }
            })
            ->addColumn('actions', function($row) use ($aktorSidang, $nppDosen, $dosen) {
                // Cek apakah sudah ada data penguji untuk sidang dan npp dosen ini
                $isAcc = $aktorSidang->where('sidang_id', $row->sidangId)->where('npp', $nppDosen)->first();

                if ($isAcc) {
                    // Jika sudah ada, tampilkan tombol Detail saja
                    $btn = '<a href="' . route('akademik.skripsi.dosen.penguji.show', $row->idEnkripsi) . '" class="btn btn-sm btn-primary" id="btn-detail">Detail</a>';
                } else {
                    // Jika belum ada, tampilkan tombol Setuju dengan tooltip
                    $sebutan = ($dosen->jenis_kelamin == 'L') ? 'Bapak' : 'Ibu';
                    $tooltip = 'Kepada Yth : ' .
                        $sebutan . ' ' . $dosen->nama_lengkap . ' ' .
                        'Dosen Penguji Di tempat. ' .
                        'Mengharap dengan hormat atas kehadiran ' . $sebutan . ' pada ' . 
                        ($row->jenis == 1 ? 'seminar proposal' : ($row->jenis == 2 ? 'seminar hasil' : 'seminar')) . 
                        ' untuk mahasiswa : ' .
                        'Nama : ' . ($row->nama ?? '-') . ' ' .
                        'NIM : ' . ($row->nim ?? '-') . ' ' .
                        'Akan dilaksanakan pada: ' .
                        'Hari/tanggal : ' . ($row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d F Y') : '-') . ' ' .
                        'Waktu : ' . ($row->waktuMulai ?? '-') . ' - ' . ($row->waktuSelesai ?? '-') . ' ' .
                        'Tempat : ' . ($row->namaRuang ?? '-') . ' ' .
                        'Demikian undangan ini, dimohon kesediaan ' . $sebutan . ' untuk menguji mahasiswa tersebut di atas. Atas perhatian dan kerjasamanya kami sampaikan terima kasih.';
                    $btn = '<form method="POST" action="' . route('akademik.skripsi.dosen.penguji.acc', $row->idSidangEnkripsi) . '" style="display:inline;" id="form-acc">'
                        . csrf_field()
                        . '<button type="submit" class="btn btn-sm btn-success" id="btn-submit" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $tooltip . '">Setuju</button>'
                        . '</form>';
                }
                return $btn;
            })
            ->rawColumns(['actions', 'mahasiswa', 'pelaksanaan', 'status', 'teks']) // Agar kolom actions dapat menampilkan HTML
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex
            ->make(true);

    }

    public function show($idEnkripsi)
    {
        try {
            $id = $this->helpers->decryptId($idEnkripsi);
            $masterSkripsi = MasterSkripsi::find($id);

            if (!$masterSkripsi) {
                return redirect()->back()->with('error', 'Data skripsi tidak ditemukan.');
            }

            $judulSkripsi = PengajuanJudulSkripsi::where('id_master', $masterSkripsi->id)->where('status', 1)->first();

            $cekUser = PegawaiBiodatum::where('user_id', Auth::id())->first();
            if(!$cekUser){
                return response()->json(["message"  => "Data User tidak ditemukan"]);
            }

            $dosen = PegawaiBiodatum::where('pegawai_biodata.id', $cekUser->id)
            ->first();

            if(!$dosen){
                return response()->json(["message"  => "Data Dosen tidak ditemukan"]);
            }

            $mahasiswa = Mahasiswa::where('nim', $masterSkripsi->nim)->first();
            $sidang = SidangSkripsi::select([
                'sidang.*',
                'master_ruang.nama_ruang AS ruangan'
            ])
            ->leftJoin('master_ruang', 'sidang.ruang_id', '=', 'master_ruang.id')
            ->where('skripsi_id', $masterSkripsi->id)
            ->first();

            if($sidang){
                $sidang->idEnkripsi = $this->helpers->encryptId($sidang->id);
            } 

            $penguji = AktorSidang::where('sidang_id', $sidang->id)->where('npp', $dosen->npp)->first();

            $data = [
                'title' => 'Detail Menguji ' . $mahasiswa->nama,
                'judulSkripsi' => $judulSkripsi,
                'mahasiswa' => $mahasiswa,
                'dosen' => $dosen,
                'sidang' => $sidang,
                'penguji' => $penguji,
            ];

            return view('dosen.akademik.skripsi.penguji.show', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateNilai(Request $request, $idEnkripsi)
    {
        $id = $this->helpers->decryptId($idEnkripsi);

        $sidang = SidangSkripsi::find($id);
        if (!$sidang) {
            return redirect()->back()->with('error', 'Data Sidang tidak ditemukan.');
        }

        try {
            AktorSidang::where('sidang_id', $sidang->id)
                ->where('npp', PegawaiBiodatum::where('user_id', Auth::id())->first()->npp)
                ->update([
                    'kesinambungan' => $request->kesinambungan,
                    'kesesuaian_daftar_pustaka' => $request->kesesuaianDaftarPustaka,
                    'keterbaruan' => $request->keterbaruan,
                    'kejelasan_rumus' => $request->kejelasanRumus,
                    'relevansi_latar_belakang' => $request->relevansiLatarBelakang,
                    'penampilan_sikap' => $request->penampilanSikap,
                    'argumen' => $request->argumen,
                    'kesesuaian_jawaban' => $request->kesesuaianJawaban,
                    'kedalaman_penguasaan' => $request->kedalamanPenguasaan,
                    'jumlah_nilai' => $request->jumlahNilai,
                    'nilai_akhir' => $request->nilaiAkhir,
                    'catatan' => $request->catatan,
                ]);

            return redirect()->back()->with('success', 'Berhasil mengupdate nilai Ujian.');
        } catch (\Exception $e) { 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $idEnkripsi)
    {
        $id = $this->helpers->decryptId($idEnkripsi);
        $sidang = SidangSkripsi::find($id);
        if (!$sidang) {
            return redirect()->back()->with('error', 'Data Sidang tidak ditemukan.');
        }

        try {
            AktorSidang::where('sidang_id', $sidang->id)
                ->where('npp', PegawaiBiodatum::where('user_id', Auth::id())->first()->npp)
                ->update(['status' => 1]);

            return redirect()->back()->with('success', 'Berhasil mengupdate nilai Ujian.');
        } catch (\Exception $e) { 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function acc(Request $request, $idEnkripsi)
    {
        $id = $this->helpers->decryptId($idEnkripsi);
        $sidang = SidangSkripsi::find($id);
        if (!$sidang) {
            return redirect()->back()->with('error', 'Data Sidang tidak ditemukan.');
        }

        try {
            AktorSidang::firstOrCreate(
                [
                    'sidang_id' => $sidang->id,
                    'npp' => PegawaiBiodatum::where('user_id', Auth::id())->first()->npp,
                    'as' => 'penguji',
                ]
            );

            return redirect()->back()->with('success', 'Berhasil, Anda telah menjadi Penguji.');
        } catch (\Exception $e) { 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
