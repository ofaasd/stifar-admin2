@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons/bootstrap-icons.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{ $title }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">

        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mx-auto">

                    <h4>Sidang Hari Ini</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Mahasiswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sidangHariIni as $sidang)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($sidang->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $sidang->nama ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('akademik.skripsi.mahasiswa.daftar-penonton-sidang.show', $sidang->idEnkripsi) }}"
                                           class="btn btn-success btn-sm btn-detail"
                                           title="Lihat"
                                           onclick="this.disabled=true; this.innerHTML='<span class=\'spinner-border spinner-border-sm\'></span> Loading...';">
                                            <span class="btn-text"><i class="bi bi-eye"></i></span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada sidang hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <h4 class="mt-4">Sidang Akan Datang</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sidangAkanDatang as $sidang)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($sidang->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $sidang->nama ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Tidak ada sidang akan datang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-detail', function(e) {
                var $btn = $(this);
                $btn.find('.btn-text').addClass('d-none');
                $btn.find('.spinner-border').removeClass('d-none');
            });
        });
    </script>
@endsection
