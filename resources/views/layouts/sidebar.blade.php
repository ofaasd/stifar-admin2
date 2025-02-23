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
                    <li>
                        <a class="submenu-title" href="#">
                            <span>Aset</span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="{{URL::to('admin/masterdata/aset/kategori-aset')}}">Kategori</a></li>
                            <li><a href="#">Unit Kerja <label class="badge badge-light-danger">!</label></a></li>
                            <li><a href="{{URL::to('admin/masterdata/aset/aset-label')}}">Label</a></li>
                            <li><a href="{{URL::to('admin/masterdata/aset/aset-jenis-ruang')}}">Jenis Ruang</a></li>
                            <li><a href="{{URL::to('admin/masterdata/aset/aset-gedung')}}">Gedung</a></li>
                            <li><a href="{{URL::to('admin/masterdata/aset/aset-lantai')}}">Lantai</a></li>
                        </ul>
                    </li>
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
            <li class="sidebar-list">
                <a class="sidebar-link sidebar-title" href="#">
                    <span><i class="fa fa-file"></i> Berkas</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ URL::to('admin/berkas/dosen') }}">Dosen</a></li>
                    <li><a href="{{ URL::to('admin/berkas/mahasiswa') }}">Mahasiswa</a></li>
                </ul>
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
                    <li><a href="#">KTM (s) <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="{{URL::to('admin/admisi/statistik')}}">Statistik</a></li>
                    <li><a href="#">Verifikasi Pembayaran Daftar Ulang <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">History PMB <label class="badge badge-light-danger">!</label></a></li>
                    {{-- <li><a href="#">Berita Pendaftaran</a></li> --}}
                    <li><a href="{{URL::to('admin/admisi/biaya_pendaftaran')}}">Biaya Pendaftaran</a></li>
                    <li><a href="{{URL::to('admin/admisi/slideshow')}}">Berita Gambar/Slideshow</a></li>
                    <li><a href="{{URL::to('admin/admisi/user_pmb')}}">User PMB Online</a></li> {{-- Daftar User pmb online--}}
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-university"></i> Akademik</span></a>
                <ul class="sidebar-submenu">
                     <li><a href="{{URL::to('admin/masterdata/matakuliah')}}">Matakuliah</a></li>
                     <li><a href="{{URL::to('admin/masterdata/jadwal')}}">Jadwal</a></li>
                     {{-- <li><a href="#">Plot Jadwal Ajar</a></li> --}}
                     <li><a href="{{URL::to('admin/masterdata/jadwal-harian')}}">Kontrol Jadwal</a></li>
                     <li><a href="{{URL::to('admin/akademik/setting-pertemuan')}}">Setting Pertemuan</a></li>
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
                            <li><a href="{{Url::to('admin/akademik/list-absensi')}}">Input Presensi</a></li>
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
                     <li><a href="#">Perubahan Status Akademik <label class="badge badge-light-danger">!</label></a></li>

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
                            <span>Skripsi <label class="badge badge-light-danger">!</label></span>
                        </a>
                        <ul class="nav-sub-childmenu submenu-content">
                            <li><a href="#">Manajemen Skripsi <label class="badge badge-light-danger">!</label></a></li>
                            <li><a href="{{Route('admin.pembimbing.index')}}">Input Dosbing </a></li>
                            <li><a href="#">Jadwal Sidang <label class="badge badge-light-danger">!</label></a></li>
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
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#" >
                <span><i class="fa fa-users"></i> Kepegawaian</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="{{URL::to('admin/kepegawaian/pegawai')}}">Data Pegawai</a></li>
                    {{-- <li><a href="#">Profil Pegawai</a></li> --}}
                    <li><a href="#">Absensi <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="{{URL::to('admin/kepegawaian/jamkerja')}}">Jam Kerja Dosen</a></li>
                    <li><a href="#">Keterlambatan <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Statistik <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Struktur <label class="badge badge-light-danger">!</label></a></li>
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
                    <li><a href="#">Setting SKS - UKT <label class="badge badge-light-danger">!</label></a></li> {{-- Untuk setting pembayaran sks dalam bentuk paket --}}
                    <li><a href="#">Input Laporan Pembayaran <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Buat Tagihan <label class="badge badge-light-danger">!</label></a></li> {{--Buat dan Publish Tagihan --}}
                    <li><a href="#">Statistik <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Cetak <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Sync <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Pembayaran Lain-lain <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="{{URL::to('admin/keuangan/bank_data_va')}}">Bank Data VA</a></li> {{-- Menu dasar untuk buka tutup krs --}}
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <span><i class="fa fa-support"></i> Aset</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="{{URL::to('admin/masterdata/ruang')}}">Ruang</a></li>
                    <li><a href="#">Kendaraan <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Tanah & Bangunan <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Elektronik <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Pengajuan <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Monev <label class="badge badge-light-danger">!</label></a></li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <span><i class="fa fa-support"></i> Setting</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="#">Pengaturan Pengguna <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Preview Mahasiswa <label class="badge badge-light-danger">!</label></a></li>
                </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <span><i class="fa fa-support"></i> Support</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="#">Kritik dan Saran <label class="badge badge-light-danger">!</label></a></li>
                    <li><a href="#">Export Data <label class="badge badge-light-danger">!</label></a></li>
                </ul>
            </li>
            {{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{URL::to('dosen/perwalian')}}" >
                <span><i class="fa fa-users"></i> Perwalian</span></a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{URL::to('dosen/krm')}}" >
                <span><i class="fa fa-folder"></i> KRM</span></a> --}}
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <span><i class="fa fa-support"></i> Tugas Akhir</span></a>
                <ul class="sidebar-submenu">
                    <li><a href="#">Daftar Skripsi Mahasiswa</a></li>
                    <li><a href="{{Route('admin.pembimbing.index')}}">Daftar Dosen Pembimbing</a></li>
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
