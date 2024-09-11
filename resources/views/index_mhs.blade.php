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
      <div class="col-xxl-2 col-sm-3 box-col-3">
        <div class="card">
            <div class="card-body b-l-primary border-3 text-center">
                <h4>0</h2>
                <h6>IPK</h6>
            </div>
        </div>
      </div>
      <div class="col-xxl-2 col-sm-3 box-col-3">
        <div class="card">
            <div class="card-body b-r-primary border-3 text-center">
                <h4>0</h4>
                <h6>IPS Terakhir</h6>
            </div>
        </div>
      </div>
      <div class="col-xxl-12 col-sm-12 box-col-12">
        <div class="card card-absolute">
            <div class="card-header bg-primary">
                <h6>KRS Aktif</h6>
            </div>
            <div class="card-body">
                <table class="table" id="tablekrs">
                    <thead>
                        <td>No.</td>
                        <td>Kode</td>
                        <td>Nama Matakuliah</td>
                        <td>Kelas</td>
                        <!-- <td>SKS</td> -->
                        <td>Hari, Waktu</td>
                        <td>Ruang</td>
                        <td>SKS</td>
                        <td>Validasi</td>
                    </thead>
                    <tbody>
                        @php
                            $total_krs = 0;
                        @endphp
                        @foreach($krs as $row_krs)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row_krs['kode_matkul'] }}</td>
                                <td>{{ $row_krs['nama_matkul'] }}</td>
                                <td>{{ $row_krs['kel'] }}</td>
                                <!-- <td>{{ $row_krs['sks_teori'] }}T/ {{ $row_krs['sks_praktek'] }}P</td> -->
                                <td>{{ $row_krs['hari'] }}, {{ $row_krs['nama_sesi'] }}</td>
                                <td>{{ $row_krs['nama_ruang'] }}</td>
                                <td>{{ ($row_krs->sks_teori+$row_krs->sks_praktek) }}</td>
                                <td>{!!($row_krs->is_validasi == 0)?'<p class="btn btn-secondary" style="font-size:8pt;">Menunggu Validasi Dosen Wali</p>':'<p class="btn btn-success" style="font-size:8pt;">Sudah Divalidasi</p>'!!}</td>
                            </tr>
                            @php
                            $total_krs += ($row_krs->sks_teori+$row_krs->sks_praktek);
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan=6 class="text-center">Total SKS</th>
                            <th>{{$total_krs}}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
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
@endsection
