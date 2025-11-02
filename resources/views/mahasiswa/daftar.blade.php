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
    <li class="breadcrumb-item">Mahasiswa</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 project-list">
                <div class="card">
                   <div class="row">
                      <div class="col-md-12">
                         <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-id="0" id="top-home-tab" data-bs-toggle="tab" href="#top-home" role="tab" aria-controls="top-home" aria-selected="true"><i data-feather="target"></i>All <span class="badge rounded-pill badge-primary" style="margin-left:2px;">{{$mhs->count()}}</span></a></li>
                            @foreach($prodi as $prod)
                                <li class="nav-item"><a class="nav-link" data-id="{{$prod->id}}" id="{{$prod->kode}}-top-tab" data-bs-toggle="tab" href="#top-profile" role="tab" aria-controls="top-profile" aria-selected="false" style="font-size:10pt;"><i data-feather="info"></i>{{$nama[$prod->id]}} <span class="badge rounded-pill badge-primary" style="margin-left:2px;">{{$jumlah[$prod->id]}}</span></a></li>
                            @endforeach
                         </ul>
                      </div>
                   </div>
                </div>
             </div>
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 d-flex align-items-center">
                                @if (!isset($isAlumni))
                                    <a class="btn btn-primary" href="{{ URL::to('mahasiswa/create')}}">Tambah mahasiswa</a>
                                @endif
                            </div>
                            <div class="col-md-6 d-flex justify-content-end align-items-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCetakKTM">Cetak KTM</a></li>
                                        <!-- Tambah aksi lainnya di sini -->
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Cetak KTM -->
                        <div class="modal fade" id="modalCetakKTM" tabindex="-1" aria-labelledby="modalCetakKTMLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form class="modal-content" method="POST" action="{{ route('cetak-ktm') }}" target="_blank">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalCetakKTMLabel">Cetak KTM</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="pilihan" class="form-label">Pilihan</label>
                                            <select id="pilihan" name="pilihan" class="form-select" required>
                                                <option value="prodi">Prodi</option>
                                                <option value="angkatan">Angkatan</option>
                                            </select>
                                        </div>

                                        <div id="form-prodi" class="mb-3">
                                            <label for="spesifik-prodi" class="form-label">Pilih Prodi</label>
                                            <select id="spesifik-prodi" name="spesifik" class="form-select">
                                                @foreach($prodi as $prod)
                                                    <option value="{{ $prod->id }}">{{ $nama[$prod->id] ?? ($prod->nama ?? $prod->kode) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div id="form-angkatan" class="mb-3" style="display:none;">
                                            <label for="spesifik-angkatan" class="form-label">Tahun Angkatan</label>
                                            <select id="spesifik-angkatan" name="spesifik" class="form-select">
                                                @foreach($angkatan as $ang)
                                                    <option value="{{ $ang }}">{{ $ang }}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Pilih tahun angkatan jika memilih "Angkatan".</div>
                                        </div>

                                        <div class="alert alert-warning mt-3" role="alert">
                                            <strong>Catatan:</strong> Jika jumlah data sangat banyak, proses pembuatan atau pengunduhan KTM dapat memakan waktu cukup lama. Harap bersabar dan jangan menutup jendela ini sampai proses selesai.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary" id="btn-submit">Cetak</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive tbl-mhs">


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
            var isAlumni = {{ isset($isAlumni) && $isAlumni ? "true" : "false" }};
            if (isAlumni) {
                $(".nav-link").click(function(){
                    const id = $(this).data('id');
                    refresh_alumni(id);
                });
                refresh_alumni(0);
            }else{
                $(".nav-link").click(function(){
                    const id = $(this).data('id');
                    refresh_mahasiswa(id);
                });
                refresh_mahasiswa(0);
            }


            $('#pilihan').on('change', function(){
                const val = $(this).val();
                if (val === 'prodi') {
                    $('#form-prodi').show();
                    $('#form-angkatan').hide();
                    $('#spesifik-angkatan').prop('selectedIndex',0);
                } else {
                    $('#form-prodi').hide();
                    $('#form-angkatan').show();
                    $('#spesifik-prodi').prop('selectedIndex',0);
                }
            });

            // Inisialisasi default saat modal dibuka
            $('#modalCetakKTM').on('show.bs.modal', function () {
                $('#pilihan').trigger('change');
            });
        });

        function refresh_mahasiswa(id){
            $(".tbl-mhs").html(`<div class="loader-box">
                            <div class="loader-2"></div>
                        </div>`);
            const url = "{{URL::to('mahasiswa/get_mhs')}}";
            $.ajax({
                url: url,
                method: "GET",
                data: { id: id },
                success: function(data) {
                    $(".tbl-mhs").html(data);
                },
                error: function(xhr, status, error) {
                    // console.log("Terjadi kesalahan:");
                    // console.log("Status:", status);
                    // console.log("Error:", error);
                    // console.log("Response Text:", xhr.responseText);
                }
            });
        }

        function refresh_alumni(id){
            $(".tbl-mhs").html(`<div class="loader-box">
                            <div class="loader-2"></div>
                        </div>`);
            const url = "{{URL::to('/alumni/get_alumni')}}";
            $.ajax({
                url: url,
                method: "GET",
                data: { id: id },
                success: function(data) {
                    $(".tbl-mhs").html(data);
                },
                error: function(xhr, status, error) {
                    console.log("Response Text:", xhr);
                }
            });
        }
    </script>
@endsection
