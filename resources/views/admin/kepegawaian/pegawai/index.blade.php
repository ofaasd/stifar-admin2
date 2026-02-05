@extends('layouts.master')
@section('title', 'Data Pegawai')

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
    <li class="breadcrumb-item">Kepegawaian</li>
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
                                        <th>NIY-NIDN-NUPTK</th>
                                        <th>Nama Pegawai</th>
                                        <th>Status Homebase</th>
                                        <th>J.Fung</th>
                                        <th>J.Struk</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pegawai as $row)
                                    <tr>
                                        <td>{{++$fake_id}}</td>
                                        <td>{{$row->npp}} - {{$row->nidn}} - {{$row->nuptk}}</td>
                                        <td>{{$row->nama_lengkap}}</td>
                                        <td>{{$homebase[$row->homebase]}}</td>
                                        <!-- <td>{{$row->pendTerakhir}}</td>
                                        <td>{{$row->golongan}}</td> -->
                                        <td>{{$jabatan_fungsional[$row->id_jabfung] ?? ''}}</td>
                                        <td>{{$jabatan_struktural[$row->id_jabstruk] ?? ''}}</td>
                                        {{-- <td>{{($row['nakhir']+$row['ntambahan'])}}</td> --}}
                                        <td>
                                            <div class="d-inline-block text-nowrap">
                                                <div class="btn-group">
                                                    <a href="{{URL::to('admin/kepegawaian/pegawai/' . $row->id .'/edit')}}" title="edit Pegawai" id="edit_pegawai" class="btn btn-sm btn-icon btn-primary"><i class="fa fa-pencil"></i></a>
                                                    <button class="btn btn-sm btn-icon delete-record btn-danger" data-id="{{$row->id}}"><i class="fa fa-trash"></i></button>
                                                </div>
                                                
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
