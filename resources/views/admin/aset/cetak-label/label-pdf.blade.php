<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    {{-- <title>Label - {{ $title }}</title> --}}
    <style>
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* untuk center secara horizontal */
            gap: 10px;
        }

        .label-container {
            width: 20%;
            padding: 8px;
            border: 1px solid #000;
            font-size: 12px;
            text-align: center;
            box-sizing: border-box;
            margin: 8px; /* ini memberikan jarak atas, bawah, kiri, kanan */
        }

        @page {
            margin: 20px;
        }

        body {
            font-family: sans-serif;
        }
    </style>
</head>
<body>
    <h6 style="text-align: center;">Label {{ $title }}</h6>

    <div class="row">
        @foreach($data as $row)
            <div class="label-container">
                {{ $row->label ?? '-' }}
            </div>
        @endforeach
    </div>
</body>
</html>