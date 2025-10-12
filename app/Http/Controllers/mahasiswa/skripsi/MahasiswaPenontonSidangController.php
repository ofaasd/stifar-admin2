<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\SidangSkripsi;
use App\Http\Controllers\Controller;
use App\Models\MasterRuang;
use App\Models\PenontonSidang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MahasiswaPenontonSidangController extends Controller
{
    public function index()
    {
        try {
            $title = 'Penonton Sidang';
            $today = now()->toDateString();

            // Clone the base query for reuse
            $baseQuery = SidangSkripsi::select([
                'sidang.id',
                'sidang.tanggal',
                'sidang.waktu_mulai AS waktuMulai',
                'sidang.waktu_selesai AS waktuSelesai',
                'sidang.penguji',
                'sidang.status',
                'sidang.jenis',
                'sidang.proposal',
                'sidang.kartu_bimbingan AS kartuBimbingan',
                'sidang.presentasi',
                'sidang.pendukung',
                'sidang.acc_pembimbing1 AS accPembimbing1',
                'sidang.acc_pembimbing2 AS accPembimbing2',
                'sidang.acc1_at AS acc1At',
                'sidang.acc2_at AS acc2At',
                'gelombang_sidang_skripsi.nama AS namaGelombang',
                'master_ruang.nama_ruang AS namaRuang',
                'mahasiswa.nama'
            ])
            ->leftJoin('master_skripsi', 'master_skripsi.id', '=', 'sidang.skripsi_id')
            ->leftJoin('mahasiswa', 'mahasiswa.nim', '=', 'master_skripsi.nim')
            ->leftJoin('gelombang_sidang_skripsi', 'sidang.gelombang_id', '=', 'gelombang_sidang_skripsi.id')
            ->leftJoin('master_ruang', 'sidang.ruang_id', '=', 'master_ruang.id')
            ->where('sidang.acc_pembimbing1', 1)
            ->where('sidang.acc_pembimbing2', 1)
            ->whereNotNull('sidang.tanggal')
            ->whereNotNull('sidang.ruang_id');

            // Sidang hari ini
            $sidangHariIni = (clone $baseQuery)
                ->whereDate('sidang.tanggal', $today)
                ->orderBy('sidang.tanggal', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
                    return $item;
                });

            // Sidang yang akan datang (tanggal > hari ini)
            $sidangAkanDatang = (clone $baseQuery)
                ->whereDate('sidang.tanggal', '>', $today)
                ->orderBy('sidang.tanggal', 'asc')
                ->get();

            // Contoh return ke view
            return view('mahasiswa.skripsi.penonton-sidang.index', compact('sidangHariIni', 'sidangAkanDatang', 'title'));

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $data = SidangSkripsi::select([
                'sidang.id',
                'sidang.tanggal',
                'sidang.waktu_mulai AS waktuMulai',
                'sidang.waktu_selesai AS waktuSelesai',
                'sidang.penguji',
                'sidang.status',
                'sidang.jenis',
                'gelombang_sidang_skripsi.nama AS namaGelombang',
                'master_ruang.nama_ruang AS namaRuang',
                'master_ruang.kapasitas',
                'mahasiswa.nama',
                'mahasiswa.nim'
            ])
            ->leftJoin('master_skripsi', 'master_skripsi.id', '=', 'sidang.skripsi_id')
            ->leftJoin('mahasiswa', 'mahasiswa.nim', '=', 'master_skripsi.nim')
            ->leftJoin('gelombang_sidang_skripsi', 'sidang.gelombang_id', '=', 'gelombang_sidang_skripsi.id')
            ->leftJoin('master_ruang', 'sidang.ruang_id', '=', 'master_ruang.id')
            ->where('sidang.acc_pembimbing1', 1)
            ->where('sidang.acc_pembimbing2', 1)
            ->whereNotNull('sidang.tanggal')
            ->whereNotNull('sidang.ruang_id')
            ->where('sidang.id', $id)
            ->first();

        $isRegistered = false;
        if ($data) {
            $mhs = Mahasiswa::where('user_id', Auth::id())->first();
            $isRegistered = PenontonSidang::where('id_sidang', $id)
            ->where('nim', $mhs->nim)
            ->exists();
            $data->isRegistered = $isRegistered;
        }

        return view('mahasiswa.skripsi.penonton-sidang.show', compact('data'));
    }

    public function daftar(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            $mhs = Mahasiswa::where('user_id', Auth::id())->first();
            $nim = $mhs->nim;

            $sidang = SidangSkripsi::select([
                'sidang.id',
                'sidang.ruang_id',
                'master_skripsi.nim AS nimSidang'
            ])
            ->leftJoin('master_skripsi', 'master_skripsi.id', '=', 'sidang.skripsi_id')
            ->where('sidang.id', $request->id)
            ->first();
            $cekJumlahPenonton = PenontonSidang::where('id_sidang', $sidang->id)->count();
            $cekRuang = MasterRuang::where('id', $sidang->ruang_id)->first();

            if($cekJumlahPenonton >= $cekRuang->kapasitas) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maaf, kuota penonton untuk sidang ini sudah penuh.'
                ], 400);
            }

            $cekSudahDaftar = PenontonSidang::where('id_sidang', $sidang->id)
            ->where('nim', $nim)
            ->first();

            if($cekSudahDaftar) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah terdaftar sebagai penonton untuk sidang ini.'
                ], 400);
            }

            if($nim == $sidang->nimSidang) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak dapat mendaftar sebagai penonton untuk sidang Anda sendiri.'
                ], 400);
            }

            PenontonSidang::create([
                'id_sidang' => $sidang->id,
                'nim' => $nim,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendaftar sebagai penonton sidang.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
