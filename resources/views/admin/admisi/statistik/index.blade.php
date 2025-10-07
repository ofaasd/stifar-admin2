@extends('layouts.master')
@section('title', 'Basic DataTables')


@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/echart.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Admisi</li>
    <li class="breadcrumb-item active">Statistik</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>{{$title}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                      <div class="col-xxl-12 col-lg-12 box-col-12">
                        <div class="card">
                            <div class="card-header card-no-border">
                              <h5>Statistik Admisi Per Tahun</h5>
                            </div>
                            <div class="card-body pt-0">
                              <div class="row m-0 overall-card">
                                <div class="col-xl-9 col-md-12 col-sm-7 p-0">
                                  <div class="chart-right">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="card-body p-0">
                                          <ul class="balance-data">
                                            <li><span class="circle bg-primary"> </span><span class="f-light ms-1">Tahun</span></li>
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
                      </div>
                      <div class="col-xxl-12 col-lg-12 box-col-12">
                        <div class="card">
                            <div class="card-header card-no-border">
                              <h5>Statistik Admisi Per Prodi TA {{$ta_awal}}</h5>
                            </div>
                            <div class="card-body pt-0">
                              <div class="row m-0 overall-card">
                                <div class="col-xl-9 col-md-12 col-sm-7 p-0">
                                  <div class="chart-right">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="card-body p-0">
                                          <ul class="balance-data">
                                            <li><span class="circle bg-primary"> </span><span class="f-light ms-1">Tahun</span></li>
                                          </ul>
                                          <div class="current-sale-container">
                                            <div id="chart-prodi"></div>
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
                      <div class="col-xxl-12 col-lg-12 box-col-12">
                        <div class="card">
                            <div class="card-header card-no-border">
                              <h5>Statistik Admisi Per Jenis Kelamin TA {{$curr_gelombang->ta_awal}} / {{$curr_gelombang->ta_akhir}}</h5>
                            </div>
                            <div class="card-body pt-0">
                              <div class="row m-0 overall-card">
                                <div class="col-xl-9 col-md-12 col-sm-7 p-0">
                                  <div class="chart-right">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="card-body p-0">
                                          <ul class="balance-data">
                                            <li><span class="circle bg-secondary"> </span><span class="f-light ms-1">Perempuan</span></li>
                                            <li><span class="circle bg-primary"> </span><span class="f-light ms-1">Laki-laki</span></li>
                                          </ul>
                                          <div class="current-sale-container">
                                            <div id="chart-currently"></div>
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
                      <div class="col-xxl-12 col-lg-12 box-col-12">
                        <div class="card">
                            <div class="card-header card-no-border">
                              <h5>Statistik Admisi Jumlah Pendaftar Per Program Studi TA {{$curr_gelombang->ta_awal}} / {{$curr_gelombang->ta_akhir}}</h5>
                            </div>
                            <div class="card-body pt-0">
                              <div class="row m-0 overall-card">
                                <div class="col-xl-9 col-md-12 col-sm-7 p-0">
                                  <div class="chart-right">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="card-body p-0">
                                          <ul class="balance-data mt-3">
                                            @php $warna = ['#7366ff','#f73164','#9b59b6','#f1c40f','#2ecc71']; $i = 0 @endphp
                                            @foreach($program_studi as $row)
                                            <li><span class="circle" style="background:{{$warna[$i]}}"> </span><span class="f-light ms-1">{{$row->nama_prodi}}</span></li>
                                            @php $i++ @endphp
                                            @endforeach
                                          </ul>
                                          <div class="current-sale-container">
                                            <div id="chart-currently3"></div>
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
                      <div class="col-xxl-12 col-lg-12 box-col-12">
                        <div class="card">
                            <div class="card-header card-no-border">
                              <h5>Statistik Admisi Jumlah Pendaftar Per Agama TA {{$curr_gelombang->ta_awal}} / {{$curr_gelombang->ta_akhir}}</h5>
                            </div>
                            <div class="card-body pt-0">
                              <div class="row m-0 overall-card">
                                <div class="col-xl-9 col-md-12 col-sm-7 p-0">
                                  <div class="chart-right">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="card-body p-0">
                                          <ul class="balance-data mt-3">
                                            @php $warna = ['#7366ff','#f73164','#9b59b6','#f1c40f','#2ecc71','#34495e']; $i = 0 @endphp
                                            @foreach($agama as $key => $value)
                                            <li><span class="circle" style="background:{{$warna[$i]}}"> </span><span class="f-light ms-1">{{$value}}</span></li>
                                            @php $i++ @endphp
                                            @endforeach
                                          </ul>
                                          <div class="current-sale-container">
                                            <div id="chart-currently4"></div>
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
                
            </div>
        </div>
    </div>
@endsection
@section('script')
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
    var options = {
      series: [
      {
        name:'Laki-laki',
        data:[{!!$laki_laki!!}]
      },
      {
        name: 'Perempuan',
        data: [{!!$perempuan!!}]
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
    colors:[CubaAdminConfig.primary,CubaAdminConfig.secondary],
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
        {!!$nama_gel!!}
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
  var options2 = {
      series: [
      {
        name:'Tahun',
        data:[{!!$jumlah_pertahun!!}]
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
    colors:[CubaAdminConfig.primary],
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
        {!!$list_tahun!!}
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
  var options3 = {
    series: [
      @foreach($program_studi as $row)
      {
        name:'{{$row->nama_prodi}}',
        data:[{!!$list_jurusan[$row->id]!!}]
      }, 
      @endforeach    
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
    },
    dataLabels:{
      enabled: true,
    },
    fill: {
      opacity: 1
    },
    legend: {
      show:false
    },
    colors:[CubaAdminConfig.primary,CubaAdminConfig.secondary,'#9b59b6','#f1c40f','#2ecc71'],
    yaxis: {
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
        {!!$nama_gel!!}
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
  var options4 = { //for statistic religion
    series: [
      @foreach($agama as $key => $value)
      {
        name:'{{$value}}',
        data:[{!!$list_agama[$key]!!}]
      }, 
      @endforeach    
          ],
    chart:{
      type:'bar',
      height:600,
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
        columnWidth: '40px',
        borderRadius: 0,
      },
    },
    grid: {
      show:true,
    },
    dataLabels:{
      enabled: true,
    },
    fill: {
      opacity: 1
    },
    legend: {
      show:false
    },
    colors:[CubaAdminConfig.primary,CubaAdminConfig.secondary,'#9b59b6','#f1c40f','#2ecc71','#2c3e50'],
    yaxis: {
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
        {!!$nama_gel!!}
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
  var options5 = {
      series: [
      {
        name:'Tahun',
        data:[{!!$jumlah_prodi!!}]
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
    colors:[CubaAdminConfig.primary,CubaAdminConfig.secondary,'#9b59b6','#f1c40f','#2ecc71','#2c3e50'],
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
  const chart = new ApexCharts(document.querySelector("#chart-currently"), options);
  chart.render();
  const chart2 = new ApexCharts(document.querySelector("#chart-currently2"), options2);
  chart2.render();
  const chart3 = new ApexCharts(document.querySelector("#chart-currently3"), options3);
  chart3.render();
  const chart4 = new ApexCharts(document.querySelector("#chart-currently4"), options4);
  chart4.render();
  const chart5 = new ApexCharts(document.querySelector("#chart-prodi"), options5);
  chart5.render();
</script>
@endsection