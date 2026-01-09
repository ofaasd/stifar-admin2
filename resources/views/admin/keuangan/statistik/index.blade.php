@extends('layouts.master')
@section('title', 'Gedung')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<style>

</style>
@endsection 

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Keuangan</li>
    <li class="breadcrumb-item active">Lapor Bayar</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 project-list">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                                @foreach($prodi as $prod)
                                    <li class="nav-item"><a href="{{URL::to('admin/keuangan/statistik/' . $prod->id)}}" class="nav-link {{($id==$prod->id)?"active":""}}" style="font-size:10pt;"><i data-feather="info"></i>{{$nama[$prod->id]}} </a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <div class="alert alert-warning">
                            <i class="fa fa-warning"></i> Data Dibawah merupakan data statistik keuangan mahasiswa global yang sudah di inputkan pada halaman total pembayaran
                    </div>
                    <div class="card-body" style="overflow-x:scroll">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{url('admin/keuangan/statistik/update_total_tagihan')}}" class="btn btn-primary mb-3">Update Total Tagihan</a>
                                    <a href="{{url('admin/keuangan/statistik/cetak/' . $id . '/' . request()->get('gelombang'))}}" class="btn btn-primary mb-3">Cetak</a>
                                </div>
                                <div class="col-md-6 text-right">
                                    @if($get_gelombang_all)
                                        <form method="GET" action="{{url('admin/keuangan/statistik/' . $id)}}" >
                                            <div class="d-flex justify-content-end">
                                                <div class="input-group">
                                                    <select name="gelombang" class="form-control">
                                                        <option value="0">--Semua Gelombang--</option>
                                                        @foreach($get_gelombang_all as $gel)
                                                            <option value="{{$gel->id}}" {{(request()->get('gelombang') == $gel->id)?"selected":""}}>Apoteker angkatan {{$gel->no_gel}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-primary">Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            @csrf
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gelombang</th>
                                        <th>Nopen</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Total Tagihan</th>
                                        <th>Total Bayar</th>
                                        <th>Sisa Bayar</th>
                                        <th>Pembayaran Terakhir</th>
                                        <th>Status</th>
                                    </tr>
                                    {{-- <th>Action</th> --}}
                                </thead>
                                <tbody>
                                    @foreach($data as $row)
                                    <tr>
                                        <td>
                                            {{$no++}}    
                                        </td>
                                        <td>{{$row['gelombang']}}</td>
                                        <td>{{$row['nopen']}}</td>
                                        <td>{{$row['nim']}}</td>
                                        <td>{{$row['nama']}}</td>
                                        <td>{{$row['total_bayar']}}</td>
                                        <td>{{$row['pembayaran']}}</td>
                                        <td>{{$row['sisa_bayar']}}</td>
                                        <td>{{$row['last_pay']}}</td>
                                        <td>{{$row['status']}}</td>
                                        
                                        {{-- <td></td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
    <script>
        $(document).ready(function() {
            const baseUrl = {!! json_encode(url('/')) !!};
            const dataTable = $('.table').DataTable({
                "paging": false,
            });
        });
    </script>

@endsection