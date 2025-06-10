@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
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

    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
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
                        data: 'nama_lengkap',
                        name: 'nama_lengkap'
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
                    emptyTable: "Tidak ada data dosen pembimbing yang tersedia." 
                }


            });

            $(document).on('click', '.btnModal', function(e) {
                e.preventDefault(); // Mencegah tindakan default tombol
                var nip = $(this).data('id'); // Ambil data NIP dari atribut tombol

                swal({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan mengajukan dosen pembimbing ini.",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((result) => {
                    if (result) {
                        var nip = $(this).data('id');

                        // Siapkan data untuk dikirim
                        var formData = {
                            nip: nip, // Masukkan NIP yang sudah dibersihkan
                            _token: '{{ csrf_token() }}'
                        };
                        console.log(formData)
                        $.ajax({
                            url: '{{ route('mhs.pembimbing.pengajuan') }}',
                            method: 'POST',
                            data: formData,
                            success: function(response) {
                                if (response.success) {
                                    $('#pembimbing-table').DataTable().ajax.reload();
                                    swal("Success",
                                        "Berhasi Menambahkan Dosen Pembimbing",
                                        "success");
                                } else {
                                    swal("Failed", response.message, "error");
                                    console.log(response
                                    .message); // Tampilkan pesan error
                                }
                            },
                            error: function(xhr) {
                                alert('Terjadi kesalahan saat menyimpan data');
                                console.log(xhr.responseText); // Untuk debugging
                            }
                        });
                    } else {
                        swal("Your imaginary file is safe!");
                    }
                })
                // Tampilkan SweetAlert2 untuk konfirmasi
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan mengajukan dosen pembimbing ini.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ajukan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#nip').val(nip); // Masukkan nilai ke input

                        // Siapkan data untuk dikirim
                        var formData = {
                            nip: $('#nip').val(), // Masukkan NIP yang sudah dibersihkan
                            _token: '{{ csrf_token() }}'
                        };
                        $.ajax({
                            url: '{{ route('mhs.pembimbing.pengajuan') }}',
                            method: 'POST',
                            data: formData, // Serializes form data
                            success: function(response) {
                                if (response.success) {
                                    $('#pembimbing-table').DataTable().ajax.reload();
                                    swal("Success",
                                        "Berhasi Menambahkan Dosen Pembimbing",
                                        "success");
                                } else {
                                    swal("Failed", response.message, "error");
                                    console.log(response
                                    .message); // Tampilkan pesan error
                                }
                            },
                            error: function(xhr) {
                                alert('Terjadi kesalahan saat menyimpan data');
                                console.log(xhr.responseText); // Untuk debugging
                            }
                        });
                    }
                });
            });



        });
    </script>
@endsection
