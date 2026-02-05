@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Import — Open Feeder</h4>
        </div>
        <div class="card-body">
            @if(empty($files))
                <div class="alert alert-info">Tidak ada file ditemukan.</div>
            @else
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Nama File</th>
                            <th>Ukuran (bytes)</th>
                            <th>Diubah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                        <tr>
                            <td>{{ $file['display_name'] }}</td>
                            <td>{{ $file['size'] }}</td>
                            <td>{{ $file['modified'] }}</td>
                            <td>
                                <a href="{{ $file['url'] }}" class="btn btn-sm btn-primary" target="_blank" rel="noopener">Buka</a>
                                <a href="{{ $file['url'] }}" class="btn btn-sm btn-success" download>Unduh</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
