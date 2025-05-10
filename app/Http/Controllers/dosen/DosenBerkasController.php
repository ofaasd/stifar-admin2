<?php

namespace App\Http\Controllers\dosen;

use Illuminate\Http\Request;
use App\Models\PegawaiBiodatum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PegawaiBerkasPendukung;

class DosenBerkasController extends Controller
{
    public function index()
    {
        $dosen = PegawaiBiodatum::where('user_id', Auth::id())->first();
        

        $dsn = PegawaiBiodatum::select('pegawai_biodata.*', 'pegawai_biodata.nidn as nidnDosen', 'pegawai_biodata.id_pegawai AS idPegawai', 'pegawai_berkas_pendukung.*')
        ->leftJoin('pegawai_berkas_pendukung', 'pegawai_berkas_pendukung.id_pegawai', '=', 'pegawai_biodata.id_pegawai')
        ->where('pegawai_biodata.id_pegawai', $dosen->id_pegawai)
        ->first();

        $title = 'Berkas ' . $dsn->nama_lengkap;

        $data = [
            'dosen' => $dsn,
            'title' => $title,
        ];

        return view('dosen.berkas.index', $data);
    }
    
    public function store(Request $request)
    {
        $fields = [
            'ktp' => 'ktp',
            'kk' => 'kk',
            'ijazah_s1' => 'ijazah_s1',
            'ijazah_s2' => 'ijazah_s2',
            'ijazah_s3' => 'ijazah_s3',
            'serdik_aa_pekerti' => 'serdik_aa_pekerti',
            'serdik_aa' => 'serdik_aa',
            'serdik_lektor' => 'serdik_lektor',
            'serdik_kepala_guru_besar' => 'serdik_kepala_guru_besar',
        ];

        $validatedData = $request->validate(array_fill_keys(array_keys($fields), 'mimes:jpg,jpeg|max:5012'));

        $berkas = PegawaiBerkasPendukung::firstOrCreate(
            ['id_pegawai' => $request->id_pegawai],
            ['id_pegawai' => $request->id_pegawai]
        );

        $tujuan_upload = 'assets/file/berkas/dosen';

        foreach ($fields as $fileInput => $dbField) {
            if ($request->hasFile($fileInput)) {
                $file = $request->file($fileInput);
                $fileName = date('YmdHi'). $request->id_pegawai . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $fileName);
                
                switch ($dbField) {
                    case "ktp":
                        $tujuan_upload .= "/ktp";
                        break;
                    case "kk":
                        $tujuan_upload .= "/kk";
                        break;
                    case "ijazah_s1":
                        $tujuan_upload .= "/ijazah_s1";
                        break;
                    case "ijazah_s2":
                        $tujuan_upload .= "/ijazah_s2";
                        break;
                    case "ijazah_s3":
                        $tujuan_upload .= "/ijazah_s3";
                        break;
                    case "serdik_aa_pekerti":
                        $tujuan_upload .= "/serdik_aa_pekerti";
                        break;
                    case "serdik_aa":
                        $tujuan_upload .= "/serdik_aa";
                        break;
                    case "serdik_lektor":
                        $tujuan_upload .= "/serdik_lektor";
                        break;
                    case "serdik_kepala_guru_besar":
                        $tujuan_upload .= "/serdik_kepala_guru_besar";
                        break;
                }

                // Pastikan folder ada
                if (!file_exists($tujuan_upload)) {
                    mkdir($tujuan_upload, 0777, true);
                }

                $file->move($tujuan_upload, $fileName);

                $berkas->update([$dbField => $fileName]);
            }
        }

        return response()->json(['message' => 'Berhasil Menyimpan Berkas']);
    }
}
