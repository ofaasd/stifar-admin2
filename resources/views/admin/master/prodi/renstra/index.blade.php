@extends('layouts.master')
@section('title', 'Basic DataTables')

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
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item active">Atribut Prodi</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="row">

                            <div class="col-md-4">
                                <select name="prodi" id="prodi" class="form-control">
                                    @foreach($prodi as $row)
                                        <option value="{{$row->id}}" {{($id_prodi == $row->id)?"selected":""}}>{{$row->nama_prodi}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal" id="add-record">+ {{$title2}}</button>

                                <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="javascript:void(0)" id="formAdd">
                                                @csrf
                                                <input type="hidden" name="id" id="id">
                                                <input type="hidden" name="id_prodi" value="{{$id_prodi}}">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ModalLabel">Tambah {{$title2}}</h5>
                                                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama" class="form-label">Nama</label>
                                                        <input type="text" name="nama" id="nama" class="form-control" placeholder="cth : SK Pendirian">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nama_gel" class="form-label">Upload File <small>*Abaikan Jika Menggunakan URL</small></label>
                                                        <input type="file" name="file" id="file" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="lat" class="form-label">URL File <small>*Abaikan Jika Menggunakan upload file</small></label>
                                                        <input type="text" name="url" id="url" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tahun" class="form-label">Tahun</label>
                                                        <input type="number" name="tahun" id="tahun" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                                    <button class="btn btn-primary" type="submit">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>File</th>
                                        <th>URL</th>
                                        <th>Tahun</th>
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
            const page = '/'.concat("admin/masterdata/prodi/renstra");
            $("#prodi").change(function(){
                window.location.href = baseUrl.concat(page,"/").concat($(this).val());
            });


            const title = "{{strtolower($title)}}";

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
                    url: baseUrl.concat(page).concat('/',$("#prodi").val()),
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
                        targets:3,
                        render:function render(data, type, full, meta){
                            return (`<a target='_blank' href="${baseUrl}/assets/file/atribut/${full['file']}" class='btn btn-primary btn-sm'>File</a>`);
                        }
                    },
                    {
                        targets:4,
                        render:function render(data, type, full, meta){
                            return (`<a target='_blank' href='${full['url']}' class="btn btn-primary">URL</a>`)
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
                $('#formAdd').trigger("reset");
            });
            //Edit Record
            $(document).on('click', '#add-record', function () {
                $('#ModalLabel').html('Tambah ' + title);
                $("#id").val('');
                $('#formAdd').trigger("reset");
            });
            $(document).on('click', '.edit-record', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabel').html('Edit ' + title);

                // get data
                $.get(''.concat(baseUrl).concat(page, '/').concat(id, '/edit'), function (data) {
                Object.keys(data).forEach(key => {
                    //console.log(key);
                    if(key != 'file'){
                         $('#' + key)
                            .val(data[key])
                            .trigger('change');
                    }
                });

                });
            });
            //save record
            $('#formAdd').on('submit',function(e){
                e.preventDefault();
                const myFormData = new FormData(document.getElementById('formAdd'));
                $.ajax({
                    data: myFormData,
                    url: ''.concat(baseUrl).concat(page),
                    type: 'POST',
                    processData: false,
                    contentType: false,
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
