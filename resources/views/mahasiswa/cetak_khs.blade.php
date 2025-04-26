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
                    <br><b>HASIL STUDI SEMESTER</b>
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
            <td rowspan="4" style="text-align: right"><img style="width:85px;" alt="" src="{{ $foto }}"></td>
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
                <td>Nilai Simbol</td>
                <td>Nilai Angka</td>
                <td>SKS</td>
                <td>Kualitas</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $t = 0;
                $p = 0;
                $total_kualitas = 0;
            ?>
            @foreach($krs as $row_krs)
            <?php
                $t += $row_krs['sks_teori'];
                $p += $row_krs['sks_praktek'];
                $kualitas = App\helpers::getKualitas($nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_huruf']) * ($row_krs['sks_praktek'] + $row_krs['sks_teori']);
                $total_kualitas += $kualitas;
            ?>
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row_krs['kode_matkul'] }}</td>
                <td>{{ $row_krs['nama_matkul'] }}</td>
                <td>{{ $nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_akhir']}}</td>
                <td>{{ $nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_huruf']}}</td>
                <td>{{ $row_krs['sks_praktek'] + $row_krs['sks_teori'] }}</td>
                <td>{{ $kualitas }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5" style="text-align: center">
                    Total
                </td>
                <td>
                    <span>{{$t+$p}}</span>
                </td>
                <td>{{$total_kualitas}}</td>
            </tr>
            <tr>
                <td colspan=10>
                    <table>
                        <tr><td>IP Semester</td><td>: {{number_format($total_kualitas / ($t+$p),2,',','')}}</td></tr>
                        <tr><td>IPK Sementara</td><td>: {{number_format($total_kualitas / ($t+$p),2,',','')}}</td></tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <br>
</body>

</html>