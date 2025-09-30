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
    <li class="breadcrumb-item">Ruang</li>
    <li class="breadcrumb-item active">{{ $ruang->nama_ruang }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="barang-ruang-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Label</th>
                                        <th>Nama</th>
                                        <th>Spesifikasi</th>
                                        <th>Estimasi Pemakaian</th>
                                        <th>Durasi Pemakaian</th>
                                        <th>Anggaran</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Tanggal Pembelian</th>
                                        <th>Pemeriksaan Terakhir</th>
                                        <th>Inventaris Lama</th>
                                        <th>Inventaris Baru</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($barang as $index => $row)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $row->label }}</td>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->spesifikasi }}</td>
                                            <td>{{ $row->estimasi_pemakaian }}</td>
                                            <td>{{ $row->durasi_pemakaian }}</td>
                                            <td>{{ $row->anggaran }}</td>
                                            <td>{{ $row->jumlah }}</td>
                                            <td>{{ $row->harga }}</td>
                                            <td>{{ $row->tanggal_pembelian }}</td>
                                            <td>{{ $row->pemeriksaan_terakhir }}</td>
                                            <td>{{ $row->inventaris_lama }}</td>
                                            <td>{{ $row->inventaris_baru }}</td>
                                            <td>{{ $row->keterangan }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="15" class="text-center">Tidak ada barang di ruangan ini.</td>
                                        </tr>
                                    @endforelse
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
        $(function () {

        });

    </script>
@endsection
