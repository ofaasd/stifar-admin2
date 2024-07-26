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
                        Keuangan Mahasiswa
                    </div>
                    <div class="card-body">
                        @if($jumlah_keuangan == 0)
                        <div class="alert alert-warning">Data Keuangan Mahasiswa TA {{$ta->kode_ta}} Belum tersedia klik tombol di bawah untuk generate keuangan mahasiswa</div>
                        <a href="{{URL::to('mahasiswa/tambah-data')}}" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            Generate keuangan Mahasiswa
                          </a>
                        @else
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
                                        <a href="{{ URL::to('/mahasiswa/detail/' . $row_mhs['nim']) }}" class="btn btn-warning btn-xs">
                                          <i class="fa fa-eye"></i>

                                        </a>
                                        <a href="{{ URL::to('/mahasiswa/' . $row_mhs['nim']) . "/edit/" }}" class="btn btn-info btn-xs">
                                          <i class="fa fa-edit"></i>

                                        </a>
                                    </td>
                                  </tr>
                                @endforeach
                            </table>
                        </div>
                        @endif
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
