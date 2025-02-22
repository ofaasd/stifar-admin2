<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\Models\BerkasDaftarSkripsi;
use App\Models\MasterBimbingan;
use App\Models\Pegawai;
use App\Models\RefPembimbing;
use App\Models\RefPengajuanPembimbing;
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
        $data = RefPengajuanPembimbing::where('nip', $npp)->join('mahasiswa', 'mahasiswa.nim', 'ref_pengajuan_pembimbing.nim')->select('mahasiswa.nama', 'mahasiswa.nim', 'ref_pengajuan_pembimbing.nip AS nip')->get();

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
        $data = RefPengajuanPembimbing::where('nip', $npp)->join('mahasiswa', 'mahasiswa.nim', 'ref_pengajuan_pembimbing.nim')->select('mahasiswa.nama', 'mahasiswa.nim',  'nip')->get();

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
                <button type="button" class="btn btn-sm btn-success" data-id= ' . $row->nim . '>
                <i class="fa fa-check"></i>
            </button>
            <button type="button" class="btn btn-sm btn-danger" data-id= ' . $row->id . '
                >
                <i class="fa fa-x"></i>
            </button>
            <button type="button" class="btn btn-sm btn-warning btnShowModal"  data-id="' . $row->nim . '">
                <i class="fa fa-eye"></i>
            </button>
                ';
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    public function getDetailMhs($nim){
        $data = BerkasDaftarSkripsi::where('berkas_daftar_skripsi.nim',$nim)->join('judul_skripsi','judul_skripsi.nim','berkas_daftar_skripsi.nim')->select('berkas_daftar_skripsi.nim','transkrip_nilai','file_pendukung_1','file_pendukung_2','judul_skripsi.judul','judul_skripsi.abstrak','judul_skripsi.created_at')->first();

        return response()->json($data);
    }

    public function accPengajuan($nim)
    {
        $user = Auth::user();
    
        // Ambil NPP dosen berdasarkan nama user yang sedang login
        $dosen = PegawaiBiodatum::where('nama_lengkap', $user->name)->select('npp')->first();
    
        if (!$dosen) {
            return response()->json(['message' => 'Dosen tidak ditemukan'], 404);
        }
    
        $npp = $dosen->npp;
    
        $data = MasterBimbingan::where('nim', $nim)->first();

        if (!$data) {
            // Jika data belum ada, buat data baru dengan dosen sebagai pembimbing 1
            MasterBimbingan::create([
                'nim' => $nim,
                'nip_pembimbing_1' => $npp,
            ]);
        } else {
            // Jika data sudah ada, periksa kondisi pembimbing 1 dan pembimbing 2
            if (is_null($data->nip_pembimbing_1)) {
                // Jika pembimbing 1 kosong, tetapkan dosen sebagai pembimbing 1
                $data->update(['nip_pembimbing_1' => $npp]);
            } elseif (is_null($data->nip_pembimbing_2)) {
                // Jika pembimbing 2 kosong, tetapkan dosen sebagai pembimbing 2
                $data->update(['nip_pembimbing_2' => $npp]);
            } else {
                // Jika pembimbing 1 dan 2 sudah ada, kembalikan respons error
                return response()->json(['message' => 'Mahasiswa sudah memiliki dua pembimbing'], 400);
            }
        }
    
        // Hapus pengajuan lain dengan NPP yang sama namun NIM berbeda
        RefPengajuanPembimbing::where('nip', $npp)->where('nim', '=', $nim)->delete();
    
        // Kurangi stok pembimbing di tabel RefPembimbing
        $refPembimbing = RefPembimbing::where('nip', $npp)->first();
        if ($refPembimbing) {
            if ($refPembimbing->kuota > 0) {
                $refPembimbing->decrement('kuota', 1);
            } else {
                return response()->json(['message' => 'Stok pembimbing habis'], 400);
            }
        }
    
        return response()->json(['message' => 'Pengajuan berhasil diterima']);
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
