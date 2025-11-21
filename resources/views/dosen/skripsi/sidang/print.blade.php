<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $filterTitle }}</title>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 6px;
        }

        #customers th {
            padding: 8px;
            text-align: left;
        }

        td {
            font-size: 11px;
        }

        a {
            color: black;
            outline: 0;
            border: none;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .badge { display: inline-block; padding: .25em .4em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
        .badge-success { background-color: #28a745; color: #fff; }
        .badge-primary { background-color: #007bff; color: #fff; }
        .badge-info { background-color: #17a2b8; color: #fff; }
        .badge-secondary { background-color: #6c757d; color: #fff; }
        .badge-dark { background-color: #343a40; color: #fff; }
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
                    <h1 style="font-size:22px; margin:0; font-weight:700; line-height:1.1;">{{ $filterTitle }}</h1>
                </div>
            </td>
        </tr>
    </table>
    <hr>
    <div class="container">
        <div class="table-responsive">
            <table id="customers" class="table table-bordered ">
                <thead >
                    <tr>
                        <th>No.</th>
                        <th>Mahasiswa</th>
                        <th>Judul</th>
                        <th>Pembimbing</th>
                        <th>Penguji</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sidang as $row)
                        @php
                            // Mahasiswa
                            $mahasiswaText = ($row->nim ?? '-') . ' - ' . ($row->nama ?? '-');

                            // Pembimbing (1/2) with npp
                            $pembimbings = [];
                            $nppsPemb = [];
                            if (!empty($row->namaPembimbing1)) {
                                $pembimbings[] = $row->namaPembimbing1;
                                $nppsPemb[] = $row->pembimbing_1 ?? '-';
                            }
                            if (!empty($row->namaPembimbing2)) {
                                $pembimbings[] = $row->namaPembimbing2;
                                $nppsPemb[] = $row->pembimbing_2 ?? '-';
                            }
                            $pembimbingHtml = '-';
                            if (count($pembimbings) > 0) {
                                $pembimbingHtml = '';
                                foreach ($pembimbings as $i => $name) {
                                    $npp = isset($nppsPemb[$i]) ? $nppsPemb[$i] : '-';
                                    $pembimbingHtml .= '<strong>' . ($i + 1) . '</strong>. ' . e($name) . ' <small>(' . e($npp) . ')</small><br>';
                                }
                            }

                            // Penguji: parse comma separated npp and lookup names
                            $pengujiHtml = '-';
                            if (!empty($row->penguji)) {
                                $npps = array_filter(array_map('trim', explode(',', $row->penguji)));
                                if (count($npps) > 0) {
                                    // adjust namespace if your Pegawai model is elsewhere
                                    $pegawais = \App\Models\PegawaiBiodatum::whereIn('npp', $npps)->get()->keyBy('npp');
                                    $pengujiHtml = '';
                                    foreach ($npps as $i => $npp) {
                                        $pegawai = $pegawais->has($npp) ? $pegawais->get($npp) : null;
                                        $name = $pegawai ? $pegawai->nama_lengkap : '-';
                                        $pengujiHtml .= '<strong>' . ($i + 1) . '</strong>. ' . e($name) . ' <small>(' . e($npp) . ')</small><br>';
                                    }
                                }
                            }

                            // Waktu (mulai - selesai)
                            $waktu = trim(($row->waktuMulai ?? '-') . ' - ' . ($row->waktuSelesai ?? '-'));

                            // Status badges mapping (sesuai snippet)
                            $statusLabels = [
                                99 => '<span>Calon Peserta Sidang</span>',
                                0 => '<span>Pengajuan</span>',
                                1 => '<span>Selesai</span>',
                                2 => '<span>Diterima</span>',
                            ];
                            $statusHtml = $statusLabels[$row->status] ?? '<span>Unknown</span>';

                            // Tanggal (gunakan created_at jika ada)
                            $tanggal = isset($row->tanggal) ? \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y') : '-';
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $mahasiswaText }}</td>
                            <td>{{ $row->judul }}</td>
                            <td>{!! $pembimbingHtml !!}</td>
                            <td>{!! $pengujiHtml !!}</td>
                            <td>{{ $tanggal }} - {{ $waktu }}</td>
                            <td>{!! $statusHtml !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">Data sidang tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <p style="text-align: right; font-size: 8px; color: #555; margin-top: 10px;">
                Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>
</body>

</html>
