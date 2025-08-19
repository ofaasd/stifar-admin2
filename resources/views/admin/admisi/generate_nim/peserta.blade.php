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

                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h4>Calon Mahasiswa {{$gelombang->nama_gel}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form method="GET">
                                    <input type="hidden" name="id_gelombang" value="{{$id}}">
                                    <select name="status" id="status" class="form-control mb-3">
                                        <option value="0" {{($status == 0)?"selected":""}}>Tampilkan List Mahasiswa yang Sudah Membayar Registrasi Ulang</option>
                                        <option value="1" {{($status == 1)?"selected":""}}>Tampilkan List Mahasiswa yang Sudah Lolos / Blm Bayar Registrasi Ulang</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Fiter</button>
                                </form>
                            </div>
                        </div>
                        <form action="{{url('admin/admisi/generate_nim/generate_preview')}}" method="POST">
                        @csrf
                        <div class="table-responsive" id="my-table">
                            <input type="hidden" name="id_gelombang" value="{{$id}}">
                            <input type="hidden" name="status" value="{{$status ?? 0}}">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>No. Pendaftaran</th>
                                        <th>Prodi</th>
                                        <th>Lulus</th>
                                        <th>Registrasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp
                                    @foreach($peserta as $row)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$row->nama}}</td>
                                            <td>{{$row->nopen}}</td>
                                            <td>{{$prod[$row->pilihan1]}}</td>
                                            <td>{!!($row->is_lolos == 0)?'<div class="col-md-12 text-center"><span class="text-center text-danger"><i class="fa fa-minus-circle fa-lg"></i></span></div>':'<div class="col-md-12 text-center"><span class="text-success text-center"><i class="fa fa-check-circle fa-lg"></i></span></div>'!!}</td>
                                            <td>{!!($row->registrasi_awal == 0)?'<div class="col-md-12 text-center"><span class="text-center text-danger"><i class="fa fa-minus-circle fa-lg"></i></span></div>':'<div class="col-md-12 text-center"><span class="text-success text-center"><i class="fa fa-check-circle fa-lg"></i></span></div>'!!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <button class="btn btn-success col-md-12">Preview</button>
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
            $("#basic-1").DataTable({
                "lengthChange": false,
                "paging": false
            });
         });
    </script>
@endsection
