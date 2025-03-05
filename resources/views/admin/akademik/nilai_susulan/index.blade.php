@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
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
                        Nilai Susulan
                    </div>
                    <div class="card-body">
                        <form method="POST" action="javascript:void(0)" id="form_nilai">
                            <div class="row g-3" >
                                <div class="col-md-6">

                                    <label for="TA">TA</label>
                                    <select name="ta" id="ta" class="col-md-6 mb-3 form-control">
                                        @foreach($ta as $row)
                                            <option value="{{$row->id}}">{{$row->keterangan}}</option>
                                        @endforeach
                                    </select>
                                    <label for="mhs">Mahasiswa</label>
                                    <select name="mhs" id="mhs" class="col-md-6 mb-3 form-control js-example-basic-single">
                                        @foreach($mhs as $row)
                                            <option value="{{$row->nim}}">{{$row->nim}} - {{$row->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" value="Tampilkan" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
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
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>

    <script>

        const baseUrl = {!! json_encode(url('/')) !!};
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })
            $("#myTable1").DataTable({
                responsive: true
            })

        })
    </script>
@endsection
