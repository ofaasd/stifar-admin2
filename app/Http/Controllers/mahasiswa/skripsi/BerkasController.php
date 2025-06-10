<?php

namespace App\Http\Controllers\mahasiswa\skripsi;

use App\Models\BerkasPendukungSkripsi;
use App\Models\RefKategoriBerkasSkripsi;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Models\MasterBimbingan;
use App\Models\BimbinganSkripsi;
use App\Models\LogbookBimbingan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BerkasController extends Controller
{
    public function index(){
        $id = Auth::user()->id;
        $nim = Mahasiswa::where('id',$id)->value('nim');
        $data = RefKategoriBerkasSkripsi::leftJoin('berkas_pendukung_skripsi', function ($join) use ($nim) {
            $join->on('berkas_pendukung_skripsi.kategori_id', '=', 'ref_kategori_berkas_skripsi.id')
                 ->where('berkas_pendukung_skripsi.nim', '=', $nim); 
        })
        ->select(
            'ref_kategori_berkas_skripsi.id AS id_kategori',
            'ref_kategori_berkas_skripsi.nama',
            'berkas_pendukung_skripsi.id AS id_berkas',
            'berkas_pendukung_skripsi.nim',
            'berkas_pendukung_skripsi.file',
            'berkas_pendukung_skripsi.kategori_id'
        )
        ->get();
    
        // dd($data);

        return view('mahasiswa.skripsi.berkas.index',compact('data'));
    }

    public function BerkasLogbook(){
        $id = Auth::user()->id;
        $mhs = Mahasiswa::select('mahasiswa.nama', 'mahasiswa.nim', 'pegawai_biodata.nama_lengkap as dsn_wali', 'program_studi.nama_prodi')
                          ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
                          ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
                          ->where('mahasiswa.id', $id)->first();
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $thn_awal = explode('-', $tahun_ajaran->tgl_awal);
        $thn_akhir = explode('-', $tahun_ajaran->tgl_akhir);
        $tahun_ajar = $thn_awal[0].'-'.$thn_akhir[0];
        $semester = ['', 'Ganjil', 'Ganjil', 'Antara'];
        $smt = substr($tahun_ajaran->kode_ta, 4);

        $idBimbingan = BimbinganSkripsi::join('master_bimbingan_skripsi', 'master_bimbingan_skripsi.id', '=', 'bimbingan_skripsi_mahasiswa.id_master_bimbingan')
        ->where('master_bimbingan_skripsi.nim', $mhs->nim)
        ->pluck('bimbingan_skripsi_mahasiswa.id');
        $dosbim = MasterBimbingan::where('master_bimbingan_skripsi.nim', $mhs->nim)
        ->join('pegawai_biodata as pembimbing1', 'pembimbing1.npp', '=', 'master_bimbingan_skripsi.nip_pembimbing_1')
        ->join('pegawai_biodata as pembimbing2', 'pembimbing2.npp', '=', 'master_bimbingan_skripsi.nip_pembimbing_2')
        ->select(
            'master_bimbingan_skripsi.nip_pembimbing_1',
            'pembimbing1.nama_lengkap as nama_pembimbing_1',
            'master_bimbingan_skripsi.nip_pembimbing_2',
            'pembimbing2.nama_lengkap as nama_pembimbing_2'
        )
        ->first();
        $LogbookBimbingan = LogbookBimbingan::whereIn('id_bimbingan', $idBimbingan)->get();
        $filename = $mhs->nim.'-krs.pdf';
        $data = [
            'mhs' => $mhs,
            'logbookBimbingan' => $LogbookBimbingan,
            'dosbim' => $dosbim,
            'no' => 1,
            'tahun_ajar' => $tahun_ajar,
            'smt' => $smt,
            'semester' => $semester,
            'logo' => public_path('/assets/images/logo/logo-icon.png')
        ];

        // print_r($data);
        // return view('mahasiswa.skripsi.berkas.template_logbook', compact('data', 'data'));
        $pdf = PDF::loadView('mahasiswa.skripsi.berkas.template_logbook', $data);
        return $pdf->download($filename);
    }
    public function BerkasBimbingan(){
        $id = Auth::user()->id;
        $mhs = Mahasiswa::select('mahasiswa.nama', 'mahasiswa.nim', 'pegawai_biodata.nama_lengkap as dsn_wali', 'program_studi.nama_prodi')
                          ->leftJoin('pegawai_biodata', 'pegawai_biodata.id', '=', 'mahasiswa.id_dsn_wali')
                          ->leftJoin('program_studi', 'program_studi.id', '=', 'mahasiswa.id_program_studi')
                          ->where('mahasiswa.id', $id)->first();
        $tahun_ajaran = TahunAjaran::where('status','Aktif')->first();
        $ta = $tahun_ajaran->id;
        $thn_awal = explode('-', $tahun_ajaran->tgl_awal);
        $thn_akhir = explode('-', $tahun_ajaran->tgl_akhir);
        $tahun_ajar = $thn_awal[0].'-'.$thn_akhir[0];
        $semester = ['', 'Ganjil', 'Ganjil', 'Antara'];
        $smt = substr($tahun_ajaran->kode_ta, 4);

        $idBimbingan = BimbinganSkripsi::join('master_bimbingan_skripsi', 'master_bimbingan_skripsi.id', '=', 'bimbingan_skripsi_mahasiswa.id_master_bimbingan')
        ->where('master_bimbingan_skripsi.nim', $mhs->nim)
        ->pluck('bimbingan_skripsi_mahasiswa.id');
        $dosbim = MasterBimbingan::where('master_bimbingan_skripsi.nim', $mhs->nim)
        ->join('pegawai_biodata as pembimbing1', 'pembimbing1.npp', '=', 'master_bimbingan_skripsi.nip_pembimbing_1')
        ->join('pegawai_biodata as pembimbing2', 'pembimbing2.npp', '=', 'master_bimbingan_skripsi.nip_pembimbing_2')
        ->select(
            'master_bimbingan_skripsi.nip_pembimbing_1',
            'pembimbing1.nama_lengkap as nama_pembimbing_1',
            'master_bimbingan_skripsi.nip_pembimbing_2',
            'pembimbing2.nama_lengkap as nama_pembimbing_2'
        )
        ->first();
        $DataBimbingan = BimbinganSkripsi::select('bimbingan_skripsi_mahasiswa.*', 
        \DB::raw('(select keterangan from logbook_bimbingan_skripsi 
                   where id = bimbingan_skripsi_mahasiswa.id 
                   order by created_at desc limit 1) as keterangan')
    )
    ->whereIn('id', $idBimbingan)
    ->get();
        $filename = $mhs->nim.'-krs.pdf';
        $data = [
            'mhs' => $mhs,
            'DataBimbingan' => $DataBimbingan,
            'dosbim' => $dosbim,
            'no' => 1,
            'tahun_ajar' => $tahun_ajar,
            'smt' => $smt,
            'semester' => $semester,
            'logo' => public_path('/assets/images/logo/logo-icon.png')
        ];

        // print_r($data);
        // return view('mahasiswa.skripsi.berkas.template_logbook', compact('data', 'data'));
        $pdf = PDF::loadView('mahasiswa.skripsi.berkas.template_bimbingan', $data);
        return $pdf->download($filename);
    }


    public function UploadBerkas(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $nim = Mahasiswa::where('id', $id)->value('nim');
    
            $request->validate([
                'id_kategori' => 'required|integer',
                'file' => 'nullable|mimes:pdf,doc,docx|max:2048',
            ], [
                'id_kategori.required' => 'ID kategori harus diisi.',
                'file.mimes' => 'File harus berupa PDF atau Word.',
                'file.max' => 'Ukuran file maksimal 2MB.',
            ]);
    
            $fileName = $request->current_file;
    
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('berkas_skripsi', $fileName, 'public');
    
                // Hapus file lama jika ada
                if ($request->current_file) {
                    Storage::disk('public')->delete('berkas_skripsi/' . $request->current_file);
                }
            }
    
            $bimbingan = BerkasPendukungSkripsi::updateOrCreate(
                ['kategori_id' => $request->id_kategori, 'nim' => $nim],
                ['file' => $fileName]
            );
    
            return redirect()->back()->with('message', 'Berkas berhasil diupload')->with('status', 'success');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan: ' . $e->getMessage())->with('status', 'error');
        }
    }
    
}
