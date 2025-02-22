<?php

namespace App\Http\Controllers\admin\skripsi;

use App\Models\KoordinatorSkripsi;
use App\Models\Prodi;
use App\Models\RefJumlahSksSkripsi;
use App\Models\Rumpun;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\JudulSkripsi;
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
    $mhs = Mahasiswa::all();
    $prodi = Prodi::all();  
  
    return view('admin.skripsi.manajemen.daftar_skripsi.index', compact('title', 'prodi', 'mhs'));
}

public function detail($id){
    $title = "Daftar Mahasiswa";
    $mhs = Mahasiswa::all();
    $prodi = Prodi::all();
    $sks = RefJumlahSksSkripsi::where('id_progdi',$id)->value('jumlah_sks');
    $nipKoordinator = KoordinatorSkripsi::where('id_progdi', $id)->pluck('nip');
    $koordinator = PegawaiBiodatum::whereIn('npp', $nipKoordinator)->select('id','npp','nama_lengkap')->get();
    return view('admin.skripsi.manajemen.daftar_skripsi.detail', compact('title', 'prodi', 'mhs','sks','koordinator'));
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

        return response()->json(['success' => 'Berhasil Menambahkan Koordinator']);
    } catch (\Exception $e) {
        // Log jika terjadi kesalahan
        Log::error('Terjadi kesalahan saat Menambahkan Koordinator.', [
            'nip' => $request->input('nip'),
            'id_progdi' => $request->input('id_progdi'),
            'error' => $e->getMessage()
        ]);

        return response()->json(['message' => 'Terjadi kesalahan, silakan coba lagi.'], 500);
    }
}
    
    public function ListMahasiswaTa()
    {

    }

    public function modifySKS(Request $request)
    {
        $request->validate([
            'jml_sks' => 'required|integer|min:0',
            'id_progdi' => 'required|exists:program_studi,id',
        ]);
    
        try {
            RefJumlahSksSkripsi::updateOrCreate(
                ['id_progdi' => $request->input('id_progdi')], 
                ['jumlah_sks' => $request->input('jml_sks')]
            );
            // Redirect back with a success message
        return response()->json(['success' => 'Berhasil Update Jumlah SKS']);
        } catch (\Exception $e) {
            Log::error('Kesalahan saat Update data SKS.', [
                'jumlah_sks' => $request->input('jml_sks'),
                'id_progdi' => $request->input('id_progdi'),
                'error' => $e->getMessage(),
            ]);
    
        return response()->json(['message' => 'Terjadi kesalahan, silakan coba lagi.'], 500);
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
