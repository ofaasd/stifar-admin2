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
            border-spacing: 12px 16px; /* jarak antar label */
        }
        .label-cell {
            border: 2px solid #222;
            border-radius: 6px;
            text-align: center;
            padding: 18px 0;
            font-size: 16px;
            font-weight: bold;
            background: #f9f9f9;
            width: 19%;
            height: 60px;
            letter-spacing: 2px;
            box-shadow: 1px 1px 4px #ccc;
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
                $kode = !empty($row->inventaris_baru) ? $row->inventaris_baru : $row->inventaris_lama;
                for ($i = 0; $i < $row->jumlah; $i++) {
                    $labelList->push($kode . '-' . ($i + 1));
                }
            } else {
                if (!empty($row->label)) {
                    $labelList->push($row->label);
                }
            }
        }
        $chunks = $labelList->chunk(5); // 5 kolom per baris
    @endphp

    <table class="label-table">
        @foreach($chunks as $row)
            <tr>
                @foreach($row as $label)
                    <td class="label-cell">
                        {{ $label }}
                    </td>
                @endforeach
                @for ($i = 0; $i < 5 - $row->count(); $i++)
                    <td class="label-cell"></td>
                @endfor
            </tr>
        @endforeach
    </table>
</body>
</html>
