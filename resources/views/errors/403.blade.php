@extends('layouts.errors.master')
@section('title', 'Error 403')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="page-wrapper compact-wrapper" id="pageWrapper">
  <!-- error-403 start-->
  <div class="error-wrapper">
    <div class="container"><img class="img-100" src="{{asset('assets/images/other-images/sad.png')}}" alt="">
      <div class="error-heading">
        <h2 class="headline font-success">403</h2>
      </div>
      <div class="col-md-8 offset-md-2">
        <p class="sub-content">The page you are attempting to reach is currently not available. The user not have access to this page.</p>
      </div>
      <div><a class="btn btn-success-gradien btn-lg" href="{{URL::to('/')}}">BACK TO HOME PAGE</a></div>
    </div>
  </div>
  <!-- error-403 end-->
</div>
@endsection

@section('script')

@endsection
