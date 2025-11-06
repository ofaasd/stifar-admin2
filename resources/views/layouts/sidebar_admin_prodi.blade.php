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
            <li class="sidebar-list"><a href="{{URL::to('pegawai')}}" class="sidebar-link sidebar-title" >
                <span><i class="fa fa-users"></i> Profile Pegawai</span></a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{URL::to('dosen/attendance')}}" >
                <span><i class="fa fa-bookmark"></i> Absensi Dosen</span></a>
            </li>
            <li class="sidebar-list">
                <a class="sidebar-link sidebar-title" href="#">
                    <span><i class="fa fa-users"></i> Mahasiswa</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ URL::to('mahasiswa') }}">Data Mahasiswa</a></li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" >
                <span><i class="fa fa-bar-chart"></i> Akademik</span></a>
                <ul class="sidebar-submenu">
                    <li>
                        <a class="submenu-title" href="{{URL::to('dosen/krm')}}" >
                             KRM
                        </a>
                    </li>

                     <li>
                        <a class="submenu-title" href="{{URL::to('dosen/krm_riwayat')}}" >
                            Riwayat KRM
                        </a>
                    </li>
                    <li>
                        <a class="submenu-title" href="{{URL::to('dosen/setting-pertemuan')}}" >
                            Setting Pertemuan
                        </a>
                    </li>
                    <li>
                        <a class="submenu-title" href="{{URL::to('dosen/input_nilai')}}" >
                             Input Nilai
                        </a>
                    </li>
                    <li>
                        <a class="submenu-title" href="#">
                            <span>Skripsi (Dosen)</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{ route('akademik.skripsi.dosen.bimbingan.index') }}">Bimbingan</a></li>
                            <li><a href="{{ route('akademik.skripsi.dosen.penguji.index') }}">Penguji</a></li>
                        </ul>
                    </li>
                    <li><a href="{{URL::to('admin/masterdata/matakuliah')}}">Matakuliah</a></li>
                     <li><a href="{{URL::to('admin/masterdata/jadwal')}}">Jadwal</a></li>
                     {{-- <li><a href="#">Plot Jadwal Ajar</a></li> --}}
                     <li><a href="{{URL::to('admin/masterdata/jadwal-harian')}}">Kontrol Jadwal</a></li>

                     <li>
                        <a class="submenu-title" href="#">
                            <span>KRS</span>  {{--  Ada menu untuk toggle krs sedang dibuka atau ditutup--}}
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{ URL::to('admin/masterdata/krs') }}">Input KRS</a></li>
                            <li><a href="{{URL::to('dashboard_akademik')}}">Statistik KRS </a></li>
                            <li><a href="#">Monitoring KRS <label class="badge badge-light-danger">!</label></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="submenu-title" href="#">
                            <span>Presensi</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{URL::to('admin/akademik/setting-pertemuan')}}">Setting Pertemuan & Presensi</a></li>
                            {{-- <li><a href="{{Url::to('admin/akademik/list-absensi')}}">Input Presensi</a></li> --}}
                            {{-- <li><a href="#">Ganti Kuliah - Pengganti <label class="badge badge-light-danger">!</label></a></li> --}}

                        </ul>
                    </li>
                    <li>
                        <a class="submenu-title" href="#">
                            <span>Nilai</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{Url::to('admin/akademik/nilai')}}">Input Nilai</a></li>
                            <li><a href="{{Url::to('admin/akademik/pengaturan-ujian')}}">Pengaturan Ujian</a></li>
                            <li><a href="{{Url::to('admin/akademik/ujian')}}">Kartu Ujian</a></li>
                        </ul>
                    </li>
                     {{-- <li><a href="#">Semester Antara <label class="badge badge-light-danger">!</label></a></li> --}}
                     {{-- <li><a href="#">Remidial <label class="badge badge-light-danger">!</label></a></li> --}}
                     <li><a href="{{Url::to('admin/akademik/khs')}}">KHS</a></li>
                     <li><a href="{{Url::to('admin/akademik/perwalian')}}">Perwalian</a></li>

                    <li>
                        <a class="submenu-title" href="#">
                            <span>Skripsi (Prodi)</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{ URL::to('admin/akademik/skripsi/pengajuan') }}">Pengajuan</a></li>
                            <li><a href="{{ Route('sidang.index') }}">Jadwal Sidang</a></li>
                            <li><a href="{{ URL::to('admin/skripsi/pembimbing') }}">Pembimbing</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{URL::to('dosen/berkas')}}" >
                <span><i class="fa fa-file"></i> Berkas</span></a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{URL::to('riwayat')}}" >
                <span><i class="fa fa-users"></i> Riwayat Pegawai</span></a>
            </li>
            {{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" >
                <span><i class="fa fa-book"></i> Bimbingan</span></a>
                <ul class="sidebar-submenu">
                    <li>
                        <a class="submenu-title" href="#" >
                             Konsultasi <label class="badge badge-light-danger">!</label>
                        </a>
                    </li>
                    <li>
                        <a class="submenu-title" href="#" >
                             Riwayat KRM <label class="badge badge-light-danger">!</label>
                        </a>
                    </li>
                </ul>
            </li> --}}
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{route('Perwalian')}}" >
                <span><i class="fa fa-bookmark"></i> Perwalian</span></a>
            </li>
            
            {{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-bookmark"></i> Kuesioner Kepuasan Dosen</span></a> <label class="badge badge-light-danger">!</label>
            </li> --}}
            {{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{URL::to('dsn/skripsi/pengajuan')}}" >
                <span><i class="fa fa-users"></i> Pengajuan Mahasiswa</span></a>
            </li> --}}
          </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </nav>
    </div>
  </div>
