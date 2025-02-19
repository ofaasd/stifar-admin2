<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiKompentensi;

class PegawaiKompetensiController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $pegawai_kompetensi = PegawaiKompentensi::where('id_pegawai',$id_pegawai)->get();


        foreach($pegawai_kompetensi as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/penghargaan/index', compact('id_pegawai','pegawai_kompetensi','fake_id'));
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
                'tanggal' => $request->tanggal,
                'link' => $request->link,
            ];

            $filename = '';
            if ($request->file('bukti') != null) {
                $bukti = $request->file('bukti');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/kompetensi';
                $bukti->move($tujuan_upload,$filename);
                $data['bukti'] = $filename;
            }
            $pegawai = PegawaiKompentensi::updateOrCreate(
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

            $pegawai = PegawaiKompentensi::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'bidang' => $request->bidang,
                    'lembaga' => $request->lembaga,
                    'tanggal' => $request->tanggal,
                    'link' => $request->link,
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

        $pegawai[0] = PegawaiKompentensi::where($where)->first();
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
        $pegawai = PegawaiKompentensi::where('id', $id)->delete();
    }
}
