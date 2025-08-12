@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @endsection

@section('breadcrumb-title')
    <h3>{{ 'Pengajuan Sidang Skripsi' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Pengajuan</li>
    <li class="breadcrumb-item active">{{ 'Sidang' }}</li>
@endsection

@section('content')
<div class="page-content" id="jadwal-page">
    {{-- Info Jadwal Sidang --}}
    @if ($sidang)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Jadwal Sidang Skripsi Anda</h5>
                    @php
                        $statusLabel = [
                            0 => ['label' => 'Menunggu Konfirmasi', 'class' => 'info'],
                            1 => ['label' => 'Selesai', 'class' => 'success'],
                            2 => ['label' => 'Jadwal Ditetapkan', 'class' => 'primary'],
                        ];
                        $status = $sidang->status;
                    @endphp
                    <span class="badge bg-{{ $statusLabel[$status]['class'] }}">
                        {{ $statusLabel[$status]['label'] }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="alert alert-{{ $statusLabel[$status]['class'] }}" role="alert">
                        <h5 class="alert-heading"><i class="bi bi-calendar-check"></i> Jadwal Sidang Anda</h5>
                        <p>{{ $statusLabel[$status]['label'] }}. Silakan persiapkan diri dengan baik.</p>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($sidang->tanggal)->translatedFormat('d F Y') }}</p>
                                <p class="mb-1"><strong>Waktu:</strong> {{ $sidang->waktu_mulai }} - {{ $sidang->waktu_selesai }} WIB</p>
                                <p class="mb-1"><strong>Ruangan:</strong> {{ $sidang->ruangan }}</p>
                                <p class="mb-1"><strong>Gelombang:</strong> {{ $sidang->gelombang->nama }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Pembimbing:</strong> {{ $pembimbing->nama ?? '-' }}</p>
                                <p class="mb-1"><strong>Penguji 1:</strong> {{ $penguji1->nama ?? '-' }}</p>
                                <p class="mb-1"><strong>Penguji 2:</strong> {{ $penguji2->nama ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tambahan card berdasarkan status --}}
    @if ($status == 0)
        {{-- SIDANG MENUNGGU KONFIRMASI --}}
        <div class="card mb-4 border-warning">
            <div class="card-body">
                <h5 class="card-title text-warning"><i class="bi bi-hourglass-split"></i> Menunggu Konfirmasi</h5>
                <p>Data sidang Anda sedang diproses oleh Universitas. Mohon tunggu konfirmasi selanjutnya.</p>
            </div>
        </div>
    @elseif ($status == 2)
        {{-- SIDANG JADWAL DITETAPKAN --}}
        <div class="card mb-4 border-primary">
            <div class="card-body">
                <h5 class="card-title text-primary"><i class="bi bi-calendar-event"></i> Jadwal Telah Ditetapkan</h5>
                <p>Silakan pastikan Anda hadir tepat waktu sesuai jadwal. Jangan lupa mempersiapkan berkas dan presentasi.</p>
            </div>
        </div>
    @elseif ($status == 1)
        {{-- SIDANG SELESAI --}}
        <div class="card mb-4 border-success">
            <div class="card-body">
                <h5 class="card-title text-success"><i class="bi bi-check-circle"></i> Sidang Telah Selesai</h5>
                <p>Selamat! Anda telah menyelesaikan sidang skripsi. Silakan lanjutkan ke proses revisi jika ada.</p>
            </div>
        </div>
    @endif
@endif


    {{-- Tata Tertib --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Persiapan Sidang</h5>
                </div>
                <div class="card-body">
                    <h6>Tata Tertib Sidang:</h6>
                    <ol>
                        <li>Hadir 30 menit sebelum jadwal sidang.</li>
                        <li>Berpakaian rapi dan formal (kemeja putih, celana/rok hitam, dasi, jas almamater).</li>
                        <li>Presentasi maksimal 15 menit.</li>
                        <li>Sesi tanya jawab sekitar 45–60 menit.</li>
                        <li>Dilarang menggunakan ponsel selama sidang berlangsung.</li>
                        <li>Bersikap sopan dan menjawab pertanyaan dengan jelas.</li>
                        <li>Mencatat semua masukan dan saran dari dosen penguji untuk revisi.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Gelombang --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Gelombang Sidang</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Gelombang</th>
                                    <th>Periode</th>
                                    <th>Pendaftaran</th>
                                    <th>Pelaksanaan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gelombang as $g)
                                    @php
                                        $status = \Carbon\Carbon::now()->between($g->tanggal_mulai_daftar, $g->tanggal_selesai_daftar)
                                            ? 'Berlangsung'
                                            : (\Carbon\Carbon::now()->lt($g->tanggal_mulai_daftar) ? 'Belum Dibuka' : 'Selesai');
                                    @endphp
                                    <tr class="{{ $status == 'Berlangsung' ? 'table-primary' : '' }}">
                                        <td>{{ $g->nama }}</td>
                                        <td>{{ $g->periode }}</td>
                                        <td>{{ \Carbon\Carbon::parse($g->tanggal_mulai_daftar)->format('d M Y') }} - {{ \Carbon\Carbon::parse($g->tanggal_selesai_daftar)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($g->tanggal_mulai_pelaksanaan)->format('d M Y') }} - {{ \Carbon\Carbon::parse($g->tanggal_selesai_pelaksanaan)->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $status == 'Berlangsung' ? 'warning' : ($status == 'Selesai' ? 'success' : 'secondary') }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($status === 'Berlangsung' && !$sidang)
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalDaftar{{ $g->id }}">
                                                    Daftar
                                                </button>

                                                {{-- Modal Pendaftaran --}}
                                                <div class="modal fade" id="modalDaftar{{ $g->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $g->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form action="{{ route('mhs.skripsi.sidang.store') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="gelombang_id" value="{{ $g->id }}">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalLabel{{ $g->id }}">Konfirmasi Pendaftaran</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Anda akan mendaftar sidang skripsi pada <strong>{{ $g->nama }}</strong>. Pastikan semua syarat telah terpenuhi.</p>
                                                                    <p class="text-danger"><small>Setelah mendaftar, Anda tidak dapat membatalkan.</small></p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success">Ya, Daftar</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @elseif ($sidang && $sidang->gelombang_id == $g->id)
                                                <span class="badge bg-info">Terdaftar</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
