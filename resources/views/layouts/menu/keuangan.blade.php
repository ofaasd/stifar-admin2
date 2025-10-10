<li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
    <span><i class="fa fa-money"></i> Keuangan</span></a>
    <ul class="sidebar-submenu">
        <li><a href="{{URL::to('admin/keuangan')}}">Buka Tutup KRS</a></li> {{-- Menu dasar untuk buka tutup krs --}}
        <li><a href="{{URL::to('admin/keuangan/jenis_keuangan')}}">Jenis Keuangan</a></li> {{-- Untuk setting pembayaran sks dalam bentuk paket --}}
        <li><a href="{{URL::to('admin/keuangan/setting_keuangan')}}">Setting Keuangan</a></li> {{-- Untuk setting pembayaran sks dalam bentuk paket --}}
        <li><a href="{{URL::to('admin/keuangan/tagihan')}}">Buat Tagihan </a></li> {{--Buat dan Publish Tagihan --}}
        <li><a href="{{URL::to('admin/keuangan/lapor_bayar')}}">Laporan Pembayaran</a></li>
        <li><a href="{{URL::to('admin/keuangan/rekening_koran')}}">Rekening Koran</a></li>
        <li><a href="{{URL::to('admin/keuangan/pembayaran')}}">Rekap Pembayaran</a></li>
        <li><a href="#">Statistik <label class="badge badge-light-danger">!</label></a></li>
        {{-- <li><a href="#">Cetak <label class="badge   badge-light-danger">!</label></a></li> --}}
        {{-- <li><a href="#">Sync <label class="badge badge-light-danger">!</label></a></li> --}}
        {{-- <li><a href="#">Pembayaran Lain-lain <label class="badge badge-light-danger">!</label></a></li> --}}
        <li><a href="{{URL::to('admin/keuangan/bank_data_va')}}">Bank Data VA</a></li> {{-- Menu dasar untuk buka tutup krs --}}
    </ul>
</li>