<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Sidang Hasil Skripsi</title>
    <style>
        html, body {
            box-sizing: border-box;
            height: auto;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            line-height: 1.2;
            color: #000;
        }

        /* Hindari pemecahan di tengah elemen penting */
        table, tr, td, .header-table, .doc-title, .signature-section, .catatan-container {
            page-break-inside: avoid;
        }

        /* --- KOP SURAT --- */
        .header-table {
            width: 100%;
            border-bottom: 3px double black;
            margin-bottom: 12px;
            padding-bottom: 5px;
        }
        .logo-cell {
            width: 12%;
            text-align: center;
            vertical-align: middle;
            padding-right: 8px;
        }
        .logo-img {
            width: 70px;
            height: auto;
        }
        .text-cell {
            width: 88%;
            text-align: center;
        }
        .text-cell h1 { font-size: 13pt; margin: 0; font-weight: bold; }
        .text-cell h2 { font-size: 11pt; margin: 2px 0; font-weight: bold; }
        .text-cell p { font-size: 8.5pt; margin: 1px 0; }

        /* --- JUDUL --- */
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin: 10px 0 12px 0;
            text-transform: uppercase;
        }

        /* --- PARAGRAF PEMBUKA --- */
        .content-text {
            text-align: justify;
            margin-bottom: 8px;
        }
        
        .dots {
            display: inline-block;
            border-bottom: 1px dotted black;
            vertical-align: bottom;
            height: 1.1em;
            /* gunakan max-width agar tidak memaksa lebar luar */
        }

        /* --- TABEL DATA --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .data-table td {
            vertical-align: top;
            padding: 2px 4px;
        }

        /* gunakan elemen dots sebagai pengganti deretan titik */
        .field-value {
            display: inline-block;
            width: 100%;
        }
        
        /* Sub-tabel Penguji */
        .penguji-subtable {
            width: 100%;
            border-collapse: collapse;
        }
        .penguji-subtable td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* --- KOTAK CATATAN --- */
        .catatan-label {
            margin-bottom: 5px;
        }
        .catatan-container {
            border: 1px solid black;
            width: 100%;
            min-height: 60px; /* dikurangi agar tidak memakan banyak halaman */
            max-height: 120px;
            padding: 6px;
            margin-bottom: 12px;
            overflow: hidden;
        }

        /* --- TANDA TANGAN --- */
        .signature-section {
            width: 100%;
            margin-top: 6px;
        }
        .date-line {
            text-align: right;
            margin-bottom: 6px;
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
            padding: 6px 4px;
            /* jangan gunakan padding-bottom besar, atur tinggi via .ttd-space */
        }
        .sig-role {
            margin-bottom: 4px;
            font-size: 10pt;
        }
        
        /* Helper spasi ttd */
        .ttd-space {
            height: 40px; 
            display: block;
        }

        /* Prevent very large gaps */
        .spacer-small { height: 6px; }
        .spacer-medium { height: 12px; }

        /* pastikan sel tabel bisa membungkus teks */
        .nowrap { white-space: nowrap; }
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
        telah berlangsung sidang hasil mahasiswa dengan keterangan sebagai berikut:
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

    <div class="catatan-label">Sidang hasil berlangsung dengan catatan sebagai berikut:</div>
    <div class="catatan-container">
        <!-- Kosongkan atau isi catatan singkat; jangan melebihi ruang -->
    </div>

    <div style="margin-bottom: 12px;">Demikian berita ini dibuat dengan sesungguhnya.</div>

    <div class="signature-section">
        <div class="date-line">
            Semarang, {{ $formattedSidang }}
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