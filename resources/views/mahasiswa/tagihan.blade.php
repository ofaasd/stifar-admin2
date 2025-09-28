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
    <li class="breadcrumb-item">Keuangan</li>
    <li class="breadcrumb-item active">Tagihan</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><b>{{$title}}</b></h5>
                            </div>

                        </div>
                    </div>
                    <div class="card-body" style="padding:0">
                        @if(!empty($list_total))
                            <div class="mt-4">
                                <div class="mt-4 col-md-6 ">
                                    <div class="mt-2"></div>
                                    <table class="table table-hover table-border-horizontal m-4" id="tablekrs">
                                        <thead>
                                            {{-- <th>No.</th> --}}
                                            <th>Jenis Tagihan</th>
                                            <th>Nominal</th>
                                        </thead>
                                        <tbody>
                                            @php $i=0; @endphp
                                            @foreach($jenis as $row)
                                                @php $i++;@endphp
                                                <tr>
                                                    {{-- <td>{{ $i }}</td> --}}
                                                    <td>{{ $row->nama }}</td>
                                                    <td>Rp. {{number_format($list_total[$row->id],0,",",".") }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total</th>
                                                <th>Rp. {{number_format($tagihan->total,0,",",".")}}</th>
                                            </tr>
                                            <tr class="{{($tagihan->status==0) ? "bg-danger text-light" : "bg-success text-light"}}">
                                                <th class="text-white">Total Bayar</th>
                                                <th class="text-white">Rp. {{number_format($tagihan->total_bayar,0,",",".")}}</th>
                                            <tr class="{{($tagihan->status==0) ? "bg-danger text-light" : "bg-success text-light"}}">
                                                <th class="text-white">Status</th>
                                                <th class="text-white">{{($tagihan->status==0) ? "Belum Lunas" : "Lunas"}}</th>
                                            </tr>
                                            <tr class="{{($tagihan->status==0) ? "bg-danger text-light" : "bg-success text-light"}}">
                                                <th class="text-white">Batas Waktu</th>
                                                <th class="text-white">{{date('d-m-Y',strtotime($tagihan->batas_waktu))}}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                
                                </div>
                            </div>
                         @else
                            <div class="alert alert-primary">Belum ada tagihan tersedia</div>
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
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
