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
    <li class="breadcrumb-item">Kepawaian</li>
    <li class="breadcrumb-item active">Data Pegawai</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <a href="{{URL::to('admin/kepegawaian/pegawai/create')}}" class="btn btn-primary">+ Tambah Pegawai</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="my-table">
                            <table class="display" id="pegawai-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NIY - NIDN</th>
                                        <th>Nama Pegawai</th>
                                        <th>Status Homebase</th>
                                        <th>Golongan</th>
                                        <th>jabatan Fungsional</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pegawai as $row)
                                    <tr>
                                        <td>{{++$fake_id}}</td>
                                        <td>{{$row->npp}} - {{$row->nidn}}</td>
                                        <td>{{$row->nama_lengkap}}</td>
                                        <td>{{$homebase[$row->homebase]}}</td>
                                        <td>{{$row->golongan}}</td>
                                        <td>{{($row['nakhir']+$row['ntambahan'])}}</td>
                                        <td>
                                            <div class="d-inline-block text-nowrap">
                                                <a href="{{URL::to('admin/kepegawaian/pegawai/' . $row->id .'/edit')}}" title="edit Pegawai" id="edit_pegawai" class="btn btn-sm btn-icon edit-record text-primary"><i class="fa fa-pencil"></i></a>
                                                <button class="btn btn-sm btn-icon delete-record text-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
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
        $(document).ready(function(){
            $("#pegawai-table").DataTable();
        });

    </script>
@endsection
