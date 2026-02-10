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
                        
                        <div class="col-lg-8 col-md-12 mb-3 mb-lg-0">
                            <a class="logo mb-3" href="{{ route('dashboard') }}">
                                <img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt="logo">
                                <img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="logo">
                            </a>
                            <h4 class="mb-3 fw-bold text-primary">Hasil Studi Mahasiswa</h4>
                            
                            @if(isset($mhs))
                                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start">
                                    
                                    {{-- 1. FOTO PROFIL --}}
                                    <div class="flex-shrink-0 me-md-4 mb-3 mb-md-0">
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
                                        
                                        {{-- Baris Badge Informasi --}}
                                        <div class="mb-2">
                                            {{-- NIM --}}
                                            <span class="badge bg-light text-dark border px-2 mb-1">
                                                <i class="fa fa-id-card-o me-1"></i> {{ $mhs->nim }}
                                            </span>

                                            {{-- Logic Status Mahasiswa --}}
                                            @php
                                                $statusLabel = 'Tidak Diketahui';
                                                $statusClass = 'bg-secondary';
                                                switch($mhs->status) {
                                                    case 1: $statusLabel = 'Aktif'; $statusClass = 'bg-success'; break;
                                                    case 2: $statusLabel = 'Cuti'; $statusClass = 'bg-warning text-dark'; break;
                                                    case 3: $statusLabel = 'Keluar'; $statusClass = 'bg-danger'; break;
                                                    case 4: $statusLabel = 'Lulus'; $statusClass = 'bg-primary'; break;
                                                    case 6: $statusLabel = 'Drop Out'; $statusClass = 'bg-danger'; break;
                                                }
                                            @endphp
                                            <span class="badge {{ $statusClass }} border px-2 mb-1">
                                                {{ $statusLabel }}
                                            </span>

                                            {{-- Logic Kelas --}}
                                            <span class="badge bg-light text-dark border px-2 mb-1">
                                                {{ ($mhs->kelas == 2) ? 'Karyawan' : 'Reguler' }}
                                            </span>
                                        </div>

                                        {{-- Detail Teks (Prodi, Angkatan, TTL) --}}
                                        <div class="text-muted f-12">
                                            @if(isset($mhs->prodi))
                                                <p class="mb-1">
                                                    <i class="fa fa-graduation-cap me-1"></i> 
                                                    {{ $mhs->prodi }} {{ isset($mhs->angkatan) ? ' - Angkatan ' . $mhs->angkatan : '' }}
                                                </p>
                                            @endif

                                            {{-- Tambahan Tempat Tanggal Lahir --}}
                                            @if(isset($mhs->tempat_lahir) || isset($mhs->tgl_lahir))
                                                <p class="mb-1">
                                                    <i class="fa fa-calendar me-1"></i> 
                                                    {{ $mhs->tempat_lahir ?? '' }}, {{ isset($mhs->tgl_lahir) ? date('d F Y', strtotime($mhs->tgl_lahir)) : '' }}
                                                </p>
                                            @endif
                                        </div>

                                        <hr class="my-2 opacity-25">

                                        {{-- Informasi Dosen Wali --}}
                                        <div class="info-dosen">
                                            @if(isset($mhs->dosenWali))
                                                <p class="text-muted f-12 mb-0">
                                                    <i class="fa fa-user-md me-1"></i> Dosen Wali: {{ $mhs->dosenWali }}
                                                </p>
                                            @endif

                                            @if(isset($mhs->noHpDosenWali))
                                                <p class="text-muted f-12 mb-0">
                                                    <i class="fa fa-user-md me-1"></i> No Hp Dosen Wali: +62{{ $mhs->noHpDosenWali }}
                                                </p>
                                            @endif

                                            @if(isset($mhs->emailDosenWali))
                                                <p class="text-muted f-12 mb-0">
                                                    <i class="fa fa-envelope me-1"></i> Email Dosen Wali: {{ $mhs->emailDosenWali }}
                                                </p>
                                            @endif
                                        </div>

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
                    <h2 class="accordion-header" id="headingDaftarNilai">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMainDaftarNilai" aria-expanded="true" aria-controls="collapseMainDaftarNilai">
                            <i class="fa fa-graduation-cap me-2 f-18"></i> Daftar Nilai
                        </button>
                    </h2>
                    
                    <div id="collapseMainDaftarNilai" class="accordion-collapse collapse show" aria-labelledby="headingDaftarNilai" data-bs-parent="#accordionAkademik">
                        <div class="accordion-body bg-light">
                            
                            {{-- ACCORDION CHILD (Per Semester) --}}
                            <div class="accordion" id="accordionDaftarNilai">
                                <div class="mt-4">
                                    <div class="mt-2"></div>
                                    <table class="table table-hover table-border-horizontal mb-3" id="tablekrs">
                                        <thead>
                                            <th>Kode</th>
                                            <th>Nama Matakuliah</th>
                                            <th>SKS</th>
                                            <th>Nilai Akhir</th>
                                        </thead>
                                        <tbody>
                                            @php $totalSks = 0; $totalIps = 0; @endphp
                                            @foreach($getNilai as $rowKrs)
                                                @php $totalSks += ($rowKrs->sks_teori+$rowKrs->sks_praktek);@endphp
                                                <tr>
                                                    <td>{{ $rowKrs['kode_matkul'] }}</td>
                                                    <td>{{ $rowKrs['nama_matkul'] }}</td>
                                                    <td>{{ ($rowKrs->sks_teori+$rowKrs->sks_praktek) }}</td>
                                                    <td>
                                                        @if($rowKrs->validasi_tugas == 1 && $rowKrs->validasi_uts == 1 && $rowKrs->validasi_uas == 1)
                                                            @php $totalIps +=  ($rowKrs->sks_teori+$rowKrs->sks_praktek) * $kualitas[$rowKrs['nhuruf']]; @endphp
                                                            {{ $rowKrs['nakhir']}} | {{ $rowKrs['nhuruf']}}
                                                        @else
                                                            - | -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
                        <div class="accordion-body bg-white p-3">
                            <div class="table-responsive w-100">
                                <table class="table table-hover table-striped mb-0 w-100 border" id="tablekrs" style="font-size: 13px;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode</th>
                                            <th>Nama Matakuliah</th>
                                            <th>Kelas</th>
                                            <th>Hari, Waktu</th>
                                            <th>Ruang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_krs = 0;
                                            $no = 1; // Inisialisasi nomor
                                        @endphp
                                        
                                        @if(isset($krs) && count($krs) > 0)
                                            @foreach($krs as $row_krs)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    {{-- Menggunakan syntax array/object sesuai snippet Anda --}}
                                                    <td>{{ $row_krs['kode_jadwal'] ?? '-' }}</td>
                                                    <td>{{ $row_krs['nama_matkul'] ?? '-' }}</td>
                                                    <td>{{ $row_krs['kel'] ?? '-' }}</td>
                                                    <td>
                                                        {{ $row_krs['hari'] ?? '' }}, {{ $row_krs['nama_sesi'] ?? '' }}
                                                    </td>
                                                    <td>{{ $row_krs['nama_ruang'] ?? '-' }}</td>
                                                    
                                                    {{-- Hitung SKS --}}
                                                    @php 
                                                        $sks_item = ($row_krs->sks_teori ?? 0) + ($row_krs->sks_praktek ?? 0); 
                                                    @endphp
                                                </tr>
                                                @php
                                                    $total_krs += $sks_item;
                                                @endphp
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center text-muted p-4">Belum ada Kartu Rencana Studi (KRS) yang diambil.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    
                                    @if(isset($krs) && count($krs) > 0)
                                    <tfoot class="bg-light fw-bold">
                                        <tr>
                                            <td colspan="5" class="text-end pe-3">Total SKS</td>
                                            <td class="text-center">{{$total_krs}}</td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ITEM 3: KEUANGAN --}}
                <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingKeuangan">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKeuangan" aria-expanded="false" aria-controls="collapseKeuangan">
                            <i class="fa fa-money me-2 f-18"></i> Tagihan Keuangan / Keuangan
                        </button>
                    </h2>
                    <div id="collapseKeuangan" class="accordion-collapse collapse" aria-labelledby="headingKeuangan" data-bs-parent="#accordionAkademik">
                        <div class="accordion-body bg-white p-3">
                            
                            {{-- Cek apakah ada data keuangan (is_publish_keuangan) --}}
                            @if(isset($mhs->is_publish_keuangan) && $mhs->is_publish_keuangan == 1)
                                
                                {{-- Alert Info --}}
                                <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                                    <i class="fa fa-info-circle me-2 f-18"></i>
                                    <div>
                                        Validasi pembayaran dilakukan di hari kerja. Jika ada ketidaksesuaian nominal, silakan hubungi bagian keuangan.
                                    </div>
                                </div>

                                {{-- Judul Tagihan --}}
                                <h6 class="fw-bold text-primary mb-3">{{ $title ?? 'Tagihan Mahasiswa' }}</h6>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped mb-0">
                                                
                                                {{-- LOGIC 1: Jika Prodi ID 1, 2, atau 5 (Bayar UPP Bulanan) --}}
                                                @if(in_array($mhs->id_program_studi, [1, 2, 5]))
                                                    <tbody>
                                                        <tr>
                                                            <td>UPP Bulanan</td>
                                                            <td class="text-end fw-bold">Rp. {{ number_format(($uppBulan ?? 0), 0, ",", ".") }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tunggakan UPP Bulan Lalu</td>
                                                            @php
                                                                $tunggakanUpp = ($newTotalTagihan ?? 0) - ($tagihanTotalBayar ?? 0) - ($uppBulan ?? 0);
                                                            @endphp
                                                            <td class="text-end fw-bold {{ $tunggakanUpp > 0 ? 'text-danger' : 'text-success' }}">
                                                                Rp. {{ $tunggakanUpp > 0 ? number_format($tunggakanUpp, 0, ",", ".") : '0' }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot class="bg-light">
                                                        <tr>
                                                            <th>Total Tagihan</th>
                                                            @if(!empty($statusBayarBayar) && $statusBayarBayar == 1)
                                                                <th class="text-end text-success">Rp. 0 (Lunas)</th>
                                                            @else
                                                                <th class="text-end text-danger">Rp. {{ number_format(($newTotalTagihan ?? 0) - ($tagihanTotalBayar ?? 0), 0, ",", ".") }}</th>
                                                            @endif
                                                        </tr>
                                                    </tfoot>

                                                {{-- LOGIC 2: Prodi Lainnya (Bayar UPP Semester) --}}
                                                @else
                                                    <tbody>
                                                        <tr>
                                                            <td>UPP Semester</td>
                                                            <td class="text-end fw-bold">Rp. {{ number_format(($uppSemester ?? 0), 0, ",", ".") }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tunggakan UPP Semester Lalu</td>
                                                            @php
                                                                $tunggakanSmt = ($newTotalTagihan ?? 0) - ($tagihanTotalBayar ?? 0) - ($uppSemester ?? 0);
                                                            @endphp
                                                            <td class="text-end fw-bold {{ $tunggakanSmt > 0 ? 'text-danger' : 'text-success' }}">
                                                                Rp. {{ $tunggakanSmt > 0 ? number_format($tunggakanSmt, 0, ",", ".") : '0' }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot class="bg-light">
                                                        <tr>
                                                            <th>Total Tagihan</th>
                                                            @if(!empty($statusBayarBayar) && $statusBayarBayar == 1)
                                                                <th class="text-end text-success">Rp. 0 (Lunas)</th>
                                                            @else
                                                                <th class="text-end text-danger">Rp. {{ number_format(($newTotalTagihan ?? 0) - ($tagihanTotalBayar ?? 0), 0, ",", ".") }}</th>
                                                            @endif
                                                        </tr>
                                                    </tfoot>
                                                @endif
                                            </table>
                                        </div>

                                        {{-- STATUS PEMBAYARAN UTAMA --}}
                                        <div class="mt-3">
                                            <div class="p-3 rounded text-white text-center {{ (empty($statusBayarBayar)) ? 'bg-danger' : 'bg-success' }}">
                                                <h5 class="mb-0">
                                                    STATUS: {{ (empty($statusBayarBayar)) ? "BELUM LUNAS" : "LUNAS" }}
                                                </h5>
                                            </div>
                                        </div>

                                        {{-- TABLE DPP (Dana Pengembangan Pendidikan) --}}
                                        @if(isset($dpp))
                                            <div class="mt-4">
                                                <h6 class="fw-bold text-dark mb-2">Rincian DPP</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td>Total DPP Wajib Bayar</td>
                                                                <td class="text-end fw-bold">Rp. {{ number_format(($dpp), 0, ",", ".") }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Sudah Dibayar</td>
                                                                <td class="text-end fw-bold text-success">Rp. {{ number_format(($bayarDpp ?? 0), 0, ",", ".") }}</td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot class="bg-light">
                                                            <tr>
                                                                <th>Sisa Tagihan DPP</th>
                                                                <th class="text-end text-danger">Rp. {{ number_format(($dpp - ($bayarDpp ?? 0)), 0, ",", ".") }}</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                            @else
                                {{-- Jika tidak ada tagihan / belum dipublish --}}
                                <div class="text-center py-5">
                                    <img src="{{ asset('assets/images/dashboard/cartoon.svg') }}" alt="No Data" style="width: 150px; opacity: 0.7;" class="mb-3">
                                    <h5 class="text-muted">Tidak ada tagihan aktif saat ini.</h5>
                                    <p class="text-muted f-12">Anda tidak memiliki tunggakan atau tagihan belum diterbitkan.</p>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- ITEM 4: PRESENSI PERKULIAHAN --}}
                <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingPresensi">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePresensi" aria-expanded="false" aria-controls="collapsePresensi">
                            <i class="fa fa-calendar-check-o me-2 f-18"></i> Presensi Perkuliahan <span class="text-danger">*</span>
                        </button>
                    </h2>
                    <div id="collapsePresensi" class="accordion-collapse collapse" aria-labelledby="headingPresensi" data-bs-parent="#accordionAkademik">
                        <div class="accordion-body bg-light">
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    {{-- Ikon Coming Soon --}}
                                    <span class="fa-stack fa-2x text-muted opacity-50">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-clock-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                </div>
                                <h5 class="text-dark fw-bold">Fitur Segera Hadir</h5>
                                <p class="text-muted mb-0">
                                    Modul Presensi Perkuliahan sedang dalam tahap pengembangan.<br>
                                    Anda akan segera dapat memantau kehadiran mahasiswa di sini.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ITEM 5: JADWAL UJIAN --}}
                <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingUjian">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUjian" aria-expanded="false" aria-controls="collapseUjian">
                            <i class="fa fa-pencil-square-o me-2 f-18"></i> Jadwal Ujian <span class="text-danger">*</span>
                        </button>
                    </h2>
                    <div id="collapseUjian" class="accordion-collapse collapse" aria-labelledby="headingUjian" data-bs-parent="#accordionAkademik">
                        <div class="accordion-body bg-light">
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    {{-- Ikon Coming Soon --}}
                                    <span class="fa-stack fa-2x text-muted opacity-50">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-clock-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                </div>
                                <h5 class="text-dark fw-bold">Fitur Segera Hadir</h5>
                                <p class="text-muted mb-0">
                                    Modul Jadwal Ujian sedang dalam tahap pengembangan.<br>
                                    Anda akan segera dapat memantau jadwal ujian mahasiswa di sini.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ITEM 6: HASIL STUDI SEMESTER LALU --}}
                <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
                    <h2 class="accordion-header" id="headingHistory">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="false" aria-controls="collapseHistory">
                            <i class="fa fa-history me-2 f-18"></i> Hasil Studi Semester Lalu <span class="text-danger">*</span>
                        </button>
                    </h2>
                    <div id="collapseHistory" class="accordion-collapse collapse" aria-labelledby="headingHistory" data-bs-parent="#accordionAkademik">
                        <div class="accordion-body bg-light">
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    {{-- Ikon Coming Soon --}}
                                    <span class="fa-stack fa-2x text-muted opacity-50">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-clock-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                </div>
                                <h5 class="text-dark fw-bold">Fitur Segera Hadir</h5>
                                <p class="text-muted mb-0">
                                    Modul Hasil Semester Lalu sedang dalam tahap pengembangan.<br>
                                    Anda akan segera dapat memantau hasil semester lalu mahasiswa di sini.
                                </p>
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