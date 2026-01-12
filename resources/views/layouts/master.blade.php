<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{asset('assets/images/logo/logo-icon.png')}}" type="image/x-icon">
    {{-- <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon"> --}}
    <title>MySTIFAR - {{$title ?? "Managing Your Study, Tools, Information, and Academic Resources"}}</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    @include('layouts.css')
    @yield('style')
  </head>
  {{-- @dd(Route::current()->getName()); --}}
  <body @if(Route::current()->getName() == 'index') onload="startTime()" @elseif (Route::current()->getName() == 'button-builder') class="button-builder" @endif>
    <div class="loader-wrapper">
      <div class="loader-index"><span></span></div>
      <svg>
        <defs></defs>
        <filter id="goo">
          <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
          <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo"> </fecolormatrix>
        </filter>
      </svg>
    </div>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper modern-layout" id="pageWrapper">
      <!-- Page Header Start-->


        @include('layouts.header')
        @if (
            session('herregistrasi') &&
            Route::currentRouteName() !== 'mhs-berkas' &&
            Route::currentRouteName() !== 'dosen-berkas'
        )
          <div class="modal fade" id="exampleModal" tabindex="-1"
            aria-labelledby="modal-herregistrasi"
            aria-hidden="true"
            data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                  <h1 class="modal-title fs-5" id="modal-herregistrasi">Herregistrasi</h1>
                    <!-- Tidak ada tombol close -->
                  </div>
                  <div class="modal-body">
                    Update data anda <a href="{{ session('role') == "mhs" ? route('mhs-berkas') : route('dosen-berkas') }}">di sini</a>
                  </div>
                </div>
            </div>
          </div>
        @endif

        @if(session('show_modal_isi_no_pisn'))
          <div class="modal fade" id="isi-no-pisn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <form action="javascript:void(0)" id="userForm">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Isi No PISN</h5>
                              <button class="btn-close" type="button" data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>Silakan isi nomor PISN Anda pada <a href="{{ url('mhs/profile') }}">profil</a> untuk melengkapi data yudisium.</p>
                          </div>
                      </div>
                  </form>
              </div>
          </div>

          <script>
            window.addEventListener('load', function() {
              var el = document.getElementById('isi-no-pisn');
              if (el) {
                var modal = new bootstrap.Modal(el);
                modal.show();
              }
            });
          </script>
        @endif


      <!-- Page Header Ends  -->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        {{-- @role('super-admin')
            @include('layouts.sidebar')
        @endrole
        @role('admin-prodi')

            @include('layouts.sidebar_admin_prodi')
        @endrole
        @role('pegawai')
            @include('layouts.sidebar_pegawai')
        @endrole
        @role('mhs')
            @include('layouts.sidebar')
        @endrole
        @role('admin-pmb')
            @include('layouts.sidebar_admin_pmb')
        @endrole
        @role('baak')
            @include('layouts.sidebar_baak')
        @endrole --}}
        @include('layouts.sidebar')

        <!-- Page Sidebar Ends-->
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6">
                  @yield('breadcrumb-title')
                </div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">
                      <svg class="stroke-icon">
                        <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                      </svg></a></li></li>
                    @yield('breadcrumb-items')
                  </ol>
                </div>
              </div>
            </div>
          </div>
          @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
              </div>
          @endif
          @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ session('error') }}
              </div>
          @endif
          <!-- Container-fluid starts-->
          @yield('content')
          <!-- Container-fluid Ends-->
        </div>
        <!-- footer start-->
        @include('layouts.footer')
        
      </div>
    </div>
    <!-- latest jquery-->
    @include('layouts.script')
    <!-- Plugin used-->

    <script type="text/javascript">
      if ($(".page-wrapper").hasClass("horizontal-wrapper")) {
            $(".according-menu.other" ).css( "display", "none" );
            $(".sidebar-submenu" ).css( "display", "block" );
      }
    </script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
          backdrop: 'static',
          keyboard: false
        });
        myModal.show();
      });
    </script>
    @include('layouts.flash-message')

  </body>
</html>
