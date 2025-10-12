<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lampiran Sidang</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
            border-radius: 10px;
            padding: 30px 40px;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            width: 80px;
            margin-right: 25px;
        }
        .header-info {
            flex: 1;
            text-align: center;
        }
        .header-info b {
            font-size: 1.3em;
            color: #007bff;
        }
        .title {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        .info-table {
            width: 100%;
            font-size: 14px;
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        .info-table td {
            padding: 6px 10px;
            background: #f1f3f6;
            border-radius: 5px;
        }
        .info-table td:first-child {
            font-weight: 500;
            color: #007bff;
            width: 28%;
        }
        .info-table td:nth-child(2) {
            width: 3%;
            text-align: center;
        }
        .info-table td:last-child {
            color: #222;
        }
        .penguji-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .penguji-list li {
            margin-bottom: 3px;
        }
        .footer {
            text-align: right;
            font-size: 12px;
            color: #555;
            margin-top: 25px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 15px 5px;
            }
            .header-info {
                font-size: 0.95em;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ $logo }}" alt="logo-stifar" style="width: 100px;"/>
            <div class="header-info">
                <b>SEKOLAH TINGGI ILMU FARMASI SEMARANG</b>
                <br>Alamat: Jl. Letnan Jendral Sarwo Edie Wibowo Km. 1, Plamongan Sari, Kec. Pedurungan, Kota Semarang
                <br>Email: admin@sistifar.id | Website: https://stifar.ac.id
            </div>
        </div>
        <div class="title">
            Lampiran Sidang 
            @if($sidang->jenis == 1)
                Terbuka
            @elseif($sidang->jenis == 2)
                Tertutup
            @else
                -
            @endif
        </div>
        @if($sidang)
        <table class="info-table">
            <tr>
                <td>Nama Mahasiswa</td>
                <td>:</td>
                <td>{{ $sidang->nama }} ({{ $sidang->nim }})</td>
            </tr>
            <tr>
                <td>Judul Skripsi</td>
                <td>:</td>
                <td>{{ $sidang->judul }}</td>
            </tr>
            <tr>
                <td>Pembimbing</td>
                <td>:</td>
                <td>
                    {{ $sidang->namaPembimbing1 }}
                    @if($sidang->namaPembimbing2)
                        &amp; {{ $sidang->namaPembimbing2 }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td>:</td>
                <td>
                    @if($sidang->jenis == 1)
                        Sidang Terbuka
                    @elseif($sidang->jenis == 2)
                        Sidang Tertutup
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>Tanggal Sidang</td>
                <td>:</td>
                <td>{{ $sidang->tanggal }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ $sidang->waktuMulai }} - {{ $sidang->waktuSelesai }}</td>
            </tr>
            <tr>
                <td>Ruang</td>
                <td>:</td>
                <td>{{ $sidang->ruangan }}</td>
            </tr>
            <tr>
                <td>Gelombang</td>
                <td>:</td>
                <td>{{ $sidang->namaGelombang }} ({{ $sidang->periode }})</td>
            </tr>
            <tr>
                <td>Penguji</td>
                <td>:</td>
                <td>
                    <ul class="penguji-list">
                        @for($i = 1; isset($sidang->{'namaPenguji'.$i}); $i++)
                            <li>{{ $sidang->{'namaPenguji'.$i} }}</li>
                        @endfor
                    </ul>
                </td>
            </tr>
        </table>
        @else
            <div style="text-align:center; color: #c00; margin: 30px 0;">
                Data sidang tidak ditemukan.
            </div>
        @endif

        <div class="footer">
            Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>

</html>