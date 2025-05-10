@extends('layouts.master')
@section('title', 'Berkas Mahasiswa')

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
    <li class="breadcrumb-item">Berkas</li>
    <li class="breadcrumb-item active">Berkas Mahasiswa</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" id="my-table">
                            <table class="display" id="mhs-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>KK</th>
                                        <th>KTP</th>
                                        <th>Akte</th>
                                        <th>Ijazah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mhs as $row)
                                    <tr>
                                        <td>{{++$fake_id}}</td>
                                        <td>{{ $row->nimMahasiswa }}</td>
                                        <td><a href="{{ URL::to('admin/berkas/mahasiswa/'. $row->nimEnkripsi) }}">{{ $row->nama }}</a></td>
                                        <td>{!! $row->kk ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</td>
                                        <td>{!! $row->ktp ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</td>
                                        <td></td>
                                        <td>
                                            Depan : {!! $row->ijazah_depan ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!} <br>
                                            Belakang : {!! $row->ijazah_belakang ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}
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
            $("#mhs-table").DataTable();
        });

    </script>
@endsection
