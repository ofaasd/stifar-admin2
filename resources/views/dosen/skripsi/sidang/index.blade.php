@extends('layouts.master')
@section('title', 'Data Gelombang Sidang')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons/bootstrap-icons.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Daftar Gelombang Sidang</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item active">Gelombang Sidang</li>
@endsection
@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Pengaturan Gelombang Sidang</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahGelombang">
                <i class="bi bi-plus-circle"></i> Tambah Gelombang
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="datatable-gelombang">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Periode</th>
                        <th>Pendaftaran</th>
                        <th>Pelaksanaan</th>
                        <th>Kuota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gelombang as $g)
                    <tr>
                        <td>{{ $g->nama }}</td>
                        <td>{{ $g->periode }}</td>
                        <td>{{ \Carbon\Carbon::parse($g->tanggal_mulai_daftar)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($g->tanggal_selesai_daftar)->translatedFormat('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($g->tanggal_mulai_pelaksanaan)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($g->tanggal_selesai_pelaksanaan)->translatedFormat('d M Y') }}</td>
                        <td>0/50</td> {{-- Contoh kuota, nanti bisa dikalkulasi --}}
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit" 
                                data-id="{{ $g->id }}"
                                data-nama="{{ $g->nama }}"
                                data-id_tahun="{{ $g->id_tahun_ajaran }}"
                                data-kuota="{{ $g->kuota }}"
                                data-daftar_mulai="{{ $g->tanggal_mulai_daftar }}"
                                data-daftar_selesai="{{ $g->tanggal_selesai_daftar }}"
                                data-pelaksanaan_mulai="{{ $g->tanggal_mulai_pelaksanaan }}"
                                data-pelaksanaan_selesai="{{ $g->tanggal_selesai_pelaksanaan }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('sidang.delete', $g->id) }}" method="POST" class="d-inline delete-form">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahGelombang" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('sidang.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Gelombang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('dosen.skripsi.sidang._form', ['action' => 'tambah'])
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEditGelombang" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEdit" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Gelombang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('dosen.skripsi.sidang._form', ['action' => 'edit'])
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function () {
        // SweetAlert konfirmasi hapus
        $('.delete-form').on('submit', function (e) {
            e.preventDefault();
            let form = this;
            swal({
                title: "Yakin ingin menghapus?",
                text: "Data tidak bisa dikembalikan!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });

        // Isi modal edit
        $('.btn-edit').click(function () {
            let id = $(this).data('id');
            $('#formEdit').attr('action', `/sidang/update/${id}`);
            $('#formEdit input[name=nama]').val($(this).data('nama'));
            $('#formEdit select[name=id_tahun_ajaran]').val($(this).data('id_tahun'));
            $('#formEdit input[name=kuota]').val($(this).data('kuota'));
            $('#formEdit input[name=tanggal_mulai_daftar]').val($(this).data('daftar_mulai'));
            $('#formEdit input[name=tanggal_selesai_daftar]').val($(this).data('daftar_selesai'));
            $('#formEdit input[name=tanggal_mulai_pelaksanaan]').val($(this).data('pelaksanaan_mulai'));
            $('#formEdit input[name=tanggal_selesai_pelaksanaan]').val($(this).data('pelaksanaan_selesai'));
            $('#modalEditGelombang').modal('show');
        });
    });
</script>
@endsection
