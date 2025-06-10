<?php

namespace App\Http\Controllers\admin\skripsi;

use App\Models\KoordinatorSkripsi;
use App\Models\PembimbingSkripsi;
use App\Models\Prodi;
use App\Models\RefJumlahSksSkripsi;
use App\Models\Rumpun;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\JudulSkripsi;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use App\Models\RefPembimbing;
use App\Models\MasterBimbingan;
use App\Models\PegawaiBiodatum;
use App\Models\BimbinganSkripsi;
use App\Models\LogbookBimbingan;
use App\Models\MasterPembimbing;
use App\Models\KelompokMataKuliah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Log;

class ManajemenSkripsiController extends Controller
{
    private $title;

    public function __construct()
    {
        $this->title = 'Manajemen Skripsi';
    }
    public function index()
    {
        $mk = MataKuliah::select('mata_kuliahs.*')->leftJoin('kelompok_mata_kuliahs', 'kelompok_mata_kuliahs.id', '=', 'mata_kuliahs.kel_mk')
            ->leftJoin('rumpuns', 'rumpuns.id', '=', 'mata_kuliahs.rumpun')->get();
        $kelompok = KelompokMatakuliah::get();
        $pembimbing = RefPembimbing::select('pegawai.nama_lengkap AS nama','ref_pembimbing_skripsi.nip AS nip','ref_pembimbing_skripsi.kuota AS kuota')->join('pegawai_biodata as pegawai','pegawai.npp' , 'ref_pembimbing_skripsi.nip')->get();
        // dd($pembimbing);
        $rumpun = Rumpun::get();
        $no = 1;
        return view('admin.skripsi.manajemen.pembimbing.index',[
            'title' =>$this->title, 
            'mk' =>$mk,
             'kelompok' =>$kelompok,
              'rumpun'=>$rumpun,
               'no'=>$no,
               'pembimbing'=>$pembimbing
            ]);
    }

    public function index_sidang(){
        $pembimbing = RefPembimbing::select('pegawai.nama_lengkap AS nama','ref_pembimbing_skripsi.nip AS nip','ref_pembimbing_skripsi.kuota AS kuota')->join('pegawai_biodata as pegawai','pegawai.npp' , 'ref_pembimbing_skripsi.nip')->get();
        return view('admin.skripsi.manajemen.sidang.index',[
            'title' =>$this->title, 
               'pembimbing'=>$pembimbing
            ]);
    }

    public function index_bimbingan(){
        $pembimbing = RefPembimbing::select('pegawai.nama_lengkap AS nama','ref_pembimbing_skripsi.nip AS nip','ref_pembimbing_skripsi.kuota AS kuota')->join('pegawai_biodata as pegawai','pegawai.npp' , 'ref_pembimbing_skripsi.nip')->get();
        return view('admin.skripsi.manajemen.bimbingan.index',[
            'title' =>$this->title, 
               'pembimbing'=>$pembimbing
            ]);

    }

