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
    <li class="breadcrumb-item active">Asal Sekolah PMB</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default active" id="masterMK-tab" data-bs-toggle="tab" href="#masterMK" role="tab" aria-controls="masterMK" aria-selected="true">Master Matakuliah</a></li>
                            {{-- <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="kelMK-tab" href="{{ url('admin/masterdata/kelompok-mk') }}" role="tab" aria-controls="kelMK" aria-selected="true">Kelompok Matakuliah</a></li> --}}
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="masterKur-tabs" href="{{ url('admin/masterdata/kurikulum') }}" role="tab" aria-controls="masterKur" aria-selected="false" tabindex="-1">Master Kurikulum</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link txt-default" id="MkKur-tab" href="{{ url('admin/masterdata/matakuliah-kurikulum') }}" role="tab" aria-controls="MkKur" aria-selected="false" tabindex="-1">Matakuliah Kurikulum</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="masterMK" role="tabpanel" aria-labelledby="masterMK-tab">
                                <div class="mt-4">
                                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahMK"><i class="fa fa-plus"></i> Tambah Matakuliah</button>
                                    <div class="modal fade" id="tambahMK" tabindex="-1" aria-labelledby="tambahMK" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="modal-toggle-wrapper">
                                                        <h5 style="text-align: center">Tambah Matakuliah</h5>
                                                        @csrf
                                                        <div class="form-group mt-2">
                                                            <label for="kode_matkul">Kode Matakuliah :</label>
                                                            <input type="text" class="form-control" name="kode_matkul" id="kode_matkul" placeholder="Kode Matakuliah" required=""/>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="nama_matkul">Nama Matakuliah :</label>
                                                            <input type="text" class="form-control" name="nama_matkul" id="nama_matkul" placeholder="Nama Matakuliah" required=""/>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="nama_inggris">Nama Inggris :</label>
                                                            <input type="text" class="form-control" name="nama_inggris" id="nama_inggris" placeholder="Nama Inggris" required=""/>
                                                        </div>
                                                        {{-- <div class="form-group mt-2">
                                                            <label for="kelompok">Nama Kelompok :</label>
                                                            <select name="kelompok" id="kelompok" class="form-control" required="">
                                                                <option value="" selected disabled>Pilih Kelompok</option>
                                                                @foreach($kelompok as $kelompok_row)
                                                                <option value="{{ $kelompok_row['id'] }}">{{ $kelompok_row['nama_kelompok'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="rumpun">Rumpun :</label>
                                                            <select name="rumpun" id="rumpun" class="form-control" required="">
                                                                <option value="" selected disabled>Pilih Rumpun</option>
                                                                @foreach($rumpun as $rumpun_row)
                                                                <option value="{{ $rumpun_row['id'] }}">{{ $rumpun_row['nama_rumpun'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div> --}}
                                                        <div class="form-group mt-2">
                                                            <label for="nama_inggris">Semester :</label>
                                                            <input type="number" class="form-control" name="semester" id="semester"/>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="nama_inggris">Jumlah SKS (Kredit) :</label>
                                                            <div class="row">
                                                                <div class="col-sm-4 mr-2">Teori
                                                                    <input type="number" name="sks_teori" class="form-control" id="sks_teori"/>
                                                                </div>,
                                                                <div class="col-sm-4" style="margin-left: 15px;">Praktek
                                                                    <input type="number" name="sks_praktek" class="form-control" id="sks_praktek"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="kelompok">Status Mata Kuliah :</label>
                                                            <select name="status_mk" id="status_mk" class="form-control">
                                                                <option value="Wajib">Wajib</option>
                                                                <option value="Pilihan">Pilihan</option>
                                                                <option value="Lainnya">Lainnya</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="status">Status :</label>
                                                            <select class="form-control" name="status" id="status">
                                                                <option disabled selected>Pilih Status</option>
                                                                <option value="Aktif">Aktif</option>
                                                                <option value="Tidak Aktif">Tidak Aktif</option>
                                                            </select>
                                                        </div>
                                                        <div class="mt-2"></div>
                                                        <button type="button" onclick="simpanMK()" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Tambah Matakuliah</button>
                                                        <button class="btn bg-danger d-flex align-items-center gap-2 text-light ms-auto" type="button" data-bs-dismiss="modal">Tutup More<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table class="display" id="tableMK">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Kode</th>
                                                <th>Nama</th>
                                                <th>Nama Inggris</th>
                                                {{-- <th>Kelompok</th>
                                                <th>Rumpun</th> --}}
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mk as $mk)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $mk['kode_matkul'] }}</td>
                                                    <td>{{ $mk['nama_matkul'] }}</td>
                                                    <td>{{ $mk['nama_matkul_eng'] }}</td>
                                                    {{-- <td>{{ $mk['nama_kelompok'] }}</td>
                                                    <td>{{ $mk['nama_rumpun'] }}</td> --}}
                                                    <td>{{ $mk['status'] }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="#" class="btn btn-warning btn-sm btn-icon edit-record" data-bs-toggle="modal" data-original-title="test" data-bs-target="#editMK{{ $mk['kode_matkul'] }}">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <a href="{{ url('admin/masterdata/matakuliah/delete/'. $mk['id']) }}" class="btn btn-danger btn-sm btn-icon edit-record">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>

                                                        <div class="modal fade" id="editMK{{ $mk['kode_matkul'] }}" tabindex="-1" aria-labelledby="editMK{{ $mk['kode_matkul'] }}" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <div class="modal-toggle-wrapper">
                                                                            <h5 style="text-align: center">Tambah Matakuliah</h5>
                                                                            @csrf
                                                                            <input type="hidden" name="id" id="id_{{ $mk['kode_matkul'] }}" value="{{$mk['id']}}">
                                                                            <div class="form-group mt-2">
                                                                                <label for="kode_matkul">Kode Matakuliah :</label>
                                                                                <input type="text" class="form-control" name="kode_matkul" id="kode_matkul_{{ $mk['kode_matkul'] }}" value="{{ $mk['kode_matkul'] }}" required=""/>
                                                                            </div>
                                                                            <div class="form-group mt-2">
                                                                                <label for="nama_matkul">Nama Matakuliah :</label>
                                                                                <input type="text" class="form-control" name="nama_matkul" id="nama_matkul_{{ $mk['kode_matkul'] }}" value="{{ $mk['nama_matkul'] }}" required=""/>
                                                                            </div>
                                                                            <div class="form-group mt-2">
                                                                                <label for="nama_inggris">Nama Inggris :</label>
                                                                                <input type="text" class="form-control" name="nama_inggris" id="nama_inggris_{{ $mk['kode_matkul'] }}" value="{{ $mk['nama_matkul_eng'] }}" required=""/>
                                                                            </div>
                                                                            {{-- <div class="form-group mt-2">
                                                                                <label for="kelompok">Nama Kelompok :</label>
                                                                                <select name="kelompok" id="kelompok_{{ $mk['kode_matkul'] }}" class="form-control" required="">
                                                                                    @foreach($kelompok as $kelompoks)
                                                                                    <option value="{{ $kelompoks['id'] }}">{{ $kelompoks['nama_kelompok'] }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mt-2">
                                                                                <label for="rumpun">Rumpun :</label>
                                                                                <select name="rumpun" id="rumpun_{{ $mk['kode_matkul'] }}" class="form-control" required="">

                                                                                    @foreach($rumpun as $rumpuns)
                                                                                    <option value="{{ $rumpuns['id'] }}">{{ $rumpuns['nama_rumpun'] }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div> --}}
                                                                            <div class="form-group mt-2">
                                                                                <label for="nama_inggris">Semester :</label>
                                                                                <input type="number" class="form-control" name="semester" id="semester_{{ $mk['kode_matkul'] }}" value="{{ $mk['semester'] }}" required=""/>
                                                                            </div>
                                                                            <div class="form-group mt-2">
                                                                                <label for="nama_inggris">Jumlah SKS (Kredit) :</label>
                                                                                <div class="row">
                                                                                    <div class="col-sm-4">Teori
                                                                                        <input type="number" name="sks_teori" id="sks_teori_{{ $mk['kode_matkul'] }}" value="{{ $mk['sks_teori'] }}" required=""/>
                                                                                    </div>,
                                                                                    <div class="col-sm-4" style="margin-left: 15px;">Praktek
                                                                                        <input type="number" name="sks_praktek" id="sks_praktek_{{ $mk['kode_matkul'] }}" value="{{ $mk['sks_praktek'] }}" required=""/>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group mt-2">
                                                                                <label for="kelompok">Status Mata Kuliah :</label>
                                                                                <select name="status_mk" id="status_mk_{{ $mk['kode_matkul'] }}" class="form-control" required="">
                                                                                    <option value="Wajib" {{ $mk['status_mk'] == 'Wajib' ? 'selected=""':'' }}>Wajib</option>
                                                                                    <option value="Pilihan" {{ $mk['status_mk'] == 'Pilihan' ? 'selected=""':'' }}>Pilihan</option>
                                                                                    <option value="Lainnya" {{ $mk['status_mk'] == 'Lainnya' ? 'selected=""':'' }}>Lainnya</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mt-2">
                                                                                <label for="status">Status :</label>
                                                                                <select class="form-control" name="status" id="status_{{ $mk['kode_matkul'] }}" required="">

                                                                                    <option value="Aktif">Aktif</option>
                                                                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="mt-2 mb-2"></div>
                                                                            <hr>
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <button type="button" onclick="updateMK('{{ $mk['kode_matkul'] }}')" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Update Matakuliah</button>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <button class="btn bg-danger d-flex align-items-center gap-2 text-light ms-auto" type="button" data-bs-dismiss="modal">Tutup More<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></button>
                                                                                </div>
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
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>

    <script>
        $(function() {
            $("#tableMK").DataTable({
                responsive: true
            })
        })
        function simpanMK(){
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/admin/masterdata/matakuliah/save',
                type: 'post',
                dataType: 'json',
                data: {
                    kode_matkul: $('#kode_matkul').val(),
                    nama_matkul: $('#nama_matkul').val(),
                    nama_inggris: $('#nama_inggris').val(),
                    kelompok: $('#kelompok').val(),
                    rumpun: $('#rumpun').val(),
                    semester: $('#semester').val(),
                    sks_teori: $('#sks_teori').val(),
                    sks_praktek: $('#sks_praktek').val(),
                    status_mk: $('#status_mk').val(),
                    status: $('#status').val()
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Matakuliah Berhasil ditambahkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl+'/admin/masterdata/matakuliah';
                    }else{
                        swal({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Matakuliah Gagal ditambahkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl+'/admin/masterdata/matakuliah';
                    }
                }
            })
        }
        function updateMK(kode){
            const baseUrl = {!! json_encode(url('/')) !!};
            $.ajax({
                url: baseUrl+'/admin/masterdata/matakuliah/update',
                type: 'post',
                dataType: 'json',
                data: {
                    id : $('#id_'+kode).val(),
                    kode_matkul: $('#kode_matkul_'+kode).val(),
                    nama_matkul: $('#nama_matkul_'+kode).val(),
                    nama_inggris: $('#nama_inggris_'+kode).val(),
                    kelompok: $('#kelompok_'+kode).val(),
                    rumpun: $('#rumpun_'+kode).val(),
                    semester: $('#semester_'+kode).val(),
                    sks_teori: $('#sks_teori_'+kode).val(),
                    sks_praktek: $('#sks_praktek_'+kode).val(),
                    status_mk: $('#status_mk_'+kode).val(),
                    status: $('#status_'+kode).val()
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Matakuliah Berhasil ditambahkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl+'/admin/masterdata/matakuliah';
                    }else{
                        swal({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Matakuliah Gagal ditambahkan!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl+'/admin/masterdata/matakuliah';
                    }
                }
            })
        }
    </script>
@endsection
