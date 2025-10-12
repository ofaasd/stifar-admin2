<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Persetujuan Seminar Proposal</title>
    <style>
        /* Gaya Dasar untuk PDF */
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            font-size: 12pt;
        }
        body, .form-content, .form-title, .approval-text, .signature-date, td {
            text-align: justify !important;
        }

        /* Kop Surat */
        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header-container {
            border-bottom: 5px solid black;
            padding-bottom: 10px;
        }

        .logo-cell {
            width: 15%;
            text-align: center;
            vertical-align: top;
            padding-right: 15px;
        }

        .logo-img {
            max-width: 100px;
            height: auto;
        }

        .text-cell {
            width: 85%;
            text-align: center;
        }

        .text-cell h1 {
            font-size: 18pt;
            margin: 0;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .text-cell h2 {
            font-size: 16pt;
            margin: 5px 0 10px;
            line-height: 1.2;
        }

        .text-cell p {
            font-size: 9pt;
            margin: 0;
            line-height: 1.5;
        }
        
        .contact-info p {
             font-size: 9pt;
             margin-top: 5px;
        }

        /* Judul Formulir */
        .form-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 25px 0;
            margin-bottom: 46px
        }

        /* Konten Formulir */
        .form-content {
            font-size: 12pt;
            line-height: 1.8;
            width: 100%;
        }

        .data-row {
            margin-bottom: 5px;
            display: flex;
        }
        
        .data-label {
            width: 100px;
            font-weight: normal;
            flex-shrink: 0;
        }

        .data-value {
            flex-grow: 1;
            min-height: 1em;
            margin-left: 5px;
        }

        .judul-multiline {
            border-bottom: 1px dashed black;
            min-height: 1em;
        }

        .approval-text {
            margin-top: 140px;
            margin-bottom: 30px;
        }

        .signature-date {
            text-align: right;
            margin-right: 50px;
            margin-bottom: 60px;
        }
        
        .signature-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 40px;
        }
        .signature-col {
            width: 40%;
            text-align: center;
            font-size: 12pt;
        }
        .signature-col:first-child {
            align-items: flex-start;
            text-align: left;
        }
        .signature-col:last-child {
            align-items: flex-end;
            text-align: right;
        }

        .signature-line {
            display: inline-block;
            border-bottom: 1px solid black;
            width: 80%;
            margin-top: 70px;
        }

        .signature-name {
            margin-top: 5px;
            font-weight: bold;
            text-decoration: underline;
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
        <img src="{{ public_path('assets/images/skripsi/header-persetujuan.png') }}" alt="Header Persetujuan" style="width: 100%; height: auto;"/>
    </div>
    
    <div class="form-title">
        FORMULIR PERSETUJUAN SEMINAR PROPOSAL
    </div>

    <div class="form-content">
        <div class="data-row">
            <span class="data-label">Nama</span>
            <span class="data-value">: {{ $sidang->nama }}</span>
        </div>
        
        <div class="data-row">
            <span class="data-label">NIM</span>
            <span class="data-value">: {{ $sidang->nim }}</span>
        </div>
        
        <div class="data-row">
            <span class="data-label">Judul:</span>
            <div style="flex-grow: 1;">
                <div class="judul-multiline">: {{ $sidang->judul }}</div>
            </div>
        </div>
        
        <p style="margin-top: 30px; margin-bottom: 30px; font-style: italic; color: #333;">
            Halaman ini dicetak sebagai bukti bahwa proposal telah disetujui oleh pembimbing untuk diajukan pada seminar proposal.
        </p>

        <div class="signature-date">
            Semarang, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}
        </div>

        <table style="width:100%;">
            <tr>
                <td style="width:50%; text-align:left; vertical-align:top;">
                    Pembimbing I,<br>
                    <span style="display:inline-block; border-bottom:1px solid #000; width:80%;"></span><br>
                    <span style="font-weight:bold; text-decoration:underline;">{{ $sidang->namaPembimbing1 }}</span>
                </td>
                <td style="width:50%; text-align:right; vertical-align:top;">
                    Pembimbing II,<br>
                    <span style="display:inline-block; border-bottom:1px solid #000; width:80%;"></span><br>
                    <span style="font-weight:bold; text-decoration:underline;">{{ $sidang->namaPembimbing2 }}</span>
                </td>
            </tr>
        </table>
        <div class="footer">
            Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

</body>
</html>