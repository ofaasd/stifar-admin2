@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item">Pengajuan</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    {{-- Data Pembimbing --}}
                    @if(isset($masterSkripsi))
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Pembimbing 1:</strong><br>
                                    Nama: {{ $masterSkripsi->nama_pembimbing1 ?? '-' }}<br>
                                    NPP: {{ $masterSkripsi->npp_pembimbing1 ?? '-' }}<br>
                                    Email: {{ $masterSkripsi->email_pembimbing1 ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Pembimbing 2:</strong><br>
                                    Nama: {{ $masterSkripsi->nama_pembimbing2 ?? '-' }}<br>
                                    NPP: {{ $masterSkripsi->npp_pembimbing2 ?? '-' }}<br>
                                    Email: {{ $masterSkripsi->email_pembimbing2 ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Form Detail Judul Skripsi --}}
                    @if(isset($judul))
                        <div class="mb-3">
                            <label class="form-label"><strong>Judul</strong></label>
                            <div>{{ $judul->judul ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Judul (English)</strong></label>
                            <div>{{ $judul->judul_eng ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Bidang Minat</strong></label>
                            <div>{{ $judul->bidangMinat ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Abstrak</strong></label>
                            <div>{{ $judul->abstrak ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Catatan</strong></label>
                            <div style="background-color: #fff3cd; color: #856404; padding: 10px; border-radius: 5px;">
                                {{ $judul->catatan ?? '-' }}
                            </div>
                        </div>
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
    $(function () {
        $('#form-melengkapi-judul').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = form.serialize();

            var $btn = $('.btn-submit');
            var originalText = $btn.html();
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    form.find('button[type=submit]').prop('disabled', true).text('Menyimpan...');
                },
                success: function(response) {
                    $btn.prop('disabled', false);
                    $btn.html(originalText);
                    swal({
                        title: "Berhasil!",
                        text: response.message || "Data berhasil disimpan.",
                        icon: "success",
                        timer: 2000,
                        buttons: false
                    });
                    window.location.href = "{{ route('mhs.skripsi.daftar.index') }}";
                },
                error: function(xhr) {
                    swal({
                        title: "Gagal!",
                        text: xhr.responseJSON?.message || "Terjadi kesalahan.",
                        icon: "error",
                        timer: 2000,
                        buttons: false
                    });
                },
                complete: function() {
                    form.find('button[type=submit]').prop('disabled', false).text('Simpan');
                }
            });
        });
    });
    </script>

@endsection
