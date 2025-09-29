<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template KRS</title>
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
    <center><b>Logbook Bimbingan Skripsi</b></center>
    <br>
    <table width="100%">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $mhs->nama }}</td>
            {{-- <td style="padding-left: 80px;">Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $tahunAjar }}</td> --}}
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $mhs->nim }}</td>
            <td style="padding-left: 80px;">Pembimbing 1</td>
            <td>:</td>
            <td>{{ $masterSkripsi->namaPembimbing1 ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jurusan/Prodi</td>
            <td>:</td>
            <td>{{ $mhs->namaProdi }}</td>
            <td style="padding-left: 80px;">Pembimbing 2</td>
            <td>:</td>
            <td>{{ $masterSkripsi->namaPembimbing2 ?? '-'}}</td>
        </tr>
    </table>
    <br>
    <div class="container">
        <div class="table-responsive">
            <table id="customers" class="table table-bordered ">
                <thead >
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Topik</th>
                        <th>Status</th>
                        <th>Catatan Mahasiswa</th>
                        <th>Catatan Dosen</th>
                        <th>Metode</th>
                        <th>Waktu Pengajuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logBook as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_waktu)->format('d/m/Y') }}</td>
                            <td>{{ $row->topik }}</td>
                            <td>
                                @if($row->status == 0)
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($row->status == 1)
                                    <span class="badge bg-success">ACC</span>
                                @elseif($row->status == 2)
                                    <span class="badge bg-primary">Setuju</span>
                                @elseif($row->status == 3)
                                    <span class="badge bg-info">Revisi</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>
                            <td>{{ $row->catatan_mahasiswa }}</td>
                            <td>{{ $row->catatan_dosen }}</td>
                            <td>{{ $row->metode }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center;">Data logbook tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <p style="text-align: right; font-size: 12px; color: #555; margin-top: 10px;">
                Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>
    
    {{-- <hr>
    <br>
    <center>PERSETUJUAN KARTU STUDI</center>
    <table width="100%">
        <tr>
            <td></td>
            <td></td>
            <td>Semarang, {{ date("d M Y") }}</td>
        </tr>
        <tr>
            <td><center>Dosen Wali</center></td>
            <td><center></center></td>
            <td><center>Mahasiswa</center></td>
        </tr>
        <tr>
            <td><center><br><br><br></center></td>
            <td><center></center></td>
            <td><center></center></td>
        </tr>
        <tr>
            <td><center>{{ $mhs->dsn_wali }}</center></td>
            <td><center>Orang Tua Wali</center></td>
            <td><center>{{ $mhs->nama }}</center></td>
        </tr>
        <tr>
            <td><center><br><br><br></center></td>
            <td><center></center></td>
            <td><center></center></td>
        </tr>
        <tr>
            <td><center></center></td>
            <td><center>..................</center></td>
            <td><center></center></td>
        </tr>
    </table> --}}
</body>

</html>