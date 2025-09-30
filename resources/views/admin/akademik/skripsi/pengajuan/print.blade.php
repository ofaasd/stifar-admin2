<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan Judul</title>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

    

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }

        td{
            font-size: 12px;
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
            font-size: 14px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
      
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            <td width="10%">
                <img src="{{ $logo }}" alt="logo-stifar" style="width: 100px;"/>
            </td>
            <td width="90%" style="padding-left: 30px;">
                <center>
                    <b>SEKOLAH TINGGI ILMU FARMASI SEMARANG</b>
                    <br>Alamat : Jl. Letnan Jendral Sarwo Edie Wibowo Km. 1, Plamongan Sari, Kec. Pedurungan, Kota Semarang
                    <br>Email : admin@sistifar.id
                    <br>Website : https://stifar.ac.id
                </center>
            </td>
        </tr>
    </table>
    <hr>
    <center><b>Daftar Mahasiswa Pengajuan Judul</b></center>
    <br>
    <div class="container">
        <div class="table-responsive">
            <table id="customers" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Mahasiswa</th>
                        <th>Judul</th>
                        <th>Pembimbing</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($pengajuan as $nim => $rows)
                        @php
                            $judulArr = [];
                            foreach ($rows as $row) {
                                switch ($row->status) {
                                    case 1:
                                        $icon = '<span title="ACC" style="color:green;">&#10004;</span>';
                                        break;
                                    case 2:
                                        $icon = '<span title="Revisi" style="color:orange;">&#9998;</span>';
                                        break;
                                    case 3:
                                        $icon = '<span title="Ditolak" style="color:red;">&#10006;</span>';
                                        break;
                                    default:
                                        $icon = '<span title="Pengajuan" style="color:gray;">&#9203;</span>';
                                        break;
                                }
                                $judulArr[] = $icon . ' ' . e($row->judul) . '<br>' . $icon . ' ' . e($row->judulEnglish);
                            }
                            $first = $rows->first();
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>({{ $nim }}) {{ $first->nama }}</td>
                            <td>{!! implode('<hr><br>', $judulArr) !!}</td>
                            <td>
                                @if($first->pembimbing2)
                                    {{ $first->pembimbing1 . ' & ' . $first->pembimbing2 }}
                                @else
                                    {{ $first->pembimbing1 }}
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