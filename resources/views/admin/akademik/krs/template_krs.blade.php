<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template KRS</title>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
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
                    <b>SEKOLAH TINGGI ILMU FARMASI SEMARANG</b>
                    <br>Alamat : Jl. Letnan Jendral Sarwo Edie Wibowo Km. 1, Plamongan Sari, Kec. Pedurungan, Kota Semarang
                    <br>Email : admin@sistifar.id
                    <br>Website : https://stifar.ac.id
                </center>
            </td>
        </tr>
    </table>
    <hr>
    <center><b>KARTU RENCANA STUDI</b></center>
    <br>
    <table width="100%">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $mhs->nama }}</td>
            <td style="padding-left: 80px;">Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $tahun_ajar }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $mhs->nim }}</td>
            <td style="padding-left: 80px;">Semester</td>
            <td>:</td>
            <td>{{ $semester[$smt] }}</td>
        </tr>
        <tr>
            <td>Jurusan/Prodi</td>
            <td>:</td>
            <td>{{ $mhs->nama_prodi }}</td>
            <td style="padding-left: 80px;">Dosen Wali</td>
            <td>:</td>
            <td>{{ $mhs->dsn_wali }}</td>
        </tr>
    </table>
    <br>
    <table id="customers">
        <thead>
            <tr>
                <td rowspan="2">No.</td>
                <td rowspan="2">Kelas</td>
                <td rowspan="2">Nama Matakuliah</td>
                <td colspan="2">SKS</td>
                <td colspan="2">
                    <center>Jadwal</center>
                </td>
            </tr>
            <tr>
                <td><center>T</center></td>
                <td><center>P</center></td>
                <td>Hari, Waktu</td>
                <td>Ruang</td>
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
                <td>{{ $row_krs['kel'] }}</td>
                <td>{{ $row_krs['nama_matkul'] }}</td>
                <td>{{ $row_krs['sks_teori'] }}</td>
                <td>{{ $row_krs['sks_praktek'] }}</td>
                <td>{{ $row_krs['hari'] }}, {{ $row_krs['nama_sesi'] }}</td>
                <td>{{ $row_krs['nama_ruang'] }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3">
                    <span>Jumlah SKS</span>
                </td>
                <td>{{ $t+$p }}</td>
            </tr>
        </tbody>
    </table>
    <hr>
    <br>
    <center>PERSETUJUAN KARTU STUDI</center>
    <table width="100%">
        <tr>
            <td></td>
            <td></td>
            <td>Semarang, {{ date("d M Y") }}</td>
        </tr>
        <tr>
            <td><center>Dosen Wali</center></td>
            <td><center></center></td>
            <td><center>Mahasiswa</center></td>
        </tr>
        <tr>
            <td><center><br><br><br></center></td>
            <td><center></center></td>
            <td><center></center></td>
        </tr>
        <tr>
            <td><center>{{ $mhs->dsn_wali }}</center></td>
            <td><center>Orang Tua Wali</center></td>
            <td><center>{{ $mhs->nama }}</center></td>
        </tr>
        <tr>
            <td><center><br><br><br></center></td>
            <td><center></center></td>
            <td><center></center></td>
        </tr>
        <tr>
            <td><center></center></td>
            <td><center>..................</center></td>
            <td><center></center></td>
        </tr>
    </table>
</body>

</html>