@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" href="https://manajemen.ppatq-rf.id/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
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
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{URL::to('admin/admisi/peserta/history')}}" method="get">
                                    <div class="row g-4 mb-4">
                                        <div class="col-12">
                                            <label for="ta">Angkatan</label>
                                            <select name="angkatan" id="ta_awal" class="form-control">
                                                <option value="0">--Semua--</option>
                                                @for($i=date('Y');$i>=(date('Y')-4);$i--)
                                                {{-- <option value="{{$i}}" {{($i == $curr_ta)?"selected":""}}>TA {{$i}} - {{($i+1)}}</option> --}}
                                                <option value="{{$i}}" {{(!empty($url_params['angkatan']) && $url_params['angkatan'] == $i)?"selected":""}}>Angkatan {{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="filter_gelombang">Gelombang Pendaftaran</label>
                                            <select name="gelombang" id="filter_gelombang" class="form-control">
                                                <option value="0">--Semua--</option>
                                                @if(!empty($gelombang))
                                                    @foreach($gelombang as $row)
                                                        <option value="{{$row->id}}" {{($row->id == $id_gelombang)?"selected":""}}>{{$row->nama_gel}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="nopen">No. Pendaftaran</label>
                                            <input type="text" name="nopen" id="nopen" class="form-control" placeholder="NIM | Eg. 062541003" value="{{(!empty($url_params['nopen']))?$url_params['nopen']:''}} ">
                                        </div>
                                        <div class="col-12">
                                            <label for="is_lolos">Lolos</label>
                                            <select name="is_lolos" id="is_lolos" class="form-control">
                                                <option value="0" {{(!empty($url_params['is_lolos']) && $url_params['is_lolos'] == 0)?"selected":""}}>Belum Lolos</option>
                                                <option value="1" {{(!empty($url_params['is_lolos']) && $url_params['is_lolos'] == 1)?"selected":""}}>Lolos</option>
                                            </select>
                                        </div>
                                        <input type="submit" value="Filter" class="btn btn-primary" placeholder="Nama | Eg. Ajeng Vilda Hidayat">
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
                                        <th>Pilihan</th>
                                        <th>Angkatan</th>
                                        <th>TTL</th>
                                        <th>Tgl. Registrasi</th>
                                        <th>Validasi</th>
                                        <th>Registrasi</th>
                                        <th>Lolos</th>
                                        <th>Asal Sekolah</th>
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
    
    <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.3.1/b-3.2.3/b-html5-3.2.3/b-print-3.2.3/r-3.0.4/datatables.min.js"></script>
    <script>
        $(function () {
            const id_gelombang = {{$id_gelombang}};
            const baseUrl = {!! json_encode(url('/')) !!};
            const title = "{{strtolower($title)}}";
            const page = '/'.concat("admin/admisi/peserta/history");
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
                    data: {            // Additional data to send with the request
                        angkatan: '{{$url_params['angkatan'] ?? ''}}',
                        gelombang: '{{$url_params['gelombang'] ?? ''}}',
                        nopen: '{{$url_params['nopen'] ?? ''}}',
                        is_lolos: '{{$url_params['is_lolos'] ?? ''}}'
                    },
                },
                columns: my_data,
                paging: false,
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
                    targets: 9,
                    render: function render(data, type, full, meta) {
                        return '<span>'.concat((full.nopen)?"<button class='btn btn-success btn-xs' style='font-size:9pt'>Sudah Validasi</button>":"<button class='btn btn-danger btn-xs' style='font-size:9pt'>Belum Validasi</button>");
                        
                    }
                    },
                    {
                    searchable: false,
                    orderable: false,
                    targets: 10,
                    render: function render(data, type, full, meta) {
                        return (full.is_bayar)?"<button class='btn btn-success btn-xs' style='font-size:9pt; margin:2px 0;'>Sudah Bayar</button>":"<button class='btn btn-danger btn-xs' style='font-size:9pt;  margin:2px 0;' title='Belum bayar registrasi uang masuk'>Belum Bayar</button>";
                    }
                    },
                    {
                    searchable: false,
                    orderable: false,
                    targets: 11,
                    render: function render(data, type, full, meta) {
                        return ((full.is_lolos)?"<button class='btn btn-success btn-xs' style='font-size:9pt'>Sudah Lolos</button>":"<button class='btn btn-danger btn-xs' style='font-size:9pt'>Belum Lolos</button>").concat('</span>');
                    }
                    },
                    {
                    searchable: false,
                    orderable: false,
                    targets: 5,
                    render: function render(data, type, full, meta) {
                        return `${full.pilihan1}-${full.pilihan2}`;
                    }
                    },
                    {
                    searchable: false,
                    orderable: false,
                    targets: 6,
                    render: function render(data, type, full, meta) {
                        return `${full.angkatan}`;
                    }
                    },
                    {
                    // Actions
                    targets: -1,
                    title: 'Asal Sekolah',
                    searchable: false,
                    orderable: false,
                    render: function render(data, type, full, meta) {
                        return '<span>'.concat(full.asal_sekolah, '</span>');
                    }
                    }
                ],
                order: [[3, 'desc']],
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
                buttons: [
                    {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle mx-3',
                    text: '<i class="mdi mdi-export-variant me-sm-1"></i>Export',
                    buttons: [
                        {
                        extend: 'print',
                        title: title,
                        text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
                        className: 'dropdown-item',
                        },
                        {
                        extend: 'csv',
                        title: title,
                        text: '<i class="mdi mdi-file-document-outline me-1" ></i>Csv',
                        className: 'dropdown-item',
                        },
                        {
                        extend: 'excel',
                        title: title,
                        text: '<i class="mdi mdi-file-excel-outline me-1" ></i>Excel',
                        className: 'dropdown-item',
                        
                        },
                        {
                        extend: 'pdf',
                        title: title,
                        text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
                        className: 'dropdown-item',
                        
                        },
                        {
                        extend: 'copy',
                        title: title,
                        text: '<i class="mdi mdi-content-copy me-1" ></i>Copy',
                        className: 'dropdown-item',
                        
                        }
                    ]
                    },
                    
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
                    url: ''.concat(baseUrl).concat('/admin/admisi/peserta/').concat(id),
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
            // $("#filter_gelombang").on('change',function(){
            //     const id = $(this).val();
            //     window.location.href = "{{URL::to('admin/admisi/peserta/gelombang')}}/"+id;
            // });
        });

    </script>
@endsection
