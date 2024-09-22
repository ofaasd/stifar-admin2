<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Models\Mahasiswa;
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
        $data = MasterPembimbing::where('nim', $nim)->where('status',1)->get();
        $totalPengajuan = MasterPembimbing::where('nim', $nim)->where('status',0)->count();
        return view('mahasiswa.skripsi.pembimbing.index', [
            'data' => $data,
            'totalPengajuan' => $totalPengajuan
        ]);

        }

    public function getDaftarPembimbing(){
        $user = Auth::user();
        $mhs = Mahasiswa::where('id',$user->id)->select('nim')->first();
        $nim = $mhs->nim;        $data = RefPembimbing::where('kuota', '!=', 0)
        ->join('pegawai_biodata AS pegawai', 'pegawai.npp', '=', 'ref_pembimbing_skripsi.nip') 
        ->leftJoin('master_pembimbing_skripsi', function($join) use ($nim) {
            $join->on('master_pembimbing_skripsi.nip', '=', 'ref_pembimbing_skripsi.nip')
                 ->where('master_pembimbing_skripsi.nim', '=', $nim);
        })
        ->whereNull('master_pembimbing_skripsi.nim')
        ->select('pegawai.nama_lengkap', 'pegawai.npp', 'ref_pembimbing_skripsi.kuota') // Pastikan kuota diambil dari ref_pembimbing_skripsi
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
                return '<button class="btn btn-primary btn-sm btnModal text-light" data-id="' . $row->npp . '" data-bs-toggle="modal" data-bs-target="#FormModal" >Ajukan</button>';
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
            'topik' => 'required|string', // Validasi topik sebagai string dan wajib diisi
        ]);
    
        // Menyimpan data ke dalam tabel MasterPembimbing
        MasterPembimbing::create([
            'nim' => $nim, // Menggunakan variabel $nim dari user yang login
            'nip' => $request->input('nip'), // Mengambil nilai npp dari request
            'topik_judul' => $request->input('topik'), // Mengambil nilai topik dari request
            'status' => 0, // Mengambil nilai topik dari request
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
