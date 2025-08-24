@extends('layouts.master')
@section('title', 'Gedung')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title2}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master</li>
    <li class="breadcrumb-item active">Gedung</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        @if(empty($link))
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal" id="add-record">+ {{$title2}}</button>
                        @endif
                        <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="javascript:void(0)" id="formAdd">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Tambah {{$title2}}</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3" id="field-nama">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" class="form-control" name="nama" id="nama">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" id="btn-submit" type="submit">Simpan</button>
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
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        @foreach($jenis as $row)
                                        <th>{{$row->nama}}</th>
                                        @endforeach
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
        console.log($("#my_column").val())
        const baseUrl = {!! json_encode(url('/')) !!};
        const title = "{{strtolower($title)}}";
        const title2 = "{{ $title2 }}";
        const page = '/'.concat("admin/keuangan/").concat(title,'_show/{{$id}}');
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
                    className: 'control',
                    searchable: false,
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function render() {
                        return '';
                    }
                },
                {
                    searchable: false,
                    orderable: false,
                    targets: 1,
                    render: function render(data, type, full) {
                        return `<span>${full['fake_id']}</span>`;
                    }
                },
                {
                    targets: -1,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function render(data, type, full) {
                        return `
                            <div class="d-inline-block text-nowrap">
                                <button class="btn btn-sm btn-icon edit-record text-primary" data-id="${full['id']}" data-bs-toggle="modal" data-bs-target="#tambahModal">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-icon delete-record text-primary" data-id="${full['id']}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>`;
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
            $('#formAdd').trigger("reset");
        });

        // Add/Edit Record
        $(document).on('click', '#add-record', function () {
            $('#ModalLabel').html('Tambah ' + title2);
            $("#id").val('');
            $('#formAdd').trigger("reset");
        });

        $(document).on('click', '.edit-record', function () {
            const id = $(this).data('id');
            $('#ModalLabel').html('Edit ' + title2);

            $.get(`${baseUrl}${page}/${id}/edit`, function (data) {
                Object.keys(data).forEach(key => {
                    $('#' + key).val(data[key]).trigger('change');
                });
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.error('Error fetching data:', textStatus, errorThrown);
            });
        });

        // Save record
        $('#formAdd').on('submit', function (e) {
            e.preventDefault();
            const myFormData = new FormData(this);

            var btnSubmit = $('#btn-submit');
            btnSubmit.prop('disabled', true);

            $.ajax({
                data: myFormData,
                url: `${baseUrl}${page}`,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function (status) {
                    dt.draw();
                    $("#tambahModal").modal('hide');
                    swal({
                        icon: 'success',
                        title: `Successfully ${status}!`,
                        text: `${title} ${status} successfully.`,
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                    btnSubmit.prop('disabled', false);
                },
                error: function (xhr) {
                    $("#tambahModal").modal('hide');
                    let errMsg = 'An error occurred. Please try again.';
                    if (xhr.status === 422) { // Laravel validation error
                        errMsg = xhr.responseJSON.message;
                    }
                    swal({
                        icon: 'error',
                        title: 'Error!',
                        text: errMsg,
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
            });
        });

        // Delete record
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
                        console.log(_error);
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
