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

                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="my-table">
                            <table class="display" id="pegawai-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NIY-NIDN-NUPTK</th>
                                        <th>Nama Pegawai</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pegawai as $row)
                                    <tr>
                                        <td>{{++$fake_id}}</td>
                                        <td>{{$row->npp}} - {{$row->nidn}} - {{$row->nuptk}}</td>
                                        <td>{{$row->nama_lengkap}}</td>
                                        {{-- <td>{{($row['nakhir']+$row['ntambahan'])}}</td> --}}
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <a href="{{URL::to('dosen/perwalian/' . $row->id)}}" title="Detail Mahasiswa Perwalian" id="edit_pegawai" class="btn edit-record btn-primary"><i class="fa fa-book"></i></a>
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
