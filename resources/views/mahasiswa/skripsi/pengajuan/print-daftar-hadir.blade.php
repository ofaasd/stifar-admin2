<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir Seminar Proposal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px;
            font-size: 11pt;
        }
        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .title-container {
            text-align: center;
            margin-bottom: 25px;
        }
        .title-container h1 {
            font-size: 13pt;
            margin: 0;
            text-transform: uppercase;
        }
        .title-container h2 {
            font-size: 11pt;
            margin: 5px 0 0;
            font-weight: normal;
        }
        .student-details {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
            font-size: 11pt;
        }
        .student-details td {
            /* padding: 2px 0; */
            vertical-align: top;
        }
        .details-label {
            width: 150px;
        }
        .details-separator {
            width: 10px;
            padding-right: 5px;
        }
        .details-line {
            width: 100%;
            height: 1em;
        }
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 8pt;
        }
        .attendance-table th, 
        .attendance-table td {
            border: 1px solid black;
            height: 18px;
            vertical-align: middle;
        }
        .attendance-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .col-no { width: 5%; }
        .col-nim { width: 15%; }
        .col-nama { width: 30%; }
        .signature-area {
            width: 100%;
            margin-top: 40px;
            font-size: 11pt;
        }
        .signature-row {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }
        .signature-col {
            width: 40%;
            text-align: right;
        }
        .ketua-sidang {
            margin-top: 50px;
            text-align: center;
            width: 250px;
            margin-left: auto;
        }
        .ketua-sidang .signature-line {
            display: block;
            border-bottom: 1px solid black;
            width: 100%;
            margin-top: 60px;
        }

        .footer {
            text-align: right;
            font-size: 12px;
            color: #555;
            margin-top: 25px;
        }
        
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('assets/images/skripsi/header-persetujuan.png') }}" alt="Header" style="width: 100%; height: auto;"/>
    </div>

    <div class="title-container">
        <h1>DAFTAR HADIR SEMINAR PROPOSAL</h1>
        <h2>PROGRAM STUDI S1 FARMASI TA. {{ $sidang->ta }}</h2>
        <h2>STIFAR YAYASAN PHARMASI SEMARANG</h2>
    </div>

    <table class="student-details">
        <tr>
            <td class="details-label">Nama Mahasiswa</td>
            <td class="details-separator">:</td>
            <td><div class="details-line">{{ $sidang->nama }}</div></td>
        </tr>
        <tr>
            <td class="details-label">NIM</td>
            <td class="details-separator">:</td>
            <td><div class="details-line">{{ $sidang->nim }}</div></td>
        </tr>
        <tr>
            <td class="details-label">Judul Skripsi</td>
            <td class="details-separator">:</td>
            <td><div class="details-line">{{ $sidang->judul }}</div></td>
        </tr>
    </table>

    <table class="attendance-table">
        <thead>
            <tr>
                <th class="col-no">No.</th>
                <th class="col-nim">NIM</th>
                <th class="col-nama">Nama Mahasiswa</th>
                <th>Hadir</th>
                <th class="col-no">No.</th>
                <th class="col-nim">NIM</th>
                <th class="col-nama">Nama Mahasiswa</th>
                <th>Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penonton->chunk(20) as $chunk)
                @for ($i = 0; $i < 20; $i++)
                    <tr>
                        @php
                            $left = $chunk->get($i);
                            $right = $chunk->get($i + 20);
                        @endphp
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $left->nim ?? '' }}</td>
                        <td style="text-align: left;">{{ $left->nama ?? '' }}</td>
                        <td></td>
                        <td>{{ $i + 21 }}</td>
                        <td>{{ $right->nim ?? '' }}</td>
                        <td style="text-align: left;">{{ $right->nama ?? '' }}</td>
                        <td></td>
                    </tr>
                @endfor
            @endforeach
        </tbody>
    </table>

    <div class="signature-area">
        <div class="signature-row">
            <div class="signature-col">
                Semarang, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}
            </div>
        </div>
        <div class="ketua-sidang">
            <p style="margin-bottom: 0;">Ketua Sidang,</p>
            <div class="signature-line"></div>
        </div>
    </div>

    <div class="footer">
        Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>
