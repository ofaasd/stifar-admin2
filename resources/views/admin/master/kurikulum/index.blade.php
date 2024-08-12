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
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="masterMK-tab" href="{{ url('admin/masterdata/matakuliah') }}" role="tab" aria-controls="masterMK" aria-selected="true">Master Matakuliah</a></li>
                            {{-- <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="kelMK-tab" href="{{ url('admin/masterdata/kelompok-mk') }}" role="tab" aria-controls="kelMK" aria-selected="true">Kelompok Matakuliah</a></li> --}}
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="masterKur-tabs" data-bs-toggle="tab" href="#masterKur" role="tab" aria-controls="masterKur" aria-selected="false" tabindex="-1">Master Kurikulum</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="MkKur-tab" href="{{ url('admin/masterdata/matakuliah-kurikulum') }}" role="tab" aria-controls="MkKur" aria-selected="false" tabindex="-1">Matakuliah Kurikulum</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="masterKur" role="tabpanel" aria-labelledby="masterKur-tab">
                                <div class="mt-4">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal">+ {{$title}}</button>
                                    <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="javascript:void(0)" id="formAdd">
                                                    @csrf
                                                    <input type="hidden" name="id" id="id">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ModalLabel">Tambah {{$title}}</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="kode_kurikulum" class="form-label">Kode Kurikulum</label>
                                                            <input type="text" name="kode_kurikulum" id="kode_kurikulum" class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="progdi" class="form-label">Kode Program Studi</label>
                                                                <select name="progdi" id="progdi" class="form-control" required="">
                                                                    <option selected disabled>Pilih Kode Program Studi</option>
                                                                    @foreach($data_prodi as $dp)
                                                                        <option value="{{ $dp['kode_prodi'] }}">{{ $dp['nama_prodi'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="thn_ajar" class="form-label">Tahun Ajaran</label>
                                                            <select name="thn_ajar" id="thn_ajar" class="form-control" required="">
                                                                    <option selected disabled>Pilih Tahun Ajaran </option>
                                                                    @foreach($data_ta as $da)
                                                                        <option value="{{ $da['id'] }}">{{ $da['kode_ta'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="angkatan" class="form-label">Mulai Angkatan</label>
                                                                    <input type="number" name="angkatan" id="angkatan" class="form-control" placeholder="Mulai dari Angkatan">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="angkatan" class="form-label">Sampai Angkatan</label>
                                                                    <input type="number" name="angkatan_akhir" id="angkatan_akhir" class="form-control" placeholder="Sampai Angkatan">
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="status" class="form-label">Status Kurikulum</label>
                                                            <select name="status" id="status" class="form-control">
                                                                    <option value="Aktif">Aktif</option>
                                                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input class="btn btn-primary" type="submit" value="simpan" />
                                                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
                                    <div class="table-responsive mt-4">
                                        <table class="display" id="basic-1">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>ID</th>
                                                    <th>Kode Kurikulum</th>
                                                    <th>Kode Prodi</th>
                                                    <th>Tahun Ajaran</th>
                                                    <th>Angkatan</th>
                                                    <th>Status</th>
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
            const page = '/'.concat("admin/masterdata/").concat(title);
            var my_column = $('#my_column').val();
            const pecah = my_column.split('\n');
            let my_data = [];
            pecah.forEach((item, index) => {
                let temp = item.replace(/ /g, '');
                let data_obj = { data: temp };
                // alert(data_obj.data);
                my_data.push(data_obj);
            });
            //alert(data_obj);
            // console.log(my_data);

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
                        targets: 5,
                        render: function render(data, type, full, meta) {
                            return '<span>'.concat(full.angkatan).concat(' - ',full.angkatan_akhir).concat('</span>');
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
