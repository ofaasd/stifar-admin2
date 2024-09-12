@extends('layouts.master')

@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Dashboard</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Default</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xxl-5 col-ed-6 col-xl-8 box-col-7">
            <div class="row">
              <div class="col-sm-12">
                <div class="card o-hidden welcome-card">
                  <div class="card-body">
                    <h4 class="mb-3 mt-1 f-w-500 mb-0 f-22">Hello {{Auth::user()->name}}<span> <img src="{{ asset('assets/images/dashboard-3/hand.svg') }}" alt="hand vector"></span></h4>
                    <p>Selamat datang di halaman dashboard Sistem Informasi STIFAR</p>
                  </div><img class="welcome-img" src="{{ asset('assets/images/dashboard-3/widget.svg') }}" alt="search image">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="card course-box">
                  <div class="card-body">
                    <div class="course-widget">
                      <div class="course-icon">
                        <svg class="fill-icon">
                          <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use>
                        </svg>
                      </div>
                      <div>
                        <h4 class="mb-0">{{$perwalian}}</h4><span class="f-light">Mahasiswa Perwalian</span><a class="btn btn-light f-light" href="{{URL::to('dosen/perwalian')}}">Lihat Mahasiswa<span class="ms-2">
                            <svg class="fill-icon f-light">
                              <use href="{{ asset('assets/svg/icon-sprite.svg#arrowright') }}"></use>
                            </svg></span></a>
                      </div>
                    </div>
                  </div>
                  <ul class="square-group">
                    <li class="square-1 warning"></li>
                    <li class="square-1 primary"></li>
                    <li class="square-2 warning1"></li>
                    <li class="square-3 danger"></li>
                    <li class="square-4 light"></li>
                    <li class="square-5 warning"></li>
                    <li class="square-6 success"></li>
                    <li class="square-7 success"></li>
                  </ul>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="card course-box">
                  <div class="card-body">
                    <div class="course-widget">
                      <div class="course-icon warning">
                        <svg class="fill-icon">
                          <use href="{{ asset('assets/svg/icon-sprite.svg#course-2') }}"></use>
                        </svg>
                      </div>
                      <div>
                        <h4 class="mb-0">{{$total_jadwal}}</h4><span class="f-light">Total Matkul Diampu</span><a class="btn btn-light f-light" href="{{URL::to('dosen/krm')}}">Lihat KRM<span class="ms-2">
                            <svg class="fill-icon f-light">
                              <use href="{{ asset('assets/svg/icon-sprite.svg#arrowright') }}"></use>
                            </svg></span></a>
                      </div>
                    </div>
                  </div>
                  <ul class="square-group">
                    <li class="square-1 warning"></li>
                    <li class="square-1 primary"></li>
                    <li class="square-2 warning1"></li>
                    <li class="square-3 danger"></li>
                    <li class="square-4 light"></li>
                    <li class="square-5 warning"></li>
                    <li class="square-6 success"></li>
                    <li class="square-7 success"></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-ed-3 col-xl-4 col-sm-6 box-col-5">
            <div class="card get-card">
              <div class="card-header card-no-border">
                <h5>Absensi Harian</h5><span class="f-14 f-w-500 f-light">Grafik absensi harian dosen</span>
              </div>
              <div class="card-body pt-0">
                <div class="progress-chart-wrap">
                  <div id="progresschart"></div>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="col-xxl-12 col-sm-12 box-col-12">
        <div class="card card-absolute">
            <div class="card-header bg-primary">
                <h6>KRM</h6>
            </div>
            <div class="card-body">
                <table class="table" id="tablekrs">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Jadwal</th>
                            <th>Hari & Waktu</th>
                            <th>Matakuliah</th>
                            <th>Ruang</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th>T/P</th>
                            <th>Kuota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwal as $jad)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $jad['kode_jadwal'] }}</td>
                                <td>{{ $jad['hari'] }}, {{ $jad['nama_sesi'] }}</td>
                                <td>[{{ $jad['kode_matkul'] }}] {{ $jad['nama_matkul'] }}</td>
                                <td>{{ $jad['nama_ruang'] }}</td>
                                <td>{{ $jad['kode_ta'] }}</td>
                                <td>{{ $jad['status'] }}</td>
                                <td>{{ $jad['tp'] }}</td>
                                <td>{{ $jad['kuota'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">
        var session_layout = '{{ session()->get('layout') }}';
        console.log(session_layout);
    </script>
@endsection

@section('script')
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script>
    var options = {
    series: [18, 50],
    chart: {
        width: 240,
        height: 360,
        type: 'radialBar',
        offsetX: -18,
    },
    plotOptions: {
        radialBar: {
            dataLabels: {
                name: {
                offsetY: 20,
                color: "var(--chart-text-color)",
                fontFamily: 'Rubik, sans-serif',
                fontWeight: 500,
                },
                value: {
                fontSize: '22px',
                offsetY: -16,
                fontFamily: 'Rubik, sans-serif',
                fontWeight: 500,
                },
                total: {
                show: true,
                label: 'Absensi',
                fontSize: '12px',
                formatter: function () {
                    return "89%"
                }
                }
            },
            hollow: {
                margin: 5,
                size: '70%',
                image: '../assets/images/dashboard-3/round.png',
                imageWidth: 115,
                imageHeight: 115,
                imageClipped: false,
            },
             track: {
              background: 'transparent',
             }
        }
    },
    colors: [ "var(--theme-deafult)", "#2ecc71"],
    labels: ['Absen(Sakit,Izin,Cuti)','Masuk'],
    stroke: {
        lineCap: 'round'
    },
    legend: {
        show: true,
        position: "bottom",
        horizontalAlign: 'center',
        offsetY: -15,
        fontSize: '14px',
        fontFamily: 'Rubik, sans-serif',
        fontWeight: 500,
        labels: {
          colors: "var(--chart-text-color)",
        },
        markers: {
          width: 6,
          height: 6,
        }
    },
    responsive: [
      {
        breakpoint: 1830,
        options:{
           chart: {
              offsetX: -40,
           }
        }
      },
      {
        breakpoint: 1750,
        options:{
           chart: {
              offsetX: -50,
           }
        }
      },
      {
        breakpoint: 1661,
        options:{
           chart: {
              offsetX: -10,
           }
        }
      },
      {
        breakpoint: 1530,
        options:{
           chart: {
              offsetX: -25,
           }
        }
      },
      {
        breakpoint: 1400,
        options:{
           chart: {
              offsetX: 10,
           }
        }
      },
      {
        breakpoint: 1300,
        options:{
           chart: {
              offsetX: -10,
           }
        }
      },
      {
        breakpoint: 1200,
        options:{
           chart: {
              width: 255,
           }
        }
      },
      {
        breakpoint: 992,
        options:{
           chart: {
              width: 245,
           }
        }
      },
      {
        breakpoint: 600,
        options:{
           chart: {
              width: 225,
           }
        }
      },
    ]
};

var chart = new ApexCharts(document.querySelector("#progresschart"), options);
chart.render();
</script>
@endsection
