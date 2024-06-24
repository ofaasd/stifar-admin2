<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiPekerjaan;

class PegawaiPekerjaanController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $pegawai_pekerjaan = PegawaiPekerjaan::where('id_pegawai',$id_pegawai)->get();


        foreach($pegawai_pekerjaan as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/pekerjaan/index', compact('id_pegawai','pegawai_pekerjaan','fake_id'));
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
                'posisi' => $request->posisi,
                'perusahaan' => $request->perusahaan,
                'tahun_masuk' => $request->tahun_masuk,
                'tahun_keluar' => $request->tahun_keluar,
            ];


            $pegawai = PegawaiPekerjaan::updateOrCreate(
                ['id' => $id],
                $data,
            );


            return response()->json('Updated');
        } else {

            $pegawai = PegawaiPekerjaan::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'posisi' => $request->posisi,
                    'perusahaan' => $request->perusahaan,
                    'tahun_masuk' => $request->tahun_masuk,
                    'tahun_keluar' => $request->tahun_keluar,
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

        $pegawai[0] = PegawaiPekerjaan::where($where)->first();
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
        $pegawai = PegawaiPekerjaan::where('id', $id)->delete();
    }
}
