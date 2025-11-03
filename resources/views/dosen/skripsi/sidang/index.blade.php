@extends('layouts.master')
@section('title', 'Data Gelombang Sidang')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons/bootstrap-icons.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Jadwal Sidang</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item active">Jadwal Sidang</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Jadwal Sidang</h5>
            <form action="{{ route('sidang.print-peserta') }}" method="POST" target="_blank" class="m-0">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-printer me-1"></i> Cetak Peserta Sidang
            </button>
            </form>
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
                            <label for="tanggalSidangAdd" class="form-label">Tanggal Sidang</label>
                            <input type="date" class="form-control" id="tanggalSidangAdd" name="tanggal" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="waktuMulaiAdd" class="form-label">Waktu Mulai</label>
                            <input type="time" class="form-control" id="waktuMulaiAdd" name="waktuMulai" required>
                        </div>
                        <div class="col-md-6">
                            <label for="waktuSelesaiAdd" class="form-label">Waktu Selesai</label>
                            <input type="time" class="form-control" id="waktuSelesaiAdd" name="waktuSelesai" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ruanganSidangAdd" class="form-label">Ruangan</label>
                        <select class="form-select" id="ruanganSidangAdd" name="ruangId">
                            <option value="" selected>Pilih Ruangan</option>
                            @foreach ($ruang as $row)
                                <option value="{{ $row->id }}">{{ $row->nama_ruang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mahasiswaSidangAdd" class="form-label">Mahasiswa</label>
                        <select class="form-select" id="mahasiswaSidangAdd" name="masterSkripsiId">
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
                            <label for="pengujiSidangAdd" class="form-label">Penguji</label>
                            <select class="form-select" id="pengujiSidangAdd" name="penguji[]" multiple>
                                @foreach ($pegawai as $row)
                                    <option value="{{ $row->npp }}">{{ $row->npp }} / {{ $row->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="jenisSidangAdd" class="form-label">Jenis Sidang</label>
                        <select class="form-select" id="jenisSidangAdd" name="jenisSidang" required>
                            <option value="" selected disabled>Pilih Jenis Sidang</option>
                            <option value="1">Seminar Proposal</option>
                            <option value="2">Seminar Hasil</option>
                        </select>
                    </div>
                    <input type="hidden" name="status" value="2">
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-tambah-jadwal">Update</button>
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

            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                <form action="" method="post" id="form-edit-jadwal">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="form-label">Mahasiswa</label>
                            <div id="mahasiswaSidangView" class="bg-info text-light bg-opacity-25 p-2 rounded"></div>
                        </div>

                        <div class="row align-items-center mt-2">
                            <div class="col-md-6 mb-3">
                                <label for="tanggalSidangEdit" class="form-label">Tanggal Sidang</label>
                                <input type="date" class="form-control" id="tanggalSidangEdit" name="tanggal" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="waktuMulaiEdit" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" id="waktuMulaiEdit" name="waktuMulai" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="waktuSelesaiEdit" class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control" id="waktuSelesaiEdit" name="waktuSelesai" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ruanganSidangEdit" class="form-label">Ruangan</label>
                                <select class="form-select" id="ruanganSidangEdit" name="ruangId">
                                    @foreach ($ruang as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama_ruang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3" id="view-update-status" style="display: none;">
                            <div class="mb-2">
                                <label class="form-label mb-1"><strong>Pembimbing</strong></label>
                                <div id="nilaiPembimbing" class="ps-2"></div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label mb-1"><strong>Penguji</strong></label>
                                <div id="nilaiPenguji" class="ps-2"></div>
                            </div>

                            <label for="input-nilai-sidang" class="form-label mt-2"><strong>Nilai Sidang Akhir</strong></label>
                            <input type="number" min="0" max="100" class="form-control mb-2" id="input-nilai-sidang" placeholder="Masukkan nilai akhir">
                            <small class="text-muted d-block">Catatan: Penilaian dibuka sampai H+7 setelah tanggal sidang.</small>

                            <button
                                type="button"
                                class="btn btn-success btn-sm mt-2"
                                id="btn-update-status"
                                style="display: none;"
                                title="Klik untuk menandai sidang ini telah selesai dan menutup proses sidang">
                                Simpan nilai dan selesai sidang
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <div class="d-flex flex-wrap gap-2">
                                    <div><strong>Proposal:</strong> <span id="sidangProposal"></span></div>
                                    <div><strong>Presentasi:</strong> <span id="sidangPresentasi"></span></div>
                                    <div><strong>Pendukung:</strong> <span id="sidangPendukung"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row select-penguji">
                        <div class="col-12 mb-3">
                            <label for="pengujiSidangEdit" class="form-label">Penguji</label>
                            <select class="form-select" id="pengujiSidangEdit" name="penguji[]" multiple size="8">
                                @foreach ($pegawai as $row)
                                    <option value="{{ $row->npp }}">{{ $row->npp }} / {{ $row->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Tekan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu penguji.</small>
                            <div id="listPengujiTerpilih" class="mt-2"></div>
                        </div>
                    </div>

                    <div class="modal-footer mt-2 btn-group-form-edit-jadwal">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-submit" id="btn-submit">Simpan</button>
                    </div>
                </form>
                <div class="mt-3">
                    <form id="form-download-daftar-hadir" action="{{ route('sidang.print-daftar-hadir') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="id" id="input-daftar-hadir-sidang-id" value="">
                        <button type="submit" class="btn btn-outline-info">
                            <i class="bi bi-download"></i> Download Lembar Daftar Hadir Seminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
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
        
        // SweetAlert konfirmasi hapus (delegated)
        $(document).on('submit', '.delete-form', function (e) {
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

        // Submit tombol spinner (tambah & edit)
        $('#form-tambah-jadwal').on('submit', function(e) {
            var $btn = $(this).find('button[type="submit"]');
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
        });

        $('#form-edit-jadwal').on('submit', function(e) {
            var $btn = $('#btn-submit');
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
            // Submit form normally
        });
    });

    function initJadwalSidangTable(idGelombang = null) {
        let ajaxUrl = '{{ route("sidang.get-data-peserta") }}';
        if (idGelombang) {
            ajaxUrl = '{{ url("sidang/get-data-peserta") }}' + '/' + idGelombang;
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
                const $form = $('#form-edit-jadwal');
                $form.attr('action', '{{ route('sidang.update-jadwal', '') }}/' + id);
                $form.find('#tanggalSidangEdit').val(data.tanggal);
                $form.find('#waktuMulaiEdit').val(data.waktuMulai);
                $form.find('#waktuSelesaiEdit').val(data.waktuSelesai);
                $form.find('#ruanganSidangEdit').val(data.ruangId);

                $('#input-daftar-hadir-sidang-id').val(data.id);

                // Tampilkan tombol update status jika hari ini >= data.tanggal dan belum ada nilaiAkhir
                // atau masih dalam rentang H+7 setelah tanggal sidang
                const now = new Date();
                const today = new Date();
                today.setHours(0,0,0,0);
                const tanggalSidang = new Date(data.tanggal);
                tanggalSidang.setHours(0,0,0,0);

                const tanggalAkhir = new Date(tanggalSidang);
                tanggalAkhir.setDate(tanggalAkhir.getDate() + 7);
                tanggalAkhir.setHours(23,59,59,999);

                const withinRangeDate = (today >= tanggalSidang && today <= tanggalAkhir);

                let startDateTime = null;
                if (data.waktuMulai) {
                    const [h, m] = data.waktuMulai.split(':').map(Number);
                    startDateTime = new Date(tanggalSidang);
                    startDateTime.setHours(h || 0, m || 0, 0, 0);
                }

                let allowedToOpen = false;
                if (data.nilaiAkhir) {
                    $('#view-update-status').show();
                    $('#input-daftar-hadir-sidang-id').val(data.id);
                    $('#input-nilai-sidang').val(data.nilaiAkhir).prop('disabled', true);
                    $('#btn-update-status').hide();
                } else if (withinRangeDate) {
                    if (today.getTime() > tanggalSidang.getTime()) {
                        allowedToOpen = true;
                    } else {
                        if (startDateTime) {
                            if (now >= startDateTime) allowedToOpen = true;
                        } else {
                            allowedToOpen = true;
                        }
                    }

                    if (allowedToOpen) {
                        // Disable form inputs except nilai input and cancel buttons
                        $form.find('input, select, textarea, button[type="submit"]').not('#input-nilai-sidang').prop('disabled', true).prop('readonly', true);
                        $form.find('button[data-bs-dismiss="modal"]').prop('disabled', false);
                        
                        $('#view-update-status').show();
                        $('#btn-update-status')
                            .show()
                            .attr('onclick', `updateStatusSidang('${data.id}', this)`);
                        $('#input-nilai-sidang').val('').prop('disabled', false);
                        $('.btn-group-form-edit-jadwal').hide();
                        $('.select-penguji').hide();
                    } else {
                        $('#view-update-status').show();
                        $('#btn-update-status').hide();
                        $('#input-nilai-sidang').val('').prop('disabled', true);
                    }
                } else {
                    $('#view-update-status').hide();
                }

                // Mahasiswa
                $form.find('#mahasiswaSidangView').html(`<span class="form-control-plaintext text-white">${data.nim ? data.nim : '-'} - ${data.nama ? data.nama : '-'}</span>`);

                // Pembimbing & Nilai Pembimbing (tampilkan sebagai list)
                let pembimbingList = '<ul class="list-group list-group-flush">';
                if (data.namaPembimbing1) {
                    pembimbingList += `<li class="list-group-item p-1">${data.nppPembimbing1} / ${data.namaPembimbing1}</li>`;
                }
                if (data.namaPembimbing2) {
                    pembimbingList += `<li class="list-group-item p-1">${data.nppPembimbing2} / ${data.namaPembimbing2}</li>`;
                }
                if (!data.namaPembimbing1 && !data.namaPembimbing2) {
                    pembimbingList += '<li class="list-group-item p-1 text-muted">-</li>';
                }
                pembimbingList += '</ul>';
                $form.find('#nilaiPembimbing').html(pembimbingList);

                // Nilai Penguji
                let nilaiPengujiHtml = '<ul class="list-group list-group-flush">';
                if (data.nilaiPenguji && typeof data.nilaiPenguji === 'object') {
                    const entries = Object.entries(data.nilaiPenguji);
                    if (entries.length) {
                        entries.forEach(([npp]) => {
                            let namaPenguji = $form.find(`#pengujiSidangEdit option[value="${npp}"]`).text();
                            if (!namaPenguji) namaPenguji = npp;
                            nilaiPengujiHtml += `<li class="list-group-item p-1">${namaPenguji}</li>`;
                        });
                    } else {
                        nilaiPengujiHtml += '<li class="list-group-item p-1 text-muted">-</li>';
                    }
                } else {
                    nilaiPengujiHtml += '<li class="list-group-item p-1 text-muted">-</li>';
                }
                nilaiPengujiHtml += '</ul>';
                $form.find('#nilaiPenguji').html(nilaiPengujiHtml);
                
                // Penguji (set selected values)
                $form.find('#pengujiSidangEdit').val(data.pengujiIds);

                // Proposal
                if (data.proposal) {
                    $form.find('#sidangProposal').html(
                        `<a href="/berkas-sidang/${data.proposal}" target="_blank" class="btn btn-outline-primary btn-sm">Download Proposal</a>`
                    );
                } else {
                    $form.find('#sidangProposal').html('<span class="text-muted">Belum diunggah</span>');
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

                $('#editJadwalModal').modal('show');
                if (btn) {
                    $(btn).prop('disabled', false);
                    btn.innerHTML = '<i class="bi bi-eye"></i>';
                }
            },
            error: function(xhr, status, error) {
                swal("Gagal!", "Gagal mengambil data jadwal sidang.", "error");
                if (btn) {
                    $(btn).prop('disabled', false);
                    btn.innerHTML = '<i class="bi bi-eye"></i>';
                }
            }
        });
    }

    function updateStatusSidang(id, btn) {
        var nilai = $('#input-nilai-sidang').val();
        if (nilai === '' || nilai < 0 || nilai > 100) {
            swal("Nilai tidak valid!", "Masukkan nilai sidang antara 0 - 100.", "warning");
            return;
        }
        swal({
            title: "Yakin ingin mengubah telah menyelesaikan sidang?",
            text: "Status sidang akan diubah menjadi selesai dan nilai akan disimpan.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willUpdate) => {
            if (!willUpdate) return;
            $(btn).prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Tunggu...');

            $.ajax({
                url: `{{ route('sidang.penilaian-sidang', ':id') }}`.replace(':id', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    status: 1,
                    nilai: nilai
                },
                success: function(response) {
                    $('#editJadwalModal').modal('hide');
                    $('#jadwal-sidang-table').DataTable().ajax.reload(null, false);
                    swal("Sukses!", response.message, "success");
                    $(btn).prop('disabled', false).html('Simpan nilai dan selesai sidang');
                },
                error: function(xhr) {
                    swal("Gagal!", "Gagal memperbarui status sidang.", "error");
                    $(btn).prop('disabled', false).html('Simpan nilai dan selesai sidang');
                }
            });
        });
    }
</script>
@endsection
