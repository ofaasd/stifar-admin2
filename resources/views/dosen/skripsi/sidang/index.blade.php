@extends('layouts.master')
@section('title', 'Data Gelombang Sidang')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons/bootstrap-icons.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
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
                        <td>{{ $g->jumlahPeserta }}/{{ $g->kuota }}</td> {{-- Contoh kuota, nanti bisa dikalkulasi --}}
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
                <h5 class="card-title mb-0">Jadwal Sidang</h5>
                <div>
                    <select class="form-select form-select-sm d-inline-block w-auto me-2" id="filterGelombang">
                        <option value="#" selected disabled>Semua Gelombang</option>
                        @foreach ($gelombang as $row)
                            <option value="{{ $row->id }}">{{ $row->nama }} / {{ $row->periode }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">
                        <i class="bi bi-plus-circle"></i> Tambah Jadwal
                    </button>
                </div>
            </div>
            <div class="card-body">
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahJadwalModalLabel">Tambah Jadwal Sidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sidang.store') }}" method="post" id="form-tambah-jadwal">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gelombangSidang" class="form-label">Gelombang</label>
                            <select class="form-select" id="gelombangSidang" name="gelombangId">
                                <option value="#" selected disabled>Pilih Gelombang</option>
                                @foreach ($gelombang as $row)
                                    <option value="{{ $row->id }}">{{ $row->nama }}/{{ $row->periode }}</option>
                                @endforeach
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
                            <input type="time" class="form-control" id="waktuMulai" name="waktuMulai" required>
                        </div>
                        <div class="col-md-6">
                            <label for="waktuSelesai" class="form-label">Waktu Selesai</label>
                            <input type="time" class="form-control" id="waktuSelesai" name="waktuSelesai" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ruanganSidang" class="form-label">Ruangan</label>
                        <select class="form-select" id="ruanganSidang" name="ruangId">
                            <option value="" selected>Pilih Ruangan</option>
                            @foreach ($ruang as $row)
                                <option value="{{ $row->id }}">{{ $row->nama_ruang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mahasiswaSidang" class="form-label">Mahasiswa</label>
                        <select class="form-select" id="mahasiswaSidang" name="masterSkripsiId">
                            <option value="" selected>Pilih Mahasiswa</option>
                            @forelse ($mahasiswaSkripsi as $mhs)
                                <option value="{{ $mhs->idMasterSkripsi }}" title="{{ $mhs->judul }}">{{ $mhs->nama }} ({{ $mhs->nim }}) / {{ $mhs->judul }}</option>
                            @empty
                                <option value="" disabled>Tidak ada mahasiswa tersedia</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12 col-md-6">
                            <label for="pengujiSidang" class="form-label">Penguji</label>
                            <select class="form-select" id="pengujiSidang" name="penguji[]" multiple>
                                @foreach ($pegawai as $row)
                                    <option value="{{ $row->npp }}">{{ $row->npp }} / {{ $row->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="jenisSidang" class="form-label">Jenis Sidang</label>
                        <select class="form-select" id="jenisSidang" name="jenisSidang" required>
                            <option value="" selected disabled>Pilih Jenis Sidang</option>
                            <option value="1">Sidang Terbuka</option>
                            <option value="2">Sidang Tertutup</option>
                        </select>
                    </div>
                    <input type="hidden" name="status" value="2">
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-tambah-jadwal">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJadwalModalLabel">Edit Jadwal Sidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
                <form action="" method="post" id="form-edit-jadwal">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <label for="gelombangSidang" class="form-label">Gelombang</label>
                        <select class="form-select" id="gelombangSidang" name="gelombangId">
                            @foreach ($gelombang as $row)
                                <option value="{{ $row->id }}">{{ $row->nama }} / {{ $row->periode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="tanggalSidang" class="form-label">Tanggal Sidang</label>
                            <input type="date" class="form-control" id="tanggalSidang" name="tanggal" required>
                        </div>
                        <div class="col-md-6 mt-2" id="view-update-status">
                            <button type="button" class="btn btn-success btn-sm mt-4" id="btn-update-status" style="display: none">Selesai Sidang</button>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preferensi Waktu Sidang Mahasiswa</label>
                            <div>
                                <p>Catatan: <span class="badge bg-info me-1" id="preferensiCatatan"></span></p>
                                <p>Hari: <span class="badge bg-info me-1" id="preferensiHari"></span></p>
                                <p>Waktu: <span class="badge bg-info" id="preferensiWaktu"></span></p>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <strong>Catatan:</strong> Preferensi ini merupakan permintaan dari mahasiswa, jadwal sidang yang sebenarnya dapat berbeda dan tidak harus mengikuti preferensi ini.
                            </small>
                        </div>
                    </div>
                    <div class="row mt-2 align-items-center">
                        <div class="col-md-6">
                            <label for="waktuMulai" class="form-label">Waktu Mulai</label>
                            <input type="time" class="form-control" id="waktuMulai" name="waktuMulai" required>
                        </div>
                        <div class="col-md-6">
                            <label for="waktuSelesai" class="form-label">Waktu Selesai</label>
                            <input type="time" class="form-control" id="waktuSelesai" name="waktuSelesai" required>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label for="ruanganSidang" class="form-label">Ruangan</label>
                        <select class="form-select" id="ruanganSidang" name="ruangId">
                            @foreach ($ruang as $row)
                                <option value="{{ $row->id }}">{{ $row->nama_ruang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="mahasiswaSidang" class="form-label">Mahasiswa</label>
                        <div id="mahasiswaSidang" class="bg-warning text-white bg-opacity-25 p-2 rounded"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Pembimbing</label>
                            <div id="pembimbingSidang"></div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="pengujiSidang" class="form-label">Penguji</label>
                            <select class="form-select" id="pengujiSidang" name="penguji[]" multiple size="8">
                                @foreach ($pegawai as $row)
                                    <option value="{{ $row->npp }}">{{ $row->npp }} / {{ $row->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            <div id="listPengujiTerpilih" class="mt-2"></div>
                            <small class="text-muted">Tekan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu penguji.</small>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex flex-wrap gap-2">
                            <div><strong>Proposal:</strong> <span id="sidangProposal"></span></div>
                            <div><strong>Kartu Bimbingan:</strong> <span id="sidangKartuBimbingan"></span></div>
                            <div><strong>Presentasi:</strong> <span id="sidangPresentasi"></span></div>
                            <div><strong>Pendukung:</strong> <span id="sidangPendukung"></span></div>
                        </div>
                    </div>
                    <div class="modal-footer mt-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-submit" id="btn-submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
</script>

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
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function () {
        initJadwalSidangTable();

        $('#filterGelombang').on('change', function() {
            let idGelombang = $(this).val();
            $('#jadwal-sidang-table').DataTable().destroy();
            initJadwalSidangTable(idGelombang);
        });
        
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

        $('#form-tambah-jadwal').on('submit', function(e) {
            var $btn = $('#btn-tambah-jadwal');
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
        });

        // Submit form edit jadwal
        $('#form-edit-jadwal').on('submit', function(e) {
            var $btn = $('#btn-submit');
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
            // Submit form
            this.submit();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
        });
    });

    function initJadwalSidangTable(idGelombang = null) {
        let ajaxUrl = '{{ route('sidang.get-data-peserta') }}';
        if (idGelombang) {
            ajaxUrl = '{{ url('sidang/get-data-peserta') }}/' + idGelombang;
        }
        $('#jadwal-sidang-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: ajaxUrl,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'waktu', name: 'waktu' },
                { data: 'ruangan', name: 'ruangan' },
                { data: 'mahasiswa', name: 'mahasiswa' },
                { data: 'judul', name: 'judul' },
                { data: 'pembimbing', name: 'pembimbing' },
                { data: 'penguji', name: 'penguji' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            language: {
                emptyTable: "Tidak ada data sidang."
            }
        });
    }

    function showEditJadwalModal(id, btn = null) 
    {
        if (btn) {
            $(btn).prop('disabled', true);
            btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Tunggu...';
        }
        const url = `{{ route('sidang.get-detail', ':id') }}`.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const data = response.data
                console.log('====================================');
                console.log(data);
                console.log('====================================');
                const $form = $('#form-edit-jadwal');
                $form.attr('action', '{{ route('sidang.update-jadwal', '') }}/' + id);
                $form.find('#gelombangSidang').val(data.gelombangId);
                $form.find('#tanggalSidang').val(data.tanggal);
                $form.find('#waktuMulai').val(data.waktuMulai);
                $form.find('#waktuSelesai').val(data.waktuSelesai);
                $form.find('#ruanganSidang').val(data.ruangId);

                // Tampilkan tombol update status jika hari ini >= data.tanggal
                const today = new Date();
                const tanggalSidang = new Date(data.tanggal);
                if (today >= tanggalSidang) {
                    $('#view-update-status').show()
                    $('#btn-update-status')
                    .show()
                    .attr('onclick', `updateStatusSidang('${data.id}', this)`);
                } else {
                    $('#view-update-status').hide();
                }

                // Mahasiswa
                $form.find('#mahasiswaSidang').html(`<span class="form-control-plaintext">${data.nama ? data.nama : '-'}</span>`);

                // Pembimbing
                let pembimbingHtml = '';
                if (data.namaPembimbing1) {
                    pembimbingHtml += `<div class="mb-1">${data.namaPembimbing1}</div>`;
                }
                if (data.namaPembimbing2) {
                    pembimbingHtml += `<div>${data.namaPembimbing2}</div>`;
                }
                $form.find('#pembimbingSidang').html(pembimbingHtml);

                // Penguji
                $form.find('#pengujiSidang').val(data.pengujiIds);

                // Proposal
                if (data.proposal) {
                    $form.find('#sidangProposal').html(
                        `<a href="/berkas-sidang/${data.proposal}" target="_blank" class="btn btn-outline-primary btn-sm">Download Proposal</a>`
                    );
                } else {
                    $form.find('#sidangProposal').html('<span class="text-muted">Belum diunggah</span>');
                }

                // Kartu Bimbingan
                if (data.kartuBimbingan) {
                    $form.find('#sidangKartuBimbingan').html(
                        `<a href="/berkas-sidang/${data.kartuBimbingan}" target="_blank" class="btn btn-outline-primary btn-sm">Download Kartu Bimbingan</a>`
                    );
                } else {
                    $form.find('#sidangKartuBimbingan').html('<span class="text-muted">Belum diunggah</span>');
                }

                // Presentasi
                if (data.presentasi) {
                    $form.find('#sidangPresentasi').html(
                        `<a href="/berkas-sidang/${data.presentasi}" target="_blank" class="btn btn-outline-primary btn-sm">Download Presentasi</a>`
                    );
                } else {
                    $form.find('#sidangPresentasi').html('<span class="text-muted">Belum diunggah</span>');
                }

                // Pendukung
                if (data.pendukung) {
                    $form.find('#sidangPendukung').html(
                        `<a href="/berkas-sidang/${data.pendukung}" target="_blank" class="btn btn-outline-primary btn-sm">Download Pendukung</a>`
                    );
                } else {
                    $form.find('#sidangPendukung').html('<span class="text-muted">Belum diunggah/tidak diunggah</span>');
                }

                // Preferensi Jadwal Sidang Mahasiswa
                $('#preferensiCatatan').text(data.catatan ? data.catatan : '-');
                $('#preferensiHari').text(data.hari ? data.hari : '-');
                $('#preferensiWaktu').text(data.waktu ? data.waktu : '-');

                $('#editJadwalModal').modal('show');
                if (btn) {
                    $(btn).prop('disabled', false);
                    btn.innerHTML = '<i class="bi bi-pencil"></i>';
                }
            },
            error: function(xhr, status, error) {
                console.log('====================================');
                console.log(xhr);
                console.log('====================================');
                alert('Gagal mengambil data jadwal sidang.');
                if (btn) {
                    $(btn).prop('disabled', false);
                    btn.innerHTML = '<i class="bi bi-pencil"></i>';
                }
            }
        });
    }

    function updateStatusSidang(id, btn) 
    {
        swal({
            title: "Yakin ingin mengubah telah menyelesaikan sidang?",
            text: "Status sidang akan diubah menjadi selesai.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willUpdate) => {
            if (!willUpdate) {
                return;
            }
            if (btn) {
                $(btn).prop('disabled', true);
                btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Tunggu...';
            }
            const url = `{{ route('sidang.update-status-jadwal', ':id') }}`.replace(':id', id);
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    status: 1
                },
                success: function(response) {
                    $('#editJadwalModal').modal('hide');
                    $('#jadwal-sidang-table').DataTable().ajax.reload(null, false);
                    swal("Sukses!", response.message, "success");
                    if (btn) {
                        $(btn).prop('disabled', false);
                        btn.innerHTML = 'Selesai Sidang';
                    }
                },
                error: function(xhr, status, error) {
                    alert('Gagal memperbarui status sidang.');
                    if (btn) {
                        $(btn).prop('disabled', false);
                        btn.innerHTML = 'Selesai Sidang';
                    }
                }
            });
        });
    }
</script>
@endsection
