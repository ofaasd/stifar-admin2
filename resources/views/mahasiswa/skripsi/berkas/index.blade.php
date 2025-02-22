@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{ 'Berkas Pendukung Skripsi' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Berkas</li>
    <li class="breadcrumb-item active">{{ 'Bimbingan Skripsi' }}</li>
@endsection

@section('content')
    <div class="container-fluid mt-3">
        @if (session('message'))
            <div class="alert alert-{{ session('status') === 'success' ? 'success' : 'danger' }} alert-dismissible fade show"
                role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table class="table" id="tableBerkas">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama Kriteria</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $berkas)
                        <tr>
                            <td></td>   
                            <td><h6>{{ $berkas->nama }}</h6></td>
                            <td>
                                @if($berkas->file)
                                    <a href="{{ asset('storage/berkas_skripsi/' . $berkas->file) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i> Lihat
                                    </a>
                                    <button data-bs-toggle="modal" data-bs-target="#Modalberkas" 
                                            data-id="{{ $berkas->id_kategori }}" 
                                            data-file="{{ $berkas->file }}" 
                                            class="btn btn-warning btn-sm btnModalEdit">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                @else
                                    <button data-bs-toggle="modal" data-bs-target="#Modalberkas" 
                                            data-id="{{ $berkas->id_kategori }}" 
                                            class="btn btn-success btn-sm btnModal">
                                        <i class="fa fa-upload"></i> Upload
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Modalberkas" tabindex="-1" role="dialog" aria-labelledby="Modalberkas"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="row g-3 needs-validation custom-input" method="POST"
                        action="{{ Route('mhs.skripsi.berkas.UploadBerkas') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="id_kategori" name="id_kategori">
                        <input type="hidden" id="current_file" name="current_file">
                        <div class="col-md-12 position-relative" id="file">
                            <label class="form-label" for="validationTooltip03">File</label>
                            <input class="form-control" name="file" id="fileValue" type="file">
                        </div>
                        <div class="col-6">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
    $(document).ready(function() {
        $('.btnModal').on('click', function() {
            var id = $(this).data('id');
            $('#id_kategori').val(id);
            $('#current_file').val('');
        });

        $('.btnModalEdit').on('click', function() {
            var id = $(this).data('id');
            var file = $(this).data('file');
            $('#id_kategori').val(id);
            $('#current_file').val(file);
        });

        $("#tableBerkas").DataTable({
            responsive: true,
            searching: false, 
            ordering: false,  
            info: false,      
            paging: false,    
            columnDefs: [
                {
                    targets: 0, 
                    searchable: false, 
                    orderable: false, 
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                }
            ]
        });
    });
    </script>
@endsection
