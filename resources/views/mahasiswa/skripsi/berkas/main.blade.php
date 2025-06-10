@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons/bootstrap-icons.css') }}">
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
    <div class="page-content" id="berkas-page">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Berkas Skripsi</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle"></i> Silakan unggah berkas-berkas yang diperlukan untuk skripsi
                            Anda. Pastikan semua berkas diunggah sebelum tanggal sidang.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Berkas</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal Upload</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($berkasSkripsi as $berkas)
                                        <tr>
                                            <td>{{ $berkas->kategori->nama ?? '-' }}</td>
                                            <td>{{ $berkas->deskripsi ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($berkas->tanggal_upload)->translatedFormat('d F Y') }}
                                            </td>
                                            <td>
                                                @if ($berkas->nama_file)
                                                    <a href="{{ asset('storage/' . $berkas->nama_file) }}"
                                                        class="btn btn-sm btn-primary" target="_blank">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('mhs.skripsi.berkas.update', $berkas->id) }}"
                                                    method="POST" enctype="multipart/form-data"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    <input type="file" name="file" required
                                                        onchange="this.form.submit()" style="display: none;"
                                                        id="fileUpload{{ $berkas->id }}">
                                                    <label for="fileUpload{{ $berkas->id }}"
                                                        class="btn btn-sm btn-warning"><i
                                                            class="bi bi-arrow-repeat"></i></label>
                                                </form>
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

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Upload Berkas Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mhs.skripsi.berkas.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="jenisBerkas" class="form-label">Jenis Berkas</label>
                                <select class="form-select" id="jenisBerkas" name="kategori_id" required>
                                    <option value="">Pilih Jenis Berkas</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsiBerkas" class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" id="deskripsiBerkas" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="fileBerkas" class="form-label">File</label>
                                <input type="file" name="file" id="fileBerkas" class="form-control" required>
                                <small class="text-muted">Format yang didukung: PDF, DOC, DOCX, JPG, PNG (Maks.
                                    10MB)</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload Berkas</button>
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
    <script></script>
@endsection
