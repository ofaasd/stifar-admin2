@extends('layouts.master')
@section('title', 'Gedung')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title2}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Keuangan</li>
    <li class="breadcrumb-item active">Rekening Koran</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        @if(empty($link))
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal" id="add-record">+ Add Tagihan Total</button>
                        <button class="btn btn-info" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#importModal" id="import-record">+ Import Tagihan Total</button>
                        
                        @endif
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
                                            <div class="mb-3" id="field-nama">
                                                <label for="nim" class="form-label">NIM</label>
                                                <select class="select2_mhs" name="nim" id="nim" >
                                                    <option value="">-- Pilih NIM --</option>
                                                    @foreach($mahasiswa as $mhs)
                                                        <option value="{{$mhs->nim}}">{{$mhs->nim}} - {{$mhs->nama}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3" id="field-nama">
                                                <label for="gelombang" class="form-label">Gelombang</label>
                                                <input type="text" class="form-control" name="gelombang" id="gelombang" placeholder="Reguter 1">
                                            </div>
                                            
                                            <table id="pembayaranTable" class="table table-striped table-hover table-bordered">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Jenis Pembayaran</th>
                                                        <th>Jumlah</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>

                                            <a id="addRowBtn" class="btn btn-primary mt-3">Tambah Baris</a>

                                            <div class="mt-4">
                                                <label for="totalJumlah" class="form-label fw-bold">Total Jumlah:</label>
                                                <input type="text" name="total_jumlah" id="total_bayar" class="form-control" readonly>
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
                        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="javascript:void(0)" id="formImport">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Import Tagihan dari Excel</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <a href="{{url('/assets/file/format_import_tagihan.xlsx')}}" class="btn btn-primary">Format Import</a>
                                                <a href="{{url('/assets/file/format_import_tagihan_s1.xlsx')}}" class="btn btn-info">Format Import S1/S2</a>
                                            </div>
                                            <div class="mb-3" id="field-nama">
                                                <label for="file_excel" class="form-label">File Excel</label>
                                                <input type="file" class="form-control" name="file_excel" id="file_excel">
                                            </div>
                                            <div class="mb-3" id="field-nama">
                                                <label for="file_excel" class="form-label">Program Studi</label>
                                                <select class="form-control" name="prodi" id="prodi" >
                                                    <option value="">-- Pilih Prodi --</option>
                                                    @foreach($prodi as $prd)
                                                        <option value="{{$prd->id}}">{{$prd->nama_prodi}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" id="btn-import" type="submit">Simpan</button>
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
                                        <th>Gelombang</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Total Bayar</th>
                                        <th>Action</th>
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
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>

    <script>
        $(function () {
        $('.select2_mhs').select2({
            dropdownParent: $('#tambahModal')
        });
        const baseUrl = {!! json_encode(url('/')) !!};
        const title = "{{strtolower($title)}}";
        const title2 = "{{ $title2 }}";
        const page = '/'.concat("admin/keuangan/").concat(title);
        var my_column = $('#my_column').val();
        const pecah = my_column.split('\n');
        let my_data = [];
        pecah.forEach((item, index) => {
            let temp = item.replace(/ /g, '');
            let data_obj = { data: temp };
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
                    targets: -1,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function render(data, type, full, meta) {
                        return (
                        '<div class="btn-group">' +
                        '<button class="btn btn-sm btn-primary edit-record" data-id="'
                            .concat(full['id'], '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal"')
                            .concat(title, '"><i class="fa fa-pencil"></i></button>') +
                        '<button class="btn btn-sm btn-danger delete-record" data-id="'.concat(
                            full['id'],
                            '"><i class="fa fa-trash"></i></button>'
                        )
                        );
                    }
                }

            ],
            order: [[1, 'asc']],
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
            $('#pembayaranTable tbody').empty(); 
        });

        $(document).on('click', '.edit-record', function () {
            const id = $(this).data('id');
            $('#ModalLabel').html('Edit ' + title2);

            $.get(`${baseUrl}${page}/${id}/edit`, function (data) {
                Object.keys(data[1]).forEach(key => {
                    $('#' + key).val(data[1][key]).trigger('change');
                });
                $('#pembayaranTable tbody').empty(); 
                let i = 1;
                Object.keys(data[2]).forEach(key => {
                    let newRow = `<tr>
                                    <td>${i++}</td>
                                    <td>
                                        <select name="jenis[]" class="form-select">
                                            <option value="">-- Pilih Jenis Pembayaran --</option>
                                            @foreach($jenis as $jns)
                                                <option value="{{$jns->id}}" ${data[2][key].id_jenis == {{$jns->id}} ? 'selected' : '' }>{{$jns->nama}}</option>
                                            @endforeach    
                                        </select>
                                    </td>
                                    <td><input type="number" name="jumlah[]" class="form-control jumlah-input" placeholder="Jumlah" value="${data[2][key].jumlah}" /></td>
                                    <td><button class="btn btn-danger btn-sm">Hapus</button></td>
                                </tr>`;
                    $('#pembayaranTable tbody').append(newRow);
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
                url: `${baseUrl}${page}`,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function (status) {
                    dt.draw();
                    $("#tambahModal").modal('hide');
                    swal({
                        icon: 'success',
                        title: `Successfully Saved!`,
                        text: `Saved successfully.`,
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
                    btnSubmit.prop('disabled', false);
                }
            });
        });
        $('#formImport').on('submit', function (e) {
            e.preventDefault();
            const myFormData = new FormData(this);

            var btnSubmit = $('#btn-import');
            btnSubmit.prop('disabled', true);

            $.ajax({
                data: myFormData,
                url: `${baseUrl}/admin/keuangan/tagihan_total/import`,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function (status) {
                    dt.draw();
                    $("#importModal").modal('hide');
                    swal({
                        icon: 'success',
                        title: `Successfully Saved!`,
                        text: `Saved successfully.`,
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                    btnSubmit.prop('disabled', false);
                },
                error: function (xhr) {
                    $("#importModal").modal('hide');
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

        // Delete record
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

    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#pembayaranTable tbody');
        const addRowBtn = document.getElementById('addRowBtn');
        const totalJumlahInput = document.getElementById('total_bayar');

        function updateTotal() {
            let total = 0;
            const jumlahInputs = tableBody.querySelectorAll('input[placeholder="Jumlah"]');
            jumlahInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });
            totalJumlahInput.value = total.toLocaleString('id-ID'); // Format number for display
        }
        // Function to handle deleting a row
        function handleDelete(event) {
            if (event.target.classList.contains('btn-danger')) {
                const rowToDelete = event.target.closest('tr');
                if (rowToDelete) {
                    rowToDelete.remove();
                    updateRowNumbers();
                    updateTotal();
                }
            }
        }

        // Function to update the "No." column after adding or deleting a row
        function updateRowNumbers() {
            const rows = tableBody.querySelectorAll('tr');
            rows.forEach((row, index) => {
                row.querySelector('td').textContent = index + 1;
            });
        }

        // Add new row button event listener
        addRowBtn.addEventListener('click', function() {
            const newRow = document.createElement('tr');
            
            // Create table cells with input fields for data entry
            
            newRow.innerHTML = `
                <td></td>
                <td>
                    <select name="jenis[]" class="form-select">
                        <option value="">-- Pilih Jenis Pembayaran --</option>
                        @foreach($jenis as $jns)
                            <option value="{{$jns->id}}">{{$jns->nama}}</option>
                        @endforeach    
                    </select>
                </td>
                <td><input type="number" name="jumlah[]" class="form-control jumlah-input" placeholder="Jumlah" /></td>
                <td><button class="btn btn-danger btn-sm">Hapus</button></td>
            `;
            
            tableBody.appendChild(newRow);
            updateRowNumbers();
            updateTotal();
        });

        // Add event listener to the table body for handling delete button clicks
        tableBody.addEventListener('click', handleDelete);

        tableBody.addEventListener('keyup', function(event) {
            if (event.target.classList.contains('jumlah-input')) {
                updateTotal();
            }
        });

        // Initial total calculation on page load
        updateTotal();
    });
    </script>
@endsection
