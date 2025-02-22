<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Models\Mahasiswa;
use App\Models\RefPengajuanPembimbing;
use Illuminate\Http\Request;
use App\Models\RefPembimbing;
use App\Models\MasterPembimbing;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PembimbingController extends Controller
{
    public function index(){
        $user = Auth::user();
        $mhs = Mahasiswa::where('id',$user->id)->select('nim')->first();
        $nim = $mhs->nim;
        // $data = MasterPembimbing::where('nim', $nim)->where('status',1)->get();
        $totalPengajuan = RefPengajuanPembimbing::where('nim', $nim)->count();
        return view('mahasiswa.skripsi.pembimbing.index', [
            // 'data' => $data,
            'totalPengajuan' => $totalPengajuan
        ]);

        }

    public function getDaftarPembimbing(){
        $user = Auth::user();
        $mhs = Mahasiswa::where('id',$user->id)->select('nim')->first();
         $data = RefPembimbing::where('kuota', '!=', 0)
        ->join('pegawai_biodata AS pegawai', 'pegawai.npp', '=', 'ref_pembimbing_skripsi.nip') 
        ->select('pegawai.nama_lengkap', 'pegawai.npp', 'ref_pembimbing_skripsi.kuota')
        ->get();
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
                return '<span class="btnModal" data-id="' . $row->npp . '" ><i class="btn btn-primary btn-sm  fa-solid fa-plus"></i></span>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    public function pengajuan(Request $request){
        // Mengambil NIM dari user yang sedang login
        $user = Auth::user();
        $mhs = Mahasiswa::where('id',$user->id)->select('nim')->first();
        $nim = $mhs->nim;    
        // Validasi input request
        $request->validate([
            'nip' => 'required', // Validasi npp sebagai string dan wajib diisi
        ]);
    
        // Menyimpan data ke dalam tabel MasterPembimbing
        RefPengajuanPembimbing::create([
            'nim' => $nim, // Menggunakan variabel $nim dari user yang login
            'nip' => $request->input('nip'), // Mengambil nilai npp dari request
            'created_at' => now(), // Mengambil nilai topik dari request
        ]);
        // Mengembalikan response dalam bentuk JSON
        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil disimpan.',
        ], 200); // 200 adalah status kode HTTP untuk OK
    }
    

    public function deletePengajuan(){

    }
}
