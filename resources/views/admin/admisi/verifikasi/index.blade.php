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
    <li class="breadcrumb-item active">Peserta</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                <form action="{{URL::to('admin/admisi/peserta')}}" method="get">
                                    <div class="row">
                                        <div class="col-4">
                                            <select name="ta_awal" id="ta_awal" class="form-control">
                                                @for($i=$ta_min;$i<=$ta_max;$i++)
                                                <option value="{{$i}}" {{($i == $curr_ta)?"selected":""}}>TA {{$i}} - {{($i+1)}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-8">
                                            <select name="filter_gelombang" id="filter_gelombang" class="form-control">
                                                @foreach($gelombang as $row)
                                                    <option value="{{$row->id}}" {{($row->id == $id_gelombang)?"selected":""}}>{{$row->nama_gel}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
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
                                        <th>Nama.</th>
                                        <th>No. Pendaftaran</th>
                                        <th>Gelombang</th>
                                        <th>Pilihan1</th>
                                        <th>Pilihan2</th>
                                        <th>TTL</th>
                                        <th>Verifikasi</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="javascript:void(0)" id="formAdd">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Update Status</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nopen" class="form-label">Nomor Pendaftaran</label>
                                                <input type="text" name="nopen" id="nopen" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label for="is_verifikasi" class="form-label">Verifikasi Data</label>
                                                <select name="is_verifikasi" id="is_verifikasi" class="form-control">
                                                    <option value="0">Belum Verifikasi</option>
                                                    <option value="1">Verifikasi Diterima</option>
                                                    <option value="2">Verifikasi Ditolak</option>
                                                </select>
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
            const id_gelombang = {{$id_gelombang}};
            const baseUrl = {!! json_encode(url('/')) !!};
            const title = "{{strtolower($title)}}";
            const page = '/'.concat("admin/admisi/").concat(title).concat('/gelombang/',id_gelombang);
            const page_edel = '/'.concat("admin/admisi/").concat(title);
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
                        searchable: false,
                        orderable: false,
                        targets: 8,
                        render: function render(data, type, full, meta) {
                            if(full['is_verifikasi'] == "0"){
                                return '<div class="col-md-12 text-center"><span class="text-center"><i class="fa fa-minus-circle fa-lg"></i></span></div>';
                            }else if(full['is_verifikasi'] == "1"){
                                return '<div class="col-md-12 text-center"><span class="text-success text-center"><i class="fa fa-check-circle fa-lg"></i></span></div>';
                            }else{
                                return '<div class="col-md-12 text-center"><span class="text-danger text-center"><i class="fa fa-times-circle fa-lg"></i></span></div>';
                            }
                            //return '<span>asdasdasd</span>';
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
                            '<button class="btn btn-sm edit-record btn-primary" data-id="'
                            .concat(full['id'], '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal"')
                            .concat(title, '"><i class="fa fa-pencil"></i></button></div>')
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
                buttons : [
                    {
                        text:
                            '<i class="mdi mdi-plus me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add New ' +
                            title +
                            '</span>',
                        className: 'add-new btn btn-primary',
                        attr: {
                            'data-bs-toggle': 'offcanvas',
                            'data-bs-target': '#offcanvasAdd' + title
                        }
                    }
                ]
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
                $.get(''.concat(baseUrl).concat(page_edel, '/').concat(id, '/edit'), function (data) {
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
                $("#btn_save").prop('disabled',true);
                $("#btn_save").text('Tunggu Sebentar');
                $.ajax({
                    data: $('#formAdd').serialize(),
                    url: ''.concat(baseUrl).concat(page_edel),
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
                        $("#btn_save").text('Simpan');
                    },
                    error: function error(err) {
                        swal({
                        title: 'Duplicate Entry!',
                        text: title + ' Not Saved !',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                        });
                        $("#btn_save").prop('disabled',false);
                        $("#btn_save").text('Simpan');
                    }
                });
            });
            $("#ta_awal").on('change',function(){
                const id = $(this).val();
                const url = ''.concat(baseUrl).concat('/admin/admisi/peserta/get_gelombang_ta');
                $.post(url,{"_token": "{{ csrf_token() }}",id:id}, (data) => {
                    $("#filter_gelombang").html('<option value="0">Pilih Gelombang</option>');
                    data.forEach(function(value) {
                        $("#filter_gelombang").append(`<option value="${value.id}">${value.nama_gel}</option>`);
                    });
                }, "json");
            });
            $("#filter_gelombang").on('change',function(){
                const id = $(this).val();
                window.location.href = "{{URL::to('admin/admisi/verifikasi/gelombang')}}/"+id;
            });
        });

    </script>
@endsection
