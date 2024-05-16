<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbNilaiTambahan;
use App\Models\PmbPesertaOnline;

class PmbNilaiTambahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        //

        $title = "Nilai Tambahan";
        $peserta = PmbPesertaOnline::find($id);
        $nilai = PmbNilaiTambahan::where('id_peserta',$id)->get();
        return view('admin.admisi.nilai_tambahan.index', compact('title','peserta','nilai','id'));
    }

    public function table($id)
    {
        //
        $title = "Nilai Tambahan";
        $peserta = PmbPesertaOnline::find($id);
        $nilai = PmbNilaiTambahan::where('id_peserta',$id)->get();
        return view('admin.admisi.nilai_tambahan.table', compact('title','peserta','nilai','id'));
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
    public function store(Request $request,$id)
    {
        //
        $id_nilai = $request->id;

        if ($id_nilai) {
            $nilai = PmbNilaiTambahan::updateOrCreate(
                ['id' => $id_nilai],
                [
                    'keterangan' => $request->keterangan,
                    'nilai' => $request->nilai,
                ]
            );

            return response()->json('Updated');
        } else {
            $nilai = PmbNilaiTambahan::updateOrCreate(
                ['id' => $id_nilai],
                [
                    'keterangan' => $request->keterangan,
                    'nilai' => $request->nilai,
                ]
            );
            if ($nilai) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Academic');
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
        $nilai = PmbNilaiTambahan::find($id);
        return response()->json($nilai);
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
        $gel = PmbNilaiTambahan::where('id', $id)->delete();
    }
}
