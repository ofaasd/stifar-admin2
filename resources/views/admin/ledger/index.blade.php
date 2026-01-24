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
                    <div class="card-body" style="overflow-x:scroll;">
                        <form class="form theme-form" method="GET" action="{{url('admin/ledger')}}">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Program Studi</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="prodi_id" required>
                                        <option value="">-- Pilih Program Studi --</option>
                                        @foreach($prodi as $p)
                                            <option value="{{$p->id}}">{{$p->nama_prodi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Angkatan</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="tahun_angkatan" required>
                                        <option value="">-- Pilih Tahun Angkatan --</option>
                                        @foreach($tahun_angkatan as $ta)
                                            <option value="{{$ta->angkatan}}">{{$ta->angkatan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Tahun Akademik</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="tahun_ajaran" required>
                                        <option value="">-- Pilih Tahun Akademik --</option>
                                        @foreach($tahun_ajaran as $ta)
                                            <option value="{{$ta->id}}">{{$ta->keterangan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>  
                        </form>
                        @if(!empty($data_mahasiswa))
                        <hr />
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>IPK</th>
                                    <th>SKS Total </th>
                                    <th>IPK</th>
                                    <th>SKS Sem</th>
                                    @foreach($header_matkul as $hm)
                                        <th>{{$hm->kode_matkul}} - {{$hm->nama_matkul}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data_mahasiswa as $i => $mhs)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>{{ $mhs->nim }}</td>
                                        <td>{{ $mhs->nama }}</td>
                                        
                                        <td class="text-center fw-bold">{{ $mhs->ipk ?? '0.00' }}</td>
                                        <td class="text-center">{{ $mhs->total_sks }}</td>
                                        <td class="text-center">{{ $mhs->ips ?? '0.00' }}</td>
                                        <td class="text-center">{{ $mhs->sks_sem }}</td>

                                        @foreach($header_matkul as $mk)
                                            @php
                                                // Cek apakah mahasiswa punya nilai untuk MK ini di array map
                                                $nilai = $mhs->nilai_map[$mk->id] ?? null;
                                            @endphp

                                            <td class="text-center">
                                                @if($nilai)
                                                    <span class="{{ ($nilai == 'D' || $nilai == 'E') ? 'text-danger fw-bold' : '' }}">
                                                        {{ $nilai }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 7 + count($header_matkul) }}" class="text-center p-4">
                                            Data Mahasiswa tidak ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @endif
                    </div>
                    
                </div>
            </div>
            <!-- Zero Configuration  Ends-->
        </div>
    </div>
@endsection 

@section('script')
    <script>
        
    </script>
@endsection