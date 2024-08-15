<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
      <div class="logo-wrapper"><a href="{{ route('dashboard')}}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_dark.png') }}" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
      <div class="logo-icon-wrapper"><a href="{{ route('dashboard')}}"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a></div>
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

            <li class="sidebar-list">
                <a class="sidebar-link sidebar-title" href="#">
                    <span><i class="fa fa-book"></i> Master Data</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a class="submenu-title" href="#">
                            <span>PT (Perguruan Tinggi)</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{URL::to('admin/masterdata/pt')}}">Profile</a></li>
                            <li><a href="{{URL::to('admin/masterdata/pt/atribut')}}">Atribut</a></li>
                            <li><a href="{{URL::to('admin/masterdata/pt/renstra')}}">Renstra</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="submenu-title" href="#">
                            <span>PRODI</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{URL::to('admin/masterdata/program-studi')}}">Daftar Prodi</a></li>
                            <li><a href="{{URL::to('admin/masterdata/prodi/atribut/1')}}">Atribut Prodi</a></li>
                            <li><a href="#">Akreditasi</a></li>
                            <li><a href="{{URL::to('admin/masterdata/prodi/renstra/1')}}">Renstra</a></li>
                        </ul>
                    </li>
                    <li><a href="{{URL::to('admin/masterdata/ta')}}">Tahun Ajaran</a></li>
                    <li><a href="#">Jenjang</a></li>
                    <li><a href="{{URL::to('admin/masterdata/jabatan_struktural')}}">Jabatan</a></li>
                    <li><a href="{{URL::to('admin/masterdata/user')}}">User Manajemen</a></li>
                    {{-- <li><a href="{{ route('ruang/')}}">Ruang</a></li> --}}
                    {{-- <li><a href="{{URL::to('admin/masterdata/sesi')}}">Sesi</a></li> --}}
                    <li><a href="{{URL::to('admin/masterdata/asal-sekolah')}}">Sekolah</a></li>
                    <li><a href="{{URL::to('admin/masterdata/waktu')}}">Waktu</a></li>
                    {{-- <li><a href="{{URL::to('admin/masterdata/rumpun')}}">Rumpun</a></li> --}}
                    {{-- <li><a href="{{URL::to('admin/masterdata/fakultas')}}">Fakultas</a></li> --}}
                </ul>
            </li>

            <li class="sidebar-list">
                <a class="sidebar-link sidebar-title" href="#">
                    <span><i class="fa fa-users"></i> Mahasiswa</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ URL::to('mahasiswa') }}">Data Mahasiswa</a></li>
                </ul>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-file-text"></i> Admisi</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="{{URL::to('admin/admisi/gelombang')}}">Gelombang</a></li>
                    <li><a href="{{URL::to('admin/admisi/peserta')}}">Pendaftaran Mahasiswa Baru</a></li>
                    <li><a href="{{URL::to('admin/admisi/daftar_soal')}}">Pengaturan Ujian PMB</a></li>
                    <li><a href="{{URL::to('admin/admisi/peringkat')}}">Peringkat PMDP</a></li>  {{--  Ada menu untuk toggle krs sedang dibuka atau ditutup--}}
                    <li><a href="{{URL::to('admin/admisi/verifikasi')}}">Verifikasi Pendaftaran</a></li>
                    <li><a href="{{URL::to('admin/admisi/verifikasi/pembayaran')}}">Verifikasi Pembayaran Daftar</a></li>
                    <li><a href="{{URL::to('admin/admisi/pengumuman')}}">Surat Pengumuman</a></li> {{-- Butuh format surat pengumuman resmi dari pihak kampus --}}
                    <li><a href="#">KTM (s)</a></li>
                    <li><a href="#">Statistik</a></li>
                    <li><a href="#">Verifikasi Pembayaran Registrasi</a></li>
                    <li><a href="#">History PMB</a></li>
                    {{-- <li><a href="#">Berita Pendaftaran</a></li> --}}
                    <li><a href="#">Berita Gambar/Slideshow</a></li>
                    <li><a href="#">User PMB Online</a></li> {{-- Daftar User pmb online--}}
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-university"></i> Akademik</span></a>
                <ul class="sidebar-submenu">
                     <li><a href="{{URL::to('admin/masterdata/matakuliah')}}">Matakuliah</a></li>
                     <li><a href="{{URL::to('admin/masterdata/jadwal')}}">Jadwal</a></li>
                     <li><a href="#">Plot Jadwal Ajar</a></li>
                     <li><a href="{{URL::to('admin/masterdata/jadwal-harian')}}">Kontrol Jadwal</a></li>
                     <li>
                        <a class="submenu-title" href="#">
                            <span>KRS</span>  {{--  Ada menu untuk toggle krs sedang dibuka atau ditutup--}}
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{ URL::to('admin/masterdata/krs') }}">Input KRS</a></li>
                            <li><a href="#">Statistik KRS</a></li>
                            <li><a href="#">Monitoring KRS</a></li>
                        </ul>
                    </li>
                     <li><a href="#">Semester Antara</a></li>
                     <li><a href="#">Remidial</a></li>
                     <li><a href="#">KHS</a></li>
                     <li>
                        <a class="submenu-title" href="#">
                            <span>Ujian</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Pengaturan</a></li>{{--  Ada menu untuk toggle ujian sedang dibuka atau ditutup--}}
                            <li><a href="#">Input Nilai</a></li>
                            <li><a href="#">Posting Nilai</a></li>
                            <li><a href="#">Input Nilai Konversi</a></li>
                            <li><a href="#">Komplain Nilai / Susulan</a></li>
                        </ul>
                    </li>
                     <li><a href="#">Perubahan Status Akademik</a></li>
                     <li><a href="#">Setting Pertemuan</a></li>
                     <li><a href="#">Perwalian</a></li>

                     <li>
                        <a class="submenu-title" href="#">
                            <span>Presensi</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Input Presensi</a></li>
                            <li><a href="#">Ganti Kuliah - Pengganti</a></li>

                        </ul>
                    </li>
                    <li>
                        <a class="submenu-title" href="#">
                            <span>Yuidisium</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Setting</a></li>
                            <li><a href="#">Proses</a></li>
                            <li><a href="#">Cetak</a></li>

                        </ul>
                    </li>
                     <li><a href="#">Persuratan</a></li>
                     <li>
                        <a class="submenu-title" href="#">
                            <span>Transkrip / Ijazah</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Print Ijazah</a></li>
                            <li><a href="#">Print Transkrip</a></li>
                            <li><a href="#">Legalisir</a></li>

                        </ul>
                    </li>
                    <li>
                        <a class="submenu-title" href="#">
                            <span>Skripsi</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Manajemen Skripsi</a></li>
                            <li><a href="#">Input Dosbing</a></li>
                            <li><a href="#">Jadwal Sidang</a></li>
                            <li><a href="#">Nilai</a></li>
                        </ul>
                    </li>
                     <li><a href="#">Evaluasi Studi</a></li>
                     <li><a href="#">Histori Jadwal</a></li>
                     <li>
                        <a class="submenu-title" href="#">
                            <span>Cetak Berkas</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Absensi</a></li>
                            <li><a href="#">Ujian</a></li>
                        </ul>
                    </li>
                 </ul>
             </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-users"></i> Kepegawaian</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="{{URL::to('admin/kepegawaian/pegawai')}}">Data Pegawai</a></li>
                    {{-- <li><a href="#">Profil Pegawai</a></li> --}}
                    <li><a href="#">Absensi</a></li>
                    <li><a href="{{URL::to('admin/kepegawaian/jamkerja')}}">Jam Kerja Dosen</a></li>
                    <li><a href="#">Keterlambatan</a></li>
                    <li><a href="#">Statistik</a></li>
                    <li><a href="#">Struktur</a></li>
                    {{-- <li>
                        <a class="submenu-title" href="#">
                            <span>Riwayat</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Mengajar</a></li>
                            <li><a href="#">Penelitian</a></li>
                            <li><a href="#">Pengabdian</a></li>
                            <li><a href="#">karya Ilmiah</a></li>
                            <li><a href="#">Organisasi</a></li>
                            <li><a href="#">Repositori</a></li>
                            <li><a href="#">Jabatan Struktural</a></li>
                            <li><a href="#">Jabatan Fungsional</a></li>
                            <li><a href="#">Pekerjaan</a></li>
                            <li><a href="#">Pendidikan</a></li>
                        </ul>
                    </li> --}}
                    <li><a href="{{URL::to('admin/kepegawaian/surat_izin')}}">Surat Izin</a></li>
                </ul>
            </li>


            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <span><i class="fa fa-money"></i> Keuangan</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="{{URL::to('admin/keuangan')}}">Buka Tutup KRS</a></li> {{-- Menu dasar untuk buka tutup krs --}}
                    <li><a href="#">Setting SKS - UKT</a></li> {{-- Untuk setting pembayaran sks dalam bentuk paket --}}
                    <li><a href="#">Input Laporan Pembayaran</a></li>
                    <li><a href="#">Buat Tagihan</a></li> {{--Buat dan Publish Tagihan --}}
                    <li><a href="#">Statistik</a></li>
                    <li><a href="#">Cetak</a></li>
                    <li><a href="#">Sync</a></li>
                    <li><a href="#">Pembayaran Lain-lain</a></li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <span><i class="fa fa-support"></i> Aset</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="{{URL::to('admin/masterdata/ruang')}}">Ruang</a></li>
                    <li><a href="#">Kendaraan</a></li>
                    <li><a href="#">Tanah & Bangunan</a></li>
                    <li><a href="#">Elektronik</a></li>
                    <li><a href="#">Pengajuan</a></li>
                    <li><a href="#">Monev</a></li>
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
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{URL::to('dosen/perwalian')}}" >
                <span><i class="fa fa-users"></i> Perwalian</span></a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{URL::to('dosen/krm')}}" >
                <span><i class="fa fa-folder"></i> KRM</span></a>
            </li>
          </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </nav>
    </div>
  </div>
