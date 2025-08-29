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
                        @if (!isset($isAlumni))
                            <div class="col-md-12 mb-4">
                                <a class="btn btn-primary" href="{{ URL::to('mahasiswa/create')}}">Tambah mahasiswa</a>
                            </div>
                        @endif

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
                    console.log('====================================');
                    console.log(data);
                    console.log('====================================');
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
    </script>
@endsection
