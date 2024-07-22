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
               <div><a class="logo" href="{{ route('register') }}"><img class="img-fluid for-light" src="{{asset('assets/images/logo/logo.png')}}" alt="looginpage"><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage"></a></div>
               <div class="login-main">
                  <form class="theme-form" method="POST" action="{{route('actionRegister')}}">
                    @csrf
                     <h4 class="text-center">Aktivasi Akun Dosen</h4>
                     <p class="text-center">Silahkan isi form di bawah ini untuk aktivasi</p>
                     @if(session('message'))
                        <div class="alert alert-success">
                            {{session('message')}}
                        </div>
                    @endif
                     @if(session('message_error'))
                        <div class="alert alert-danger">
                            {{session('message_error')}}
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="col-form-label">NIP</label>
                        <input class="form-control" type="text" name="nip" required="" placeholder="cth : 123123">
                     </div>
                     <div class="form-group">
                        <label class="col-form-label">Email Address</label>
                        <input class="form-control" type="email" name="email" required="" placeholder="Test@gmail.com">
                     </div>
                     <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input class="form-control" type="password" name="password" required="" placeholder="*********">

                     </div>
                     <div class="form-group mb-0">
                        <button class="btn btn-primary btn-block" type="submit">Buat Akun</button>
                     </div>
                     {{-- <h6 class="text-muted mt-4 or">Or signup with</h6> --}}
                     {{-- <div class="social mt-4">
                        <div class="btn-showcase"><a class="btn btn-light" href="https://www.linkedin.com/login" target="_blank"><i class="txt-linkedin" data-feather="linkedin"></i> LinkedIn </a><a class="btn btn-light" href="https://twitter.com/login?lang=en" target="_blank"><i class="txt-twitter" data-feather="twitter"></i>twitter</a><a class="btn btn-light" href="https://www.facebook.com/" target="_blank"><i class="txt-fb" data-feather="facebook"></i>facebook</a></div>
                     </div> --}}
                     <p class="mt-4 mb-0">Sudah punya akun ?  <br /><a class="btn btn-success" href="{{ route('login') }}">Sign In</a></p>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
@endsection
