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
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <a href="{{url('admin/masterdata/jadwal-harian')}}" class="btn btn-primary mb-3"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <h4>List Mahasiswa - {{$jadwal->kode_jadwal}} {{$jadwal->nama_matkul}}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" id="tbl_mhs">
                            <thead>
                                <tr>
                                    <td>No.</td>
                                    <td>NIM</td>
                                    <td>Nama</td>
                                    <td>Program Studi</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i=1;
                                @endphp
                                @foreach($krs as $row)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$row->nim}}</td>
                                        <td>{{$row->nama}}</td>
                                        <td>{{$row->nama_prodi}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        $("#tbl_mhs").DataTable();
    </script>
@endsection
