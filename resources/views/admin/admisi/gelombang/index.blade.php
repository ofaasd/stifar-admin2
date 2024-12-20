@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/simple-mde.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item active">Gelombang Pendaftaran</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal" id="add-record">+ {{$title}}</button>
                        <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form action="javascript:void(0)" id="formAdd">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Tambah {{$title}}</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="no_gel" class="form-label">Nomor Gelombang</label>
                                                        <input type="text" name="no_gel" id="no_gel" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nama_gel" class="form-label">Nama Gelombang</label>
                                                        <input type="text" name="nama_gel" id="nama_gel" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="no_gel" class="form-label">Tahun Ajaran</label>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input type="number" name="ta_awal" id="ta_awal" class="form-control" value='{{date('Y')}}'>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="number" name="ta_akhir" id="ta_akhir" class="form-control" value='{{(date('Y')+1)}}'>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="id_jalur" class="form-label">Jalur Pendaftaran</label>
                                                        <select name="id_jalur" id="id_jalur" class="form-control">
                                                        @foreach($jalur as $row)
                                                            <option value="{{$row->id}}">{{$row->nama}}</option>
                                                        @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                                                        <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tgl_akhir" class="form-label">Tanggal Akhir</label>
                                                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="ujian" class="form-label">Tanggal Ujian</label>
                                                        <input type="date" name="ujian" id="ujian" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="nama_gel_long" class="form-label">Keterangan</label>
                                                        <textarea name="nama_gel_long" id="nama_gel_long editable" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" type="submit" id="btn_save">Simpan</button>
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
                                        <th>No. Gel.</th>
                                        <th>Nama Gelombang</th>
                                        <th>Keterangan</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Jalur</th>
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
    <script src="{{asset('assets/js/editor/simple-mde/simplemde.min.js')}}"></script>

    <script>
        $(function () {
            id = "editable";
            var simplemde = new SimpleMDE({
                element: $("textarea#" + id)[0],
                autosave: {
                    enabled: true,
                    uniqueId: "name_gelombang_long_text",
                }
            });
            toolbarInitialTop = $('.editor-toolbar').offset().top;
            toolbarOuterHeight = $('.editor-toolbar').outerHeight();
            toolbarFixedTop = 0;
            cmPaperTop = toolbarFixedTop + toolbarOuterHeight;
            toolbarAffixAt = toolbarInitialTop - toolbarFixedTop;
            $(document).scroll(fnAffix);
            $(document).resize(fnSetWidth);
            function fnAffix() {
                // Fix toolbar at set distance from top
                // and adjust toolbar width
                if ($(document).scrollTop() > toolbarAffixAt) {
                    $('.editor-toolbar').addClass('toolbar-fixed');
                    $('.editor-toolbar').css({'top': toolbarFixedTop + 'px'});
                    $('.CodeMirror.cm-s-paper.CodeMirror-wrap').css({'top': cmPaperTop + 'px'});
                    fnSetWidth();
                }
                else {
                    $('.editor-toolbar').removeClass('toolbar-fixed');
                    $('.CodeMirror.cm-s-paper.CodeMirror-wrap').css({'top': ''});
                }
            }
            function fnSetWidth() {
                // Adjust fixed toolbar width to the width of CodeMirror
                $('.toolbar-fixed').width($('.CodeMirror.cm-s-paper.CodeMirror-wrap').width());
            }

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
                        '<div class="btn-group">' +
                        '<button class="btn btn-sm btn-primary edit-record" data-id="'
                            .concat(full['id'], '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal"')
                            .concat(title, '"><i class="fa fa-pencil"></i></button>') +
                        '<button class="btn btn-sm btn-danger delete-record" data-id="'.concat(
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
                $("#nama_gel_long").val('').trigger('change');
                simplemde.value('');
                $(".CodeMirror-code").html('');
                $("#id").val('');
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
                    if(key == "nama_gel_long"){
                        simplemde.value(data[key]);
                    }
                });
                });
            });
            //save record
            $('#formAdd').on('submit',function(e){
                e.preventDefault();
                $("#btn_save").prop('disabled',true);
                $("#btn_save").val('Tunggu Sebentar');
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
                        $("#btn_save").prop('disabled',false);
                        $("#btn_save").val('Simpan');
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
                        $("#btn_save").prop('disabled',false);
                        $("#btn_save").val('Simpan');
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
