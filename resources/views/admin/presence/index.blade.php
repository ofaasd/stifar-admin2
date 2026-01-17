@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dosen</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')

<div class="container py-5">
    {{-- <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-primary"><i class="bi bi-calendar-check"></i> Data Absensi Pegawai</h2>
            <p class="text-muted">Rekapitulasi data dari Mesin Fingerprint & Aplikasi Mobile</p>
        </div>
    </div> --}}
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel"></i> Import Absensi Fingerprint
        </button>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('presences.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Data Fingerprint</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle"></i> Pastikan format file sesuai (CSV/Excel) dengan kolom:<br>
                                <strong>No. Staff, Tanggal, Jam Masuk, Jam Keluar.</strong>
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fileImport" class="form-label">Pilih File Excel/CSV</label>
                            <input class="form-control" type="file" id="fileImport" name="file" required accept=".csv, .xls, .xlsx">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload & Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }} 
            @if(session('link'))
                <a href="{{ session('link') ?? '#' }}" class="btn btn-primary mt-2 mb-2">Perbarui Data Pegawai</a>.
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form action="{{ url('admin/presences') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label fw-semibold">Pegawai</label>
                    <select name="pegawai_id" class="js-example-basic-single form-select">
                        <option value="">-- Semua Pegawai --</option>
                        @foreach($pegawai as $item)
                            <option value="{{ $item->id }}" {{ request('pegawai_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_lengkap }} - {{$item->nama}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Tampilkan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover datatables mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col" class="ps-4">Tanggal</th>
                            <th scope="col">Nama Pegawai</th>
                            <th scope="col">Sumber</th>
                            <th scope="col">Jam Masuk</th>
                            <th scope="col">Jam Pulang</th>
                            <th scope="col">Keterlambatan</th>
                            <th scope="col">Lokasi/IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presences as $item)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold">{{ \Carbon\Carbon::parse($item->day)->translatedFormat('d M Y') }}</span>
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->day)->translatedFormat('l') }}</small>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                        {{ substr($item->pegawai->nama_lengkap ?? 'X', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $item->pegawai->nama_lengkap ?? 'Pegawai Tidak Dikenal' }}</div>
                                        <small class="text-muted">ID: {{ $item->user_id }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($item->attendance_source == 'mobile')
                                    <span class="badge bg-info text-dark"><i class="bi bi-phone"></i> Mobile</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-fingerprint"></i> Mesin</span>
                                @endif
                            </td>

                            <td>
                                @if($item->start)
                                    <span class="text-success fw-bold">{{ date('H:i', $item->start) }}</span>
                                @else
                                    <span class="text-danger">-</span>
                                @endif
                            </td>

                            <td>
                                @if($item->end)
                                    <span class="text-primary fw-bold">{{ date('H:i', $item->end) }}</span>
                                @else
                                    <span class="text-muted fst-italic">Belum Pulang</span>
                                @endif
                            </td>

                            <td>
                                @if($item->start_late > 0)
                                    <span class="badge bg-danger">
                                        {{ floor($item->start_late / 60) }} Menit
                                    </span>
                                @else
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @endif
                            </td>

                            <td>
                                @if($item->attendance_source == 'mobile')
                                    <small class="d-block text-truncate" style="max-width: 150px;" title="{{ $item->lat_start }}, {{ $item->long_start }}">
                                        <i class="bi bi-geo-alt-fill text-danger"></i> Maps
                                    </small>
                                @else
                                    <small class="text-muted"><i class="bi bi-building"></i> Kantor</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="mt-2 text-muted">Tidak ada data absensi pada rentang tanggal ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $presences->firstItem() ?? 0 }} s/d {{ $presences->lastItem() ?? 0 }} dari {{ $presences->total() }} data
                </small>
                {{ $presences->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function(event) {
      const baseUrl = '{!! url("") !!}';
      $(".datatables").DataTable();
    })
  </script>
@endsection
