<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Models\BerkasDaftarSkripsi;
use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DaftarSkripsiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mhs = Mahasiswa::where('id', $user->id)->select('nim')->first();
        $nim = $mhs->nim;
    
        $data = BerkasDaftarSkripsi::where('nim', $nim)->get(); // Perbaiki dengan get()

        // dd($data);
        return view('mahasiswa.skripsi.daftar.index', compact('data'));
    }
    
    public function getData(){
        $user = Auth::user();
        $mhs = Mahasiswa::where('id',$user->id)->select('nim')->first();
        $nim = $mhs->nim;    
        $data = BerkasDaftarSkripsi::where('nim', $nim)->get(); // Perbaiki dengan get()
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
                return '<button class="btn btn-primary btn-sm btnModal text-light" data-id="' . $row->nim . '" data-bs-toggle="modal" data-bs-target="#FormModal" >Ajukan</button>';
            })
            ->rawColumns(['button'])
            ->make(true);
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
            ->addColumn('statusd', function ($row) {
                return $row->status == 0 
                    ? '<span class="btn btn-primary btn-sm  text-light">Pending</span>' 
                    : '<span class="btn btn-success btn-sm  text-light">Done</span>';
            })
            ->addColumn('button', function ($row) {
                return '<button class="btn btn-primary btn-sm btnModal text-light" data-id="' . $row->npp . '" data-bs-toggle="modal" data-bs-target="#FormModal" >Ajukan</button>';
            })
            ->rawColumns(['button','statusd'])
            ->make(true);
    }

    public function saveDaftar(Request $request)
    {
        $user = Auth::user();
        $mhs = Mahasiswa::where('id', $user->id)->select('nim')->first();
        $nim = $mhs->nim;
    
        $request->validate([
            'transkrip' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file1' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file2' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
    
        $directory = "skripsi/berkas_daftar";
    
        $transkripPath = $request->file('transkrip')->storeAs($directory, Str::random(10) . '.' . $request->file('transkrip')->getClientOriginalExtension(), 'public');
        $file1Path = $request->file('file1') ? $request->file('file1')->storeAs($directory, Str::random(10) . '.' . $request->file('file1')->getClientOriginalExtension(), 'public') : null;
        $file2Path = $request->file('file2') ? $request->file('file2')->storeAs($directory, Str::random(10) . '.' . $request->file('file2')->getClientOriginalExtension(), 'public') : null;
    
        BerkasDaftarSkripsi::create([
            'nim' => $nim,
            'transkrip_nilai' => $transkripPath,
            'file_pendukung_1' => $file1Path,
            'file_pendukung_2' => $file2Path,
            'status' => '0',
            'created_at' => now(),
        ]);
    
        return redirect()->back()->with('message', 'Pengajuan berhasil disimpan.');
    }
    

}
