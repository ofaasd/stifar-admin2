<?php

namespace App\Http\Controllers\admin\skripsi;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\RefPembimbing;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\PegawaiBiodatum;

class DosenPembimbingController extends Controller
{
    public function index()
    {
        return view('admin.skripsi.dosbim.index');
    }

    public function getListDosen()
    {
        // Mengambil data dosen pembimbing dengan kuota yang tidak 0 dan join ke tabel pegawai
        $data = RefPembimbing::join('pegawai_biodata as pegawai', 'pegawai.npp', '=', 'ref_pembimbing_skripsi.nip')
            ->select('pegawai.nama_lengkap AS nama', 'pegawai.npp', 'kuota')
            ->get();

        // Jika data kosong, kirim response dengan pesan khusus
        if ($data->isEmpty()) {
            return response()->json([
                'draw' => request('draw'), // draw dari DataTables request
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        // Mengirim data ke DataTables
        return \DataTables::of($data)
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex
            ->addColumn('button', function ($row) {
                return '<button class="btn btn-primary btn-sm edit-btn text-light" data-id="' . $row->npp . '" >Edit Kuota</button>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    public function getData()
    {
        $data = PegawaiBiodatum::get(); // Mengambil data dari model Pegawai

        return \DataTables::of($data)
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex
            ->addColumn('button', function ($row) {
                return '<button class="btn btn-warning btn-sm edit-btn text-light" data-id="' . $row->nip . '">Acc</button>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    public function getNppDosen()
    {
        $data = PegawaiBiodatum::select('npp', 'nama_lengkap')->get();
        // dd($data);
        return response()->json($data);
    }

    public function accDosen(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|string|max:255',
            'kuota' => 'required|integer|min:0', // Tambahkan validasi untuk sisa kuota
        ]);

        try {
            // Menggunakan updateOrCreate untuk menyederhanakan logika penyimpanan
            RefPembimbing::updateOrCreate(
                ['nip' => $request->input('nip')],
                ['sisa_kuota' => $request->input('sisa_kuota')]
            );

            return response()->json(['success' => 'Dosen Pembimbing berhasil ditambahkan/diupdate']);
        } catch (\Exception $e) {
            // Log jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat Menambahkan Dosen Pembimbing.', [
                'nip' => $request->input('nip'),
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'Terjadi kesalahan, silakan coba lagi.'], 500);
        }
    }

    public function edit($nip)
    {
        // Ambil data dosen pembimbing berdasarkan nip
        $dosen = RefPembimbing::where('nip', $nip)
            ->join('pegawai_biodata AS pegawai','pegawai.npp', '=', 'ref_pembimbing_skripsi.nip')
            ->select('pegawai.nama_lengkap','nip','kuota')
            ->firstOrFail();
    if($dosen){
        return response()->json([
            'nip' => $dosen->nip . ' - ' . $dosen->nama_lengkap,
            'kuota' => $dosen->kuota
        ]);
    }else{
        return response()->json(['message' => 'Dosen Pembimbing Tidak Ditemukan'], 404);
    }
    }
    

    public function updateKuota(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|string|max:255',
            'kuota' => 'required|integer|min:0'
        ]);
        $nipFull = $request->input('nip'); // Contoh: "020399004 - Achmad Wildan, ST.,M.T"
        $nip = explode(' - ', $nipFull)[0]; // Ambil bagian sebelum " - "
      
        try {
            // Menggunakan updateOrCreate untuk menyederhanakan logika penyimpanan
            RefPembimbing::updateOrCreate(
                ['nip' => $nip],
                ['kuota' => $request->input('kuota')]
            );

            return response()->json(['success' => 'Kuota berhasil diperbarui']);
        } catch (\Exception $e) {
            // Log jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat memperbarui kuota.', [
                'nip' => $request->input('nip'),
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'Terjadi kesalahan, silakan coba lagi.'], 500);
        }
    }

}
