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
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item">Skripsi</li>
    <li class="breadcrumb-item">Penguji</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Riwayat Penguji Skripsi -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Judul (English): {{ $judulSkripsi->judul_eng }}</h6>
                    <p class="mb-0">Mahasiswa: {{ $mahasiswa->nama }} (NIM: {{ $mahasiswa->nim }})</p>
                    {{-- Detail Sidang --}}
                    @if(isset($sidang))
                        <div class="mt-3">
                            <ul class="list-group mb-3">
                                <li class="list-group-item">
                                    <strong>Tanggal Sidang:</strong> 
                                    {{ \Carbon\Carbon::parse($sidang->tanggal)->format('d F Y') }}
                                    @if(\Carbon\Carbon::parse($sidang->tanggal)->isToday())
                                        <span class="badge bg-warning text-dark ms-2">Hari Ini</span>
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    <strong>Waktu:</strong>
                                    {{ $sidang->waktu_mulai }} - {{ $sidang->waktu_selesai }}
                                </li>
                                <li class="list-group-item"><strong>Ruangan:</strong> {{ $sidang->ruangan }}</li>
                                <li class="list-group-item">
                                    <strong>Jenis Sidang:</strong>
                                    @if($sidang->jenis == 1)
                                        Seminar Proposal
                                    @elseif($sidang->jenis == 2)
                                        Seminar Hasil
                                    @else
                                        {{ $sidang->jenis }}
                                    @endif
                                </li>
                                {{-- <li class="list-group-item"><strong>Status:</strong> 
                                    @if($sidang->status == 1)
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($sidang->status == 2 && isset($penguji) && $penguji->status == 1)
                                        <span class="badge bg-info">Sudah Ternilai (Sidang Masih Berjalan)</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $sidang->status_label ?? 'Belum Validasi' }}</span>
                                    @endif
                                </li> --}}
                            </ul>

                            {{-- Form Input Nilai Sidang (jika tanggal sidang = hari ini) --}}
                            {{-- @if(\Carbon\Carbon::parse($sidang->tanggal)->isToday() || \Carbon\Carbon::parse($sidang->tanggal)->isPast())
                                @if(isset($penguji) && $penguji->status == 1)
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Penulisan (15) : NILAI SEMINAR PROPOSAL</label>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div>Kesinambungan penulisan dan bahasa: <strong>{{ $penguji->kesinambungan ?? '-' }}</strong></div>
                                                <div>Kesesuaian isi dengan daftar pustaka: <strong>{{ $penguji->kesesuaian_daftar_pustaka ?? '-' }}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Isi (30)</label>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div>Keterbaruan penelitian: <strong>{{ $penguji->keterbaruan ?? '-' }}</strong></div>
                                                <div>Kejelasan rumusan masalah: <strong>{{ $penguji->kejelasan_rumus ?? '-' }}</strong></div>
                                                <div>Relevansi antara latar belakang, rumusan masalah, dan metodologi penelitian: <strong>{{ $penguji->relevansi_latar_belakang ?? '-' }}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Presentasi (10)</label>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div>Penampilan dan sikap selama tanya jawab: <strong>{{ $penguji->penampilan_sikap ?? '-' }}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Tanya Jawab (20)</label>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div>Kemampuan menyampaikan argumentasi: <strong>{{ $penguji->argumen ?? '-' }}</strong></div>
                                                <div>Kesesuaian antara jawaban dengan pertanyaan: <strong>{{ $penguji->kesesuaian_jawaban ?? '-' }}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Pengetahuan (25)</label>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div>Kedalaman penguasaan materi: <strong>{{ $penguji->kedalaman_penguasaan ?? '-' }}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Jumlah (70-100)</label>
                                        <div><strong>{{ $penguji->jumlah_nilai ?? '-' }}</strong></div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Nilai Akhir seminar proposal (70%)</label>
                                        <div><strong>{{ $penguji->nilai_akhir ?? '-' }}</strong></div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label mb-1">Catatan Penguji</label>
                                        <div class="form-control-plaintext">
                                            <blockquote class="blockquote border-start border-3 ps-3 fst-italic text-muted">
                                                {{ $penguji->catatan ?? '-' }}
                                            </blockquote>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('akademik.skripsi.dosen.penguji.update-nilai', $sidang->idEnkripsi) }}" method="POST" class="mb-3" id="form-nilai-sidang">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-2">
                                            <label class="form-label fw-bold">Penulisan (15) : NILAI SEMINAR PROPOSAL</label>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <label>Kesinambungan penulisan dan bahasa <span class="text-muted">(5 - 8)</span></label>
                                                    <input type="number" name="kesinambungan" class="form-control d-inline w-auto ms-2" min="5" max="8" value="{{ old('kesinambungan', $penguji->kesinambungan ?? '') }}" required>
                                                    <label class="ms-3">Kesesuaian isi dengan daftar pustaka <span class="text-muted">(5 - 7)</span></label>
                                                    <input type="number" name="kesesuaianDaftarPustaka" class="form-control d-inline w-auto ms-2" min="5" max="7" value="{{ old('kesesuaianDaftarPustaka', $penguji->kesesuaian_daftar_pustaka ?? '') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-bold">Isi (30)</label>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <label>Keterbaruan penelitian <span class="text-muted">(8 - 10)</span></label>
                                                    <input type="number" name="keterbaruan" class="form-control d-inline w-auto ms-2" min="8" max="10" value="{{ old('keterbaruan', $penguji->keterbaruan ?? '') }}" required>
                                                    <label class="ms-3">Kejelasan rumusan masalah <span class="text-muted">(8 - 10)</span></label>
                                                    <input type="number" name="kejelasanRumus" class="form-control d-inline w-auto ms-2" min="8" max="10" value="{{ old('kejelasanRumus', $penguji->kejelasan_rumus ?? '') }}" required>
                                                    <label class="ms-3">Relevansi antara latar belakang, rumusan masalah, dan metodologi penelitian <span class="text-muted">(8 - 10)</span></label>
                                                    <input type="number" name="relevansiLatarBelakang" class="form-control d-inline w-auto ms-2" min="8" max="10" value="{{ old('relevansiLatarBelakang', $penguji->relevansi_latar_belakang ?? '') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-bold">Presentasi (10)</label>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <label>Penampilan dan sikap selama tanya jawab <span class="text-muted">(5 - 10)</span></label>
                                                    <input type="number" name="penampilanSikap" class="form-control d-inline w-auto ms-2" min="5" max="10" value="{{ old('penampilanSikap', $penguji->penampilan_sikap ?? '') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-bold">Tanya Jawab (20)</label>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <label>Kemampuan menyampaikan argumentasi <span class="text-muted">(8 - 10)</span></label>
                                                    <input type="number" name="argumen" class="form-control d-inline w-auto ms-2" min="8" max="10" value="{{ old('argumen', $penguji->argumen ?? '') }}" required>
                                                    <label class="ms-3">Kesesuaian antara jawaban dengan pertanyaan <span class="text-muted">(8 - 10)</span></label>
                                                    <input type="number" name="kesesuaianJawaban" class="form-control d-inline w-auto ms-2" min="8" max="10" value="{{ old('kesesuaianJawaban', $penguji->kesesuaian_jawaban ?? '') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-bold">Pengetahuan (25)</label>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <label>Kedalaman penguasaan materi <span class="text-muted">(15 - 25)</span></label>
                                                    <input type="number" name="kedalamanPenguasaan" class="form-control d-inline w-auto ms-2" min="15" max="25" value="{{ old('kedalamanPenguasaan', $penguji->kedalaman_penguasaan ?? '') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-bold">Jumlah (70-100)</label>
                                            <input type="number" name="jumlahNilai" class="form-control" min="70" max="100" value="{{ old('jumlahNilai', $penguji->jumlah_nilai ?? '') }}" readonly>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-bold">Nilai Akhir seminar proposal (70%)</label>
                                            <input type="number" name="nilaiAkhir" class="form-control" min="0" max="100" value="{{ old('nilaiAkhir', $penguji->nilai_akhir ?? '') }}" readonly>
                                        </div>
                                        <div class="mb-2">
                                            <label for="catatan" class="form-label">Catatan Penguji</label>
                                            <textarea name="catatan" id="catatan" class="form-control" rows="2">{{ old('catatan', $penguji->catatan ?? '') }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm btn-nilai" title="Simpan Nilai">Simpan</button>
                                    </form>
                                    <div class="d-flex justify-content-end">
                                        <form action="{{ route('akademik.skripsi.dosen.penguji.update-status', $sidang->idEnkripsi) }}" method="POST" id="form-status-sidang">
                                            @csrf
                                            @method('PUT')
                                            <button name="status" type="submit" class="btn btn-success btn-sm" title="Validasi Nilai" id="btn-validasi">Validasi</button>
                                        </form>
                                    </div>
                                @endif
                            @endif --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>

    <script>
    $(function () {
        $('#form-penguji').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = form.serialize();

            var $btn = $('.btn-submit');
            var originalText = $btn.html();
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    form.find('button[type=submit]').prop('disabled', true).text('Menyimpan...');
                },
                success: function(response) {
                    $btn.prop('disabled', false);
                    $btn.html(originalText);
                    swal({
                        title: "Berhasil!",
                        text: response.message || "Data berhasil disimpan.",
                        icon: "success",
                        timer: 2000,
                        buttons: false
                    });
                    location.redirect = "{{ route('pengajuan-skripsi') }}";
                },
                error: function(xhr) {
                    swal({
                        title: "Gagal!",
                        text: xhr.responseJSON?.message || "Terjadi kesalahan.",
                        icon: "error",
                        timer: 2000,
                        buttons: false
                    });
                },
                complete: function() {
                    form.find('button[type=submit]').prop('disabled', false).text('Simpan');
                }
            });
        });

        $(document).on('submit', '#form-nilai-sidang', function(e) {
            var $btn = $(this).find('button[type="submit"]');
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
        });

        $(document).on('click', '#btn-validasi', function(e) {
            e.preventDefault();
            var $form = $(this).closest('form');
            var $btn = $(this);
            swal({
            title: "Konfirmasi Validasi",
            text: "Setelah divalidasi, Anda tidak dapat mengubah nilai lagi. Lanjutkan?",
            icon: "warning",
            buttons: ["Batal", "Ya, Validasi"],
            dangerMode: true,
            }).then(function(willValidate) {
            if (willValidate) {
                $btn.prop('disabled', true);
                $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                $form.submit();
            }
            });
        });

        $(document).on('submit', '.form-catatan-dosen', function(e) {
            var $btn = $(this).find('.btn-submit-catatan');
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
        });

        // Untuk banyak form dengan class .form-update-status
        $(document).on('click', '.form-update-status button[type="submit"], .form-update-status button[name="status"]', function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = btn.closest('form');
            var statusValue = btn.val();
            var statusText = btn.text().trim() || 'Update';
            
            swal({
                title: "Konfirmasi",
                text: "Anda yakin ingin mengubah status menjadi '" + statusText + "'?",
                icon: "warning",
                buttons: ["Batal", "Ya"],
                dangerMode: true,
            }).then(function(willUpdate) {
                if (willUpdate) {
                    var formData = form.serializeArray();
                    formData.push({ name: 'status', value: statusValue });

                    $.ajax({
                        url: form.attr('action'),
                        method: form.attr('method'),
                        data: $.param(formData),
                        dataType: 'json',
                        beforeSend: function() {
                            btn.prop('disabled', true).text('Memproses...');
                        },
                        success: function(response) {
                            swal({
                                title: "Berhasil!",
                                text: response.message || "Status berhasil diubah.",
                                icon: "success",
                                timer: 2000,
                                buttons: false
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        },
                        error: function(xhr) {
                            swal({
                                title: "Gagal!",
                                text: xhr.responseJSON?.message || "Terjadi kesalahan.",
                                icon: "error",
                                timer: 2000,
                                buttons: false
                            });
                        },
                        complete: function() {
                            btn.prop('disabled', false).text(statusText);
                        }
                    });
                }
            });
        });

        let $inputs = $('[name="kesinambungan"], [name="kesesuaianDaftarPustaka"], [name="keterbaruan"], [name="kejelasanRumus"], [name="relevansiLatarBelakang"], [name="penampilanSikap"], [name="argumen"], [name="kesesuaianJawaban"], [name="kedalamanPenguasaan"]');
        $inputs.on('input', hitungJumlah);

        hitungJumlah();
    });

    function hitungJumlah() {
        let n1 = parseFloat(document.querySelector('[name="kesinambungan"]').value) || 0;
        let n2 = parseFloat(document.querySelector('[name="kesesuaianDaftarPustaka"]').value) || 0;
        let n3 = parseFloat(document.querySelector('[name="keterbaruan"]').value) || 0;
        let n4 = parseFloat(document.querySelector('[name="kejelasanRumus"]').value) || 0;
        let n5 = parseFloat(document.querySelector('[name="relevansiLatarBelakang"]').value) || 0;
        let n6 = parseFloat(document.querySelector('[name="penampilanSikap"]').value) || 0;
        let n7 = parseFloat(document.querySelector('[name="argumen"]').value) || 0;
        let n8 = parseFloat(document.querySelector('[name="kesesuaianJawaban"]').value) || 0;
        let n9 = parseFloat(document.querySelector('[name="kedalamanPenguasaan"]').value) || 0;

        let jumlah = n1 + n2 + n3 + n4 + n5 + n6 + n7 + n8 + n9;
        document.querySelector('[name="jumlahNilai"]').value = jumlah;

        // Nilai akhir = jumlah * 0.7
        let nilaiAkhir = Math.round(jumlah * 0.7 * 100) / 100;
        document.querySelector('[name="nilaiAkhir"]').value = nilaiAkhir;
    }
    </script>

@endsection
