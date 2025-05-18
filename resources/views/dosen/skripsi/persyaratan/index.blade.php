@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/tagify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons/bootstrap-icons.css') }}">
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
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
    {{-- Persyaratan SKS --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Pengaturan Minimal SKS</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('koor.skripsi.update-sks') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="jumlah_sks" class="form-label">Minimal SKS</label>
                    <input type="number" name="jumlah_sks" id="jumlah_sks" class="form-control" value="{{ $sks->jumlah_sks }}" required>
                    <div class="form-text">Jumlah SKS minimal yang harus diselesaikan mahasiswa untuk mengambil skripsi.</div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    {{-- Berkas Skripsi --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0">Pengaturan Berkas Skripsi</h5>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle"></i> Tambah Berkas
            </button>
        </div>
        <div class="card-body">
        
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Berkas</th>
                            <th>Deskripsi</th>
                            <th>Wajib</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoriBerkas as $index => $berkas)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $berkas->nama }}</td>
                                <td>{{ $berkas->deskripsi }}</td>
                                <td>
                                    @if ($berkas->wajib)
                                        <span class="badge bg-success">Ya</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Tombol Edit --}}
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $berkas->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('koor.skripsi.berkas.destroy', $berkas->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus berkas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    {{-- Modal Edit --}}
                                    <div class="modal fade" id="modalEdit{{ $berkas->id }}" tabindex="-1"
                                        aria-labelledby="modalEditLabel{{ $berkas->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('koor.skripsi.berkas.update', $berkas->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditLabel{{ $berkas->id }}">Edit Berkas</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Berkas</label>
                                                            <input type="text" class="form-control" name="nama_kategori"
                                                                value="{{ $berkas->nama }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Deskripsi</label>
                                                            <textarea class="form-control" name="deskripsi" rows="3">{{ $berkas->deskripsi }}</textarea>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" name="wajib"
                                                                id="editWajib{{ $berkas->id }}" {{ $berkas->wajib ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="editWajib{{ $berkas->id }}">Berkas Wajib</label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    {{-- End Modal Edit --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada berkas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('koor.skripsi.berkas.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Berkas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Berkas</label>
                            <input type="text" class="form-control" name="nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="wajib" id="wajibTambah" checked>
                            <label class="form-check-label" for="wajibTambah">Berkas Wajib</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
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

        });
    </script>
@endsection
