@extends('layouts.master')
@section('title', 'Basic DataTables')


@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/echart.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  /* Grid Layout untuk 2 Peta */
  .maps-grid {
      display: grid;
      grid-template-columns: 1fr 1fr; /* 2 Kolom */
      gap: 20px;
  }
  @media (max-width: 768px) {
      .maps-grid { grid-template-columns: 1fr; } /* Mobile 1 Kolom */
  }
  .row-tables {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-top: 30px;
      border-top: 1px solid #eee;
      padding-top: 20px;
  }
  @media (max-width: 768px) {
      .row-tables { grid-template-columns: 1fr; }
  }

  /* Styling Tabel Sederhana & Bersih */
  .table-data {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.9rem;
  }
  .table-data th {
      background: #f8f9fa;
      padding: 10px;
      text-align: left;
      border-bottom: 2px solid #ddd;
      font-weight: 600;
      color: #555;
  }
  .table-data td {
      padding: 8px 10px;
      border-bottom: 1px solid #eee;
  }
  .table-data tr:hover { background-color: #f1f1f1; }
</style>
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
                      <div class="row g-3">
                        
                        <div class="col-xxl-4 col-lg-4 box-col-4">
                          <div class="card">
                              <div class="card-header card-no-border">
                                <h5>Statistik Gender Pendaftar per Gelombang</h5>
                              </div>
                              <div class="card-body pt-0">
                                <div class="row m-0 overall-card">
                                  <div class="col-xl-12 col-md-12 col-sm-12 p-0">
                                    <div class="chart-right">
                                      <div class="row">
                                        <div class="col-xl-12">
                                          <div class="card-body p-0">
                                            <div class="col-md-12 mb-5">
                                              <select id="filter-gelombang" class="form-control" style="width: 100%;">
                                                  @foreach($list_gelombang as $g)
                                                      <option value="{{ $g->id }}">
                                                          {{ $g->nama_gel }} ({{ $g->ta_awal }}/{{ $g->ta_akhir }})
                                                      </option>
                                                  @endforeach
                                              </select>
                                            </div>
                                            <div class="current-sale-container">
                                              <div id="chart-gender"></div>
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
                        <div class="col-xxl-4 col-lg-4 box-col-4">
                          <div class="card">
                              <div class="card-header card-no-border">
                                <h5>Statistik Jumlah Validasi Pendaftar per Gelombang</h5>
                              </div>
                              <div class="card-body pt-0">
                                <div class="row m-0 overall-card">
                                  <div class="col-xl-12 col-md-12 col-sm-12 p-0">
                                    <div class="chart-right">
                                      <div class="row">
                                        <div class="col-xl-12">
                                          <div class="card-body p-0">
                                            <div class="col-md-12 mb-5">
                                              <select id="filter-gelombang-validasi" class="form-control" style="width: 100%;">
                                                  @foreach($list_gelombang as $g)
                                                      <option value="{{ $g->id }}">
                                                          {{ $g->nama_gel }} ({{ $g->ta_awal }}/{{ $g->ta_akhir }})
                                                      </option>
                                                  @endforeach
                                              </select>
                                            </div>
                                            <div class="current-sale-container">
                                              <div id="chart-validasi"></div>
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
                        <div class="col-xxl-4 col-lg-4 box-col-4">
                          <div class="card">
                              <div class="card-header card-no-border">
                                <h5>Statistik Jumlah Pendaftar Lolos per Gelombang</h5>
                              </div>
                              <div class="card-body pt-0">
                                <div class="row m-0 overall-card">
                                  <div class="col-xl-12 col-md-12 col-sm-12 p-0">
                                    <div class="chart-right">
                                      <div class="row">
                                        <div class="col-xl-12">
                                          <div class="card-body p-0">
                                            <div class="col-md-12 mb-5">
                                              <select id="filter-gelombang-lolos" class="form-control" style="width: 100%;">
                                                  @foreach($list_gelombang as $g)
                                                      <option value="{{ $g->id }}">
                                                          {{ $g->nama_gel }} ({{ $g->ta_awal }}/{{ $g->ta_akhir }})
                                                      </option>
                                                  @endforeach
                                              </select>
                                            </div>
                                            <div class="current-sale-container">
                                              <div id="chart-lolos"></div>
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
                        <div class="col-xxl-5 col-lg-5 box-col-5">
                          <div class="card">
                              <div class="card-header card-no-border">
                                <h5>Statistik Total Pendaftar Per Tahun</h5>
                              </div>
                              <div class="card-body pt-0">
                                <div class="row m-0 overall-card">
                                  <div class="col-xl-12 col-md-12 col-sm-7 p-0">
                                    <div class="chart-right">
                                      <div class="row">
                                        <div class="col-xl-12">
                                          <div class="card-body p-0">
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
                        <div class="col-xxl-7 col-lg-7 box-col-7">
                          <div class="card">
                              <div class="card-header card-no-border">
                                <h5>Statistik Admisi Per Prodi per Tahun Ajaran</h5>
                              </div>
                              <div class="card-body">
                                <div class="row m-0 overall-card">
                                  <div class="col-xl-10 col-md-10 col-sm-7 p-0">
                                    <div class="chart-right">
                                      <div class="row">
                                        <div class="col-xl-12">
                                          <div class="card-body p-0">
                                            <ul class="balance-data">
                                              
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
                                <h5>Peta Sebaran Asal Sekolah</h5>
                              </div>
                              <div class="card-body">
                                <div class="row g-3">
                                  <div class="col-md-12">
                                    <select id="filter-ta" class="form-control">
                                      <option value="">Semua Tahun</option>
                                      @foreach($list_ta as $ta)
                                          <option value="{{ $ta['id'] }}">{{ $ta['text'] }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="row g-3">
                                      <div class="col-md-6">
                                        <div>
                                          <h4>🗺️ Sebaran Asal Sekolah</h4>
                                          <div id="peta-sekolah" class="map-wrapper"></div>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <h4>📍 Sebaran Domisili (KTP)</h4>
                                        <div id="peta-domisili" class="map-wrapper"></div>
                                      </div>
                                    </div>
                                    <div class="row-tables">
                                      <div class="table-wrapper">
                                        <h4>🏫 Top 10 Kota Asal Sekolah</h4>
                                        <table class="table-data">
                                            <thead>
                                                <tr>
                                                    <th width="10%">#</th>
                                                    <th>Nama Kota/Kab</th>
                                                    <th width="20%" class="text-right">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody-sekolah">
                                                <tr><td colspan="3" class="text-center">Memuat...</td></tr>
                                            </tbody>
                                        </table>
                                      </div>

                                      <div class="table-wrapper">
                                        <h4>🏠 Top 10 Kota Domisili</h4>
                                        <table class="table-data">
                                            <thead>
                                                <tr>
                                                    <th width="10%">#</th>
                                                    <th>Nama Kota/Kab</th>
                                                    <th width="20%" class="text-right">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody-domisili">
                                                <tr><td colspan="3" class="text-center">Memuat...</td></tr>
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
                      <!-- <div class="col-xxl-12 col-lg-12 box-col-12">
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
                      </div> -->
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script> --}}
<script src="{{ asset('assets/js/height-equal.js') }}"></script>
<script src="{{ asset('assets/js/animation/wow/wow.min.js') }}"></script>  

