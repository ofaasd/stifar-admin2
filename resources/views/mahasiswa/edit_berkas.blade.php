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
    <div class="edit-profile">
        <div class="row">
          <div class="col-xl-4">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title mb-0">My Profile</h4>
                <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
              </div>
              <div class="card-body">

                  <div class="row mb-2">
                    <div class="profile-title">
                      <div class="media">
                          <div class="photo-profile">
                              <img class="img-70 rounded-circle" alt="" src="{{ (!empty($mahasiswa->foto_mhs))?asset('assets/images/mahasiswa/' . $mahasiswa->foto_mhs):asset('assets/images/user/7.jpg') }}">
                          </div>
                        <div class="media-body">
                          <h5 class="mb-1">{{$mahasiswa->nama}}</h5>
                          <p>{{$prodi[$mahasiswa->id_program_studi]}}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  {{-- <div class="mb-3">
                    <h6 class="form-label">Bio</h6>
                    <textarea class="form-control" rows="5">On the other hand, we denounce with righteous indignation</textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <input class="form-control" placeholder="your-email@domain.com" value="{{$mahasiswa->email1}}">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Ubah Password</label>
                    <input class="form-control" type="password" value="password">
                  </div>
                   <div class="mb-3">
                    <label class="form-label">Website</label>
                    <input class="form-control" placeholder="http://Uplor .com">
                  </div> --}}
              </div>
            </div>
          </div>
          <div class="col-xl-8">
            <form class="card" id="formMahasiswa" action="javascript:void(0)">
              @csrf
              <div class="card-header">
                <h4 class="card-title mb-0">Heregistrasi Data Pendukung</h4>
                <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
              </div>
              <div class="card-body">
                  <input type="hidden" name="id" value="{{$mahasiswa->id}}">
                  <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="id_program_studi" value="{{$mahasiswa->id_program_studi}}">
                        <input type="hidden" name="nim" value="{{$mahasiswa->nim}}">
                        <input type="hidden" name="id_dsn_wali" value="{{$mahasiswa->id_dsn_wali}}">
                        <input type="hidden" name="angkatan" value="{{$mahasiswa->angkatan}}">
                        <div class="mb-2">
                            <label class="col-sm-12 col-form-label">KTP: </label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="file" name="ktp" class="form-control" placeholder="Nama" id="ktp" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    {!!(!empty($berkas) && !empty($berkas->ktp))?"<div class='alert alert-success'>File Sudah Diupload</div>":"<div class='alert alert-danger'>File Belum Diupload | Format PDF Max : 2 MB</div>"!!}
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="col-sm-12 col-form-label">KK: </label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="file" name="kk" class="form-control" placeholder="kk" id="kk" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    {!!(!empty($berkas) && !empty($berkas->kk))?"<div class='alert alert-success'>File Sudah Diupload</div>":"<div class='alert alert-danger'>File Belum Diupload | Format PDF Max : 2 MB</div>"!!}
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="col-sm-12 col-form-label">Akta Kelahiran: </label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="file" name="akta" class="form-control" placeholder="akta" id="akta" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    {!!(!empty($berkas) && !empty($berkas->akta))?"<div class='alert alert-success'>File Sudah Diupload</div>":"<div class='alert alert-danger'>File Belum Diupload | Format PDF Max : 2 MB</div>"!!}
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="col-sm-12 col-form-label">Ijazah Depan: </label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="file" name="ijazah_depan" class="form-control" id="ijazah_depan" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    {!!(!empty($berkas) && !empty($berkas->ijazah_depan))?"<div class='alert alert-success'>File Sudah Diupload</div>":"<div class='alert alert-danger'>File Belum Diupload | Format PDF Max : 2 MB</div>"!!}
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="col-sm-12 col-form-label">Ijazah Belakang: </label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="file" name="ijazah_belakang" class="form-control" id="ijazah_belakang" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    {!!(!empty($berkas) && !empty($berkas->ijazah_belakang))?"<div class='alert alert-success'>File Sudah Diupload</div>":"<div class='alert alert-danger'>File Belum Diupload | Format PDF Max : 2 MB</div>"!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="card-footer text-end">
                <button class="btn btn-primary update-btn" type="submit">Update Profile</button>
              </div>
            </form>
          </div>
        </div>
      </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script>
    $(document.body).on("submit","#formMahasiswa",function(){
        $(".update-btn").prop('disabled', true);
        $(".update-btn").html('<div class="loader-2"></div> Please Wait');

        const myFormData = new FormData(document.getElementById("formMahasiswa"));
        $.ajax({
            url:'{{URL::to('mahasiswa/berkas_update')}}',
            method:'POST',
            data:myFormData,
            processData: false,
            contentType: false,
            success:function(data){
                swal({
                    icon: 'success',
                    title: 'Successfully '.concat(data.status, '!'),
                    text: ''.concat(data.status, ' Successfully.'),
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                }).then(function(){
                    location.reload();
                });
            },
            error: function error(err) {
                //offCanvasForm.offcanvas('hide');
                swal({
                title: 'Duplicate Entry!',
                text: 'Data Not Saved !',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-success'
                }
                }).then(function(){
                    location.reload();
                });
            }
        });
    });
    </script>
@endsection
