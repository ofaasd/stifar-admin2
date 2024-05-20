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
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item active">Surat Pengumuman</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="my-table">
                            <table class="display" id="pengumuman-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>No. Gel.</th>
                                        <th>Nama Gelombang</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Jumlah Pendaftar</th>
                                        <th>Jumlah Verifikasi</th>
                                        <th>Jumlah Pembayaran</th>
                                        <th>Jumlah Diterima</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp
                                    @foreach($gelombang as $row)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$row->no_gel}}</td>
                                        <td>{{$row->nama_gel}}</td>
                                        <td>{{date('d-m-Y', strtotime($row->tgl_mulai))}}</td>
                                        <td>{{date('d-m-Y', strtotime($row->tgl_akhir))}}</td>
                                        <td>{{$jumlah_pendaftar[$row->id]}}</td>
                                        <td>{{$jumlah_verifikasi[$row->id]}}</td>
                                        <td>{{$jumlah_bayar[$row->id]}}</td>
                                        <td>{{$jumlah_diterima[$row->id]}}</td>
                                        <td><a href="{{URL::to('admin/admisi/pengumuman/'. $row->id .'/peserta')}}" title="Lihat Pendaftar" class="btn btn-primary btn-xs add_nilai" data-id="{{$row['id']}}" ><i class="fa fa-eye"></i></a> <a href="#" title="Delete Nilai Tambahan" class="btn btn-info btn-xs delete-record" data-id="{{$row['id']}}"><i class="fa fa-print"></i></a></td>
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
            $("#pengumuman-table").DataTable();
         });
    </script>
@endsection
