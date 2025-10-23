@extends('layouts.master')
@section('title', 'Gedung')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title2}} Bulan {{$list_bulan[$bulan]}} Tahun {{$tahun}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Keuangan</li>
    <li class="breadcrumb-item active">Pembayaran</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        @if(empty($link))
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal" id="add-record">+ Add Pembayaran</button>
                        <button class="btn btn-warning" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#filterModal" id="filter-record">Filter Pembayaran</button>
                        <button class="btn btn-info" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#importModal" id="import-record">+ Import Pembayaran</button>
                        
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
                                                <label for="nim" class="form-label">Mahasiswa</label>
                                            </div>
                                            <div class="mb-3" id="fied_column">
                                                <select name="nim" class="select2_mhs" id="nim">
                                                    @foreach($mhs_all as $row)
                                                        <option value="{{$row->nim}}">{{$row->nim}} - {{$row->nama}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="mb-3" id="field-nama">
                                                <label for="jumlah" class="form-label">Jumlah</label>
                                                <input type="number" class="form-control" name="jumlah" id="jumlah">
                                            </div>
                                            <div class="mb-3" id="field-nama">
                                                <label for="keterangan" class="form-label">Keterangan</label>
                                                <input type="text" class="form-control" name="keterangan" id="keterangan">
                                            </div>
                                            <div class="mb-3" id="field-nama">
                                                <label for="tanggl_bayar" class="form-label">Tanggal Bayar</label>
                                                <input type="date" class="form-control" name="tanggal_bayar" id="tanggal_bayar">
                                            </div>
                                            <div class="mb-3" id="field-nama">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-control" name="status" id="status">
                                                    <option value="1" >Berhasil</option>
                                                    <option value="0">Draft</option>
                                                    <option value="2">Ditolak</option>
                                                </select>
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
                        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="" method="get" id="formFilter">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Filter {{$title2}}</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3" id="field-nama">
                                                <label for="nim" class="form-label">Bulan</label>
                                            </div>
                                            <div class="mb-3">
                                                <select name="bulan" class="form-control" id="bulan">
                                                    @foreach($list_bulan as $key=>$value)
                                                        <option value="{{$key}}" {{($bulan == $key)?"selected":""}}>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <div class="mb-3" id="field-nama">
                                                    <label for="nim" class="form-label">Tahun</label>
                                                </div>
                                                <select name="tahun" class="form-control" id="tahun">
                                                    @for($i=date('Y');$i>=(date('Y')-5);$i--)
                                                        <option value="{{$i}}" {{($tahun == $i)?"selected":""}}>{{$i}}</option>
                                                    @endfor
                                                </select>
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
                        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="javascript:void(0)" id="formImport">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Import Pembayaran</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3" id="field-nama">
                                                <label for="file_excel" class="form-label">File Excel</label>
                                                <input type="file" class="form-control" name="file_excel" id="file_excel">
                                            </div>
                                            <a href="{{url('/assets/file/format_import_pembayaran.xlsx')}}" class="btn btn-primary">Format Import</a>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" id="btn-import" type="submit">Simpan</button>
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
                                        <th>Prodi</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>

    <script>
        $(function () {
        $('.select2_mhs').select2({
            dropdownParent: $('#nimModal')
        });
        const baseUrl = {!! json_encode(url('/')) !!};
        const title = "{{strtolower($title)}}";
        const title2 = "{{ $title2 }}";
        const page = '/'.concat("admin/keuangan/").concat(title);
        var my_column = $('#my_column').val();
        const pecah = my_column.split('\n');
        let my_data = [];
        pecah.forEach((item, index) => {
            let temp = item.replace(/ /g, '');
            let data_obj = { data: temp };
            my_data.push(data_obj);
        });
        console.log(my_data);
        const dt = $("#basic-1").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl.concat(page),
                data: {
                    'bulan' : $("#bulan").val(),
                    'tahun' : $("#tahun").val(),
                }
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
                    if(full.status == 0){
                        return '<span>Draft</span>';
                    }else if(full.status == 1){
                        return `<span class="text-success"><i class='fa fa-check'></i></span>`;
                    }else{
                        return `<span class="text-danger"><i class='fa fa-ban'></i></span>`;
                    }
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
                        title: `Successfully Saved!`,
                        text: `Saved successfully.`,
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
                    btnSubmit.prop('disabled', false);
                }
            });
        });
        $('#formImport').on('submit', function (e) {
            e.preventDefault();
            const myFormData = new FormData(this);

            var btnSubmit = $('#btn-import');
            btnSubmit.prop('disabled', true);

            $.ajax({
                data: myFormData,
                url: `${baseUrl}/admin/keuangan/pembayaran/import`,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function (status) {
                    dt.draw();
                    $("#importModal").modal('hide');
                    swal({
                        icon: 'success',
                        title: `Successfully Imported!`,
                        text: `Data successfully Imported.`,
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                    btnSubmit.prop('disabled', false);
                },
                error: function (xhr) {
                    $("#importModal").modal('hide');
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
