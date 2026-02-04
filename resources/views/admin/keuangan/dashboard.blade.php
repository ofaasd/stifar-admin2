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
      <div class="col-xxl-6 col-sm-6 box-col-6">
        <div class="card profile-box">
          <div class="card-header">
            Total Pembayaran / Tunggakan Mahasiswa
          </div>
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <div class="greeting-user">
                    <table>
                        @foreach($prodi as $p)
                          <tr><td>{{$p->nama_prodi}}</td><td> : </td><td>Rp. {{number_format($total_pembayaran[$p->id])}}/{{number_format($total_tunggakan[$p->id])}}</td></tr>  
                        @endforeach
                    </table>
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
@endsection
