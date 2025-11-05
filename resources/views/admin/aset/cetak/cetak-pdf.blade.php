<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 80px;
        }
        .header-info {
            padding-left: 24px;
        }

        h4 {
            margin-top: 18px;
            margin-bottom: 18px;
            text-align: left;
        }

        /* tabel data dengan border yang jelas */
        .label-table {
            width: 100%;
            border-collapse: collapse; /* gunakan collapse agar border menyatu */
            margin-top: 12px;
        }
        .label-table th,
        .label-table td {
            border: 1px solid #222;
            padding: 4px 6px;
            vertical-align: middle;
        }
        .label-table th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="10%">
                <img src="{{ $logo }}" alt="logo-stifar" class="header-logo"/>
            </td>
            <td width="90%" class="header-info">
                <b>SEKOLAH TINGGI ILMU FARMASI SEMARANG</b>
                <br>Alamat : Jl. Letnan Jendral Sarwo Edie Wibowo Km. 1, Plamongan Sari, Kec. Pedurungan, Kota Semarang
                <br>Email : admin@sistifar.id
                <br>Website : https://stifar.ac.id
            </td>
        </tr>
    </table>
    <h4>Aset {{ $title }}</h4>

    <table class="label-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th>Nama</th>
                <th style="width: 12%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->nama }}</td>
                <td>{{ $row->jumlah ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
