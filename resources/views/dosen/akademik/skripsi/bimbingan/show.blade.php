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
    <li class="breadcrumb-item">Bimbingan</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Riwayat Bimbingan Skripsi -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Judul: {{ $judulSkripsi->judul }}</h5>
                    <h6 class="text-muted">Judul (English): {{ $judulSkripsi->judul_eng }}</h6>
                    <p class="mb-0">Mahasiswa: {{ $mahasiswa->nama }} (NIM: {{ $mahasiswa->nim }})</p>
                </div>
                <div class="card-body">
                    @if($bimbingan->count())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Berkas</th>
                                        <th>Catatan Mahasiswa</th>
                                        <th>Catatan Dosen</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bimbingan as $item)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d F Y') }}</td>
                                            <td>
                                                @if ($item->berkas && $item->berkas->count() > 0)
                                                    <div class="list-group">
                                                        @foreach ($item->berkas as $berkas)
                                                            <a href="{{ asset('storage/' . $berkas->file) }}"
                                                                target="_blank"
                                                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                {{ basename($berkas->file) }}
                                                                <span class="badge bg-primary rounded-pill">Unduh</span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">Tidak ada</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->catatan_mahasiswa }}</td>
                                            <td>
                                                @if(empty($item->catatan_dosen))
                                                    <form action="{{ route('akademik.skripsi.dosen.bimbingan.update', $item->idEnkripsi) }}" method="POST" class="form-catatan-dosen">
                                                        @csrf
                                                        @method('PUT')
                                                        <textarea name="catatanDosen" class="form-control form-control-sm" placeholder="Catatan dosen" rows="2"></textarea>
                                                        <button type="submit" class="btn btn-primary btn-sm mt-2 btn-submit-catatan">Simpan</button>
                                                    </form>
                                                @else
                                                    {{ $item->catatan_dosen }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($item->status == 0) bg-secondary
                                                    @elseif($item->status == 1) bg-success
                                                    @elseif($item->status == 2) bg-info
                                                    @elseif($item->status == 3) bg-warning
                                                    @endif
                                                ">
                                                    {{ $item->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($item->status == 0)
                                                    <form action="{{ route('akademik.skripsi.dosen.bimbingan.update-status', $item->idEnkripsi) }}" method="POST" class="d-flex gap-1 form-update-status">
                                                        @csrf
                                                        @method('PUT')
                                                        <button name="status" value="1" class="btn btn-success btn-sm" title="ACC">ACC</button>
                                                        <button name="status" value="2" class="btn btn-info btn-sm" title="Setuju">Setuju</button>
                                                        <button name="status" value="3" class="btn btn-warning btn-sm" title="Revisi">Revisi</button>
                                                    </form>
                                                @elseif($item->status == 2 && $item->bimbingan_to == $dosen->npp)
                                                    <form action="{{ route('akademik.skripsi.dosen.bimbingan.update-status', $item->idEnkripsi) }}" method="POST" class="d-flex gap-1 form-update-status">
                                                        @csrf
                                                        @method('PUT')
                                                        <button name="status" value="1" class="btn btn-success btn-sm" title="ACC">ACC</button>
                                                        <button name="status" value="3" class="btn btn-warning btn-sm" title="Revisi">Revisi</button>
                                                    </form>
                                                @else
                                                    @if($item->status == 1)
                                                        <span class="badge bg-success">ACC</span>
                                                    @elseif($item->status == 2)
                                                        <span class="badge bg-info">Setuju</span>
                                                    @elseif($item->status == 3)
                                                        <span class="badge bg-warning">Revisi</span>
                                                    @endif
                                                @endif

                                                @if ($item->status != 0)
                                                    <p>Bimbingan dengan: {{ $item->bimbinganKe ?? '' }}</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Belum ada riwayat bimbingan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>

    <script>
    $(function () {
        $('#form-bimbingan').on('submit', function(e) {
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
                    location.redirect = "{{ route('pengajuan-skripsi') }}";
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

        $(document).on('submit', '.form-catatan-dosen', function(e) {
            var $btn = $(this).find('.btn-submit-catatan');
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
        });

        // Untuk banyak form dengan class .form-update-status
        $(document).on('click', '.form-update-status button[type="submit"], .form-update-status button[name="status"]', function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = btn.closest('form');
            var statusValue = btn.val();
            var statusText = btn.text().trim() || 'Update';
            
            swal({
                title: "Konfirmasi",
                text: "Anda yakin ingin mengubah status menjadi '" + statusText + "'?",
                icon: "warning",
                buttons: ["Batal", "Ya"],
                dangerMode: true,
            }).then(function(willUpdate) {
                if (willUpdate) {
                    var formData = form.serializeArray();
                    formData.push({ name: 'status', value: statusValue });

                    $.ajax({
                        url: form.attr('action'),
                        method: form.attr('method'),
                        data: $.param(formData),
                        dataType: 'json',
                        beforeSend: function() {
                            btn.prop('disabled', true).text('Memproses...');
                        },
                        success: function(response) {
                            swal({
                                title: "Berhasil!",
                                text: response.message || "Status berhasil diubah.",
                                icon: "success",
                                timer: 2000,
                                buttons: false
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
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
                            btn.prop('disabled', false).text(statusText);
                        }
                    });
                }
            });
        });
    });
    </script>

@endsection
