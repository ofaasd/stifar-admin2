@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
@endsection

@section('breadcrumb-title')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item active">{{ 'Pengajuan' }}</li>
@endsection

@section('content')
    <div id="pengajuan" class="content-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-file-earmark-plus me-2"></i>Pengajuan</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pengajuanModal">
                <i class="bi bi-plus-circle me-2"></i>Buat Pengajuan Baru
            </button>
        </div>

        <!-- Pengajuan Tabs -->
        <ul class="nav nav-tabs" id="pengajuanTabs" role="tablist">
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="dosbim-tab" data-bs-toggle="tab" data-bs-target="#dosbim"
                    type="button">
                    <i class="bi bi-person-plus me-2"></i>Dosen Pembimbing
                </button>
            </li> --}}
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="judul-tab" data-bs-toggle="tab" data-bs-target="#judul" type="button">
                    <i class="bi bi-file-text me-2"></i>Judul Skripsi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sidang-tab" data-bs-toggle="tab" data-bs-target="#sidang" type="button">
                    <i class="bi bi-calendar-check me-2"></i>Sidang
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pengajuanTabsContent">
            <!-- Pengajuan Dosbim -->
            <div class="tab-pane fade" id="dosbim" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Riwayat Pengajuan Dosen Pembimbing</h5>
                    </div>
                    <div class="card-body">
                        @if ($dataDosbim)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Dosen Pembimbing</th>
                                            <th>Status</th>
                                            <th>Catatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $dataDosbim->created_at->translatedFormat('d F Y') }}</td>
                                            <td>
                                                <strong>Pembimbing 1:</strong> {{ $dataDosbim->nama_pembimbing1 }}<br>
                                                <strong>Pembimbing 2:</strong> {{ $dataDosbim->nama_pembimbing2 }}
                                            </td>
                                            <td>
                                                @if ($dataDosbim->status == 0)
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif ($dataDosbim->status == 1 || $dataDosbim->status == 2)
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-secondary">Tidak Diketahui</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dataDosbim->status == 0)
                                                    Menunggu persetujuan dosen
                                                @elseif ($dataDosbim->status == 1 || $dataDosbim->status == 2)
                                                    Pengajuan disetujui dosen
                                                @else
                                                    Status tidak diketahui
                                                @endif
                                            </td>

                                            <td>
                                                <button class="btn btn-sm btn-outline-primary"
                                                    onclick="lihatDetail('dosbim1')">
                                                    <i class="bi bi-eye"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Belum ada pengajuan Pembimbing. Silahkan Ajukan Pembimbing terlebih dahulu.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pengajuan Judul -->
            <div class="tab-pane fade show active" id="judul" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Riwayat Pengajuan Judul</h5>
                    </div>
                    <div class="card-body">
                        @if (isset($dataJudul) && count($dataJudul) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Judul</th>
                                            <th>Status</th>
                                            <th>Feedback</th>
                                            {{-- <th>Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataJudul as $judul)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($judul->created_at)->format('d/m/Y') }}</td>
                                                <td>
                                                    {{ $judul->judul }}
                                                    @if ($judul->status == 2)
                                                        <button type="button" class="btn btn-sm btn-outline-warning ms-2" data-bs-toggle="modal" data-bs-target="#edit-judul-modal" onclick="editJudul({{ $judul->id }})">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                    @endif

                                                    @if ($judul->status == 1)
                                                        <a href="{{ route('mhs.skripsi.daftar.show', [$judul->idEnkripsi, 0]) }}" class="btn btn-sm btn-outline-primary ms-2">
                                                            <i class="bi bi-eye"></i> Detail
                                                        </a>
                                                        <a href="{{ route('mhs.skripsi.daftar.show', [$judul->idEnkripsi, 1]) }}" class="btn btn-sm btn-outline-warning ms-2">
                                                            <i class="bi bi-pencil-square"></i> Edit
                                                        </a>
                                                        @if (empty($judul->latar_belakang) || empty($judul->rumusan_masalah) || empty($judul->tujuan) || empty($judul->metodologi) || empty($judul->jenis_penelitian))
                                                            <span class="text-danger ms-2">
                                                                <i class="bi bi-exclamation-triangle"></i>
                                                                Segera lengkapi data skripsi (Latar Belakang, Rumusan Masalah, Tujuan, Metodologi, Jenis Penelitian)
                                                            </span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($judul->status == 0)
                                                        <span class="badge bg-primary">Menunggu</span>
                                                    @elseif ($judul->status == 1)
                                                        <span class="badge bg-success">Disetujui</span>
                                                    @elseif ($judul->status == 2)
                                                        <span class="badge bg-warning">Revisi</span>
                                                    @elseif ($judul->status == 3)
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Diketahui</span>
                                                    @endif
                                                </td>
                                                <td>{{ $judul->catatan ?? '-' }}</td>
                                                {{-- <td>
                                                    <button class="btn btn-sm btn-outline-primary"
                                                        onclick="lihatDetail('{{ $judul->id }}')">
                                                        <i class="bi bi-eye"></i> Detail
                                                    </button>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Belum ada pengajuan Judul.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pengajuan Sidang -->
            <div class="tab-pane fade" id="sidang" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Riwayat Pengajuan Sidang</h5>
                    </div>
                    <div class="card-body">
                        @if (isset($sidang) && count($sidang) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover" id="jadwal-sidang-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Ruangan</th>
                                            <th>Mahasiswa</th>
                                            <th>Judul</th>
                                            <th>Pembimbing</th>
                                            <th>Penguji</th>
                                            <th>Status</th>
                                            <th>Jenis</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sidang as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $row->tanggal }}</td>
                                                <td>{{ $row->waktuMulai }} - {{ $row->waktuSelesai }}</td>
                                                <td>{{ $row->ruangan }}</td>
                                                <td>{{ $row->nim ?? '-' }} - {{ $row->nama ?? '-' }}</td>
                                                <td>{{ $row->judul ?? '-' }}</td>
                                                <td>
                                                    <ul class="list-unstyled mb-0">
                                                        <li><strong>1:</strong> {{ $row->namaPembimbing1 ?? '-' }}</li>
                                                        <li><strong>2:</strong> {{ $row->namaPembimbing2 ?? '-' }}</li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="list-unstyled mb-0">
                                                        @for ($i = 1; $i <= $row->jmlPenguji; $i++)
                                                            <li><strong>{{ $i }}:</strong> {{ $row->{'namaPenguji' . $i} ?? '-' }}</li>
                                                        @endfor
                                                    </ul>
                                                </td>
                                                <td>
                                                    @if ($row->status == 0)
                                                        <span class="badge bg-primary">Pengajuan</span>
                                                    @elseif ($row->status == 1)
                                                        <span class="badge bg-success">Selesai</span>
                                                    @elseif ($row->status == 2)
                                                        <span class="badge bg-info">Diterima</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Diketahui</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($row->jenis == 1)
                                                        <span class="badge bg-success">Sidang Terbuka</span>
                                                    @elseif ($row->jenis == 2)
                                                        <span class="badge bg-warning">Sidang Tertutup</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Diketahui</span>
                                                    @endif
                                                </td>
                                                <td class="d-flex gap-2">
                                                    @if ($row->status == 1 || $row->status == 2)
                                                        <form action="{{ route('mhs.skripsi.daftar.print-sidang') }}" method="POST" class="d-inline" target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $row->id }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-success ms-2" title="Download Surat Pengantar Sidang">
                                                                <i class="bi bi-download"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif

                                                    @if ($row->jenis == 1 && ($row->accPembimbing1 == 1 || $row->accPembimbing2 == 1))
                                                        <form action="{{ route('mhs.skripsi.daftar.print-persetujuan-proposal') }}" method="POST" class="d-inline" target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $row->id }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-success ms-2" title="Download Surat Persetujuan Seminar Proposal">
                                                                <i class="bi bi-download"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Belum ada pengajuan sidang. Selesaikan proposal terlebih dahulu.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="pengajuanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Pengajuan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- <div class="col-md-4 mb-3">
                            <a href="{{ route('mhs.pengajuan.pembimbing.index') }}" class="card h-100 pengajuan-option">
                                <div class="card-body text-center">
                                    <i class="bi bi-person-plus fs-1 text-primary mb-3"></i>
                                    <h6>Dosen Pembimbing</h6>
                                    <small class="text-muted">Ajukan dosen pembimbing 1 & 2</small>
                                </div>
                            </a>
                        </div> --}}
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 pengajuan-option">
                                <a href="{{ route('mhs.pengajuan.judul.index') }}" class="card h-100 pengajuan-option">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-text fs-1 text-success mb-3"></i>
                                        <h6>Judul Skripsi</h6>
                                        <small class="text-muted">Ajukan judul penelitian</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 pengajuan-option">
                                <a href="{{ route('mhs.pengajuan.sidang.index') }}" class="card h-100 pengajuan-option">
                                    <div class="card-body text-center">
                                        <i class="bi bi-calendar-check fs-1 text-warning mb-3"></i>
                                        <h6>Sidang</h6>
                                        <small class="text-muted">Ajukan sidang proposal/skripsi</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Judul -->
    <div class="modal fade" id="edit-judul-modal" tabindex="-1" aria-labelledby="editJudulModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="form-edit-judul" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-judul-id">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title fw-bold fs-3" id="label-nama-edit-judul"></h5>
                            <small class="text-muted" id="label-nim-edit-judul"></small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-judul" class="form-label">Judul Skripsi</label>
                            <textarea class="form-control" id="edit-judul" name="judul" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-judul-english" class="form-label">Judul Skripsi (English)</label>
                            <textarea class="form-control" id="edit-judul-english" name="judulEnglish" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-abstrak" class="form-label">Abstrak</label>
                            <textarea class="form-control" id="edit-abstrak" name="abstrak" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="edit-catatan" name="catatan" rows="3" disabled></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-submit">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#form-edit-judul').on('submit', function(e) {
                e.preventDefault();
                var $btn = $('.btn-submit');
                var originalText = $btn.html();
                $btn.prop('disabled', true);
                $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                var id = $('#edit-judul-id').val();
                var formData = {
                    _token: $('input[name="_token"]').val(),
                    _method: 'PUT',
                    judul: $('#edit-judul').val(),
                    judulEnglish: $('#edit-judul-english').val(),
                    abstrak: $('#edit-abstrak').val()
                };
                $.ajax({
                    url: "{{ route('mhs.skripsi.daftar.update', ['id' => '__id__']) }}".replace('__id__', id),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                    $('#edit-judul-modal').modal('hide');
                        $btn.prop('disabled', false);
                        $btn.html(originalText);
                        // Optional: reload table or show success message
                        location.reload();
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false);
                        $btn.html(originalText);
                        console.error(xhr);
                    }
                });
            });
        });

        function editJudul(id) {
            $.ajax({
                url: "{{ route('mhs.skripsi.daftar.getDataPengajuanJudul', ['id' => '__id__']) }}".replace('__id__', id),
                method: 'GET',
                success: function(response) {
                    $('#label-nama-edit-judul').text(response.nama);
                    $('#label-nim-edit-judul').text(response.nim);

                    $('#edit-judul-id').val(response.id);
                    $('#edit-judul').val(response.judul);
                    $('#edit-judul-english').val(response.judul_eng);
                    $('#edit-abstrak').val(response.abstrak);
                    $('#edit-catatan').val(response.catatan);
                },
                error: function(e) {
                    console.error(e);
                }
            });
        }
    </script>
@endsection
