@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}} - {{ $nama_mk }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">

                    </div>
                    <div class="card-body">
                    <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="masterJadwal-tab" data-bs-toggle="tab" href="#masterJadwal" role="tab" aria-controls="masterJadwal" aria-selected="true">Master Jadwal</a></li>
                            <!-- <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="koorMK-tabs" href="{{ url('/admin/masterdata/koordinator-mk/'.$id_mk) }}" role="tab" aria-controls="koorMK" aria-selected="false" tabindex="-1">Koordinator Matakuliah</a></li> -->
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="DsnMK-tab" href="{{ url('/admin/masterdata/anggota-mk/'.$id_mk) }}" role="tab" aria-controls="DsnMK" aria-selected="false" tabindex="-1">Koordinator & Anggota Matakuliah</a></li>
                            <!-- <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="Pertemuan-tab" href="{{ url('/admin/masterdata/pertemuan-mk/'.$id_mk) }}" role="tab" aria-controls="Pertemuan" aria-selected="false" tabindex="-1">Pertemuan Matakuliah</a></li> -->
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="masterJadwal" role="tabpanel" aria-labelledby="masterJadwal-tab">
                                @csrf
                                <div class="row" style="padding-top: 20px;">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="kode_jadwal" class="form-label">Kode Jadwal</label>
                                            <input type="text" name="kode_jadwal" id="kode_jadwal" class="form-control" readonly>
                                            <input type="text" name="id_mk" id="id_mk" value="{{ $id_mk }}" hidden="" readonly="">
                                        </div>
                                        <div class="mb-3">
                                            <label for="hari" class="form-label">Hari</label>
                                            <select name="hari" id="hari" class="form-control">
                                                @foreach($days as $h)
                                                    <option value="{{ $h['hari'] }}">{{ $h['hari'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="ruang" class="form-label">Ruang</label>
                                            <select name="ruang" id="ruang" class="form-control">
                                                @foreach($ruang as $r)
                                                    <option value="{{ $r['id'] }}">{{ $r['nama_ruang'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sesi" class="form-label">Sesi</label>
                                            <select name="sesi" id="sesi" class="form-control">
                                                @foreach($sesi as $s)
                                                    <option value="{{ $s['id'] }}">{{ $s['nama_sesi'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tp" class="form-label">T/P</label>
                                            <select name="tp" id="tp" class="form-control">
                                                <option value="T">T</option>
                                                <option value="P">P</option>
                                                <option value="TP">TP</option>
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" onclick="tambahJadwal()"><i class="fa fa-save"></i> Tambahkan</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="kel" class="form-label">Kelompok/Kelas</label>
                                            <select name="kel" id="kel" class="form-control">
                                                <option value="" selected disabled> Pilih Kelompok </option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                                <option value="E">E</option>
                                                <option value="F">F</option>
                                                <option value="G">G</option>
                                                <option value="H">H</option>
                                                <option value="I">I</option>
                                                <option value="J">J</option>
                                                <option value="K">K</option>
                                                <option value="L">L</option>
                                                <option value="M">M</option>
                                                <option value="N">N</option>
                                                <option value="O">O</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kuota" class="form-label">Kuota</label>
                                            <input type="number" name="kuota" id="kuota" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Aktif">Aktif</option>
                                                <option value="Tidak Aktif">Tidak Aktif</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                                            <select name="tahun_ajaran" id="tahun_ajaran" class="form-control" required>
                                                <option value="" selected disabled>Tahun Ajaran</option>
                                                @foreach ($ta as $t)
                                                    <option value="{{ $t['id'] }}">{{ $t['kode_ta'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="dosen" class="form-label">Dosen Pengampu</label>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <select name="dosen0" id="dosen0" class="form-control">
                                                        <option value="Pilih Dosen Pengampu">Pilih Dosen Pengampu</option>
                                                        @foreach ($anggota as $dsn)
                                                            <option value="{{ $dsn['id_pegawai_bio'] }}">{{ $dsn['nama_lengkap'] }}, {{ $dsn['gelar_belakang'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div id="input0">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <button onclick="javascript:tambahRow();" class="btn btn-primary">+</button>
                                                    <!-- <button onclick="javascript:kurangRow();" class="btn btn-danger">-</button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                @foreach($warning as $value)
                                <div class="alert alert-warning">
                                    {{$value}}
                                </div>
                                @endforeach
                                <div class="table-responsive">
                                    <table class="display" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Kode Jadwal</th>
                                                <th>Hari</th>
                                                <th>Ruang</th>
                                                <th>Sesi</th>
                                                <th>Kelompok</th>
                                                <th>Kuota</th>
                                                <th>Status</th>
                                                <th>Tahun Ajaran</th>
                                                <th>Set Pertemuan</th>
                                                <th>Dosen Pengampu</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jadwal as $row)
                                                <tr>
                                                    <td>{{ $id++ }}</td>
                                                    <td>{{ $row['kode_jadwal'] }}</td>
                                                    <td>{{ $row['hari'] }}</td>
                                                    <td>{{ $row['nama_ruang'] }}</td>
                                                    <td>{{ $row['nama_sesi'] }}</td>
                                                    <td>{{ $row['kel'] }}</td>
                                                    <td>{{ $row['kuota'] }}</td>
                                                    <td>{{ $row['status'] }}</td>
                                                    <td>{{ $row['kode_ta'] }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-success btn-xs" onclick="setPertemuan({{ $row['id'] }})" class="btn btn-primary btn-sm btn-icon edit-record" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalPertemuan{{ $row['id'] }}"><i class="fa fa-gear"></i> Set Pertemuan</a>
                                                        <div class="modal fade" id="modalPertemuan{{ $row['id'] }}" tabindex="-1" aria-labelledby="tambahbaru" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <div class="modal-toggle-wrapper">
                                                                            <h5 style="text-align: center">Daftar Pertemuan</h5>
                                                                            <div class="form-group mt-2">
                                                                                <label for="kelompok">Tambah Pertemuan :</label>
                                                                                <input type="text" hidden value="<?= $row['id']?>" id="idjadwal">
                                                                                <input type="date" id="tgl_pertemuan<?= $row['id']?>" class="form-control" />
                                                                                <br>
                                                                                <label for="kelompok">Dosen Pengampu :</label>
                                                                                <select name="nama_anggota" id="nama_anggota<?= $row['id']?>" class="form-control" required>
                                                                                    <option value="" selected disabled>Pilih Dosen</option>
                                                                                    @foreach($anggota as $dsn)
                                                                                        <option value="{{ $dsn['id_dsn'] }}">{{ $dsn['nama_lengkap'] }}, {{ $dsn['gelar_belakang'] }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="mt-2"></div>
                                                                            <button type="button" onclick="simpanPertemuan(<?=$row['id']?>)" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Tambah Pertemuan</button>
                                                                            <hr>
                                                                            <div id="tablePertemuan{{ $row['id'] }}"></div>
                                                                            <button class="btn bg-danger d-flex align-items-center gap-2 text-light ms-auto btn-sm" type="button" data-bs-dismiss="modal">Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="#" onclick="lihatPengampu({{ $id_mk }}, {{ $row['id'] }})" class="btn btn-primary btn-sm btn-icon edit-record" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalDsn{{ $row['id'] }}">
                                                                                    <i class="fa fa-eye"></i> Dosen Pengampu
                                                        </a>
                                                        <div class="modal fade" id="modalDsn{{ $row['id'] }}" tabindex="-1" aria-labelledby="tambahbaru" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <div class="modal-toggle-wrapper">
                                                                            <h5 style="text-align: center">Daftar Dosen Pengampu</h5>
                                                                            <div class="form-group mt-2">
                                                                                <label for="kelompok">Dosen Pengampu :</label>
                                                                                <input type="text" hidden value="<?= $row['id']?>" id="idjadwal">
                                                                                <select name="dosenPengampu<?=$row['id']?>" id="dosenPengampu<?=$row['id']?>" class="form-control">
                                                                                    <option value="Pilih Dosen Pengampu">Pilih Dosen Pengampu</option>
                                                                                    @foreach ($anggota as $dsn)
                                                                                        <option value="{{ $dsn['id_pegawai_bio'] }}">{{ $dsn['nama_lengkap'] }}, {{ $dsn['gelar_belakang'] }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="mt-2"></div>
                                                                            <button type="button" onclick="simpanPengampu(<?=$row['id']?>)" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Tambah Dosen Pengampu</button>
                                                                            <hr>
                                                                            <div id="tablePengampu{{ $row['id'] }}"></div>
                                                                            <button class="btn bg-danger d-flex align-items-center gap-2 text-light ms-auto btn-sm" type="button" data-bs-dismiss="modal">Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-warning btn-sm btn-icon edit-record" data-bs-toggle="modal" data-original-title="test" data-bs-target="#jadwalEdit{{ $row['id'] }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="{{ url('jadwal/hapus/'.$row['id']) }}" class="btn btn-danger btn-sm btn-icon edit-record"><i class="fa fa-trash"></i></a>
                                                        <div class="modal fade" id="jadwalEdit{{ $row['id'] }}" tabindex="-1" aria-labelledby="tambahbaru" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <div class="modal-toggle-wrapper">
                                                                            <h5 style="text-align: center">Edit Jadwal</h5>
                                                                            <div class="mt-2"></div>
                                                                            <div class="mb-3">
                                                                                <label for="kode_jadwal" class="form-label">Kode Jadwal</label>
                                                                                <input type="text" name="kode_jadwal" id="kode_jadwal{{ $row['id'] }}" value="{{ $row['kode_jadwal'] }}" class="form-control">
                                                                                <input type="text" name="id_mk" id="id_mk{{ $row['id'] }}" value="{{ $row['id_mk'] }}" hidden="">
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="hari" class="form-label">Hari</label>
                                                                                <select name="hari" id="hari{{ $row['id'] }}" class="form-control">
                                                                                    @foreach($days as $h)
                                                                                        <option value="{{ $h['hari'] }}" {{ $h['hari'] == $row['hari'] ? 'selected=""':'' }}>{{ $h['hari'] }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="ruang" class="form-label">Ruang</label>
                                                                                <select name="ruang" id="ruang{{ $row['id'] }}" class="form-control">
                                                                                    @foreach($ruang as $r)
                                                                                        <option value="{{ $r['id'] }}" {{ $r['id'] == $row['id_ruang'] ? 'selected=""':'' }}>{{ $r['nama_ruang'] }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="sesi" class="form-label">Sesi</label>
                                                                                <select name="sesi" id="sesi{{ $row['id'] }}" class="form-control">
                                                                                    @foreach($sesi as $s)
                                                                                        <option value="{{ $s['id'] }}" {{ $s['id'] == $row['id_sesi'] ? 'selected=""':'' }}>{{ $s['nama_sesi'] }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="tp" class="form-label">T/P</label>
                                                                                <select name="tp" id="tp{{ $row['id'] }}" class="form-control">
                                                                                    <option value="T" {{ $row['tp'] == 'T' ? 'selected=""':'' }}>T</option>
                                                                                    <option value="P" {{ $row['tp'] == 'P' ? 'selected=""':'' }}>P</option>
                                                                                    <option value="TP" {{ $row['tp'] == 'TP' ? 'selected=""':'' }}>TP</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="kel" class="form-label">Kelompok/Kelas</label>
                                                                                <select name="kel" id="kel{{ $row['id'] }}" class="form-control">
                                                                                    <option value="A" {{ $row['kel'] == 'A' ? 'selected=""':'' }}>A</option>
                                                                                    <option value="B" {{ $row['kel'] == 'B' ? 'selected=""':'' }}>B</option>
                                                                                    <option value="C" {{ $row['kel'] == 'C' ? 'selected=""':'' }}>C</option>
                                                                                    <option value="D" {{ $row['kel'] == 'D' ? 'selected=""':'' }}>D</option>
                                                                                    <option value="E" {{ $row['kel'] == 'E' ? 'selected=""':'' }}>E</option>
                                                                                    <option value="F" {{ $row['kel'] == 'F' ? 'selected=""':'' }}>F</option>
                                                                                    <option value="G" {{ $row['kel'] == 'G' ? 'selected=""':'' }}>G</option>
                                                                                    <option value="H" {{ $row['kel'] == 'H' ? 'selected=""':'' }}>H</option>
                                                                                    <option value="I" {{ $row['kel'] == 'I' ? 'selected=""':'' }}>I</option>
                                                                                    <option value="J" {{ $row['kel'] == 'J' ? 'selected=""':'' }}>J</option>
                                                                                    <option value="K" {{ $row['kel'] == 'K' ? 'selected=""':'' }}>K</option>
                                                                                    <option value="L" {{ $row['kel'] == 'L' ? 'selected=""':'' }}>L</option>
                                                                                    <option value="M" {{ $row['kel'] == 'M' ? 'selected=""':'' }}>M</option>
                                                                                    <option value="N" {{ $row['kel'] == 'N' ? 'selected=""':'' }}>N</option>
                                                                                    <option value="N" {{ $row['kel'] == 'O' ? 'selected=""':'' }}>O</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="kuota" class="form-label">Kuota</label>
                                                                                <input type="text" name="kuota" id="kuota{{ $row['id'] }}" value="{{ $row['kuota'] }}" class="form-control">
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="status" class="form-label">Status</label>
                                                                                <select name="status" id="status{{ $row['id'] }}" class="form-control">
                                                                                    <option value="Aktif" {{ $row['status'] == 'Aktif' ? 'selected=""':'' }}>Aktif</option>
                                                                                    <option value="Tidak Aktif" {{ $row['status'] == 'Tidak Aktif' ? 'selected=""':'' }}>Tidak Aktif</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                                                                                <select name="tahun_ajaran" id="tahun_ajaran{{ $row['id'] }}" class="form-control">
                                                                                    <option value="" selected disabled>Tahun Ajaran</option>
                                                                                    @foreach ($ta as $t)
                                                                                        <option value="{{ $t['id'] }}" {{ $t['id'] == $row['id_tahun'] ? 'selected=""':'' }}>{{ $t['kode_ta'] }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <button type="button" onclick="editJadwal(<?=$row['id']?>)" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Edit Jadwal</button>

                                                                            <button class="btn bg-danger d-flex align-items-center gap-2 text-light ms-auto btn-sm" type="button" data-bs-dismiss="modal">Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>

    <script>
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })
        })
        const baseUrl = {!! json_encode(url('/')) !!};
        counter = 0;
        function tambahRow(){
            counterNext = counter + 1;
            var dosen = `<br>
                            <select name="dosen${counterNext}" id="dosen${counterNext}" class="form-control">
                                <option value="" selected disabled>Pilih Dosen Pengampu</option>
                                <?php
                                    foreach($anggota as $dosen){
                                        echo '<option value="'. $dosen['id_pegawai_bio'] .'">'. $dosen['nama_lengkap'] .','. $dosen['gelar_belakang'] .'</option>';
                                    }
                                ?>
                            `;

            dosen += `</select>
                        <div id="input${counterNext}"></div>`;
            document.getElementById("input"+counter).innerHTML = dosen;
            counter++;
        }
        function setPertemuan(idjadwal){
            $.ajax({
                url: baseUrl+'/jadwal/daftar-pertemuan',
                type: 'post',
                data: {
                    id_jadwal: idjadwal
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    var result = res.kode
                    var list = res.pertemuan

                    if(result == 200){
                        var table = `
                                        <table class="table">
                                            <tr>
                                                <td>Tanggal Pertemuan</td>
                                                <td>Dosen Pengampu</td>
                                                <td>Aksi</td>
                                            </tr>
                                    `;
                        for (let i = 0; i < list.length; i++) {
                            table += `
                                        <tr>
                                            <td>${ list[i].tgl_pertemuan }</td>
                                            <td>${ list[i].nama_lengkap }</td>
                                            <td>
                                                <a href="{{ url('jadwal/hapus-pertemuan') }}/${ list[i].id }" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Hapus</a>
                                            </td>
                                        </tr>
                                    `
                        }
                        table += '</table>'
                        $('#tablePertemuan'+idjadwal).html(table);
                    }else{
                        alert('server error');
                    }
                }
            })
        }
        function simpanPertemuan(idjadwal){
            var tgl_pertemuan = $('#tgl_pertemuan'+idjadwal).val();
            var nama_anggota = $('#nama_anggota'+idjadwal).val();
            $.ajax({
                url: baseUrl+'/jadwal/tambah-pertemuan',
                type: 'post',
                data: {
                    id_jadwal: idjadwal,
                    tgl_pertemuan: tgl_pertemuan,
                    nama_pengampu:nama_anggota
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    var result = res.kode
                    var list = res.pertemuan

                    if(result == 200){
                        var table = `
                                        <table class="table">
                                            <tr>
                                                <td>Tanggal Pertemuan</td>
                                                <td>Dosen Pengampu</td>
                                                <td>Aksi</td>
                                            </tr>
                                    `;
                        for (let i = 0; i < list.length; i++) {
                            table += `
                                        <tr>
                                            <td>${ list[i].tgl_pertemuan }</td>
                                            <td>${ list[i].nama_lengkap }</td>
                                            <td>
                                                <a href="{{ url('jadwal/hapus-pertemuan') }}/${ list[i].id }" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Hapus</a>
                                            </td>
                                        </tr>
                                    `
                        }
                        table += '</table>'
                        $('#tablePertemuan'+idjadwal).html(table);
                    }else{
                        alert('server error');
                    }
                }
            })
        }
        function simpanPengampu(idjadwal){
            var dsn = $('#dosenPengampu'+idjadwal).val();
            $.ajax({
                url: baseUrl+'/jadwal/tambah-pegampu',
                type: 'post',
                data: {
                    id_dsn: dsn,
                    id_jadwal: idjadwal
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    var result = res.kode
                    if(result == 200){
                        window.location.href = baseUrl+'/admin/masterdata/jadwal/create/'+ {{ $id_mk }};
                    }
                }
            })
        }
        function lihatPengampu(idmk, idjadwal){
            $.ajax({
                url: baseUrl+'/jadwal/pengampu',
                type: 'post',
                data: {
                    idmk: idmk,
                    idjadwal: idjadwal
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function(res){
                    var data = res.daftar;
                    var table = `<table class="table">
                                    <tr>
                                        <th>NPP</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>`
                    for (let i = 0; i < data.length; i++) {
                        table += `
                                    <tr>
                                        <td>${ data[i].npp }</td>
                                        <td>${ data[i].nama_lengkap }, ${ data[i].gelar_belakang }</td>
                                        <td>
                                            <a class="btn btn-danger btn-sm" href="{{ url('jadwal/hapus-pengampu') }}/${ data[i].id }"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                `
                    }
                    table += '</table>'
                    $('#tablePengampu'+idjadwal).html(table);
                }
            });
        }
        function editJadwal(id){
            var kjadwal = $('#kode_jadwal'+id).val();
            var kel = $('#kel'+id).val();
            var kuota = $('#kuota'+id).val();
            var hari = $('#hari'+id).val();
            var ruang = $('#ruang'+id).val();
            var sesi = $('#sesi'+id).val();
            var tp = $('#tp'+id).val();
            var status = $('#status'+id).val();
            var id_mk = $('#id_mk'+id).val();
            var tahun_ajaran = $('#tahun_ajaran'+id).val();

            if(kjadwal == ''){
                // sweetalert
                swal({
                        icon: 'warning',
                        title: 'Galat!',
                        text: 'Kode Jadwal Harus Isi.',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
            }
            if(kel == ''){
                // sweetalert
                swal({
                        icon: 'warning',
                        title: 'Galat!',
                        text: 'Kelompok/Kelas Harus Isi.',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
            }
            if(kuota == ''){
                // sweetalert
                swal({
                        icon: 'warning',
                        title: 'Galat!',
                        text: 'Kuota Harus Isi.',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
            }
            $.ajax({
                url: baseUrl+'/admin/masterdata/jadwal/update',
                type: 'post',
                dataType: 'json',
                data: {
                    id:id,
                    kjadwal:kjadwal,
                    kel:kel,
                    kuota:kuota,
                    hari:hari,
                    ruang:ruang,
                    sesi:sesi,
                    tp:tp,
                    id_mk:id_mk,
                    status:status,
                    tahun_ajaran:tahun_ajaran
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    if(res.kode == 203){
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Jadwal Bentrok dengan Kode Jadwal : '+ res.kode_jadwal,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                    if(res.kode == 204){
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Dosen Bentrok, Mohon dicek ulang!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Jadwal Berhasil Terinputkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl+'/admin/masterdata/jadwal/create/'+id_mk;
                    }
                }
            });
        }
        function tambahJadwal(){
            var kjadwal = $('#kode_jadwal').val();
            var kel = $('#kel').val();
            var kuota = $('#kuota').val();
            var hari = $('#hari').val();
            var ruang = $('#ruang').val();
            var sesi = $('#sesi').val();
            var tp = $('#tp').val();
            var status = $('#status').val();
            var id_mk = $('#id_mk').val();
            var tahun_ajaran = $('#tahun_ajaran').val();

            if(kel == ''){
                // sweetalert
                swal({
                        icon: 'warning',
                        title: 'Galat!',
                        text: 'Kelompok/Kelas Harus Isi.',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
            }
            if(kuota == ''){
                // sweetalert
                swal({
                        icon: 'warning',
                        title: 'Galat!',
                        text: 'Kuota Harus Isi.',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
            }
            var dsn = []
            for (let i = 0; i <= counter; i++) {
                var dosen = $('#dosen'+i).val();
                dsn.push(dosen)
            }
            $.ajax({
                url: baseUrl+'/admin/masterdata/jadwal/create',
                type: 'post',
                dataType: 'json',
                data: {
                    kjadwal:kjadwal,
                    kel:kel,
                    kuota:kuota,
                    hari:hari,
                    ruang:ruang,
                    sesi:sesi,
                    tp:tp,
                    id_mk:id_mk,
                    status:status,
                    dsn:dsn,
                    tahun_ajaran:tahun_ajaran
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    if(res.kode == 203){
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Jadwal Bentrok dengan Kode Jadwal : '+ res.kode_jadwal,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                    if(res.kode == 204){
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Dosen Bentrok, Mohon dicek ulang!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Jadwal Berhasil Terinputkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl+'/admin/masterdata/jadwal/create/'+id_mk;
                    }
                }
            });
        }
    </script>
@endsection
