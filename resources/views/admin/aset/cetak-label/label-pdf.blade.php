<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        .label-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px 14px; /* jarak antar label */
        }
        .label-cell {
            border: 1px solid #222;
            font-size: 16px;
            font-weight: bold;
            background: #f9f9f9;
            /* Mengubah width agar sesuai dengan 3 kolom (chunk(3)) */
            height: 60px;
            vertical-align: top; /* Pastikan konten mulai dari atas */
        }
        .header-table {
            width: 100%;
        }
        .header-logo {
            width: 80px;
        }
        .header-info {
            padding-left: 24px;
        }
        h4 {
            margin-top: 18px;
            margin-bottom: 18px;
            text-align: left;
        }

        /* --- CSS UNTUK HEADER LABEL --- */
        .label-header {
            display: flex;
            flex-direction: row; /* Mengatur arah menjadi baris (kanan-kiri) */
            width: 100%;
            min-height: 40px;
        }

        .label-logo {
            width: 28px;
            height: auto;
        }

        .label-text {
            font-size: 8px;
            font-weight: bold;
        }

        /* --- CSS UNTUK DIVIDER --- */
        .label-divider {
            display: block;
            border-top: 1px solid #222;
            /* Tarik garis keluar 12px (sesuai padding) di kedua sisi */
            margin-left: -12px;
            margin-right: -12px;
            /* Atur margin vertikal */
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .label-code {
            margin-top: 8px;
            font-size: 14px;
            text-align: center;
            word-break: break-word; /* Pecah kata jika kode terlalu panjang */
        }

        .parent {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(5, 1fr);
            gap: 8px;
        }
            
        .div1 {
            grid-column: span 2 / span 2;
            grid-row: span 3 / span 3;
        }

        .div2 {
            grid-column: span 3 / span 3;
            grid-row: span 3 / span 3;
            grid-column-start: 3;
        }

        .div3 {
            grid-column: span 5 / span 5;
            grid-row: span 2 / span 2;
            grid-row-start: 4;
        }
                
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="10%">
                <img src="{{ $logo }}" alt="logo-stifar" class="header-logo"/>
            </td>
            <td width="90%" class="header-info">
                <b>SEKOLAH TINGGI ILMU FARMASI SEMARANG</b>
                <br>Alamat : Jl. Letnan Jendral Sarwo Edie Wibowo Km. 1, Plamongan Sari, Kec. Pedurungan, Kota Semarang
                <br>Email : admin@sistifar.id
                <br>Website : https://stifar.ac.id
            </td>
        </tr>
    </table>
    <h4>Label {{ $title }}</h4>

    @php
        $labelList = collect();
        foreach ($data as $row) {
            if (isset($row->jumlah) && is_numeric($row->jumlah)) {
                // $kode = !empty($row->inventaris_baru) ? $row->inventaris_baru : $row->inventaris_lama;
                for ($i = 0; $i < $row->jumlah; $i++) {
                    $kode = "YP.ST." . date('y', strtotime($row->tanggal_pembelian)) . '.' . $row->kode_jenis_barang . '.' . ($i + 1) . '.' . $row->kode_ruang;
                    $labelList->push($kode);
                }
            } else {
                if (!empty($row->label)) {
                    $labelList->push($row->label);
                }
            }
        }
        $chunks = $labelList->chunk(3);
    @endphp

    <table class="label-table">
        @foreach($chunks as $row)
            <tr>
                @foreach($row as $label)
                    <td class="label-cell">
                        <div class="parent">
                            <div class="div1" style="height:32px;display:flex;align-items:center;justify-content:center;padding:0;margin:0;border-bottom:1px solid #222;">
                                <img src="{{ $headerLabel }}" alt="logo" class="label-logo" style="width:100%;height:100%;object-fit:contain;" />
                            </div>
                            {{-- <div class="div2">
                                <div class="label-text">
                                    STIFAR "YAYASAN PHARMASI" SEMARANG
                                </div>
                            </div> --}}
                            <div class="div3">
                                <div class="label-code">{{ $label }}</div>
                            </div>
                        </div>
                    </td>
                @endforeach
                
                @for ($i = 0; $i < 3 - $row->count(); $i++)
                    <td style="border-color: transparent; background: none; box-shadow: none;"></td>
                @endfor
            </tr>
        @endforeach
    </table>
</body>
</html>