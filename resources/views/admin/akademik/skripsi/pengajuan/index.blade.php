@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/tagify.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{ $title }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex flex-wrap align-items-end mb-3">
                            <div>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                    <i class="fa fa-print"></i> Cetak
                                </button>
                            </div>

                            <div class="ms-auto" style="min-width:260px;">
                                <label for="prodiSelect" class="form-label mb-1 small text-muted">Program Studi</label>
                                <select id="prodiSelect" class="form-select form-select-sm" onchange="loadPengajuan(this.value)">
                                    <option value="">Semua Prodi</option>
                                    @foreach($prodi as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="filterModalLabel">Cetak Pengajuan Skripsi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>

                                    <form action="{{ route('print-pengajuan-skripsi') }}" method="POST" target="_blank" class="row g-2 align-items-end">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="col-12">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="all">Semua</option>
                                                    @foreach ($statusPengajuan as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <label for="from_date" class="form-label">Dari Tanggal</label>
                                                <input type="date" name="fromDate" id="from_date" class="form-control" required>
                                            </div>

                                            <div class="col-12">
                                                <label for="to_date" class="form-label">Sampai Tanggal</label>
                                                <input type="date" name="toDate" id="to_date" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-download"></i> Download
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="display" id="pengajuan-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mahasiswa</th>
                                    <th>Judul</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Action</th>
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
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select3-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            loadPengajuan(null);

            var nip = $("#nip");
            var tagify;

            // Fungsi untuk menginisialisasi Tagify
            function initializeTagify() {
                tagify = new Tagify(nip[0], {
                    enforceWhitelist: true,
                    mode: "select",
                    whitelist: [],
                    dropdown: {
                    maxItems: 50,
                    }
                });
            }


            // Ambil data dan update whitelist Tagify
            $(document).on('click', '#tambahDosbim', function() {
                nip.val('');
                nip.prop('disabled', false);
                initializeTagify();
                $('#kuotaDosen').val('');

                $.ajax({
                    url: '{{ route('admin.pembimbing.getNppDosen') }}',
                    method: 'GET',
                    success: function(response) {
                        // Mengambil data dari respons dan menyiapkan whitelist untuk Tagify
                        var whitelistData = response.map(function(response) {
                            return {
                                value: response.npp + ' - ' + response.nama_lengkap
                            };
                        });

                        // Update whitelist Tagify setelah data tersedia
                        tagify.whitelist = whitelistData;
                        tagify.addTags(whitelistData.map(item => item.value));
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat mengambil data');
                        console.log(xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                var nip = $(this).data('id');
                console.log("test")
                if (tagify) {
                    tagify.destroy();
                }
                $.ajax({
                    url: '{{ route('admin.pembimbing.editDosen', '') }}/' + nip,
                    method: 'GET',
                    success: function(response) {
                        console.log(response)
                        $('#nip').val(response.nip);
                        $('#nip').prop('disabled', true);
                        $('#kuotaDosen').val(response.kuota);
                        $('#FormModal').modal('show');
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr.responseJSON?.message || 'An error occurred');
                    }
                });
            });

            // Contoh pemanggilan:
            // loadPengajuan('all');
            // atau loadPengajuan($('#prodiSelect').val());

            $('#formDosbim').on('submit', function(e) {
                e.preventDefault(); // Mencegah default form submission

                // Ambil nilai dari Tagify jika ada, atau gunakan nilai input langsung jika tidak ada
                var nip = '';

                if (tagify && tagify.value.length > 0) {
                    var nipTagify = tagify.value; // Ambil nilai dari Tagify
                    nip = nipTagify[0].value.split(' - ')[0]; // Ekstrak NIP dari Tagify
                } else {
                    nip = $('#nip').val(); // Fallback ke nilai input biasa jika Tagify kosong
                }

                // Siapkan data untuk dikirim
                var formData = {
                    nip: nip, // Masukkan NIP yang sudah dibersihkan
                    kuota: $('#kuotaDosen').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '{{ route('admin.pembimbing.updateKuota') }}',
                    method: 'POST',
                    data: formData, // Serializes form data
                    success: function(response) {
                        if (response.success) {
                            swal("Success", "Berhasil Update Kuota Dosen", "success");
                            $('#FormModal').modal('hide'); // Tutup modal
                            $('#pengajuan-table').DataTable().ajax
                        .reload(); // Reload DataTables
                        } else {
                            swal("Error", "Gagal Update Kuota Dosen", "error");
                        }
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat menyimpan data');
                        console.log(xhr.responseText); // Untuk debugging
                    }
                });
            });

        });

        function loadPengajuan(prodi) {
            // Destroy existing instance if any
            if ($.fn.dataTable.isDataTable('#pengajuan-table')) {
                $('#pengajuan-table').DataTable().destroy();
                $('#pengajuan-table tbody').empty();
            }

            return $('#pengajuan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('get-data-skripsi') }}',
                    data: {
                        prodi: prodi
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'mahasiswa',
                        name: 'mahasiswa'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data yang tersedia."
                }
            });
        }
    </script>
@endsection
