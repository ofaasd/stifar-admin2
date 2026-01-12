<?php

namespace App\Http\Controllers\dosen;

use Illuminate\Http\Request;
use App\Models\PegawaiBiodatum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PegawaiBerkasPendukung;
use App\Models\TahunAjaran;
use App\Models\Prodi;

class DosenBerkasController extends Controller
{
    /**
    * menampilkan halaman dan data berkas pendukung dosen.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function index()
    {
        $cekUser = PegawaiBiodatum::where('user_id', Auth::id())->first();

        if(!$cekUser){
            return response()->json(["message"  => "Data tidak ditemukan"]);
        }

        $dosen = PegawaiBiodatum::select('pegawai_biodata.*', 'pegawai_biodata.nidn as nidnDosen', 'pegawai_biodata.id_pegawai AS idPegawai')
        ->where('pegawai_biodata.id', $cekUser->id)
        ->first();
        

        if(!$dosen){
            return response()->json(["message"  => "Data tidak ditemukan"]);
        }

        $berkas = PegawaiBerkasPendukung::where("id_pegawai", $dosen->id)->latest()->first();
        $curr_prodi = Prodi::where("id", $dosen->id_progdi)->first();

        $title = 'Berkas ' . $dosen->nama_lengkap;

        $data = [
            'dosen' => $dosen,
            'title' => $title,
            'berkas' => $berkas,
            'curr_prodi' => $curr_prodi,
        ];

        $ta = TahunAjaran::where("status", "Aktif")->first();
        if($berkas){
            if($berkas->id_ta != $ta->id){
                $data['updateHerregistrasi'] = true;
            }
        }

        return view('dosen.berkas.index', $data);
    }
    
    /**
    * menyimpan data berkas pendukung dosen.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
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

        $ta = TahunAjaran::where("status", "Aktif")->first();

        // Cek apakah request ingin update_herregistrasi
        if ($request->has('update_herregistrasi') && $request->update_herregistrasi) {

            // Ambil TA sebelumnya
            $taSebelumnya = TahunAjaran::where("created_at", '<', $ta->created_at)
                ->orderBy('created_at', 'desc')
                ->first();

            // Ambil berkas lama
            $berkasLama = PegawaiBerkasPendukung::where("id_pegawai", $request->id_pegawai)
                ->where("id_ta", $taSebelumnya?->id) // safe navigation in case $taSebelumnya null
                ->first();

            // Siapkan data baru dengan isian dari request, jika tidak ada ambil dari lama
            $dataBerkas = ['id_pegawai' => $request->id_pegawai, 'id_ta' => $ta->id];

            foreach ($fields as $fileInput => $dbField) {
                if ($request->hasFile($fileInput)) {
                    $file = $request->file($fileInput);
                    $fileName = date('YmdHi') . $request->id_pegawai . $file->getClientOriginalName();
                    $fileName = str_replace(' ', '-', $fileName);

                    $tujuan_upload = 'assets/file/berkas/dosen/' . $dbField;

                    if (!file_exists($tujuan_upload)) {
                        mkdir($tujuan_upload, 0777, true);
                    }

                    $file->move($tujuan_upload, $fileName);

                    $dataBerkas[$dbField] = $fileName;
                } else {
                    // Ambil dari berkas lama jika tidak dikirim
                    if ($berkasLama) {
                        $dataBerkas[$dbField] = $berkasLama->$dbField;
                    }
                }
            }

            // Simpan sebagai baris baru
            PegawaiBerkasPendukung::create($dataBerkas);

        } else {
            // Jika tidak herregistrasi → update data jika sudah ada
            $berkas = PegawaiBerkasPendukung::firstOrCreate(
                [
                    'id_pegawai' => $request->id_pegawai,
                    'id_ta' => $ta->id
                ],
                [
                    'id_pegawai' => $request->id_pegawai
                ]
            );

            foreach ($fields as $fileInput => $dbField) {
                if ($request->hasFile($fileInput)) {
                    $file = $request->file($fileInput);
                    $fileName = date('YmdHi') . $request->id_pegawai . $file->getClientOriginalName();
                    $fileName = str_replace(' ', '-', $fileName);

                    $tujuan_upload = 'assets/file/berkas/dosen/' . $dbField;

                    if (!file_exists($tujuan_upload)) {
                        mkdir($tujuan_upload, 0777, true);
                    }

                    $file->move($tujuan_upload, $fileName);

                    $berkas->update([$dbField => $fileName]);
                }
            }
        }

        return response()->json(['message' => 'Berhasil Menyimpan Berkas']);
    }

}
