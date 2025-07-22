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
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{ URL::to('dsn/dashboard')}}" >
                <span><i class="fa fa-home"></i> Dashboard</span></a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" >
                <span><i class="fa fa-users"></i> Profile Pegawai</span></a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{URL::to('pegawai')}}" class="submenu-title">Data Diri</a>
                    </li>
                    <li>
                        <a class="submenu-title" href="{{URL::to('riwayat')}}" >
                             Riwayat Pegawai</a>
                    </li>
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
                        <a class="submenu-title" href="#" >
                             Daftar Nilai <label class="badge badge-light-danger">!</label>
                        </a>
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
                            <li><a href="#">Ganti Kuliah - Pengganti <label class="badge badge-light-danger">!</label></a></li>

                        </ul>
                    </li>
                    <li>
                        <a class="submenu-title" href="#">
                            <span>Ujian <label class="badge badge-light-danger">!</label></span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{Url::to('admin/akademik/pengaturan-ujian')}}">Pengaturan</a></li>{{--  Ada menu untuk toggle ujian sedang dibuka atau ditutup--}}
                            <li><a href="{{Url::to('admin/akademik/nilai')}}">Input Nilai</a></li>
                            {{-- <li><a href="#">Posting Nilai <label class="badge badge-light-danger">!</label></a></li> --}}
                            {{-- <li><a href="#">Input Nilai Konversi <label class="badge badge-light-danger">!</label></a></li> --}}
                            <li><a href="#">Komplain Nilai / Susulan <label class="badge badge-light-danger">!</label></a></li>
                        </ul>
                    </li>
                     <li><a href="#">Semester Antara <label class="badge badge-light-danger">!</label></a></li>
                     <li><a href="#">Remidial <label class="badge badge-light-danger">!</label></a></li>
                     <li><a href="{{Url::to('admin/akademik/khs')}}">KHS</a></li>
                     <li><a href="{{Url::to('admin/akademik/kuesioner')}}">Kuesioner Mahasiswa</a></li>
                     <li><a href="{{Url::to('admin/akademik/perwalian')}}">Perwalian</a></li>


                    <li>
                        <a class="submenu-title" href="#">
                            <span>Yuidisium <label class="badge badge-light-danger">!</label></span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Setting <label class="badge badge-light-danger">!</label></a></li>
                            <li><a href="#">Proses <label class="badge badge-light-danger">!</label></a></li>
                            <li><a href="#">Cetak <label class="badge badge-light-danger">!</label></a></li>

                        </ul>
                    </li>
                     <li><a href="#">Persuratan <label class="badge badge-light-danger">!</label></a></li>
                     <li>
                        <a class="submenu-title" href="#">
                            <span>Transkrip / Ijazah <label class="badge badge-light-danger">!</label></span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Print Ijazah <label class="badge badge-light-danger">!</label></a></li>
                            <li><a href="#">Print Transkrip <label class="badge badge-light-danger">!</label></a></li>
                            <li><a href="#">Legalisir <label class="badge badge-light-danger">!</label></a></li>

                        </ul>
                    </li>
                    <li>
                        <a class="submenu-title" href="#">
                            <span>Skripsi</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{ Route('admin.skripsi.manajemen.daftar.index') }}">Manajemen Skripsi </a></li>
                            <li><a href="{{Route('pembimbing.index')}}">Input Dosbing </a></li>
                            <li><a href="{{ Route('sidang.index') }}">Jadwal Sidang </a></li>
                            <li><a href="#">Nilai <label class="badge badge-light-danger">!</label></a></li>
                        </ul>
                    </li>
                     <li><a href="#">Evaluasi Studi <label class="badge badge-light-danger">!</label></a></li>
                     <li><a href="#">Histori Jadwal <label class="badge badge-light-danger">!</label></a></li>
                     <li>
                        <a class="submenu-title" href="#">
                            <span>Cetak Berkas <label class="badge badge-light-danger">!</label></span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Absensi <label class="badge badge-light-danger">!</label></a></li>
                            <li><a href="#">Ujian <label class="badge badge-light-danger">!</label></a></li>
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
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" >
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
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{route('Perwalian')}}" >
                <span><i class="fa fa-bookmark"></i> Perwalian</span></a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-bookmark"></i> Kuesioner Kepuasan Dosen</span></a> <label class="badge badge-light-danger">!</label>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{URL::to('dsn/skripsi/pengajuan')}}" >
                <span><i class="fa fa-users"></i> Pengajuan Mahasiswa</span></a>
            </li>

          </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </nav>
    </div>
  </div>
