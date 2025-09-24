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
    <li class="breadcrumb-item active">Barang</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <button class="btn btn-primary" type="button" id="btn-tambah" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal">+ {{$title2}}</button>
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
                                                    <label for="kode_ruang " class="form-label">Ruang</label>
                                                    <select class="form-select" aria-label="Default select example" id="kode_ruang" name="kodeRuang">
                                                        @foreach ($asetRuang as $row)
                                                            <option value="{{ $row->nama_ruang }}">{{ $row->nama_ruang }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="id_penanggung_jawab" class="form-label">Penanggung Jawab</label>
                                                    <select class="form-select" aria-label="Default select example" id="id_penanggung_jawab" name="idPenanggungJawab">
                                                        @foreach ($dataPegawai as $row)
                                                            <option value="{{ $row->id }}">{{ $row->nama_lengkap }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="kode_jenis_barang" class="form-label">Jenis Barang</label>
                                                    <select class="form-select" aria-label="Default select example" id="kode_jenis_barang" name="kodeJenisBarang">
                                                        @foreach ($dataJenisBarang as $row)
                                                            <option value="{{ $row->kode }}">{{ $row->kode }} - {{ $row->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="kode_vendor" class="form-label">Vendor</label>
                                                    <select class="form-select" aria-label="Default select example" id="kode_vendor" name="kodeVendor">
                                                        @foreach ($dataVendor as $row)
                                                            <option value="{{ $row->kode }}">{{ $row->kode }} - {{ $row->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="spesifikasi" class="form-label">Spesifikasi</label>
                                                    <input type="text" name="spesifikasi" id="spesifikasi" class="form-control" placeholder="kayu, tinggi 2cm">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nama" class="form-label">Nama</label>
                                                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Kursi Kelas">
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="elektronik" class="form-label">Barang Elektronik ?</label>
                                                    <select class="form-select" aria-label="Default select example" id="elektronik" name="elektronik">
                                                        <option value="1">Iya</option>
                                                        <option value="0">Bukan</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="kondisi_fisik" class="form-label">Kondisi Fisik</label>
                                                    <select class="form-select" aria-label="Default select example" id="kondisi_fisik" name="kondisiFisik">
                                                        <option value="baik">Baik</option>
                                                        <option value="rusak">Rusak</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="estimasi_pemakaian" class="form-label">Estimasi Pemakaian</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" name="estimasiPemakaian" id="estimasi_pemakaian" placeholder="12.4">
                                                        <span class="input-group-text">tahun</span>
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="durasi_pemakaian" class="form-label">Durasi Pemakaian</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" name="durasiPemakaian" id="durasi_pemakaian" placeholder="4">
                                                        <span class="input-group-text">tahun</span>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="anggaran" class="form-label">Anggaran</label>
                                                    <input type="number" name="anggaran" id="anggaran" class="form-control" placeholder="200000">
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="jumlah" class="form-label">Jumlah</label>
                                                    <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="40">
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                                                    <input type="date" name="tanggalPembelian" id="tanggal_pembelian" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="harga" class="form-label">Harga</label>
                                                    <input type="number" name="harga" id="harga" class="form-control" placeholder="2000000">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="keterangan" class="form-label">Keterangan</label>
                                                    <input type="text" name="keterangan" id="keterangan" class="form-control">
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="inventaris_lama" class="form-label">Kode Inventaris Lama</label>
                                                    <input type="text" name="inventarisLama" id="inventaris_lama" class="form-control">
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="inventaris_baru" class="form-label">Kode Inventaris Baru</label>
                                                    <input type="text" name="inventarisBaru" id="inventaris_baru" class="form-control">
                                                </div>
                                                <div class="mb-3">
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
                    </div>
                    <div class="card-body">
                        <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>No</th>
                                        <th>Ruang</th>
                                        <th>Jenis Barang</th>
                                        <th>Label</th>
                                        <th>Nama</th>
                                        <th>Penanggung Jawab</th>
                                        <th>Pemeriksaan Terakhir</th>
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
            $('#btn-tambah').on('click', function () {
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
                    if(key == 'pemeriksaan_terakhir')
                    {
                        var dateOnly = data[key].split(' ')[0];
                        $('#' + key)
                            .val(dateOnly)
                            .trigger('change');
                    }else if(key == 'tanggal_pembelian')
                    {
                        var dateOnly = data[key].split(' ')[0];
                        $('#' + key)
                            .val(dateOnly)
                            .trigger('change');
                    }{
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
