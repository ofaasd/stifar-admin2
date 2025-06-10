@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/tagify.css') }}">
<link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons/bootstrap-icons.css') }}">
@endsection

@section('breadcrumb-title')
<h3>Daftar Pembimbing</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Skripsi</li>
<li class="breadcrumb-item active">Daftar Pembimbing</li>
@endsection

@section('content')
<div class="page-content" id="pembimbing-page">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Dosen Pembimbing</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#FormModal"
                    id="tambahDosbim">
                        <i class="bi bi-plus-circle"></i> Tambah Dosen
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="pembimbing-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Kuota</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Dosen -->
    <div class="modal fade" id="FormModal" tabindex="-1" aria-labelledby="FormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formDosbim">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="FormModalLabel">Form Dosen Pembimbing</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" name="nip" id="nip" placeholder="Please select">
                        </div>
                        <div class="mb-3">
                            <label for="kuotaDosen" class="form-label">Kuota</label>
                            <input type="number" class="form-control" name="kuota" id="kuotaDosen" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/tagify.js') }}"></script>
<script>
    $(document).ready(function() {
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


            $('#pembimbing-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('pembimbing.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'npp',
                        name: 'npp'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'kuota',
                        name: 'kuota'
                    },
                    {
                        data: 'button',
                        name: 'button',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data dosen pembimbing yang tersedia." // Pesan ketika data kosong
                }
            });

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
                            $('#pembimbing-table').DataTable().ajax
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
</script>
@endsection
