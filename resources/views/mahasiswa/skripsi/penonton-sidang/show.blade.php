@extends('layouts.master')
@section('title', 'Detail Sidang')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Detail Sidang</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item"><a href="{{ route('akademik.skripsi.mahasiswa.daftar-penonton-sidang.index') }}" style="text-decoration: none; color: inherit;">Penonton Sidang</a></li>
    <li class="breadcrumb-item active">Detail Sidang</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <td>{{ $data->nama }}</td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td>{{ $data->nim }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Sidang</th>
                            <td>{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Waktu</th>
                            <td>{{ $data->waktuMulai }} - {{ $data->waktuSelesai }}</td>
                        </tr>
                        <tr>
                            <th>Ruang</th>
                            <td>{{ $data->namaRuang }} ({{ $data->kapasitas }} orang)</td>
                        </tr>
                        <tr>
                            <th>Jenis Sidang</th>
                            <td>
                                @if($data->jenis == 1)
                                    Sidang Terbuka
                                @elseif($data->jenis == 2)
                                    Sidang Tertutup
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                    @if ($data->isRegistered)
                        <div class="alert alert-info" role="alert">
                            Anda sudah terdaftar sebagai penonton sidang ini.
                        </div>
                    @else
                        <form action="{{ route('akademik.skripsi.mahasiswa.daftar-penonton-sidang.daftar') }}" method="POST" id="form-daftar-penonton">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <button type="submit" class="btn btn-primary mt-3" id="btn-daftar-penonton">
                                Daftar Jadi Penonton Sidang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script>
    $('#form-daftar-penonton').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = $('#btn-daftar-penonton');
        swal({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin mendaftar sebagai penonton sidang?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function(willSubmit) {
            if (willSubmit) {
                btn.prop('disabled', true).text('Mendaftar...');
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        swal({
                            title: "Berhasil!",
                            text: res.message || "Anda berhasil mendaftar sebagai penonton.",
                            icon: "success",
                            timer: 2000,
                            buttons: false
                        });
                        btn.prop('disabled', true).text('Sudah Terdaftar');
                    },
                    error: function(xhr) {
                        swal({
                            title: "Gagal!",
                            text: xhr.responseJSON?.message || "Terjadi kesalahan.",
                            icon: "error",
                            timer: 2000,
                            buttons: false
                        });
                        btn.prop('disabled', false).text('Daftar Jadi Penonton Sidang');
                    }
                });
            }
        });
    });
</script>
@endsection
