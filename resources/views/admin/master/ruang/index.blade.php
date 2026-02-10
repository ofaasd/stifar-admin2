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
    <li class="breadcrumb-item active">Ruang</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-sm-12 mb-2">
                <div class="row">
                    <div class="col-lg-6 col-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Statistik ruang pada gedung</h6>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div id="chartGedung"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Statistik Jenis Ruang</h6>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div id="chartJenis"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
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
                                                <label for="kodeGedung " class="form-label">Kode Gedung</label>
                                                <select class="form-select" aria-label="Default select example" id="kode_gedung" name="kodeGedung">
                                                    @foreach ($asetGedung as $row)
                                                    <option value="{{ $row->kode }}">{{ $row->kode }} - {{ $row->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nama_ruang" class="form-label">Nama</label>
                                                <input type="text" name="namaRuang" id="nama_ruang" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label for="lantai" class="form-label">Lantai</label>
                                                <select class="form-select" aria-label="Default select example" id="lantai_id" name="lantai">
                                                    @foreach ($asetLantai as $row)
                                                    <option value="{{ $row->id }}">{{ $row->lantai }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jenis_ruang " class="form-label">Jenis Ruang</label>
                                                <select class="form-select" aria-label="Default select example" id="kode_jenis" name="kodeJenis">
                                                    @foreach ($asetJenisRuang as $row)
                                                    <option value="{{ $row->kode }}">{{ $row->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kapasitas" class="form-label">Kapasitas</label>
                                                <input type="text" name="kapasitas" id="kapasitas" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label for="luas" class="form-label">Luas</label>
                                                <input type="text" name="luas" id="luas" class="form-control">
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
                                        <th>Kode Gedung</th>
                                        <th>Jenis Ruang</th>
                                        <th>Nama</th>
                                        <th>Kapasitas</th>
                                        <th>Lantai</th>
                                        <th>Luas</th>
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
            const page = '/'.concat("admin/masterdata/").concat(title);
            var my_column = $('#my_column').val();
            const pecah = my_column.split('\n');
            let my_data = [];
            pecah.forEach((item, index) => {
                let temp = item.replace(/ /g, '');
                let data_obj = { data: temp };
                //alert(data_obj.data);
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
                        targets: 4, // indeks kolom ke-4 (Nama), sesuaikan jika posisinya berbeda
                        render: function (data, type, full, meta) {
                            return '<a href="' + baseUrl.concat(page) + '/' + full.idEnkripsi + '">' + data + '</a>';
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
                                .concat(full.id, '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal"')
                                .concat(title, '"><i class="fa fa-pencil"></i></button>') +
                            '<button class="btn btn-sm btn-icon delete-record text-primary" data-id="'.concat(
                                full.id,
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

            // Chart Gedung
            const gedungData = @json($statGedung ?? []);
            if (document.querySelector("#chartGedung") && Object.keys(gedungData).length > 0) {
                
                const gedungLabels = Object.keys(gedungData); // ['Gedung A', 'Gedung B']
                const gedungValues = Object.values(gedungData); // [10, 5]

                var optionsGedung = {
                    series: [{
                        name: 'Jumlah Aset',
                        data: gedungValues
                    }],
                    chart: {
                        type: 'bar', // Bisa diganti 'bar' (vertikal) atau 'pie'
                        height: 300,
                        toolbar: { show: false },
                        fontFamily: 'Rubik, sans-serif',
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '50%', // Lebar batang
                            distributed: true // Warna beda-beda tiap batang
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: { fontSize: '12px', colors: ['#fff'] }
                    },
                    xaxis: {
                        categories: gedungLabels,
                        labels: {
                            style: { fontSize: '11px' },
                            rotate: -45 // Miringkan label jika nama gedung panjang
                        },
                        title: { text: 'Gedung' }
                    },
                    yaxis: {
                        title: { text: 'Total Ruang' }
                    },
                    colors: ['#7366ff', '#f73164', '#51bb25', '#f8d62b', '#544fff'], // Warna-warni
                    legend: { show: false }, // Sembunyikan legend jika pakai distributed colors
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " Item"
                            }
                        }
                    }
                };

                var chartGedung = new ApexCharts(document.querySelector("#chartGedung"), optionsGedung);
                chartGedung.render();

            } else if (document.querySelector("#chartGedung")) {
                // Tampilan jika data kosong
                document.querySelector("#chartGedung").innerHTML = "<div class='text-center p-5 text-muted'>Data Gedung Kosong</div>";
            }

            // Chart Jenis
            const jenisData = @json($statJenis ?? []);
            if (document.querySelector("#chartJenis") && Object.keys(jenisData).length > 0) {
                
                const jenisLabels = Object.keys(jenisData); // ['Jenis A', 'Jenis B']
                const jenisValues = Object.values(jenisData); // [10, 5]

                var optionsJenis = {
                    series: [{
                        name: 'Jumlah Aset',
                        data: jenisValues
                    }],
                    chart: {
                        type: 'bar', // Bisa diganti 'bar' (vertikal) atau 'pie'
                        height: 300,
                        toolbar: { show: false },
                        fontFamily: 'Rubik, sans-serif',
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '50%', // Lebar batang
                            distributed: true // Warna beda-beda tiap batang
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: { fontSize: '12px', colors: ['#fff'] }
                    },
                    xaxis: {
                        categories: jenisLabels,
                        labels: {
                            style: { fontSize: '11px' },
                            rotate: -45 // Miringkan label jika nama jenis panjang
                        },
                        title: { text: 'Jenis' }
                    },
                    yaxis: {
                        title: { text: 'Total Ruang' }
                    },
                    colors: ['#7366ff', '#f73164', '#51bb25', '#f8d62b', '#544fff'], // Warna-warni
                    legend: { show: false }, // Sembunyikan legend jika pakai distributed colors
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " Item"
                            }
                        }
                    }
                };

                var chartJenis = new ApexCharts(document.querySelector("#chartJenis"), optionsJenis);
                chartJenis.render();

            } else if (document.querySelector("#chartJenis")) {
                // Tampilan jika data kosong
                document.querySelector("#chartJenis").innerHTML = "<div class='text-center p-5 text-muted'>Data Jenis Kosong</div>";
            }

            $('#tambahModal').on('hidden.bs.modal', function () {
                $('#id').val('');
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
                var btnSubmit = $('#btn-submit');
                btnSubmit.prop('disabled', true); 
                btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
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
                        // offCanvasForm.offcanvas('hide');
                        console.log('====================================');
                        console.log(err);
                        console.log('====================================');
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
