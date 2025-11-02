@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons/bootstrap-icons.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{ $title }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Bimbingan</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
    <div class="container mt-3">
        @if (session('message'))
            <div class="alert alert-{{ session('status') === 'success' ? 'success' : 'danger' }} alert-dismissible fade show"
                role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <div class="page-content" id="bimbingan-page">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pembimbing</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Pembimbing 1</strong>
                                @if(!empty($masterSkripsi->acc_1) && $masterSkripsi->acc_1 == 1)
                                    <span class="badge bg-success">Sidang <i class="bi bi-check-circle" title="Sudah disetujui"></i></span>
                                @else
                                    {{-- <i class="bi bi-x-circle text-secondary" title="Belum disetujui"></i> --}}
                                @endif
                                <ul class="list-unstyled mb-0">
                                    <li>Nama: {{ $masterSkripsi->nama_pembimbing1 ?? '-' }}</li>
                                    <li>NPP: {{ $masterSkripsi->npp_pembimbing1 ?? '-' }}</li>
                                    <li>Email: {{ $masterSkripsi->email_pembimbing1 ?? '-' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <strong>Pembimbing 2</strong>
                                @if(!empty($masterSkripsi->acc_2) && $masterSkripsi->acc_2 == 1)
                                    <span class="badge bg-success">Sidang <i class="bi bi-check-circle" title="Sudah disetujui"></i></span>
                                @else
                                    {{-- <i class="bi bi-x-circle text-secondary" title="Belum disetujui"></i> --}}
                                @endif
                                <ul class="list-unstyled mb-0">
                                    <li>Nama: {{ $masterSkripsi->nama_pembimbing2 ?? '-' }}</li>
                                    <li>NPP: {{ $masterSkripsi->npp_pembimbing2 ?? '-' }}</li>
                                    <li>Email: {{ $masterSkripsi->email_pembimbing2 ?? '-' }}</li>
                                </ul>
                            </div>
                        </div>
                        @if(!empty($masterSkripsi->acc_1) && $masterSkripsi->acc_1 == 1 && !empty($masterSkripsi->acc_2) && $masterSkripsi->acc_2 == 1)
                            <div class="alert alert-success d-flex align-items-center mt-2" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                <div>Kedua pembimbing telah menyetujui. Anda dapat mengajukan sidang. <a href="{{ route('mhs.skripsi.daftar.index') }}">Disini</a></div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Jadwal Bimbingan</h5>
                        <a href="{{ route('mhs.skripsi.bimbingan.download-logbook', ['nimEnkripsi' => $masterSkripsi->nimEnkripsi]) }}" class="btn btn-outline-success btn-sm ms-2" target="_blank">
                            <i class="bi bi-download"></i> Download Logbook
                        </a>
                        @if(empty($judulSkripsi))
                            <div class="alert alert-warning mb-0" role="alert">
                                <i class="bi bi-exclamation-circle"></i>
                                Selesaikan pengajuan Judul Skripsi terlebih dahulu sebelum mengajukan bimbingan.
                            </div>
                        @else
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#tambahBimbinganModal">
                                <i class="bi bi-plus-circle"></i> Ajukan Bimbingan
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Permasalahan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bimbingan as $index => $item)
                                    <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d/m/Y') }}</td>
                                      
                                            <td>{{ $item->permasalahan }}</td>
                                            <td>
                                                @switch($item->status)
                                                    @case(0)
                                                        <span class="badge bg-warning">Menunggu</span>
                                                    @break

                                                    @case(1)
                                                        <span class="badge bg-info">ACC</span>
                                                    @break

                                                    @case(2)
                                                        <span class="badge bg-success">Disetujui</span>
                                                    @break

                                                    @case(3)
                                                        <span class="badge bg-danger">Revisi</span>
                                                    @break

                                                    @case(4)
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @break

                                                    @default
                                                        <span class="badge bg-secondary">-</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-id="{{ $item->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#detailBimbinganModal">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                @if ($item->status == 0 || $item->status == 3)
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#editBimbinganModal"
                                                        onclick="loadEditBimbingan({{ $item->id }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="deleteBimbingan({{ $item->id }})">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Belum ada jadwal bimbingan</td>
                                            </tr>
                                        @endforelse
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
                            <h5 class="card-title mb-0">Catatan Bimbingan</h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="accordionBimbingan">
                                {{-- @forelse($bimbingan->where('status', '!=', 0) as $index => $item) --}}
                                @forelse($bimbingan as $index => $item)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $item->id }}">
                                            <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $item->id }}"
                                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $item->id }}">
                                                Bimbingan #{{ $loop->iteration }} -
                                                {{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d F Y') }}
                                                @switch($item->status)
                                                    @case(1)
                                                        <span class="badge bg-info ms-2">ACC</span>
                                                    @break

                                                    @case(2)
                                                        <span class="badge bg-success ms-2">Disetujui</span>
                                                    @break

                                                    @case(3)
                                                        <span class="badge bg-danger ms-2">Revisi</span>
                                                    @break
                                                @endswitch
                                                @if(!empty($item->bimbinganKe))
                                                    <span class="ms-2">Bimbingan Ke: {{ $item->bimbinganKe }}</span>
                                                @endif
                                                <span class="badge bg-{{ $item->status == 1 ? 'info' : ($item->status == 2 ? 'success' : 'danger') }} ms-2">{{ $item->status_label }}</span>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $item->id }}"
                                            class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                            aria-labelledby="heading{{ $item->id }}" data-bs-parent="#accordionBimbingan">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h6>Permasalahan:</h6>
                                                        <p>{{ $item->permasalahan ?? '-' }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h6>Catatan Mahasiswa:</h6>
                                                        <p>{{ $item->catatan_mahasiswa ?? '-' }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h6>Solusi Permasalahan:</h6>
                                                        <p>{{ $item->solusi_permasalahan ?? 'Belum ada solusi permasalahan dari dosen' }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h6>File Dosen:</h6>
                                                        @if(!empty($item->file_dosen))
                                                            <a href="{{ asset('storage/' . $item->file_dosen) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                Lihat File
                                                            </a>
                                                        @else
                                                            <span class="text-muted">Tidak ada file</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if ($item->berkas && $item->berkas->count() > 0)
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <h6>File Terkait:</h6>
                                                            <div class="list-group">
                                                                @foreach ($item->berkas as $berkas)
                                                                    <a href="{{ asset('storage/' . $berkas->file) }}"
                                                                        target="_blank"
                                                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                                        @if(Str::endsWith(strtolower($berkas->file), '.pdf')) rel="noopener noreferrer" @endif>
                                                                        {{ basename($berkas->file) }}
                                                                        <span class="badge bg-primary rounded-pill">Unduh</span>
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                        <div class="text-center py-4">
                                            <p class="text-muted">Belum ada catatan bimbingan</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Tambah Bimbingan -->
                <div class="modal fade" id="tambahBimbinganModal" tabindex="-1" aria-labelledby="tambahBimbinganModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="formBimbingan" method="POST" action="{{ route('mhs.skripsi.bimbingan.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tambahBimbinganModalLabel">Ajukan Jadwal Bimbingan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="tanggalBimbingan" class="form-label">Tanggal Bimbingan</label>
                                        <input type="date" class="form-control" name="tanggal" id="tanggalBimbingan"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pembimbing" class="form-label">Pembimbing</label>
                                        <select class="form-control" name="pembimbing" id="pembimbing" required>
                                            <option value="">Pilih Pembimbing</option>
                                            @if($masterSkripsi->npp_pembimbing1)
                                                <option value="{{ $masterSkripsi->npp_pembimbing1 }}">{{ $masterSkripsi->nama_pembimbing1 }}</option>
                                            @endif
                                            @if($masterSkripsi->npp_pembimbing2)
                                                <option value="{{ $masterSkripsi->npp_pembimbing2 }}">{{ $masterSkripsi->nama_pembimbing2 }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="metode" class="form-label">Metode</label>
                                        <select class="form-control" name="metode" id="metode" required>
                                            <option value="">Pilih Metode</option>
                                            <option value="Offline">Offline</option>
                                            <option value="Online">Online</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="permasalahan" class="form-label">Permasalahan</label>
                                        <textarea class="form-control" name="permasalahan" id="permasalahan" rows="3" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="catatan" class="form-label">Catatan</label>
                                        <textarea class="form-control" name="catatan" id="catatan" rows="3" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">File Pendukung</label>
                                        <div id="fileInputs">
                                            <div class="input-group mb-2">
                                                <input type="file" name="filePendukung[]" class="form-control"
                                                    accept=".pdf,.docx,.doc,.zip,.rar,.jpg,.png" />
                                                <button type="button" class="btn btn-outline-secondary add-file-input">+</button>
                                            </div>
                                        </div>
                                        <div class="form-text">Unggah file pendukung seperti draft skripsi, data, atau materi
                                            presentasi. (Max: 2MB per file)</div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary" id="submitBimbinganBtn">Ajukan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Bimbingan -->
                <div class="modal fade" id="detailBimbinganModal" tabindex="-1" aria-labelledby="detailBimbinganModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Bimbingan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h6>Informasi Bimbingan:</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td>Tanggal</td>
                                                <td>:</td>
                                                <td id="tglBimbingan"></td>
                                            </tr>
                                            <tr>
                                                <td>Permasalahan</td>
                                                <td>:</td>
                                                <td id="detail-permasalahan"></td>
                                            </tr>
                                            <tr>
                                                <td>Solusi Permasalahan</td>
                                                <td>:</td>
                                                <td id="solusi-permasalahan"></td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>:</td>
                                                <td><span id="statusBimbingan" class="badge"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Metode</td>
                                                <td>:</td>
                                                <td><span id="metodeBimbingan" class="badge"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h6>Catatan Mahasiswa:</h6>
                                        <div class="card bg-light mb-3 text-dark">
                                            <div class="card-body" id="catatanMahasiswa"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h6>Catatan Dosen:</h6>
                                        <div class="card bg-light mb-3 text-dark">
                                            <div class="card-body" id="catatanDosen"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h6>File Terkait:</h6>
                                        <div class="list-group" id="fileTerkait"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal Edit Bimbingan -->
                <div class="modal fade" id="editBimbinganModal" tabindex="-1" aria-labelledby="editBimbinganModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="formEditBimbingan" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editBimbinganModalLabel">Edit Jadwal Bimbingan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body" id="editBimbinganContent">
                                    <!-- Content will be loaded dynamically -->
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @endsection

        @section('script')
            <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
            <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                $(document).ready(function() {
                    $('#fileInputs').on('click', '.add-file-input', function() {
                        const newInput = `
                            <div class="input-group mb-2">
                            <input type="file" name="filePendukung[]" class="form-control" />
                            <button type="button" class="btn btn-outline-danger remove-file-input">-</button>
                            </div>`;
                        $('#fileInputs').append(newInput);
                    });

                    $('#fileInputs').on('click', '.remove-file-input', function() {
                        $(this).closest('.input-group').remove();
                    });

                    $('#detailBimbinganModal').on('show.bs.modal', function(e) {
                        let button = $(e.relatedTarget); // tombol yang diklik
                        let id = button.data('id');
                        let modal = $(this);
                        modal.find('#detailBimbinganContent').html(
                            '<div class="text-center p-3">Memuat <i class="spinner-border spinner-border-sm"></i></div>'
                        );
                        let url = "{{ route('mhs.skripsi.bimbingan.detail', ['id' => '__id__']) }}".replace(
                            '__id__', id);

                        $.ajax({
                            url: url,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                let detail = data.html; // karena objek detail ada di `html`
                                let tanggal = new Date(detail.tanggal_waktu);
                                let formattedTanggal = tanggal.toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'long',
                                    year: 'numeric'
                                });
                                $('#tglBimbingan').text(formattedTanggal);
                                $('#tempatBimbingan').text(detail.tempat ?? '-');
                                $('#detail-permasalahan').text(detail.permasalahan ?? '-');
                                $('#solusi-permasalahan').text(detail.solusi_permasalahan ?? '-');
                                $('#catatanMahasiswa').html(detail.catatan_mahasiswa ?? '-');
                                $('#catatanDosen').html(detail.catatan_dosen ?? '-');

                                let statusText = '',
                                    statusClass = '';
                                switch (detail.status) {
                                    case 0:
                                        statusText = 'Menunggu';
                                        statusClass = 'bg-warning';
                                        break;
                                    case 1:
                                        statusText = 'ACC';
                                        statusClass = 'bg-info';
                                        break;
                                    case 2:
                                        statusText = 'Disetujui';
                                        statusClass = 'bg-success';
                                        break;
                                    case 3:
                                        statusText = 'Revisi';
                                        statusClass = 'bg-danger';
                                        break;
                                    default:
                                        statusText = '-';
                                        statusClass = 'bg-secondary';
                                }
                                $('#statusBimbingan').text(statusText).removeClass().addClass('badge ' +
                                    statusClass);

                                let metodeText = '',
                                    metodeClass = '';
                                switch (detail.metode) {
                                    case "Offline":
                                        metodeText = 'Offline';
                                        metodeClass = 'bg-warning';
                                        break;
                                    case "Online":
                                        metodeText = 'Online';
                                        metodeClass = 'bg-success';
                                        break;
                                    default:
                                        metodeText = '-';
                                        metodeClass = 'bg-secondary';
                                }
                                $('#metodeBimbingan').text(metodeText).removeClass().addClass('badge ' +
                                    metodeClass);

                                // Render file
                                let fileHtml = '';
                                if (detail.berkas.length > 0) {
                                    detail.berkas.forEach(file => {
                                        let fileName = file.file.split('/').pop();
                                        fileHtml += `
                                            <a href="/storage/${file.file}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" target="_blank">
                                                ${fileName}
                                                <span class="badge bg-primary rounded-pill">Unduh</span>
                                            </a>`;
                                    });
                                } else {
                                    fileHtml = `<span class="text-muted">Tidak ada file</span>`;
                                }
                                $('#fileTerkait').html(fileHtml);
                            },
                            error: function(xhr) {
                                alert('Gagal memuat detail bimbingan.');
                            }
                        });
                    });

                    $('#formBimbingan').on('submit', function(e) {
                        var $btn = $('#submitBimbinganBtn');
                        $btn.prop('disabled', true);
                        $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
                    });
                });

                function loadEditBimbingan(id) {
                    $('#editBimbinganContent').html('<div class="text-center p-3">Memuat <i class="spinner-border spinner-border-sm"></i></div>');
                    let url = "{{ route('mhs.skripsi.bimbingan.edit', ['id' => '__id__']) }}".replace('__id__', id);

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            let detail = data.html;
                            let fileInputsHtml = '';
                            if (detail.berkas && detail.berkas.length > 0) {
                                detail.berkas.forEach(function(file, idx) {
                                    let fileName = file.file.split('/').pop();
                                    fileInputsHtml += `
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" value="${fileName}" readonly>
                                            <a href="/storage/${file.file}" target="_blank" class="btn btn-outline-primary">Unduh</a>
                                        </div>
                                    `;
                                });
                            }
                            fileInputsHtml += `
                                <div id="fileInputsEdit">
                                    <div class="input-group mb-2">
                                        <input type="file" name="filePendukung[]" class="form-control" accept=".pdf,.docx,.doc,.zip,.rar,.jpg,.png" />
                                        <button type="button" class="btn btn-outline-secondary add-file-input-edit">+</button>
                                    </div>
                                </div>
                                <div class="form-text">Unggah file pendukung seperti draft skripsi, data, atau materi presentasi. (Max: 2MB per file)</div>
                            `;

                            let html = `
                                <div class="mb-3">
                                    <label for="editTanggalBimbingan" class="form-label">Tanggal Bimbingan</label>
                                    <input type="date" class="form-control" name="tanggal" id="editTanggalBimbingan" value="${detail.tanggal_waktu ? detail.tanggal_waktu.substring(0,10) : ''}" required placeholder="yyyy-mm-dd">
                                </div>
                                <div class="mb-3">
                                    <label for="editMetode" class="form-label">Metode</label>
                                    <select class="form-control" name="metode" id="editMetode" required>
                                        <option value="">Pilih Metode</option>
                                        <option value="Offline" ${detail.metode === 'Offline' ? 'selected' : ''}>Offline</option>
                                        <option value="Online" ${detail.metode === 'Online' ? 'selected' : ''}>Online</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="editPermasalahan" class="form-label">Topik Bimbingan</label>
                                    <textarea class="form-control" name="permasalahan" id="editPermasalahan" rows="3" required>${detail.permasalahan ?? ''}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="editCatatan" class="form-label">Catatan</label>
                                    <textarea class="form-control" name="catatan" id="editCatatan" rows="3" required>${detail.catatan_mahasiswa ?? ''}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">File Pendukung</label>
                                    ${fileInputsHtml}
                                </div>
                            `;
                            $('#editBimbinganContent').html(html);

                            // Set form action
                            $('#formEditBimbingan').attr('action', "{{ route('mhs.skripsi.bimbingan.update', ['id' => '__id__']) }}".replace('__id__', id));

                            // Dinamis file input untuk edit
                            $('#editBimbinganContent').on('click', '.add-file-input-edit', function() {
                                const newInput = `
                                    <div class="input-group mb-2">
                                        <input type="file" name="filePendukung[]" class="form-control" accept=".pdf,.docx,.doc,.zip,.rar,.jpg,.png" />
                                        <button type="button" class="btn btn-outline-danger remove-file-input-edit">-</button>
                                    </div>`;
                                $('#fileInputsEdit').append(newInput);
                            });

                            $('#editBimbinganContent').on('click', '.remove-file-input-edit', function() {
                                $(this).closest('.input-group').remove();
                            });
                        },
                        error: function(xhr) {
                            $('#editBimbinganContent').html('<div class="text-danger text-center">Gagal memuat data.</div>');
                        }
                    });
                }

                function deleteBimbingan(id) {
                    Swal.fire({
                        title: 'Hapus Bimbingan?',
                        text: 'Apakah Anda yakin ingin menghapus jadwal bimbingan ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let url = "{{ route('mhs.skripsi.bimbingan.delete', ['id' => '__id__']) }}".replace('__id__', id);
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire('Berhasil!', 'Jadwal bimbingan berhasil dihapus.', 'success').then(() => {
                                        location.reload();
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                                }
                            });
                        }
                    });
                }
            </script>
        @endsection
