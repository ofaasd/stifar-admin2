<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiPendidikan;

class PegawaiPendidikanController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $pegawai_pendidikan = PegawaiPendidikan::where('id_pegawai',$id_pegawai)->get();


        foreach($pegawai_pendidikan as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/pendidikan/index', compact('id_pegawai','pegawai_pendidikan','fake_id'));
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
        //
        $id = $request->id;

        if ($id) {
            $data = [
                'id_pegawai' => $request->id_pegawai,
                'jenjang' => $request->jenjang,
                'jenjang_profesi' => $request->jenjang_profesi,
                'universitas' => $request->universitas,
                'jurusan' => $request->jurusan,
                'tempat' => $request->tempat,
                'no_ijazah' => $request->no_ijazah,
                'tanggal_ijazah' => $request->tanggal_ijazah,
                'tahun' => $request->tahun,
            ];

            $filename = '';
            if ($request->file('dokumen') != null) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/pendidikan';
                $dokumen->move($tujuan_upload,$filename);
                $data['dokumen'] = $filename;
            }
            $pegawai = PegawaiPendidikan::updateOrCreate(
                ['id' => $id],
                $data,
            );


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('dokumen')) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/pendidikan';
                $dokumen->move($tujuan_upload,$filename);
            }

            $pegawai = PegawaiPendidikan::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'jenjang' => $request->jenjang,
                    'jenjang_profesi' => $request->jenjang_profesi,
                    'universitas' => $request->universitas,
                    'jurusan' => $request->jurusan,
                    'tempat' => $request->tempat,
                    'no_ijazah' => $request->no_ijazah,
                    'tanggal_ijazah' => $request->tanggal_ijazah,
                    'tahun' => $request->tahun,
                    'dokumen' => $filename,
                ]
            );

            if ($pegawai) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create PT');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $where = ['id' => $id];

        $pegawai[0] = PegawaiPendidikan::where($where)->first();
        return response()->json($pegawai);
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
        $pegawai = PegawaiPendidikan::where('id', $id)->delete();
    }
}
