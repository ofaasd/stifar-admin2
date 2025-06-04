@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/echart.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item active">KHS</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="card mb-4">
                <div class="card-header card-no-border">
                  <a href="{{url('admin/akademik/khs')}}" class="btn btn-primary mb-3"><i class="fa fa-arrow-left"></i> Kembali</a>
                  <h5>Statistik KHS ({{$mhs->nama}})</h5>
                </div>
                <div class="card-body pt-0">
                  <div class="row m-0 overall-card">
                    <div class="col-xl-9 col-md-12 col-sm-7 p-0">
                      <div class="chart-right">
                        <div class="row">
                          <div class="col-xl-12">
                            <div class="card-body p-0">
                              <ul class="balance-data">
                                <li><span class="circle bg-primary"> </span><span class="f-light ms-1">Tahun Ajaran</span></li>
                              </ul>
                              <div class="current-sale-container">
                                <div id="chart-currently2"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            @foreach($tahun_ajaran_all as $tahun_ajaran)
            @php
                $ta = $tahun_ajaran->id;
            @endphp
            <div class="col-sm-12">

                <div class="card mb-4">
                    <div class="card-header bg-primary">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><b>{{$tahun_ajaran->keterangan}}</b></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding:0">
                        <div class="mt-4">
                            <div class="mt-4">
                                <div class="mt-2"></div>
                                <table class="table table-hover table-border-horizontal mb-3" id="tablekrs">
                                    <thead>
                                        <th>Kode</th>
                                        <th>Nama Matakuliah</th>
                                        <th>SKS</th>
                                        <th>Tugas</th>
                                        <th>UTS</th>
                                        <th>UAS</th>
                                        <th>Nilai Akhir</th>
                                    </thead>
                                    <tbody>
                                        @foreach($krs[$ta] as $row_krs)
                                            <tr>
                                                <td>{{ $row_krs['kode_matkul'] }}</td>
                                                <td>{{ $row_krs['nama_matkul'] }}</td>
                                                <td>{{ ($row_krs->sks_teori+$row_krs->sks_praktek) }}</td>
                                                <td>{{ ($nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_tgs'] == 0)?"-":$nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_tgs']}}</td>
                                                <td>{{ ($nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_uts'] == 0)?"-":$nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_uts']}}</td>
                                                <td>{{ ($nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_uas'] == 0)?"-":$nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_uas']}}</td>
                                                <td>{{ $nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_akhir']}} | {{ $nilai[$row_krs->id_jadwal][$ta][$mhs->nim]['nilai_huruf']}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
    <script src="{{ asset('assets/js/notify/index.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/height-equal.js') }}"></script>
    <script src="{{ asset('assets/js/animation/wow/wow.min.js') }}"></script>
    <script>
        var options2 = {
            series: [
            {
                name:'Tahun Ajaran',
                data:[{!!$new_total_nilai!!}]
            }
            ],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: true
            },
            stroke: {
                curve: 'straight'
            },
            title: {
                text: 'Grafik IPS per semester',
                align: 'left'
                },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
                padding: {
                    left: 20,
                    right:20,
                    bottom:20,
                    top:20
                }
            },
            xaxis: {
                categories: [{!!$tahun_ajaran_keterangan!!}],
            }
        };
        const chart2 = new ApexCharts(document.querySelector("#chart-currently2"), options2);
        chart2.render();
    </script>
@endsection
