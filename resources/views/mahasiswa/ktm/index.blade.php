<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            gap: 30px;
            color: #2f2f2f;
        }
        
        .card-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }
        
        .id-card {
            border: 1px solid;
            width: 8.56cm;
            height: 5.398cm;
            position: relative;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border-radius: 8px;
            overflow: hidden;
        }

        .id-card-front img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0; /* Pastikan gambar di belakang konten lain */
        }

        .id-card-back img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0; /* Pastikan gambar di belakang konten lain */
        }

        .photo-area {
            position: absolute;
            top: 1.92cm;
            left: 0.29cm;
            width: 1.82cm;
            height: 2.22cm;
            background: rgba(255, 255, 255, 0.1);
            border: 2px dashed transparent;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-area img {
            border-radius: 6px;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-preview {
            position: absolute;
            top: 0;
            border: 1px solid;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
            pointer-events: none;
        }
        
        /* Form fields */
        .form-field {
            position: absolute;
            background: transparent;
            border: none;
            border-bottom: 1px solid transparent;
            font-family: Arial, sans-serif;
            font-size: 8px;
            color: #2f2f2f;
            font-weight: bold;
            outline: none;
        }
        
        .nama-field {
            top: 1.94cm;
            left: 4.23cm;
            width: 4.18cm;
        }

        .nim-field {
            top: 2.24cm;
            left: 4.23cm;
            width: 7.41cm;
        }

        .program-field {
            top: 2.54cm;
            left: 4.23cm;
            width: 4.18cm;
        }

        .agama-field {
            top: 2.84cm;
            left: 4.23cm;
            width: 7.41cm;
        }

        .tempat-lahir-field {
            top: 3.12cm;
            left: 4.23cm;
            width: 7.41cm;
        }

        .tanggal-lahir-field {
            top: 3.42cm;
            left: 4.23cm;
            width: 7.41cm;
        }

        .alamat-field {
            top: 3.7cm;
            left: 4.23cm;
            width: 4.18cm;
        }

        .masa-berlaku-field {
            top: 4.3cm;
            left: 4.23cm;
            width: 7.41cm;
        }
        
        .barcode-area {
            position: absolute;
            top: 112px;
            left: 0.58cm;
            width: 140px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 5px;
            padding: 5px;
            text-align: center;
        }

        .barcode-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .nim-text {
            font-size: 8px;
            font-weight: bold;
            margin-top: 4px;
            color: #2f2f2f;
            font-family: Arial, sans-serif;
        }

        .location-date-area {
            position: absolute;
            top: 112px;
            left: 5.5cm;
            width: 200px;
            color: #2f2f2f;
        }
        
        .location-input {
            background: transparent;
            border: none;
            border-bottom: 1px solid transparent;
            font-family: Arial, sans-serif;
            font-size: 7px;
            font-weight: bold;
            width: 100px;
            outline: none;
            color: #2f2f2f;
            margin-right: 2px;
            letter-spacing: -0.5px;
        }
    </style>
</head>
<body>
    @php
        use Carbon\Carbon;
        Carbon::setLocale('id');
        $agamaList = [
            1 => 'Islam',
            2 => 'Kristen',
            3 => 'Katolik',
            4 => 'Hindu',
            5 => 'Budha',
            6 => 'Konghucu',
            99 => 'Lainnya',
        ];
    @endphp
    <div class="card-container">
        @foreach($data as $item)
            <div class="student-wrapper" style="margin-bottom: 6px;">
                <div class="id-card id-card-front">
                    <img src="assets/images/mahasiswa/template-ktm/depan.jpg" style="width: 8.56cm; height: 5.398cm;" />
                    <div class="photo-area">
                    @php
                        $photoPath = $item->fotoMahasiswa ? public_path('assets/images/mahasiswa/' . $item->fotoMahasiswa) : null;
                    @endphp

                    @if($photoPath && file_exists($photoPath))
                        <img src="data:image/{{ pathinfo($photoPath, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($photoPath)) }}" />
                    @endif
                    </div>
                    
                    <p class="form-field nama-field">{{ $item->nama }}</p>
                    <p class="form-field nim-field">{{ $item->nim }}</p>
                    <p class="form-field program-field">{{ $item->programStudi ?? '-' }}</p>
                    <p class="form-field agama-field">{{ $agamaList[$item->agama] ?? 'Tidak diketahui' }}</p>
                    <p class="form-field tempat-lahir-field">{{ $item->tempatLahir }}</p>
                    <p class="form-field tanggal-lahir-field">{{ \Carbon\Carbon::parse($item->tanggalLahir)->format('d-m-Y') }}</p>
                    <p class="form-field alamat-field">{{ $item->alamat }}</p>
                    <p class="form-field masa-berlaku-field">{{ Carbon::parse($item->createdAt)->addYears(5)->translatedFormat('d F Y') }}</p>
                </div>
                
                <div class="id-card id-card-back">
                    <img src="assets/images/mahasiswa/template-ktm/belakang.jpg" style="width: 8.56cm; height: 5.398cm;" />
                    <div class="barcode-area">
                    <div class="barcode-wrapper">
                        <div style="height: 46px; width: 156px; overflow: hidden; color:#2f2f2f">
                        <div style="transform: scale(1.6); transform-origin: left top; color:#2f2f2f">
                            {!! str_replace('black', '#2f2f2f', DNS1D::getBarcodeHTML($item->nim, 'C128', 1, 100)) !!}
                        </div>
                        </div>

                        <p class="nim-text">{{ $item->nim }}</p>
                    </div>
                    </div>

                    <div class="location-date-area">
                    <div>
                        <p class="location-input">{{ \Carbon\Carbon::parse($item->createdAt)->translatedFormat('F Y') }}</p>
                    </div>
                    </div>
                </div>
            </div>

            @if(!$loop->last && $loop->iteration % 2 == 0)
            <div style="page-break-after: always; break-after: page;"></div>
            @endif
        @endforeach
    </div>
</body>
</html>
