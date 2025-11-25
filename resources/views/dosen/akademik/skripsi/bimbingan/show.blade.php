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
                    <h5>Judul: {{ $judulSkripsi->judul }} <small class="text-muted">Pengajuan: {{ \Carbon\Carbon::parse($judulSkripsi->created_at)->format('d/m/Y') }}</small>, <small class="text-muted">Acc: {{ \Carbon\Carbon::parse($judulSkripsi->updated_at)->format('d/m/Y') }}</small></h5>
                    {{-- <h6 class="text-muted">Judul (English): {{ $judulSkripsi->judul_eng }}</h6> --}}
                    <h6 class="text-muted">Bidang Minat: {{ $judulSkripsi->bidang_minat }}</h6>
                    <div class="mt-2">
                        <div class="row align-items-start">
                            <div class="col-md-8">
                                <p class="mb-1"><strong>Pembimbing 1:</strong>
                                    <span>
                                        {{ optional($mahasiswa->dosenPembimbing)->namaPembimbing1
                                            ?? $masterSkripsi->pembimbing1->nama ?? $masterSkripsi->pembimbing_1_nama ?? $masterSkripsi->pembimbing_1 ?? '-' }}
                                    </span>
                                    <small class="text-muted">(
                                        {{ optional($mahasiswa->dosenPembimbing)->nppPembimbing1 ?? '-' }}
                                    )</small>
                                    @if(!empty($masterSkripsi->acc_1_at))
                                        , ACC Sidang: {{ \Carbon\Carbon::parse($masterSkripsi->acc_1_at)->format('d/m/Y H:i:s') }}
                                    @endif
                                </p>

                                <p class="mb-0"><strong>Pembimbing 2:</strong>
                                    @php
                                        $nama2 = optional($mahasiswa->dosenPembimbing)->namaPembimbing2
                                            ?? $masterSkripsi->pembimbing2->nama ?? $masterSkripsi->pembimbing_2_nama ?? $masterSkripsi->pembimbing_2 ?? null;
                                        $npp2 = optional($mahasiswa->dosenPembimbing)->nppPembimbing2
                                            ?? $masterSkripsi->pembimbing2->npp ?? $masterSkripsi->pembimbing_2_npp ?? null;
                                    @endphp

                                    @if($nama2 || $npp2)
                                        {{ $nama2 ?? '-' }}
                                        <small class="text-muted">({{ $npp2 ?? '-' }})</small>
                                        @if(!empty($masterSkripsi->acc_2_at))
                                            , ACC Sidang: {{ \Carbon\Carbon::parse($masterSkripsi->acc_2_at)->format('d/m/Y H:i:s') }}
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>

                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <div class="d-inline-flex gap-2">
                                    <!-- Button: Profil Mahasiswa -->
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalProfilMahasiswa">
                                        <i class="fa fa-user"></i> Profil Mahasiswa
                                    </button>

                                    <!-- Button: KHS Mahasiswa -->
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalKhsMahasiswa">
                                        <i class="fa fa-graduation-cap"></i> KHS Mahasiswa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

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

                <!-- Modal: Profil Mahasiswa -->
                <div class="modal fade" id="modalProfilMahasiswa" tabindex="-1" aria-labelledby="modalProfilMahasiswaLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalProfilMahasiswaLabel">Profil Mahasiswa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-3 text-center">
                                        @if(!empty($mahasiswa->foto_mhs))
                                            <img src="{{ public_path('assets/images/mahasiswa/' . $mahasiswa->foto_mhs) }}" alt="Foto Mahasiswa" class="img-fluid rounded">
                                        @else
                                            <div class="border rounded p-4 text-muted">Tidak ada foto</div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <th>NIM</th>
                                                    <td>{{ $mahasiswa->nim ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Nama</th>
                                                    <td>{{ $mahasiswa->nama ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Program Studi</th>
                                                    <td>{{ $mahasiswa->prodi ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Angkatan</th>
                                                    <td>{{ $mahasiswa->angkatan ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>{{ $mahasiswa->email ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Telepon</th>
                                                    <td>{{ $mahasiswa->hp ?? '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @if(!empty($mahasiswa->alamat))
                                    <div class="mt-3">
                                        <strong>Alamat:</strong>
                                        <p class="mb-0">{{ $mahasiswa->alamat }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal: KHS Mahasiswa -->
                <div class="modal fade" id="modalKhsMahasiswa" tabindex="-1" aria-labelledby="modalKhsMahasiswaLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalKhsMahasiswaLabel">KHS Mahasiswa - {{ $mahasiswa->nama ?? $mahasiswa->full_name ?? '' }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                                <p class="text-muted">IPK: {{ $mahasiswa->ipk !== null ? number_format($mahasiswa->ipk, 2) : '-' }}</p>
                                @if(isset($mahasiswa->daftarNilai) && $mahasiswa->daftarNilai->count())
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Kode Mata Kuliah</th>
                                                    <th>Mata Kuliah</th>
                                                    <th>Tugas</th>
                                                    <th>UTS</th>
                                                    <th>UAS</th>
                                                    <th>Nilai Akhir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($mahasiswa->daftarNilai as $row)
                                                <tr>
                                                        <td style="white-space: nowrap;">{{ $row->kode_matkul ?? '-' }}</td>
                                                        <td style="white-space: nowrap;">{{ $row->nama_matkul ?? '-' }}</td>
                                                        <td>{{ $row->ntugas ?? '-' }}</td>
                                                        <td>{{ $row->nuts ?? '-' }}</td>
                                                        <td>{{ $row->nuas ?? '-' }}</td>
                                                        <td>{{ $row->nakhir ?? '-' }} / {{ $row->nhuruf }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">Belum ada data KHS untuk mahasiswa ini.</p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
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
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d/m/Y') }}</td>
                                            <td>
                                                @if ($item->berkas && $item->berkas->count() > 0)
                                                    <div class="list-group">
                                                        @foreach ($item->berkas as $berkas)
                                                            <a href="{{ asset('storage/' . $berkas->file) }}"
                                                                target="_blank"
                                                                class="list-group-item list-group-item-action d-flex justify-content-center align-items-center"
                                                                @if(Str::endsWith(strtolower($berkas->file), '.pdf')) rel="noopener noreferrer" @endif
                                                                title="{{ basename($berkas->file) }}">
                                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
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
                                                        <a href="{{ asset('storage/' . $item->file_dosen) }}" target="_blank" class="btn btn-sm">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif
                                                @elseif(!empty($item->file_dosen))
                                                    <a href="{{ asset('storage/' . $item->file_dosen) }}" target="_blank" class="btn btn-sm">
                                                        <i class="fa fa-eye"></i>
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
                                                    @if($item->status == 2 && $item->bimbingan_to == $dosen->npp)
                                                        @if(!empty($item->solusi_permasalahan))
                                                            <form action="{{ route('akademik.skripsi.dosen.bimbingan.update-status', $item->idEnkripsi) }}" method="POST" class="d-flex gap-1 form-update-status">
                                                                @csrf
                                                                @method('PUT')
                                                                <button name="status" value="1" class="btn btn-success btn-sm" title="ACC">ACC</button>
                                                                <button name="status" value="3" class="btn btn-warning btn-sm" title="Revisi">Revisi</button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted">Menunggu dosen untuk memberi solusi permasalahan</span>
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
