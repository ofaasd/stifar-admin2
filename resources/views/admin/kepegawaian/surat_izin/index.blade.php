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
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Surat Izin Dosen</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal">+ {{$title2}}</button>
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
                                            <div class="mb-3">
                                                <label for="id_pegawai" class="form-label">Dosen</label>
                                                <select  name="id_pegawai" id="id_pegawai" class="form-control">
                                                    @foreach($pegawai as $row)
                                                        <option value="{{$row->id}}">{{$row->nama_lengkap}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tgl_surat" class="form-label">Tanggal Surat</label>
                                                <input type="date" name="tgl_surat" id="tgl_surat" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label for="perihal" class="form-label">Perihal</label>
                                                <input type="text" name="perihal" id="perihal" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label for="keterangan" class="form-label">Keterangan</label>
                                                <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="izin_mgr_sdm" class="form-label">Izin Manager SDM</label>
                                                <div class="form-check radio radio-primary">
                                                    <input class="form-check-input" id="izin_mgr_sdm0" type="radio" name="izin_mgr_sdm" value="1">
                                                    <label class="form-check-label" for="izin_mgr_sdm0">Disetujui</label>
                                                </div>
                                                <div class="form-check radio radio-primary">
                                                    <input class="form-check-input" id="izin_mgr_sdm1" type="radio" name="izin_mgr_sdm" value="0">
                                                    <label class="form-check-label" for="izin_mgr_sdm1">Tidak Disetujui</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="izin_ka_jenjang" class="form-label">Izin KA Jenjang</label>
                                                <div class="form-check radio radio-primary">
                                                    <input class="form-check-input" id="izin_ka_jenjang0" type="radio" name="izin_ka_jenjang" value="1">
                                                    <label class="form-check-label" for="izin_ka_jenjang0">Disetujui</label>
                                                </div>
                                                <div class="form-check radio radio-primary">
                                                    <input class="form-check-input" id="izin_ka_jenjang1" type="radio" name="izin_ka_jenjang" value="0">
                                                    <label class="form-check-label" for="izin_ka_jenjang1">Tidak Disetujui</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="id_kategori" class="form-label">Kategori</label>
                                                <select  name="id_kategori" id="id_kategori" class="form-control">
                                                    @foreach($kategori as $row)
                                                        <option value="{{$row->id}}">{{$row->nama}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dokumen" class="form-label">Dokumen</label>
                                                <input type="file" name="dokumen" id="dokumen" class="form-control">
                                                <div id="dokumen_exist">

                                                </div>
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
                    <div class="card-body">
                        <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Dosen</th>
                                        <th>Tanggal Surat</th>
                                        <th>Perihal</th>
                                        <th>Keterangan</th>
                                        <th>Kategori</th>
                                        <th>Izin Mgr SDM</th>
                                        <th>Izin KA Jenjang</th>
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
            const page = '/'.concat("admin/kepegawaian/").concat(title);
            var my_column = $('#my_column').val();
            const pecah = my_column.split('\n');
            let my_data = [];
            pecah.forEach((item, index) => {
                let temp = item.replace(/ /g, '');
                let data_obj = { data: temp };
                //alert(data_obj.data);
                my_data.push(data_obj);
            });

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
                $("#id").val("");
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
                    if(key == 'dokumen'){

                        if(data[key]){
                            const url = baseUrl.concat('/assets/file/surat_izin/',data[key]);
                            $('#dokumen_exist')
                            .html(`<a href="${url}" target="_blank" class="btn btn-info" style="margin-top:20px">Lihat File</a>`);
                        }else{
                            $('#dokumen_exist')
                            .html('');
                        }
                    }else if(key == 'izin_ka_jenjang'){
                        if(data[key] == "1"){
                            $("#izin_ka_jenjang0").attr('checked',true);
                        }else{
                            $("#izin_ka_jenjang1").attr('checked',true);
                        }
                    }else if(key == 'izin_mgr_sdm'){
                        if(data[key] == "1"){
                            $("#izin_mgr_sdm0").attr('checked',true);
                        }else{
                            $("#izin_mgr_sdm1").attr('checked',true);
                        }
                    }else{
                        $('#' + key)
                            .val(data[key])
                            .trigger('change');
                    }
                });
                });
            });
            //save record
            $('#formAdd').on('submit',function(e){
                const myFormData = new FormData(document.getElementById("formAdd"));
                e.preventDefault();
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
                        $("#tambahModal").modal('hide');
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
