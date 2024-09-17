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
    <li class="breadcrumb-item">Berkas</li>
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
                                        <th>KK</th>
                                        <th>KTP</th>
                                        <th>Ijazah</th>
                                        <th>Serdik</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pegawai as $row)
                                    <tr>
                                        <td>{{++$fake_id}}</td>
                                        <td>{{$row->npp}} - {{$row->nidnDosen}} - {{$row->nuptk}}</td>
                                        {{-- <td><a href="{{URL::to('admin/berkas/dosen/' . $row->id)}}">{{$row->nama_lengkap}}</a></td> --}}
                                        <td><a href="{{ URL::to('admin/berkas/dosen/'. $row->nidnDosen) }}">{{$row->nama_lengkap}}</a></td>
                                        <td>{!! $row->kk ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</td>
                                        <td>{!! $row->ktp ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</td>
                                        <td>
                                            S1 : {!! $row->ijazah_s1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}<br>
                                            S2 : {!! $row->ijazah_s2 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}<br>
                                            S3 : {!! $row->ijazah_s3 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}
                                        </td>
                                        <td>
                                            AA.pekerti : {!! $row->serdik_aa_pekerti ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}<br>
                                            AA : {!! $row->serdik_aa ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}<br>
                                            Lektor : {!! $row->serdik_lektor ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}<br>
                                            L.KGB : {!! $row->serdik_kepala_guru_besar ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}
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
