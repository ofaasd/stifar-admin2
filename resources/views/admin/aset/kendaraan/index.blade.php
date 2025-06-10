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
    <li class="breadcrumb-item">Aset</li>
    <li class="breadcrumb-item active">Kendaraan</li>
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
                                            <div class="row">
                                                <div class="mb-3 col-6">
                                                    <label for="kode_jenis_kendaraan " class="form-label">Jenis Kendaraan</label>
                                                    <select class="form-select" aria-label="Default select example" id="kode_jenis_kendaraan" name="kodeJenisKendaraan">
                                                        @foreach ($dataJenisKendaraan as $row)
                                                            <option value="{{ $row->kode }}">{{ $row->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="kode_merek_kendaraan " class="form-label">Merk Kendaraan</label>
                                                    <select class="form-select" aria-label="Default select example" id="kode_merek_kendaraan" name="kodeMerkKendaraan">
                                                        @foreach ($dataMerkKendaraan as $row)
                                                            <option value="{{ $row->kode }}">{{ $row->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="id_penanggung_jawab" class="form-label">Penanggung Jawab</label>
                                                    <select class="form-select" aria-label="Default select example" id="id_penanggung_jawab" name="idPenanggungJawab">
                                                        @foreach ($dataPegawai as $row)
                                                            <option value="{{ $row->id }}">{{ $row->nama_lengkap }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="nama" class="form-label">Nama</label>
                                                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Innova Reborn">
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="nomor_polisi" class="form-label">Nomor Polisi</label>
                                                    <input type="text" name="nomorPolisi" id="nomor_polisi" class="form-control" placeholder="H 1234 HH">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tanggal_perolehan" class="form-label">Tanggal Perolehan</label>
                                                    <input type="date" name="tanggalPerolehan" id="tanggal_perolehan" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="harga_perolehan" class="form-label">Harga Perolehan</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="hargaPerolehan" id="harga_perolehan" placeholder="120000000">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="harga_penyusutan" class="form-label">Harga Penyusutan</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="hargaPenyusutan" id="harga_penyusutan" placeholder="35000000">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nomor_rangka" class="form-label">Nomor Rangka</label>
                                                    <input type="text" name="nomorRangka" id="nomor_rangka" class="form-control" placeholder="MHY1234567B567890">
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="bahan_bakar" class="form-label">Bahan Bakar</label>
                                                    <select class="form-select" aria-label="Default select example" id="bahan_bakar" name="bahanBakar">
                                                        <option value="bensin">Bensin</option>
                                                        <option value="solar">Solar</option>
                                                        <option value="listrik">Listrik</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="transmisi" class="form-label">Transmisi</label>
                                                    <select class="form-select" aria-label="Default select example" id="transmisi" name="transmisi">
                                                        <option value="manual">Manual</option>
                                                        <option value="matic">Matic</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="kapasitas_mesin" class="form-label">Kapasitas Mesin</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="kapasitasMesin" id="kapasitas_mesin" placeholder="2400">
                                                        <span class="input-group-text">cc</span>
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="pemeriksaan_terakhir" class="form-label">Pemeriksaan Terakhir</label>
                                                    <input type="date" name="pemeriksaanTerakhir" id="pemeriksaan_terakhir" class="form-control">
                                                </div>
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
                                                    <p>Jenis : <span id='view-jenis'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Merk : <span id='view-merek'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Penanggung Jawab : <span id='view-penanggung_jawab'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Nama : <span id='view-nama_kendaraan'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Nomor Polisi : <span id='view-nomor_polisi'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Tanggal Perolehan : <span id='view-tanggal_perolehan'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Harga Perolehan : <span id='view-harga_perolehan'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Harga Penyusutan : <span id='view-harga_penyusutan'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Nomor Rangka : <span id='view-nomor_rangka'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Bahan Bakar : <span id='view-bahan_bakar'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Transmisi : <span id='view-transmisi'></span></p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <p>Kapasitas Mesin : <span id='view-kapasitas_mesin'></span>cc</p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                <p> Pemeriksaan Terakhir : <span id='view-pemeriksaan_terakhir'></span></p>
                                                </div>
                                            </div>
                                           
                                        </div>
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
                                        <th>Nomor Polisi</th>
                                        <th>Nama</th>
                                        <th>Transmisi</th>
                                        <th>Penanggung Jawab</th>
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
            const title2 = "{{$title2}}";
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
                $('#id').val('');
                $('#formAdd').trigger("reset");
            });
            //Edit Record
            $(document).on('click', '.edit-record', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabel').html('Edit ' + title);
                $('#formAdd').trigger("reset");

                // get data
                $.get(''.concat(baseUrl).concat(page, '/').concat(id, '/edit'), function (data) {
                Object.keys(data).forEach(key => {
                    if(key == 'pemeriksaan_terakhir' || key == 'tanggal_perolehan'){
                        var dateOnly = data[key].split(' ')[0];
                        $('#' + key)
                            .val(dateOnly)
                            .trigger('change');
                    }else{
                        $('#' + key)
                            .val(data[key])
                            .trigger('change');
                    }
                });
                });
            });

            //view record
            $(document).on('click', '.view-record', function () {
                const id = $(this).data('id');

                $('#formAdd').trigger("reset");

                // changing the title of offcanvas
                $('#ModalLabel').html('Edit ' + title2);

                // get data
                $.get(''.concat(baseUrl).concat(page, '/').concat(id), function (data) {
                    Object.keys(data).forEach(key => {
                        if(key == 'nama'){
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
                btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');

                const offCanvasForm = $('#formAdd');
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
                        btnSubmit.prop('disabled', false);
                        btnSubmit.text('Simpan');
                    },
                    error: function error(err) {
                        console.log('====================================');
                        console.log(err);
                        console.log('====================================');
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
                        btnSubmit.text('Simpan');
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
                        // console.log(_error);
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
