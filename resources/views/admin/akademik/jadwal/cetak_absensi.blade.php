
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Absensi</title>
    <style>
        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            font-size:10pt;

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
            text-align: left;
            background-color: #04AA6D;
            color: white;
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
    <table width="100%" align="left">
        <tr><th colspan="4">DAFTAR HADIR PERKULIAHAN</th></tr>
        <tr><td>Program Studi</td><td width="50%">: {{$jadwal->nama_prodi}}</td><td>Semester / Tahun</td><td>: {{$semester}} / {{$tahun}}</td></tr>
        <tr><td>Kode MK / Mata Kuliah</td><td>: {{$jadwal->kode_matkul}} / {{$jadwal->nama_matkul}}</td><td>Kelas</td><td>: {{$jadwal->kel}}</td></tr>
        <tr><td>Nama Dosen</td><td colspan="3">: {{$dosen}}</td></tr>
    </table>
    <br />
    <table width="100%" class="customers" style="margin-top:20px;">
        <thead>
            <tr>
                <td rowspan=3 width="20" align="center">No</td>
                <td rowspan="3" align="center">NIM</td>
                <td rowspan="3" align="center" width="50%">Nama Mahasiswa</td>
                <td colspan="16" align="center"><b>Tanggal Kuliah dan Paraf Mahasiswa</b></td>
                <td rowspan="3" align="center">Keterangan</td>
            </tr>
            <tr>
                @for($i=1; $i<=16; $i++)
                <td {{($i == 8 || $i == 16)?"bgcolor=#dfdfdf":""}} align="center">{{$i}}</td>
                @endfor
            </tr>
            <tr>
                @for($i=1; $i<=16; $i++)
                <td {{($i == 8 || $i == 16)?"bgcolor=#dfdfdf":""}} width="23">&nbsp;</td>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php $j = 1; @endphp
            @foreach($daftar_mhs as $row)
            <tr>
                <td align="center">{{$j++}}</td>
                <td>{{$row->nim}}</td>
                <td>{{$row->nama}}</td>
                @for($i=1; $i<=16; $i++)
                <td {{($i == 8 || $i == 16)?"bgcolor=#dfdfdf":""}} align="center"></td>
                @endfor
                <td></td>
            </tr>
            @endforeach
            <tr>
                <td colspan=3>Tanggal Pertemuan</td>
                @for($i=1; $i<=16; $i++)
                <td {{($i == 8 || $i == 16)?"bgcolor=#dfdfdf":""}} align="center"></td>
                @endfor
                <td></td>
            </tr>
            <tr>
                <td colspan=3>Paraf Dosen Pengampu</td>
                @for($i=1; $i<=16; $i++)
                <td {{($i == 8 || $i == 16)?"bgcolor=#dfdfdf":""}} align="center"></td>
                @endfor
                <td></td>
            </tr>
        </tbody>
    </table>
    <table width="30%" align="right">
        <tr>
            <td>
                Semarang,
            </td>
        </tr>
        <tr>
            <td>
                Mengetahui,
            </td>
        </tr>
        <tr>
            <td>
                Ka Program Studi,
            </td>
        </tr>
        <tr>
            <td height=30>
                
            </td>
        </tr>
        <tr>
            <td height=20>
                {{$kep_prodi->gelar_depan}} {{$kep_prodi->nama_lengkap}}{{(!empty($kep_prodi->gelar_belakang))?', '.$kep_prodi->gelar_belakang:''}}
            </td>
        </tr>
        
    </table>
</body>
</html>
