<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiUnitKerja;
use App\Models\PegawaiJabatanStruktural;
use App\Models\JabatanStruktural;
use App\Models\PegawaiBagian;
use App\Models\Prodi;

class PegawaiJabatanStrukturalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $unit_kerja = PegawaiUnitKerja::all();
        $pegawai_jabatan_stuktural = PegawaiJabatanStruktural::where('id_pegawai',$id_pegawai)->get();
        $list_unit = [];
        foreach($unit_kerja as $row){
            $list_unit[$row->id] = $row->unit_kerja;
        }

        $prodi = Prodi::all();
        $list_prodi = [];
        $list_prodi[0] = "Tidak Ada";
        foreach($prodi as $row){
            $list_prodi[$row->id] = $row->nama_jurusan;
        }

        $jabatan_struktural = JabatanStruktural::all();
        $list_jabatan_struktural = [];
        foreach($jabatan_struktural as $row){
            if($row->unit_kerja_id == 1){
                $list_jabatan_struktural[$row->id] = $row->jabatan . " " . $row->bagian;
            }else{
                $list_jabatan_struktural[$row->id] = $row->jabatan . " " . $row->bagian;
            }
        }
        $data = [];
        foreach($pegawai_jabatan_stuktural as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/struktural/index', compact('id_pegawai','pegawai_jabatan_stuktural','list_unit','list_jabatan_struktural','fake_id'));
    }

    public function get_jabatan(Request $request){
        $unit_kerja = $request->id;
        $jabatan_struktural = JabatanStruktural::where('unit_kerja_id',$unit_kerja)->get();
        $list_jabatan_struktural = [];
        foreach($jabatan_struktural as $row){
            if($row->unit_kerja_id == 1){
                $list_jabatan_struktural[$row->id] = $row->jabatan . " " . $row->bagian;
            }else{
                $list_jabatan_struktural[$row->id] = $row->jabatan . " " . $row->bagian;
            }
        }

        return response()->json($list_jabatan_struktural);
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
                $tujuan_upload = 'assets/file/struktural';
                $dokumen->move($tujuan_upload,$filename);
            }
            if(!empty($filename)){
                $jabatan = PegawaiJabatanStruktural::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_pegawai' => $request->id_pegawai,
                        'unit_kerja' => $request->unit_kerja,
                        'id_jabatan_struktural' => $request->jabatan_struktural,
                        'no_sk_struktural' => $request->no_sk,
                        'tanggal_sk_struktural' => $request->tanggal_sk,
                        'tmt_sk_struktural' => $request->tmt_sk,
                        'status' => $request->status,
                        'dokumen' => $filename
                    ]
                );
            }else{
                $jabatan = PegawaiJabatanStruktural::updateOrCreate(
                    ['id' => $id],
                    [
                        'id_pegawai' => $request->id_pegawai,
                        'unit_kerja' => $request->unit_kerja,
                        'id_jabatan_struktural' => $request->jabatan_struktural,
                        'no_sk_struktural' => $request->no_sk,
                        'tanggal_sk_struktural' => $request->tanggal_sk,
                        'tmt_sk_struktural' => $request->tmt_sk,
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
                $tujuan_upload = 'assets/file/struktural';
                $dokumen->move($tujuan_upload,$filename);
            }
            $jabatan = PegawaiJabatanStruktural::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'unit_kerja' => $request->unit_kerja,
                    'id_jabatan_struktural' => $request->jabatan_struktural,
                    'no_sk_struktural' => $request->no_sk,
                    'tanggal_sk_struktural' => $request->tanggal_sk,
                    'tmt_sk_struktural' => $request->tmt_sk,
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

        $jabatan[0] = PegawaiJabatanStruktural::where($where)->first();
        $jabatan['tanggal_sk'] = date('Y-m-d',strtotime($jabatan[0]->tanggal_sk_struktural));
        $jabatan['tmt_sk'] = date('Y-m-d',strtotime($jabatan[0]->tmt_sk_struktural));
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
        $jaatan = PegawaiJabatanStruktural::where('id', $id)->delete();
    }
}
