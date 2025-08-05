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
    <li class="breadcrumb-item active">Alumni</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal">+ {{$title}}</button>
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
                                            <div class="col-md-4 mb-3">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" name="nama" id="nama" class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label for="nim" class="form-label">NIM</label>
                                                    <input type="text" name="nim" id="nim" class="form-control">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="jenjang" class="form-label">Jenjang</label>
                                                    <select name="jenjang" id="jenjang" class="form-control">
                                                        <option value="">Pilih Jenjang</option>
                                                        <option value="D3">D3</option>
                                                        <option value="S1">S1</option>
                                                        <option value="S2">S2</option>
                                                        <option value="S3">S3</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="angkatan" class="form-label">Angkatan</label>
                                                    <input type="number" name="angkatan" id="angkatan" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-md-4">
                                                    <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                                                    <input type="number" name="tahun_lulus" id="tahun_lulus" class="form-control">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                                        <option value="">Pilih Jenis Kelamin</option>
                                                        <option value="1">Laki-laki</option>
                                                        <option value="2">Perempuan</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label for="no_hp" class="form-label">No HP</label>
                                                    <input type="number" name="no_hp" id="no_hp" class="form-control">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email_pribadi" class="form-label">Email Pribadi</label>
                                                <input type="email" name="email_pribadi" id="email_pribadi" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label for="prodi" class="form-label">Prodi</label>
                                                <select name="prodi" id="prodi" class="form-control">
                                                    <option value="">Pilih Prodi</option>
                                                    @foreach($prodi as $value)
                                                        <option value='{{$value->id}}'>{{$value->nama_prodi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="judul_skripsi" class="form-label">Judul Skripsi</label>
                                                <input type="text" name="judul_skripsi" id="judul_skripsi" class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col waktuAwalKerja">
                                                    <label for="waktu_awal_kerja" class="form-label">Waktu Awal Kerja</label>
                                                    <input type="date" name="waktu_awal_kerja" id="waktu_awal_kerja" class="form-control">
                                                    <p id="text-awal-kerja"></p>
                                                </div>
                                                <div class="mb-3 col">
                                                    <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                                                    <input type="date" name="waktu_mulai" id="waktu_mulai" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label for="status_pekerjaan" class="form-label">Status Pekerjaan</label>
                                                    <select name="status_pekerjaan" id="status_pekerjaan" class="form-control">
                                                        <option value="">Pilih Status Pekerjaan</option>
                                                        <option value="1">Lanjut Studi</option>
                                                        <option value="2">Wirausaha</option>
                                                        <option value="3">Fulltime/Part Time</option>
                                                        <option value="4">Tidak Kerja & Sedang Mencari Kerja</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="posisi" class="form-label">Posisi</label>
                                                    <select name="posisi" id="posisi" class="form-control">
                                                        <option value="">Pilih Posisi</option>
                                                        <option value="1">S2</option>
                                                        <option value="2">Direktur Utama</option>
                                                        <option value="3">Wakil Direktur</option>
                                                        <option value="4">Manajer Umum</option>
                                                        <option value="5">Kepala Divisi</option>
                                                        <option value="6">Kepala Bagian</option>
                                                        <option value="7">Supervisor</option>
                                                        <option value="8">Koordinator</option>
                                                        <option value="9">Team Leader</option>
                                                        <option value="10">Staf Administrasi</option>
                                                        <option value="11">Staf Keuangan</option>
                                                        <option value="12">Staf SDM</option>
                                                        <option value="13">Staf Operasional</option>
                                                        <option value="14">Staf Gudang</option>
                                                        <option value="15">Staf IT</option>
                                                        <option value="16">Customer Service</option>
                                                        <option value="17">Marketing</option>
                                                        <option value="18">Sales</option>
                                                        <option value="19">Office Boy / Girl</option>
                                                        <option value="20">Sekretaris</option>
                                                        <option value="21">Resepsionis</option>
                                                        <option value="22">Driver</option>
                                                        <option value="23">Satpam</option>
                                                        <option value="24">Cleaning Service</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tempat_pekerjaan" class="form-label">Tempat Pekerjaan</label>
                                                <input type="text" name="tempat_pekerjaan" id="tempat_pekerjaan" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" type="submit" id="btn-submit">Simpan</button>
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
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Jenjang</th>
                                        <th>Angkatan</th>
                                        <th>Tahun Lulus</th>
                                        <th>Prodi</th>
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
            const page = '/'.concat("admin/").concat(title);
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
                $('#waktu_awal_kerja').attr('type', 'date').val('');
                $('#text-awal-kerja').text('');
                $('#formAdd').trigger("reset");
                $('#id').val('');
            });
            //Edit Record
            $(document).on('click', '.edit-record', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabel').html('Edit ' + title);

                // get data
                $.get(''.concat(baseUrl).concat(page, '/').concat(id, '/edit'), function (data) {
                    Object.keys(data).forEach(key => {
                        
                        if(key == 'teksWaktuAwalKerja' && data[key] != null){
                            $('#waktu_awal_kerja').attr('type', 'hidden');
                            $('#text-awal-kerja').text(data[key]);
                        } else if (key == 'waktu_mulai' && data[key] != null) {
                            let date = new Date(data[key]);
                            let yyyy = date.getFullYear();
                            let mm = String(date.getMonth() + 1).padStart(2, '0');
                            let dd = String(date.getDate()).padStart(2, '0');
                            let formattedDate = `${yyyy}-${mm}-${dd}`;
                            $('#' + key)
                                .val(formattedDate)
                                .trigger('change');
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
                e.preventDefault();
                var btnSubmit = $('#btn-submit');
                var oldBtnHtml = btnSubmit.html(); // Simpan nilai sebelum diubah ke loading
                btnSubmit.prop('disabled', true); 
                btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                
                $.ajax({
                    data: $('#formAdd').serialize(),
                    url: ''.concat(baseUrl).concat(page),
                    type: 'POST',
                    success: function success(response) {
                        dt.draw();
                        $("#tambahModal").modal('hide');

                        // sweetalert
                        swal({
                            icon: 'success',
                            title: 'Successfully '.concat(response.message, '!'),
                            text: ''.concat(title, ' ').concat(response.message, ' Successfully.'),
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        btnSubmit.prop('disabled', false); // Aktifkan kembali tombol
                        btnSubmit.html(oldBtnHtml); // Kembalikan teks tombol ke semula
                    },
                    error: function error(err) {
                        swal({
                            title: err.responseText || 'Duplicate Entry.',
                            text: title + ' Not Saved !',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        btnSubmit.prop('disabled', false); // Aktifkan kembali tombol
                        btnSubmit.html(oldBtnHtml); // Kembalikan teks tombol ke semula
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
