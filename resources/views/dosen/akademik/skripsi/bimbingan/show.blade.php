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
                    <h6 class="text-muted">Bidang Minat: {{ $judulSkripsi->bidang_minat }}</h6>
                    @if(isset($pengajuanSidang) && $pengajuanSidang)
                        <div class="alert mt-2 mb-0">
                            <strong>Pengajuan Sidang:</strong><br>
                            <ul class="mb-0 ps-3">
                                <li><b>Gelombang:</b> {{ $pengajuanSidang->namaGelombang ?? '-' }}</li>
                                <li>
                                    <b>Jenis Sidang:</b>
                                    @if($pengajuanSidang->jenis == 1)
                                        Seminar Proposal
                                    @elseif($pengajuanSidang->jenis == 2)
                                        Seminar Hasil
                                    @else
                                        -
                                    @endif
                                </li>
                                <li>
                                    <b>Tanggal:</b>
                                    {{ \Carbon\Carbon::parse($pengajuanSidang->tanggal)->translatedFormat('d F Y') ?? '-' }}
                                </li>
                                <li><b>Waktu:</b> {{ $pengajuanSidang->waktuMulai ?? '-' }} - {{ $pengajuanSidang->waktuSelesai ?? '-' }}</li>
                                <li><b>Ruangan:</b> {{ $pengajuanSidang->namaRuang ?? '-' }}</li>
                                @if($pengajuanSidang->kartuBimbingan)
                                    <li>
                                        <b>Kartu Bimbingan:</b>
                                        <a href="{{ asset('berkas-sidang/' . $pengajuanSidang->kartuBimbingan) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                                    </li>
                                @endif
                                @if($pengajuanSidang->presentasi)
                                    <li>
                                        <b>Berkas Presentasi:</b>
                                        <a href="{{ asset('berkas-sidang/' . $pengajuanSidang->presentasi) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                                    </li>
                                @endif
                                @if($pengajuanSidang->pendukung)
                                    <li>
                                        <b>Berkas Pendukung:</b>
                                        <a href="{{ asset('berkas-sidang/' . $pengajuanSidang->pendukung) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                                    </li>
                                @endif
                            </ul>

                            {{-- Form Input Nilai Tugas Akhir --}}
                            <form action="{{ route('akademik.skripsi.dosen.bimbingan.penilaian', $pengajuanSidang->idEnkripsi ?? '') }}" method="POST" class="mt-3" id="form-penilaian">
                                @csrf
                                @method('PUT')
                                @php
                                    $isDisabled = (isset($penilaian) && isset($penilaian->status) && $penilaian->status == 1) ? 'disabled' : '';
                                @endphp
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Penulisan</label>
                                        <div class="mb-2 ms-3 d-flex align-items-center gap-2">
                                            <label class="mb-0 flex-grow-1">
                                                Konsistensi penulisan dan kesesuaian dengan aturan
                                                <span class="text-muted">(10 - 15)</span>
                                            </label>
                                            <input type="number" name="konsistensiPenulisan" class="form-control form-control-sm w-auto" min="10" max="15" step="1" required value="{{ old('konsistensiPenulisan', $penilaian->konsistensi_penulisan ?? '') }}" {{ $isDisabled }}>
                                            <label class="mb-0 flex-grow-1">
                                                Penelusuran pustaka
                                                <span class="text-muted">(10 - 15)</span>
                                            </label>
                                            <input type="number" name="penelusuran" class="form-control form-control-sm w-auto" min="10" max="15" step="1" required value="{{ old('penelusuran', $penilaian->penelusuran ?? '') }}" {{ $isDisabled }}>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Sikap</label>
                                        <div class="mb-2 ms-3 d-flex align-items-center gap-2">
                                            <label class="mb-0 flex-grow-1">
                                                Kontribusi dan keterlibatan ide
                                                <span class="text-muted">(10 - 15)</span>
                                            </label>
                                            <input type="number" name="kontribusi" class="form-control form-control-sm w-auto" min="10" max="15" step="1" required value="{{ old('kontribusi', $penilaian->kontribusi ?? '') }}" {{ $isDisabled }}>
                                            <label class="mb-0 flex-grow-1">
                                                Kontinuitas dan ketekunan
                                                <span class="text-muted">(10 - 15)</span>
                                            </label>
                                            <input type="number" name="ketekunan" class="form-control form-control-sm w-auto" min="10" max="15" step="1" required value="{{ old('ketekunan', $penilaian->ketekunan ?? '') }}" {{ $isDisabled }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Kedalaman Materi</label>
                                        <div class="mb-2 ms-3 d-flex align-items-center gap-2">
                                            <label class="mb-0 flex-grow-1">
                                                Penguasaan materi
                                                <span class="text-muted">(15 - 20)</span>
                                            </label>
                                            <input type="number" name="penguasaan" class="form-control form-control-sm w-auto" min="15" max="20" step="1" required value="{{ old('penguasaan', $penilaian->penguasaan ?? '') }}" {{ $isDisabled }}>
                                            <label class="mb-0 flex-grow-1">
                                                Relevansi latar belakang, rumusan masalah, dan metodologi penelitian
                                                <span class="text-muted">(15 - 20)</span>
                                            </label>
                                            <input type="number" name="menemukanRelevansi" class="form-control form-control-sm w-auto" min="15" max="20" step="1" required value="{{ old('menemukanRelevansi', $penilaian->menemukan_relevansi ?? '') }}" {{ $isDisabled }}>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Rekapitulasi</label>
                                        <div class="mb-2 ms-3">
                                            <label>
                                                Jumlah (70-100)
                                            </label>
                                            <input type="number" name="jumlahNilai" class="form-control form-control-sm" min="70" max="100" step="1" readonly value="{{ old('jumlahNilai', $penilaian->jumlah_nilai ?? '') }}">
                                        </div>
                                        <div class="mb-2 ms-3">
                                            <label>
                                                Nilai Akhir proses penyusunan proposal (30%)
                                            </label>
                                            <input type="number" name="nilaiAkhir" class="form-control form-control-sm" min="0" max="100" step="0.01" readonly value="{{ old('nilaiAkhir', $penilaian->nilai_akhir ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                @if (!isset($penilaian) || !isset($penilaian->status) || $penilaian->status != 1)
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary btn-sm">Simpan Nilai</button>
                                    </div>
                                @endif
                            </form>

                            {{-- Form Validasi Nilai --}}
                            @if(isset($penilaian) && $penilaian->status == 0)
                                <form action="{{ route('akademik.skripsi.dosen.bimbingan.validasi-nilai', $pengajuanSidang->idEnkripsi ?? '') }}" method="POST" class="mt-2" id="form-validasi-nilai">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Validasi Nilai
                                    </button>
                                </form>
                            @elseif(isset($penilaian) && $penilaian->status == 1)
                                <span class="badge bg-success mt-2">Nilai telah divalidasi</span>
                            @endif
                        </div>
                    @endif
                    @php
                        $accField = 'acc_' . $dosen->pembimbingKe;
                    @endphp
                    @if($isSidang && $masterSkripsi->$accField == 0)
                        <div class="mt-2">
                            <p class="mb-2"><strong>Persetujuan Sidang:</strong></p>
                            <form action="{{ route('akademik.skripsi.dosen.bimbingan.acc-sidang', $masterSkripsi->idEnkripsi) }}" method="POST" class="d-inline" id="form-acc-sidang">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="pembimbingKe" value="{{ $dosen->pembimbingKe }}">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-check"></i> Setujui Mahasiswa untuk Sidang
                                </button>
                            </form>
                            @if($masterSkripsi->acc_1)
                                <span class="badge bg-info mt-2">Pembimbing 1 telah menyetujui untuk maju ke sidang</span>
                            @endif
                            @if($masterSkripsi->acc_2)
                                <span class="badge bg-info mt-2">Pembimbing 2 telah menyetujui untuk maju ke sidang</span>
                            @endif
                        </div>
                    @else
                        <div class="mt-2">
                            <p class="mb-2"><strong>Status Persetujuan Sidang:</strong></p>
                            @if($masterSkripsi->acc_1)
                                <span class="badge bg-success mt-2">Pembimbing 1 telah menyetujui untuk maju ke sidang</span>
                            @endif
                            @if($masterSkripsi->acc_2)
                                <span class="badge bg-success mt-2">Pembimbing 2 telah menyetujui untuk maju ke sidang</span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if($bimbingan->count())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Berkas</th>
                                        <th>Permasalahan</th>
                                        <th>Catatan Mahasiswa</th>
                                        <th>Solusi Permasalahan</th>
                                        <th>File</th>
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
                                                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                                @if(Str::endsWith(strtolower($berkas->file), '.pdf')) rel="noopener noreferrer" @endif>>
                                                                {{ basename($berkas->file) }}
                                                                <span class="badge bg-primary rounded-pill">Unduh</span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">Tidak ada</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->permasalahan }}</td>
                                            <td>{{ $item->catatan_mahasiswa }}</td>
                                            <td>
                                                @if(empty($item->solusi_permasalahan) && $item->bimbingan_to == $dosen->npp)
                                                    <form action="{{ route('akademik.skripsi.dosen.bimbingan.update', $item->idEnkripsi) }}" method="POST" class="form-solusi-permasalahan">
                                                        @csrf
                                                        @method('PUT')
                                                        <textarea name="solusiPermasalahan" class="form-control form-control-sm" placeholder="Solusi Permasalahan" rows="2"></textarea>
                                                        <button type="submit" class="btn btn-primary btn-sm mt-2 btn-submit-catatan">Simpan</button>
                                                    </form>
                                                @else
                                                    {{ $item->solusi_permasalahan }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->status == 2 && $item->bimbingan_to == $dosen->npp)
                                                    @if(empty($item->file_dosen))
                                                        <form action="{{ route('akademik.skripsi.dosen.bimbingan.upload-revisi', $item->idEnkripsi) }}" method="POST" enctype="multipart/form-data" class="form-upload-revisi">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="file" name="fileRevisi" class="form-control form-control-sm mb-2" accept=".pdf,.doc,.docx" required>
                                                            <button type="submit" class="btn btn-primary btn-sm btn-submit-revisi">Upload File</button>
                                                        </form>
                                                    @else
                                                        <a href="{{ asset('storage/' . $item->file_dosen) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            Lihat File
                                                        </a>
                                                    @endif
                                                @elseif(!empty($item->file_dosen))
                                                    <a href="{{ asset('storage/' . $item->file_dosen) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        Lihat File
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($item->status == 0) bg-secondary
                                                    @elseif($item->status == 1) bg-success
                                                    @elseif($item->status == 2) bg-info
                                                    @elseif($item->status == 3) bg-warning
                                                    @elseif($item->status == 4) bg-danger
                                                    @endif
                                                ">
                                                    {{ $item->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($item->bimbingan_to == $dosen->npp)
                                                    @if($item->status == 0)
                                                        <form action="{{ route('akademik.skripsi.dosen.bimbingan.update-status', $item->idEnkripsi) }}" method="POST" class="d-flex gap-1 form-update-status">
                                                            @csrf
                                                            @method('PUT')
                                                            <button name="status" value="2" class="btn btn-info btn-sm" title="Setuju">Setuju</button>
                                                            <button name="status" value="4" class="btn btn-danger btn-sm" title="Tolak">Tolak</button>
                                                        </form>
                                                    @elseif($item->status == 2 && $item->bimbingan_to == $dosen->npp)
                                                        @if(!empty($item->file_dosen))
                                                            <form action="{{ route('akademik.skripsi.dosen.bimbingan.update-status', $item->idEnkripsi) }}" method="POST" class="d-flex gap-1 form-update-status">
                                                                @csrf
                                                                @method('PUT')
                                                                <button name="status" value="1" class="btn btn-success btn-sm" title="ACC">ACC</button>
                                                                <button name="status" value="3" class="btn btn-warning btn-sm" title="Revisi">Revisi</button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted">Menunggu file dosen untuk tindakan</span>
                                                        @endif
                                                    @else
                                                        @if($item->status == 1)
                                                            <span class="badge bg-success">ACC</span>
                                                        @elseif($item->status == 2)
                                                            <span class="badge bg-info">Setuju</span>
                                                        @elseif($item->status == 3)
                                                            <span class="badge bg-warning">Revisi</span>
                                                        @elseif($item->status == 4)
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @endif
                                                    @endif
                                                @else
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

            // Handle form acc sidang submit via AJAX
            $(document).on('submit', '#form-acc-sidang', function(e) {
                e.preventDefault();
                var form = $(this);
                var $btn = form.find('button[type=submit]');
                var originalText = $btn.html();

                swal({
                title: "Konfirmasi",
                text: "Anda yakin ingin menyetujui mahasiswa untuk mengajukan sidang?",
                icon: "warning",
                buttons: ["Batal", "Ya, Setujui"],
                dangerMode: true,
                }).then(function(willApprove) {
                if (willApprove) {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                    $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        swal({
                        title: "Berhasil!",
                        text: response.message || "Berhasil menyetujui sidang.",
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
                        $btn.prop('disabled', false).html(originalText);
                    }
                    });
                }
                });
            });

            $(document).on('submit', '#form-penilaian', function(e) {
                e.preventDefault();
                var form = $(this);
                var $btn = form.find('button[type=submit]');
                var originalText = $btn.html();

                swal({
                    title: "Konfirmasi",
                    text: "Anda yakin ingin menyimpan nilai?",
                    icon: "warning",
                    buttons: ["Batal", "Ya, Simpan"],
                    dangerMode: true,
                }).then(function(willApprove) {
                if (willApprove) {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                    $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        swal({
                            title: "Berhasil!",
                            text: response.message || "Nilai berhasil disimpan.",
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
                        $btn.prop('disabled', false).html(originalText);
                    }
                    });
                }
                });
            });

            $(document).on('submit', '#form-validasi-nilai', function(e) {
                e.preventDefault();
                var form = $(this);
                var $btn = form.find('button[type=submit]');
                var originalText = $btn.html();

                swal({
                    title: "Konfirmasi",
                    text: "Anda yakin ingin memvalidasi nilai?\n\nCatatan: Jika ada perubahan nilai, silakan simpan nilai terlebih dahulu sebelum validasi.",
                    icon: "warning",
                    buttons: ["Batal", "Ya, Validasi"],
                    dangerMode: true,
                }).then(function(willApprove) {
                if (willApprove) {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                    $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        swal({
                            title: "Berhasil!",
                            text: response.message || "Berhasil divalidasi.",
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
                        $btn.prop('disabled', false).html(originalText);
                    }
                    });
                }
                });
            });

            $(document).on('submit', '.form-solusi-permasalahan', function(e) {
                var $btn = $(this).find('.btn-submit-catatan');
                $btn.prop('disabled', true);
                $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
            });

            $(document).on('submit', '.form-upload-revisi', function(e) {
                var $btn = $(this).find('.btn-submit-revisi');
                $btn.prop('disabled', true);
                $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengupload...');
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

            var $inputs = $('[name="konsistensiPenulisan"], [name="penelusuran"], [name="kontribusi"], [name="ketekunan"], [name="penguasaan"], [name="menemukanRelevansi"]');
            $inputs.on('input', function() {
                hitungJumlahDanNilaiAkhir();
            });
            hitungJumlahDanNilaiAkhir();
        });

        function hitungJumlahDanNilaiAkhir() {
            let konsistensi = parseInt(document.querySelector('[name="konsistensiPenulisan"]').value) || 0;
            let pustaka = parseInt(document.querySelector('[name="penelusuran"]').value) || 0;
            let kontribusi = parseInt(document.querySelector('[name="kontribusi"]').value) || 0;
            let kontinuitas = parseInt(document.querySelector('[name="ketekunan"]').value) || 0;
            let penguasaan = parseInt(document.querySelector('[name="penguasaan"]').value) || 0;
            let relevansi = parseInt(document.querySelector('[name="menemukanRelevansi"]').value) || 0;
            let jumlah = konsistensi + pustaka + kontribusi + kontinuitas + penguasaan + relevansi;
            let nilaiAkhir = jumlah * 0.3;
            document.querySelector('[name="jumlahNilai"]').value = jumlah;
            document.querySelector('[name="nilaiAkhir"]').value = nilaiAkhir.toFixed(2);
        }
    </script>

@endsection
