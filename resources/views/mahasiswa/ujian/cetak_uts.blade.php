<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Ujian</title>
    <style>
        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            font-size:10pt;
            
        }

        .customers td,
        .customers th {
            border: 1px solid #ddd;
        }

        .customers tr:nth-child(even) {
            background-color: #f2f2f2;
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
                    <br><b>KARTU UJIAN</b>
                </center>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td>Semester</td>
            <td>:</td>
            <td>{{ $semester[$smt] }} - {{ $tahun_ajar }}</td>
            <td rowspan="4" style="text-align: right"><img style="width:90;" alt="" src="{{ $foto }}"></td>
        </tr>
        <tr>
            <td>Jurusan/Prodi</td>
            <td>:</td>
            <td>{{ $mhs->nama_prodi }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $mhs->nama }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $mhs->nim }}</td>
        </tr>
    </table>
    <br>
    <table class="customers" cellpadding="5" style="width: 100%;">
        <thead>
            <tr>
                <td>No.</td>
                <td>Kode MK</td>
                <td>Nama Matakuliah</td>
                <td>(%) Kehadiran</td>
                <td>SKS</td>
                <td>
                    <center>TTD Pengawas</center>
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
                $t = 0;
                $p = 0;
            ?>
            @foreach($krs as $row_krs)
            <?php
                $t += $row_krs['sks_teori'];
                $p += $row_krs['sks_praktek'];
            ?>
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row_krs['kode_jadwal'] }}</td>
                <td>{{ $row_krs['nama_matkul'] }}</td>
                <td>0</td>
                <td>{{ $row_krs['sks_praktek'] + $row_krs['sks_teori'] }}</td>
                <td></td>
            </tr>
            @endforeach
            <tr>
                <td colspan="10" style="text-align: center">
                    <span>Jumlah Matakuliah : {{$no -1}}, Jumlah SKS yang diambil : {{$t+$p}}</span>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <br>
   
    <table align="right" class="customers">
        <tr>
            <td>Bag. Keuangan</td>
            <td>Ka Sub. BAAK</td>
        </tr>
        <tr>
            <td height="50" valign="top">Lunas UPP</td>
            <td valign="top">Pengesahan</td>
        </tr>
    </table>
</body>

</html>