<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
      <div class="logo-wrapper"><a href="{{ route('/')}}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_dark.png') }}" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
      <div class="logo-icon-wrapper"><a href="{{ route('/')}}"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn">
              <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{ route('index')}}" >
                <span><i class="fa fa-home"></i> Dashboard</span></a>
            </li>
            <li class="sidebar-list">
                <a class="sidebar-link sidebar-title" href="#">
                    <span><i class="fa fa-book"></i> Master Data</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('ruang')}}">Ruang</a></li>
                    <li><a href="#">Waktu</a></li>
                    <li><a href="#">Rumpun</a></li>
                    <li><a href="#">Fakultas</a></li>
                    <li><a href="#">Program Studi</a></li>
                    <li><a href="#">Matakuliah</a></li>
                    <li><a href="#">Kurikulum</a></li>
                    <li><a href="#">Rombel</a></li>
                </ul>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-users"></i> Kepegawaian</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="#">Data Pegawai</a></li>
                    <li><a href="#">Profil Pegawai</a></li>
                    <li><a href="#">Lihat KRM</a></li>
                    <li><a href="#">Struktur</a></li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
               <span><i class="fa fa-university"></i> Akademik</span></a>
               <ul class="sidebar-submenu">
                    <li><a href="#">Data Mahasiswa</a></li>
                    <li><a href="#">Pengaturan NIM</a></li>
                    <li><a href="#">Jadwal</a></li>
                    <li><a href="#">KRS</a></li>  {{--  Ada menu untuk toggle krs sedang dibuka atau ditutup--}}
                    <li><a href="#">Perwalian</a></li>
                    <li><a href="#">Setting Pertemuan</a></li>
                    <li><a href="#">Presensi</a></li>
                    <li><a href="#">Nilai</a></li>
                    <li><a href="#">Pengaturan Ujian</a></li>
                    <li><a href="#">Reset Password</a></li>
                    <li><a href="#">KHS</a></li>
                    <li><a href="#">Yudisium</a></li>
                    <li><a href="#">Transkrip / Ijazah</a></li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-file-text"></i> Admisi</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="#">Pendaftaran Mahasiswa</a></li>
                    <li><a href="#">Surat Pengumuman</a></li>
                    <li><a href="#">Statistik</a></li>
                    <li><a href="#">Peringkat PMDP</a></li>  {{--  Ada menu untuk toggle krs sedang dibuka atau ditutup--}}
                    <li><a href="#">Gelombang</a></li>
                    <li><a href="#">USPI</a></li>
                    <li><a href="#">Pembayaran</a></li>
                    <li><a href="#">Daftar Sekolah</a></li>
                    <li><a href="#">Verifikasi PMB Online</a></li>
                    <li><a href="#">Daftar PMB Online</a></li>
                    <li><a href="#">Pengaturan Ujian PMB</a></li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <span><i class="fa fa-money"></i> Keuangan</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="#">Verifikasi Keuangan Mahasiswa</a></li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <span><i class="fa fa-support"></i> Setting</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="#">Pengaturan Pengguna</a></li>
                    <li><a href="#">Preview Mahasiswa</a></li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <span><i class="fa fa-support"></i> Support</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="#">Kritik dan Saran</a></li>
                    <li><a href="#">Export Data</a></li>
                </ul>
            </li>
          </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </nav>
    </div>
  </div>
