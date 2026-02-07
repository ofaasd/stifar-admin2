@extends('layouts.authentication.master2')
@section('title', 'Hasil Studi Mahasiswa')

@section('css')
<style>
    .card-header.bg-primary {
        background-color: #7366ff !important;
        color: white;
    }
    .container-fluid {
        background-color: #f5f7fb;
        min-height: 100vh;
        padding-top: 30px;
        padding-bottom: 30px;
    }
    
    /* Style agar header terlihat bisa diklik */
    .card-header[data-bs-toggle="collapse"] {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .card-header[data-bs-toggle="collapse"]:hover {
        background-color: #5e50ee !important; /* Warna sedikit lebih gelap saat hover */
    }

    /* Animasi panah chevron berputar */
    .header-icon {
        transition: transform 0.3s ease-in-out;
    }
    /* Ketika collapse terbuka (aria-expanded=true), putar icon */
    [aria-expanded="true"] .header-icon {
        transform: rotate(180deg);
    }
</style>
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            {{-- Header Informasi Mahasiswa & Tombol Keluar --}}
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        
                        {{-- KOLOM 1: INFO MAHASISWA & FOTO --}}
                        <div class="col-lg-8 col-md-12 mb-3 mb-lg-0">
                            <a class="logo mb-3" href="{{ route('dashboard') }}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt="looginpage"><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage"></a>
                            <h4 class="mb-3 fw-bold text-primary">Hasil Studi Mahasiswa</h4>
                            
                            @if(isset($mhs))
                                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start">
                                    
                                    {{-- 1. FOTO PROFIL --}}
                                    <div class="flex-shrink-0 me-md-4 mb-3 mb-md-0">
                                        {{-- Cek apakah ada foto di database --}}
                                        @php
                                            $fotoPath = isset($mhs->foto_mhs) && !empty($mhs->foto_mhs) 
                                                ? asset('assets/images/mahasiswa/' . $mhs->foto_mhs)
                                                : 'https://ui-avatars.com/api/?name='.urlencode($mhs->nama).'&background=random&color=fff&size=128';
                                        @endphp
                                        <img src="{{ $fotoPath }}" 
                                            alt="Foto Profil" 
                                            class="rounded-circle shadow-sm" 
                                            style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #fff;">
                                    </div>

                                    {{-- 2. DETAIL INFORMASI --}}
                                    <div class="text-center text-md-start">
                                        <h5 class="fw-bold text-dark mb-1">{{ $mhs->nama }}</h5>
                                        
                                        <div class="text-muted mb-2">
                                            <span class="badge bg-light text-dark border px-2">
                                                <i class="fa fa-id-card-o me-1"></i> {{ $mhs->nim }}
                                            </span>
                                            {{-- Jika ada data Prodi & Angkatan --}}
                                            @if(isset($mhs->prodi))
                                                <span class="badge bg-light text-dark border px-2">
                                                    {{ $mhs->prodi }} {{ isset($mhs->angkatan) ? '- ' . $mhs->angkatan : '' }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Optional: Dosen Wali --}}
                                        @if(isset($mhs->dosenWali))
                                            <p class="text-muted f-12 mb-0">
                                                <i class="fa fa-user-md me-1"></i> Dosen Wali: {{ $mhs->dosenWali }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- KOLOM 2: STATISTIK AKADEMIK (BADGE) --}}
                        <div class="col-lg-4 col-md-12 text-center text-lg-end">
                            @if(isset($mhs))
                                <div class="card bg-light border-0 p-3 d-inline-block text-start w-100 w-lg-auto">
                                    <h6 class="text-muted f-12 text-uppercase fw-bold mb-3">Ringkasan Akademik</h6>
                                    
                                    <div class="d-flex justify-content-between justify-content-lg-end gap-3">
                                        {{-- IPK --}}
                                        <div class="text-center px-2">
                                            <h3 class="fw-bold text-primary mb-0">{{ $mhs->ipk ?? '0.00' }}</h3>
                                            <span class="f-12 text-muted">IPK</span>
                                        </div>

                                        <div style="border-left: 1px solid #ddd;"></div>

                                        {{-- SKS --}}
                                        <div class="text-center px-2">
                                            <h3 class="fw-bold text-info mb-0">{{ $mhs->totalSks ?? '0' }}</h3>
                                            <span class="f-12 text-muted">Total SKS</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 
                ================================================
                ACCORDION UTAMA (PARENT) - id="accordionAkademik"
                ================================================
            --}}
            <div class="accordion" id="accordionAkademik">

                {{-- ITEM 1: KHS (Kartu Hasil Studi) --}}
                <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingKHS">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMainKHS" aria-expanded="true" aria-controls="collapseMainKHS">
                            <i class="fa fa-graduation-cap me-2 f-18"></i> Kartu Hasil Studi (KHS)
                        </button>
                    </h2>
                    
                    {{-- Isi KHS (Looping Tahun Ajaran ada di sini) --}}
                    <div id="collapseMainKHS" class="accordion-collapse collapse show" aria-labelledby="headingKHS" data-bs-parent="#accordionAkademik">
                        <div class="accordion-body bg-light">
                            
                            {{-- ACCORDION CHILD (KHS per Semester) - id="accordionKHS" --}}
                            <div class="accordion" id="accordionKHS">
                                @if(isset($tahunAjaran) && count($tahunAjaran) > 0)
                                    @foreach($tahunAjaran as $rowTa)
                                        @php 
                                            $ta = $rowTa->id;
                                            $sks_semester = 0;
                                            $found_data = false;
                                            $khsSemesterIni = []; 

                                            foreach($krsNow as $k) {
                                                if(isset($nilai[$k->id_jadwal][$ta][$mhs->nim])) {
                                                    $sks_semester += ($k->sks_teori + $k->sks_praktek);
                                                    $found_data = true;
                                                    $khsSemesterIni[] = $k;
                                                }
                                            }
                                        @endphp

                                        <div class="card mb-3 border">
                                            {{-- Header Semester --}}
                                            <div class="card-header bg-white p-3 btn-header collapsed" 
                                                 data-bs-toggle="collapse" 
                                                 data-bs-target="#collapseTa{{$ta}}" 
                                                 aria-expanded="false">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8 d-flex align-items-center">
                                                        <i class="fa fa-chevron-down me-3 header-icon text-primary"></i>
                                                        <span class="mb-0 fw-bold text-dark">{{$rowTa->keterangan}}</span>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <span class="badge bg-primary">SKS: {{ $sks_semester }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Body Semester --}}
                                            {{-- Perhatikan data-bs-parent="#accordionKHS" agar antar semester saling menutup --}}
                                            <div id="collapseTa{{$ta}}" class="collapse" data-bs-parent="#accordionKHS">
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover mb-0" style="font-size: 13px;">
                                                            <thead class="bg-light">
                                                                <tr>
                                                                    <th class="text-center">Kode</th>
                                                                    <th>Matakuliah</th>
                                                                    <th class="text-center">SKS</th>
                                                                    <th class="text-center">Nilai</th>
                                                                    <th class="text-center">Huruf</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if(count($khsSemesterIni) > 0)
                                                                    @foreach($khsSemesterIni as $rowKrs)
                                                                        @php $val = $nilai[$rowKrs->id_jadwal][$ta][$mhs->nim]; @endphp
                                                                        <tr>
                                                                            <td class="text-center">{{ $rowKrs['kode_matkul'] }}</td>
                                                                            <td>{{ $rowKrs['nama_matkul'] }}</td>
                                                                            <td class="text-center">{{ ($rowKrs->sks_teori+$rowKrs->sks_praktek) }}</td>
                                                                            <td class="text-center font-weight-bold">{{ $val['nilai_akhir'] }}</td>
                                                                            <td class="text-center"><span class="badge badge-info">{{ $val['nilai_huruf'] }}</span></td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr><td colspan="5" class="text-center text-muted p-3">Belum ada nilai.</td></tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-light text-center">Data akademik belum tersedia.</div>
                                @endif
                            </div> {{-- End Accordion Child --}}

                        </div>
                    </div>
                </div>

                {{-- ITEM 2: KRS (Kartu Rencana Studi) --}}
                <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingKRS">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMainKRS" aria-expanded="false" aria-controls="collapseMainKRS">
                            <i class="fa fa-calendar-check-o me-2 f-18"></i> Kartu Rencana Studi (KRS)
                        </button>
                    </h2>
                    <div id="collapseMainKRS" class="accordion-collapse collapse" aria-labelledby="headingKRS" data-bs-parent="#accordionAkademik">
                        <div class="accordion-body bg-light">
                            <div class="text-center py-4 text-muted">
                                <i class="fa fa-wrench mb-2 f-24"></i><br>
                                Fitur KRS Mahasiswa akan segera hadir di sini.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ITEM 3: TAGIHAN / KEUANGAN (Contoh Tambahan) --}}
                <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingKeuangan">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKeuangan" aria-expanded="false" aria-controls="collapseKeuangan">
                            <i class="fa fa-money me-2 f-18"></i> Riwayat Keuangan
                        </button>
                    </h2>
                    <div id="collapseKeuangan" class="accordion-collapse collapse" aria-labelledby="headingKeuangan" data-bs-parent="#accordionAkademik">
                        <div class="accordion-body bg-light">
                            <div class="text-center py-4 text-muted">
                                <i class="fa fa-info-circle mb-2 f-24"></i><br>
                                Data keuangan belum tersedia.
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- End Accordion Utama --}}

        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
@endsection