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
    <li class="breadcrumb-item">Yudisium</li>
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
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Tambah {{$title}}</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body d-flex flex-wrap gap-3">
                                            <div class="mb-3 flex-fill w-100">
                                                <div class="mb-3">
                                                    <label for="gelombang" class="form-label">Gelombang Yudisium</label>
                                                    <select class="form-control form-control-lg" id="gelombang" name="gelombang" required style="width:100%;">
                                                        <option value="">-- Pilih Gelombang --</option>
                                                        @foreach($gelombang as $row)
                                                            <option value="{{ $row->id }}">
                                                                {{ $row->periode }} | 
                                                                {{ $row->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label>Daftar Mahasiswa Lulus</label>
                                                        <select id="mhs-available" class="form-control" size="10" multiple style="height:auto;">
                                                            @foreach($mhs as $row)
                                                                <option value="{{ $row->nim }}"
                                                                    title="{{ $row->nim }} | {{ $row->nama }} | sks: {{ $row->totalSks }} | ipk: {{ $row->ipk }}"
                                                                    data-foto="{{ $row->foto_mhs ? asset('assets/images/mahasiswa/' . $row->foto_mhs) : asset('assets/images/user/1.jpg') }}"
                                                                    class="px-3 py-2">
                                                                    {{ $row->nim }} | {{ $row->nama }} | sks: {{ $row->totalSks }} | ipk: {{ $row->ipk }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-1 d-flex flex-column justify-content-center align-items-center gap-2">
                                                        <button type="button" class="btn btn-primary btn-sm" id="add-mhs">&gt;&gt;</button>
                                                        <button type="button" class="btn btn-primary btn-sm" id="remove-mhs">&lt;&lt;</button>
                                                    </div>
                                                    <div class="col-5">
                                                        <label>Mahasiswa Terpilih</label>
                                                        <select id="mhs-selected" name="listMahasiswa[]" class="form-control" size="10" multiple>
                                                            <!-- Selected mahasiswa will appear here -->
                                                        </select>
                                                    </div>
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

                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form action="javascript:void(0)" id="formEdit">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabelEdit">Edit {{$title}}</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <div class="modal-body d-flex flex-wrap gap-3">
                                            <div class="mb-3 flex-fill w-100">
                                                <div class="mb-3">
                                                    <label for="gelombang" class="form-label">Gelombang Yudisium</label>
                                                    <select class="form-control" id="id_gelombang_yudisium" name="gelombang" required>
                                                        <option value="">-- Pilih Gelombang --</option>
                                                        @foreach($gelombang as $row)
                                                            <option value="{{ $row->id }}">
                                                                {{ $row->periode }} | 
                                                                {{ $row->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label>Mahasiswa</label>
                                                        <select id="nim" name="nim" class="form-control">
                                                            @foreach($mhs as $row)
                                                                <option value="{{ $row->nim }}">{{ $row->nim }} - {{ $row->nama }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" type="submit" id="btn-submit-edit">Simpan</button>
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
                                        <th>NIM/Nama</th>
                                        <th>SKS/IPK</th>
                                        <th>Jumlah Nilai D/ E</th>
                                        <th>Gelombang</th>
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
            const page = '/'.concat("admin/akademik/yudisium/").concat(title);
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
                            .concat(full['id'], '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#editModal"')
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
                        console.log('====================================');
                        console.log(key);
                        console.log('====================================');
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

            $('#formEdit').on('submit', function(e) {
                e.preventDefault();
                var btnSubmit = $('#btn-submit-edit');
                var oldBtnHtml = btnSubmit.html();
                btnSubmit.prop('disabled', true);
                btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                $('#mhs-selected option').prop('selected', true);

                $.ajax({
                    data: $('#formEdit').serialize(),
                    url: ''.concat(baseUrl).concat(page),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                    },
                    success: function success(message) {
                        dt.draw();
                        $("#editModal").modal('hide');
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

            $(document).on('click', '#add-mhs', function() {
                $('#mhs-available option:selected').each(function() {
                    $('#mhs-selected').append($(this).clone());
                    $(this).remove();
                });
            });
            $(document).on('click', '#remove-mhs', function() {
                $('#mhs-selected option:selected').each(function() {
                    $('#mhs-available').append($(this).clone());
                    $(this).remove();
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
