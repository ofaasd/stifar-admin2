
<table class="customers"  id="myTable" cellpadding="5" width="100%">
    <tr>
        <td>Nama Lengkap</td>
        <td>{{$pegawai->nama_lengkap}}</td>
        <td>NIY</td>
        <td>{{$pegawai->npp}}</td>
        <td>No. Telp</td>
        <td>{{$pegawai->notelp}}</td>
        <td rowspan=3>
            <img src='{{ (!empty($pegawai->foto))?asset('assets/images/pegawai/' . $pegawai->foto):asset('assets/images/user/7.jpg') }}' width="100%">
        </td>
    </tr>
    <tr>
        <td>NIDN</td>
        <td>{{$pegawai->nidn}}</td>
        <td>Tempat, Tanggal Lahir</td>
        <td>{{$pegawai->tempat_lahir}}, {{date('d-m-Y', strtotime($pegawai->tanggal_lahir))}}</td>
        <td>No. HP</td>
        <td>{{$pegawai->nohp}}</td>
    </tr>
    <tr>
        <td>Jenis Kelamin</td>
        <td>{{($pegawai->jenis_kelamin == 'L')?'Laki-laki':'Perempuan'}}</td>
        <td>Jabatan Struktural</td>
        <td>{{$jabatan_struktural}}</td>
        <td>Email</td>
        <td>{{$pegawai->email}}</td>
    </tr>
    <tr>
        <td>Kebangsaan</td>
        <td>Indonesia</td>
        <td>Jabatan Fungsional</td>
        <td>{{$jabatan_fungsional}}</td>
        <td>Alamat</td>
        <td colspan=2>{{$pegawai->alamat}}</td>
    </tr>
    <tr>
        <td><b>Riwayat Pendidikan</b></td>
        <td colspan=2>
            <b>Waktu</b>
        </td>
        <td colspan=2>
            <b>Jenjang</b>
        </td>
        <td colspan=2>
            <b>Nama Universitas</b>
        </td>
    </tr>
    @foreach($pegawai_pendidikan as $row)
        <tr>
            <td></td>
            <td colspan=2>{{date('d-m-Y', strtotime($row->tanggal_ijazah))}}</td>
            <td colspan=2>{{$row->jenjang}} {{$row->jurusan}}</td>
            <td colspan=2>{{$row->universitas}}</td>
        </tr>
    @endforeach
    <tr>
        <td><b>Riwayat Organisasi</b></td>
        <td colspan=2>
            <b>Waktu</b>
        </td>
        <td colspan=2>
            <b>Jabatan</b>
        </td>
        <td colspan=2>
            <b>Nama Organisasi</b>
        </td>
    </tr>
    @foreach($pegawai_organisasi as $row)
        <tr>
            <td></td>
            <td colspan=2>{{$row->tahun}}-{{($row->tahun_keluar == 0)?"Sekarang":$row->tahun_keluar}}</td>
            <td colspan=2>{{$row->jabatan}}</td>
            <td colspan=2>{{$row->nama_organisasi}}</td>
        </tr>
    @endforeach
    <tr>
        <td><b>Riwayat Pekerjaan</b></td>
        <td colspan=2>
            <b>Waktu</b>
        </td>
        <td colspan=2>
            <b>Posisi</b>
        </td>
        <td colspan=2>
            <b>Nama Pekerjaan</b>
        </td>
    </tr>
    @foreach($pegawai_pekerjaan as $row)
        <tr>
            <td></td>
            <td colspan=2>{{$row->tahun_masuk}}-{{($row->tahun_keluar == 0)?"Sekarang":$row->tahun_keluar}}</td>
            <td colspan=2>{{$row->posisi}}</td>
            <td colspan=2>{{$row->perusahaan}}</td>
        </tr>
    @endforeach
    <tr>
        <td><b>Riwayat Mengajar</b></td>
        <td colspan=2>
            <b>Tahun Akademik</b>
        </td>
        <td colspan=2>
            <b>Mata Kuliah</b>
        </td>
        <td colspan=2>
            <b>Prodi</b>
        </td>
    </tr>
    @foreach($pegawai_mengajar as $row)
        <tr>
            <td></td>
            <td colspan=2>{{$row->tahun}}-{{($row->tahun+1)}}</td>
            <td colspan=2>{{$row->mata_kuliah}}</td>
            <td colspan=2>{{$row->prodi}}</td>
        </tr>
    @endforeach
    <tr>
        <td><b>Riwayat Penelitian</b></td>
        <td colspan=2>
            <b>Judul</b>
        </td>
        <td>
            <b>Tahun</b>
        </td>
        <td>
            <b>Jenis Penelitian</b>
        </td>
        <td colspan=2>
            <b>Sumber Dana</b>
        </td>
    </tr>
    @foreach($pegawai_penelitian as $row)
        <tr>
            <td></td>
            <td colspan=2>{{$row->judul}}</td>
            <td>{{$row->tahun}}</td>
            <td>{{$row->jenis_penelitian}}</td>
            <td colspan=2>{{$row->sumber_dana}}</td>
        </tr>
    @endforeach
</table>
