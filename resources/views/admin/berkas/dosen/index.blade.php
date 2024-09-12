@extends('layouts.master')
@section('title', 'Berkas Dosen')

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
    <li class="breadcrumb-item active">Berkas Dosen</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" id="my-table">
                            <table class="display" id="pegawai-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NIY-NIDN-NUPTK</th>
                                        <th>Nama Pegawai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pegawai as $row)
                                    <tr>
                                        <td>{{++$fake_id}}</td>
                                        <td>{{$row->npp}} - {{$row->nidn}} - {{$row->nuptk}}</td>
                                        <td>{{$row->nama_lengkap}}</td>
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
