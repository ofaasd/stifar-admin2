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

        .details {
            margin-bottom: 20px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details th, .details td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }

        td{
            font-size: 15px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
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
    <center><b>Nota Pembimbing</b></center>
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
            <td style="padding-left: 80px;">Pembimbing 1</td>
            <td>:</td>
            <td>{{ $dosbim->nama_pembimbing_1 ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jurusan/Prodi</td>
            <td>:</td>
            <td>{{ $mhs->nama_prodi }}</td>
            <td style="padding-left: 80px;">Pembimbing 2</td>
            <td>:</td>
            <td>{{ $dosbim->nama_pembimbing_2 ?? '-'}}</td>
        </tr>
    </table>
    <br>
    <div class="container">
        <div class="details">
            <table>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <td>{{ $mhs->nama }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>{{ $mhs->nim }}</td>
                </tr>
                <tr>
                    <th>Jurusan/Prodi</th>
                    <td>{{ $mhs->nama_prodi }}</td>
                </tr>
                <tr>
                    <th>Judul Skripsi</th>
                    <td>Pengembangan Sistem Informasi Berbasis Web</td>
                </tr>
            </table>
    </div>
    
    <hr>
    <table width="100%">
        <tr>
            <td></td>
            <td></td>
            <td>Semarang, {{ date("d M Y") }}</td>
        </tr>
        <tr>
            <td><br><br><br><</td>
            <td><</td>
            <td><</td>
        </tr>
        <tr>
            <td><center>Pembimbing 1</center></td>
            <td><center></center></td>
            <td><center>Pembimbing 2</center></td>
        </tr>
        <tr>
            <td><center><br><br><br></center></td>
            <td><center></center></td>
            <td><center></center></td>
        </tr>
        <tr>
            <td><center>{{ $dosbim->nama_pembimbing_1 }}</center></td>
            <td><center></center></td>
            <td><center>{{ $dosbim->nama_pembimbing_2 }}</center></td>
        </tr>
        <tr>
            <td><center><br><br><br></center></td>
            <td><center></center></td>
            <td><center></center></td>
        </tr>
        <tr>
            <td><center><br><br><br></center></td>
            <td><center>Dosen Wali</center></td>
            <td><center></center></td>
        </tr>
        <tr>
            <td><center><br><br><br></center></td>
            <td><center></center></td>
            <td><center></center></td>
        </tr>
        <tr>
            <td><center></center></td>
            <td><center>{{ $mhs->dsn_wali }}</center></td>
            <td><center></center></td>
        </tr>
    </table>
</body>

</html>