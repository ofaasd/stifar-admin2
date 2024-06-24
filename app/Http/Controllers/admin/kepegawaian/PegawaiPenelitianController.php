<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiPenelitian;

class PegawaiPenelitianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $pegawai_penelitian = PegawaiPenelitian::where('id_pegawai',$id_pegawai)->get();


        foreach($pegawai_penelitian as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/penelitian/index', compact('id_pegawai','pegawai_penelitian','fake_id'));
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
                'nomor' => $request->nomor,
                'judul' => $request->judul,
                'fakultas' => $request->fakultas,
                'jenis_penelitian' => $request->jenis_penelitian,
                'tahun' => $request->tahun,
                'sumber_dana' => $request->sumber_dana,
                'dana' => $request->dana,
                'no_surat' => $request->no_surat,
                'penyelenggara' => $request->penyelenggara,
                'ketua' => $request->ketua,
                'anggota' => $request->anggota,
            ];

            $filename = '';
            if ($request->file('dokumen') != null) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/penelitian';
                $dokumen->move($tujuan_upload,$filename);
                $data['dokumen'] = $filename;
            }
            $filename2 = '';
            if ($request->file('proposal') != null) {
                $proposal = $request->file('proposal');
                $filename2 = date('YmdHi') . $proposal->getClientOriginalName();
                $tujuan_upload = 'assets/file/penelitian';
                $proposal->move($tujuan_upload,$filename2);
                $data['proposal'] = $filename2;
            }
            $filename3 = '';
            if ($request->file('lap_kemajuan') != null) {
                $lap_kemajuan = $request->file('lap_kemajuan');
                $filename3 = date('YmdHi') . $lap_kemajuan->getClientOriginalName();
                $tujuan_upload = 'assets/file/penelitian';
                $lap_kemajuan->move($tujuan_upload,$filename3);
                $data['lap_kemajuan'] = $filename3;
            }
            $filename4 = '';
            if ($request->file('lap_keuangan') != null) {
                $lap_keuangan = $request->file('lap_keuangan');
                $filename4 = date('YmdHi') . $lap_keuangan->getClientOriginalName();
                $tujuan_upload = 'assets/file/penelitian';
                $lap_keuangan->move($tujuan_upload,$filename4);
                $data['lap_keuangan'] = $filename4;
            }
            $filename5 = '';
            if ($request->file('lap_akhir') != null) {
                $lap_akhir = $request->file('lap_akhir');
                $filename5 = date('YmdHi') . $lap_akhir->getClientOriginalName();
                $tujuan_upload = 'assets/file/penelitian';
                $lap_akhir->move($tujuan_upload,$filename5);
                $data['lap_akhir'] = $filename5;
            }
            $pegawai = PegawaiPenelitian::updateOrCreate(
                ['id' => $id],
                $data,
            );


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('dokumen') != null) {
                $dokumen = $request->file('dokumen');
                $filename = date('YmdHi') . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/penelitian';
                $dokumen->move($tujuan_upload,$filename);

            }
            $filename2 = '';
            if ($request->file('proposal') != null) {
                $proposal = $request->file('proposal');
                $filename2 = date('YmdHi') . $proposal->getClientOriginalName();
                $tujuan_upload = 'assets/file/penelitian';
                $proposal->move($tujuan_upload,$filename2);

            }
            $filename3 = '';
            if ($request->file('lap_kemajuan') != null) {
                $lap_kemajuan = $request->file('lap_kemajuan');
                $filename3 = date('YmdHi') . $lap_kemajuan->getClientOriginalName();
                $tujuan_upload = 'assets/file/penelitian';
                $lap_kemajuan->move($tujuan_upload,$filename3);
                $data['lap_kemajuan'] = $filename3;
            }
            $filename4 = '';
            if ($request->file('lap_keuangan') != null) {
                $lap_keuangan = $request->file('lap_keuangan');
                $filename4 = date('YmdHi') . $lap_keuangan->getClientOriginalName();
                $tujuan_upload = 'assets/file/penelitian';
                $lap_keuangan->move($tujuan_upload,$filename4);
                $data['lap_keuangan'] = $filename4;
            }
            $filename5 = '';
            if ($request->file('lap_akhir') != null) {
                $lap_akhir = $request->file('lap_akhir');
                $filename5 = date('YmdHi') . $lap_akhir->getClientOriginalName();
                $tujuan_upload = 'assets/file/penelitian';
                $lap_akhir->move($tujuan_upload,$filename5);
                $data['lap_akhir'] = $filename5;
            }

            $pegawai = PegawaiPenelitian::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'nomor' => $request->nomor,
                    'judul' => $request->judul,
                    'fakultas' => $request->fakultas,
                    'jenis_penelitian' => $request->jenis_penelitian,
                    'tahun' => $request->tahun,
                    'sumber_dana' => $request->sumber_dana,
                    'dana' => $request->dana,
                    'no_surat' => $request->no_surat,
                    'penyelenggara' => $request->penyelenggara,
                    'ketua' => $request->ketua,
                    'anggota' => $request->anggota,
                    'dokumen' => $filename,
                    'proposal' => $filename2,
                    'lap_kemajuan' => $filename3,
                    'lap_keuangan' => $filename4,
                    'lap_akhir' => $filename5
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

        $pegawai[0] = PegawaiPenelitian::where($where)->first();
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
