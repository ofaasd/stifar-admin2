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
    <li class="breadcrumb-item">Mahasiswa</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                      <a href="/mahasiswa/tambah-data">
                        <i class="fa fa-plus"></i>
                        Tambah Data Mahasiswa
                      </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="myTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>HP</th>
                                        <th>Email</th>
                                        <th>Status Mahasiswa</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                @foreach($mhs as $row_mhs)
                                  <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $row_mhs['nim'] }}</td>
                                    <td>{{ $row_mhs['nama'] }}</td>
                                    <td>{{ $row_mhs['hp'] }}</td>
                                    <td>{{ $row_mhs['email'] }}</td>
                                    <td>{{ $row_mhs['status'] == 1? 'Aktif':'Tidak Aktif' }}</td>
                                    <td>
                                        <a href="/mahasiswa/detail/{{ $row_mhs['nim'] }}" class="btn btn-warning btn-xs">
                                          <i class="fa fa-edit"></i>
                                          Edit/Lihat
                                        </a>
                                    </td>
                                  </tr>
                                @endforeach
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
