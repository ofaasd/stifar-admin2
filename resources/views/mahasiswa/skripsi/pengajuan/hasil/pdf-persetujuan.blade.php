<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Persetujuan Sidang Hasil</title>
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

        /* --- JUDUL --- */
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            margin: 20px 0 30px 0;
            text-transform: uppercase;
        }

        /* --- FORMULIR ISIAN --- */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            padding: 5px 0;
        }
        .label-col { width: 15%; }
        .sep-col { width: 2%; }
        .val-col { width: 83%; }

        /* --- PARAGRAF PERNYATAAN --- */
        .statement-text {
            margin-bottom: 20px;
            text-align: justify;
        }

        /* --- TANDA TANGAN --- */
        .signature-section {
            width: 100%;
            margin-top: 40px;
        }
        .date-line {
            text-align: right;
            margin-bottom: 10px;
            padding-right: 24px;
        }
        .sig-table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
        }
        .sig-table td {
            vertical-align: top;
            width: 50%;
            padding-bottom: 60px; /* Ruang tanda tangan */
        }
        .sig-role { margin-bottom: 5px; }
        
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

    <div class="doc-title">FORMULIR PERSETUJUAN SIDANG HASIL</div>

    <table class="info-table">
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

    <div class="statement-text">
        Hasil dengan judul diatas <span style="font-weight:bold; text-decoration:underline">telah disetujui</span> untuk diujikan pada sidang hasil.
    </div>

    <div class="signature-section">
        <div class="date-line">
            Semarang, {{ $formattedSidang }}
        </div>

        <table class="sig-table">
            <tr>
                <td>
                    <div class="sig-role">Pembimbing I,</div>
                </td>
                <td>
                    <div class="sig-role">Pembimbing II,</div>
                </td>
            </tr>
            <tr>
                </tr>
            <tr>
                <td>
                    <div class="sig-name">{{ $sidang->namaPembimbing1 }}</div>
                    <div>NIY. {{ $sidang->pembimbing_1 }}</div>
                </td>
                <td>
                    <div class="sig-name">{{ $sidang->namaPembimbing2 }}</div>
                    <div>NIY. {{ $sidang->pembimbing_2 }}</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>