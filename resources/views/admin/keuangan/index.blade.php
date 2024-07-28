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
    <li class="breadcrumb-item">Mahasiswa</li>
    <li class="breadcrumb-item active">{{$title2}}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        Keuangan Mahasiswa
                    </div>
                    <div class="card-body">
                        <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
                        @if($jumlah_keuangan < $jumlah_mhs)
                        <div class="alert alert-warning">Data Keuangan Mahasiswa TA {{$ta->kode_ta}} Belum tersedia / Terdapat tambahan mahasiswa baru. klik tombol di bawah untuk generate keuangan mahasiswa</div>
                        <a href="{{URL::to('admin/keuangan/generate_mhs')}}" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            Generate keuangan Mahasiswa
                          </a>
                        @endif
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>No.</th>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Tahun AJaran</th>
                                        <th>KRS</th>
                                        <th>UTS</th>
                                        <th>UAS</th>
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
                                            <h5 class="modal-title" id="ModalLabelkarya">Update Keuangan</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="mb-3">
                                                <label for="judul" class="form-label">NIM</label>
                                                <input type="text" name="nim" id="nim" class="form-control" placeholder="nim" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" name="nama" id="nama" class="form-control" placeholder="nama" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Tahun Ajaran</label>
                                                <input type="text" name="ta" id="ta" class="form-control" placeholder="Tagun Ajaran" readonly>
                                            </div>
                                            <input type="hidden" name="id_mahasiswa" id="id_mahasiswa">
                                            <input type="hidden" name="id_tahun_ajaran" id="id_tahun_ajaran">
                                            <div class="mb-3">
                                                <label for="label_krs" class="form-label">KRS</label>
                                                <label class="d-block" for="krs">
                                                    <input name="krs" class="checkbox_animated" id="krs" type="checkbox" value="1"> Izinkan / Tidak Izinkan
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <label for="label_uts" class="form-label">UTS</label>
                                                <label class="d-block" for="uts">
                                                    <input name="uts" class="checkbox_animated" id="uts" type="checkbox" value="1"> Izinkan / Tidak Izinkan
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <label for="label_uas" class="form-label">UAS</label>
                                                <label class="d-block" for="uas">
                                                    <input name="uas" class="checkbox_animated" id="uas" type="checkbox" value="1"> Izinkan / Tidak Izinkan
                                                </label>
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
            const page = '/'.concat("admin/").concat(title);
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
                        targets: 5,
                        searchable: false,
                        orderable: false,
                        render: function render(data, type, full, meta) {
                            if(full['krs'] == 1){
                                return('<i class="fa fa-check"></i>');
                            }else{
                                return('<i class="fa fa-times"></i>');
                            }
                        }
                    },
                    {
                        // Actions
                        targets: 6,
                        searchable: false,
                        orderable: false,
                        render: function render(data, type, full, meta) {
                            if(full['uts'] == 1){
                                return('<i class="fa fa-check"></i>');
                            }else{
                                return('<i class="fa fa-times"></i>');
                            }
                        }
                    },
                    {
                        // Actions
                        targets: 7,
                        searchable: false,
                        orderable: false,
                        render: function render(data, type, full, meta) {
                            if(full['uas'] == 1){
                                return('<i class="fa fa-check"></i>');
                            }else{
                                return('<i class="fa fa-times"></i>');
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
                        '<div class="d-inline-block text-nowrap">' +
                        '<button class="btn btn-sm btn-icon edit-record text-primary" data-id="'
                            .concat(full['id'], '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal"')
                            .concat(title, '"><i class="fa fa-pencil"></i></button>')
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
                    if(key == "krs" || key == "uts" || key == "uas"){
                        if(data[key] == 1){
                            $("#"+key).prop('checked',true);
                        }else{
                            $("#"+key).prop('checked',false);
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
                let krs_value = 0;
                if($('#krs').prop('checked')){
                    krs_value = 1;
                }
                let uts_value = 0;
                if($('#uts').prop('checked')){
                    uts_value = 1;
                }
                let uas_value = 0;
                if($('#uas').prop('checked')){
                    uas_value = 1;
                }

                myFormData.append('krs_value',krs_value)
                myFormData.append('uts_value',uts_value)
                myFormData.append('uas_value',uas_value)
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
        });

    </script>
@endsection
