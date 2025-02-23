<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiKompetensi;

class PegawaiKompetensiController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $pegawai_kompetensi = PegawaiKompetensi::where('id_pegawai',$id_pegawai)->get();


        foreach($pegawai_kompetensi as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/kompetensi/index', compact('id_pegawai','pegawai_kompetensi','fake_id'));
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
                'bidang' => $request->bidang,
                'lembaga' => $request->lembaga,
                'link' => $request->link,
                'tanggal' => $request->tanggal,
            ];

            $filename = '';
            if ($request->file('bukti') != null) {
                $dokumen = $request->file('bukti');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/kompetensi';
                $dokumen->move($tujuan_upload,$filename);
                $data['bukti'] = $filename;
            }
            $pegawai = PegawaiKompetensi::updateOrCreate(
                ['id' => $id],
                $data,
            );


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('bukti') != null) {
                $dokumen = $request->file('bukti');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/kompetensi';
                $dokumen->move($tujuan_upload,$filename);
                //$data['file'] = $filename;
            }

            $pegawai = PegawaiKompetensi::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'bidang' => $request->bidang,
                    'lembaga' => $request->lembaga,
                    'link' => $request->link,
                    'tanggal' => $request->tanggal,
                    'bukti' => $filename
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

        $pegawai[0] = PegawaiKompetensi::where($where)->first();
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
        $pegawai = PegawaiKompetensi::where('id', $id)->delete();
    }
}
