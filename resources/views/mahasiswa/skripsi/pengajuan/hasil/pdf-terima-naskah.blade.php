<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanda Terima Naskah</title>
    <style>

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }

        /* --- KOP SURAT --- */
        .header-table {
            width: 100%;
            border-bottom: 3px double black;
            margin-bottom: 20px;
            padding-bottom: 5px;
        }
        .logo-cell {
            width: 12%;
            text-align: center;
            vertical-align: middle;
        }
        .logo-img {
            width: 80px;
            height: auto;
        }
        .text-cell {
            width: 88%;
            text-align: center;
        }
        .text-cell h1 { font-size: 14pt; margin: 0; }
        .text-cell h2 { font-size: 12pt; margin: 2px 0; }
        .text-cell p { font-size: 9pt; margin: 1px 0; }

        /* --- JUDUL --- */
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            margin: 20px 0 30px 0;
            text-transform: uppercase;
        }

        /* --- IDENTITAS MAHASISWA --- */
        .intro-text {
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            padding: 5px 0;
        }
        .label-col { width: 15%; }
        .sep-col { width: 2%; }
        .val-col { width: 83%; }

        /* --- TABEL TANDA TERIMA --- */
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .receipt-table th, .receipt-table td {
            padding: 10px;
            vertical-align: middle;
        }
        .receipt-table th {
            text-align: center;
            font-weight: bold;
        }
        
        /* Lebar Kolom Tabel */
        .col-no { width: 8%; text-align: center; }
        .col-dosen { width: 45%; }
        .col-tanggal { width: 25%; }
        .col-ttd { width: 25%; }

        /* --- TANDA TANGAN ADMIN --- */
        .signature-section {
            width: 100%;
            margin-top: 40px;
        }
        .sig-container {
            float: right;
            width: 40%;
            text-align: center;
        }
        .sig-space {
            height: 70px;
        }

        /* Helper Clearfix */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('assets/images/skripsi/header-pdf.png') }}" alt="header pdf" style="width: 100%; height: auto;"/>
    </div>

    <div class="doc-title">TANDA TERIMA NASKAH</div>

    <div class="intro-text">Telah terima naskah sidang hasil untuk ujian dari mahasiswa:</div> <table class="info-table">
        <tr>
            <td class="label-col">Nama</td>
            <td class="sep-col">:</td>
            <td class="val-col">{{ $sidang->nama }}</td> 
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $sidang->nim }}</td> 
        </tr>
        <tr>
            <td>Judul</td>
            <td>:</td>
            <td>{{ $sidang->judul }}</td> 
        </tr>
    </table>

    <table class="receipt-table">
        <thead>
            <tr>
                <th colspan="2">Dosen Penguji</th> <th>Tanggal</th> <th>Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $romawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV'];
            @endphp
            @for ($i = 1; $i <= $sidang->jmlPenguji; $i++)
                <tr>
                    <td class="col-no">{{ ($romawi[$i-1] ?? $i) . '.' }}</td>
                    <td class="col-dosen">{{ $sidang->{'namaPenguji' . $i} ?? '-' }}</td>
                    <td style="text-align: center">..........</td>
                    <td style="text-align: center">..........</td>
                </tr>
            @endfor
            <tr>
                <td class="col-no">III.</td> 
                <td class="col-dosen">{{ $sidang->namaPembimbing1 }}</td> 
                <td style="text-align: center">..........</td>
                <td style="text-align: center">..........</td>
            </tr>
            <tr>
                <td class="col-no">IV.</td> 
                <td class="col-dosen">{{ $sidang->namaPembimbing2 }}</td> 
                <td style="text-align: center">..........</td>
                <td style="text-align: center">..........</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section clearfix">
        <div class="sig-container">
            <div style="margin-bottom: 5px;">Semarang, {{ $formattedSidang }}</div> <div>Administrasi Skripsi,</div> <div class="sig-space"></div>
            <div class="sig-name"> ............................................ </div>
        </div>
    </div>

</body>
</html>