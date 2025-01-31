<?php

namespace App\Http\Controllers\admin\kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiKegiatanLuar;

class PegawaiKegiatanLuarController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $id_pegawai = $request->id;
        $pegawai_kegiatan_luar = PegawaiKegiatanLuar::where('id_pegawai',$id_pegawai)->get();


        foreach($pegawai_kegiatan_luar as $row){
            $i = 0;
            $data['fake_id'] = ++$i;
        }
        $fake_id = 0;
        return view('admin/kepegawaian/pegawai/kegiatan_luar/index', compact('id_pegawai','pegawai_kegiatan_luar','fake_id'));
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
                'nama_instansi' => $request->nama_instansi,
                'sebagai' => $request->sebagai,
                'durasi' => $request->durasi,
                'link' => $request->link,
                'tanggal' => $request->tanggal,
            ];

            $filename = '';
            if ($request->file('surat_tugas') != null) {
                $dokumen = $request->file('surat_tugas');
                $filename = date('YmdHi') . "surat_tugas" . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/kegiatan_luar';
                $dokumen->move($tujuan_upload,$filename);
                $data['surat_tugas'] = $filename;
            }
            $filename2 = '';
            if ($request->file('bukti_kegiatan') != null) {
                $dokumen = $request->file('bukti_kegiatan');
                $filename2 = date('YmdHi') . "bukti_kegiatan" . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/kegiatan_luar';
                $dokumen->move($tujuan_upload,$filename2);
                $data['bukti_kegiatan'] = $filename2;
            }
            $filename3 = '';
            if ($request->file('dokumen_pendukung') != null) {
                $dokumen = $request->file('dokumen_pendukung');
                $filename3 = date('YmdHi') . "dokumen_pendukung" . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/kegiatan_luar';
                $dokumen->move($tujuan_upload,$filename3);
                $data['dokumen_pendukung'] = $filename3;
            }
            $pegawai = PegawaiKegiatanLuar::updateOrCreate(
                ['id' => $id],
                $data,
            );


            return response()->json('Updated');
        } else {
            $filename = '';
            if ($request->file('surat_tugas') != null) {
                $dokumen = $request->file('surat_tugas');
                $filename = date('YmdHi') . "surat_tugas" . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/kegiatan_luar';
                $dokumen->move($tujuan_upload,$filename);
                //$data['file'] = $filename;
            }
            $filename2 = '';
            if ($request->file('bukti_kegiatan') != null) {
                $dokumen = $request->file('bukti_kegiatan');
                $filename2= date('YmdHi') . "bukti_kegiatan" . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/kegiatan_luar';
                $dokumen->move($tujuan_upload,$filename2);
                //$data['file'] = $filename;
            }
            $filename3 = '';
            if ($request->file('dokumen_pendukung') != null) {
                $dokumen = $request->file('dokumen_pendukung');
                $filename3 = date('YmdHi') . "dokumen_pendukung" . $dokumen->getClientOriginalName();
                $tujuan_upload = 'assets/file/kegiatan_luar';
                $dokumen->move($tujuan_upload,$filename3);
                //$data['file'] = $filename;
            }

            $pegawai = PegawaiKegiatanLuar::updateOrCreate(
                ['id' => $id],
                [
                    'id_pegawai' => $request->id_pegawai,
                    'nama_instansi' => $request->nama_instansi,
                    'sebagai' => $request->sebagai,
                    'durasi' => $request->durasi,
                    'link' => $request->link,
                    'tanggal' => $request->tanggal,
                    'surat_tugas' => $filename,
                    'bukti_kegiatan' => $filename2,
                    'dokumen_pendukung' => $filename3,
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

        $pegawai[0] = PegawaiKegiatanLuar::where($where)->first();
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
        $pegawai = PegawaiKegiatanLuar::where('id', $id)->delete();
    }
}
