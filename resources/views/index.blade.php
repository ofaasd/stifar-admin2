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
    <div class="row widget-grid">
      <div class="col-xxl-4 col-sm-6 box-col-6">
        <div class="card profile-box">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <div class="greeting-user">
                  <h4 class="f-w-600">Selamat Datang Di SISTIFAR</h4>
                  <p>Lihat aktifitas terbaru anda</p><br />
                  <div class="whatsnew-btn"><a class="btn btn-outline-white">Lihat Sekarang</a></div>
                </div>
              </div>
              <div>
                <div class="clockbox">
                  <svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
                    <g id="face">
                      <circle class="circle" cx="300" cy="300" r="253.9"></circle>
                      <path class="hour-marks" d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6"></path>
                      <circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
                    </g>
                    <g id="hour">
                      <path class="hour-hand" d="M300.5 298V142"></path>
                      <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                    </g>
                    <g id="minute">
                      <path class="minute-hand" d="M300.5 298V67"></path>
                      <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                    </g>
                    <g id="second">
                      <path class="second-hand" d="M300.5 350V55"></path>
                      <circle class="sizing-box" cx="300" cy="300" r="253.9">   </circle>
                    </g>
                  </svg>
                </div>
                <div class="badge f-10 p-0" id="txt"></div>
              </div>
            </div>
            <div class="cartoon"><img class="img-fluid" src="{{ asset('assets/images/dashboard/cartoon.svg') }}" alt="vector women with leptop"></div>
          </div>
        </div>
      </div>
      <div class="col-xxl-auto col-xl-3 col-sm-6 box-col-6">
        <div class="row">
          <div class="col-xl-12">
            <div class="card widget-1">
              <div class="card-body">
                <div class="widget-content">
                  <div class="widget-round secondary">
                    <div class="bg-round">
                      <svg class="svg-fill">
                        <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"> </use>
                      </svg>
                      <svg class="half-circle svg-fill">
                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                      </svg>
                    </div>
                  </div>
                  <div>
                    <h4>{{number_format($jumlah_mhs)}}</h4><span class="f-light">Jumlah Mahasiswa</span>
                  </div>
                </div>
                <div class="font-secondary f-w-500"><i class="icon-arrow-up icon-rotate me-1"></i><span>+50%</span></div>
              </div>
            </div>
            <div class="col-xl-12">
              <div class="card widget-1">
                <div class="card-body">
                  <div class="widget-content">
                    <div class="widget-round warning">
                      <div class="bg-round">
                        <svg class="svg-fill">
                          <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"> </use>
                        </svg>
                        <svg class="half-circle svg-fill">
                          <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                        </svg>
                      </div>
                    </div>
                    <div>
                      <h4>{{number_format($jumlah_pegawai)}}</h4><span class="f-light">Jumlah Pegawai</span>
                    </div>
                  </div>
                  <div class="font-primary f-w-500"><i class="icon-arrow-up icon-rotate me-1"></i><span>+70%</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-xxl-auto col-xl-3 col-sm-6 box-col-6">
      <div class="row">
        <div class="col-xl-12">
          <div class="card widget-1" >
            <div class="card-body">
              <div class="widget-content">
                <div class="widget-round warning">
                  <div class="bg-round">
                    <svg class="svg-fill">
                      <use href="{{ asset('assets/svg/icon-sprite.svg#rate') }}"> </use>
                    </svg>
                    <svg class="half-circle svg-fill">
                      <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                    </svg>
                  </div>
                </div>
                <div>
                  <h4>{{number_format($total_pendaftar)}} </h4><span class="f-light">Total Pendaftar</span>
                </div>
              </div>
              <div class="font-warning f-w-500"><i class="icon-arrow-down icon-rotate me-1"></i><span>-20%</span></div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card widget-1">
              <div class="card-body">
                <div class="widget-content">
                  <div class="widget-round success">
                    <div class="bg-round">
                      <svg class="svg-fill">
                        <use href="{{ asset('assets/svg/icon-sprite.svg#orders') }}"> </use>
                      </svg>
                      <svg class="half-circle svg-fill">
                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                      </svg>
                    </div>
                  </div>
                  <div>
                    <h4>{{number_format($jumlah_matkul)}}</h4><span class="f-light">Jumlah Matakuliah</span>
                  </div>
                </div>
                <div class="font-success f-w-500"><i class="icon-arrow-up icon-rotate me-1"></i><span>+70%</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xxl-6 col-lg-12 box-col-12">
        <div class="card">
            <div class="card-header card-no-border">
              <h5>Program Studi</h5>
            </div>
            <div class="card-body pt-0">
              <div class="row m-0 overall-card">
                <div class="col-xl-9 col-md-12 col-sm-7 p-0">
                  <div class="chart-right">
                    <div class="row">
                      <div class="col-xl-12">
                        <div class="card-body p-0">
                          <ul class="balance-data">
                            <li><span class="circle bg-secondary"> </span><span class="f-light ms-1">Matakuliah Teori</span></li>
                            <li><span class="circle bg-primary"> </span><span class="f-light ms-1">Matakuliah Praktek</span></li>
                          </ul>
                          <div class="current-sale-container">
                            <div id="chart-currently"></div>
                          </div>
                        </div>
                      </div>
                   
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-md-12 col-sm-5 p-0">
                  <div class="row g-sm-4 g-2">
                    <div class="col-xl-12 col-md-4">
                      <div class="light-card balance-card widget-hover">
                        <div class="svg-box">
                          <svg class="svg-fill">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#income') }}"></use>
                          </svg>
                        </div>
                        <div> <span class="f-light">Jumlah Kurikulum</span>
                          <h6 class="mt-1 mb-0">{{$jumlah_kurikulum}}</h6>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-12 col-md-4">
                      <div class="light-card balance-card widget-hover">
                        <div class="svg-box">
                          <svg class="svg-fill">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#expense') }}"></use>
                          </svg>
                        </div>
                        <div> <span class="f-light">Mata Kuliah Teori</span>
                          <h6 class="mt-1 mb-0">{{$jumlah_teori}}</h6>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-12 col-md-4">
                      <div class="light-card balance-card widget-hover">
                        <div class="svg-box">
                          <svg class="svg-fill">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#doller-return') }}"></use>
                          </svg>
                        </div>
                        <div> <span class="f-light">Mata Kuliah Praktek</span>
                          <h6 class="mt-1 mb-0">{{$jumlah_praktek}}</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
             
            </div>
            
        </div>
        <div class="card">
            <div class="card-header card-no-border">
              <h5>Pembayaran</h5>
            </div>
            <div class="card-body pt-0">
              <div class="row m-0 overall-card">
                <div class="col-xl-12 col-md-12 col-sm-12 p-0">
                  <div class="chart-right">
                    <div class="row">
                      <div class="col-xl-12">
                        <div class="card-body p-0">
                          <ul class="balance-data">
                            <li><span class="circle bg-secondary"> </span><span class="f-light ms-1">Matakuliah Teori</span></li>
                            <li><span class="circle bg-primary"> </span><span class="f-light ms-1">Matakuliah Praktek</span></li>
                          </ul>
                          <div class="current-sale-container">
                            <div id="chart-pembayaran"></div>
                          </div>
                        </div>
                      </div>
                   
                    </div>
                  </div>
                </div>
             
              </div>
             
            </div>
            
        </div>
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
  @php
    $data_validasi = !empty($list_tidak_valid) ? $list_tidak_valid : '0,0,0,0';
@endphp
    var options = {
     series: [
      {
        name:'Matkul Teori',
        data:[{!!$list_teori!!}]
      },
      {
        name: 'Matkul Praktek',
        data: [{!!$list_praktek!!}]
      }
    ],
    chart:{
      type:'bar',
      height:300,
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

var chart = new ApexCharts(document.querySelector("#chart-currently"), options);
chart.render();


var options2 = {
     series: [
      {
        name: 'Sudah Validasi',
            data: [{!! $list_valid !!}]
      },
      {
        name: 'Belum Validasi',
        data: [{!! $list_tidak_valid !!}]

      }
    ],
    chart:{
      type:'bar',
      height:300,
      stacked:false,
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
      enabled: true,
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
        {!!$list_rombel!!}
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

var chart2 = new ApexCharts(document.querySelector("#chart-pembayaran"), options2);
chart2.render();
</script>
@endsection
