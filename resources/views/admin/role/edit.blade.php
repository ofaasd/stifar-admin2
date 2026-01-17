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
    <li class="breadcrumb-item active">Edit Role</li>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Role: <span class="text-primary">{{ $role->name }}</span></h5>
                        <a href="{{ url('admin/role/index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                    </div>

                    <form action="{{ url('admin/role/'. $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">Nama Role</label>
                                <input type="text" name="name" id="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $role->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h6 class="fw-bold mb-3">Manajemen Permission</h6>
                            <p class="text-muted small">Centang pada nama grup (Parent) untuk memilih semua sub-permission di bawahnya.</p>
                            <div class="row">
                                @foreach($groupedPermissions as $groupName => $permissions)
                                    <div class="card mb-3 border-light shadow-sm col-md-4">
                                        <div class="card-header bg-primary">
                                            <div class="form-check">
                                                <input class="form-check-input parent-checkbox" 
                                                    type="checkbox" 
                                                    id="parent-{{ $groupName }}" 
                                                    data-group="{{ $groupName }}">
                                                <label class="form-check-label fw-bold text-uppercase text-light" for="parent-{{ $groupName }}" style="font-size: 0.85rem;">
                                                    Grup: {{ $groupName }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body bg-white">
                                            <div class="row">
                                                @foreach($permissions as $permission)
                                                    <div class="col-md-12 col-lg-12 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input child-checkbox" 
                                                                name="permissions[]" 
                                                                type="checkbox" 
                                                                value="{{ $permission->name }}" 
                                                                id="perm-{{ $permission->id }}" 
                                                                data-group="{{ $groupName }}"
                                                                {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                                {{-- Menampilkan nama setelah titik, misal: mahasiswa.create -> create --}}
                                                                {{ str_contains($permission->name, '.') ? explode('.', $permission->name)[1] : $permission->name }}
                                                            </label>
                                                        </div>
                                                        @if(isset($permissionToMenus[$permission->name]))
                                                            <ul style="list-style-type: square; margin-left: 20px; margin-top: 5px; ">
                                                                @foreach($permissionToMenus[$permission->name] as $menu)
                                                                    <li style="font-size:9pt;">{{ $menu->title }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-footer bg-white py-3">
                            <button type="submit" class="btn btn-primary px-4">Update Role & Permission</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const parentCheckboxes = document.querySelectorAll('.parent-checkbox');

            // Fungsi untuk mengecek status parent saat halaman dimuat (Initial Load)
            function checkInitialStatus() {
                parentCheckboxes.forEach(parent => {
                    const groupName = parent.getAttribute('data-group');
                    const children = document.querySelectorAll(`.child-checkbox[data-group="${groupName}"]`);
                    const checkedChildren = document.querySelectorAll(`.child-checkbox[data-group="${groupName}"]:checked`);
                    
                    if (children.length === checkedChildren.length && children.length > 0) {
                        parent.checked = true;
                    } else if (checkedChildren.length > 0) {
                        parent.indeterminate = true;
                    }
                });
            }

            // Jalankan pengecekan awal
            checkInitialStatus();

            // Event listener untuk Parent Checkbox
            parentCheckboxes.forEach(parent => {
                parent.addEventListener('change', function() {
                    const groupName = this.getAttribute('data-group');
                    const children = document.querySelectorAll(`.child-checkbox[data-group="${groupName}"]`);
                    
                    children.forEach(child => {
                        child.checked = this.checked;
                    });
                });
            });

            // Event listener untuk Child Checkbox (agar parent ikut berubah)
            const childCheckboxes = document.querySelectorAll('.child-checkbox');
            childCheckboxes.forEach(child => {
                child.addEventListener('change', function() {
                    const groupName = this.getAttribute('data-group');
                    const parent = document.querySelector(`.parent-checkbox[data-group="${groupName}"]`);
                    const allChildren = document.querySelectorAll(`.child-checkbox[data-group="${groupName}"]`);
                    const checkedChildren = document.querySelectorAll(`.child-checkbox[data-group="${groupName}"]:checked`);

                    if (checkedChildren.length === 0) {
                        parent.checked = false;
                        parent.indeterminate = false;
                    } else if (checkedChildren.length === allChildren.length) {
                        parent.checked = true;
                        parent.indeterminate = false;
                    } else {
                        parent.checked = false;
                        parent.indeterminate = true; // Status garis tengah jika hanya sebagian
                    }
                });
            });
        });
    </script>
@endsection