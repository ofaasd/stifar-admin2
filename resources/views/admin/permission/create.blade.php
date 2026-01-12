@extends('layouts.master')
@section('title', isset($permission) ? 'Edit Permission' : 'Tambah Permission')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>{{ isset($permission) ? 'Edit Permission' : 'Tambah Permission Baru' }}</h5>
                </div>
                <div class="card-body">
                    <form class="form theme-form" method="POST" 
                          action="{{ isset($permission) ? route('permission.update', $permission->id) : route('permission.store') }}">
                        @csrf
                        @if(isset($permission))
                            @method('PUT')
                        @endif
                        
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Permission Name</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" name="name" 
                                       value="{{ old('name', $permission->name ?? '') }}" 
                                       placeholder="Contoh: user-create" required>
                                <small class="text-muted">Gunakan format kepingan kecil (kebab-case) seperti: <code>nama-modul-aksi</code></small>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Guard Name</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" name="guard_name" 
                                       value="{{ old('guard_name', $permission->guard_name ?? 'web') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($permission) ? 'Update Permission' : 'Simpan Permission' }}
                                </button>
                                <a href="{{ route('permission.index') }}" class="btn btn-light">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection