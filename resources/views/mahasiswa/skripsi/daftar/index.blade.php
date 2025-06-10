@extends('layouts.master')
@section('title', 'Daftar Bimbingan Skripsi')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/tagify.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Daftar Bimbingan Skripsi</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Daftar Bimbingan Skripsi</li>
@endsection

@section('content')
    @if ($data->isEmpty())
        <div class="alert alert-warning">Anda belum mengajukan judul skripsi.</div>
        @include('mahasiswa.skripsi.daftar._form-create')

    @else
        @php $skripsi = $data->first(); @endphp

        @switch($skripsi->status)
            @case(1)
                <div class="alert alert-success" role="alert">
                    <h5 class="alert-heading">Pengajuan Skripsi Disetujui</h5>
                    <p>Judul: <strong>{{ $skripsi->judul }}</strong></p>
                    <p>Tanggal Disetujui: <strong>{{ \Carbon\Carbon::parse($skripsi->tanggal_persetujuan)->translatedFormat('d F Y') }}</strong></p>
                </div>
                @include('mahasiswa.skripsi.daftar._form-detail', ['skripsi' => $skripsi])
                @break

            @case(2)
                <div class="alert alert-danger" role="alert">
                    <h5 class="alert-heading">Judul Skripsi Perlu Direvisi</h5>
                    <p>Silakan perbaiki judul dan isian lainnya sesuai catatan dari dosen pembimbing atau koordinator.</p>
                </div>
                @include('mahasiswa.skripsi.daftar._form-edit', ['skripsi' => $skripsi])
                @break

            @default
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading">Pengajuan Judul Sedang Diproses</h5>
                    <p>Judul Anda sedang menunggu persetujuan.</p>
                </div>
                @include('mahasiswa.skripsi.daftar._form-edit', ['skripsi' => $skripsi])
        @endswitch
    @endif
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
@endsection
