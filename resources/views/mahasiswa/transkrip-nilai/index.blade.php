<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transkrip Nilai - {{ $data->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            max-width: 220mm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 4px;
        }

        .header-top {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: -3px;
        }
        
        .header-bot {
            margin-top: -3px;
            font-size: 12px;
            font-style: italic;
        }

        .decree-info {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .decree-info p {
            margin-bottom: 3px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 10px;
        }

        .info-table td {
            vertical-align: top;
        }

        .info-table td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .label {
            font-weight: bold;
        }

        .bottom-table {
            width: 100%;
            font-size: 10px;
        }

        .bottom-table th,
        .bottom-table td {
            text-align: center;
        }

        .grades-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
            border: 1px solid;
        }

        .grades-table th {
            font-weight: bold;
            text-align: center;
            border: 1px solid;
            font-size: 9px;
        }

        .grades-table .body-2 tr:nth-child(1) {
            border-bottom: 1px solid white;
        }

        .grades-table .body-1 td {
            padding: 2px;
            text-align: left;
            border-right: 1px solid;
            vertical-align: top;
        }

        .grades-table th:nth-child(3),
        .grades-table td.body-1:nth-child(3),
        .grades-table .body-1 td:nth-child(3) {
            border-right: 1px solid white;
        }

        /* .grades-table th:first-child,
        .grades-table th:nth-child(1),
        .grades-table th:nth-child(2),
        .grades-table td:first-child,
        .grades-table td:nth-child(1),
        .grades-table td:nth-child(2){
            border-right: 1px solid;
        } */

        /* .grades-table th:nth-child(5),
        .grades-table th:nth-child(6),
        .grades-table th:nth-child(7),
        .grades-table th:nth-child(8),
        .grades-table td:nth-child(5),
        .grades-table td:nth-child(6),
        .grades-table td:nth-child(7),
        .grades-table td:nth-child(8) {
            border-left: 1px solid;
        } */

        .grades-table td:first-child,
        .grades-table td:nth-child(1),
        .grades-table td:nth-child(5),
        .grades-table td:nth-child(6),
        .grades-table td:nth-child(7),
        .grades-table td:nth-child(8) {
            text-align: center;
        }

        .grades-table .body-1 .course-name {
            font-size: 9px;
            line-height: 1.3;
        }

        .grades-table .body-1 .course-name em {
            font-style: italic;
            font-size: 8px;
        }

        .english {
            font-style: italic;
        }

        .summary {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .summary p {
            margin-bottom: 8px;
        }

        .grade-legend {
            margin-bottom: 20px;
            font-size: 10px;
        }

        .grade-legend p {
            margin-bottom: 3px;
        }

        .signatures {
            display: table;
            width: 100%;
            font-size: 11px;
        }

        .signature-row {
            display: table-row;
        }

        .signature-cell {
            display: table-cell;
            text-align: center;
            width: 50%;
            vertical-align: top;
        }

        .signature-cell:first-child {
            padding-right: 20px;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .signature-id {
            font-size: 10px;
        }

        @media print {
            body {
                font-size: 11px;
            }
            
            .grades-table {
                font-size: 9px;
            }
            
            .grades-table th {
                font-size: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Updated header structure to match original with underlined text -->
    <div class="header">
        <p class="header-top">TRANSKRIP NILAI AKADEMIK</p>
        <p class="header-bot">TRANSCRIPT OF ACADEMIC RECORD</p>
    </div>

    <!-- Converted info section to table format for better alignment -->
    <table class="info-table">
        <tbody>
            <tr>
                <td>Nomor Surat Keputusan Mendiknas Republik Indonesia</td>
                <td>:</td>
                <td>{{ $nomorSk }}</td>
            </tr>
            <tr>
                <td class="english">The Minister of National Education of the Republic of Indonesia Number</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Program Studi / <span class="english">Study Program</span></td>
                <td>:</td>
                <td>{{ $data->programStudi }}</td>
            </tr>
            <tr>
                <td>Jenjang / <span class="english">Stratum</span></td>
                <td>:</td>
                <td>
                    @if($data->jenjang == 'S2')
                        Magister (S-2)
                    @elseif($data->jenjang == 'S1')
                        Sarjana (S-1)
                    @elseif($data->jenjang == 'DIII')
                        Diploma (D-III)
                    @else
                        {{ $data->jenjang }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Gelar / <span class="english">Degree</span></td>
                <td>:</td>
                <td>{{ $data->gelar }}</td>
            </tr>
            <tr>
                <td>Nomor Seri Transkrip / <span class="english">Transcript Serial Number</span></td>
                <td>:</td>
                <td>{{ $nomorSeri }}</td>
            </tr>
            <tr>
                <td>Kode Perguruan Tinggi / <span class="english">School Code</span></td>
                <td>:</td>
                <td>{{ $dataKampus->kode }}</td>
            </tr>
            <tr>
                <td>Nama / <span class="english">Name</span></td>
                <td>:</td>
                <td>{{ $data->nama }}</td>
            </tr>
            <tr>
                <td>NIM / <span class="english">Student Registration Number</span></td>
                <td>:</td>
                <td>{{ $data->nim }}</td>
            </tr>
            <tr>
                <td>Tempat dan Tanggal Lahir / <span class="english">Place and Date of Birth</span></td>
                <td>:</td>
                <td>{{ $data->tempatLahir }}, {{ $data->tglLahir }}</td>
            </tr>
            <tr>
                <td>Tanggal Lulus / <span class="english">Date of Graduation</span></td>
                <td>:</td>
                <td>{{ $data->tanggalLulus }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Updated table structure with proper borders and spacing -->
    <table class="grades-table">
        <thead>
            <tr>
                <th>NO</th>
                <th>KODE<br><span class="english">CODE</span></th>
                <th>MATA KULIAH</th>
                <th class="english">COURSE</th>
                <th>SKS<br><span class="english">CREDIT</span></th>
                <th>NILAI<br><span class="english">GRADE</span></th>
                <th>MUTU<br><span class="english">GQ</span></th>
                <th>BOBOT<br><span class="english">CXGQ</span></th>
            </tr>
        </thead>
        <tbody class="body-1">
            @foreach ($data->mataKuliah as $row )
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['kodeMatkul'] }}</td>
                    <td class="course-name">{{ $row['namaMataKuliah'] ?? 'data tidak ditemukan' }}</td>
                    <td><em>{{ $row['namaMataKuliahEng'] ?? 'data tidak ditemukan' }}</em></td>
                    <td>{{ $row['totalSks'] }}</td>
                    <td>{{ $row['nilai'] }}</td>
                    <td>{{ $row['mutu'] }}</td>
                    <td>{{ $row['bobot'] }}</td>
                </tr>
            @endforeach
        </tbody>
        
        <tbody>
             <tr style="border: 1px solid">
                <td></td>
                <td></td>
                <td>SKS Kumulatif</td>
                <td style="border-right:1px solid"><em class="english">Total Credit</em></td>
                <td style="border-right:1px solid">{{ $data->totalSks }}</td>
                <td style="border-right:1px solid"></td>
                <td style="border-right:1px solid">{{ $data->totalMutu }}</td>
                <td>{{ $data->totalBobot }}</td>
            </tr>
        </tbody>

        <tbody class="body-2">
            <tr>
                <td></td>
                <td></td>
                <td>IPK Kumulatif</td>
                <td><em class="english">Grade Point Average</em> : {{ $data->ipk }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Predikat</td>
                <td>
                    <span class="english">Qualification</span>
                    <span style="color: white">------------</span>:
                    @if($data->ipk >= 3.51 && $data->ipk <= 4.00)
                        Dengan Pujian / <span class="english">Cumlaude</span>
                    @elseif($data->ipk >= 2.76 && $data->ipk <= 3.50)
                        Sangat Memuaskan / <span class="english">Very Satisfying</span>
                    @elseif($data->ipk >= 2.00 && $data->ipk <= 2.75)
                        Memuaskan / <span class="english">Satisfying</span>
                    @else
                        -
                    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align:left; padding-left: 12px; padding-right: 12px; padding-bottom: 12px; border-top:1px solid">
                    <p>JUDUL TESIS / <span class="english">Title of Scientific Paper</span> :</p>
                    <p>{{ $data->judul }}</p>
                    <p class="english">{{ $data->judulEng }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="grade-legend">
        <p><span class="label">KETERANGAN</span> : A : Istimewa/<span class="english">Excellent</span>; AB : Sangat Baik/<span class="english">Very Good</span>; B : Baik/<span class="english">Good</span>; BC : Baik/<span class="english">Good</span>; C : Cukup/<span class="english">Fair</span>; CD : Cukup/<span class="english">Fair</span>; D : Kurang/<span class="english">Poor</span>; E : Sangat Kurang/<span class="english">Very Poor</span></p>
    </div>

    <table class="bottom-table">
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>Semarang, {{ \Carbon\Carbon::parse($printedAt)->translatedFormat('d F Y') }} <br><em>Semarang, {{ \Carbon\Carbon::parse($printedAt)->format('F j') }}<sup>{{ date('S', strtotime($printedAt)) }}</sup>, {{ \Carbon\Carbon::parse($printedAt)->format('Y') ?? '-' }}</em></td>
            </tr>
            <tr>
                <td>Ketua Program Studi</td>
                <td></td>
                <td>Ketua,</td>
            </tr>
            <tr>
                <td><span class="english">Head of Study Program,</span></td>
                <td></td>
                <td><span class="english">Director,</span></td>
            </tr>
            {{-- Bingkai --}}
            <tr>
                <td></td>
                <td>
                    @php
                        function imageExists($path) {
                            return file_exists(public_path($path));
                        }
                        $fotoYudisiumPath = 'assets/images/mahasiswa/foto-yudisium/' . ($data->fotoYudisium ?? '');
                        $fotoMhsPath = 'assets/images/mahasiswa/' . ($data->fotoMhs ?? '');
                        $framePath = 'assets/images/mahasiswa/ijazah/frame-3x4.png';
                    @endphp

                    @if(!empty($data->fotoYudisium) && imageExists($fotoYudisiumPath))
                        <img src="{{ asset($fotoYudisiumPath) }}" alt="Foto Yudisium" style="position: relative; height:80px; width:auto; max-width:90px; object-fit:cover; border-radius:6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    @elseif(!empty($data->fotoMhs) && imageExists($fotoMhsPath))
                        <img src="{{ asset($fotoMhsPath) }}" alt="Foto Mahasiswa" style="position: relative; height:80px; width:auto; max-width:90px; object-fit:cover; border-radius:6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    @else
                        <img src="{{ asset($framePath) }}" alt="Foto frame pas foto" style="position: relative; height:80px; width:auto; max-width:90px; object-fit:cover; border-radius:6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    @endif
                </td>
                <td></td>
            </tr>
            {{-- /Bingkai --}}
            <tr>
                <td>{{ $data->namaKaprodi ?? 'data tidak ditemukan' }}</td>
                <td></td> 
                <td>{{ $data->namaKetua ?? 'data tidak ditemukan' }}</td>
            </tr>
            <tr>
                <td>NIY. YP. {{ $data->nppKaprodi ?? 'data tidak ditemukan' }}</td>
                <td></td> 
                <td>NIY. YP. {{ $data->nppKetua ?? 'data tidak ditemukan' }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
