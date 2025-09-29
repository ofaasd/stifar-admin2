<?php

namespace App\Http\Controllers\dosen\akademik\skripsi;

use Illuminate\Http\Request;
use App\Models\MasterSkripsi;
use App\Models\BerkasBimbingan;
use App\Models\PegawaiBiodatum;
use App\Models\BimbinganSkripsi;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanJudulSkripsi;
use Illuminate\Support\Facades\Crypt;

class DosenBimbinganController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Bimbingan',
        ];
        return view('dosen.akademik.skripsi.bimbingan.index', $data);
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
        ])
        ->where(function($query) use ($nppDosen) {
            $query->where('master_skripsi.pembimbing_1', $nppDosen)
                ->orWhere('master_skripsi.pembimbing_2', $nppDosen);
        })
        ->leftJoin('pengajuan_judul_skripsi', 'master_skripsi.id', '=', 'pengajuan_judul_skripsi.id_master')
        ->leftJoin('mahasiswa', 'master_skripsi.nim', '=', 'mahasiswa.nim')
        ->where('master_skripsi.status', '=', 2)
        ->where('pengajuan_judul_skripsi.status', '=', 1)
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
            ->addColumn('actions', function($row) {
                $btn = '<a href="' . route('akademik.skripsi.dosen.bimbingan.show', $row->idEnkripsi) . '" class="btn btn-sm btn-primary" id="btn-bimbingan">Lihat Bimbingan</a>';
                return $btn;
            })
            ->rawColumns(['actions', 'mahasiswa'])
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
                return response()->json(["message"  => "Data tidak ditemukan"]);
            }

            $dosen = PegawaiBiodatum::where('pegawai_biodata.id', $cekUser->id)
            ->first();

            if(!$dosen){
                return response()->json(["message"  => "Data tidak ditemukan"]);
            }

            // Ambil semua data bimbingan mahasiswa dengan relasi
            $bimbingan = BimbinganSkripsi::where('id_master', $masterSkripsi->id)
            ->orderBy('tanggal_waktu', 'desc')
            ->get();

            $bimbingan = $bimbingan->map(function ($item) {
                $item->idEnkripsi = Crypt::encryptString($item->id . "stifar");
           
                // Parse waktu jika tersimpan dalam format tertentu
                if ($item->created_at) {
                    $datetime = \Carbon\Carbon::parse($item->created_at);
                    $item->tanggal_formatted = $datetime->format('d F Y');
                    $item->waktu_formatted = $datetime->format('H:i');
                }

                $item->bimbinganKe = PegawaiBiodatum::where('npp', $item->bimbingan_to)->value('nama_lengkap');
                
                // Status label untuk referensi
                switch ($item->status) {
                    case 0:
                        $item->status_label = 'Menunggu';
                        break;
                    case 1:
                        $item->status_label = 'ACC';
                        break;
                    case 2:
                        $item->status_label = 'Disetujui';
                        break;
                    case 3:
                        $item->status_label = 'Revisi';
                        break;
                    default:
                        $item->status_label = 'Unknown';
                }
                
                return $item;
            });

            $mahasiswa = Mahasiswa::where('nim', $masterSkripsi->nim)->first();

            $data = [
                'title' => 'Detail Bimbingan ' . $mahasiswa->nama,
                'bimbingan' => $bimbingan,
                'judulSkripsi' => $judulSkripsi,
                'mahasiswa' => $mahasiswa,
                'dosen' => $dosen,
            ];

            return view('dosen.akademik.skripsi.bimbingan.show', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $bimbingan = BimbinganSkripsi::find($id);
        if (!$bimbingan) {
            return redirect()->back()->with('error', 'Data bimbingan tidak ditemukan.');
        }

        try {
            $request->validate([
                'catatanDosen' => 'nullable|string',
            ]);

            $bimbingan->catatan_dosen = $request->input('catatanDosen', $bimbingan->catatan_dosen);
            $bimbingan->save();

            return redirect()->back()->with('success', 'Data bimbingan berhasil diperbarui.');
        } catch (\Exception $e) { 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $idEnkripsi)
    {
        $idDekrip = Crypt::decryptString($idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $bimbingan = BimbinganSkripsi::find($id);
        if (!$bimbingan) {
            return response()->json(['error' => 'Data bimbingan tidak ditemukan.'], 404);
        }

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

        try {
            $request->validate([
            'status' => 'nullable|string',
            ]);

            $bimbingan->status = $request->input('status', $bimbingan->status);
            $bimbingan->bimbingan_to = $nppDosen;

            $bimbingan->save();

            return response()->json(['success' => true, 'message' => 'Data bimbingan berhasil diperbarui.']);
        } catch (\Exception $e) { 
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
