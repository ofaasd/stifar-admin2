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
<div id="pengajuan" class="content-section" >
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-file-earmark-plus me-2"></i>Pengajuan</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pengajuanModal">
            <i class="bi bi-plus-circle me-2"></i>Buat Pengajuan Baru
        </button>
    </div>
    
    <!-- Pengajuan Tabs -->
<ul class="nav nav-tabs" id="pengajuanTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="dosbim-tab" data-bs-toggle="tab" data-bs-target="#dosbim" type="button">
                <i class="bi bi-person-plus me-2"></i>Dosen Pembimbing
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="judul-tab" data-bs-toggle="tab" data-bs-target="#judul" type="button">
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
        <div class="tab-pane fade show active" id="dosbim" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Pengajuan Dosen Pembimbing</h5>
                </div>
                <div class="card-body">
                    @if($dataDosbim)
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
                                            Menunggu persetujuan koordinator
                                        @elseif ($dataDosbim->status == 1 || $dataDosbim->status == 2)
                                            Pengajuan disetujui koordinator
                                        @else
                                            Status tidak diketahui
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="lihatDetail('dosbim1')">
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
        <div class="tab-pane fade" id="judul" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Pengajuan Judul</h5>
                </div>
                <div class="card-body">
                    @if($dataJudul)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Feedback</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $dataJudul->created_at->translatedFormat('d F Y') }}</td>
                                    <td>{{ $dataJudul->judul }}</td>
                                    <td>
                                        @if ($dataJudul->status == 0)
                                        <span class="badge bg-primary">Menunggu</span>
                                    @elseif ($dataJudul->status == 1 )
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif ($dataJudul->status == 2 )
                                        <span class="badge bg-warning">Revisi</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Diketahui</span>
                                    @endif
                                    </td>
                                    <td>{{ $dataJudul->catatan ?? '-'  }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="lihatDetail('judul1')">
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
                        Belum ada pengajuan Judul. Selesaikan Pengajuan Pembimbing terlebih dahulu.
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
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Belum ada pengajuan sidang. Selesaikan proposal terlebih dahulu.
                    </div>
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
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('mhs.pengajuan.pembimbing.index') }}" class="card h-100 pengajuan-option">
                            <div class="card-body text-center">
                                <i class="bi bi-person-plus fs-1 text-primary mb-3"></i>
                                <h6>Dosen Pembimbing</h6>
                                <small class="text-muted">Ajukan dosen pembimbing 1 & 2</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 pengajuan-option" onclick="selectPengajuan('judul')">
                        <a href="{{ route('mhs.pengajuan.judul.index') }}" class="card h-100 pengajuan-option">
                            <div class="card-body text-center">
                                <i class="bi bi-file-text fs-1 text-success mb-3"></i>
                                <h6>Judul Skripsi</h6>
                                <small class="text-muted">Ajukan judul penelitian</small>
                            </div>
                        </a>
                    </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 pengajuan-option" onclick="selectPengajuan('sidang')">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-check fs-1 text-warning mb-3"></i>
                                <h6>Sidang</h6>
                                <small class="text-muted">Ajukan sidang proposal/skripsi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function() {
        
        });
    </script>
@endsection
