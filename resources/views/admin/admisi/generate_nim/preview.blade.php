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
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item active">Generate NIM</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="alert alert-primary inverse alert-dismissible fade show" role="alert"><i class="icon-help-alt"></i>
                    Pada halaman ini user dapat melihat list mahasiswa sebelum di ubah menjadi NIM. User dapat melakukan edit nim, delete list mahasiswa dan menggenerate ulang NIM untuk mengurutkan NIM kembali berdasarkan NIM terakhir yang tersimpan dan diurutkan otomatis berdasarkan nama
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button>
                </div>
                <div class="alert alert-warning inverse alert-dismissible fade show" role="alert"><i class="fa fa-exclamation-triangle"></i>
                    Hati-hati ketika mengubah nim karena dapat menimbulkan duplikasi NIM, pastikan NIM yang diubah belum terdaftar
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button>
                </div>
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h4>Preview Generate Mahasiswa</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{url('admin/admisi/generate_nim/save_temp')}}" method="POST">
                        @csrf
                        <div class="col-md-12 text-end mb-3">
                            <a href="{{url('admin/admisi/generate_nim/regenerate')}}" class="btn btn-info float-start">Re-generate Preview NIM</a>
                            <a href="{{url('admin/admisi/generate_nim/generate')}}" class="btn btn-success">Generate NIM</a>

                        </div>
                        <div class="table-responsive" id="my-table">

                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>No. Pendaftaran</th>
                                        <th>Prodi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp
                                    @foreach($mhs as $row)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td><input type="hidden" name="id_mhs[]" value="{{$row->id}}"><input type="text" name="nim[]" value="{{$row->nim}}"></td>
                                            <td>{{$row->nama}}</td>
                                            <td>{{$row->nopen}}</td>
                                            <td>{{$row->nama_prodi}}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="javascript:void(0)" class="btn btn-danger delete-record" data-id="{{$row->id}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <button class="btn btn-primary col-md-12 mb-3">Simpan Perubahan</button>
                            </div>
                        </div>
                        </form>
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
         $(document).ready(function(){
            const baseUrl = {!! json_encode(url('/')) !!};
            const title = "generate_nim";
            $("#basic-1").DataTable({
                "lengthChange": false,
                "paging": false
            });
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
                    url: ''.concat(baseUrl).concat('/admin/admisi/generate_nim/delete_temp/').concat(id),
                    data:{
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function success() {
                        window.location.reload();
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
