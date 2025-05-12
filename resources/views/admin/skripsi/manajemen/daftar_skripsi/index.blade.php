@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
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

    <div class="container-fluid">

        <div class="row">
            <div class="col">
                <div class="card widget-1">
                    <div class="card-body">
                        <div class="widget-content">
                            <div class="widget-round warning">
                                <div class="bg-round">
                                    <svg class="svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#rate') }}"> </use>
                                    </svg>
                                    <svg class="half-circle svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4>255 </h4><span class="f-light">Mahasiswa Skripsi</span>
                            </div>
                        </div>
                        <div class="font-warning f-w-500"><i class="icon-arrow-down icon-rotate me-1"></i><span>-20%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card widget-1">
                    <div class="card-body">
                        <div class="widget-content">
                            <div class="widget-round warning">
                                <div class="bg-round">
                                    <svg class="svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#rate') }}"> </use>
                                    </svg>
                                    <svg class="half-circle svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4>{{ $pembimbing }} </h4><span class="f-light">Dosen Pembimbing</span>
                            </div>
                        </div>
                        <div class="font-warning f-w-500"><i class="icon-arrow-down icon-rotate me-1"></i><span>-20%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card widget-1">
                    <div class="card-body">
                        <div class="widget-content">
                            <div class="widget-round warning">
                                <div class="bg-round">
                                    <svg class="svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#rate') }}"> </use>
                                    </svg>
                                    <svg class="half-circle svg-fill">
                                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4>{{ $judulSkripsi }} </h4><span class="f-light">Topik Skripsi</span>
                            </div>
                        </div>
                        <div class="font-warning f-w-500"><i class="icon-arrow-down icon-rotate me-1"></i><span>-20%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display table-basic" >
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Prodi</th>
                            <th>minimal SKS</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($prodi as $prod)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ e($prod->nama_prodi) }}</td>
                            <td>{{ e($prod->sks) }} 
                                <button type="button"
                                class="btn p-0 border-0 bg-transparent openSKSModal"
                                data-bs-toggle="modal"
                                data-bs-target="#formSKS"
                                data-id="{{ $prod->id }}"
                                data-sks="{{ $prod->sks }}">
                                <i class="icon-pencil-alt text-secondary" style="cursor: pointer;"></i>
                            </button>
                              
                            </td>
                            <td>
                                <ul class="action">
                                    <li class="detail" data-id="{{ $prod->id }}">
                                        <a href="{{ route('admin.skripsi.manajemen.detail', $prod->id) }}">
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
    </div>

    <div class="modal fade" id="formSKS" tabindex="-1" role="dialog" aria-labelledby="formSKS" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="row g-3 needs-validation custom-input" id="formDosbim" method="POST"
                    action="{{ Route('admin.skripsi.manajemen.daftar.sks') }}">
                    @csrf
                    <input class="form-control" name="id_prodi" id="id_prodi" type="hidden" required>
                
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
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        $(function() {
            $('.openSKSModal').on('click', function () {
            var prodiId = $(this).data('id');
            var sks = $(this).data('sks');

            $('#id_prodi').val(prodiId);
            $('#kuotaSKS').val(sks);
        });
            $('.detail').on('click', function () {
                // Ambil data-id dari elemen yang diklik
                var id = $(this).data('id');
                
                // Simpan data-id ke localStorage
                localStorage.setItem('idProdi', id);

                console.log('ID disimpan ke localStorage:', id);
            });
            @if (session('success'))
                swal("success", "{{ session('success') }}", "success");
            @endif

            @if (session('error'))
                swal("error", "{{ session('error') }}", "error");
            @endif
        });
    </script>
@endsection
