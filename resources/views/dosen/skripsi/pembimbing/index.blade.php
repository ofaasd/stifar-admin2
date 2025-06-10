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
    <h3>{{ 'Daftar Mahasiwa Pengajuan' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item active">{{ 'Daftar Mahasiwa Pengajuan' }}</li>
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
    </div>
      
    


<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel">Detail Mahasiswa</h5>
             <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label for="judul">Judul Skripsi:</label>
                <p id="judul">-</p>
            </div>
            <div class="form-group">
                <label for="abstrak">Abstrak:</label>
                <p id="abstrak">-</p>
            </div>
            <div class="form-group">
                <label for="transkrip_nilai">Transkrip Nilai:</label>
                <p id="transkrip_nilai">-</p>
            </div>
            <div class="form-group">
                <label for="file_pendukung_1">File Pendukung 1:</label>
                <p id="file_pendukung_1">-</p>
            </div>
            <div class="form-group">
                <label for="file_pendukung_2">File Pendukung 2:</label>
                <p id="file_pendukung_2">-</p>
            </div>
        </div>
          <div class="modal-footer">
             <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
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


            $(document).on('click', '.btnShowModal', function() {
        var nim = $(this).data('id'); // Get nim from button

        // Fetch data using AJAX
        $.ajax({
            url: '{{ route('dosen.pengajuan.getDetailMhs', '') }}/' + nim,
            method: 'GET',
            success: function(data) {
                // Fill modal fields with data
                $('#judul').text(data.judul || '-');
                $('#abstrak').text(data.abstrak || '-');
                $('#transkrip_nilai').html(data.transkrip_nilai 
                ? `<a href="{{ asset('storage/') }}/${data.transkrip_nilai}" target="_blank">Download</a>` 
                : '-');
            $('#file_pendukung_1').html(data.file_pendukung_1 
                ? `<a href="{{ asset('storage/') }}/${data.file_pendukung_1}" target="_blank">Download</a>` 
                : '-');
            $('#file_pendukung_2').html(data.file_pendukung_2 
                ? `<a href="{{ asset('storage/') }}/${data.file_pendukung_2}" target="_blank">Download</a>` 
                : '-');

                // Show modal
                $('#detailModal').modal('show');
            },
            error: function(xhr) {
                swal("Error", "Gagal mengambil data detail mahasiswa.", "error");
                console.log(xhr.responseText);
            }
        });
    });

            $(document).on('click', '.btn-info', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('dosen.pengajuan.acc', '') }}/' + id,
                    method: 'GET',
                    success: function(response) {
                        swal("Success", response.message, "success");
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
