<div class="page-header">
  <div class="header-wrapper row m-0">
    <form class="form-inline search-full col" action="#" method="get">
      <div class="form-group w-100">
        <div class="Typeahead Typeahead--twitterUsers">
          <div class="u-posRelative">
            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Cuba .." name="q" title="" autofocus>
            <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><i class="close-search" data-feather="x"></i>
          </div>
          <div class="Typeahead-menu"></div>
        </div>
      </div>
    </form>
    <div class="header-logo-wrapper col-auto p-0">
      <div class="logo-wrapper"><a href="{{ route('dashboard')}}"><img class="img-fluid" src="{{ asset('assets/images/logo/logo.png') }}" alt=""></a></div>
      <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
    </div>
    {{-- <div class="left-header col-xxl-5 col-xl-6 col-lg-5 col-md-4 col-sm-3 p-0">
      <div class="notification-slider">
        <div class="d-flex h-100"> <img src="{{ asset('assets/images/giftools.gif') }}" alt="gif">
          <h6 class="mb-0 f-w-400"><span class="font-primary">Don't Miss Out! </span><span class="f-light">Out new update has been release.</span></h6><i class="icon-arrow-top-right f-light"></i>
        </div>
        <div class="d-flex h-100"><img src="{{ asset('assets/images/giftools.gif') }}" alt="gif">
          <h6 class="mb-0 f-w-400"><span class="f-light">Something you love is now on sale! </span></h6><a class="ms-1" href="https://1.envato.market/3GVzd" target="_blank">Buy now !</a>
        </div>
      </div>
    </div> --}}
    <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
      <ul class="nav-menus">
        @php
            $ta = \App\Models\TahunAjaran::where('status',1)->first();
            $ta_awal = (int)substr($ta->kode_ta,0,4);
            $ta_akhir = $ta_awal+1;

            $ganjil = substr($ta->kode_ta,-1,1);
            $status_ganjil = ($ganjil % 2 == 0)?"Genap":"Ganjil";
        @endphp
        <li>TA {{$ta_awal}} - {{$ta_akhir}} {{$status_ganjil}}</li>
        <li>
            <span class="header-search">
                <svg>
                <use href="{{ asset('assets/svg/icon-sprite.svg#search') }}"></use>
                </svg>
            </span>
        </li>
        <li>
          <div class="mode">
            <svg>
              <use href="{{ asset('assets/svg/icon-sprite.svg#moon') }}"></use>
            </svg>
          </div>
        </li>
        <li class="profile-nav onhover-dropdown pe-0 py-0">
          @php
            if(Auth::user()->roles->pluck('name')[0] == 'pegawai'){
                $pegawai = DB::table('pegawai_biodata')->where('user_id',Auth::user()->id)->first();
            }else{
                $mhs = DB::table('mahasiswa')->where('user_id',Auth::user()->id)->first();
            }
          @endphp
          <div class="media profile-media"><img class="b-r-10 img-30" 
            @if (Auth::user()->roles->pluck('name')[0] == 'pegawai')
              src="{{ (!empty($pegawai->foto))?asset('assets/images/pegawai/' . $pegawai->foto):asset('assets/images/dashboard/profile.png') }}" 
            @else
              src="{{ (!empty($mhs->foto_mhs))?asset('assets/images/mahasiswa/' . $mhs->foto_mhs):asset('assets/images/user/7.jpg') }}" 
            @endif
            alt="">
            @php
              $id = Auth::user()->id;
              $pegawai = \App\Models\PegawaiBiodatum::where('user_id',$id)->first();
            @endphp
            <div class="media-body"><span>{{$pegawai->nama_lengkap ?? Auth::user()->name}}</span>

              <p class="mb-0 font-roboto">{{Auth::user()->roles->pluck('name')[0]}} <i class="middle fa fa-angle-down"></i></p>
            </div>
          </div>
          <ul class="profile-dropdown onhover-show-div">
            <li><a href="#"><i data-feather="user"></i><span>Account </span></a></li>
            <li><a href="#"><i data-feather="settings"></i><span>Settings</span></a></li>
            <li><a href="{{route('logout')}}"><i data-feather="log-in"> </i><span>Logout</span></a></li>
          </ul>
        </li>
      </ul>
    </div>
    <script class="result-template" type="text/x-handlebars-template">
      <div class="ProfileCard u-cf">
      <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
      <div class="ProfileCard-details">
      {{-- <div class="ProfileCard-realName">{{name}}</div> --}}
      </div>
      </div>
    </script>
    <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
  </div>
</div>
