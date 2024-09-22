@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{ 'Bimbingan Mahasiswa' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Bimbingan</li>
    <li class="breadcrumb-item active">{{ 'Daftar Dosen Pembimbing' }}</li>
@endsection

@section('content')

<div class="container">
    <div class="d-flex gap-4 justify-content-between items-center">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#FormModal" >Upload Dokumen</button>
    </div>
</div>
    <div class="container-fluid">
        <!--Centered modal-->
        <div class="modal fade" id="FormModal" tabindex="-1" role="dialog" aria-labelledby="FormModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form class="row g-3 needs-validation custom-input" id="formDosbim">
                            <div class="col-md-12 position-relative">
                                <label class="form-label" for="validationTooltip03">Kategori</label>
                                <select class="form-control" name="kategori" id="kategori">
                                    <option disabled selected>Pilih Kategori</option>
                                    <option value="Judul">Judul</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="Bab {{ $i }}">Bab {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <input type="number" class="d-none" name="id_pembimbing" value="{{$data->id}}">
                            <div class="col-md-12 position-relative d-none" id="judul">
                                <label class="form-label" for="validationTooltip03">Judul</label>
                                <input class="form-control " name="judul" id="JudulValue" type="text" required>
                            </div>
                        
                            <div class="col-md-12 position-relative d-none" id="file">
                                <label class="form-label" for="validationTooltip03">File</label>
                                <input class="form-control" name="file" id="fileValue" type="file" accept=".pdf,.doc,.docx">
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
    <script>
        $(document).ready(function() {
            $('#kategori').on('change', function() {
            var selectedValue = $(this).val(); // Ambil value dari select

            if (selectedValue === 'Judul') {
                $('#judul').removeClass('d-none'); // Tampilkan input judul
                $('#file').addClass('d-none');     // Sembunyikan input file
                $('#file').val('');                // Reset value file jika sebelumnya dipilih
            } else {
                $('#judul').addClass('d-none');    // Sembunyikan input judul
                $('#file').removeClass('d-none');  // Tampilkan input file
                $('#judul').val('');                // Reset value file jika sebelumnya dipilih
            }


        
            
        });
    </script>
@endsection
