<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pendaftaran Sidang Hasil</title>
    <style>
        /* --- KONFIGURASI HALAMAN A4 --- */
        @page {
            size: A4;
            margin: 2cm 2.5cm; /* Margin standar */
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.5; /* Spasi sedikit lebih longgar untuk form isian */
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
            line-height: 1.3;
        }
        .text-cell h1 { font-size: 14pt; margin: 0; font-weight: bold; }
        .text-cell h2 { font-size: 12pt; margin: 2px 0; font-weight: bold; }
        .text-cell p { font-size: 9pt; margin: 1px 0; }

        /* --- JUDUL --- */
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            margin: 20px 0 30px 0;
            text-decoration: underline;
            text-transform: uppercase;
        }

        /* --- FORMULIR ISIAN --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            padding: 8px 0; /* Jarak antar baris isian */
        }
        .label-col { width: 25%; }
        .sep-col { width: 2%; }
        .val-col { width: 73%; }

        /* --- TANDA TANGAN --- */
        .signature-section {
            width: 100%;
            margin-top: 50px;
        }
        .sig-container {
            float: right;
            width: 40%;
            text-align: center;
            line-height: 1.3;
        }
        .sig-space {
            height: 70px;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
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

    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('assets/images/logo-stifar.png') }}" class="logo-img" alt="Logo">
            </td>
            <td class="text-cell">
                <h1>SEKOLAH TINGGI ILMU FARMASI YAYASAN PHARMASI SEMARANG</h1>
                <h2>PROGRAM STUDI STRATA 1 (S-1) FARMASI</h2>
                <p>Jalan Letnan Jendral Sarwo Edie Wibowo Km. 1 Plamongansari - Pucanggading - Semarang - 50193</p>
                <p>Telepon : 024 - 6706147 ; 6725272 ; Faksimile : 024 - 6706148</p>
                <p>Email : stifar_yaphar@yahoo.com | Website : www.stifar.ac.id</p>
            </td>
        </tr>
    </table>

    <div class="doc-title">FORMULIR PENDAFTARAN SIDANG HASIL</div>

    <table class="info-table">
        <tr>
            <td class="label-col">Nama</td>
            <td class="sep-col">:</td>
            <td class="val-col">...........................................................................................</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>...........................................................................................</td>
        </tr>
        <tr>
            <td>Judul Skripsi</td>
            <td>:</td>
            <td>...........................................................................................<br><br>...........................................................................................<br><br>...........................................................................................</td>
        </tr>
        <tr>
            <td>Dosen Pembimbing 1</td>
            <td>:</td>
            <td>...........................................................................................</td>
        </tr>
        <tr>
            <td>Dosen Pembimbing 2</td>
            <td>:</td>
            <td>...........................................................................................</td>
        </tr>
    </table>

    <div class="signature-section clearfix">
        <div class="sig-container">
            <div style="margin-bottom: 5px;">Semarang, ...................................</div>
            <div style="margin-bottom: 5px;">Mahasiswa,</div>
            <div class="sig-space"></div>
            <div class="sig-name">( ............................................ )</div>
            <div>NIM. .....................................</div>
        </div>
    </div>

</body>
</html>