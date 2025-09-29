<?php

namespace App\Http\Controllers\dosen\akademik\skripsi;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\PegawaiBiodatum;
use App\Models\BimbinganSkripsi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanJudulSkripsi;
use App\Models\PengujiSkripsi;
use App\Models\SidangSkripsi;
use Illuminate\Support\Facades\Crypt;

class DosenPengujiController extends Controller
{
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

        $data = MasterSkripsi::select([
            'master_skripsi.id',
            'mahasiswa.nim',
            'mahasiswa.nama',
            'pengajuan_judul_skripsi.judul',
            'master_ruang.nama_ruang AS namaRuang',
            'sidang.tanggal',
            'sidang.waktu_mulai AS waktuMulai',
            'sidang.waktu_selesai AS waktuSelesai',
        ])
        ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
        ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
        ->leftJoin('sidang', 'master_skripsi.id', '=', 'sidang.skripsi_id')
        ->leftJoin('master_ruang', 'sidang.ruang_id', '=', 'master_ruang.id')
        ->whereIn('master_skripsi.status', [1, 2])
        ->where('pengajuan_judul_skripsi.status', '=', 1)
        ->whereRaw('FIND_IN_SET(?, sidang.penguji)', [$nppDosen])
        ->orderBy('sidang.tanggal', 'asc')
        ->get()
        ->map(function ($item) {
            $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
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
                    $tanggal = \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y');
                    return $tanggal . ' | ' . $row->waktuMulai . ' - ' . $row->waktuSelesai;
                }
                return '-';
            })
            ->addColumn('actions', function($row) {
                $btn = '<a href="' . route('akademik.skripsi.dosen.penguji.show', $row->idEnkripsi) . '" class="btn btn-sm btn-primary" id="btn-detail">Detail</a>';
                return $btn;
            })
            ->rawColumns(['actions', 'mahasiswa', 'pelaksanaan'])
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex
            ->make(true);

    }

    public function show($idEnkripsi)
    {
        try {
            $idDekrip = Crypt::decryptString($idEnkripsi);
            $id = str_replace("stifar", "", $idDekrip);
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
                $sidang->idEnkripsi = Crypt::encryptString($sidang->id . "stifar");
            } 

            $penguji = PengujiSkripsi::where('sidang_id', $sidang->id)->where('npp', $dosen->npp)->first();

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
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $sidang = SidangSkripsi::find($id);
        if (!$sidang) {
            return redirect()->back()->with('error', 'Data Sidang tidak ditemukan.');
        }

        try {
            PengujiSkripsi::updateOrCreate(
                [
                    'sidang_id' => $sidang->id,
                    'npp' => PegawaiBiodatum::where('user_id', Auth::id())->first()->npp,
                ],
                [
                    'nilai' => $request->input('nilai'),
                    'catatan' => $request->input('catatan'),
                ]
            );

            return redirect()->back()->with('success', 'Berhasil mengupdate nilai Ujian.');
        } catch (\Exception $e) { 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $sidang = SidangSkripsi::find($id);
        if (!$sidang) {
            return redirect()->back()->with('error', 'Data Sidang tidak ditemukan.');
        }

        try {
            PengujiSkripsi::where('sidang_id', $sidang->id)
                ->where('npp', PegawaiBiodatum::where('user_id', Auth::id())->first()->npp)
                ->update(['status' => 1]);

            return redirect()->back()->with('success', 'Berhasil mengupdate nilai Ujian.');
        } catch (\Exception $e) { 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
