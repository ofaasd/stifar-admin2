@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')
<style>
    .dataTables_wrapper tbody td{
        font-size: 0.85em; /* Adjust this value (e.g., 14px, small, 85%) */
        padding: 0.3em 0.5em;
    }
</style>
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
            <div class="col-sm-12 mb-2">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Status Pembayaran</h6>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div id="chartBayar"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Status Verifikasi</h6>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div id="chartVerifikasi"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Status Kelolosan</h6>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div id="chartLolos"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Peminat per Prodi (Pilihan 1)</h6>
                            </div>
                            <div class="card-body">
                                <div id="chartProdi"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="alert alert-primary inverse alert-dismissible fade show" role="alert"><i class="icon-help-alt"></i>
                    <p>Menu verifikasi digunakan untuk memverifikasi no pendaftaran mahasiswa baru untuk dapat dikoneksikan dengan data dari bank data VA yang disediakan oleh bagian keuangan</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button>
                </div>
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
                        <div class="table-responsive ">
                            <table class="display dataTables_wrapper" id="basic-1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Nama.</th>
                                        <th>No. Pendaftaran</th>
                                        <th>Gelombang</th>
                                        <th>Pilihan</th>
                                        <th>Tgl Registrasi</th>
                                        <th>Verifikasi</th>
                                        <th>No. VA</th>
                                        <th>Status</th>
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
                                            <div id="alert"></div>
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
                        <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Detail Calon Mahasiswa Baru
                                    </div>
                                    <div class="modal-body">
                                        <table id="detail_mahasiswa" class="table"></table>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="ListVaModal" tabindex="-1" role="dialog" aria-labelledby="showModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        List All VA
                                    </div>
                                    <div class="modal-body">
                                        <table id="vaTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>No. VA</th>
                                                    <th>No. Pendaftaran</th>
                                                    <th>status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($bank as $row)
                                                <tr>
                                                    <td>{{$row->id}}</td>
                                                    <td>{{$row->no_va}}</td>
                                                    <td>{{$row->nopen}}</td>
                                                    <td>{{($row->status == 0)?'Belum Dipakai':'Sudah Dipakai'}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
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
            $("#vaTable").DataTable({
                responsive: true,
            });
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
                            return '<span>'.concat(full.pilihan1).concat(' | ', full.pilihan2).concat('</span>');
                        }
                    },
                    {
                        searchable: false,
                        orderable: false,
                        targets: 7,
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
                    searchable: false,
                    orderable: false,
                    targets: 9,
                    render: function render(data, type, full, meta) {
                        return '<span>'.concat((full.is_bayar)?"<button class='btn btn-success btn-xs' style='font-size:9pt; margin:2px 0;'>Sudah Bayar</button>":"<button class='btn btn-danger btn-xs' style='font-size:9pt;  margin:2px 0;' title='Belum bayar registrasi uang masuk'>Belum Bayar</button>")
                        .concat((full.is_lolos)?"<button class='btn btn-success btn-xs' style='font-size:9pt'>Sudah Lolos</button>":"<button class='btn btn-danger btn-xs' style='font-size:9pt'>Belum Lolos</button>").concat('</span>');
                    }
                    },
                   
                    {
                        searchable: false,
                        orderable: false,
                        targets: 9,
                        render: function render(data, type, full, meta) {
                            return '91291' + full['nopen'];
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
                            
                            '<div class="btn-group btn-group-sm"><a class="btn btn-sm  show-record btn-primary" data-id="'
                            .concat(full['id'], '" data-bs-toggle="modal" data-original-title="show" data-bs-target="#showModal"')
                            .concat(title, '"><i class="fa fa-eye"></i></a>'+
                            
                            '<a class="btn btn-sm edit-record btn-info" data-id="')
                            .concat(full['id'], '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal"')
                            .concat(title, '"><i class="fa fa-pencil"></i></a>')+ 
                            '<a class="btn btn-sm  list-va btn-success" title="Cek List VA" data-id="'
                            .concat(full['id'], '" data-bs-toggle="modal" data-original-title="show" data-bs-target="#ListVaModal"')
                            .concat(title, '"><i class="fa fa-list-alt"></i></a></div>')
                        );
                    }
                    }
                ],
                order: [[1, 'desc']],
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

            // 1. Chart Pembayaran (Pie)
            var totalSudahBayar = {{ $stat_bayar->sudah_bayar ?? 0 }};
            var totalBelumBayar = {{ $stat_bayar->belum_bayar ?? 0 }};

            var optionsBayar = {
                series: [totalSudahBayar, totalBelumBayar],
                chart: { type: 'pie', height: 280 },
                labels: ['Sudah Bayar', 'Belum Bayar'],
                colors: ['#24695c', '#d22d3d'],
                legend: { position: 'bottom' },
                dataLabels: { enabled: true, formatter: function (val, opts) { return opts.w.config.series[opts.seriesIndex] } }
            };
            new ApexCharts(document.querySelector("#chartBayar"), optionsBayar).render();

            // 2. Chart Verifikasi (Donut)
            var verifTerima = {{ $stat_verifikasi->diterima ?? 0 }};
            var verifTolak = {{ $stat_verifikasi->ditolak ?? 0 }};
            var verifBelum = {{ $stat_verifikasi->belum ?? 0 }};

            var optionsVerif = {
                series: [verifTerima, verifTolak, verifBelum],
                chart: { type: 'donut', height: 280 },
                labels: ['Diterima', 'Ditolak', 'Belum'],
                colors: ['#24695c', '#d22d3d', '#f8d62b'], // Hijau, Merah, Kuning
                legend: { position: 'bottom' },
                plotOptions: { pie: { donut: { size: '65%' } } },
                dataLabels: { enabled: true }
            };
            new ApexCharts(document.querySelector("#chartVerifikasi"), optionsVerif).render();

            // 3. 
            var sudahLolos = {{ $stat_lolos->lolos ?? 0 }};
            var belumLolos = {{ $stat_lolos->belum_lolos ?? 0 }};
            var tidakLolos = {{ $stat_lolos->belum ?? 0 }};

            var optionsVerif = {
                series: [sudahLolos, belumLolos, tidakLolos],
                chart: { type: 'donut', height: 280 },
                labels: ['Sudah Lolos', 'Belum Lolos', 'Tidak Lolos'],
                colors: ['#24695c', '#d22d3d', '#f8d62b'], // Hijau, Merah, Kuning
                legend: { position: 'bottom' },
                plotOptions: { pie: { donut: { size: '65%' } } },
                dataLabels: { enabled: true }
            };
            new ApexCharts(document.querySelector("#chartLolos"), optionsVerif).render();

            // 3. Chart Prodi (Bar)
            var labelProdi = [];
            var dataProdi = [];
            @foreach($stat_prodi as $sp)
                labelProdi.push("{{ $sp->nama_prodi }}");
                dataProdi.push({{ $sp->total }});
            @endforeach

            var optionsProdi = {
                series: [{ name: 'Peserta', data: dataProdi }],
                chart: { type: 'bar', height: 280, toolbar: {show: false} },
                plotOptions: { bar: { borderRadius: 4, horizontal: true } },
                xaxis: { categories: labelProdi },
                colors: ['#7366ff'],
                dataLabels: { enabled: true }
            };
            new ApexCharts(document.querySelector("#chartProdi"), optionsProdi).render();

            $('#tambahModal').on('hidden.bs.modal', function () {
                $('#formAdd').trigger("reset");
                $("#alert").html('');
            });
            //Edit Record
            $(document).on('click', '.edit-record', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabel').html('Edit ' + title);
                

                // get data
                $.get(''.concat(baseUrl).concat(page_edel, '/').concat(id, '/edit'), function (data) {
                Object.keys(data[0]).forEach(key => {
                    if(key == 'is_verifikasi' && data[0]['is_verifikasi'] != 1){
                        if(data[1] == 0){
                            $("#alert").html('<div class="alert alert-danger mb-4">No. Pendaftaran Sudah Di pakai</div>');
                        }else{
                            $("#alert").html('<div class="alert alert-success mb-4">No. Pendaftaran Tersedia dan dapat digunakan</div>');
                        }
                    }
                    $('#' + key)
                        .val(data[0][key])
                        .trigger('change');
                    });
                    
                    
                });
                
                
            });
            $(document).on('click', '.show-record', function () {
                const id = $(this).data('id');

                // changing the title of offcanvas
                $('#ModalLabel').html('Edit ' + title);

                // get data
                $.get(''.concat(baseUrl).concat(page_edel, '/').concat(id, '/show'), function (data) {
                $("#detail_mahasiswa").html('');
                Object.keys(data).forEach(key => {
                    //console.log(key);
                    $("#detail_mahasiswa").append(`<tr><td>${key.replace(/_/g, ' ')}</td><td>:</td><td>${data[key]}</td></tr>`);
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
