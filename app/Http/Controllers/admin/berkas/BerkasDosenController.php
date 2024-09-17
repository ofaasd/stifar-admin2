<?php

namespace App\Http\Controllers\admin\berkas;

use App\Models\Prodi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\PegawaiBiodatum;
use App\Http\Controllers\Controller;
use App\Models\PegawaiBerkasPendukung;

class BerkasDosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Berkas Dosen";
        $pegawai = PegawaiBiodatum::select('pegawai_biodata.*', 'pegawai_biodata.nidn as nidnDosen', 'pegawai_berkas_pendukung.*')
        ->leftJoin('pegawai_berkas_pendukung', 'pegawai_berkas_pendukung.nidn', '=', 'pegawai_biodata.nidn')
        ->whereIn('id_posisi_pegawai', [1,2])
        ->get();
        $fake_id = 0;
        return view('admin.berkas.dosen.index', compact('title','pegawai','fake_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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

        $berkas = PegawaiBerkasPendukung::firstOrCreate(
            ['nidn' => $request->nidn],
            ['nidn' => $request->nidn]
        );

        $tujuan_upload = 'assets/images/dosen/berkas';

        foreach ($fields as $fileInput => $dbField) {
            if ($request->hasFile($fileInput)) {
                $file = $request->file($fileInput);
                $fileName = date('YmdHi') . $file->getClientOriginalName();
                $file->move($tujuan_upload, $fileName);

                $berkas->update([$dbField => $fileName]);
            }
        }

        return response()->json(['message' => 'Berhasil Menyimpan Berkas']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nidn)
    {
        $dsn = PegawaiBiodatum::select('pegawai_biodata.*', 'pegawai_biodata.nidn as nidnDosen', 'pegawai_berkas_pendukung.*')
        ->leftJoin('pegawai_berkas_pendukung', 'pegawai_berkas_pendukung.nidn', '=', 'pegawai_biodata.nidn')
        ->where('pegawai_biodata.nidn', $nidn)
        ->first();

        $title = 'Berkas ' . $dsn->nama_lengkap;

        $data = [
            'dosen' => $dsn,
            'title' => $title,
        ];

        return view('admin.berkas.dosen.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
