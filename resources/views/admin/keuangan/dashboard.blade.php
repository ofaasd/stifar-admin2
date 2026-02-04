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
    <h3>{{$title}}</h3>
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
        <div class="col-xxl-4 col-xl-4 col-sm-4 box-col-4">
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
                        <h4>{{number_format($jumlah_krs)}} / {{number_format($jumlah_mhs)}}</h4><span class="f-light">Jumlah Mahasiswa KRS</span>
                    </div>
                    </div>
                    <!-- <div class="font-secondary f-w-500"><i class="icon-arrow-up icon-rotate me-1"></i><span>+50%</span></div> -->
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-xxl-4 col-xl-4 col-sm-4 box-col-4">
            <div class="row">
            <div class="col-xl-12">
                <div class="card widget-1">
                <div class="card-body">
                    <div class="widget-content">
                    <div class="widget-round success">
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
                        <h4>{{number_format($jumlah_uts)}} / {{number_format($jumlah_mhs)}}</h4><span class="f-light">Jumlah Mahasiswa UTS</span>
                    </div>
                    </div>
                    <!-- <div class="font-primary f-w-500"><i class="icon-arrow-up icon-rotate me-1"></i><span>+70%</span></div> -->
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-xxl-4 col-xl-4 col-sm-4 box-col-4">
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
                    <h4>{{number_format($jumlah_uas)}} / {{number_format($jumlah_mhs)}}</h4><span class="f-light">Jumlah Mahasiswa UAS</span>
                    </div>
                </div>
                <!-- <div class="font-warning f-w-500"><i class="icon-arrow-down icon-rotate me-1"></i><span>-20%</span></div> -->
                </div>
            </div>
        </div>
       </div>
    </div>
      <div class="col-xxl-6 col-sm-6 box-col-6">
        <div class="card profile-box">
          <div class="card-body bg-warning" style="border-radius:10px;">
            <div class="media">
              <div class="media-body">
                <div class="greeting-user">
                  <h4 class="f-w-600">Keuangan Mahasiswa</h4>
                  <p>Lihat detail keuangan mahasiswa</p><br />
                  <div class="whatsnew-btn"><a href="{{ url('admin/keuangan/tagihan')}}" class="btn btn-outline-white">Lihat Sekarang</a></div>
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
      <div class="col-xxl-6 col-sm-6 box-col-6">
        <div class="card profile-box">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <div class="greeting-user">
                  <h4 class="f-w-600">Tahun Ajaran Aktif</h4>
                  <p>{{$ta->keterangan}}</p><br />
                  <div class="whatsnew-btn"><a href="{{ url('admin/masterdata/ta')}}" class="btn btn-outline-white">Lihat Sekarang</a></div>
                </div>
              </div>  
            </div>  
          </div>
        </div>
    </div>
    <div class="col-xxl-12 col-sm-12 box-col-12">
        <div class="card" style="width:100%;overflow:hidden;background:#FFF59D; opacity:0.95;">
          <div class="card-header text-dark">
            <h5 class="f-w-600">Statistik Pembayaran per Program Studi <small class="text-dark" style="font-size:10pt">(Update Pembayaran terakhir (tanggal : {{date('d-m-Y', strtotime($pembayaran_terakhir))}}))</small></h5>
          </div>
          <div class="card-body" style="width:100%;overflow-x:hidden;padding:0;">
            <div class="media" style="width:100%;">
              <div class="media-body p-3" style="width:100%;overflow-x:hidden;">
                <div class="greeting-user" style="width:100%;overflow-x:hidden;">
                    <div class="chart-container" style="height:500px;width:100%;max-width:100%;box-sizing:border-box;overflow:hidden;overflow-x:hidden;padding:12px;background:transparent;border-radius:6px;position:relative;">
                        <canvas id="bar-chart" style="background:#ffffff;padding:10px;border-radius:6px;display:block;width:100%;max-width:100%;height:100%;box-sizing:border-box;"></canvas>
                    </div>
                    <br />
                </div>
                <div class="table-responsive bg-white p-3" style="border-radius:6px;">
                    <h2 class="text-dark">Detail Statistik Pembayaran per Prodi</h2>
                    <table class="table table-bordered text-white">
                        @foreach($prodi as $p)
                        <tr><td>{{$p->nama_prodi}}</td><td > : </td><td >Rp. {{number_format($total_bayar_statistik[$p->id])}}/{{number_format($total_tagihan_statistik[$p->id])}} (Total Bayar / Total Tagihan)</td></tr>  
                        @endforeach
                    </table>
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
    new WOW().init();
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function(){
    // Prepare data from Blade variables (serialize only needed fields)
    var prodi = @json($prodi->map(function($p){ return ['id' => $p->id, 'nama_prodi' => $p->nama_prodi]; }));
    var bayarMap = @json($total_bayar_statistik ?? []);
    var tagihanMap = @json($total_tagihan_statistik ?? []);

    var labels = prodi.map(function(p){ return p.nama_prodi; });
    var bayarData = prodi.map(function(p){ return Number(bayarMap[p.id] ?? 0); });
    var tunggakanData = prodi.map(function(p, i){
      var t = Number(tagihanMap[p.id] ?? 0);
      var b = Number(bayarMap[p.id] ?? 0);
      return Math.max(0, t - b);
    });

    // Scale numbers to millions for shorter display
    var scaleFactor = 1000000; // 1 = 1 juta
    var bayarDataScaled = bayarData.map(function(v){ return +(v/scaleFactor); });
    var tunggakanDataScaled = tunggakanData.map(function(v){ return +(v/scaleFactor); });

    var ctx = document.getElementById('bar-chart');
    if(ctx){
            new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Total Bayar',
                      data: bayarDataScaled,
                      backgroundColor: 'rgba(54, 162, 235, 0.85)',
                      barPercentage: 0.45,
                      categoryPercentage: 0.6,
                      borderRadius: 4
            },
            {
              label: 'Tunggakan',
                      data: tunggakanDataScaled,
                      backgroundColor: 'rgba(255, 99, 132, 0.85)',
                      barPercentage: 0.45,
                      categoryPercentage: 0.6,
                      borderRadius: 4
            }
          ]
        },
                options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  interaction: {mode: 'index', intersect: false},
                  scales: {
                    x: {
                      stacked: true,
                      ticks: {
                        autoSkip: true,
                        maxRotation: 45,
                        minRotation: 0
                      }
                    },
                    y: {
                      stacked: true,
                      beginAtZero: true,
                      ticks: {
                        callback: function(value){
                          return Number(value).toLocaleString(undefined,{maximumFractionDigits:2}) + ' jt';
                        }
                      }
                    }
                  },
                  plugins: {
                    tooltip: {
                      callbacks: {
                        label: function(context){
                          var val = context.parsed.y || context.parsed || 0;
                          return context.dataset.label + ': Rp. ' + Number(val).toLocaleString(undefined,{maximumFractionDigits:2}) + ' jt';
                        }
                      }
                    },
                    legend: { position: 'top' }
                  },
                  layout: { padding: { top: 6, right: 6, left: 6, bottom: 6 } }
                }
      });
    }
  })();
</script>
@endsection
