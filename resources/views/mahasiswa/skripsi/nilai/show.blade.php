@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item">Nilai</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($sidang))
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nama:</strong> {{ $sidang->nama ?? '-' }}<br>
                                <strong>NIM:</strong> {{ $sidang->nim ?? '-' }}<br>
                                <strong>Judul Skripsi:</strong> {{ $sidang->judul ?? '-' }}<br>
                                <strong>Judul Skripsi (English):</strong> {{ $sidang->judulEnglish ?? '-' }}<br>
                                <strong>Pembimbing 1:</strong> {{ $sidang->namaPembimbing1 ?? '-' }}<br>
                                <strong>Pembimbing 2:</strong> {{ $sidang->namaPembimbing2 ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Sidang:</strong> {{ $sidang->tanggal ?? '-' }}<br>
                                <strong>Waktu:</strong> {{ $sidang->waktuMulai ?? '-' }} - {{ $sidang->waktuSelesai ?? '-' }}<br>
                                <strong>Ruangan:</strong> {{ $sidang->ruangan ?? '-' }}<br>
                                <strong>Gelombang:</strong> {{ $sidang->namaGelombang ?? '-' }}<br>
                                <strong>Periode:</strong> {{ $sidang->periode ?? '-' }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>Jenis Sidang:</strong> 
                                @if($sidang->jenis == 1)
                                    Sidang Terbuka
                                @elseif($sidang->jenis == 2)
                                    Sidang Tertutup
                                @else
                                    -
                                @endif
                                <br>
                            <strong>Status:</strong> 
                            @if($sidang->status === 0)
                                Pengajuan
                            @elseif($sidang->status === 1)
                                Selesai
                            @elseif($sidang->status === 2)
                                Diterima
                            @else
                                -
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong>Penguji:</strong>
                            <ul>
                                @for($i = 1; isset($sidang->{'namaPenguji'.$i}); $i++)
                                    <li>{{ $sidang->{'namaPenguji'.$i} }}</li>
                                @endfor
                            </ul>
                        </div>
                        <div class="mb-3">
                            <strong>Dokumen Pendukung:</strong>
                            <ul>
                                <li>Kartu Bimbingan: {!! $sidang->kartuBimbingan ? '<a href="'.asset('berkas-sidang/'.$sidang->kartuBimbingan).'" target="_blank">Lihat</a>' : '-' !!}</li>
                                <li>Proposal: {!! $sidang->proposal ? '<a href="'.asset('berkas-sidang/'.$sidang->proposal).'" target="_blank">Lihat</a>' : '-' !!}</li>
                                <li>Presentasi: {!! $sidang->presentasi ? '<a href="'.asset('berkas-sidang/'.$sidang->presentasi).'" target="_blank">Lihat</a>' : '-' !!}</li>
                                <li>Pendukung: {!! $sidang->pendukung ? '<a href="'.asset('berkas-sidang/'.$sidang->pendukung).'" target="_blank">Lihat</a>' : '-' !!}</li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <strong>Nilai Penguji:</strong>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Penguji</th>
                                        <th>Nilai Akhir</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($nilaiPenguji as $nilai)
                                        <tr>
                                            <td>
                                                @php
                                                    $idx = array_search($nilai->npp, $sidang->nppsPenguji);
                                                    $namaPenguji = $sidang->{'namaPenguji'.($idx+1)} ?? $nilai->npp;
                                                @endphp
                                                {{ $namaPenguji }} <span class="badge bg-primary">Penguji</span>
                                            </td>
                                            <td>{{ $nilai->nilai_akhir ?? '-' }}</td>
                                            <td>{{ $nilai->catatan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Belum ada nilai penguji.</td>
                                        </tr>
                                    @endforelse

                                    @if(!empty($nilaiPembimbing))
                                        @foreach($nilaiPembimbing as $nilai)
                                            <tr>
                                                <td>
                                                    @php
                                                        $idx = array_search($nilai->npp, $sidang->nppsPembimbing);
                                                        $namaPembimbing = $sidang->{'namaPembimbing'.($idx+1)} ?? $nilai->npp;
                                                    @endphp
                                                    {{ $namaPembimbing }} <span class="badge bg-success">Pembimbing</span>
                                                </td>
                                                <td>{{ $nilai->nilai_akhir ?? '-' }}</td>
                                                <td>{{ $nilai->catatan ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                @if(count($nilaiPenguji) > 0 || !empty($nilaiPembimbing))
                                    <tfoot>
                                        <tr>
                                            <th colspan="1">Nilai: </th>
                                            <th colspan="2">{{ $sidang->nilaiAkhir ?? '-' }}</th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">Data sidang tidak ditemukan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>

    <script>
    $(function () {
        //
    });
    </script>

@endsection
