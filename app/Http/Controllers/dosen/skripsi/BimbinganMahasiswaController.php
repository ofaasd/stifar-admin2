<?php

namespace App\Http\Controllers\dosen\skripsi;

use App\Http\Controllers\Controller;
use App\Models\BimbinganSkripsi;
use App\Models\JudulSkripsi;
use App\Models\LogbookBimbingan;
use App\Models\Mahasiswa;
use App\Models\MasterBimbingan;
use App\Models\PegawaiBiodatum;
use Auth;
use Illuminate\Http\Request;

class BimbinganMahasiswaController extends Controller
{
    public function index()
    {

        return view('dosen.skripsi.bimbingan.index');
    }

    public function getDataMhs()
    {
        $user = Auth::user();
        $dosen = PegawaiBiodatum::where('nama_lengkap', $user->name)->select('npp')->first();
        $npp = $dosen->npp;
        $data = MasterBimbingan::orwhere('nip_pembimbing_1', $npp)->orWhere('nip_pembimbing_2', $npp)->join('mahasiswa', 'mahasiswa.nim', 'master_bimbingan_skripsi.nim')->join('judul_skripsi', 'judul_skripsi.nim', 'master_bimbingan_skripsi.nim')->select('master_bimbingan_skripsi.id AS id_master', 'judul_skripsi.judul', 'judul_skripsi.abstrak', 'mahasiswa.nama', 'mahasiswa.nim')->get();

        // Jika data kosong, kirim response dengan pesan khusus
        if ($data->isEmpty()) {
            return response()->json([
                'draw' => request('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }

        // Mengirim data ke DataTables
        return \DataTables::of($data)
            ->addIndexColumn() // Menambahkan kolom DT_RowIndex
            ->addColumn('button', function ($row) {
                return '<a href="' . route('dosen.bimbingan.detail', $row->id_master) . '" class="btn btn-sm btn-warning">
            <i class="fa fa-eye"></i>
        </a>';
            })
            ->rawColumns(['button'])
            ->make(true);

    }
    public function detail($id)
    {
        // Mengambil data master bimbingan
        $dataMaster = MasterBimbingan::select('id', 'nim')
            ->where('id', $id)
            ->first();

        // Jika data master bimbingan tidak ditemukan, kembalikan view dengan data null
        if (!$dataMaster) {
            return $this->renderView();
        }

        $namaMahasiswa = Mahasiswa::where('nim', $dataMaster->nim)->select('nama')->first();
        $judul = JudulSkripsi::where('nim', $dataMaster->nim)->first();
        // Ambil semua data bimbingan sekaligus, termasuk data logbook terkait
        $dataBimbingan = BimbinganSkripsi::join('logbook_bimbingan_skripsi', function ($join) {
            $join->on('logbook_bimbingan_skripsi.id_bimbingan', '=', 'bimbingan_skripsi_mahasiswa.id')
                ->whereRaw('logbook_bimbingan_skripsi.created_at = (
                    SELECT MAX(l.created_at)
                    FROM logbook_bimbingan_skripsi l
                    WHERE l.id_bimbingan = logbook_bimbingan_skripsi.id_bimbingan
                )');
        })
        ->select(
            'bimbingan_skripsi_mahasiswa.id AS id_bimbingan',
            'bimbingan_skripsi_mahasiswa.file',
            'bimbingan_skripsi_mahasiswa.kategori',
            'logbook_bimbingan_skripsi.status',
            'logbook_bimbingan_skripsi.keterangan',
            'logbook_bimbingan_skripsi.id AS id_logbook',
            'bimbingan_skripsi_mahasiswa.created_at'
        )
        ->where('bimbingan_skripsi_mahasiswa.id_master_bimbingan', $dataMaster->id)
        ->orderBy('bimbingan_skripsi_mahasiswa.created_at', 'desc')
        ->get();
    
    

        // Ambil tahap bimbingan terakhir langsung dengan query tunggal
        $TahapBimbingan = BimbinganSkripsi::where('id_master_bimbingan', $dataMaster->id)
            ->latest()
            ->first();
        $idBimbingan = $dataBimbingan->pluck('id_bimbingan');
        // Ambil semua logbook bimbingan berdasarkan daftar ID bimbingan
        $logbookBimbingan = LogbookBimbingan::whereIn(
            'id_bimbingan', $idBimbingan
            // Ambil semua ID bimbingan langsung dari $dataBimbingan
        )
            ->orderBy('created_at', 'desc')
            ->get();

        // Render view dengan data
        return $this->renderView($dataMaster, $namaMahasiswa, $dataBimbingan, $TahapBimbingan, $judul, $logbookBimbingan);
    }
    private function renderView(
        $dataMaster = null,
        $namaMahasiswa = null,
        $dataBimbingan = null,
        $TahapBimbingan = null,
        $judul = null,
        $logbookBimbingan = null
    ) {
        return view('dosen.skripsi.bimbingan.detail', compact(
            'dataMaster',
            'namaMahasiswa',
            'dataBimbingan',
            'TahapBimbingan',
            'judul',
            'logbookBimbingan'
        ));
    }

    public function getModalLogbook($id)
    {
        $data = LogbookBimbingan::where('id', $id)
            ->select('keterangan', 'kategori', 'komentar', 'kategori_pembimbing', 'file_pembimbing', 'file_mhs', 'created_at')
            ->first();

        if ($data) {
            $data->formatted_created_at = \Carbon\Carbon::parse($data->created_at)->format('Y-m-d');
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
    }


    public function upload(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'keterangan' => 'nullable|string',
            'status' => 'nullable|integer',
            'file' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ], [
            'id.required' => 'ID Kosong.',
            'file.mimes' => 'File harus berupa PDF atau Word.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);
    
        // Ambil data user dan NPP dosen
        $user = Auth::user();
        $dosen = PegawaiBiodatum::where('nama_lengkap', $user->name)->select('npp')->first();
        $npp = $dosen->npp;
    
        // Ambil data inputan
        $id = $request->input('id');
        $keterangan = $request->input('keterangan');
        $status = $request->input('status');
        $fileDsn = $request->file('file');
        // Cari data logbook bimbingan berdasarkan ID
        $data = LogbookBimbingan::where('id', $id)->first();
    
        $kategori_pembimbing = null;
        $masterBimbingan = MasterBimbingan::where(function ($query) use ($npp) {
            $query->where('nip_pembimbing_1', $npp)
                  ->orWhere('nip_pembimbing_2', $npp);
        })->first();
    
        if ($masterBimbingan) {
            $kategori_pembimbing = ($masterBimbingan->nip_pembimbing_1 === $npp) ? 1 : 2;
        }
    
        // Proses upload file
        $filePath = $fileDsn ? $fileDsn->store('uploads/files', 'public') : null;
    
        // Simpan data ke tabel logbook bimbingan
        LogbookBimbingan::create([
            'id_bimbingan' => $data->id_bimbingan,
            'keterangan' => $data->keterangan,
            'file_mhs' => $data->file_mhs,
            'kategori' => $data->kategori,
            'tgl_pengajuan' => $data->tgl_pengajuan,
            'komentar' => $keterangan,
            'file_pembimbing' => $filePath,
            'kategori_pembimbing' => $kategori_pembimbing,
            'status' => $status,
        ]);
    
        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'File berhasil diunggah!',
        ]);
    }
    

    public function edit()
    {

    }
    public function destroy()
    {

    }
}
