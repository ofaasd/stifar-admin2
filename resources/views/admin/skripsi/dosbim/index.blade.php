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
    <h3>{{ 'Daftar Dosen Pembimbing' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{ 'Daftar Dosen Pembimbing' }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#FormModal"
                        id="tambahDosbim">Tambah Dosen
                        Pembimbing</button>
                    <div class="table-responsive">
                        <table class="display" id="pembimbing-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Npp</th>
                                    <th>Nama Dosen</th>
                                    <th>Kuota</th>
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
        <!--Centered modal-->
        <div class="modal fade" id="FormModal" tabindex="-1" role="dialog" aria-labelledby="FormModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form class="row g-3 needs-validation custom-input" id="formDosbim">
                            <div class="col-md-12 position-relative">
                                <label class="form-label" for="npp">Pilih Dosen</label>
                                <input class="form-control" name="npp" id="npp" placeholder="Please select">
                            </div>

                            <div class="col-md-12 position-relative">
                                <label class="form-label" for="validationTooltip03">Kuota</label>
                                <input class="form-control" name="kuota" id="kuotaDosen" type="number" required="">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Submit form</button>
                            </div>
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
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select3-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            var nip = $("#npp");
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
                var npp = $(this).data('id');
                if (tagify) {
                    tagify.destroy();
                }
                $.ajax({
                    url: '{{ route('admin.pembimbing.editDosen', '') }}/' + npp,
                    method: 'GET',
                    success: function(response) {
                        $('#npp').val(response.npp);
                        $('#npp').prop('disabled', true);
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
                ajax: '{{ route('admin.pembimbing.listDosen') }}',
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
                    nip = $('#npp').val(); // Fallback ke nilai input biasa jika Tagify kosong
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
