
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
        <tr><td>Program Studi</td><td width="40%">: {{$jadwal->nama_prodi}}</td><td>Nama Dosen</td><td>: {{$dosen}}</td></tr>
        <tr><td>Kode MK / Mata Kuliah</td><td>: {{$jadwal->kode_matkul}} / {{$jadwal->nama_matkul}}</td><td>Kelas / Kelompok</td><td>: 1</td></tr>
    </table>
    <br />
    <table width="100%" class="customers" style="margin-top:20px;" cellpadding="5">
        <thead>
           <tr>
                <td rowspan="2" width="15" align="center">Pertemuan Ke</td>
                <td rowspan="2" align="center" width="15%">Hari / Tanggal</td>
                <td colspan="2" align="center" width="50%">Realisasi SAP</td>
                <td rowspan="2" align="center">Jumlah Hadir Mhs</td>
                <td rowspan="2" align="center">Paraf Mhs</td>
                <td rowspan="2" align="center">TTD Dosen</td>
            </tr>
            <tr>
                <td align="center" width="25%">Judul Bab</td>
                <td align="center" width="25%">Rincian Materi</td>
            </tr>
        </thead>
        <tbody>
            @for($i=8; $i<=14; $i++)
            <tr>
                <td align="center">{{$i+1}}</td>
                <td>{{$list_pertemuan[$i]['tanggal_pertemuan']}}</td>
                <td>{{$list_pertemuan[$i]['judul']}}</td>
                <td>{{$list_pertemuan[$i]['rincian']}}</td>
                <td align="center">{{$list_pertemuan[$i]['jml_hadir']}}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            @endfor
            
            <tr><td align="center">16</td><td colspan="6" align="center">Ujian Akhir Semester</td></tr>
        </tbody>
    </table>
    <table width="30%" align="right">
        <tr>
            <td>
                Mengetahui,
            </td>
        </tr>
        <tr>
            <td>
                Ketua Program Studi,
            </td>
        </tr>
        <tr>
            <td height=30>
                
            </td>
        </tr>
        <tr>
            <td height=20>
                {{$kep_prodi->nama_lengkap}}
            </td>
        </tr>
        
    </table>
</body>
</html>
