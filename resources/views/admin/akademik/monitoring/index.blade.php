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
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="jadwalHarian" role="tabpanel" aria-labelledby="jadwalHarian-tab">
                                <div class="table-responsive mt-2">
                                    <div class="row">
                                        <div id="vJadwalHarian" class="mt-2">
                                            <table class="display" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Kode TA</th>
                                                        <th>Tanggal Awal</th>
                                                        <th>Tanggal Awal Kuliah</th>
                                                        <th>Tanggal Akhir</th>
                                                        <th>Status TA</th>
                                                        <th>Kuesioner</th>
                                                        <th>Keterangan</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $no = 1; @endphp
                                                    @foreach($ta as $row)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $row['kode_ta'] }}</td>
                                                            <td>{{ $row['krs_awal'] }}</td>
                                                            <td>{{ $row['krs_akhir'] }}</td>
                                                            <td>{!! ($row['status'] == 0)?"<span class='btn btn-danger'>Tidak Aktif</span>":"<span class='btn btn-success'>Aktif</span>" !!}</td>
                                                            <td>{!! ($row['krs'] == 0)?"<span class='btn btn-danger'>Tutup</span>":"<span class='btn btn-success'>Buka</span>" !!}</td>
                                                            <td>{{ $row['keterangan'] }}</td>
                                                            <td>
                                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                                    <a href="{{ url('/admin/akademik/list_soal/'.$row['id']) }}" class="btn btn-primary btn-xs">Soal Kuesioner</a>
                                                                    <a href="{{ url('/admin/akademik/list_jawaban/'.$row['id']) }}" class="btn btn-success btn-xs">Jawaban Kuesioner</a>
                                                                </div>
                                                            </td>
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
