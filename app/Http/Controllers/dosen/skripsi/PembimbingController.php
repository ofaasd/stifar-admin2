<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\Http\Controllers\Controller;
use App\Models\PegawaiBiodatum;
use App\Models\RefPembimbing;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Log;

class PembimbingController extends Controller
{
    public function index()
    {
        return view('dosen.skripsi.pembimbing.pembimbing');
    }

    public function getPembimbingData(Request $request)
    {
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
        ->addColumn('button', function($row){
            $btn = '<button class="btn btn-sm btn-info view" data-id="' . $row->npp . '"><i class="bi bi-eye"></i></button>';
            $btn .= ' <button class="btn btn-sm btn-warning edit-btn" data-id="' . $row->npp . '"><i class="bi bi-pencil"></i></button>';
            return $btn;
        })
        ->rawColumns(['button'])
        ->make(true);
        }

        public function detail($nip){
            
            $mahasiswaPengajuan = Skripsi::where('status',0)->join('pembimbing', 'pembimbing.skripsi.id','skripsi.id')->join('mahasiswa','mahasiswa.nim','skripsi.nim')->get();
            $mahasiswaBimbingan = Skripsi::where('status',2)->join('pembimbing', 'pembimbing.skripsi.id','skripsi.id')->join('mahasiswa','mahasiswa.nim','skripsi.nim')->get();
            return view('dosen.skripsi.pembimbing.detail',compact('mahasiswaPengajuan','mahasiswaBimbingan'));
        }
        public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|max:255',
            'kuota' => 'required|integer|min:0',
            'id_progdi' => 'required|integer',
        ]);

        try {
            RefPembimbing::create($request->only('nip', 'kuota', 'id_progdi'));
            return response()->json(['success' => 'Data pembimbing berhasil ditambahkan.']);
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan pembimbing: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan data.'], 500);
        }
    }

    public function edit($id)
    {
        $pembimbing = RefPembimbing::findOrFail($id);
        return response()->json($pembimbing);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nip' => 'required|string|max:255',
            'kuota' => 'required|integer|min:0',
            'id_progdi' => 'required|integer',
        ]);

        try {
            $pembimbing = RefPembimbing::findOrFail($id);
            $pembimbing->update($request->only('nip', 'kuota', 'id_progdi'));
            return response()->json(['success' => 'Data pembimbing berhasil diperbarui.']);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pembimbing: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pembimbing = RefPembimbing::findOrFail($id);
            $pembimbing->delete();
            return response()->json(['success' => 'Data pembimbing berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pembimbing: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }

}
