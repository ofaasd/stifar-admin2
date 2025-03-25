@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            @include('admin.akademik.jadwal.note')
            <div class="col-md-12 project-list">
                <div class="card">
                   <div class="row">
                      <div class="col-md-12">
                         <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                            <li class="nav-item"><a href="{{URL::to('admin/masterdata/jadwal-harian')}}" class="nav-link {{($id_prodi==0)?"active":""}}" ><i data-feather="target"></i>All</a></li>
                            @foreach($prodi as $prod)
                                <li class="nav-item"><a href="{{URL::to('admin/masterdata/jadwal-harian/prodi/' . $prod->id)}}" class="nav-link {{($id_prodi==$prod->id)?"active":""}}" style="font-size:10pt;"><i data-feather="info"></i>{{$nama[$prod->id]}} </a></li>
                            @endforeach
                         </ul>
                      </div>
                   </div>
                </div>
            </div>
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">

                    </div>
                    <div class="card-body">
                        <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="masterJadwal-tab" href="{{ url('/admin/masterdata/jadwal') }}" role="tab" aria-controls="masterJadwal" aria-selected="true">Jadwal Matakuliah</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="jadwalHarian-tab" href="{{ url('/admin/masterdata/jadwal-harian') }}" aria-controls="jadwalHarian" aria-selected="false">Jadwal Harian</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="distribusi-sks-tab" href="{{ url('/admin/masterdata/distribusi-sks') }}" aria-controls="distribusiSks" aria-selected="false">Distribusi SKS</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="jadwalHarian" role="tabpanel" aria-labelledby="jadwalHarian-tab">
                                <div class="table-responsive mt-2">
                                    <div class="row">
                                        @csrf
                                        <div class="col-sm-6">
                                            <label for="hari">Pilih Hari</label>
                                            <select name="hari" id="hari" class="form-control">
                                                <option value="0">Semua Hari</option>
                                                <option value="Senin">Senin</option>
                                                <option value="Selasa">Selasa</option>
                                                <option value="Rabu">Rabu</option>
                                                <option value="Kamis">Kamis</option>
                                                <option value="Jum'at">Jum'at</option>
                                                <option value="Sabtu">Sabtu</option>
                                                <option value="Minggu">Minggu</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="matakuliah">Pilih MataKuliah</label>
                                            <select name="matakuliah" id="matakuliah" class="form-control">
                                                    <option value="0">Semua Matakuliah</option>
                                                @foreach($mk as $mk)
                                                    <option value="{{ $mk['id'] }}">{{ $mk['kode_matkul'] }} - {{ $mk['nama_matkul'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mt-2">
                                            <button onclick="JadwalHarian()" class="btn btn-primary btn-sm">Cari</button>
                                        </div>
                                        <div id="vJadwalHarian" class="mt-2">
                                            <table class="display" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Kode Jadwal</th>
                                                        <th>Hari & Waktu</th>
                                                        <th>Dosen pengampu</th>
                                                        <th>Matakuliah</th>
                                                        <th>Ruang</th>
                                                        {{-- <th>Tahun Ajaran</th>
                                                        <th>Status</th> --}}
                                                        <th>T/P</th>
                                                        <th>Kuota</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($jadwal as $jad)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jad['kode_jadwal'] }}</td>
                                                            <td>{{ $jad['hari'] }}, {{ $jad['nama_sesi'] }}</td>
                                                            <td>{!! $list_pengajar[$jad['id']] !!}</td>
                                                            <td>[{{ $jad['kode_matkul'] }}] {{ $jad['nama_matkul'] }}</td>
                                                            <td>{{ $jad['nama_ruang'] }}</td>
                                                            {{-- <td>{{ $jad['kode_ta'] }}</td>
                                                            <td>{{ $jad['status'] }}</td> --}}
                                                            <td>{{ $jad['tp'] }}</td>
                                                            <td>{{$jumlah_input_krs[$jad->id]}} / {{ $jad['kuota'] }}</td>
                                                            <td>
                                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                                    #
                                                                    {{-- <a href="#" class="btn btn-warning btn-sm btn-icon edit-record" data-bs-toggle="modal" data-original-title="test" data-bs-target="#jadwalEdit{{ $jad['id'] }}">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a> --}}
                                                                    <div class="modal fade" id="jadwalEdit{{ $jad['id'] }}" tabindex="-1" aria-labelledby="tambahbaru" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-body">
                                                                                    <div class="modal-toggle-wrapper">
                                                                                        <h5 style="text-align: center">Edit Jadwal</h5>
                                                                                        <div class="mt-2"></div>
                                                                                        <div class="mb-3">
                                                                                            <label for="kode_jadwal" class="form-label">Kode Jadwal</label>
                                                                                            <input type="text" name="kode_jadwal" id="kode_jadwal{{ $jad['id'] }}" value="{{ $jad['kode_jadwal'] }}" class="form-control">
                                                                                            <input type="text" name="id_mk" id="id_mk{{ $jad['id'] }}" value="{{ $jad['id_mk'] }}" hidden="">
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label for="hari" class="form-label">Hari</label>
                                                                                            <select name="hari" id="hari{{ $jad['id'] }}" class="form-control">
                                                                                                @foreach($days as $h)
                                                                                                    <option value="{{ $h['hari'] }}" {{ $h['hari'] == $jad['hari'] ? 'selected=""':'' }}>{{ $h['hari'] }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label for="ruang" class="form-label">Ruang</label>
                                                                                            <select name="ruang" id="ruang{{ $jad['id'] }}" class="form-control">
                                                                                                @foreach($ruang as $r)
                                                                                                    <option value="{{ $r['id'] }}" {{ $r['id'] == $jad['id_ruang'] ? 'selected=""':'' }}>{{ $r['nama_ruang'] }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label for="sesi" class="form-label">Sesi</label>
                                                                                            <select name="sesi" id="sesi{{ $jad['id'] }}" class="form-control">
                                                                                                @foreach($sesi as $s)
                                                                                                    <option value="{{ $s['id'] }}" {{ $s['id'] == $jad['id_sesi'] ? 'selected=""':'' }}>{{ $s['nama_sesi'] }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label for="tp" class="form-label">T/P</label>
                                                                                            <select name="tp" id="tp{{ $jad['id'] }}" class="form-control">
                                                                                                <option value="T" {{ $jad['tp'] == 'T' ? 'selected=""':'' }}>T</option>
                                                                                                <option value="P" {{ $jad['tp'] == 'P' ? 'selected=""':'' }}>P</option>
                                                                                                <option value="TP" {{ $jad['tp'] == 'TP' ? 'selected=""':'' }}>TP</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label for="kel" class="form-label">Kelompok/Kelas</label>
                                                                                            <select name="kel" id="kel{{ $jad['id'] }}" class="form-control">
                                                                                                <option value="A" {{ $jad['kel'] == 'A' ? 'selected=""':'' }}>A</option>
                                                                                                <option value="B" {{ $jad['kel'] == 'B' ? 'selected=""':'' }}>B</option>
                                                                                                <option value="C" {{ $jad['kel'] == 'C' ? 'selected=""':'' }}>C</option>
                                                                                                <option value="D" {{ $jad['kel'] == 'D' ? 'selected=""':'' }}>D</option>
                                                                                                <option value="E" {{ $jad['kel'] == 'E' ? 'selected=""':'' }}>E</option>
                                                                                                <option value="F" {{ $jad['kel'] == 'F' ? 'selected=""':'' }}>F</option>
                                                                                                <option value="G" {{ $jad['kel'] == 'G' ? 'selected=""':'' }}>G</option>
                                                                                                <option value="H" {{ $jad['kel'] == 'H' ? 'selected=""':'' }}>H</option>
                                                                                                <option value="I" {{ $jad['kel'] == 'I' ? 'selected=""':'' }}>I</option>
                                                                                                <option value="J" {{ $jad['kel'] == 'J' ? 'selected=""':'' }}>J</option>
                                                                                                <option value="K" {{ $jad['kel'] == 'K' ? 'selected=""':'' }}>K</option>
                                                                                                <option value="L" {{ $jad['kel'] == 'L' ? 'selected=""':'' }}>L</option>
                                                                                                <option value="M" {{ $jad['kel'] == 'M' ? 'selected=""':'' }}>M</option>
                                                                                                <option value="N" {{ $jad['kel'] == 'N' ? 'selected=""':'' }}>N</option>
                                                                                                <option value="N" {{ $jad['kel'] == 'O' ? 'selected=""':'' }}>O</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label for="kuota" class="form-label">Kuota</label>
                                                                                            <input type="text" name="kuota" id="kuota{{ $jad['id'] }}" value="{{ $jad['kuota'] }}" class="form-control">
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label for="status" class="form-label">Status</label>
                                                                                            <select name="status" id="status{{ $jad['id'] }}" class="form-control">
                                                                                                <option value="Aktif" {{ $jad['status'] == 'Aktif' ? 'selected=""':'' }}>Aktif</option>
                                                                                                <option value="Tidak Aktif" {{ $jad['status'] == 'Tidak Aktif' ? 'selected=""':'' }}>Tidak Aktif</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                                                                                            <select name="tahun_ajaran" id="tahun_ajaran{{ $jad['id'] }}" class="form-control">
                                                                                                <option value="" selected disabled>Tahun Ajaran</option>
                                                                                                @foreach ($ta as $t)
                                                                                                    <option value="{{ $t['id'] }}" {{ $t['id'] == $jad['id_tahun'] ? 'selected=""':'' }}>{{ $t['kode_ta'] }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <button type="button" onclick="editJadwal({{$jad['id']}})" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Edit Jadwal</button>

                                                                                        <button class="btn bg-danger d-flex align-items-center gap-2 text-light ms-auto btn-sm" type="button" data-bs-dismiss="modal">Tutup</button>
                                                                                    </div>
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

        const baseUrl = {!! json_encode(url('/')) !!};
        $(function() {
            $("#myTable").DataTable({
                responsive: true
            })
            $("#myTable1").DataTable({
                responsive: true
            })
        })
        function JadwalHarian(){
            $("#vJadwalHarian").html(`<div class="loader-box">
                            <div class="loader-2"></div>
                        </div>`);
            var hari = $('#hari').val();
            var matakuliah = $('#matakuliah').val();
            const id_prodi = {{$id_prodi}};
            $.ajax({
                url: baseUrl+'/jadwal/daftar-jadwal-harian',
                type: 'post',
                data: {
                    hari: hari,
                    matakuliah: matakuliah,
                    id_prodi : id_prodi,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    var data = res.data
                    var jumlah_input = res.jumlah_input
                    var list_pengajar = res.list_pengajar
                    var html = `
                        <table class="table" id="myTable1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Jadwal</th>
                                    <th>Hari & Waktu</th>
                                    <th>Dosen Pengampu</th>
                                    <th>Matakuliah</th>
                                    <th>Ruang</th>

                                    <th>T/P</th>
                                    <th>Kuota</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                    `
                    for (let i = 0; i < data.length; i++) {
                        console.log(data[i].kode_jadwal)
                        const no = i + 1;
                        html += `
                                <tr>
                                    <td>${ no }</td>
                                    <td>${ data[i].kode_jadwal }</td>
                                    <td>${ data[i].hari }, ${ data[i].nama_sesi }</td>
                                    <td>${ list_pengajar[data[i].id] }</td>
                                    <td>[${ data[i].kode_matkul }] ${ data[i].nama_matkul }</td>
                                    <td>${ data[i].nama_ruang }</td>

                                    <td>${ data[i].tp }</td>
                                    <td>${ jumlah_input[data[i].id]} / ${ data[i].kuota }</td>

                                    <td>
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <a href="#" class="btn btn-primary btn-xs">Edit</a>
                                            <a href="#" class="btn btn-success btn-xs">Setting Pertemuan</a>
                                        </div>

                                    </td>
                                </tr>
                                `
                    }
                    html += `</tbody></table>`
                    $('#vJadwalHarian').html(html)
                    $("#myTable1").DataTable({
                        responsive: true
                    })
                }
            })
        }
    </script>
@endsection
