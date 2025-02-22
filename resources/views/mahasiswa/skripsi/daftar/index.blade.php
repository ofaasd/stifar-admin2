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
    <h3>{{ 'Daftar Bimbingan Skripsi' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{ 'Daftar Bimbingan Skripsi' }}</li>
@endsection
@section('content')


                <form class="row g-3 needs-validation custom-input" method="POST" action="{{ route('mhs.skripsi.daftar.saveDaftar') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 position-relative">
                        <label class="form-label" for="transkrip">Transkrip Nilai</label>
                        <input type="file" class="form-control" name="transkrip" required>
                    </div>
                    <div class="col-md-12 position-relative">
                        <label class="form-label" for="file1">Sertifikat Pendukung 1</label>
                        <input type="file" class="form-control" name="file1">
                    </div>
                    <div class="col-md-12 position-relative">
                        <label class="form-label" for="file2">Sertifikat Pendukung 2</label>
                        <input type="file" class="form-control" name="file2">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="pembimbing-table">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Transkrip Nilai</th>
                                <th>File Pendukung 1</th>
                                <th>File Pendukung 2</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <td>{{ $item->nim }}</td>
                                <td><a href="{{ asset('storage/' . $item->transkrip_nilai) }}" target="_blank">Download</a></td>
                                <td>
                                    @if($item->file_pendukung_1)
                                    <a href="{{ asset('storage/' . $item->file_pendukung_1) }}" target="_blank">Download</a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if($item->file_pendukung_2)
                                    <a href="{{ asset('storage/' . $item->file_pendukung_2) }}" target="_blank">Download</a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    <span class="p-2 label label-{{ $item->status == 0 ? 'primary' : 'success' }} text-light">
                                        {{ $item->status == 0 ? 'Pending' : 'Done' }}
                                    </span>
                                </td>
                                
                                
                            </tr>
                            @endforeach
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
@endsection