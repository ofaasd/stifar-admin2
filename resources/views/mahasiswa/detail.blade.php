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
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>
                                    Nomor Induk Mahasiswa
                                </td>
                                <td>
                                    :
                                </td>
                                <td>
                                    {{ $detail[0]['nim'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Nama Mahasiswa
                                </td>
                                <td>
                                    :
                                </td>
                                <td>
                                    {{ $detail[0]['nama'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Jenis Kelamin
                                </td>
                                <td>
                                    :
                                </td>
                                <td>
                                    {{ $detail[0]['jk'] == 1? 'Laki - Laki':'Perempuan' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Tempat, Tanggal Lahir
                                </td>
                                <td>
                                    :
                                </td>
                                <td>
                                    {{ $detail[0]['tempat_lahir'] }}, {{ $detail[0]['tgl_lahir'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Email
                                </td>
                                <td>
                                    :
                                </td>
                                <td>
                                    {{ $detail[0]['email'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Status Mahasiswa
                                </td>
                                <td>
                                    :
                                </td>
                                <td>
                                    {{ $detail[0]['status'] }}
                                </td>
                            </tr>
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
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })
        })
    </script>
@endsection
