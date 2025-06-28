<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h6 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
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

    <table style="width: 100%; border-collapse: collapse;">
        @foreach($chunks as $row)
            <tr>
                @foreach($row as $label)
                    <td style="border: 1px solid #000; text-align: center; padding: 6px; font-size: 12px">
                        {{ $label }}
                    </td>
                @endforeach
                 @for ($i = 0; $i < 5 - $row->count(); $i++)
                    <td style="border: 1px solid #000; width: 20%;"></td>
                @endfor
            </tr>
        @endforeach
    </table>

</body>
</html>
