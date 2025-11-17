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
                            <div class="mt-4">
                                <div class="mt-4 col-md-8">
                                    <div class="mt-2"></div>
                                    <table class="table table-hover table-border-horizontal m-4" id="tablekrs">
                                        <thead>
                                            {{-- <th>No.</th> --}}
                                            <th>Tanggal</th>
                                            {{-- <th>Jenis Pembayaran / Keterangan</th> --}}
                                            <th>Jumlah</th>
                                        </thead>
                                        <tbody>
                                            @php $total_pembayaran = 0; @endphp
                                            @foreach($pembayaran as $row_pembayaran)
                                            <tr>
                                            <td>{{date('d-m-Y',strtotime($row_pembayaran->tanggal_bayar))}}</td>
                                            {{-- <td>{{$list_jenis[$row_pembayaran->jenis_keuangan] ?? 'Akumulasi Pembayaran Sebelumnya'}}</td> --}}
                                            <td>
                                                @if(!empty($row_pembayaran->keterangan))
                                                    {{$row_pembayaran->keterangan}}
                                                @else
                                                    @if($mhs->id_program_studi == '1' || $mhs->id_program_studi == '2')
                                                        Biaya Kuliah Per Bulan
                                                    @else
                                                        {{$list_jenis[$row_pembayaran->jenis_keuangan] ?? 'Angsuran Pembayaran'}}   
                                                    @endif
                                                @endif
                                            </td>
                                            <td>Rp. {{number_format($row_pembayaran->jumlah,0,",",".")}}</td>
                                            @php $total_pembayaran += $row_pembayaran->jumlah; @endphp
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">Total Pembayaran</th>
                                                <th>Rp. {{number_format($total_pembayaran,0,",",".")}}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                
                                </div>
                            </div>
                        </div>
                    </div>
               </div>     
            <!-- Zero Configuration Ends-->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
