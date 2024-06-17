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
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="kode_jadwal" class="form-label">Kode Jadwal</label>
                                        <input type="text" name="kode_jadwal" id="kode_jadwal" class="form-control">
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
                                                <option value="{{ $s['id'] }}">{{ $s['kode_sesi'] }}</option>
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
                                        <input type="text" name="kel" id="kel" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="kuota" class="form-label">Kuota</label>
                                        <input type="text" name="kuota" id="kuota" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="Aktif">Aktif</option>
                                            <option value="Tidak Aktif">Tidak Aktif</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dosen" class="form-label">Dosen Pengampu</label>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <select name="dosen0" id="dosen0" class="form-control">
                                                    <option value="Pilih Dosen Pengampu">Pilih Dosen Pengampu</option>
                                                    <option value="5">Dosen A</option>
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
                    </div>
                    <div class="card-body">
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
                                            <td>{{ $row['kode_sesi'] }}</td>
                                            <td>{{ $row['kel'] }}</td>
                                            <td>{{ $row['kuota'] }}</td>
                                            <td>{{ $row['status'] }}</td>
                                            <td>
                                                @foreach($row->pengajar as $dsn)
                                                    - {{ $dsn['id_dsn'] }} <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{ url('admin/masterdata/jadwal/create/'. $row['id']) }}" class="btn btn-sm btn-icon edit-record text-primary">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        counter = 0;
        function tambahRow(){
            counterNext = counter + 1;
            document.getElementById("input"+counter).innerHTML = `<br><select name="dosen${counterNext}" id="dosen${counterNext}" class="form-control">
                                                    <option value="Pilih Dosen Pengampu">Pilih Dosen Pengampu</option>
                                                </select><div id="input${counterNext}"></div>`;
            counter++;
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
            const baseUrl = {!! json_encode(url('/')) !!};
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
                    dsn:dsn
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