<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>Daftar Pengajuan Judul</title> --}}
    <style>
        #customers {
            font-family: 'Times New Roman', Times, serif, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 4px;
        }

        td{
            font-size: 11px;
        }
        a{
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
        .table th, .table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
      
    </style>
</head>

<body>
    <table width="100%" style="margin-bottom:12px;">
        <tr>
            <td style="width:90px; vertical-align:middle;">
                <img src="{{ $logo }}" alt="logo-stifar" style="width:80px; height:auto; display:block;">
            </td>
            <td style="vertical-align:middle; padding-left:10px;">
                <div style="text-align:center; padding:8px 0;">
                    <h1 style="font-size:22px; margin:0; font-weight:700; line-height:1.1;">{{ $title }}</h1>
                </div>
            </td>
        </tr>
    </table>
    <hr>
    <div class="container">
        <div class="table-responsive">
            <table id="customers" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Mahasiswa</th>
                        <th>Judul</th>
                        <th>Pembimbing Pengajuan</th>
                        <th>Pembimbing Realisasi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($pengajuan as $nim => $rows)
                        @php
                            $judulArr = [];
                            foreach ($rows as $row) {
                                $statusLabels = [
                                    0 => 'Pengajuan',
                                    1 => 'ACC',
                                    2 => 'Revisi',
                                    3 => 'Ditolak',
                                    4 => 'Pergantian Judul',
                                ];

                                $icons = [
                                    0 => '<span title="' . $statusLabels[0] . '" style="color:gray;">&#9203;</span>',
                                    1 => '<span title="' . $statusLabels[1] . '" style="color:green;">&#10004;</span>',
                                    2 => '<span title="' . $statusLabels[2] . '" style="color:orange;">&#9998;</span>',
                                    3 => '<span title="' . $statusLabels[3] . '" style="color:red;">&#10006;</span>',
                                    4 => '<span title="' . $statusLabels[4] . '" style="color:blue;">&#8635;</span>',
                                ];

                                $icon = $icons[$row->status] ?? '<span title="Unknown" style="color:gray;">&#9203;</span>';
                                $judulArr[] = $icon . ' ' . e($row->judul);
                            }
                            $first = $rows->first();
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>({{ $nim }}) {{ $first->nama }}</td>
                            <td>{!! implode('<hr><br>', $judulArr) !!}</td>
                            <td>
                                @if($isPengajuan)
                                    @php
                                        $kuota1 = $first->kuotaPembimbing1 ?? 0;
                                        $kuota2 = $first->kuotaPembimbing2 ?? 0;
                                        $jml1   = $first->jmlBimbingan1 ?? 0;
                                        $jml2   = $first->jmlBimbingan2 ?? 0;
                                    @endphp

                                    <div style="font-size:11px; line-height:1.2;">
                                        @if($first->pembimbing2)
                                            <div>
                                                <strong>{{ $first->pembimbing1 ?? '-' }}</strong><br>
                                                <span style="font-size:10px; color:#555;">(Kuota: {{ $kuota1 }}, Bimbingan: {{ $jml1 }})</span>
                                            </div>
                                            <div style="margin-top:4px;">
                                                <strong>{{ $first->pembimbing2 ?? '-' }}</strong><br>
                                                <span style="font-size:10px; color:#555;">(Kuota: {{ $kuota2 }}, Bimbingan: {{ $jml2 }})</span>
                                            </div>
                                        @else
                                            <div>
                                                <strong>{{ $first->pembimbing1 ?? '-' }}</strong><br>
                                                <span style="font-size:10px; color:#555;">(Kuota: {{ $kuota1 }}, Bimbingan: {{ $jml1 }})</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    @php
                                        $pp1 = $rows->pluck('pembimbingPengajuan1')->filter()->unique()->values()->all();
                                        $pp2 = $rows->pluck('pembimbingPengajuan2')->filter()->unique()->values()->all();
                                    @endphp

                                    @if(count($pp1) && count($pp2))
                                        {{ $pp1[0] . ' & ' . $pp2[0] }}
                                    @elseif(count($pp1))
                                        {{ $pp1[0] }}
                                    @elseif(count($pp2))
                                        {{ $pp2[0] }}
                                    @else
                                        -
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($isPengajuan)
                                    -
                                @else
                                    @if($first->pembimbing2)
                                        {{ $first->pembimbing1 . ' & ' . $first->pembimbing2 }}
                                    @else
                                        {{ $first->pembimbing1 ?? '-' }}
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">Data pengajuan tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <p style="text-align: right; font-size: 12px; color: #555; margin-top: 10px;">
                Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>
</body>

</html>