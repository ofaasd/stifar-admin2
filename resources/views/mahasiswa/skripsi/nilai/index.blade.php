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
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped display" id="pembimbing-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Waktu</th>
                                <th>Sidang</th>
                                <th>Pembimbing</th>
                                <th>Penguji</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sidang as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $row->tanggal }}<br>
                                        {{ $row->waktuMulai }} - {{ $row->waktuSelesai }}<br>
                                        Ruang: {{ $row->ruangan }}
                                    </td>
                                    <td>
                                        @if($row->jenis == 1)
                                            Sidang Terbuka
                                        @elseif($row->jenis == 2)
                                            Sidang Tertutup
                                        @else
                                            {{ $row->jenis }}
                                        @endif
                                        <br>
                                        <span class="badge bg-secondary">{{ $row->namaGelombang }} ({{ $row->periode }})</span><br>
                                    </td>
                                    <td>
                                        1. {{ $row->namaPembimbing1 }}<br>
                                        2. {{ $row->namaPembimbing2 }}
                                    </td>
                                    <td>
                                        @for ($i = 1; $i <= 5; $i++)
                                            @php $nama = $row->{'namaPenguji' . $i} ?? null; @endphp
                                            @if ($nama)
                                                {{ $i }}. {{ $nama }}<br>
                                            @endif
                                        @endfor
                                    </td>
                                    <td>
                                        <a href="{{ route('akademik.skripsi.mahasiswa.nilai-sidang.show', $row->idEnkripsi) }}" class="btn btn-primary btn-sm btn-detail">
                                            <span class="btn-text">Detail</span>
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Data tidak ditemukan.</td>
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
