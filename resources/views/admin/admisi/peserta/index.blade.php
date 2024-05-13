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
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item active">Peserta</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <a href="{{URL::to('admin/admisi/peserta/create')}}" class="btn btn-primary" >+ {{$title}}</a>
                    </div>
                    <div class="card-body">
                        <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Nama.</th>
                                        <th>No. Pendaftaran</th>
                                        <th>Gelombang</th>
                                        <th>Pilihan1</th>
                                        <th>Pilihan2</th>
                                        <th>TTL</th>
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
            const title = "{{strtolower($title)}}";
            const page = '/'.concat("admin/admisi/").concat(title);
            var my_column = $('#my_column').val();
            const pecah = my_column.split('\n');
            let my_data = [];
            pecah.forEach((item, index) => {
                let temp = item.replace(/ /g, '');
                let data_obj = { data: temp };
                //alert(data_obj.data);
                my_data.push(data_obj);
            });
            //alert(data_obj);
            console.log(my_data);

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
                        '<a href="'+baseUrl+'/admin/admisi/peserta/'+full['id']+'/edit" class="btn btn-sm btn-icon edit-record text-primary"'
                            .concat(title, '"><i class="fa fa-pencil"></i></a>') +
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
                $('#formAdd').trigger("reset");
            });
            //Edit Record
            $(document).on('click', '.edit-record', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabel').html('Edit ' + title);

                // get data
                $.get(''.concat(baseUrl).concat(page, '/').concat(id, '/edit'), function (data) {
                Object.keys(data).forEach(key => {
                    //console.log(key);
                    $('#' + key)
                        .val(data[key])
                        .trigger('change');
                });
                });
            });
            //save record
            $('#formAdd').on('submit',function(e){
                e.preventDefault();
                $.ajax({
                    data: $('#formAdd').serialize(),
                    url: ''.concat(baseUrl).concat(page),
                    type: 'POST',
                    success: function success(status) {
                        dt.draw();
                        $("#tambahModal").modal('hide');

                        // sweetalert
                        swal({
                        icon: 'success',
                        title: 'Successfully '.concat(status, '!'),
                        text: ''.concat(title, ' ').concat(status, ' Successfully.'),
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                        });
                    },
                    error: function error(err) {
                        offCanvasForm.offcanvas('hide');
                        swal({
                        title: 'Duplicate Entry!',
                        text: title + ' Not Saved !',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                        });
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
