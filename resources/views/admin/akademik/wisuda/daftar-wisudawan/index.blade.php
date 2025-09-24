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
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item">Wisuda</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    
                    @if (empty($isArsip))
                        <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
                            <div>
                                <button class="btn btn-primary" type="button" id="btn-select-all-nim">Pilih Semua</button>
                                <button class="btn btn-primary d-none" type="button" id="btn-submit">Pindahkan ke alumni</button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ url('/admin/akademik/wisuda/daftar-wisudawan-arsip') }}" class="btn btn-outline-info" title="Arsip Wisudawan">
                                    <i class="fa fa-archive"></i> Arsip
                                </a>

                                <!-- Notes Section -->
                                <div class="mb-0 flex-grow-1">
                                    <div class="alert alert-warning mb-0 text-dark" role="alert">
                                        <strong>Catatan:</strong> Wisudawan yang telah bertanda <i class="bi bi-check-circle-fill text-success bg-white rounded-circle"></i> dapat dipindahkan ke data alumni melalui tombol di samping kiri.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card-body">
                        <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>nim</th>
                                        <th>Wisuda</th>
                                        <th>Yudisium</th>
                                        <th>Berkas</th>
                                        <th>Status Pembayaran</th>
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
            const title = "{{strtolower($title2)}}";
            const page = '/'.concat("admin/akademik/wisuda/").concat(title);
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
                            return `
                                <input type="checkbox" class="form-check-input me-2 list-mhs" style="width:24px;height:24px;border-radius:0;" value="${full.nim}">
                                <span>${full.fake_id}</span>
                            `;
                        }
                    },
                    {
                        searchable: false,
                        orderable: false,
                        targets: 2,
                        render: function render(data, type, full, meta) {
                            // Render NIM, Nama, Photo, and Checkbox
                            var photoUrl = full.photo ? `{{ asset("assets/images/mahasiswa/foto-yudisium/") }}/${full.photo}` : '{{ asset("assets/images/user/1.jpg") }}';
                            return `
                                <div class="d-flex align-items-center">
                                    <img src="${photoUrl}" alt="Photo" class="rounded me-2" style="width:45px; height:60px; object-fit:cover;">
                                    <div>
                                        <div><strong>${full.nim}</strong></div>
                                        <div>${full.nama}</div>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        searchable: false,
                        orderable: false,
                        targets: 5,
                        render: function render(data, type, full, meta) {
                            const fileBase = '{{ asset("assets/file/berkas/mahasiswa") }}';
                            const links = [
                                full.kk ? `<a href="${fileBase}/kk/${full.kk}" target="_blank" class="btn btn-sm btn-outline-primary mb-1" title="KK"><i class="fa fa-id-card"></i></a>` : '',
                                full.ktp ? `<a href="${fileBase}/ktp/${full.ktp}" target="_blank" class="btn btn-sm btn-outline-primary mb-1" title="KTP"><i class="fa fa-address-card"></i></a>` : '',
                                full.akte ? `<a href="${fileBase}/akte/${full.akte}" target="_blank" class="btn btn-sm btn-outline-primary mb-1" title="Akte"><i class="fa fa-address-book-o"></i></a>` : '',
                            ].filter(Boolean).join(' ');
                            return links ? links : '-';
                        }
                    },
                    {
                        // Actions
                        targets: -1,
                        title: 'Actions',
                        searchable: false,
                        orderable: false,
                        render: function render(data, type, full, meta) {
                            return '';
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

            let dataMhs = [];
            // Toggle all checkboxes and collect checked values
            $(document).on('click', '#btn-select-all-nim', function () {
                $('.list-mhs').each(function () {
                    this.checked = !this.checked;
                    const value = $(this).val();
                    if (this.checked) {
                        if (!dataMhs.includes(value)) dataMhs.push(value);
                    } else {
                        dataMhs = dataMhs.filter(v => v !== value);
                    }
                });
            });

            $(document).on('change', '.list-mhs', function () {
                const value = $(this).val();
                if (this.checked) {
                    if (!dataMhs.includes(value)) dataMhs.push(value);
                } else {
                    dataMhs = dataMhs.filter(v => v !== value);
                }
            });

            $(document).on('change click', '.list-mhs, #btn-select-all-nim', function () {
                if (dataMhs.length > 0) {
                    $('#btn-submit').removeClass('d-none');
                } else {
                    $('#btn-submit').addClass('d-none');
                }
            });

            $(document).on('click', '#btn-submit', function () {
                swal({
                    title: 'Apakah anda yakin?',
                    text: "Data tidak dapat dikembalikan!",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, alumnikan!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                if (result) {
                    dataNim = {
                        'nim': dataMhs,
                        '_token': '{{ csrf_token() }}'
                    }

                    // acc the data
                    $.ajax({
                    type: 'POST',
                    url: ''.concat(baseUrl).concat(page),
                    data: dataNim,
                    success: function success(response) {
                        dt.draw();
                        swal({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    },
                    error: function error(xhr) {
                        const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred while processing your request.';
                        swal({
                            title: 'Failed!',
                            text: message,
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }
                    });

                    // success sweetalert
                    swal({
                        icon: 'success',
                        title: 'Accepted!',
                        text: 'The Record has been accepted!',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                } else {
                    swal({
                        title: 'Cancelled',
                        text: 'The record is not accepted!',
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
