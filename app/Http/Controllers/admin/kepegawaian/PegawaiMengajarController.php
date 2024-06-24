<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiMengajar;

class PegawaiMengajarController extends Controller
{
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $pegawai_mengajar = PegawaiMengajar::where('id_pegawai',$id_pegawai)->get();


        foreach($pegawai_mengajar as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/mengajar/index', compact('id_pegawai','pegawai_mengajar','fake_id'));
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
                'tahun' => $request->tahun,
                'institusi' => $request->institusi,
                'prodi' => $request->prodi,
                'mata_kuliah' => $request->mata_kuliah,
                'kelas' => $request->kelas,
                'sks' => $request->sks,
            ];

            $filename = '';
            if ($request->file('dokumen') != null) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/mengajar';
                $dokumen->move($tujuan_upload,$filename);
                $data['dokumen'] = $filename;
            }
            $filename2 = '';
            if ($request->file('sk_mengajar') != null) {
                $sk_mengajar = $request->file('sk_mengajar');
                $filename2 = date('YmdHi') . $sk_mengajar->getClientOriginalName();
                $tujuan_upload = 'assets/file/mengajar';
                $sk_mengajar->move($tujuan_upload,$filename2);
                $data['sk_mengajar'] = $filename2;
            }
            $pegawai = PegawaiMengajar::updateOrCreate(
                ['id' => $id],
                $data,
            );


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('dokumen')) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/mengajar';
                $dokumen->move($tujuan_upload,$filename);
            }

            $filename2 = '';
            if ($request->file('sk_mengajar') != null) {
                $sk_mengajar = $request->file('sk_mengajar');
                $filename2 = date('YmdHi') . $sk_mengajar->getClientOriginalName();
                $tujuan_upload = 'assets/file/mengajar';
                $sk_mengajar->move($tujuan_upload,$filename2);
            }

            $pegawai = PegawaiMengajar::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'tahun' => $request->tahun,
                    'institusi' => $request->institusi,
                    'prodi' => $request->prodi,
                    'mata_kuliah' => $request->mata_kuliah,
                    'kelas' => $request->kelas,
                    'sks' => $request->sks,
                    'dokumen' => $filename,
                    'sk_mengajar' => $filename2
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

        $pegawai[0] = PegawaiMengajar::where($where)->first();
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
        $pegawai = PegawaiMengajar::where('id', $id)->delete();
    }
}
