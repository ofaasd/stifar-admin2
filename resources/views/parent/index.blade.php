@extends('layouts.authentication.master2')
@section('title', 'Login Parent')

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
               <div>
                  <a class="logo" href="#">
                     <img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt="looginpage">
                     <img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage">
                  </a>
               </div>
               <div class="login-main">
                  {{-- Action diarahkan ke route parent.login.process --}}
                  <form class="theme-form" action="{{ route('parent.show') }}" method="POST">
                     @csrf
                     
                     <h4 class="text-center">MySTIFAR Parent</h4>
                     <p class="text-center">Masukan NIM dan Tanggal Lahir Mahasiswa untuk memantau akademik.</p>
                     
                     {{-- Menampilkan error session jika ada --}}
                     @if(session('error'))
                        <div class="alert alert-danger">
                           {{session('error')}}
                        </div>
                     @endif

                     {{-- Input NIM --}}
                     <div class="form-group">
                        <label class="col-form-label">NIM Mahasiswa</label>
                        {{-- Type text untuk NIM --}}
                        <input class="form-control" type="text" name="nim" required="" value="{{ old('nim') }}" placeholder="1062311123">
                     </div>

                     {{-- Input Tanggal Lahir --}}
                     <div class="form-group">
                        <label class="col-form-label">Tanggal Lahir Mahasiswa</label>
                        {{-- Type date untuk Tanggal Lahir --}}
                        <input class="form-control" type="date" name="tglLahir" value="{{ old('tglLahir') }}" required="">
                     </div>

                     {{-- Tombol Login --}}
                     <div class="form-group mb-0">
                        {{-- Checkbox Remember & Forgot Password dihapus karena tidak relevan untuk login parent by NIM/Tgl Lahir --}}
                        <button class="btn btn-primary btn-block w-100 mt-3" type="submit">Masuk</button>
                     </div>
                     
                     {{-- Bagian Aktivasi Dosen/Mhs dihapus sesuai permintaan --}}
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