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
            @if($mhs->is_skripsi == 0)
                <button class="btn btn-primary" disabled>
                    <i class="bi bi-lock me-2"></i>Belum diizinkan pengajuan skripsi
                </button>
            @else
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pengajuanModal">
                    <i class="bi bi-plus-circle me-2"></i>Buat Pengajuan Baru
                </button>
            @endif
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
                        @if ($dataDosbim && count($dataDosbim) > 0)
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
                                        @foreach ($dataDosbim as $dosbim)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($dosbim->created_at)->format('d/m/Y') }}</td>
                                                <td>
                                                    <strong>Pembimbing 1:</strong> {{ $dosbim->nama_pembimbing1 }}<br>
                                                    <strong>Pembimbing 2:</strong> {{ $dosbim->nama_pembimbing2 }}
                                                </td>
                                                <td>
                                                    @if ($dosbim->status == 0)
                                                        <span class="badge bg-warning">Menunggu</span>
                                                    @elseif ($dosbim->status == 1 || $dosbim->status == 2)
                                                        <span class="badge bg-success">Disetujui</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Diketahui</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($dosbim->status == 0)
                                                        Menunggu persetujuan dosen
                                                    @elseif ($dosbim->status == 1 || $dosbim->status == 2)
                                                        Pengajuan disetujui dosen
                                                    @else
                                                        Status tidak diketahui
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary"
                                                        onclick="lihatDetail('{{ $dosbim->id }}')">
                                                        <i class="bi bi-eye"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
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

            <!-- Pengajuan Sidang (Edit dari sidang itu) -->
            <div class="tab-pane fade" id="sidang" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Riwayat Pengajuan Sidang</h5>
                        {{-- Tidak ada tombol global "Ajukan", edit dilakukan dari baris sidang --}}
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
                                                        <span class="badge bg-success">Seminar Proposal</span>
                                                    @elseif ($row->jenis == 2)
                                                        <span class="badge bg-warning">Seminar Hasil</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Diketahui</span>
                                                    @endif
                                                </td>
                                                <td class="d-flex gap-2 align-items-center">
                                                    @if ($row->status == 1 || $row->status == 2)
                                                        <form action="{{ route('mhs.skripsi.daftar.print-sidang') }}" method="POST" class="d-inline" target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $row->id }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Download Surat Pengantar Sidang">
                                                                <i class="bi bi-download"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if ($row->status == 1 || $row->status == 2)
                                                        <form action="{{ route('mhs.skripsi.daftar.print-persetujuan-proposal') }}" method="POST" class="d-inline" target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $row->id }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Download Surat Persetujuan Seminar">
                                                                <i class="bi bi-download"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Tombol Edit: buka modal pengeditan untuk baris ini --}}
                                                    @if (is_null($row->tanggal)) {{-- tidak bisa edit jika sudah selesai --}}
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#edit-sidang-modal"
                                                            data-id="{{ $row->idEnkripsi }}"
                                                            data-tanggal="{{ $row->tanggal }}"
                                                            data-waktu-mulai="{{ $row->waktuMulai }}"
                                                            data-waktu-selesai="{{ $row->waktuSelesai }}"
                                                            data-ruang-id="{{ $row->ruang_id ?? '' }}"
                                                            data-jenis="{{ $row->jenis }}"
                                                            title="Submit Waktu Sidang"
                                                            onclick="openEditSidangModal(this)">
                                                            <i class="bi bi-clock"></i>
                                                        </button>
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

            <!-- Modal: Submit Waktu Sidang -->
            <div class="modal fade" id="edit-sidang-modal" tabindex="-1" aria-labelledby="editSidangLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="form-submit-sidang" action="#" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit-sidang-id">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSidangLabel">Submit Waktu Sidang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit-sidang-tanggal" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="edit-sidang-tanggal" name="tanggal" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-sidang-waktu-mulai" class="form-label">Waktu Mulai</label>
                                        <input type="time" class="form-control" id="edit-sidang-waktu-mulai" name="waktuMulai" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-sidang-waktu-selesai" class="form-label">Waktu Selesai</label>
                                        <input type="time" class="form-control" id="edit-sidang-waktu-selesai" name="waktuSelesai" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-sidang-ruang" class="form-label">Ruangan</label>
                                    <select name="idRuang" id="edit-sidang-ruang" class="form-select" required>
                                        <option value="">Pilih Ruang</option>
                                        @if(isset($ruang) && count($ruang) > 0)
                                            @foreach($ruang as $r)
                                                <option value="{{ $r->id }}">{{ $r->nama_ruang ?? $r->nama }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" id="btn-submit">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function openEditSidangModal(btn) {
                    var $btn = $(btn);
                    var id = $btn.data('id');
                    var tanggal = $btn.data('tanggal') || '';
                    var waktuMulai = $btn.data('waktu-mulai') || '';
                    var waktuSelesai = $btn.data('waktu-selesai') || '';
                    var ruangId = $btn.data('ruang-id') || '';
                    var jenis = $btn.data('jenis') || '';

                    $('#edit-sidang-id').val(id);
                    $('#edit-sidang-tanggal').val(tanggal);
                    $('#edit-sidang-waktu-mulai').val(waktuMulai);
                    $('#edit-sidang-waktu-selesai').val(waktuSelesai);
                    $('#edit-sidang-ruang').val(ruangId);
                    $('#edit-sidang-jenis').val(jenis);

                    // atur action form ke route update (ganti __id__ dengan id)
                    var urlTemplate = "{{ route('mhs.skripsi.daftar.input-waktu-sidang', ['id' => '__id__']) }}";
                    $('#form-submit-sidang').attr('action', urlTemplate.replace('__id__', id));
                }
            </script>
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
                                @if($isAllowSidang)
                                    <a href="{{ route('mhs.pengajuan.sidang.index') }}" class="card h-100 pengajuan-option">
                                        <div class="card-body text-center">
                                            <i class="bi bi-calendar-check fs-1 text-warning mb-3"></i>
                                            <h6>Sidang</h6>
                                            <small class="text-muted">Ajukan sidang proposal/skripsi</small>
                                        </div>
                                    </a>
                                @else
                                    <div class="card h-100 pengajuan-option opacity-75">
                                        <div class="card-body text-center">
                                            <i class="bi bi-lock fs-1 text-secondary mb-3"></i>
                                            <h6>Sidang</h6>
                                            <small class="text-muted">Belum diizinkan untuk sidang</small>
                                        </div>
                                    </div>
                                @endif
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
