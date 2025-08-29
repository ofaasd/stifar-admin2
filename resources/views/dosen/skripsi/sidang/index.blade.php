@extends('layouts.master')
@section('title', 'Data Gelombang Sidang')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons/bootstrap-icons.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Daftar Gelombang Sidang</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item active">Gelombang Sidang</li>
@endsection
@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Pengaturan Gelombang Sidang</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahGelombang">
                <i class="bi bi-plus-circle"></i> Tambah Gelombang
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="datatable-gelombang">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Periode</th>
                        <th>Pendaftaran</th>
                        <th>Pelaksanaan</th>
                        <th>Kuota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gelombang as $g)
                    <tr>
                        <td>{{ $g->nama }}</td>
                        <td>{{ $g->periode }}</td>
                        <td>{{ \Carbon\Carbon::parse($g->tanggal_mulai_daftar)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($g->tanggal_selesai_daftar)->translatedFormat('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($g->tanggal_mulai_pelaksanaan)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($g->tanggal_selesai_pelaksanaan)->translatedFormat('d M Y') }}</td>
                        <td>0/50</td> {{-- Contoh kuota, nanti bisa dikalkulasi --}}
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit" 
                                data-id="{{ $g->id }}"
                                data-nama="{{ $g->nama }}"
                                data-id_tahun="{{ $g->id_tahun_ajaran }}"
                                data-kuota="{{ $g->kuota }}"
                                data-daftar_mulai="{{ $g->tanggal_mulai_daftar }}"
                                data-daftar_selesai="{{ $g->tanggal_selesai_daftar }}"
                                data-pelaksanaan_mulai="{{ $g->tanggal_mulai_pelaksanaan }}"
                                data-pelaksanaan_selesai="{{ $g->tanggal_selesai_pelaksanaan }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('sidang.delete', $g->id) }}" method="POST" class="d-inline delete-form">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Jadwal Sidang Skripsi</h5>
                <div>
                    <select class="form-select form-select-sm d-inline-block w-auto me-2">
                        <option>Semua Gelombang</option>
                        <option selected>Gelombang 1</option>
                        <option>Gelombang 2</option>
                        <option>Gelombang 3</option>
                    </select>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">
                        <i class="bi bi-plus-circle"></i> Tambah Jadwal
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Ruangan</th>
                                <th>Mahasiswa</th>
                                <th>Judul Skripsi</th>
                                <th>Pembimbing</th>
                                <th>Penguji</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2 Mei 2025</td>
                                <td>08:00 - 10:00</td>
                                <td>R.301</td>
                                <td>Ahmad Fauzi (1901234)</td>
                                <td>Implementasi Machine Learning untuk Prediksi Cuaca</td>
                                <td>Dr. Budi Santoso, M.Kom</td>
                                <td>Dr. Siti Aminah, M.T</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>2 Mei 2025</td>
                                <td>10:30 - 12:30</td>
                                <td>R.301</td>
                                <td>Siti Nurhaliza (1905678)</td>
                                <td>Analisis Sentimen Media Sosial Terhadap Kebijakan Pendidikan</td>
                                <td>Dr. Ahmad Fauzi, M.Sc</td>
                                <td>Dewi Lestari, M.Kom</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>3 Mei 2025</td>
                                <td>08:00 - 10:00</td>
                                <td>R.302</td>
                                <td>Budi Santoso (1907890)</td>
                                <td>Pengembangan Aplikasi Mobile untuk Monitoring Kesehatan</td>
                                <td>Dewi Lestari, M.Kom</td>
                                <td>Dr. Budi Santoso, M.Kom</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>3 Mei 2025</td>
                                <td>10:30 - 12:30</td>
                                <td>R.302</td>
                                <td>Dewi Lestari (1902345)</td>
                                <td>Pengaruh Media Sosial Terhadap Perilaku Remaja</td>
                                <td>Dr. Siti Aminah, M.T</td>
                                <td>Dr. Ahmad Fauzi, M.Sc</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahGelombang" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Gelombang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('dosen.skripsi.sidang._form', ['action' => 'tambah'])
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="tambahJadwalModal" tabindex="-1" aria-labelledby="tambahJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahJadwalModalLabel">Tambah Jadwal Sidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sidang.store') }}" method="post">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gelombangSidang" class="form-label">Gelombang</label>
                            <select class="form-select" id="gelombangSidang" name="gelombang_id">
                                <option value="1" selected>Gelombang 1</option>
                                <option value="2">Gelombang 2</option>
                                <option value="3">Gelombang 3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tanggalSidang" class="form-label">Tanggal Sidang</label>
                            <input type="date" class="form-control" id="tanggalSidang" name="tanggal" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="waktuMulai" class="form-label">Waktu Mulai</label>
                            <input type="time" class="form-control" id="waktuMulai" name="waktu_mulai" required>
                        </div>
                        <div class="col-md-6">
                            <label for="waktuSelesai" class="form-label">Waktu Selesai</label>
                            <input type="time" class="form-control" id="waktuSelesai" name="waktu_selesai" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ruanganSidang" class="form-label">Ruangan</label>
                        <select class="form-select" id="ruanganSidang" name="ruangan">
                            <option value="R.301" selected>R.301</option>
                            <option value="R.302">R.302</option>
                            <option value="R.303">R.303</option>
                            <option value="R.304">R.304</option>
                            <option value="R.305">R.305</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mahasiswaSidang" class="form-label">Mahasiswa</label>
                        <select class="form-select" id="mahasiswaSidang" name="skripsi_id">
                            <option value="" selected>Pilih Mahasiswa</option>
                            <option value="1">Rina Wati (1903456) - Analisis Performa Algoritma Sorting</option>
                            <option value="2">Joko Susilo (1904567) - Implementasi IoT untuk Smart Home</option>
                            <option value="3">Anita Sari (1905678) - Pengembangan Game Edukasi untuk Anak</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="pembimbingSidang" class="form-label">Pembimbing</label>
                            <select class="form-select" id="pembimbingSidang" name="pembimbing">
                                <option value="1" selected>Dr. Budi Santoso, M.Kom</option>
                                <option value="2">Dr. Siti Aminah, M.T</option>
                                <option value="3">Dr. Ahmad Fauzi, M.Sc</option>
                                <option value="4">Dewi Lestari, M.Kom</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="pengujiSidang" class="form-label">Penguji</label>
                            <select class="form-select" id="pengujiSidang" name="penguji[]" multiple>
                                <option value="1">Dr. Budi Santoso, M.Kom</option>
                                <option value="2">Dr. Siti Aminah, M.T</option>
                                <option value="3" selected>Dr. Ahmad Fauzi, M.Sc</option>
                                <option value="4" selected>Dewi Lestari, M.Kom</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="status" value="2">
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="modalEditGelombang" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEdit" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Gelombang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('dosen.skripsi.sidang._form', ['action' => 'edit'])
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function () {
        // SweetAlert konfirmasi hapus
        $('.delete-form').on('submit', function (e) {
            e.preventDefault();
            let form = this;
            swal({
                title: "Yakin ingin menghapus?",
                text: "Data tidak bisa dikembalikan!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });

        // Isi modal edit
        $('.btn-edit').click(function () {
            let id = $(this).data('id');
            $('#formEdit').attr('action', `/sidang/update/${id}`);
            $('#formEdit input[name=nama]').val($(this).data('nama'));
            $('#formEdit select[name=id_tahun_ajaran]').val($(this).data('id_tahun'));
            $('#formEdit input[name=kuota]').val($(this).data('kuota'));
            $('#formEdit input[name=tanggal_mulai_daftar]').val($(this).data('daftar_mulai'));
            $('#formEdit input[name=tanggal_selesai_daftar]').val($(this).data('daftar_selesai'));
            $('#formEdit input[name=tanggal_mulai_pelaksanaan]').val($(this).data('pelaksanaan_mulai'));
            $('#formEdit input[name=tanggal_selesai_pelaksanaan]').val($(this).data('pelaksanaan_selesai'));
            $('#modalEditGelombang').modal('show');
        });
    });
</script>
@endsection
