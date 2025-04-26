<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblSoalKuesioner;
use App\Models\TahunAjaran;
use Session;

class SoalKuesionerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $id)
    {
        //
        $ta = TahunAjaran::where('id',$id)->first();
        $kuesioner = TblSoalKuesioner::where('id_ta',$id)->get();
        $title = "Soal Kuesioner Tahun Ajaran " . $ta->kode;
        return view('admin.akademik.kuesioner.soal', compact('title','kuesioner','id','ta'));
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
        if(empty($id)){
            //create
            $kuesioner = TblSoalKuesioner::updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'id_ta' => $request->id_ta,
                'no_soal' => $request->no_soal,
                'soal' => $request->soal,
                'category' => $request->category,
                'tipe_soal' => $request->tipe_soal,
            ]);
            if($kuesioner){
                Session::flash('success','Data Soal berhasil tersimpan');
                return redirect('/admin/akademik/list-soal/' . $request->id_ta);

            }else{
                Session::flash('error','Data Soal gagal tersimpan');
                return redirect('/admin/akademik/list-soal/' . $request->id_ta);
            }
        }else{
            //update
            $kuesioner = TblSoalKuesioner::updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'id_ta' => $request->id_ta,
                'no_soal' => $request->no_soal,
                'soal' => $request->soal,
                'category' => $request->category,
                'tipe_soal' => $request->tipe_soal,
            ]);
            if($kuesioner){
                Session::flash('success','Data Soal berhasil tersimpan');
                return redirect('/admin/akademik/list-soal/' . $request->id_ta);

            }else{
                Session::flash('error','Data Soal gagal tersimpan');
                return redirect('/admin/akademik/list-soal/' . $request->id_ta);
            }
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $ta = TahunAjaran::where('id',$id)->first();
        $kuesioner = TblSoalKuesioner::where('id_ta',$id)->get();
        $title = "Soal Kuesioner Tahun Ajaran " . $ta->kode_ta;
        return view('admin.akademik.kuesioner.soal', compact('title','kuesioner','id','ta'));
    }

    public function simpan_status(Request $request){
        $id = $request->id_ta;
        $ta = TahunAjaran::find($id);
        $ta->kuesioner = $request->status;
        $ta->save();
        Session::flash('success','Status Kuesioner berhasil diperbarui');
        return redirect('/admin/akademik/list-soal/' . $request->id_ta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $soal = TblSoalKuesioner::where('id', $id)->delete();
    }
}
