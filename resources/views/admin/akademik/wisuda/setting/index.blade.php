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
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="periode" class="form-label">Periode</label>
                                                    <input type="number" class="form-control" id="periode" name="periode" required placeholder="Masukkan periode">
                                                </div>
                                                <div class="col">
                                                    <label for="nama" class="form-label">Nama</label>
                                                    <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="mb-3 col">
                                                    <label for="tempat" class="form-label">Tempat</label>
                                                    <input type="text" class="form-control" id="tempat" name="tempat" required placeholder="Masukkan tempat">
                                                </div>
                                                <div class="mb-3 col">
                                                    <label for="waktu_pelaksanaan" class="form-label">Waktu Pelaksanaan</label>
                                                    <input type="datetime-local" class="form-control" id="waktu_pelaksanaan" name="waktu_pelaksanaan" required placeholder="Waktu pelaksanaan">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col">
                                                    <label for="tanggal_pemberkasan" class="form-label">Tanggal Pemberkasan</label>
                                                    <input type="date" class="form-control" id="tanggal_pemberkasan" name="tanggal_pemberkasan" required>
                                                </div>
                                                <div class="mb-3 col">
                                                    <label for="tanggal_gladi" class="form-label">Tanggal Gladi</label>
                                                    <input type="date" class="form-control" id="tanggal_gladi" name="tanggal_gladi" required>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="mb-3 col">
                                                    <label for="mulai_pendaftaran" class="form-label">Tanggal Mulai Pendaftaran</label>
                                                    <input type="date" class="form-control" id="mulai_pendaftaran" name="mulai_pendaftaran" required>
                                                </div>
                                                <div class="mb-3 col">
                                                    <label for="selesai_pendaftaran" class="form-label">Tanggal Selesai Pendaftaran</label>
                                                    <input type="date" class="form-control" id="selesai_pendaftaran" name="selesai_pendaftaran" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col">
                                                <label for="tarif_wisuda" class="form-label">Tarif Wisuda</label>
                                                <input type="number" class="form-control" id="tarif_wisuda" name="tarif_wisuda" required placeholder="Masukkan tarif wisuda">
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
                                        <th>Periode</th>
                                        <th>Nama</th>
                                        <th>Tempat</th>
                                        <th>Waktu Pelaksanaan</th>
                                        <th>Tanggal Pendaftaran</th>
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
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function render(data, type, full, meta) {
                        return (
                        '<div class="d-inline-block text-nowrap">' +
                        '<button class="btn btn-sm btn-icon edit-record text-primary" data-id="'
                            .concat(full['id'], '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal"')
                            .concat(title, '"><i class="fa fa-pencil"></i></button>') +
                        '<button class="btn btn-sm btn-icon delete-record text-primary" data-id="'.concat(
                            full['id'],
                            '"><i class="fa fa-trash"></i></button>'
                        )
                        );
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
            });
            //Edit Record
            $(document).on('click', '.edit-record', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabelEdit').html('Edit ' + title);

                // get data
                $.get(''.concat(baseUrl).concat(page, '/').concat(id, '/edit'), function (data) {
                    Object.keys(data).forEach(key => {
                        if(key == 'mulai_pendaftaran' || key == 'selesai_pendaftaran' || key == 'tanggal_pemberkasan' || key == 'tanggal_gladi'){
                            let formattedDate = data[key] ? data[key].slice(0, 10) : '';
                            $('#' + key)
                                .val(formattedDate)
                                .trigger('change');
                        }else{
                            $('#' + key)
                            .val(data[key])
                            .trigger('change');
                        }
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

            //delete record
            $(document).on('click', '.delete-record', function () {
                const id = $(this).data('id');
                // sweetalert for confirmation of delete
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
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
                        title: 'Deleted!',
                        text: 'The Record has been deleted!',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                } else {
                    swal({
                        title: 'Cancelled',
                        text: 'The record is not deleted!',
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
