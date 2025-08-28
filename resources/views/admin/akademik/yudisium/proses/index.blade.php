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
    <li class="breadcrumb-item">Yudisium</li>
    <li class="breadcrumb-item active">{{ $title2 }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        {{-- Tambah proses --}}
        <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formAdd">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Tambah {{$title}}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex flex-wrap gap-3">
                            <div class="mb-3 flex-fill w-100">
                                <div class="mb-3">
                                    <label for="gelombang" class="form-label">Gelombang Yudisium</label>
                                    <select class="form-control form-control-lg" id="gelombang" name="gelombang" required style="width:100%;" {{ count($gelombang) == 0 ? 'disabled' : '' }}>
                                        @if(count($gelombang) == 0)
                                            <option value="">Tidak ada gelombang tersedia</option>
                                        @else
                                            <option value="">-- Pilih Gelombang --</option>
                                            @foreach($gelombang as $row)
                                                <option value="{{ $row->id }}">
                                                    {{ $row->periode }} | 
                                                    {{ $row->nama }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label>Daftar Mahasiswa Lulus</label>
                                        <select id="mhs-available" class="form-control" size="10" multiple style="height:auto;" {{ count($mhs) == 0 ? 'disabled' : '' }}>
                                            @forelse ($mhs as $row)
                                                <option value="{{ $row->nim }}"
                                                    title="{{ $row->nim }} | {{ $row->nama }} | sks: {{ $row->totalSks }} | ipk: {{ $row->ipk }}"
                                                    data-foto="{{ $row->foto_mhs ? asset('assets/images/mahasiswa/' . $row->foto_mhs) : asset('assets/images/user/1.jpg') }}"
                                                    class="px-3 py-2">
                                                    {{ $row->nim }} | {{ $row->nama }} | sks: {{ $row->totalSks }} | ipk: {{ $row->ipk }}
                                                </option>
                                            @empty
                                                <option value="">Tidak ada mahasiswa tersedia</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-1 d-flex flex-column justify-content-center align-items-center gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="add-mhs">&gt;&gt;</button>
                                        <button type="button" class="btn btn-primary btn-sm" id="remove-mhs">&lt;&lt;</button>
                                    </div>
                                    <div class="col-5">
                                        <label>Mahasiswa Terpilih</label>
                                        <select id="mhs-selected" name="listMahasiswa[]" class="form-control" size="10" multiple>
                                            <!-- Selected mahasiswa will appear here -->
                                        </select>
                                    </div>
                                </div>
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

        {{-- Edit proses --}}
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="javascript:void(0)" id="formEdit">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabelEdit">Edit {{$title}}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body d-flex flex-wrap gap-3">
                            <div class="mb-3 flex-fill w-100">
                                <div class="mb-3">
                                    <label for="gelombang" class="form-label">Gelombang Yudisium</label>
                                    <select class="form-control" id="id_gelombang_yudisium" name="gelombang" required>
                                        <option value="">-- Pilih Gelombang --</option>
                                        @foreach($gelombang as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->periode }} | 
                                                {{ $row->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-12">
                                        <label>Mahasiswa</label>
                                        <select id="nim" name="nim" class="form-control">
                                            @foreach($mhs as $row)
                                                <option value="{{ $row->nim }}">{{ $row->nim }} - {{ $row->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit" id="btn-submit-edit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Transkrip Nilai --}}
        <div class="modal fade" id="cetakTranskripModal" tabindex="-1" aria-labelledby="cetak-transkrip-nilai" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" id="formCetakTranskripNilai" action="{{ url('/admin/akademik/yudisium/cetak-transkrip-nilai') }}" target="_blank">
                    @csrf
                        <input type="hidden" name="nimEnkripsi" id="nim-transkrip">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cetak-ijazah">Cetak Transkrip Nilai <span id="nama-transkrip"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nomor-sk" class="form-label">Nomor SK</label>
                                <input type="text" class="form-control" id="nomor-sk" name="nomorSk" value="153/D/O/2000 tanggal 10 Agustus 2000" required>
                            </div>
                            <div class="mb-3">
                                <label for="nomor-seri" class="form-label">Nomor Seri Transkrip</label>
                                <input type="text" class="form-control" id="nomor-seri" name="nomorSeri" value="063032" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="btn-submit">Cetak</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">

                    @if (empty($isArsip))
                        <div class="card-header pb-0 card-no-border d-flex align-items-center justify-content-between">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal">
                                + {{$title}}
                            </button>
                            <a href="{{ url('/admin/akademik/yudisium/proses-arsip') }}" class="btn btn-outline-info" title="Arsip Yudisium">
                                <i class="fa fa-archive"></i> Arsip
                            </a>
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
                                        <th>NIM/Nama</th>
                                        <th>SKS/IPK</th>
                                        <th>Jumlah Nilai D/ E</th>
                                        <th>Gelombang</th>
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
    <!-- Modal Upload Foto Yudisium -->
    <div class="modal fade" id="uploadFotoYudisiumModal" tabindex="-1" aria-labelledby="uploadFotoYudisiumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form id="formUploadFotoYudisium" enctype="multipart/form-data" method="POST" action="javascript:void(0)">
                @csrf
                <input type="hidden" name="nim" id="nim-upload-foto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadFotoYudisiumModalLabel">Upload Foto Yudisium</span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <img id="preview-foto-yudisium" src="{{ asset('assets/images/mahasiswa/ijazah/pas-foto-3x4.png') }}" alt="Preview Foto" class="rounded mb-2" style="width:120px; height:160px; object-fit:contain;">
                            <div id="nama-upload-foto" class="fw-bold mt-2"></div>
                            <small class="text-muted">Ukuran foto: 3x4</small>
                        </div>
                        <div class="mb-3">
                            <label for="foto-yudisium" class="form-label">Pilih Foto Yudisium</label>
                            <input type="file" class="form-control" id="foto-yudisium" name="foto_yudisium" accept="image/*" required>
                            <small class="text-muted">Format: JPG, JPEG. Maksimal 5MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="btn-submit">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('admin.akademik.transkrip-ijazah.modal.modal-ijazah')
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>

    <script>
        $(function () {
            const baseUrl = {!! json_encode(url('/')) !!};
            const title = "{{strtolower($title2)}}";
            const page = '/'.concat("admin/akademik/yudisium/").concat(title);
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
                        searchable: false,
                        orderable: false,
                        targets: 2,
                        render: function render(data, type, full, meta) {
                            // Render NIM, Nama, Photo, and Checkbox
                            var photoUrl = full.fotoYudisium ? `{{ asset("assets/images/mahasiswa/foto-yudisium/") }}/${full.fotoYudisium}` : '{{ asset("assets/images/mahasiswa/ijazah/pas-foto-3x4.png") }}';
                            var photoHtml = `
                                <img src="${photoUrl}" alt="Photo" class="rounded me-2" style="width:40px; height:53px; object-fit:cover;">
                            `;
                            var uploadBtn = '';
                            if (!full.fotoYudisium) {
                                uploadBtn = `
                                    <button class="btn btn-sm btn-warning ms-2 upload-foto-yudisium" 
                                        data-nim="${full.nim}" 
                                        data-nama="${full.namaMahasiswa}" 
                                        title="Upload Foto Yudisium"
                                        data-bs-toggle="modal"
                                        data-bs-target="#uploadFotoYudisiumModal">
                                        <i class="fa fa-upload"></i>
                                    </button>
                                `;
                            }
                            return `
                                <div class="d-flex align-items-center">
                                    ${photoHtml}
                                    <div>
                                        <div><strong>${full.nim}</strong></div>
                                        <div>${full.namaMahasiswa}</div>
                                    </div>
                                    ${uploadBtn}
                                </div>
                            `;
                        }
                    },
                    {
                        // Actions
                        targets: -1,
                        title: 'Actions',
                        searchable: false,
                        orderable: false,
                        render: function render(data, type, full, meta) {
                            if (full['tanggalPengesahan']) {
                                return "Disahkan pada tanggal " + full['tanggalPengesahan'];
                            }else{
                                return (
                                    '<div class="d-inline-block text-nowrap">' +
                                    '<button class="btn btn-sm btn-icon cetak-transkrip-record text-info" title="Cetak Transkrip Nilai" data-nim="' + full['nimEnkripsi'] +
                                    '" data-nama="' + full['namaMahasiswa'] +
                                    '" data-bs-toggle="modal" data-original-title="Cetak Transkrip" data-bs-target="#cetakTranskripModal"><i class="fa fa-file-text"></i></button> | ' +
                                    '<button class="btn btn-sm btn-icon text-info cetak-ijazah-record" title="Cetak Ijazah" data-nim="' + full['nimEnkripsi'] +
                                    '" data-nama="' + full['namaMahasiswa'] +
                                    '" data-bs-toggle="modal" data-original-title="Cetak Ijazah" data-bs-target="#cetakIjazahModal"><i class="fa fa-print"></i></button> | ' +
                                    '<button class="btn btn-sm btn-icon edit-record text-primary" data-id="' + full['id'] +
                                    '" data-bs-toggle="modal" data-original-title="Edit" data-bs-target="#editModal"><i class="fa fa-pencil"></i></button>' +
                                    '<button class="btn btn-sm btn-icon delete-record text-primary" data-id="' + full['id'] +
                                    '"><i class="fa fa-trash"></i></button>' +
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

            //Cetak transkrip Record
            $(document).on('click', '.cetak-transkrip-record', function () {
                const nim = $(this).data('nim');
                const nama = $(this).data('nama');
                $('#nim-transkrip').val(nim);
                $('#nama-transkrip').text(nama);
            });

            //modal upload foto yudisium
            $(document).on('click', '.upload-foto-yudisium', function () {
                const nim = $(this).data('nim');
                const nama = $(this).data('nama');
                $('#nim-upload-foto').val(nim);
                $('#nama-upload-foto').text(nama);
            });

            //form upload foto yudisium
            $('#formUploadFotoYudisium').on('submit', function(e) {
                e.preventDefault();
                var btnSubmit = $(this).find('#btn-submit');
                var oldBtnHtml = btnSubmit.html();
                btnSubmit.prop('disabled', true);
                btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');

                var formData = new FormData(this);

                $.ajax({
                    url: ''.concat(baseUrl).concat(page, '/upload-foto-yudisium'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                    },
                    success: function(message) {
                        dt.draw();
                        $("#uploadFotoYudisiumModal").modal('hide');
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
                    error: function(err) {
                        swal({
                            title: err.responseText || 'Upload Failed.',
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

            $('#formEdit').on('submit', function(e) {
                e.preventDefault();
                var btnSubmit = $('#btn-submit-edit');
                var oldBtnHtml = btnSubmit.html();
                btnSubmit.prop('disabled', true);
                btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');
                $('#mhs-selected option').prop('selected', true);

                $.ajax({
                    data: $('#formEdit').serialize(),
                    url: ''.concat(baseUrl).concat(page),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                    },
                    success: function success(message) {
                        dt.draw();
                        $("#editModal").modal('hide');
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

            $(document).on('click', '#add-mhs', function() {
                $('#mhs-available option:selected').each(function() {
                    $('#mhs-selected').append($(this).clone());
                    $(this).remove();
                });
            });

            $(document).on('click', '#remove-mhs', function() {
                $('#mhs-selected option:selected').each(function() {
                    $('#mhs-available').append($(this).clone());
                    $(this).remove();
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
                        swal({
                            title: 'Failed!',
                            text: _error.message,
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        // console.log('====================================');
                        // console.log(_error.error);
                        // console.log('====================================');
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
