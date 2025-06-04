<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiBerkasPendukung;

class PegawaiBerkasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $berkas_pendukung = PegawaiBerkasPendukung::where('id_pegawai',$id_pegawai);
        $jumlah = $berkas_pendukung->count();
        $data = [];
        $berkas = $berkas_pendukung->get();
        foreach($berkas as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;

        return view('admin/kepegawaian/pegawai/berkas/index', compact('id_pegawai','berkas','jumlah','fake_id'));
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
            ];
            $filename = '';
            if ($request->file('ktp') != null) {
                $dokumen = $request->file('ktp');
                $filename = "ktp" . date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/berkas/ktp';
                $dokumen->move($tujuan_upload,$filename);
                $data['ktp'] = $filename;
            }
            $filename2 = '';
            if ($request->file('kk') != null) {
                $dokumen = $request->file('kk');
                $filename2 = "kk" . date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/berkas/kk';
                $dokumen->move($tujuan_upload,$filename2);
                $data['kk'] = $filename2;
            }

            $pegawai = PegawaiBerkasPendukung::updateOrCreate(
                ['id' => $id],
                $data,
            );


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('ktp') != null) {
                $dokumen = $request->file('ktp');
                $filename = "ktp" . date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/berkas/ktp';
                $dokumen->move($tujuan_upload,$filename);

            }
            $filename2 = '';
            if ($request->file('kk') != null) {
                $dokumen = $request->file('kk');
                $filename2 = "kk" . date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/berkas/kk';
                $dokumen->move($tujuan_upload,$filename2);
            }
            $pegawai = PegawaiBerkasPendukung::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'ktp' => $filename,
                    'kk' => $filename2
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

        $pegawai[0] = PegawaiBerkasPendukung::where($where)->first();
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
        $pegawai = PegawaiPenelitian::where('id', $id)->delete();
    }
}
