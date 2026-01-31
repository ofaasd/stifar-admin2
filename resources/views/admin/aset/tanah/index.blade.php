@extends('layouts.master')
@section('title', 'Aset Tanah')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title2}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Aset</li>
    <li class="breadcrumb-item active">Tanah</li>
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
                    </div>
                    <div class="card-body">
                        <h5>Statistik Tanah</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Status Tanah</h6>
                                        <ul>
                                            @foreach($statsJenisTanah as $status => $jumlah)
                                                <li>{{ $status }}: {{ $jumlah }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Kategori Luas Tanah</h6>
                                        <ul>
                                            @foreach($statsLuasTanah as $kategori => $jumlah)
                                                <li>{{ $kategori }}: {{ $jumlah }}</li>
                                            @endforeach
                                        </ul>
                                        <p>Total Luas Tanah: {{ $totalLuas }} m²</p>
                                    </div>
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
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Luas m<sup>2</sup></th>
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
                        <div class="row">
                            <div class="col-md-6 mb-2" id="field-kode">
                                <label for="kode" class="form-label">Kode</label>
                                <input type="text" class="form-control" name="kode" id="kode" placeholder="TL">
                            </div>
                            <div class="col-md-6 mb-2" id="field-nama">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Tanah Lot">
                            </div>
                        </div>

                        <div class="mb-2" id="field-alamat">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Jl.jalan">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="luas" class="form-label">Luas</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="luas" id="luas" placeholder="12.4">
                                    <span class="input-group-text">m<sup>2</sup></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2" id="field-tanggal_perolehan">
                                <label for="tanggal_perolehan" class="form-label">Tanggal Perolehan</label>
                                <input type="date" class="form-control" name="tanggalPerolehan" id="tanggal_perolehan">
                            </div>
                        </div>

                        <div class="mb-2" id="field-no_sertifikat">
                            <label for="no_sertifikat" class="form-label">No Sertifikat</label>
                            <input type="text" class="form-control" name="noSertifikat" id="no_sertifikat" placeholder="120482958">
                        </div>
                        <div class="mb-2" id="field-status_tanah">
                            <label for="status_tanah">Status Tanah</label>
                            <select name="statusTanah" class="form-control" id="status_tanah">
                                <option value="sewa">SEWA</option>
                                <option value="aktif">AKTIF</option>
                                <option value="dijual">DIJUAL</option>
                            </select>
                        </div>
                        <div class="mb-2" id="field-keterangan">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea type="text" class="form-control" name="keterangan" id="keterangan"></textarea>
                        </div>
                        <div class="mb-2" id="field-bukti_fisik">
                            <label for="bukti_fisik" class="form-label">Bukti Fisik</label>
                            <input type="file" class="form-control" name="buktiFisik" id="bukti_fisik">
                            <a href="#" id="edit-view-bukti_fisik"><i class="fa fa-file text-dark fs-3 mt-3"></i></a> <span id="edit-view-text-bukti_fisik">bukti fisik</span>
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

    <div class="modal fade bd-example-modal-lg" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Detail <span id="view-judul-nama"></span></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <p>Kode : <span id='view-kode'></span></p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <p>Nama : <span id='view-nama'></span></p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                            <p>Alamat : <span id='view-alamat'></span></p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                            <p>Luas : <span id='view-luas'></span> m<sup>2</sup></p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                            <p> Tanggal Perolehan : <span id='view-tanggal_perolehan'></span></p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                            <p>No Sertifikat : <span id='view-no_sertifikat'></span></p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <p>Status Tanah : <span id='view-status_tanah' class="text-uppercase"></span></p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                            <p>Keterangan : <span id='view-keterangan'></span></p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                            <a class="btn btn-sm btn-primary" id='view-bukti_fisik' href="" target="_blank">Lihat Bukti</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            const title2 = "{{ $title2 }}";
            const page = '/'.concat("admin/aset/").concat(title);
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
                        return '<span>'.concat(full['fake_id'], '</span>');
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
                                '<button class="btn btn-sm btn-icon view-record text-primary" data-id="'
                                    .concat(full['id'], '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#viewModal"')
                                    .concat(title, '"><i class="fa fa-eye"></i></button>') +
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
                $('#ModalLabel').html('Tambah ' + title2);
                $('#formAdd').trigger("reset");

                $('#edit-view-bukti_fisik').addClass('d-none');
                $('#edit-view-text-bukti_fisik').addClass('d-none');
            });
            $(document).on('click', '.edit-record', function () {
                const id = $(this).data('id');

                $('#edit-view-bukti_fisik').removeClass('d-none');
                $('#edit-view-text-bukti_fisik').removeClass('d-none');

                $('#formAdd').trigger("reset");

                $('#edit-view-bukti_fisik').attr('href', '#');

                // changing the title of offcanvas
                $('#ModalLabel').html('Edit ' + title2);

                // get data
                $.get(''.concat(baseUrl).concat(page, '/').concat(id, '/edit'), function (data) {
                    Object.keys(data).forEach(key => {
                        if(key == 'tanggal_perolehan'){
                            var dateOnly = data[key].split(' ')[0];
                            $('#' + key)
                                .val(dateOnly)
                                .trigger('change');
                        }else if(key == 'bukti_fisik'){
                            var href = baseUrl + '/assets/images/aset/tanah/' + data[key]
                            $('#edit-view-'+ key)
                                .attr('href', href)
                                .attr('target', '_blank');
                        }else{
                            $('#' + key)
                                .val(data[key])
                                .trigger('change');
                        }
                    });
                });

            });

            $(document).on('click', '.view-record', function () {
                const id = $(this).data('id');

                $('#formAdd').trigger("reset");

                // changing the title of offcanvas
                $('#ModalLabel').html('Edit ' + title2);

                // get data
                $.get(''.concat(baseUrl).concat(page, '/').concat(id), function (data) {
                    Object.keys(data).forEach(key => {
                        if(key == 'bukti_fisik'){
                            var href = baseUrl + '/assets/images/aset/tanah/' + data[key]
                            $('#view-'+ key)
                                .attr('href', href)
                                .attr('target', '_blank');
                        }else if(key == 'nama'){
                            $('#view-judul-nama').text(data[key]);
                            $('#view-' + key)
                                .text(data[key]);
                        }else{
                            $('#view-' + key)
                                .text(data[key]);
                        }
                    });
                });

            });

            //save record
            $('#formAdd').on('submit',function(e){
                e.preventDefault();
                var btnSubmit = $('#btn-submit');
                btnSubmit.prop('disabled', true); 
                const myFormData = new FormData(document.getElementById('formAdd'));
                const offCanvasForm = $('#formAdd');
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
                        btnSubmit.prop('disabled', false);
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
                        btnSubmit.prop('disabled', false);
                    }
                });
            });

            //delete record
            $(document).on('click', '.delete-record', function () {
                const id = $(this).data('id');
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
                                // console.log(_error);
                            }
                        });

                        swal({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The Record has been deleted!',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        // location.reload()
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
