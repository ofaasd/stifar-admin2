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
    <h3>{{ 'Daftar Dosen Pembimbing' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{ 'Daftar Dosen Pembimbing' }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="pembimbing-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul / Topik</th>
                                    <th>Nim</th>
                                    <th>Nama</th>
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
        <!--Centered modal-->
        <div class="modal fade" id="FormModal" tabindex="-1" role="dialog" aria-labelledby="FormModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form class="row g-3 needs-validation custom-input" id="formDosbim">
                            <input class="form-control" type="hidden" name="nip" id="nip"
                                placeholder="Please select">

                            <div class="col-md-12 position-relative">
                                <label class="form-label" for="validationTooltip03">Topik/Judul</label>
                                <input class="form-control" name="topik" id="topik" type="text" required="">
                            </div>
                            <div class="col-6">
                                <button class="btn btn-primary" type="submit">Submit form</button>
                            </div>
                        </form>
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
    <script src="{{ asset('assets/js/select2/tagify.polyfills.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select3-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#pembimbing-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dosen.pengajuan.getDataMahasiswa') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'button',
                        name: 'button',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data dosen pembimbing yang tersedia." // Pesan ketika data kosong
                }


            });


            $(document).on('click', '.btn-info', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('dosen.pengajuan.acc', '') }}/' + id,
                    method: 'GET',
                    success: function(response) {
                        swal("Success", "Acc Mahasiswa Berhasil ", "success");
                        $('#pembimbing-table').DataTable().ajax
                                .reload(); // Reload DataTables
                    },
                    error: function(xhr) {
                        swal("error", "Gagal Menerima mahasiswa ", "error");
                        console.log(xhr.responseText);
                    }
                });
            });
            $(document).on('click', '.btn-danger', function() {
                var nip = $(this).data('id');

                $.ajax({
                    url: '{{ route('dosen.pengajuan.delete', '') }}/' + nip,
                    method: 'GET',
                    success: function(response) {
                        swal("Success", "Delete Mahasiswa Berhasil ", "success");
                        $('#pembimbing-table').DataTable().ajax
                                .reload(); // Reload DataTables
                    },
                    error: function(xhr) {
                        swal("error", "Gagal menghapus data mahasiswa ", "error");
                        console.log(xhr.responseText);
                    }
                });
            });


        });
    </script>
@endsection
