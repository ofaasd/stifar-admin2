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
                    <div class="table-responsive">
                        <table class="display" id="pembimbing-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Npp</th>
                                    <th>Nama</th>
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
                                <input class="form-control" type="hidden" name="nip" id="nip" placeholder="Please select">

                            <div class="col-md-12 position-relative">
                                <label class="form-label" for="validationTooltip03">Topik/Judul</label>
                                <input class="form-control" name="topik" id="topik" type="text" required="">
                            </div>
                            <div class="col-6">
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
    <script src="{{ asset('assets/js/select2/tagify.polyfills.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select3-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#pembimbing-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('mhs.pembimbing.getDaftarPembimbing') }}',
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

            $(document).on('click', '.btnModal', function() {
                var nip = $(this).data('id');
                $('#nip').val(nip); // Gunakan .val() untuk menetapkan nilai ke input
            });


            $('#FormModal').on('submit', function(e) {
                e.preventDefault(); // Mencegah default form submission
              
                // Siapkan data untuk dikirim
                var formData = {
                    nip: $('#nip').val(), // Masukkan NIP yang sudah dibersihkan
                    topik: $('#topik').val(),
                    _token: '{{ csrf_token() }}'
                };
                $.ajax({
                    url: '{{ route('mhs.pembimbing.pengajuan') }}',
                    method: 'POST',
                    data: formData, // Serializes form data
                    success: function(response) {
                        if (response.success) {
                            $('#FormModal').modal('hide'); // Tutup modal
                            $('#pembimbing-table').DataTable().ajax.reload(); 
                        swal("Success", "Berhasi Menambahkan Dosen Pembimbing", "success");
                        } else {
                        swal("Failed", response.message, "error");
                            console.log(response.message); // Tampilkan pesan error
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
