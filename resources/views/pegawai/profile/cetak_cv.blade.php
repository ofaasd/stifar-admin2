
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak KHS</title>
    <style>
        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            font-size:8pt;
            width:"100%"
        }

        .customers td,
        .customers th {
            border: 1px solid #000;
        }

        .customers tr:nth-child(even) {
            background-color: #fff;
        }

        .customers tr:hover {
            background-color: #ddd;
        }

        .customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: center;
            background-color: #dfdfdf;
            color: black;
        }
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            <td width="10%">
                <img src="{{ $logo }}" alt="logo-stifar" style="width: 100px;"/>
            </td>
            <td width="90%" style="padding-left: 30px;">
                <center>
                    <b>SEKOLAH TINGGI ILMU FARMASI YAYASAN PHARMASI SEMARANG</b>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%" align="left"  cellpadding="5">
        <tr><th>Curiculum Vitae (CV)</th></tr>
    </table>
    <br />
    <table class="customers"  id="myTable" cellpadding="5" width="100%">
        <tr>
            <td><b>Nama Lengkap</b></td>
            <td>{{$pegawai->nama_lengkap}}</td>
            <td><b>NIY</b></td>
            <td>{{$pegawai->npp}}</td>
            <td><b>No. Telp</b></td>
            <td>{{$pegawai->notelp ?? "-"}}</td>
            <td rowspan=3 width="80">
                <img src='{{ (!empty($pegawai->foto))?public_path('assets/images/pegawai/' . $pegawai->foto):public_path('assets/images/user/7.jpg') }}' width="100%">
            </td>
        </tr>
        <tr>
            <td><b>NIDN</b></td>
            <td>{{$pegawai->nidn}}</td>
            <td><b>Tempat, Tanggal Lahir</b></td>
            <td>{{$pegawai->tempat_lahir}}, {{date('d-m-Y', strtotime($pegawai->tanggal_lahir))}}</td>
            <td><b>No. HP</b></td>
            <td>{{$pegawai->nohp ?? '-'}}</td>
        </tr>
        <tr>
            <td><b>Jenis Kelamin</b></td>
            <td>{{($pegawai->jenis_kelamin == 'L')?'Laki-laki':'Perempuan'}}</td>
            <td><b>Jabatan Struktural</b></td>
            <td>{{$jabatan_struktural}}</td>
            <td><b>Email</b></td>
            <td>{{$pegawai->email ?? '-'}}</td>
        </tr>
        <tr>
            <td>Kebangsaan</td>
            <td>Indonesia</td>
            <td>Jabatan Fungsional</td>
            <td>{{$jabatan_fungsional}}</td>
            <td>Alamat</td>
            <td colspan=2>{{$pegawai->alamat}}</td>
        </tr>
    </table><br />
    <table class="customers"  id="myTable" cellpadding="5" width="100%">
        <tr>
            <td><b>Riwayat Pendidikan</b></td>
            <td colspan=2>
                <b>Waktu</b>
            </td>
            <td colspan=2>
                <b>Jenjang</b>
            </td>
            <td colspan=2>
                <b>Nama Universitas</b>
            </td>
        </tr>
        @foreach($pegawai_pendidikan as $row)
            <tr>
                <td></td>
                <td colspan=2>{{date('d-m-Y', strtotime($row->tanggal_ijazah))}}</td>
                <td colspan=2>{{$row->jenjang}} {{$row->jurusan}}</td>
                <td colspan=2>{{$row->universitas}}</td>
            </tr>
        @endforeach
        <tr>
            <td><b>Riwayat Organisasi</b></td>
            <td colspan=2>
                <b>Waktu</b>
            </td>
            <td colspan=2>
                <b>Jabatan</b>
            </td>
            <td colspan=2>
                <b>Nama Organisasi</b>
            </td>
        </tr>
        @foreach($pegawai_organisasi as $row)
            <tr>
                <td></td>
                <td colspan=2>{{$row->tahun}}-{{($row->tahun_keluar == 0)?"Sekarang":$row->tahun_keluar}}</td>
                <td colspan=2>{{$row->jabatan}}</td>
                <td colspan=2>{{$row->nama_organisasi}}</td>
            </tr>
        @endforeach
        <tr>
            <td><b>Riwayat Pekerjaan</b></td>
            <td colspan=2>
                <b>Waktu</b>
            </td>
            <td colspan=2>
                <b>Posisi</b>
            </td>
            <td colspan=2>
                <b>Nama Pekerjaan</b>
            </td>
        </tr>
        @foreach($pegawai_pekerjaan as $row)
            <tr>
                <td></td>
                <td colspan=2>{{$row->tahun_masuk}}-{{($row->tahun_keluar == 0)?"Sekarang":$row->tahun_keluar}}</td>
                <td colspan=2>{{$row->posisi}}</td>
                <td colspan=2>{{$row->perusahaan}}</td>
            </tr>
        @endforeach
        <tr>
            <td><b>Riwayat Mengajar</b></td>
            <td colspan=2>
                <b>Tahun Akademik</b>
            </td>
            <td colspan=2>
                <b>Mata Kuliah</b>
            </td>
            <td colspan=2>
                <b>Prodi</b>
            </td>
        </tr>
        @foreach($pegawai_mengajar as $row)
            <tr>
                <td></td>
                <td colspan=2>{{$row->tahun}}-{{($row->tahun+1)}}</td>
                <td colspan=2>{{$row->mata_kuliah}}</td>
                <td colspan=2>{{$row->prodi}}</td>
            </tr>
        @endforeach
        <tr>
            <td><b>Riwayat Penelitian</b></td>
            <td colspan=2>
                <b>Judul</b>
            </td>
            <td>
                <b>Tahun</b>
            </td>
            <td>
                <b>Jenis Penelitian</b>
            </td>
            <td colspan=2>
                <b>Sumber Dana</b>
            </td>
        </tr>
        @foreach($pegawai_penelitian as $row)
            <tr>
                <td></td>
                <td colspan=2>{{$row->judul}}</td>
                <td>{{$row->tahun}}</td>
                <td>{{$row->jenis_penelitian}}</td>
                <td colspan=2>{{$row->sumber_dana}}</td>
            </tr>
        @endforeach
    </table>
    <p>Saya menyetujui isi diatas dengan sebenar-benarnya</p>
    <table align="right">
        <tr>
            <td align="center">Semarang, {{date('d')}} {{$bulan[date('m')]}} {{date('Y')}}</td>
        </tr>
        <tr>
            <td height="50"></td>
        </tr>
        <tr>
            <td align="center">{{$pegawai->nama_lengkap}}</td>
        </tr>
    </table>
</body>
</html>
