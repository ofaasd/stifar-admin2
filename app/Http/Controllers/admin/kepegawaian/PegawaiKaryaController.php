<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaikaryaIlmiah;

class PegawaiKaryaController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $pegawai_karya = PegawaikaryaIlmiah::where('id_pegawai',$id_pegawai)->get();
        $bulan = array('1'=>'Januari', '2'=>'Februari', '3'=>'Maret', '4'=>'April', '5'=>'Mei', '6'=>'Juni', '7'=>'Juli', '8'=>'Agustus', '9'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');

        foreach($pegawai_karya as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/karya/index', compact('id_pegawai','pegawai_karya','fake_id','bulan'));
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
                'judul' => $request->judul,
                'nama_majalah' => $request->nama_majalah,
                'volume' => $request->volume,
                'nomor' => $request->nomor,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'link_url' => $request->link_url,
            ];

            $filename = '';
            if ($request->file('dokumen') != null) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/karya';
                $dokumen->move($tujuan_upload,$filename);
                $data['dokumen'] = $filename;
            }
            $pegawai = PegawaikaryaIlmiah::updateOrCreate(
                ['id' => $id],
                $data,
            );


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('dokumen')) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/karya';
                $dokumen->move($tujuan_upload,$filename);
            }

            $pegawai = PegawaikaryaIlmiah::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'judul' => $request->judul,
                    'nama_majalah' => $request->nama_majalah,
                    'volume' => $request->volume,
                    'nomor' => $request->nomor,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'link_url' => $request->link_url,
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

        $pegawai[0] = PegawaikaryaIlmiah::where($where)->first();
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
        $pegawai = PegawaikaryaIlmiah::where('id', $id)->delete();
    }
}
