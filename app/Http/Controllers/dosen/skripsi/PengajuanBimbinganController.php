<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\MasterPembimbing;
use App\Http\Controllers\Controller;
use App\Models\PegawaiBiodatum;
use Illuminate\Support\Facades\Auth;

class PengajuanBimbinganController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $dosen = PegawaiBiodatum::where('nama_lengkap', $user->name)->select('npp')->first();

        $npp = $dosen->npp;
        $data = MasterPembimbing::where('nip', $npp)->join('mahasiswa', 'mahasiswa.nim', 'master_pembimbing_skripsi.nim')->select('mahasiswa.nama', 'mahasiswa.nim', 'master_pembimbing_skripsi.topik_judul AS judul', 'master_pembimbing_skripsi.nip AS nip')->get();

        return view('dosen.skripsi.pembimbing.index', [
            'data' => $data,
            // 'totalPengajuan' => $totalPengajuan
        ]);
    }

    public function getDataMahasiswa()
    {
        $user = Auth::user();
        $dosen = PegawaiBiodatum::where('nama_lengkap', $user->name)->select('npp')->first();
        $npp = $dosen->npp;
        $data = MasterPembimbing::where('nip', $npp)->join('mahasiswa', 'mahasiswa.nim', 'master_pembimbing_skripsi.nim')->select('master_pembimbing_skripsi.id','mahasiswa.nama', 'mahasiswa.nim', 'master_pembimbing_skripsi.topik_judul AS judul', 'master_pembimbing_skripsi.nip AS nip')->get();

        // Jika data kosong, kirim response dengan pesan khusus
        if ($data->isEmpty()) {
            return response()->json([
                'draw' => request('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        // Mengirim data ke DataTables
        return \DataTables::of($data)
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex
            ->addColumn('button', function ($row) {
                return '
                <button type="button" class="btn btn-sm btn-info" data-id= ' . $row->nim . '>
                Acc
            </button>
            <button type="button" class="btn btn-sm btn-danger" data-id= ' . $row->id . '
                >
                Tolak
            </button>
                ';
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    public function accPengajuan($nim)
    {
        $user = Auth::user();
        $dosen = PegawaiBiodatum::where('nama_lengkap', $user->name)->select('npp')->first();
        $npp = $dosen->npp;
        $data = MasterPembimbing::where('nim', $nim)->where('nip', $npp);
        if ($data->exists()) {
            $data->update(['status' => 1]);

            // Menghapus data lain dengan npp yang sama namun nim berbeda
            MasterPembimbing::where('nip', $npp)->where('nim', '!=', $nim)->delete();

            return response()->json(['message' => 'Pengajuan berhasil diterima']);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function delete($id)
    {
        $data = MasterPembimbing::where('id', $id);

        if ($data->exists()) {
            // Hapus data
            $data->delete();

            return response()->json(['message' => 'Data berhasil dihapus']);
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

}
