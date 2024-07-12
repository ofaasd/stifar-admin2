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
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                            
                    </div>
                    <div class="card-body">
                    <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="masterJadwal-tab" href="{{ url('/admin/masterdata/jadwal/create/'.$idmk) }}" role="tab" aria-controls="masterJadwal" aria-selected="true">Master Jadwal</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="koorMK-tabs" data-bs-toggle="tab" href="#koorMK" role="tab" aria-controls="koorMK" aria-selected="false" tabindex="-1">Koordinator Matakuliah</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="DsnMK-tab" href="{{ url('/admin/masterdata/anggota-mk/'.$idmk) }}" role="tab" aria-controls="DsnMK" aria-selected="false" tabindex="-1">Anggota Matakuliah</a></li>
                            <!-- <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="Pertemuan-tab" href="{{ url('/admin/masterdata/pertemuan-mk/'.$idmk) }}" role="tab" aria-controls="Pertemuan" aria-selected="false" tabindex="-1">Pertemuan Matakuliah</a></li> -->
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="koorMK" role="tabpanel" aria-labelledby="koorMK-tab">
                                @csrf
                                <div class="row" style="padding-top: 20px;">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="kode_jadwal" class="form-label">Nama Koordinator</label>
                                            <input type="text" value="{{ $idmk }}" name="idmk" id="idmk" hidden="" />
                                            <select name="nama_koordinator" id="nama_koordinator" class="form-control">
                                                <option value="" selected disabled>Pilih Dosen Koordinator</option>
                                                @foreach($pegawai as $dsn)
                                                    <option value="{{ $dsn['id'] }}">{{ $dsn['nama_lengkap'] }}, {{ $dsn['gelar_belakang'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" onclick="simpanKoor()"><i class="fa fa-save"></i> Tambahkan</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table class="display" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>NPP</th>
                                                <th>Nama Dosen</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($koordinator as $koor)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $koor['npp'] }}</td>
                                                    <td>{{ $koor['nama_lengkap'] }}, {{ $koor['gelar_belakang'] }}</td>
                                                    <td><a href="{{ url('jadwal/hapus-koor/'.$koor['id']) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</a></td>
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
        counter = 0;
        function tambahRow(){
            counterNext = counter + 1;
            document.getElementById("input"+counter).innerHTML = `<br><select name="dosen${counterNext}" id="dosen${counterNext}" class="form-control">
                                                    <option value="Pilih Dosen Pengampu">Pilih Dosen Pengampu</option>
                                                </select><div id="input${counterNext}"></div>`;
            counter++;
        }
        function simpanKoor(){
            var id_pegawai_bio = $('#nama_koordinator').val();
            var idmk = $('#idmk').val();
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/jadwal/save-koordinator',
                type: 'post',
                dataType: 'json',
                data: {
                    id_pegawai_bio:id_pegawai_bio,
                    idmk:idmk
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    if(res.kode == 204){
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Simpan Gagal!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Koordinator Berhasil Terinputkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl+'/admin/masterdata/koordinator-mk/'+idmk;
                    }
                }
            });
        }
    </script>
@endsection