    public function index_daftar(Request $request)
{
    $title = "Daftar Mahasiswa";
    $mhs = Skripsi::where('status',1)->count();
    $pembimbing = RefPembimbing::where('kuota','!=',0)->count();
    $judulSkripsi = JudulSkripsi::join('master_bimbingan_skripsi AS master','master.nim','judul_skripsi.nim')->where('master.status',1)->count();
    $prodi = Prodi::all();  
    foreach($prodi as $prod){
        $prod->sks = RefJumlahSksSkripsi::where('id_progdi', $prod->id)->value('jumlah_sks') ?? 0;
    }
    
    
    
    return view('admin.skripsi.manajemen.daftar_skripsi.index', compact('title', 'prodi', 'mhs','pembimbing','judulSkripsi'));
}

public function detail($id){
    $title = "Daftar Mahasiswa";
    
    $prodi = Prodi::all();
    $sks = RefJumlahSksSkripsi::where('id_progdi',$id)->value('jumlah_sks');
    $nipKoordinator = KoordinatorSkripsi::where('id_progdi', $id)->pluck('nip');
    $koordinator = PegawaiBiodatum::whereIn('npp', $nipKoordinator)->select('id','npp','nama_lengkap')->get();
    return view('admin.skripsi.manajemen.daftar_skripsi.detail', compact('id','title', 'prodi','sks','koordinator'));
}
public function mahasiswa($nim)
{
    // Ambil data skripsi berdasarkan NIM
    $skripsi = \App\Models\Skripsi::where('nim', $nim)->latest()->firstOrFail();

    // Ambil data pembimbing berdasarkan peran (1 = Pembimbing 1, 2 = Pembimbing 2)
    $nipPembimbing1 = PembimbingSkripsi::where('skripsi_id', $skripsi->id)->where('peran', 1)->first();
    $nipPembimbing2 = PembimbingSkripsi::where('skripsi_id', $skripsi->id)->where('peran', 2)->first();

    // Ambil data biodata pegawai berdasarkan NPP
    $pembimbing1 = null;
    $pembimbing2 = null;

    if ($nipPembimbing1) {
        $pembimbing1 = PegawaiBiodatum::select('nama_lengkap', 'npp', 'nidn', 'email1', 'nohp', 'homebase')
            ->where('npp', $nipPembimbing1->nip)
            ->first();
    }

    if ($nipPembimbing2) {
        $pembimbing2 = PegawaiBiodatum::select('nama_lengkap', 'npp', 'nidn', 'email1', 'nohp', 'homebase')
            ->where('npp', $nipPembimbing2->nip)
            ->first();
    }

    if (!$skripsi) {
        return view('admin.skripsi.manajemen.daftar_skripsi.mahasiswa', [
            'bimbingan' => collect(),
            'message' => 'Anda belum terdaftar dalam sistem skripsi.'
        ]);
    }

    // Ambil semua data bimbingan mahasiswa dengan relasi
    $bimbingan = BimbinganSkripsi::with([
        'berkas',
        'skripsi:id,nim,judul'
    ])
    ->where('skripsi_id', $skripsi->id)
    ->orderBy('tanggal_waktu', 'desc')
    ->get();

    // Format data untuk tampilan
    $bimbingan = $bimbingan->map(function ($item) {
        // Parse waktu jika tersimpan dalam format tertentu
        if ($item->tanggal_waktu) {
            $datetime = \Carbon\Carbon::parse($item->tanggal_waktu);
            $item->tanggal_formatted = $datetime->format('d F Y');
            $item->waktu_formatted = $datetime->format('H:i');
        }
        
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

    // Kirim ke view atau response
    return view('admin.skripsi.manajemen.daftar_skripsi.mahasiswa', compact('skripsi', 'pembimbing1', 'pembimbing2','bimbingan'));
}

public function tambahKoor(Request $request)
{
    // Validasi input
    $request->validate([
        'nip' => 'required|string|max:255',
        'id_progdi' => 'required',
    ]);
    $nipFull = $request->input('nip'); // Contoh: "020399004 - Achmad Wildan, ST.,M.T"
    $nip = explode(' - ', $nipFull)[0]; // Ambil bagian sebelum " - "
  
    try {
        // Menggunakan updateOrCreate untuk menyederhanakan logika penyimpanan
        KoordinatorSkripsi::updateOrCreate(
            ['nip' => $nip],
            ['id_progdi' => $request->input('id_progdi')]
        );

        return redirect()->back()->with('success', 'Berhasil Update Koor');
    } catch (\Exception $e) {
        // Log jika terjadi kesalahan
        Log::error('Terjadi kesalahan saat Menambahkan Koordinator.', [
            'nip' => $request->input('nip'),
            'id_progdi' => $request->input('id_progdi'),
            'error' => $e->getMessage()
        ]);
        return redirect()->back()->with('error', 'Terjadi kesalahan, silakan coba lagi.');
    }
}
    
    public function ListMahasiswaByProd($id)
    {
        $data = Skripsi::join('mahasiswa', 'mahasiswa.nim', '=', 'skripsi.nim')
    ->where('skripsi.status', 1)
    // ->where('mahasiswa.id_program_studi', $id)
    ->where('mahasiswa.id_program_studi', 2)
    ->select([
        'mahasiswa.nama as nama',
        'mahasiswa.nim as nim',
        'skripsi.judul as judul'
    ])
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
        ->addIndexColumn()
        ->addColumn('button', function ($row) {
            $url = route('admin.skripsi.manajemen.mahasiswa', $row->nim); // atau $row->id jika pakai ID
            return '<a href="' . $url . '" class="btn btn-sm btn-primary">
                        <i class="icon-eye"></i>
                    </a>';
        })
        ->rawColumns(['button']) 
        ->make(true);
    
    }

    public function modifySKS(Request $request)
    {
        $request->validate([
            'jml_sks' => 'required|integer|min:0',
            'id_prodi' => 'required|exists:program_studi,id',
        ]);
    
        try {
            RefJumlahSksSkripsi::updateOrCreate(
                ['id_progdi' => $request->input('id_prodi')], 
                ['jumlah_sks' => $request->input('jml_sks')]
            );
            // Redirect back with a success message
        return redirect()->back()->with('success', 'Berhasil Update SKS');
        } catch (\Exception $e) {
            Log::error('Kesalahan saat Update data SKS.', [
                'jumlah_sks' => $request->input('jml_sks'),
                'id_progdi' => $request->input('id_progdi'),
                'error' => $e->getMessage(),
            ]);
    
        return redirect()->back()->with('error', 'Terjadi kesalahan, silakan coba lagi.');
        }
    }
    
    
    public function detailBimbingan($nip){

        $data = MasterPembimbing::where('nip', $nip)->join('mahasiswa', 'mahasiswa.nim', 'master_pembimbing_skripsi.nim')->select('mahasiswa.nama', 'mahasiswa.nim', 'master_pembimbing_skripsi.topik_judul AS judul', 'master_pembimbing_skripsi.nip AS nip')->get();

        return view('dosen.skripsi.manajemen.pembimbing.detail', [
            'data' => $data,
            // 'totalPengajuan' => $totalPengajuan
        ]);
    }
}
