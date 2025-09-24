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
    <li class="breadcrumb-item">Keuangan</li>
    <li class="breadcrumb-item active">Setting Pertemuan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                @if($SettingKeuangan->count() == 0)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Setting Keuangan pada tahun ajaran ini belum dilakukan
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button>
                </div>
                @endif
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h4>Setting Keuangan</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="my-table">
                            <form method="POST" action="{{url('admin/keuangan/setting_keuangan')}}">
                            @csrf
                            <table class="table table-stripped" id="pengumuman-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Program Studi</th>
                                        @foreach($jenis as $jen)
                                        <th>{{$jen->nama}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp
                                    @foreach($prodi as $pro)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$pro->nama_prodi}}</td>
                                            @foreach($jenis as $jen)
                                                <td>
                                                    <input type="hidden" name="jenis[]" value="{{$jen->id}}">
                                                    <input type="hidden" name="prodi[]" value="{{$pro->id}}">
                                                    <input type="number" name="setting_keuangan[]" value="{{$setting_keuangan[$pro->id][$jen->id]}}" class="form-control">
                                                    </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-12">
                                <input type="submit" name="simpan" value="simpan" class="btn btn-primary col-md-12">
                            </div>
                            <form method="POST" action="{{url('admin/keuangan/setting_keuangan')}}">
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
         $(document).ready(function(){
            // $("#pengumuman-table").DataTable();
         });
    </script>
@endsection
