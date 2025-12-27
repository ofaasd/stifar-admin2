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
    <div class="container-fluid">
        <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                        <h5>{{$title}}</h5>
                    </div>
                    <div class="card-body">
                        <form class="form theme-form" method="POST" action="{{url('admin/role/store')}}">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Role Name</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="name" placeholder="Role Name">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Guard Name</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="guard_name" placeholder="Guard Name" value="web">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Permissions</label>
                                <div class="col-sm-9">
                                    @foreach($permission as $perm)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permission[]" value="{{$perm->id}}" id="flexCheckDefault{{$perm->id}}">   
                                            <label class="form-check-label" for="flexCheckDefault{{$perm->id}}">
                                                {{$perm->name}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            <div class="mb-3 row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>  
                        </form>
                    </div>
                </div>
            </div>
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
@endsection 