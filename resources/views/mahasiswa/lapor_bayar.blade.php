@extends('layouts.master')
@section('title', 'Basic DataTables')

@section('css')

@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<style>
        .payment-form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Keuangan</li>
    <li class="breadcrumb-item active">Lapor</li>
@endsection

@section('content')
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
             Gunakan Form Lapor Pembayaran Jika Sudah melakukan konfirmasi pembayaran
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="alert alert-primary">
            Validasi pembayaran dilakukan dihari kerja, jika ada ketidaksesuaian dalam nominal pembayaran silahkan dapat menghubungi bagian keuangan.
        </div>
        <div class="payment-form-container">
            <h2 class="text-center mb-4">Lapor Pembayaran</h2>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Menampilkan Pesan Error Validasi -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{url('/mhs/lapor_bayar')}}" method="POST" enctype="multipart/form-data">
                <!-- Token CSRF Wajib untuk keamanan Laravel -->
                @csrf

                <!-- Tanggal Bayar -->
                <div class="mb-3">
                    <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                    <input type="date" class="form-control" id="tanggal_bayar" name="tanggal_bayar" required>
                </div>

                <!-- Atas Nama Rekening -->
                <div class="mb-3">
                    <label for="nama_rekening" class="form-label">Atas Nama Rekening Pengirim</label>
                    <input type="text" class="form-control" id="nama_rekening" name="nama_rekening" placeholder="Contoh: Budi Santoso" required>
                </div>

                <!-- Bukti Bayar -->
                <div class="mb-4">
                    <label for="bukti_bayar" class="form-label">Upload Bukti Bayar</label>
                    <input class="form-control" type="file" id="bukti_bayar" name="bukti_bayar" accept="image/png, image/jpeg, application/pdf" required>
                    <div class="form-text">
                        File yang diizinkan: .JPG, .PNG, .PDF. Ukuran maksimal 2MB.
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Kirim Bukti Bayar</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
