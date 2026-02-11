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
                      <div class="row">
                        <div class="col-xxl-4 col-lg-4 box-col-4">
                          <div class="card">
                              <div class="card-header card-no-border">
                                <h3 class="fw-bold text-dark">Statistik Jumlah Gender Pegawai</h3>
                                <p class="text-muted">Sebaran jumlah pegawai per jenis kelamin</p>
                              </div>
                              <div class="card-body pt-0">
                                <div id="chart-gender"></div>
                              </div>
                          </div>
                        </div>
                        <div class="col-xxl-8 col-lg-8 box-col-8">
                          <div class="card">
                              <div class="card-header card-no-border">
                                <h3 class="fw-bold text-dark">Statistik Jumlah Pegawai Tiap Program Studi</h3>
                                <p class="text-muted">Sebaran jumlah pegawai per program studi</p>
                              </div>
                              <div class="card-body pt-0">
                                <div class="row m-0 overall-card">
                                  <div class="col-xl-9 col-md-12 col-sm-7 p-0">
                                    <div class="chart-right">
                                      <div class="row">
                                        <div class="col-xl-12">
                                          <div class="card-body p-0">
                                            <ul class="balance-data">
                                              <li><span class="circle bg-primary"> </span><span class="f-light ms-1">Program Studi</span></li>
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
                        <div class="col-xxl-6 col-lg-6 box-col-6">
                          <div class="card">
                              <div class="card-header">
                                <h3 class="fw-bold text-dark">🎓 Statistik Jabatan Fungsional</h3>
                                <p class="text-muted">Sebaran jabatan fungsional dosen per program studi.</p>
                              </div>
                              <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-12" style="min-width: 0;"> 
                                      <div id="chart-jabfung"></div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-light border">
                                            <strong>💡 Info:</strong>
                                            Data <em>"Belum Ada / Tenaga Pengajar"</em> merujuk pada pegawai yang kolom jabatannya belum diisi atau masih berstatus Tenaga Pengajar.
                                        </div>
                                    </div>
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 box-col-6">
                          <div class="card border-0 h-100">
                            <div class="card-header bg-white py-3">
                                <h3 class="fw-bold text-dark">🎓 Statistik Jenis Pegawai</h3>
                                <p class="text-muted">Sebaran Jenis Pegawai per program studi.</p>
                            </div>
                            <div class="card-body">
                                <div id="chart-posisi"></div>
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

  var options7 = {
      series: [
      {
        name:'Jumlah Pegawai',
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
  const chart7 = new ApexCharts(document.querySelector("#chart-prodi"), options7);
  chart7.render();
  document.addEventListener('DOMContentLoaded', function () {
    
    // Menerima data dari Controller Laravel menggunakan 
    var genderSeries = @json($series_gender); // Output: [15, 20]
    var genderLabels = @json($labels_gender); // Output: ["Laki-laki", "Perempuan"]

    var options = {
        series: genderSeries, // Data langsung dimasukkan di sini
        labels: genderLabels,
        chart: {
            type: 'pie',
            height: 350
        },
        colors: ['#008FFB', '#FF4560'], // Biru (L), Pink (P)
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return Math.round(val) + "%"
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value + " Orang";
                }
            }
        },
        // Teks jika data kosong (0 pegawai)
        noData: {
            text: 'Belum ada data pegawai'
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-gender"), options);
    chart.render();

    var dataSeriesJabfung = @json($series);
    var dataCategoriesJabfung = @json($categories);

    var options_jabfung = {
        series: dataSeriesJabfung,
        chart: {
            type: 'bar',
            height: 500,
            width: '100%', // <--- TAMBAHKAN INI
            toolbar: { show: true },
            stacked: true, // Membuat bar bertumpuk agar rapi
            zoom: { enabled: true }
        },
        plotOptions: {
            bar: {
                horizontal: false, // Vertical bar
                borderRadius: 4,
                columnWidth: '60%', // Lebar batang
                dataLabels: {
                    total: {
                        enabled: true, // Menampilkan Angka Total di atas batang
                        style: {
                            fontSize: '13px',
                            fontWeight: 900
                        }
                    }
                }
            },
        },
        dataLabels: {
            enabled: false // Hide angka di dalam potongan warna agar tidak penuh
        },
        stroke: {
            width: 1,
            colors: ['#fff']
        },
        xaxis: {
            categories: dataCategoriesJabfung, // Nama Prodi
            title: { text: 'Program Studi' },
            labels: {
                rotate: -45, // Miringkan teks jika nama prodi panjang
                trim: true,
                maxHeight: 120
            }
        },
        yaxis: {
            title: { text: 'Jumlah Pegawai' }
        },
        // Warna Custom untuk membedakan Jenjang
        // Asisten Ahli (Hijau Muda), Lektor (Hijau Tua), LK (Biru), GB (Ungu), Null (Abu)
        colors: ['#28a745', '#198754', '#0d6efd', '#6610f2', '#6c757d'], 
        legend: {
            position: 'top',
            horizontalAlign: 'left'
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " Orang"
                }
            }
        }
    };

    var chart_jabfung = new ApexCharts(document.querySelector("#chart-jabfung"), options_jabfung);
    // Tunda render selama 100ms agar CSS sempat load
    

    // --- 2. CONFIG BAR CHART (POSISI) ---
        var barSeries = @json($barSeries);         // Data Series (Dosen Tetap, Staff, dll)
        var barCategories = @json($prodiCategories); // Nama-nama Prodi

        var optionsPosisi = {
            series: barSeries,
            chart: {
                type: 'bar',
                height: 500,
                stacked: true, // Membuat bar bertumpuk agar rapi
                toolbar: { show: true },
                zoom: { enabled: false }
            },
            plotOptions: {
                bar: {
                    horizontal: false, // Ubah true jika ingin bar menyamping
                    columnWidth: '50%',
                    borderRadius: 4
                },
            },
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: {
                categories: barCategories,
                labels: {
                    rotate: -45, // Miringkan label prodi jika panjang
                    style: { fontSize: '12px' }
                }
            },
            yaxis: {
                title: { text: 'Jumlah Pegawai' }
            },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function (val) { return val + " Orang" }
                }
            },
            // Warna-warni untuk tiap jenis posisi
            colors: ['#775DD0', '#00E396', '#FEB019', '#FF4560', '#008FFB'],
            legend: { position: 'top' },
            noData: { text: 'Belum ada data posisi' }
        };

        var chartPosisi = new ApexCharts(document.querySelector("#chart-posisi"), optionsPosisi);
        
    setTimeout(function() {
        chart_jabfung.render();
        chartPosisi.render();
    }, 100);
});
</script>
@endsection