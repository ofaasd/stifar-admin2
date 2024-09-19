@extends('layouts.master')

@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/echart.css') }}">
@endsection

@section('style')
<style>
.widget-1{
    background-image:none;
}
</style>
@endsection

@section('breadcrumb-title')
    <h3>Default</h3>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Default</li>
@endsection

@section('content')
<div class="container-fluid">
    @foreach($angkatan as $row)
    <div class="row">

        <div class="col-xxl-6 col-lg-6 box-col-6">
            <div class="card">
                <div class="card-header card-no-border">
                <h5>Grafik Input KRS Mahasiswa Angkatan {{$row->angkatan}}</h5><br /><br />
                </div>
                <div class="card-body pt-0">
                <div class="row m-0 overall-card">
                    <div class="col-xl-12 col-md-12 col-sm-12 p-0">
                    <div class="chart-right">
                        <div class="row">
                        <div class="col-xl-12">
                            <div class="card-body p-0">
                            <ul class="balance-data">
                                <li><span class="circle bg-secondary"> </span><span class="f-light ms-1">Belum Input</span></li>
                                <li><span class="circle bg-primary"> </span><span class="f-light ms-1">Sudah Input</span></li>
                            </ul>
                            <div class="current-sale-container">
                                <div id="chart-currently-{{$row->angkatan}}"></div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                        <th>Program Studi</th>
                                        <th>Mhs Belum KRS</th>
                                        <th>Mhs Sudah KRS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $jumlah_krs = explode(",",$list_jumlah_krs[$row->angkatan]);
                                        $blm_krs = explode(",",$list_total_mahasiswa[$row->angkatan]);
                                        $i = 0;
                                    @endphp
                                    @foreach($prodi as $r)
                                    <tr>
                                        <td>{{$r->nama_prodi}}</td>
                                        <td>{{$blm_krs[$i]}}</td>
                                        <td>{{$jumlah_krs[$i]}}</td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-lg-6 box-col-6">
            <div class="card">
                <div class="card-header card-no-border">
                <h5>Grafik Validasi KRS Mahasiswa Angkatan {{$row->angkatan}}</h5><br /><br />
                </div>
                <div class="card-body pt-0">
                <div class="row m-0 overall-card">
                    <div class="col-xl-12 col-md-12 col-sm-12 p-0">
                    <div class="chart-right">
                        <div class="row">
                        <div class="col-xl-12">
                            <div class="card-body p-0">
                            <ul class="balance-data">
                                <li><span class="circle bg-secondary"> </span><span class="f-light ms-1">Belum Valid</span></li>
                                <li><span class="circle bg-primary"> </span><span class="f-light ms-1">Sudah Valid</span></li>
                            </ul>
                            <div class="current-sale-container">
                                <div id="chart-validasi-{{$row->angkatan}}"></div>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <table class="table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Program Studi</th>
                                            <th>Mhs Belum Validasi KRS</th>
                                            <th>Mhs Sudah Validasi KRS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $krs_invalid = explode(",",$list_jumlah_krs_invalid[$row->angkatan]);
                                            $krs_valid = explode(",",$list_jumlah_krs_valid[$row->angkatan]);
                                            $i = 0;
                                        @endphp
                                        @foreach($prodi as $r)
                                        <tr>
                                            <td>{{$r->nama_prodi}}</td>
                                            <td>{{$krs_invalid[$i]}}</td>
                                            <td>{{$krs_valid[$i]}}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
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
    @endforeach
</div>
  </div>
</div>
    <script type="text/javascript">
        var session_layout = '{{ session()->get('layout') }}';
    </script>
@endsection

@section('script')
<script src="{{ asset('assets/js/clock.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>

