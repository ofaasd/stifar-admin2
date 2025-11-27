
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
    <center><b>Total Tagihan @if(empty($id))Semua Program Studi @else Program Studi {{ $nama_prodi }} @endif</b></center>
    <br>
    <table class="customers"  id="myTable" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Gelombang</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Total Tagihan</th>
                <th>Total Bayar</th>
                <th>Sisa Bayar</th>
                <th>Pembayaran Terakhir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td>
                        {{$no++}}    
                    </td>
                    <td>{{$row['gelombang']}}</td>
                    <td>{{$row['nim']}}</td>
                    <td>{{$row['nama']}}</td>
                    <td>{{$row['total_bayar']}}</td>
                    <td>{{$row['pembayaran']}}</td>
                    <td>{{$row['sisa_bayar']}}</td>
                    <td>{{$row['last_pay']}}</td>
                    <td>{{$row['status']}}</td>
                    
                    {{-- <td></td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
