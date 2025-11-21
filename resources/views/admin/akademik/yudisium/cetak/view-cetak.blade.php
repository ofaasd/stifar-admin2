<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- <title>{{ 'Yudisium-' . $gelombang->nama  }}</title> --}}
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 11px; line-height: 1.15; }
        .contai-portrait { width: 100%; }
        .header { text-align: center; margin-bottom: 1cm; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        table { width: 100%; border-collapse: collapse; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        #table-2 tr, #table-2 td, #table-2 th { vertical-align: top; padding: 4px; font-size: 11px; border: 1px solid; }
        #table-2 td { padding: 4px; font-size: 11px;}
        .border-gede { border: 3px solid; }
        .header-biru { background-color: #c6c6c600; margin-top: 24px; }
        th { text-align: center; }
        .qr { height: 80px; }
        .vertical-middle { vertical-align: middle; }
        .text-printed { text-align: right; width: 100%; font-size: 6px; color: #6c757d; font-style: italic; }

    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td style="width:90px; vertical-align:middle;">
                <img src="{{ $logo }}" alt="logo-stifar" style="width:80px; height:auto; display:block;">
            </td>
            <td style="vertical-align:middle; padding-left:10px;">
                <div style="text-align:center; padding:8px 0;">
                    <h1 style="font-size:22px; margin:0; font-weight:700; line-height:1.1;">Daftar {{ $gelombang->nama }} {{ $gelombang->nama_prodi }}</h1>
                </div>
            </td>
        </tr>
    </table>
    <hr>
    <div class="contai-portrait">
        <section class="section-1">
            <div class="table-container">
                <table border="1" id="table-2">
                    <thead>
                        <tr class="header-biru" style="text-align: center">
                            <th style="vertical-align: middle;">No</th>
                            <th style="vertical-align: middle;">NIM</th>
                            <th style="vertical-align: middle;">Nama</th>
                            <th style="vertical-align: middle;">SKS / IPK</th>
                            <th style="vertical-align: middle;">Nilai D / E</th>
                            <th style="vertical-align: middle;">Judul</th>
                            <th style="vertical-align: middle;">Pembimbing</th>
                            <th style="vertical-align: middle;">Penguji</th>
                            <th style="vertical-align: middle;">Sidang</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr style="border: none">
                            <th colspan="9" class="text-printed">
                                {{ now()->translatedFormat('l, d F Y H:i') }} - {{ auth()->user()->name }}
                            </th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td style="vertical-align: middle; text-align:center;">{{ $loop->iteration }}</td>
                                <td style="vertical-align: middle; text-align:center;">{{ $row->nim ?? '-' }}</td>
                                <td style="vertical-align: middle; text-align:center;">{{ $row->nama ?? '-' }}</td>
                                <td style="vertical-align: middle; text-align:center;">{{ $row->totalSks ?? '-' }} / {{ $row->ipk ?? '-' }} \</td>
                                <td style="vertical-align: middle; text-align:center;"></td>
                                <td style="vertical-align: middle; text-align:justify;">{{ $row->judul ?? '-' }}</td>
                                <td style="vertical-align: middle;">1. {{ $row->pembimbing1 ?? '-' }}<br> 2. {{ $row->pembimbing2 ?? '-' }}</td>
                                <td style="vertical-align: middle;">
                                    @for ($i = 1; $i <= $row->jmlPenguji; $i++)
                                        {{ $i }}. {{ $row->{'namaPenguji' . $i} ?? '-' }}<br>
                                    @endfor
                                </td>
                                <td style="vertical-align: middle; text-align:center;">{{ $row->tanggalSidang ? \Carbon\Carbon::parse($row->tanggalSidang)->translatedFormat('d F Y') : '-' }}</td>
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
