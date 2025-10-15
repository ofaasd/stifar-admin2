<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
      <div class="logo-wrapper"><a href="{{ route('dashboard')}}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_dark.png') }}" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
      <div class="logo-icon-wrapper"><a href="{{ route('dashboard')}}"></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn">
              <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{ route('dashboard')}}" >
                <span><i class="fa fa-home"></i> Dashboard</span></a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-file-text"></i> Admisi</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="{{URL::to('admin/admisi/jalur_pendaftaran')}}">Jalur Pendaftaran</a></li>
                    <li><a href="{{URL::to('admin/admisi/gelombang')}}">Gelombang</a></li>
                    <li><a href="{{(!empty(session('gelombang')))?URL::to('admin/admisi/peserta/gelombang/' . session('gelombang')):URL::to('admin/admisi/peserta')}}">Pendaftaran Mahasiswa Baru</a></li>
                    <li><a href="{{URL::to('admin/admisi/daftar_soal')}}">Pengaturan Ujian PMB</a></li>
                    <li><a href="{{URL::to('admin/admisi/peringkat')}}">Peringkat PMDP</a></li>  {{--  Ada menu untuk toggle krs sedang dibuka atau ditutup--}}
                    <li><a href="{{URL::to('admin/admisi/verifikasi')}}">Verifikasi Pendaftaran</a></li>
                    <li><a href="{{URL::to('admin/admisi/verifikasi/pembayaran')}}">Verifikasi Pembayaran</a></li>
                    <li><a href="{{URL::to('admin/admisi/pengumuman')}}">Pengumuman Peserta</a></li> {{-- Butuh format surat pengumuman resmi dari pihak kampus --}}
                    <li><a href="{{URL::to('admin/admisi/verifikasi_pembayaran')}}">Verifikasi Pembayaran Daftar Ulang </a></li>
                    <li>
                        <a class="submenu-title" href="#" >
                        <span>NIM Generator</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{URL::to('admin/admisi/generate_nim')}}">Calon Mahasiswa</a></li>
                            <li><a href="{{URL::to('admin/admisi/generate_nim/preview')}}">Preview</a></li>
                        </ul>
                    </li>
                    <li><a href="{{URL::to('admin/admisi/statistik')}}">Statistik</a></li>

                    <li><a href="{{URL::to('admin/admisi/peserta/history')}}">History PMB</a></li>
                    {{-- <li><a href="#">Berita Pendaftaran</a></li> --}}
                    <li><a href="{{URL::to('admin/admisi/biaya_pendaftaran')}}">Biaya Pendaftaran</a></li>
                    <li><a href="{{URL::to('admin/admisi/slideshow')}}">Berita Gambar/Slideshow</a></li>
                    <li><a href="{{URL::to('admin/admisi/user_pmb')}}">User PMB Online</a></li> {{-- Daftar User pmb online--}}
                </ul>
            </li>

          </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </nav>
    </div>
  </div>
