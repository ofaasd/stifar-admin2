@extends('layouts.authentication.master2')
@section('title', 'Login')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid p-0">
   <div class="row m-0">
      <div class="col-12 p-0">
         <div class="login-card">
            <div>
               <div><a class="logo" href="{{ route('dashboard') }}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt="looginpage"><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage"></a></div>
               <div class="login-main">
                <div class="row">
                    <div class="col-md-6">
                        <a href='' class="btn-square btn-outline-primary">
                            <i class="fa fa-graduation-cap"></i><br /><br />
                            Login Mahasiswa
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href='' class="btn-square btn-outline-primary">
                            <i class="fa fa-users"></i><br /><br />
                            Login Dosen / Karyawan
                        </a>
                    </div>
                </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
@endsection
