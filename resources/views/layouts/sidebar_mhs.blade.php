<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
      <div class="logo-wrapper"><a href="{{ route('dashboard_mahasiswa')}}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_dark.png') }}" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
      <div class="logo-icon-wrapper"><a href="{{ route('dashboard_mahasiswa')}}"></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn">
              <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{ route('dashboard_mahasiswa')}}" >
                <span><i class="fa fa-home"></i> Dashboard</span></a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-user"></i> Profile</span></a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{URL::to('mhs/profile')}}" class="submenu-title">Data diri</a>
                    </li>
                    <li>
                        <a class="submenu-title" href="{{URL::to('mhs/berkas')}}" >
                             <span>Heregistrasi (Data Pendukung)</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-bar-chart"></i> Akademik</span></a>
                <ul class="sidebar-submenu">
                    <li class="submenu-title"><a class="sidebar-link sidebar-title" href="{{URL::to('mhs/input_krs')}}" >
                        <span> Input KRS</span></a>
                    </li>
                    <li class="submenu-title"><a class="sidebar-link sidebar-title" href="{{URL::to('mhs/riwayat_krs')}}" >
                        <span> Riwayat KRS</span></a>
                    </li>
                    <li class="submenu-title"><a class="sidebar-link sidebar-title" href="{{URL::to('mhs/ujian')}}" >
                        <span> Ujian</span></a>
                    </li>
                    <li class="submenu-title"><a class="sidebar-link sidebar-title" href="{{URL::to('mhs/khs')}}" >
                        <span> KHS</span></a>
                    </li>
                    <li class="submenu-title"><a class="sidebar-link sidebar-title" href="{{URL::to('mhs/khs_riwayat')}}" >
                        <span>Riwayat KHS</span></a>
                    </li>
                    <li class="submenu-title"><a class="sidebar-link sidebar-title" href="{{URL::to('mhs/daftar_nilai')}}" >
                        <span> Daftar Nilai</span></a>
                    </li>
                    <li>
                        <a class="submenu-title" href="#">
                            <span>Skripsi <label class="badge badge-light-danger">!</label></span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{ Route('mhs.skripsi.daftar.index') }}">Pengajuan Skripsi </a></li>
                            <li><a href="{{Route('mhs.skripsi.bimbingan.index')}}">Bimbingan</a></li>
                            <li><a href="{{Route('mhs.skripsi.berkas.index')}}">Berkas</a></li>
                            <li><a href="{{ Route('sidang.index') }}">Daftar Sidang</a></li>
                            <li><a href="#">Nilai <label class="badge badge-light-danger">!</label></a></li>
                        </ul>
                    </li>
                    @if(session()->has('isYudisium') && session()->get('isYudisium') == 1)
                        <li class="submenu-title"><a class="sidebar-link sidebar-title" href="{{URL::to('mhs/akademik/daftar-wisuda')}}" >
                            <span> Daftar Wisuda</span></a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-money"></i> Tagihan</span></a>
                <ul class="sidebar-submenu">
                    <li class="submenu-title"><a class="sidebar-link sidebar-title" href="{{URL::to('mhs/tagihan')}}" >
                        <span> Info Tagihan</a>
                    </li>

                    <li class="submenu-title"><a class="sidebar-link sidebar-title" href="{{URL::to('mhs/lapor_bayar')}}" >
                        <span> Lapor Pembayaran</a>
                    </li>
                </ul>
            </li>
             <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{ url('mhs/absensi')}}" >
                <span><i class="fa fa-clock-o"></i>  Presensi</span></a>
            </li>
            {{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-clock-o"></i> Presensi</span><label class="badge badge-light-danger">!</label></a>
                <ul class="sidebar-submenu">
                    <li class="submenu-title"><a class="sidebar-link sidebar-title" href="#" >
                        <span> Input Presensi</span><label class="badge badge-light-danger">!</label></a>
                    </li>

                    <li class="submenu-title"><a class="sidebar-link sidebar-title" href="#" >
                        <span> Riwayat Presensi</span><label class="badge badge-light-danger">!</label></a>
                    </li>
                </ul>
            </li> --}}
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-book"></i> Bimbingan</span><label class="badge badge-light-danger">!</label></a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-bookmark"></i> Kuesioner Kepuasan Mahasiswa</span><label class="badge badge-light-danger">!</label></a>
            </li>
           <li class="sidebar-list">
            <a class="sidebar-link sidebar-title" href="#">
                <span><i class="fa fa-support"></i> Tugas Akhir</span>
            </a>
            <ul class="sidebar-submenu">
              <li><a  href="{{Route('mhs.pembimbing.index')}}">Daftar Dosen Pembimbing</a></li>
              <li><a href="{{ Route('mhs.bimbingan.index') }}">Bimbingan Skripsi</a></li>
              <li><a href="{{ Route('mhs.skripsi.berkas.index') }}">Manajemen Berkas Skripsi</a></li>
              <li><a href="#">Daftar Mahasiswa Bimbingan</a></li>
              <li><a href="#">Manajemen Bimbingan</a></li>
              <li><a href="#">Berita Acara</a></li>
            </ul>
        </li>

          </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </nav>
    </div>
  </div>
