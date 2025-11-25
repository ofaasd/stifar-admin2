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
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="d-flex flex-wrap align-items-end gap-3">
                            {{-- <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#tambahModal">
                                + {{ $title }}
                            </button> --}}

                            <div style="min-width:220px;">
                                <label for="prodiSelect" class="form-label small mb-1">Program Studi</label>
                                <select id="prodiSelect" class="form-select form-select-sm">
                                    <option value="">Semua Prodi</option>
                                    @foreach($prodi as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama_prodi }}</option>
                                    @endforeach
                                </select>
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
                                        <th>Mahasiswa</th>
                                        <th>Dicetak Pada</th>
                                        <th>Diserahkan Pada</th>
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
            const page = '/'.concat("admin/akademik/").concat(title);
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
                data: function (d) {
                d.filterprodi = $('#prodiSelect').val();
                }
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
                targets: 4,
                render: function render(data, type, full, meta) {
                    if (full.diserahkan_pada === null || full.diserahkan_pada === '' || typeof full.diserahkan_pada === 'undefined') {
                        return '<input type="date" class="form-control form-control-sm gived-at-input" data-id="' + full.id + '">';
                    } else {
                        var display = full.diserahkan_pada;
                        return '<span>' + display + '</span>';
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
                    if (full.diserahkan_pada == null || full.diserahkan_pada == '') {
                        return (
                                '<div class="d-inline-block text-nowrap">'+
                                '<button class="btn btn-sm btn-icon btn-save-date text-primary me-1" data-id="' +
                                full['id'] +
                                '" title="Simpan"><i class="fa fa-save"></i></button>' +
                                '</div>'
                            );
                    }else{
                        return (
                                '<div class="d-inline-block text-nowrap">'+
                                '<button class="btn btn-sm btn-icon btn-save-date text-primary me-1" data-id="' +
                                full['id'] +
                                '" title="Simpan" disabled><i class="fa fa-save"></i></button>' +
                                '</div>'
                            );
                    }
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


            $(document).on('change', '#prodiSelect', function () {
            dt.ajax.reload();
            });
            
            $('#tambahModal').on('hidden.bs.modal', function () {
            $('#formAdd').find('input, textarea, select').val('');
            $('#formAdd').trigger("reset");
            $('#id').val('');
            $('#mhs-selected').empty();
            });
            //Edit Record
            $(document).on('click', '.edit-record', function () {
            const id = $(this).data('id');

            // changing the title of offcanvas
            $('#ModalLabelEdit').html('Edit ' + title);

            // get data
            $.get(''.concat(baseUrl).concat(page, '/').concat(id, '/edit'), function (data) {
                Object.keys(data).forEach(key => {
                $('#' + key)
                .val(data[key])
                .trigger('change');
                });
            });
            });

            //save record
            $('#formAdd').on('submit', function(e) {
            e.preventDefault();
            var btnSubmit = $('#btn-submit');
            var oldBtnHtml = btnSubmit.html();
            btnSubmit.prop('disabled', true);
            btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
            $('#mhs-selected option').prop('selected', true);

            $.ajax({
                data: $('#formAdd').serialize(),
                url: ''.concat(baseUrl).concat(page),
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                },
                success: function success(message) {
                dt.draw();
                $("#tambahModal").modal('hide');
                swal({
                    icon: 'success',
                    title: 'Successfully '.concat(message, '!'),
                    text: ''.concat(title, ' ').concat(message, ' Successfully.'),
                    customClass: {
                    confirmButton: 'btn btn-success'
                    }
                });
                btnSubmit.prop('disabled', false);
                btnSubmit.html(oldBtnHtml);
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
                btnSubmit.prop('disabled', false);
                btnSubmit.html(oldBtnHtml);
                }
            });
            });

            //save date record
            $(document).on('click', '.btn-save-date', function () {
                const id = $(this).data('id');
                const dateInput = $('input.gived-at-input[data-id="' + id + '"]');
                const gived_at = dateInput.val();

                if (!gived_at) {
                    swal({
                    title: 'Pilih tanggal terlebih dahulu',
                    icon: 'warning',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                    });
                    return;
                }

                // confirmation dialog
                swal({
                    title: 'Are you sure?',
                    text: "Save this date?",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: false,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, save it!',
                    customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result) {
                    $.ajax({
                        type: 'POST',
                        url: ''.concat(baseUrl).concat(page),
                        data: {
                        id: id,
                        gived_at: gived_at,
                        _token: '{{ csrf_token() }}'
                        },
                        success: function success() {
                        dt.draw();
                        swal({
                            icon: 'success',
                            title: 'Saved!',
                            text: 'The date has been saved!',
                            customClass: {
                            confirmButton: 'btn btn-success'
                            }
                        });
                        },
                        error: function error(_error) {
                        swal({
                            title: 'Failed!',
                            text: _error.responseText || _error.message || 'Error saving date',
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-success'
                            }
                        });
                        }
                    });
                    } else {
                    swal({
                        title: 'Cancelled',
                        text: 'The date was not saved',
                        icon: 'info',
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
