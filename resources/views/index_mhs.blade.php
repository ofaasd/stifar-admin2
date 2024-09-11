@extends('layouts.master')

@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/echart.css') }}">
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-title')
    <h3>Default</h3>
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
                  <h4 class="f-w-600">Halo {{$mahasiswa->nama}}</h4>
                  <p>Lihat aktifitas terbaru anda</p><br />
                  <div class="whatsnew-btn"><a href="{{URL::to('mhs/input_krs')}}" class="btn btn-outline-white">Lihat Sekarang</a></div>
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
      <div class="col-xxl-4 col-sm-6 box-col-6">
        <div class="card">
            <div class="card-body">
                <div id="ipchart"></div>
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
<script src="{{ asset('assets/js/height-equal.js') }}"></script>
<script src="{{ asset('assets/js/animation/wow/wow.min.js') }}"></script>
<script src="{{asset('assets/js/chart/echart/esl.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/config.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/pie-chart/facePrint.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/pie-chart/testHelper.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/pie-chart/custom-transition-texture.js')}}"></script>
<script src="{{asset('assets/js/chart/echart/data/symbols.js')}}"></script>
<script>
 var options = {
          series: [3],
          chart: {
          type: 'radialBar',
          offsetY: -20,
          sparkline: {
            enabled: true
          }
        },
        plotOptions: {
          radialBar: {
            startAngle: -90,
            endAngle: 90,
            track: {
              background: "#e7e7e7",
              strokeWidth: '97%',
              margin: 5, // margin is in pixels
              dropShadow: {
                enabled: true,
                top: 2,
                left: 0,
                color: '#999',
                opacity: 1,
                blur: 2
              }
            },
            dataLabels: {
              name: {
                show: true
              },
              value: {
                offsetY: 30,
                fontSize: '22px'
              }
            }
          }
        },
        grid: {
          padding: {
            top: -10
          }
        },
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'light',
            shadeIntensity: 0.4,
            inverseColors: false,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 53, 91]
          },
        },
        yaxis:[{
            min: 0,
            max: 4,
        }],
        labels: ['IPK'],
        };

        var chart = new ApexCharts(document.querySelector("#ipchart"), options);
        chart.render();
</script>
@endsection
