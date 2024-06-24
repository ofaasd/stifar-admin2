<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JabatanFungsional;
use App\Models\PegawaiJabatanFungsional;

class PegawaiJabatanFungsionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $pegawai_jabatan_fungsional = PegawaiJabatanFungsional::where('id_pegawai',$id_pegawai)->get();

        $jabatan_fungsional = JabatanFungsional::all();
        $list_jabatan_fungsional = [];
        foreach($jabatan_fungsional as $row){
            $list_jabatan_fungsional[$row->id] = $row->jabatan;
        }
        $data = [];
        foreach($pegawai_jabatan_fungsional as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/fungsional/index', compact('id_pegawai','pegawai_jabatan_fungsional','list_jabatan_fungsional','fake_id'));
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
            $filename = '';
            if ($request->file('dokumen') != null) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/fungsional';
                $dokumen->move($tujuan_upload,$filename);
            }
            if(!empty($filename)){
                $jabatan = PegawaiJabatanFungsional::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_pegawai' => $request->id_pegawai,
                        'jabatan_fungsional_sekarang' => $request->jabatan_fungsional,
                        'no_sk_fungsional' => $request->no_sk,
                        'tgl_sk_fungsional' => $request->tanggal_sk,
                        'tmt_sk_fungsional' => $request->tmt_sk,
                        'kum' => $request->kum,
                        'status' => $request->status,
                        'dokumen' => $filename
                    ]
                );
            }else{
                $jabatan = PegawaiJabatanFungsional::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_pegawai' => $request->id_pegawai,
                        'jabatan_fungsional_sekarang' => $request->jabatan_fungsional,
                        'no_sk_fungsional' => $request->no_sk,
                        'tgl_sk_fungsional' => $request->tanggal_sk,
                        'tmt_sk_fungsional' => $request->tmt_sk,
                        'kum' => $request->kum,
                        'status' => $request->status,
                    ]
                );
            }


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('dokumen')) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/fungsional';
                $dokumen->move($tujuan_upload,$filename);
            }
            $jabatan = PegawaiJabatanFungsional::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                   'jabatan_fungsional_sekarang' => $request->jabatan_fungsional,
                    'no_sk_fungsional' => $request->no_sk,
                    'tgl_sk_fungsional' => $request->tanggal_sk,
                    'tmt_sk_fungsional' => $request->tmt_sk,
                    'kum' => $request->kum,
                    'status' => $request->status,
                    'dokumen' => $filename
                ]
            );

            if ($jabatan) {
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

        $jabatan[0] = PegawaiJabatanFungsional::where($where)->first();
        $jabatan['tanggal_sk'] = date('Y-m-d',strtotime($jabatan[0]->tgl_sk_fungsional));
        $jabatan['tmt_sk'] = date('Y-m-d',strtotime($jabatan[0]->tmt_sk_fungsional));
        return response()->json($jabatan);
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
        $jaatan = PegawaiJabatanFungsional::where('id', $id)->delete();
    }
}
