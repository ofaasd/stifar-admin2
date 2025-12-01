<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Rekap Nilai Seminar Hasil</title>
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

        .logo-img {
            width: 80px;
            height: auto;
        }

        /* --- JUDUL --- */
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            margin: 15px 0 20px 0;
            text-transform: uppercase;
        }

        /* --- IDENTITAS MAHASISWA --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            padding: 3px 0;
        }
        .label-col { width: 18%; }
        .sep-col { width: 2%; }
        .val-col { width: 80%; }

        /* --- TABEL REKAP NILAI --- */
        .recap-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .recap-table th, .recap-table td {
            border: 1px solid black;
            padding: 8px;
        }
        .recap-table th {
            text-align: center;
            background-color: #f9f9f9;
        }
        .col-no { width: 5%; text-align: center; }
        .col-penguji { width: 40%; }
        .col-nilai { width: 15%; text-align: center; }
        .col-ttd { width: 15%; text-align: center; }
        
        .text-bold {
            font-weight: bold;
        }

        .footer-row td {
            font-weight: bold;
        }

        /* --- TANDA TANGAN --- */
        .signature-section {
            width: 100%;
            margin-top: 30px;
        }
        .sig-container {
            float: right;
            width: 40%;
            text-align: center;
        }
        .sig-space {
            height: 70px !important;
        }

        .sig-name {
            text-align: center
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

    <div class="doc-title">LEMBAR REKAP NILAI SEMINAR HASIL</div>

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
            <td>Judul Skripsi</td>
            <td>:</td>
            <td>{{ $sidang->judul }}</td>
        </tr>
    </table>

    <table class="recap-table">
        <thead>
            <tr>
                <th class="col-no" rowspan="2">No</th>
                <th class="col-penguji" rowspan="2">Nama Penguji</th>
                <th colspan="2" class="col-nilai">Nilai Sidang Hasil</th>
            </tr>
            <tr>
                <th class="col-nilai">Nilai</th>
                <th class="col-ttd">TTD</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 1; $i <= $sidang->jmlPenguji; $i++)
                <tr>
                    <td class="col-no">{{ $i }}</td>
                    <td>{{ $sidang->{'namaPenguji' . $i} ?? '-' }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total Nilai</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">Nilai Rata-rata</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2" class="text-bold">Angka Mutu</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top;">
                    <p class="text-bold">Keterangan Sidang Hasil (Angka mutu)</p>
                    (jika rerata total nilai sidang hasil : > 76 = A; 71-76 = AB; 66-70 = B; < 70 = K)
                </td>
                <td colspan="2" style="vertical-align: top;">
                    <div class="signature-section clearfix">
                        <div class="sig-container" style="width:60%;">
                            <div style="margin-bottom: 5px; padding-right: 24px;">Semarang, {{ $formattedSidang }}</div>
                            <div>Ketua Sidang,</div>
                            <img src="{{ public_path('assets/images/blank-image.png') }}" alt="header pdf" style="width: 100%; height: 20%;"/>
                            <div class="sig-name">{{ $sidang->namaPenguji1 }}</div>
                        </div>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>