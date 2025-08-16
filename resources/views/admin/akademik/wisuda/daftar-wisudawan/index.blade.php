@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item">Wisuda</li>
    <li class="breadcrumb-item active">{{ $title2 }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal">+ {{$title}}</button>
                        <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <form action="javascript:void(0)" id="formAdd">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Tambah {{$title}}</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <h5 class="mb-2">Pilih Gelombang Wisuda</h5>
                                                    <select class="form-select" name="gelombang_id" id="gelombang_id" {{ count($gelombang) == 0 ? 'disabled' : '' }}>
                                                            @if(count($gelombang) > 0)
                                                                    @foreach($gelombang as $row)
                                                                            <option value="{{ $row->id }}"> 
                                                                                {{ $row->nama }} | {{ $row->tempat }} | {{ \Carbon\Carbon::parse($row->waktu_pelaksanaan)->translatedFormat('d F Y H:i'); }} | {{ \Carbon\Carbon::parse($row->mulai_pendaftaran)->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::parse($row->selesai_pendaftaran)->translatedFormat('d F Y') }}
                                                                            </option>
                                                                    @endforeach
                                                            @else
                                                                    <option value="">Tidak ada gelombang wisuda tersedia</option>
                                                            @endif
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <h5 class="mb-2">Pilih Mahasiswa</h5>
                                                    <select class="form-select" name="nim" id="nim" {{ count($mhs) == 0 ? 'disabled' : '' }}>
                                                            @if(count($mhs) > 0)
                                                                    @foreach($mhs as $row)
                                                                            <option value="{{ $row->nim }}"> 
                                                                                {{ $row->nim }} | {{ $row->nama }}
                                                                            </option>
                                                                    @endforeach
                                                            @else
                                                                    <option value="">Tidak ada mahasiswa tersedia</option>
                                                            @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" type="submit" id="btn-submit">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>nim</th>
                                        <th>Wisuda</th>
                                        <th>Yudisium</th>
                                        <th>Berkas</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>

    <script>
        $(function () {
            const baseUrl = {!! json_encode(url('/')) !!};
            const title = "{{strtolower($title2)}}";
            const page = '/'.concat("admin/akademik/wisuda/").concat(title);
            var my_column = $('#my_column').val();
            const pecah = my_column.split('\n');
            let my_data = [];
            pecah.forEach((item, index) => {
                let temp = item.replace(/ /g, '');
                let data_obj = { data: temp };
                my_data.push(data_obj);
            });

            const dt = $("#basic-1").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: baseUrl.concat(page),
                },
                columns: my_data,
                columnDefs: [
                    {
                        // For Responsive
                        className: 'control',
                        searchable: false,
                        orderable: false,
                        responsivePriority: 2,
                        targets: 0,
                        render: function render(data, type, full, meta) {
                            return '';
                        }
                    },
                    {
                        searchable: false,
                        orderable: false,
                        targets: 1,
                        render: function render(data, type, full, meta) {
                            return '<span>'.concat(full.fake_id, '</span>');
                        }
                    },
                    {
                        searchable: false,
                        orderable: false,
                        targets: 2,
                        render: function render(data, type, full, meta) {
                            // Render NIM, Nama, and Photo
                            var photoUrl = full.photo ? `{{ asset("assets/images/mahasiswa/") }}/${full.photo}` : '{{ asset("assets/images/user/1.jpg") }}';
                            return `
                                <div class="d-flex align-items-center">
                                    <img src="${photoUrl}" alt="Photo" class="rounded-circle me-2" width="40" height="40">
                                    <div>
                                        <div><strong>${full.nim}</strong></div>
                                        <div>${full.nama}</div>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        searchable: false,
                        orderable: false,
                        targets: 5,
                        render: function render(data, type, full, meta) {
                            const fileBase = '{{ asset("assets/file/berkas/mahasiswa") }}';
                            const links = [
                                full.kk ? `<a href="${fileBase}/kk/${full.kk}" target="_blank" class="btn btn-sm btn-outline-primary mb-1" title="KK"><i class="fa fa-id-card"></i></a>` : '',
                                full.ktp ? `<a href="${fileBase}/ktp/${full.ktp}" target="_blank" class="btn btn-sm btn-outline-primary mb-1" title="KTP"><i class="fa fa-address-card"></i></a>` : '',
                                full.akte ? `<a href="${fileBase}/akte/${full.akte}" target="_blank" class="btn btn-sm btn-outline-primary mb-1" title="Akte"><i class="fa fa-address-book-o"></i></a>` : '',
                                full.ijazah_depan ? `<a href="${fileBase}/ijazah_depan/${full.ijazah_depan}" target="_blank" class="btn btn-sm btn-outline-primary mb-1" title="Ijazah Depan"><i class="fa fa-file"></i></a>` : '',
                                full.ijazah_belakang ? `<a href="${fileBase}/ijazah_belakang/${full.ijazah_belakang}" target="_blank" class="btn btn-sm btn-outline-primary mb-1" title="Ijazah Belakang"><i class="fa fa-file"></i></a>` : ''
                            ].filter(Boolean).join(' ');
                            return links ? links : '-';
                        }
                    },
                    {
                        // Actions
                        targets: -1,
                        title: 'Actions',
                        searchable: false,
                        orderable: false,
                        render: function render(data, type, full, meta) {
                            if (full['status_daftar'] == 0) {
                                return (
                                    '<div class="d-inline-block text-nowrap">' +
                                    '<button class="btn btn-sm btn-icon acc-record text-primary" data-id="' +
                                    full['id'] +
                                    '" title="Acc"><i class="fa fa-check"></i></button>' +
                                    '<button class="btn btn-sm btn-icon delete-record text-primary" title="Hapus" data-id="' +
                                    full['id'] +
                                    '"><i class="fa fa-times"></i></button>' +
                                    '</div>'
                                );
                            } else if (full['status_daftar'] == 1) {
                                return (
                                    '<div class="d-inline-block text-nowrap">' +
                                    '<span class="badge bg-success me-1">Sudah disetujui</span>' +
                                    '<button class="btn btn-sm btn-icon delete-record text-primary" title="Hapus" data-id="' +
                                    full['id'] +
                                    '"><i class="fa fa-times"></i></button>' +
                                    '</div>'
                                );
                            } else {
                                return '';
                            }
                        }
                    }
                ],
                order: [[2, 'desc']],
                dom:
                    '<"row mx-2"' +
                    '<"col-md-2"<"me-3"l>>' +
                    '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
                    '>t' +
                    '<"row mx-2"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                language: {
                    sLengthMenu: '_MENU_',
                    search: '',
                    searchPlaceholder: 'Search..'
                },
            });
            $('#tambahModal').on('hidden.bs.modal', function () {
                $('#formAdd').find('input, textarea, select').val('');
                $('#formAdd').trigger("reset");
                $('#id').val('');
                $('#mhs-selected').empty();
            });
            //Edit Record
            $(document).on('click', '.edit-record', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelEdit').html('Edit ' + title);

                // get data
                $.get(''.concat(baseUrl).concat(page, '/').concat(id, '/edit'), function (data) {
                    Object.keys(data).forEach(key => {
                        $('#' + key)
                        .val(data[key])
                        .trigger('change');
                    });
                });
            });

            //save record
            $('#formAdd').on('submit', function(e) {
                e.preventDefault();
                var btnSubmit = $('#btn-submit');
                var oldBtnHtml = btnSubmit.html();
                btnSubmit.prop('disabled', true);
                btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                $('#mhs-selected option').prop('selected', true);

                $.ajax({
                    data: $('#formAdd').serialize(),
                    url: ''.concat(baseUrl).concat(page),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                    },
                    success: function success(message) {
                        dt.draw();
                        $("#tambahModal").modal('hide');
                        swal({
                            icon: 'success',
                            title: 'Successfully '.concat(message, '!'),
                            text: ''.concat(title, ' ').concat(message, ' Successfully.'),
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        btnSubmit.prop('disabled', false);
                        btnSubmit.html(oldBtnHtml);
                    },
                    error: function error(err) {
                        swal({
                            title: err.responseText || 'Duplicate Entry.',
                            text: title + ' Not Saved !',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        btnSubmit.prop('disabled', false);
                        btnSubmit.html(oldBtnHtml);
                    }
                });
            });

            //acc record
            $(document).on('click', '.acc-record', function () {
                const id = $(this).data('id');
                // sweetalert for confirmation of acc
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert accept this!",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, acc!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                if (result) {

                    // acc the data
                    $.ajax({
                    type: 'POST',
                    url: ''.concat(baseUrl).concat(page, '/acc/').concat(id),
                    data:{
                        'id': id,
                        '_method': 'PUT',
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function success() {
                        dt.draw();
                    },
                    error: function error(_error) {
                        swal({
                            title: 'Failed!',
                            text: _error.message,
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        // console.log('====================================');
                        // console.log(_error);
                        // console.log('====================================');
                    }
                    });

                    // success sweetalert
                    swal({
                        icon: 'success',
                        title: 'Accepted!',
                        text: 'The Record has been accepted!',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                } else {
                    swal({
                        title: 'Cancelled',
                        text: 'The record is not accepted!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                }
                });
            });

            //reject record
            $(document).on('click', '.delete-record', function () {
                const id = $(this).data('id');
                // sweetalert for confirmation of reject
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert the reject this!",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, reject it!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                if (result) {

                    // delete the data
                    $.ajax({
                    type: 'DELETE',
                    url: ''.concat(baseUrl).concat(page, '/').concat(id),
                    data:{
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function success() {
                        dt.draw();
                    },
                    error: function error(_error) {
                        swal({
                            title: 'Failed!',
                            text: _error.message,
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        // console.log('====================================');
                        // console.log(_error.error);
                        // console.log('====================================');
                    }
                    });

                    // success sweetalert
                    swal({
                        icon: 'success',
                        title: 'Rejected!',
                        text: 'The Record has been rejected!',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                } else {
                    swal({
                        title: 'Cancelled',
                        text: 'The record is not rejected!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                }
                });
            });
        });

    </script>
@endsection
