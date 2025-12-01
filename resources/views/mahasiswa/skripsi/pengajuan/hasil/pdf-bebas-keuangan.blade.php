<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Bebas Keuangan</title>
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
        .text-cell h1 { font-size: 14pt; margin: 0; font-weight: bold; }
        .text-cell h2 { font-size: 12pt; margin: 2px 0; font-weight: bold; }
        .text-cell p { font-size: 9pt; margin: 1px 0; }

        /* --- JUDUL --- */
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            margin: 20px 0 25px 0;
            text-decoration: underline;
            text-transform: uppercase;
        }

        /* --- INFORMASI & FORMULIR --- */
        .content-text {
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            vertical-align: top;
            padding: 3px 0;
        }
        /* Lebar kolom disesuaikan untuk formulir ini */
        .label-col { width: 20%; }
        .sep-col { width: 2%; }
        .val-col { width: 78%; }

        /* --- TABEL KEUANGAN --- */
        .finance-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .finance-table th, .finance-table td {
            border: 1px solid black;
            padding: 10px;
            vertical-align: top;
        }
        .finance-table th {
            text-align: center;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        /* --- TANDA TANGAN --- */
        .signature-section {
            width: 100%;
            margin-top: 40px;
        }
        .sig-container {
            float: right;
            width: 50%; /* Sedikit lebih lebar karena jabatan panjang */
            text-align: center;
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

    <div class="doc-title">SURAT KETERANGAN BEBAS KEUANGAN</div>

    <div class="content-text">Yang bertanda tangan dibawah ini adalah :</div>
    <table class="info-table">
        <tr>
            <td class="label-col">Nama</td>
            <td class="sep-col">:</td>
            <td class="val-col">...........................................................................................</td>
        </tr>
        <tr>
            <td>NIY</td>
            <td>:</td>
            <td>...........................................................................................</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td class="val-col">
                Puket II bid. Administrasi Umum dan Keuangan<br>
                Stifar Yayasan Pharmasi Semarang
            </td>
        </tr>
    </table>

    <div class="content-text">Menerangkan bahwa mahasiswa :</div>
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
            <td>Progdi</td>
            <td>:</td>
            <td>...........................................................................................</td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td>:</td>
            <td>...........................................................................................</td>
        </tr>
    </table>

    <div class="content-text">Telah memenuhi ketentuan sebagai berikut:</div>

    <table class="finance-table">
        <thead>
            <tr>
                <th width="60%">Keterangan</th>
                <th width="40%">Tanda Tangan & Cap</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding-bottom: 50px;">
                    <div style="margin-bottom: 10px;">Telah melunasi semua biaya :</div>
                    <div style="padding-left: 20px;">1. UPP</div>
                    <div style="padding-left: 20px;">2. DPP</div>
                </td>
                <td style="text-align: center;">
                    <div style="margin-bottom: 60px;">Adm. Keuangan</div>
                    <div>( ............................................ )</div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="content-text">Guna memenuhi persyaratan mendaftar Sidang Hasil.</div>

    <div class="signature-section clearfix">
        <div class="sig-container">
            <div style="margin-bottom: 5px;">Semarang, ...................................</div>
            <div style="margin-bottom: 5px;">Puket II bid. Administrasi Umum dan Keuangan,</div>
            <div class="sig-space"></div>
            <div class="sig-name">( Nama Puket II )</div>
            <div>NIY. .....................................</div>
        </div>
    </div>

</body>
</html>