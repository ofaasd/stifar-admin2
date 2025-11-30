<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Nilai Sidang Hasil</title>
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
            margin: 15px 0 25px 0;
            text-transform: uppercase;
        }

        /* --- IDENTITAS --- */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            padding: 3px 0;
        }
        .label-col { width: 18%; }
        .sep-col { width: 2%; }
        .val-col { width: 80%; }

        /* --- TABEL PENILAIAN --- */
        .grade-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .grade-table th, .grade-table td {
            padding: 5px 8px;
            vertical-align: middle;
        }
        .grade-table th {
            text-align: center;
        }
        
        /* Lebar kolom */
        .col-aspek { width: 20%; font-weight: bold; }
        .col-kriteria { width: 50%; }
        .col-rentang { width: 15%; text-align: center; }
        .col-nilai { width: 15%; text-align: center; }

        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }

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
            height: 70px;
        }
        
        /* Helper Clearfix */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Page break for PDF rendering */
        .page-break {
            page-break-before: always; /* For older engines */
            break-before: page;        /* For modern browsers / pdf generators */
        }
    </style>
</head>
<body>

    @for ($i = 1; $i <= $sidang->jmlPenguji; $i++)
        <div class="{{ $i > 1 ? 'page-break' : '' }}">
            <div class="header">
                <img src="{{ public_path('assets/images/skripsi/header-pdf.png') }}" alt="header pdf" style="width: 100%; height: auto;"/>
            </div>

            <div class="doc-title">LEMBAR NILAI SIDANG HASIL</div>

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
                <tr>
                    <td>Penguji</td>
                    <td>:</td>
                    <td>{{ $sidang->{'namaPenguji' . $i} ?? '-' }}</td>
                </tr>
            </table>

            <div style="font-weight: bold; margin-bottom: 10px; text-align: center;">NILAI SIDANG HASIL</div>
            
            <table class="grade-table">
                <thead>
                    <tr>
                        <th colspan="2"></th>
                        <th class="col-rentang">Rentang Nilai</th>
                        <th class="col-nilai">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="2" class="col-aspek">Penulisan (15)</td>
                        <td>: Kesinambungan penulisan dan bahasa</td>
                        <td class="text-center">5 - 8</td>
                        <td>....</td>
                    </tr>
                    <tr>
                        <td>: Kesesuaian isi dengan daftar pustaka</td>
                        <td class="text-center">5 - 7</td>
                        <td>....</td>
                    </tr>

                    <tr>
                        <td rowspan="3" class="col-aspek">Isi (30)</td>
                        <td>: Keterbaruan penelitian</td>
                        <td class="text-center">8 - 10</td>
                        <td>....</td>
                    </tr>
                    <tr>
                        <td>: Kejelasan rumusan masalah</td>
                        <td class="text-center">8 - 10</td>
                        <td>....</td>
                    </tr>
                    <tr>
                        <td>: Relevansi antara latar belakang, rumusan masalah, dan metodologi penelitian</td>
                        <td class="text-center">8 - 10</td>
                        <td>....</td>
                    </tr>

                    <tr>
                        <td class="col-aspek">Presentasi (10)</td>
                        <td>: Penampilan dan sikap selama tanya jawab</td>
                        <td class="text-center">5 - 10</td>
                        <td>....</td>
                    </tr>

                    <tr>
                        <td rowspan="2" class="col-aspek">Tanya Jawab (20)</td>
                        <td>: Kemampuan menyampaikan argumentasi</td>
                        <td class="text-center">8 - 10</td>
                        <td>....</td>
                    </tr>
                    <tr>
                        <td>: Kesesuaian antara jawaban dengan pertanyaan</td>
                        <td class="text-center">8 - 10</td>
                        <td>....</td>
                    </tr>

                    <tr>
                        <td class="col-aspek">Pengetahuan (25)</td>
                        <td>: Kedalaman penguasaan materi</td>
                        <td class="text-center">15 - 25</td>
                        <td>....</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-bold" style="text-align: right; padding-right: 15px;">Jumlah</td>
                        <td class="text-center">(70 - 100)</td>
                        <td>....</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-bold" style="text-align: right; padding-right: 15px;">Nilai Akhir Sidang Hasil</td>
                        <td>....</td>
                    </tr>
                </tfoot>
            </table>

            <div class="signature-section clearfix">
                <div class="sig-container">
                    <div style="margin-bottom: 5px;">Semarang, {{ $formattedSidang }}</div>
                    <div>Penguji,</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ $sidang->{'namaPenguji' . $i} ?? '-' }}</div>
                </div>
            </div>
        </div>
    @endfor
</body>
</html>