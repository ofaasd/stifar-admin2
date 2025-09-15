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
    <li class="breadcrumb-item">Dosen</li>
    <li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{$title}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">{{$title}}</li>
                </ol>
            </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title mb-0">Attendance</h5>
                        </div>
                        <div class="col-md-4 text-right">
                            <div id="clock-wrapper"></div>
                        </div>
                    </div>

                </div>
                <div class="card-datatable card-body table-responsive">
                    <div id="overlay-place"></div>
                    <input type="hidden" name="page" id='page' value='absensi'>
                    <input type="hidden" name="title" id='title' value='Absensi'>
                    <form class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}Form" action="javascript:void(0)">
                      @csrf
                      <div class="mb-4">
                        <div class="row">
                          <div class="col-md-6">
                            <div id="my_camera" style="margin:auto;"></div>
                            <br/>
                            <input type="button" class="btn btn-success me-sm-3 me-1 data-submit" value="Take Picture" onClick="take_snapshot()">
                            <input type="hidden" name="image" class="image-tag">
                          </div>
                          <div class="col-md-6">
                            <br/>
                            <div id="results" style="text-align:center">Result Foto</div>

                            <input type="hidden" name="lat" id="lat" class="form-control" >
                            <input type="hidden" name="long" id="long" class="form-control" >
                            <input type="hidden" name="tanggal" id="tanggal" value="{{$tanggal}}" class="form-control" >
                          </div>

                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 text-center">
                          @if(empty($absensi->start))
                          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Attendance In</button>
                          @elseif(empty($absensi->end))
                          <button type="submit" class="btn btn-danger me-sm-3 me-1 data-submit">Attendance Out</button>
                          @else
                          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" disabled>Already Attendance</button>
                          @endif
                        </div>
                      </div>
                    </form>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">Log Attendance</div>
                        <div class="col-md-6 text-right">
                            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#filterModal">Filter</a>
                      </div>
                    </div>
                </div>
                <div class="card-body">
                  <table class="table datatables">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>In</th>
                        <th>Late</th>
                        <th>Out</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($list_absensi as $row)
                        <tr>
                          <td>{{date('d-m-Y', strtotime($row->day))}}</td>
                          <td >{{!empty($row->start) ? date('H:i:s', $row->start) : ''}}</td>
                          <td {{!empty($row->start_late) ? "class=bg-danger":"class=bg-success"}}>{{!empty($row->start_late) ? date('H:i:s', $row->start_late) : '00:00:00'}}</td>
                          <td>{{!empty($row->end) ? date('H:i:s', $row->end) : '00:00:00'}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
</div>
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <form action="{{url('attendance')}}" method="GET">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Filter Date</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="date_start">Date Start : </label>
                    <input type="date" name="date_start" class="form-control" id="date_start" value="{{empty($date_start)?date('Y-m'). '-01':$date_start}}">
                </div>
                <div class="form-group">
                    <label for="date_start">Date End : </label>
                    <input type="date" name="date_end" class="form-control" id="date_end" value="{{empty($date_end)?date('Y-m-d'):$date_end}}">
                </div>

            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
        </form>
    </div>
</div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function(event) {
      const baseUrl = '{!! url("") !!}';
      $(".datatables").DataTable();
      Webcam.set({
          width: 200,
          height: 200,
          image_format: 'jpeg',
          jpeg_quality: 90
      });

      Webcam.attach( '#my_camera' );
      $('#addNew{{$title}}Form').submit(function(e) {

            e.preventDefault();
            $("#overlay-place").html(`<div class="overlay">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>`);
            const lat = $("#lat").val();
            const long = $("#long").val();
            if(lat && long){

                const url = ''.concat(baseUrl).concat('/dosen/attendance');
                // alert(url);
                $.ajax({
                    data: $('#addNew{{$title}}Form').serialize(),
                    url: url,
                    type: 'POST',
                    success: function success(status) {
                        // sweetalert
                        //$("#overlay-place").html('<div class="alert alert-success">Data Saved !</div>');
                        swal({
                        icon: 'success',
                        title: 'Successfully '.concat(status.nama, ' Updated !'),
                        text: ''.concat('Absensi ', ' ').concat(' Updated Successfully.'),
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                        });
                        location.reload();
                    },
                    error: function error(err) {
                        $("#overlay-place").html('');
                        swal({
                        title: 'Data Not Saved',
                        text: ' Please take a picture first',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                        });
                    }
                });
            }else{
                swal({
                    title: 'Longitude and Latitude not found',
                    text: ' Please allow location from your browser. Or Take Picture First',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });

                $("#overlay-place").html(`<div class="alert alert-danger">Longitude and Latitude not found. please allow it from your browser. Or Take Picture First</div>`);
            }
      });

      setInterval(function() {
            var date = new Date();
            $('#clock-wrapper').html(
                date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear() + " " +
                date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds()
                );
        }, 500);
    });
    const res = document.getElementById('results');
    function take_snapshot() {
      Webcam.snap( function(data_uri) {
          $(".image-tag").val(data_uri);
          res.innerHTML = '<img src="'+data_uri+'" align="center" width="200" height="200" />';
      } );
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
      } else {
        res.innerHTML = "Geolocation is not supported by this browser.";
      }
    }
    function showPosition(position) {
      $("#lat").val(position.coords.latitude);
      $("#long").val(position.coords.longitude);
    }
  </script>
@endsection
