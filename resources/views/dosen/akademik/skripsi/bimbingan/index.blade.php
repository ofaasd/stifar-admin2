@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/tagify.css') }}">
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
        <div class="row">
            <div class="card">
                <div class="card-body">
                    {{-- <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#FormModal" id="tambahDosbim"></button> --}}
                    <div class="table-responsive">
                        <table class="display" id="bimbingan-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mahasiswa</th>
                                    <th>Judul</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
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
    <script src="{{ asset('assets/js/select2/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select3-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#bimbingan-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('akademik.skripsi.dosen.bimbingan.get-data') }}',
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
                },
                {
                data: 'mahasiswa',
                name: 'mahasiswa'
                },
                {
                data: 'judul',
                name: 'judul'
                },
                {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
                }
            ],
            language: {
                emptyTable: "Tidak ada bimbingan." // Pesan ketika data kosong
            }
            });

            // Show loading when "Lihat Bimbingan" button is clicked
            $(document).on('click', '#btn-bimbingan', function(e) {
            var $btn = $(this);
            var originalHtml = $btn.html();
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            setTimeout(function() {
                $btn.prop('disabled', false);
                $btn.html(originalHtml);
            }, 8000);
            });
        });
    </script>
@endsection
