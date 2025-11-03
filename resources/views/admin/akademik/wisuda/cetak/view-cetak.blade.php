<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>{{ 'Wisuda-' . $gelombang->nama  }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 16px; line-height: 1.15; }
        .contai-portrait { width: 100%; }
        .header { text-align: center; margin-bottom: 1cm; }
        .report-title { font-size: 18pt; font-weight: bold; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        table { width: 100%; border-collapse: collapse; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        #table-2 tr, #table-2 td, #table-2 th { vertical-align: top; padding: 4px; font-size: 12px; border: 1px solid; }
        #table-2 td { padding: 4px; font-size: 12px;}
        .border-gede { border: 3px solid; }
        .header-biru { background-color: #c6c6c600; margin-top: 24px; }
        th { text-align: center; }
        .qr { height: 80px; }
        .vertical-middle { vertical-align: middle; }
        .text-printed { text-align: right; width: 100%; font-size: 12px; color: #6c757d; font-style: italic; }

    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td width="10%">
                <img src="{{ $logo }}" alt="logo-stifar" style="width: 100px;"/>
            </td>
            <td width="90%" style="padding-left: 30px;">
                <center>
                    <b>SEKOLAH TINGGI ILMU FARMASI SEMARANG</b>
                    <br>Alamat : Jl. Letnan Jendral Sarwo Edie Wibowo Km. 1, Plamongan Sari, Kec. Pedurungan, Kota Semarang
                    <br>Email : admin@sistifar.id
                    <br>Website : https://stifar.ac.id
                </center>
            </td>
        </tr>
    </table>
    <hr>
    <div class="contai-portrait">
        <section class="section-1">
            <div class="header mb-2">
                <div class="mt-4">
                    <table class="table table-borderless" style="font-size: 0.9rem; margin-top: 10px;">
                        <tr>
                            <td style="width: 180px;">Nama</td>
                            <td>: {{ $gelombang->nama }}</td>
                        </tr>
                        <tr>
                            <td>Tempat</td>
                            <td>: {{ $gelombang->tempat }}</td>
                        </tr>
                        <tr>
                            <td>Mulai Pendaftaran</td>
                            <td>: {{ \Carbon\Carbon::parse($gelombang->mulai_pendaftaran)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td>Selesai Pendaftaran</td>
                            <td>: {{ \Carbon\Carbon::parse($gelombang->selesai_pendaftaran)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Pemberkasan</td>
                            <td>: {{ \Carbon\Carbon::parse($gelombang->tanggal_pemberkasan)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Gladi</td>
                            <td>: {{ \Carbon\Carbon::parse($gelombang->tanggal_gladi)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td>Tarif Wisuda</td>
                            <td>: Rp{{ number_format($gelombang->tarif_wisuda, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Waktu Pelaksanaan</td>
                            <td>: {{ \Carbon\Carbon::parse($gelombang->waktu_pelaksanaan)->translatedFormat('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="table-container">
                <table border="1" id="table-2">
                    <thead>
                        <tr class="header-biru" style="text-align: center">
                            <th style="vertical-align: middle;">No.</th>
                            <th style="vertical-align: middle; width: 12%;">NIM</th>
                            <th style="vertical-align: middle;">Nama</th>
                            <th style="vertical-align: middle;">Program Studi</th>
                            <th style="vertical-align: middle;">Predikat</th>
                            <th style="vertical-align: middle;">Gelombang Yudisium</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr style="border: none">
                            <th colspan="7" class="text-printed">
                                {{ now()->translatedFormat('l, d F Y H:i') }} - {{ auth()->user()->name }}
                            </th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                <td style="vertical-align: middle;">{{ $row->nim ?? '-' }}</td>
                                <td style="vertical-align: middle;">{{ $row->nama ?? '-' }}</td>
                                <td style="vertical-align: middle;">{{ $row->prodi ?? '-' }}</td>
                                <td style="vertical-align: middle;">{{ $row->predikat ?? '-' }}</td>
                                <td style="vertical-align: middle;">{{ $row->gelombangYudisium ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
</html>
