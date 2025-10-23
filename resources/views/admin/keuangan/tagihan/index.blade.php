@extends('layouts.master')
@section('title', 'Gedung')

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
    <li class="breadcrumb-item">Master</li>
    <li class="breadcrumb-item active">Gedung</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 project-list">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                                    @foreach($prodi as $prod)
                                        <li class="nav-item"><a href="{{URL::to('admin/keuangan/tagihan_show/' . $prod->id)}}" class="nav-link {{($id==$prod->id)?"active":""}}" style="font-size:10pt;"><i data-feather="info"></i>{{$nama[$prod->id]}} </a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        @if(empty($link))
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal" id="add-record">+ Generate {{$title2}}</button>
                        <a href="{{url("admin/keuangan/tagihan/publish/" . $id)}}" class="btn btn-success" >Publish Tagihan</a>
                        <a href="{{url("admin/keuangan/tagihan/unpublish/" . $id)}}" class="btn btn-danger" >UnPublish Tagihan</a>
                        <a href="{{url("admin/keuangan/tagihan/payment_checking/" . $id)}}" class="btn btn-info" >Checking Pembayaran</a>
                        @endif
                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                {{ Session::get('success') }}
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>  
                        @endif
                        <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="javascript:void(0)" id="formAdd">
                                        @csrf

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Generate {{$title2}}</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <label for="tahun_ajaran">Tahun Ajaran</label>
                                            <select name="tahun_ajaran" id="tahun_ajaran" class="form-control">
                                                @foreach($ta_all as $row)
                                                <option value="{{$row->id}}" {{($tahun_ajaran == $row->id)?"selected":""}}>{{substr($row->kode_ta,0,4)}} - {{(substr($row->kode_ta,-1,1) == 1)?"Ganjil":"Genap"}}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-group">
                                                <label for="tahun_ajaran">Angkatan</label>
                                                <select name="angkatan"  id="angkatan" class="form-control">
                                                    @foreach($angkatan as $row)
                                                        <option value="{{ $row['angkatan'] }}">{{ $row['angkatan'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="batas_waktu">Program Studi</label>
                                                <select name="id_prodi" id="id_prodi" class="form-control">
                                                    @foreach($prodi as $row)
                                                     <option value="{{$row->id}}" {{(!empty($id) && $id == $row->id)?"selected":""}}>{{$row->nama_prodi}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="periode">Bulan</label>
                                                <select name="periode" id="periode" class="form-control">
                                                    @foreach($list_bulan as $key=>$value)
                                                     <option value="{{$key}}" {{(date('m') == $key)?"selected":""}}>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" id="btn-submit" type="submit">Generate</button>
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
                                        @foreach($jenis as $row)
                                        <th>{{$row->nama}}</th>
                                        @endforeach
                                        <th>Total</th>
                                        <th>Total Bayar</th>
                                        <th>Status</th>
                                        <th>Publish</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="javascript:void(0)" id="formAdd2">
                                        @csrf

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Edit Tagihan <span id="tpt-nim"></span></h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_prodi" id="id_prodi_edit" value="{{$id}}">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="nim" id="nim">
                                            @foreach($jenis as $row)
                                            <div class="mb-3" id="field-nama">
                                                <label for="jenis" class="form-label">{{$row->nama}}</label>
                                                <input type="hidden" name="id_jenis[]" id="id_jenis{{$row->id}}" value="{{$row->id}}">
                                                <input type="number" class="form-control" name="jenis[]" id="jenis_edit{{$row->id}}" value="0">
                                            </div>
                                            @endforeach
                                            <div class="mb-3" id="field-nama">
                                                <label for="total" class="form-label">Total</label>
                                                <input type="number" class="form-control" name="total" id="total" value="" readonly>
                                            </div>
                                            <div class="mb-3" id="field-nama">
                                                <label for="total_bayar" class="form-label">Total Bayar</label>
                                                <input type="number" class="form-control" name="total_bayar" id="total_bayar" value="">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="batas_waktu">Batas Waktu</label>
                                                <input type="date" name="batas_waktu" id="batas_waktu" class="form-control">
                                            </div>
                                            <div class="mb-3" id="field-nama">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status_bayar" id="status" class="form-control">
                                                    <option value="0">Belum Lunas</option>
                                                    <option value="1">Lunas</option>
                                                </select>
                                            </div>
                                            <div class="mb-3" id="field-nama">
                                                <label for="is_publish" class="form-label">Publilsh Tagihan</label>
                                                <select name="is_publish" id="is_publish" class="form-control">
                                                    <option value="0">Draft</option>
                                                    <option value="1">Publish</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" id="btn-submit2" type="submit">Simpan</button>
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
        console.log($("#my_column").val())
        const baseUrl = {!! json_encode(url('/')) !!};
        const title = "{{strtolower($title)}}";
        const title2 = "{{ $title2 }}";
        const page = '/'.concat("admin/keuangan/").concat(title,'_show/{{$id}}');
        const page2 = '/'.concat("admin/keuangan/").concat(title);
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
                    className: 'control',
                    searchable: false,
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function render() {
                        return '';
                    }
                },
                {
                    searchable: false,
                    orderable: false,
                    targets: 1,
                    render: function render(data, type, full) {
                        return `<span>${full['fake_id']}</span>`;
                    }
                },
                {
                    searchable: false,
                    orderable: false,
                    targets: 11,
                    render: function render(data, type, full) {
                        return `<span>${full['total']}</span>`;

                    }
                },
                {
                    searchable: false,
                    orderable: false,
                    targets: 12,
                    render: function render(data, type, full) {
                        return `<span>${full['total_bayar']}</span>`;

                    }
                },
                {
                    searchable: false,
                    orderable: false,
                    targets: 13,
                    render: function render(data, type, full) {
                        if(full['status']){
                            return `<i class="fa fa-check text-success" title="Sudah Bayar"></i>`;
                        }else{
                            return `<i class="fa fa-times text-danger" title="Belum Bayar"></i>`;
                        }

                    }
                },
                {
                    searchable: false,
                    orderable: false,
                    targets: 14,
                    render: function render(data, type, full) {
                        if(full['is_publish'] == 1){
                            return `<i class="fa fa-check text-success" title="Sudah Publish"></i>`;
                        }else{
                            return `<i class="fa fa-times text-danger" title="Belum Publish"></i>`;
                        }

                    }
                },
                {
                    targets: -1,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function render(data, type, full) {
                        return `
                            <div class="d-inline-block text-nowrap">
                                <button class="btn btn-sm btn-primary edit-record" data-nama="${full['nama']}" data-nim="${full['nim']}" data-id="${full['id_tagihan']}" data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </div>`;
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
            $('#formAdd2').trigger("reset");
            $("#id").val('0');
             $("#nim").val($(this).data('nim'));
             $("#tpt-nim").html($(this).data('nim'));
            $('#ModalLabel').html('Edit ' + title2);

            $.get(`${baseUrl}/admin/keuangan/tagihan/${id}/edit`, function (data) {
                Object.keys(data[0]).forEach(key => {
                    $('#' + key).val(data[0][key]).trigger('change');
                });
                // Object.keys(data[1]).forEach(key => {
                //     console.log(data[1][key][id_jenis]);
                //     //$('#' + key).val(data[1][key]).trigger('change');
                // });
                data[1].forEach(item => {
                    // 'item' represents each object in the 'data' array
                    $(`#id_jenis${item.id_jenis}`).val(`${item.id_jenis}`);
                    $(`#jenis_edit${item.id_jenis}`).val(`${item.jumlah}`);
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
                url: `${baseUrl}${page2}`,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function (status) {
                    dt.draw();
                    $("#tambahModal").modal('hide');
                    swal({
                        icon: 'success',
                        title: `Successfully ${status}!`,
                        text: `${title} ${status} successfully.`,
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
                }
            });
        });
        $('#formAdd2').on('submit', function (e) {
            e.preventDefault();
            const myFormData = new FormData(this);

            var btnSubmit = $('#btn-submit2');
            btnSubmit.prop('disabled', true);

            $.ajax({
                data: myFormData,
                url: `${baseUrl}${page2}`,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function (status) {
                    dt.draw();
                    $("#tambahModal").modal('hide');
                    swal({
                        icon: 'success',
                        title: `Successfully ${status}!`,
                        text: `${title} ${status} successfully.`,
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
                }
            });
        });
    });


    </script>
@endsection
