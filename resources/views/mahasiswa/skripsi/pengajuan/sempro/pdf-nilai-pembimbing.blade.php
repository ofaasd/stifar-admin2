<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penilaian Dosen Pembimbing</title>
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
            margin-bottom: 25px;
        }
        .grade-table th, .grade-table td {
            padding: 6px 8px; /* Padding sedikit lebih kecil agar hemat tempat */
            vertical-align: middle;
        }
        .grade-table th {
            text-align: center;
        }
        
        /* Lebar Kolom */
        .col-kategori { width: 20%; vertical-align: top; }
        .col-indikator { width: 50%; }
        .col-rentang { width: 15%; text-align: center; }
        .col-nilai { width: 15%; text-align: center; }

        .sub-header {
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center; /* Sesuai dokumen "NILAI PROSES..." di tengah */
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

    {{-- pembimbing 1 --}}
    <div>
        <div class="header">
            <img src="{{ public_path('assets/images/skripsi/header-pdf.png') }}" alt="header pdf" style="width: 100%; height: auto;"/>
        </div>

        <div class="doc-title">PENILAIAN DOSEN PEMBIMBING</div>

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
                <td>Nama Dosen</td>
                <td>:</td>
                <td>{{ $sidang->namaPembimbing1 }}</td>
            </tr>
        </table>

        <div class="sub-header">NILAI PROSES PENYUSUNAN PROPOSAL</div>

        <table class="grade-table">
            <thead>
                <tr>
                    <th colspan="2"></th> 
                    <th class="col-rentang">Nilai Rentang</th>
                    <th class="col-nilai">Nilai</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="2" class="col-kategori">Penulisan:</td>
                    <td>Konsistensi penulisan dan kesesuaian dengan aturan</td>
                    <td class="col-rentang">10 - 15</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td>Penelusuran pustaka</td>
                    <td class="col-rentang">10 - 15</td>
                    <td>....</td>
                </tr>

                <tr>
                    <td rowspan="2" class="col-kategori">Sikap:</td>
                    <td>Kontribusi dan keterlibatan ide</td>
                    <td class="col-rentang">10 - 15</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td>Kontinuitas dan ketekunan</td>
                    <td class="col-rentang">10 - 15</td>
                    <td>....</td>
                </tr>

                <tr>
                    <td rowspan="2" class="col-kategori">Kedalaman Materi:</td>
                    <td>Penguasaan materi</td>
                    <td class="col-rentang">15 - 20</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td>Kemampuan menemukan relevansi antara latar belakang, rumusan masalah, dan metodologi penelitian</td>
                    <td class="col-rentang">15 - 20</td>
                    <td>....</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right; padding-right: 15px; font-weight: bold;">Jumlah</td>
                    <td style="text-align: center;">(70 - 100)</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; padding-right: 15px; font-weight: bold;">Nilai Akhir proses penyusunan proposal <span style="font-weight:normal;">(30%)</span></td>
                    <td>....</td>
                </tr>
            </tfoot>
        </table>

        <div class="signature-section clearfix">
            <div class="sig-container">
                <div style="margin-bottom: 5px;">Semarang, {{ $formattedSidang }}</div>
                <div>Pembimbing I</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $sidang->namaPembimbing1 }}</div>
            </div>
        </div>
    </div>

    <div style="page-break-after: always;"></div>

    {{-- pembimbing 2 --}}
    <div>
        <div class="header">
            <img src="{{ public_path('assets/images/skripsi/header-pdf.png') }}" alt="header pdf" style="width: 100%; height: auto;"/>
        </div>

        <div class="doc-title">PENILAIAN DOSEN PEMBIMBING</div>

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
                <td>Nama Dosen</td>
                <td>:</td>
                <td>{{ $sidang->namaPembimbing2 }}</td>
            </tr>
        </table>

        <div class="sub-header">NILAI PROSES PENYUSUNAN PROPOSAL</div>

        <table class="grade-table">
            <thead>
                <tr>
                    <th colspan="2">Aspek Penilaian</th> <th class="col-rentang">Nilai Rentang</th>
                    <th class="col-nilai">Nilai</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="2" class="col-kategori">Penulisan:</td>
                    <td>Konsistensi penulisan dan kesesuaian dengan aturan</td>
                    <td class="col-rentang">10 - 15</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td>Penelusuran pustaka</td>
                    <td class="col-rentang">10 - 15</td>
                    <td>....</td>
                </tr>

                <tr>
                    <td rowspan="2" class="col-kategori">Sikap:</td>
                    <td>Kontribusi dan keterlibatan ide</td>
                    <td class="col-rentang">10 - 15</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td>Kontinuitas dan ketekunan</td>
                    <td class="col-rentang">10 - 15</td>
                    <td>....</td>
                </tr>

                <tr>
                    <td rowspan="2" class="col-kategori">Kedalaman Materi:</td>
                    <td>Penguasaan materi</td>
                    <td class="col-rentang">15 - 20</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td>Kemampuan menemukan relevansi antara latar belakang, rumusan masalah, dan metodologi penelitian</td>
                    <td class="col-rentang">15 - 20</td>
                    <td>....</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right; padding-right: 15px; font-weight: bold;">Jumlah</td>
                    <td style="text-align: center;">(70 - 100)</td>
                    <td>....</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; padding-right: 15px; font-weight: bold;">Nilai Akhir proses penyusunan proposal <span style="font-weight:normal;">(30%)</span></td>
                    <td>....</td>
                </tr>
            </tfoot>
        </table>

        <div class="signature-section clearfix">
            <div class="sig-container">
                <div style="margin-bottom: 5px;">Semarang, {{ $formattedSidang }}</div>
                <div>Pembimbing II</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $sidang->namaPembimbing2 }}</div>
            </div>
        </div>
    </div>

</body>
</html>