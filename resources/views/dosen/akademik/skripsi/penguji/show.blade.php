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
    <li class="breadcrumb-item">Penguji</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Riwayat Penguji Skripsi -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Judul (English): {{ $judulSkripsi->judul_eng }}</h6>
                    <p class="mb-0">Mahasiswa: {{ $mahasiswa->nama }} (NIM: {{ $mahasiswa->nim }})</p>
                    {{-- Detail Sidang --}}
                    @if(isset($sidang))
                        <div class="mt-3">
                            <ul class="list-group mb-3">
                                <li class="list-group-item">
                                    <strong>Tanggal Sidang:</strong> 
                                    {{ \Carbon\Carbon::parse($sidang->tanggal)->format('d F Y') }}
                                    @if(\Carbon\Carbon::parse($sidang->tanggal)->isToday())
                                        <span class="badge bg-warning text-dark ms-2">Hari Ini</span>
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    <strong>Waktu:</strong>
                                    {{ $sidang->waktu_mulai }} - {{ $sidang->waktu_selesai }}
                                </li>
                                <li class="list-group-item"><strong>Ruangan:</strong> {{ $sidang->ruangan }}</li>
                                <li class="list-group-item">
                                    <strong>Jenis Sidang:</strong>
                                    @if($sidang->jenis == 1)
                                        Sidang Terbuka
                                    @elseif($sidang->jenis == 2)
                                        Sidang Tertutup
                                    @else
                                        {{ $sidang->jenis }}
                                    @endif
                                </li>
                                <li class="list-group-item"><strong>Status:</strong> 
                                    @if($sidang->status == 1)
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($sidang->status == 2 && $penguji->status == 1)
                                        <span class="badge bg-info">Sudah Ternilai (Sidang Masih Berjalan)</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $sidang->status_label ?? 'Belum Validasi' }}</span>
                                    @endif
                                </li>
                            </ul>

                            {{-- Form Input Nilai Sidang (jika tanggal sidang = hari ini) --}}
                            @if(\Carbon\Carbon::parse($sidang->tanggal)->isToday() || \Carbon\Carbon::parse($sidang->tanggal)->isPast())
                                @if($penguji->status == 1)
                                    <div class="mb-2">
                                        <label class="form-label mb-1">Nilai</label>
                                        <div class="form-control-plaintext">
                                            <blockquote class="blockquote border-start border-3 ps-3 fst-italic text-muted">
                                                {{ $penguji->nilai ?: '-' }}
                                            </blockquote>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-1">Catatan Penguji</label>
                                        <div class="form-control-plaintext">
                                            <blockquote class="blockquote border-start border-3 ps-3 fst-italic text-muted">
                                                {{ $penguji->catatan ?: '-' }}
                                            </blockquote>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('akademik.skripsi.dosen.penguji.update-nilai', $sidang->idEnkripsi) }}" method="POST" class="mb-3" id="form-nilai-sidang">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-2">
                                            <label for="nilai" class="form-label">Nilai Sidang</label>
                                            <input type="number" name="nilai" id="nilai" class="form-control" min="0" max="100" value="{{ $penguji->nilai }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label for="catatan" class="form-label">Catatan Penguji</label>
                                            <textarea name="catatan" id="catatan" class="form-control" rows="2">{{ $penguji->catatan }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm btn-nilai" title="Simpan Nilai">Simpan</button>
                                    </form>
                                    {{-- Tombol Publish dan Validasi --}}
                                    <div class="d-flex justify-content-end">
                                        <form action="{{ route('akademik.skripsi.dosen.penguji.update-status', $sidang->idEnkripsi) }}" method="POST" id="form-status-sidang">
                                            @csrf
                                            @method('PUT')
                                            <button name="status" type="submit" class="btn btn-success btn-sm" title="Validasi Nilai" id="btn-validasi">Validasi</button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
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
        $('#form-penguji').on('submit', function(e) {
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

        $(document).on('submit', '#form-nilai-sidang', function(e) {
            var $btn = $(this).find('button[type="submit"]');
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
        });

        $(document).on('click', '#btn-validasi', function(e) {
            e.preventDefault();
            var $form = $(this).closest('form');
            var $btn = $(this);
            swal({
            title: "Konfirmasi Validasi",
            text: "Setelah divalidasi, Anda tidak dapat mengubah nilai lagi. Lanjutkan?",
            icon: "warning",
            buttons: ["Batal", "Ya, Validasi"],
            dangerMode: true,
            }).then(function(willValidate) {
            if (willValidate) {
                $btn.prop('disabled', true);
                $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                $form.submit();
            }
            });
        });

        $(document).on('submit', '.form-catatan-dosen', function(e) {
            var $btn = $(this).find('.btn-submit-catatan');
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
        });

        // Untuk banyak form dengan class .form-update-status
        $(document).on('click', '.form-update-status button[type="submit"], .form-update-status button[name="status"]', f        benarkan, ketika di klik button submitnya baru muncul kan notifunction(e) {
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
