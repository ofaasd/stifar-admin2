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
                
                    
                @if(!$status_bayar)
                    <div class="card mb-4">
                        <div class="card-header bg-primary">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><b>{{$title}}</b></h5>
                                </div>

                            </div>
                        </div>
                        <div class="card-body" style="padding:0">
                            <div class="mt-4">
                                <div class="mt-4 col-md-6 ">
                                    <div class="mt-2"></div>
                                    <table class="table table-hover table-border-horizontal m-4" id="tablekrs">
                                        <thead>
                                            {{-- <th>No.</th> --}}
                                            <th>Tunggakan Tagihan</th>
                                            <th>Nominal</th>
                                        </thead>
                                        <tbody>
                                            <td>Tagihan Bulanan</td>
                                            <td>Rp. {{number_format(($new_total_tagihan-$tagihan_total->pembayaran),0,",",".")}}</td>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total</th>
                                                <th>Rp. {{number_format(($new_total_tagihan-$tagihan_total->pembayaran),0,",",".")}}</th>
                                            </tr>
                                            {{-- <tr class="{{($tagihan->status==0) ? "bg-danger text-light" : "bg-success text-light"}}">
                                                <th class="text-white">Total Bayar</th>
                                                <th class="text-white">Rp. {{number_format($tagihan->total_bayar,0,",",".")}}</th>
                                            </tr> --}}
                                            <tr class="{{(!empty($tagihan->status) && $tagihan->status==0) ? "bg-danger text-light" : "bg-success text-light"}}">
                                                <th class="text-white">Status</th>
                                                <th class="text-white">{{(!empty($tagihan->status) && $tagihan->status==0) ? "Belum Lunas" : "Lunas"}}</th>
                                            </tr>
                                            {{-- <tr class="{{($tagihan->status==0) ? "bg-danger text-light" : "bg-success text-light"}}">
                                                <th class="text-white">Batas Waktu</th>
                                                <th class="text-white">{{date('d-m-Y',strtotime($tagihan->batas_waktu))}}</th>
                                            </tr> --}}
                                        </tfoot>
                                    </table>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                
                @else
                    <div class="alert alert-success text-center"><h5>Tidak Ada Tagihan Untuk Anda Saat Ini </h5></div>
                @endif
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
