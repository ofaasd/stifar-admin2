@extends('layouts.master')
@section('title', isset($menu) ? 'Edit Menu' : 'Tambah Menu')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>{{ isset($menu) ? 'Edit Menu: ' . $menu->title : 'Tambah Menu Baru' }}</h5>
                </div>
                <div class="card-body">
                    {{-- Logika Penentuan Action Route --}}
                    <form class="form theme-form" method="POST" 
                          action="{{ isset($menu) ? url('admin/menu/'.$menu->id) : url('admin/menu') }}">
                        @csrf
                        @if(isset($menu))
                            @method('PUT')
                        @endif
                        
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Parent Menu</label>
                            <div class="col-sm-9">
                                <select name="parent_id" class="form-control select2-basic">
                                    <option value="">-- Main Menu (No Parent) --</option>
                                    @foreach($parent_menus as $pm)
                                        <option value="{{ $pm->id }}" 
                                            {{ (isset($menu) && $menu->parent_id == $pm->id) ? 'selected' : '' }}>
                                            {{ $pm->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Menu Title</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" name="title" 
                                       value="{{ old('title', $menu->title ?? '') }}" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">URL / Route</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" name="url" 
                                       value="{{ old('url', $menu->url ?? '') }}" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Icon Class</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i id="icon-preview" class="{{ $menu->icon ?? 'fa fa-link' }}"></i></span>
                                    <input class="form-control" type="text" name="icon" id="icon-input"
                                           value="{{ old('icon', $menu->icon ?? 'fa fa-link') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Permission Name</label>
                            <div class="col-sm-9">
                                <select name="permission_name" class="form-control select2-basic" required>
                                    <option value="">-- Pilih Permission --</option>
                                    @foreach($permissions as $perm)
                                        <option value="{{ $perm->name }}"
                                            {{ (isset($menu) && $menu->permission_name == $perm->name) ? 'selected' : '' }}>
                                            {{ $perm->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Display Order</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="number" name="order" 
                                       value="{{ old('order', $menu->order ?? '1') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($menu) ? 'Update Menu' : 'Simpan Menu' }}
                                </button>
                                <a href="{{ url('admin/menu') }}" class="btn btn-light">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select2-basic').select2({
            placeholder: "-- Pilih --",
            allowClear: true,
            width: '100%' // Memastikan lebar select2 sesuai dengan kolom bootstrap
        });

        // Script Live Icon Preview yang sudah ada sebelumnya
        $('#icon-input').on('keyup', function() {
            $('#icon-preview').attr('class', $(this).val());
        });
    });
</script>
@endsection