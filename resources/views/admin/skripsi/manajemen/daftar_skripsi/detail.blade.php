@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/tagify.css') }}">
    <style>
        .widget-1 {
            background-image: none;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>{{ $title }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Mahasiswa</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')

        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="search-container">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKoor" id="tambahKoor">Tambah Koordinator</button>
                        </div>
                        <table class="display table-basic">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Npp</th>
                                    <th>Nama</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($koordinator as $koor)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ e($koor->npp) }}</td>
                                        <td>{{ e($koor->nama_lengkap) }}</td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit" data-id="{{ $koor->id }}">
                                                    <a href="{{ route('admin.skripsi.manajemen.detail', $koor->id) }}">
                                                        <i class="icon-eye"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#FormModal" id="tambahDosbim">Tambah Dosen Pembimbing</button>
                    <div class="table-responsive">
                        <table class="display" id="pembimbing-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Npp</th>
                                    <th>Nama Dosen</th>
                                    <th>Kuota</th>
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
    </div>

    <!-- Modals -->
    <div class="modal fade" id="formSKS" tabindex="-1" role="dialog" aria-labelledby="formSKS" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="row g-3 needs-validation custom-input" id="formSK" method="POST" action="{{ Route('admin.skripsi.manajemen.daftar.sks') }}">
                        @csrf
                        <div class="col-md-12 position-relative">
                            <label class="form-label" for="validationTooltip03">Total SKS</label>
                            <input class="form-control" name="jml_sks" id="kuotaSKS" type="number" required>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalKoor" tabindex="-1" role="dialog" aria-labelledby="modalKoor" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="row g-3 needs-validation custom-input" id="formKoor">
                        <input type="hidden" class="form-control" name="id_progdi" id="id_progdi">
                        <div class="col-md-12 position-relative">
                            <label class="form-label" for="nip">Pilih Dosen</label>
                            <input class="form-control" name="nip" id="nip" placeholder="Please select">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Submit form</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="FormModal" tabindex="-1" role="dialog" aria-labelledby="FormModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="row g-3 needs-validation custom-input" id="formDosbim">
                        <div class="col-md-12 position-relative">
                            <label class="form-label" for="nip">Pilih Dosen</label>
                            <input class="form-control" name="nip" id="nipDosbim" placeholder="Please select">
                        </div>
                        <div class="col-md-12 position-relative">
                            <label class="form-label" for="validationTooltip03">Kuota</label>
                            <input class="form-control" name="kuota" id="kuotaDosen" type="number" required>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select3-custom.js') }}"></script>
    <script>
        $(function() {
            const idProdi = localStorage.getItem('idProdi');
            const tagifyInstances = {};

            function initializeTagify(inputElement) {
                return new Tagify(inputElement, {
                    enforceWhitelist: true,
                    mode: "select",
                    whitelist: [],
                    dropdown: {
                        maxItems: 50,
                    }
                });
            }

            function updateTagifyWhitelist(tagify) {
                $.ajax({
                    url: '{{ route('admin.pembimbing.getNppDosen') }}',
                    method: 'GET',
                    success: function(response) {
                        const whitelistData = response.map(item => ({
                            value: `${item.npp} - ${item.nama_lengkap}`
                        }));
                        tagify.whitelist = whitelistData;
                        tagify.addTags(whitelistData.map(item => item.value));
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat mengambil data');
                        console.log(xhr.responseText);
                    }
                });
            }

            function handleModalOpen(modalId, inputId, formId) {
                const inputElement = document.getElementById(inputId);
                if (tagifyInstances[inputId]) {
                    tagifyInstances[inputId].destroy();
                }
                tagifyInstances[inputId] = initializeTagify(inputElement);
                updateTagifyWhitelist(tagifyInstances[inputId]);
                $(`#${formId}`).find('input[type="text"]').val('');
                $(`#${formId}`).find('input[type="number"]').val('');
                $(`#${modalId}`).modal('show');
            }

            function handleEditClick(button, inputId, formId, modalId) {
                const nip = button.data('id');
                if (tagifyInstances[inputId]) {
                    tagifyInstances[inputId].destroy();
                }
                $.ajax({
                    url: `{{ route('admin.pembimbing.editDosen', '') }}/${nip}`,
                    method: 'GET',
                    success: function(response) {
                        $(`#${inputId}`).val(response.nip);
                        $(`#${inputId}`).prop('disabled', true);
                        $(`#${formId}`).find('input[name="kuota"]').val(response.kuota);
                        $(`#${modalId}`).modal('show');
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr.responseJSON?.message || 'An error occurred');
                    }
                });
            }

            $('#tambahDosbim').on('click', () => handleModalOpen('FormModal', 'nipDosbim', 'formDosbim'));
            $('#tambahKoor').on('click', () => handleModalOpen('modalKoor', 'nip', 'formKoor'));

            $('.edit-btn').on('click', function() {
                const button = $(this);
                if (button.closest('table').attr('id') === 'pembimbing-table') {
                    handleEditClick(button, 'nipDosbim', 'formDosbim', 'FormModal');
                } else {
                    handleEditClick(button, 'nip', 'formKoor', 'modalKoor');
                }
            });

            function handleFormSubmit(formId, url, successMessage, errorMessage) {
                $(formId).on('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('id_progdi', idProdi);

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                swal("Success", successMessage, "success");
                                $(formId).closest('.modal').modal('hide');
                                if (formId === '#formDosbim') {
                                    $('#pembimbing-table').DataTable().ajax.reload();
                                }
                            } else {
                                swal("Error", errorMessage, "error");
                            }
                        },
                        error: function(xhr) {
                            swal("Error", xhr.responseJSON.message, "error");
                        }
                    });
                });
            }

            handleFormSubmit('#formDosbim', '{{ route('admin.pembimbing.updateKuota') }}', 'Berhasil Update Kuota Dosen', 'Gagal Update Kuota Dosen');
            handleFormSubmit('#formKoor', '{{ route('admin.skripsi.manajemen.daftar.koordinator') }}', 'Berhasil Update Koordinator', 'Gagal Update Koordinator');
            handleFormSubmit('#formSK', '{{ route('admin.skripsi.manajemen.daftar.sks') }}', 'Berhasil Update SKS', 'Gagal Update SKS');

            @if (session('success'))
                swal("success", "{{ session('success') }}", "success");
            @endif

            @if (session('error'))
                swal("error", "{{ session('error') }}", "error");
            @endif

            $('#pembimbing-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.pembimbing.listDosen') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'npp', name: 'npp' },
                    { data: 'nama', name: 'nama' },
                    { data: 'kuota', name: 'kuota' },
                    { data: 'button', name: 'button', orderable: false, searchable: false }
                ],
                language: {
                    emptyTable: "Tidak ada data dosen pembimbing yang tersedia."
                }
            });
        });
    </script>
@endsection
