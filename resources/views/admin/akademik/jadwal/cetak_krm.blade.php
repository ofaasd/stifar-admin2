
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
            font-size:9pt;

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
                    <b>SEKOLAH TINGGI ILMU FARMASI SEMARANG</b>
                    <br>Alamat : Jl. Letnan Jendral Sarwo Edie Wibowo Km. 1, Plamongan Sari, Kec. Pedurungan, Kota Semarang
                    <br>Email : admin@sistifar.id
                    <br>Website : https://stifar.ac.id
                </center>
            </td>
        </tr>
    </table>
    <hr>
    <center><b>Kartu Rencana Mengajar (KRM)</b></center>
    <br>
    <table width="100%">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $dosen->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>NIY</td>
            <td>:</td>
            <td>{{ $dosen->npp }}</td>
        </tr>
         <tr>
            <td>Semester</td>
            <td>:</td>
            <td>{{ $semester[$smt] }} - {{ $tahun_ajar }}</td>
        </tr>
    </table>
    <br />
    <table class="customers"  id="myTable" cellpadding="5">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Jadwal</th>
                <th>Hari & Waktu</th>
                <th>Matakuliah</th>
                <th>Ruang</th>
                <th>Tahun Ajaran</th>
                <th>Status</th>
                <th>T/P</th>
                <th>Sisa / Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwal as $jad)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $jad['kode_jadwal'] }}</td>
                    <td>{{ $jad['hari'] }}, {{ $jad['nama_sesi'] }}</td>
                    <td>[{{ $jad['kode_matkul'] }}] {{ $jad['nama_matkul'] }}</td>
                    <td>{{ $jad['nama_ruang'] }}</td>
                    <td>{{ $jad['kode_ta'] }}</td>
                    <td>{{ $jad['status'] }}</td>
                    <td>{{ $jad['tp'] }}</td>
                    <td>{{$jumlah_input_krs[$jad['id']]}} / {{ $jad['kuota'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table width="100%">
        <tr>
            <td></td>
            <td></td>
            <td><center>Semarang, {{ date("d M Y") }}</center></td>
        </tr>
        <tr>
            <td width="33%"><center></center></td>
            <td width="33%"><center></center></td>
            <td width="33%"><center>Kepala Program Studi</center></td>
        </tr>
        <tr>
            <td><center><br><br><br></center></td>
            <td><center></center></td>
            <td><center></center></td>
        </tr>
        <tr>
            <td></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><center>{{$nama_kepala}}</center></td>
        </tr>
    </table>
</body>
</html>
