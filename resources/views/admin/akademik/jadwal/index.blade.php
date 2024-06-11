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
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="myTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Kode Matakuliah</th>
                                        <th>Nama Matakuliah</th>
                                        <th>SKS</th>
                                        <th>Smt.</th>
                                        <th>Status Mata Kuliah</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mk as $mk)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $mk['kode_matkul'] }}</td>
                                            <td>{{ $mk['nama_matkul'] }}</td>
                                            <td>{{ $mk['sks_teori'] }} T / {{ $mk['sks_praktek'] }} P</td>
                                            <td>{{ $mk['semester'] }}</td>
                                            <td>{{ $mk['status_mk'] }}</td>
                                            <td>
                                                <a href="{{ url('admin/masterdata/jadwal/create/'. $mk['id']) }}" class="btn btn-sm btn-icon edit-record text-primary">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
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
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })
        })
    </script>
@endsection