<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/mapdata/countries/id/id-all.js"></script>
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
      height:500,
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
                height: 400,
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
    series: @json($series),
    chart: {
        type: 'bar',
        height: 450,
        toolbar: { show: true }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '60%',
            endingShape: 'rounded'
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: @json($categories), // Data Tahun Ajaran
        title: {
            text: 'Tahun Ajaran'
        }
    },
    yaxis: {
        title: {
            text: 'Jumlah Mahasiswa'
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val + " Mahasiswa"
            }
        }
    },
    // Warna-warna bar, bisa disesuaikan
    colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a'],
    legend: {
        position: 'bottom'
    }
  };
  // const chart = new ApexCharts(document.querySelector("#chart-currently"), options);
  // chart.render();
  const chart2 = new ApexCharts(document.querySelector("#chart-currently2"), options2);
  chart2.render();
  // const chart3 = new ApexCharts(document.querySelector("#chart-currently3"), options3);
  // chart3.render();
  // const chart4 = new ApexCharts(document.querySelector("#chart-currently4"), options4);
  // chart4.render();
  const chart5 = new ApexCharts(document.querySelector("#chart-prodi"), options5);
  chart5.render();
  $(document).ready(function() {
    // --- 1. Inisialisasi Select2 ---
    $('#filter-gelombang').select2({
        placeholder: "Pilih Gelombang Pendaftaran",
        allowClear: false
    });
    $('#filter-gelombang-validasi').select2({
        placeholder: "Pilih Gelombang Pendaftaran",
        allowClear: false
    });

    // --- 2. Konfigurasi Awal ApexCharts ---
    var options_gender = {
        series: [0, 0], // Data awal kosong dulu
        labels: ['Laki-laki', 'Perempuan'],
        chart: {
            type: 'pie', // Bisa diganti 'donut'
            height: 350
        },
        colors: ['#008FFB', '#FF4560'], // Biru untuk Laki, Merah Muda untuk Perempuan
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                // Menampilkan angka jumlah asli, bukan persentase default
                return opts.w.config.series[opts.seriesIndex]
            },
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value + " Orang";
                }
            }
        },
        noData: {
            text: 'Loading...'
        }
    };

    var chart_gender = new ApexCharts(document.querySelector("#chart-gender"), options_gender);
    chart_gender.render();

    // --- 3. Fungsi AJAX Fetch Data ---
    function updateChartGender(gelombangId) {
        $.ajax({
            url: "{{ url('admin/admisi/statistik/gender-data') }}", 
            type: 'GET',
            data: { gelombang_id: gelombangId },
            success: function(response) {
                // Update Data Chart dengan method updateSeries() milik ApexCharts
                // response.series berisi array [jumlah_laki, jumlah_perempuan]
                chart_gender.updateSeries(response.series);
            },
            error: function(xhr) {
                console.log('Error:', xhr);
                alert('Gagal memuat data grafik.');
            }
        });
    }
  
    var options_validasi = {
        series: [0, 0], // Data awal kosong dulu
        labels: ['Sudah Validasi', 'Belum Validasi'],
        chart: {
            type: 'pie', // Bisa diganti 'donut'
            height: 350
        },
        colors: ['#008FFB', '#FF4560'], // Biru untuk Laki, Merah Muda untuk Perempuan
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                // Menampilkan angka jumlah asli, bukan persentase default
                return opts.w.config.series[opts.seriesIndex]
            },
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value + " Orang";
                }
            }
        },
        noData: {
            text: 'Loading...'
        }
    };

    var chart_validasi = new ApexCharts(document.querySelector("#chart-validasi"), options_validasi);
    chart_validasi.render();

    // --- 3. Fungsi AJAX Fetch Data ---
    function updateChartValidasi(gelombangId) {
        $.ajax({
            url: "{{ url('admin/admisi/statistik/validasi-data') }}", 
            type: 'GET',
            data: { gelombang_id: gelombangId },
            success: function(response) {
                // Update Data Chart dengan method updateSeries() milik ApexCharts
                // response.series berisi array [jumlah_sudah_validasi, jumlah_belum_validasi]
                chart_validasi.updateSeries(response.series);
            },
            error: function(xhr) {
                console.log('Error:', xhr);
                alert('Gagal memuat data grafik.');
            }
        });
    }

    var options_lolos = {
        series: [0, 0], // Data awal kosong dulu
        labels: ['Sudah Lolos', 'Belum Lolos'],
        chart: {
            type: 'pie', // Bisa diganti 'donut'
            height: 350
        },
        colors: ['#008FFB', '#FF4560'], // Biru untuk Laki, Merah Muda untuk Perempuan
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                // Menampilkan angka jumlah asli, bukan persentase default
                return opts.w.config.series[opts.seriesIndex]
            },
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value + " Orang";
                }
            }
        },
        noData: {
            text: 'Loading...'
        }
    };

    var chart_lolos = new ApexCharts(document.querySelector("#chart-lolos"), options_lolos);
    chart_lolos.render();

    // --- 3. Fungsi AJAX Fetch Data ---
    function updateChartLolos(gelombangId) {
        $.ajax({
            url: "{{ url('admin/admisi/statistik/lolos-data') }}", 
            type: 'GET',
            data: { gelombang_id: gelombangId },
            success: function(response) {
                // Update Data Chart dengan method updateSeries() milik ApexCharts
                // response.series berisi array [jumlah_sudah_lolos, jumlah_belum_lolos]
                chart_lolos.updateSeries(response.series);
            },
            error: function(xhr) {
                console.log('Error:', xhr);
                alert('Gagal memuat data grafik.');
            }
        });
    }
    
    // Ketika Select2 berubah nilai
    $('#filter-gelombang').on('change', function() {
        var selectedId = $(this).val();
        updateChartGender(selectedId);
    });
    $('#filter-gelombang-validasi').on('change', function() {
        var selectedId = $(this).val();
        updateChartValidasi(selectedId);
    });
    $('#filter-gelombang-lolos').on('change', function() {
        var selectedId = $(this).val();
        updateChartLolos(selectedId);
    });

    // Trigger manual saat halaman pertama kali dibuka
    // Agar grafik langsung muncul sesuai pilihan pertama (terbaru)
    var initialId = $('#filter-gelombang').val();
    var initialId = $('#filter-gelombang-validasi').val();
    var initialId = $('#filter-gelombang-lolos').val();
    if(initialId) {
        updateChartGender(initialId);
        updateChartValidasi(initialId);
        updateChartLolos(initialId);
    }
    $('#filter-ta').select2({
        placeholder: "Pilih Tahun Ajaran",
        allowClear: true
    });

    // --- FUNGSI GENERATOR OPSI PETA (Agar tidak menulis ulang) ---
    function getMapOptions(title, colorMin, colorMax) {
        return {
            chart: { map: 'countries/id/id-all' },
            title: { text: '' },
            mapNavigation: { enabled: true, buttonOptions: { verticalAlign: 'bottom' } },
            colorAxis: {
                min: 0,
                minColor: colorMin,
                maxColor: colorMax
            },
            series: [{
                data: [],
                name: 'Jumlah',
                states: { hover: { color: '#BADA55' } },
                dataLabels: { enabled: true, format: '{point.name}' },
                tooltip: { pointFormat: '{point.name}: <b>{point.value}</b> Orang' }
            }],
            credits: { enabled: false }
        };
    }

    // 1. Inisialisasi Chart
    var chartSekolah = Highcharts.mapChart('peta-sekolah', getMapOptions('Asal Sekolah', '#E0F7FA', '#006064')); // Biru
    var chartDomisili = Highcharts.mapChart('peta-domisili', getMapOptions('Domisili', '#FFF3E0', '#E65100')); // Oranye

    function renderTable(tbodyId, data) {
      var html = '';
      if(data.length === 0) {
          html = '<tr><td colspan="3" class="text-center">Tidak ada data</td></tr>';
      } else {
          $.each(data, function(index, item) {
              html += `<tr>
                  <td>${index + 1}</td>
                  <td>${item.nama_kota}</td>
                  <td class="text-right"><strong>${item.total}</strong></td>
              </tr>`;
          });
      }
      $(tbodyId).html(html);
  }
    // 2. Fungsi Load Data (Global untuk kedua peta)
    function loadAllMaps(taAwal) {
        // Tampilkan Loading
        chartSekolah.showLoading('Memuat...');
        chartDomisili.showLoading('Memuat...');
        $('#tbody-sekolah').html('<tr><td colspan="3" class="text-center">Sedang memuat data...</td></tr>');
        $('#tbody-domisili').html('<tr><td colspan="3" class="text-center">Sedang memuat data...</td></tr>');

        // Request AJAX Peta Sekolah
        $.ajax({
            url: "{{ url('admin/admisi/statistik/map-data') }}",
            data: { ta_awal: taAwal },
            success: function(data) {
                chartSekolah.series[0].setData(data);
                chartSekolah.hideLoading();
            }
        });

        // Request AJAX Peta Domisili
        $.ajax({
            url: "{{ url('admin/admisi/statistik/get-domisili-map-data') }}",
            data: { ta_awal: taAwal },
            success: function(data) {
                chartDomisili.series[0].setData(data);
                chartDomisili.hideLoading();
            }
        });

        $.ajax({
          url: "{{ url('admin/admisi/statistik/get-top-10-data') }}",
          data: { ta_awal: taAwal },
          success: function(response) {
              // Render Tabel Sekolah
              renderTable('#tbody-sekolah', response.sekolah);
              // Render Tabel Domisili
              renderTable('#tbody-domisili', response.domisili);
          },
          error: function() {
              $('#tbody-sekolah').html('<tr><td colspan="3" class="text-center text-danger">Gagal memuat</td></tr>');
              $('#tbody-domisili').html('<tr><td colspan="3" class="text-center text-danger">Gagal memuat</td></tr>');
          }
      });
    }

    // 3. Event Listener
    $('#filter-ta').on('change', function() {
        loadAllMaps($(this).val());
    });

    // Load Awal
    loadAllMaps('');
});
</script>
@endsection