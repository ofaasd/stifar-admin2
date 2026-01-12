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
                        <form class="form theme-form" method="POST" action="{{url('admin/role')}}">
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
                            <div class="row">
                                @foreach($groupedPermissions as $groupName => $permissions)
                                    <div class="card mb-3 border-light shadow-sm col-md-4">
                                        <div class="card-header bg-primary">
                                            <div class="form-check">
                                                <input class="form-check-input parent-checkbox" 
                                                    type="checkbox" 
                                                    id="parent-{{ $groupName }}" 
                                                    data-group="{{ $groupName }}">
                                                <label class="form-check-label fw-bold text-uppercase" for="parent-{{ $groupName }}">
                                                    {{ $groupName }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($permissions as $permission)
                                                    <div class="col-md-12 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input child-checkbox" 
                                                                name="permissions[]" 
                                                                type="checkbox" 
                                                                value="{{ $permission->name }}" 
                                                                id="perm-{{ $permission->id }}" 
                                                                data-group="{{ $groupName }}">
                                                            <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                                {{ str_replace($groupName.'.', '', $permission->name) }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
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

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua checkbox parent
            const parentCheckboxes = document.querySelectorAll('.parent-checkbox');

            parentCheckboxes.forEach(parent => {
                parent.addEventListener('change', function() {
                    const groupName = this.getAttribute('data-group');
                    // Cari semua child yang punya data-group yang sama
                    const children = document.querySelectorAll(`.child-checkbox[data-group="${groupName}"]`);
                    
                    children.forEach(child => {
                        child.checked = this.checked;
                    });
                });
            });

            // Opsional: Jika semua child dicentang manual, parent otomatis tercentang
            const childCheckboxes = document.querySelectorAll('.child-checkbox');
            childCheckboxes.forEach(child => {
                child.addEventListener('change', function() {
                    const groupName = this.getAttribute('data-group');
                    const parent = document.querySelector(`.parent-checkbox[data-group="${groupName}"]`);
                    const sameGroupChildren = document.querySelectorAll(`.child-checkbox[data-group="${groupName}"]`);
                    const checkedChildren = document.querySelectorAll(`.child-checkbox[data-group="${groupName}"]:checked`);

                    parent.checked = (sameGroupChildren.length === checkedChildren.length);
                    // Indeterminate state (opsional: jika hanya sebagian tercentang)
                    parent.indeterminate = (checkedChildren.length > 0 && checkedChildren.length < sameGroupChildren.length);
                });
            });
        });
    </script>
@endsection