<?php

namespace App\Http\Controllers\admin\akademik\yudisium;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GelombangYudisium;
use Illuminate\Support\Facades\Crypt;

class PengesahanYudisiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
    * pengesahan gelombang yudisium.
    *
    * Terakhir diedit: 6 November 2025
    * Editor: faiz
    */
    public function store(Request $request)
    {
        $idDekrip = Crypt::decryptString($request->idEnkripsi);
        $id = str_replace("stifar", "", $idDekrip);

        $data = GelombangYudisium::where('id', $id)->first();
        if(!$data)
        {
            return redirect()->back()->with('error', 'Data not found');
        }

        $data->update([
            'tanggal_pengesahan' => $request->tanggalPengesahan
        ]);

        return redirect()->back()->with('success', $data->nama . " Telah Berhasil disahkan");
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
    }
}
