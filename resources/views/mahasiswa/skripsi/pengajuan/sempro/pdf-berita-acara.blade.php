<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Seminar Proposal</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt; /* Dikecilkan sedikit agar muat 1 halaman */
            line-height: 1.2; /* Spasi baris dirapatkan */
            margin: 0;
            padding: 0;
        }

        /* --- KOP SURAT --- */
        .header-table {
            width: 100%;
            border-bottom: 3px double black;
            margin-bottom: 15px; /* Kurangi jarak ke judul */
            padding-bottom: 5px;
        }
        .logo-cell {
            width: 12%; /* Logo diperkecil sedikit areanya */
            text-align: center;
            vertical-align: middle;
        }
        .logo-img {
            width: 80px; /* Ukuran fisik logo disesuaikan */
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
            margin: 10px 0 20px 0; /* Margin atas bawah judul */
            text-transform: uppercase;
        }

        /* --- ISI UTAMA --- */
        .content-text {
            text-align: justify;
            margin-bottom: 10px;
        }
        
        .dots {
            border-bottom: 1px dotted black;
            display: inline-block;
        }

        /* --- TABEL DATA --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .data-table td {
            vertical-align: top;
            padding: 2px 0; /* Padding baris diperkecil */
        }
        
        /* Sub-tabel Penguji */
        .penguji-subtable {
            width: 100%;
            border-collapse: collapse;
        }
        .penguji-subtable td {
            padding: 2px 0;
        }

        /* --- KOTAK CATATAN --- */
        .catatan-label {
            margin-bottom: 5px;
        }
        .catatan-container {
            width: 100%;
            height: 60px; /* Tinggi kotak catatan dibatasi agar tidak memakan tempat */
            margin-bottom: 15px;
        }

        /* --- TANDA TANGAN --- */
        .signature-section {
            width: 100%;
            margin-top: 10px;
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
            padding-bottom: 50px; /* Ruang untuk tanda tangan */
        }
        
        /* Class helper untuk spasi tanda tangan */
        .ttd-space {
            height: 50px; /* Tinggi area kosong tanda tangan */
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/images/skripsi/header-pdf.png') }}" alt="header pdf" style="width: 100%; height: auto;"/>
    </div>

    <div class="doc-title">BERITA ACARA</div>

    <div class="content-text">
        Pada hari ini <span class="dots" style="width: 80px;">{{ $sidang->hari }}</span> 
        Tanggal <span class="dots" style="width: 40px;">{{ $sidang->date }}</span> 
        bulan <span class="dots" style="width: 80px;">{{ $sidang->bulan }}</span> 
        tahun <span class="dots" style="width: 50px;">{{ $sidang->tahun }}</span> 
        bertempat di ruang <span class="dots" style="width: 120px;">{{ $sidang->ruangan }}</span> 
        telah berlangsung seminar proposal mahasiswa dengan keterangan sebagai berikut:
    </div>

    <table class="data-table">
        <tr>
            <td width="15%">Nama</td>
            <td width="2%">:</td>
            <td width="83%">{{ $sidang->nama }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $sidang->nim }}</td>
        </tr>
        <tr>
            <td>Penguji</td>
            <td>:</td>
            <td>
                <table class="penguji-subtable">
                    @php
                        $roles = [
                            '(Ketua Sidang)',
                            '(Anggota Penguji I)',
                            '(Anggota Penguji II)',
                            '(Anggota Penguji III)'
                        ];
                    @endphp

                    @foreach($roles as $idx => $role)
                        @php
                            $i = $idx + 1;
                            $prop = 'namaPenguji' . $i;
                            $nama = $sidang->{$prop} ?? null;
                        @endphp
                        <tr>
                            <td width="20px">{{ $i }}.</td>
                            <td width="55%">{{ $nama ?? '..............................................................' }}</td>
                            <td>{{ $role }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>

    <div class="catatan-label">Seminar proposal berlangsung dengan catatan sebagai berikut:</div>
    <div class="catatan-container">
        <p>..........................................................................................................</p>
        <p>..........................................................................................................</p>
    </div>

    <div style="margin-bottom: 15px;">Demikian berita ini dibuat dengan sesungguhnya.</div>

    <div class="signature-section">
        <div class="date-line">
            Semarang, {{ $formattedHariIni }}
        </div>

        @php
            // Ambil nama penguji dari properti sidang (maks 4 sesuai konfigurasi)
            $pengujis = [];
            for ($i = 1; $i <= 4; $i++) {
                $prop = 'namaPenguji' . $i;
                $pengujis[] = isset($sidang->$prop) && trim($sidang->$prop) !== '' ? $sidang->$prop : null;
            }
            $rows = ceil(count($pengujis) / 2);
            // helper untuk teks placeholder jika nama kosong
            $placeholder = '.....................................................';
        @endphp

        <table class="sig-table">
            @for ($r = 0; $r < $rows; $r++)
                <tr>
                    <td>Penguji {{ $r * 2 + 1 }}</td>
                    <td>Penguji {{ $r * 2 + 2 }}</td>
                </tr>
                <tr>
                    <td><div class="ttd-space"></div></td>
                    <td><div class="ttd-space"></div></td>
                </tr>
                <tr>
                    <td class="sig-name">( {{ $pengujis[$r * 2] ?? $placeholder }} )</td>
                    <td class="sig-name">( {{ $pengujis[$r * 2 + 1] ?? $placeholder }} )</td>
                </tr>

                @if ($r < $rows - 1)
                    <tr><td colspan="2" style="height: 20px;"></td></tr>
                @endif
            @endfor
        </table>
    </div>

</body>
</html>