{{-- <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script> --}}
<script src="{{ asset('assets/js/height-equal.js') }}"></script>
<script src="{{ asset('assets/js/animation/wow/wow.min.js') }}"></script>
<script>
    @foreach($angkatan as $row)
    var options = {
     series: [
      {
        name: 'Mahasiswa Input KRS',
        data: [{!!$list_jumlah_krs[$row->angkatan]!!}]
      },
      {
        name:'Mahasiswa Belum Input KRS',
        data:[{!!$list_total_mahasiswa[$row->angkatan]!!}]
      }
    ],
    chart:{
      type:'bar',
      height:400,
      stacked:true,
      toolbar:{
        show:false,
      },
       dropShadow: {
        enabled: true,
        top: 8,
        left: 0,
        blur: 10,
        color: '#7064F5',
        opacity: 0.1
      }
    },
    plotOptions: {
      bar:{
        horizontal: false,
        columnWidth: '25px',
        borderRadius: 0,
      },
    },
    grid: {
      show:true,
      borderColor: 'var(--chart-border)',
    },
    dataLabels:{
      enabled: false,
    },
    stroke: {
      width: 2,
      dashArray: 0,
      lineCap: 'butt',
      colors: "#fff",
    },
    fill: {
      opacity: 1
    },
    legend: {
      show:false
    },
    states: {
      hover: {
        filter: {
          type: 'darken',
          value: 1,
        }
      }
    },
    colors:[CubaAdminConfig.primary,'#AAAFCB'],
    yaxis: {
      tickAmount: 3,
      labels: {
        show: true,
        style: {
          fontFamily: 'Rubik, sans-serif',
        },
      },
      axisBorder:{
       show:false,
     },
      axisTicks:{
        show: false,
      },
    },
    xaxis:{
      categories:[
        {!!$list_prodi!!}
      ],
      labels: {
        style: {
          fontFamily: 'Rubik, sans-serif',
        },
      },
      axisBorder:{
       show:false,
     },
    axisTicks:{
        show: false,
      },
    },
    states: {
      hover: {
        filter: {
          type: 'darken',
          value: 1,
        }
      }
    },
    responsive: [
        {
          breakpoint: 1661,
          options:{
            chart: {
                height: 290,
            }
          }
        },
         {
          breakpoint: 767,
          options:{
            plotOptions: {
              bar:{
                columnWidth: '35px',
              },
            },
             yaxis: {
                  labels: {
                    show: false,
                  }
                }
          }
        },
        {
          breakpoint: 481,
          options:{
            chart: {
                height: 200,
            }
          }
        },
        {
          breakpoint: 420,
          options:{
            chart: {
                height: 170,
            },
            plotOptions: {
              bar:{
                columnWidth: '40px',
              },
            },
          }
        },
      ]
  };

var chart = new ApexCharts(document.querySelector("#chart-currently-{{$row->angkatan}}"), options);
chart.render();

var options = {
     series: [
      {
        name: 'KRS Valid',
        data: [{!!$list_jumlah_krs_valid[$row->angkatan]!!}]
      },
      {
        name:'KRS Belum Valid',
        data:[{!!$list_jumlah_krs_invalid[$row->angkatan]!!}]
      }
    ],
    chart:{
      type:'bar',
      height:400,
      stacked:true,
      toolbar:{
        show:false,
      },
       dropShadow: {
        enabled: true,
        top: 8,
        left: 0,
        blur: 10,
        color: '#7064F5',
        opacity: 0.1
      }
    },
    plotOptions: {
      bar:{
        horizontal: false,
        columnWidth: '25px',
        borderRadius: 0,
      },
    },
    grid: {
      show:true,
      borderColor: 'var(--chart-border)',
    },
    dataLabels:{
      enabled: false,
    },
    stroke: {
      width: 2,
      dashArray: 0,
      lineCap: 'butt',
      colors: "#fff",
    },
    fill: {
      opacity: 1
    },
    legend: {
      show:false
    },
    states: {
      hover: {
        filter: {
          type: 'darken',
          value: 1,
        }
      }
    },
    colors:[CubaAdminConfig.primary,'#AAAFCB'],
    yaxis: {
      tickAmount: 3,
      labels: {
        show: true,
        style: {
          fontFamily: 'Rubik, sans-serif',
        },
      },
      axisBorder:{
       show:false,
     },
      axisTicks:{
        show: false,
      },
    },
    xaxis:{
      categories:[
        {!!$list_prodi!!}
      ],
      labels: {
        style: {
          fontFamily: 'Rubik, sans-serif',
        },
      },
      axisBorder:{
       show:false,
     },
    axisTicks:{
        show: false,
      },
    },
    states: {
      hover: {
        filter: {
          type: 'darken',
          value: 1,
        }
      }
    },
    responsive: [
        {
          breakpoint: 1661,
          options:{
            chart: {
                height: 290,
            }
          }
        },
         {
          breakpoint: 767,
          options:{
            plotOptions: {
              bar:{
                columnWidth: '35px',
              },
            },
             yaxis: {
                  labels: {
                    show: false,
                  }
                }
          }
        },
        {
          breakpoint: 481,
          options:{
            chart: {
                height: 200,
            }
          }
        },
        {
          breakpoint: 420,
          options:{
            chart: {
                height: 170,
            },
            plotOptions: {
              bar:{
                columnWidth: '40px',
              },
            },
          }
        },
      ]
  };

var chart = new ApexCharts(document.querySelector("#chart-validasi-{{$row->angkatan}}"), options);
chart.render();
@endforeach
</script>
@endsection
