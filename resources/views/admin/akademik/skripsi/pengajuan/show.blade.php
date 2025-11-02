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
    <li class="breadcrumb-item">Pengajuan</li>
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <strong>Nama:</strong> {{ $mahasiswa->nama }}<br>
                    <strong>NIM:</strong> {{ $mahasiswa->nim }}
                </div>
                <div class="card-body">

                    <form id="form-judul" action="{{ route('store-pengajuan-skripsi') }}" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="idMaster" value="{{ $masterSkripsi->id }}">
                            @foreach ($judulSkripsi as $index => $row)
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <strong>Judul {{ $index+1 }}:</strong> {{ $row->judul }}
                                            @php
                                                $icon = '';
                                                $iconClass = '';
                                                $iconTitle = '';
                                                switch ($row->status) {
                                                    case 1:
                                                        $icon = 'bi-check-circle-fill';
                                                        $iconClass = 'text-success';
                                                        $iconTitle = 'Diterima';
                                                        break;
                                                    case 2:
                                                        $icon = 'bi-pencil-square';
                                                        $iconClass = 'text-warning';
                                                        $iconTitle = 'Revisi';
                                                        break;
                                                    case 3:
                                                        $icon = 'bi-x-circle-fill';
                                                        $iconClass = 'text-danger';
                                                        $iconTitle = 'Ditolak';
                                                        break;
                                                    default:
                                                        $icon = 'bi-hourglass-split';
                                                        $iconClass = 'text-secondary';
                                                        $iconTitle = 'Pengajuan';
                                                }
                                            @endphp
                                            <i class="bi {{ $icon }} {{ $iconClass }} ms-2" title="{{ $iconTitle }}"></i>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>Bidang Minat:</strong> {{ $row->nama_bidang_minat ?? '-' }}
                                            </div>
                                            <div class="mb-2">
                                                <label for="catatan-judul-{{ $row->id }}" class="form-label"><strong>Catatan:</strong> <span class="text-danger">*</span></label>
                                                <textarea id="catatan-judul-{{ $row->id }}" name="catatanJudul{{ $row->id }}" class="form-control" rows="2" placeholder="Tulis catatan Judul {{ $index+1 }} disini" {{ $row->status == 3 ? 'disabled' : 'required aria-required="true"' }}></textarea>
                                            </div>
                                            <input type="hidden" name="statusJudul{{ $row->id }}" id="status-judul-{{ $row->id }}" value="{{ $row->status }}">
                                            <div class="d-flex gap-2 mb-2">
                                                <button type="button" class="btn btn-success btn-sm btn-setujui" data-index="{{ $row->id }}">Setujui</button>
                                                <button type="button" class="btn btn-danger btn-sm btn-tolak" data-index="{{ $row->id }}">Tolak</button>
                                                <button type="button" class="btn btn-warning btn-sm btn-revisi" data-index="{{ $row->id }}">Revisi</button>
                                            </div>
                                            <div id="status-label-{{ $row->id }}"></div>

                                            <!-- Pembimbing per Judul -->
                                            <div class="mt-3 pembimbing-section-{{ $row->id }}" style="display:none;">
                                                <h6>Pilih Pembimbing:</h6>
                                                <div class="mb-2">
                                                    <label class="form-label">Pembimbing 1</label>
                                                    <select class="form-select pembimbing1" name="pembimbing1_{{ $row->id }}" data-judul="{{ $row->id }}">
                                                        <option selected disabled>Pilih Pembimbing 1</option>
                                                        @forelse ($dosen->where('id_bidang_minat', $row->id_bidang_minat) as $d)
                                                            <option value="{{ $d->npp }}" {{ $row->pembimbing_1 == $d->npp ? 'selected' : '' }}>
                                                                {{ $d->nama }} (NPP: {{ $d->npp }}) &mdash; Kuota: {{ $d->kuota }}
                                                            </option>
                                                        @empty
                                                            <option disabled>Tidak ada pembimbing tersedia</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Pembimbing 2</label>
                                                    <select class="form-select pembimbing2" name="pembimbing2_{{ $row->id }}" data-judul="{{ $row->id }}">
                                                        <option selected disabled>Pilih Pembimbing 2</option>
                                                        @forelse ($dosen->where('id_bidang_minat', $row->id_bidang_minat) as $d)
                                                            <option value="{{ $d->npp }}" {{ $row->pembimbing_2 == $d->npp ? 'selected' : '' }}>
                                                                {{ $d->nama }} (NPP: {{ $d->npp }}) &mdash; Kuota: {{ $d->kuota }}
                                                            </option>
                                                        @empty
                                                            <option disabled>Tidak ada pembimbing tersedia</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary mt-2 btn-submit">Simpan</button>
                    </form>

                    <!-- Abstrak Umum -->
                    @if ($judulSkripsi->isNotEmpty())
                        <div class="mt-3">
                            <p><strong>Abstrak:</strong> {{ $judulSkripsi->first()->abstrak }}</p>
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
        // 1. Tombol disabled sebelum catatan diisi
        $('[id^=catatan-judul-]').each(function() {
            var index = $(this).attr('id').split('-')[2];
            var btns = $('.btn-setujui[data-index="'+index+'"], .btn-tolak[data-index="'+index+'"], .btn-revisi[data-index="'+index+'"]');
            btns.prop('disabled', true);

            $(this).on('input', function() {
                if ($(this).val().trim().length > 0) {
                    btns.prop('disabled', false);
                } else {
                    btns.prop('disabled', true);
                }
            });
        });

        // 2. Pilihan pembimbing 2 otomatis menghilangkan dosen yang dipilih di pembimbing 1 (per judul)
        $('.pembimbing1').on('change', function() {
            var judulId = $(this).data('judul');
            var selectedNpp = $(this).val();
            var $select2 = $('.pembimbing2[data-judul="'+judulId+'"]');
            
            $select2.find('option').show();
            if (selectedNpp) {
                $select2.find('option[value="'+selectedNpp+'"]').hide();
                if ($select2.val() === selectedNpp) {
                    $select2.val('');
                }
            }
        });

        $('.pembimbing2').on('change', function() {
            var judulId = $(this).data('judul');
            var selectedNpp = $(this).val();
            var $select1 = $('.pembimbing1[data-judul="'+judulId+'"]');
            
            $select1.find('option').show();
            if (selectedNpp) {
                $select1.find('option[value="'+selectedNpp+'"]').hide();
                if ($select1.val() === selectedNpp) {
                    $select1.val('');
                }
            }
        });

        // 3. Update pembimbing section per judul
        function updatePembimbingSection(index) {
            var status = $('#status-judul-' + index).val();
            if (status == '1') {
                $('.pembimbing-section-' + index).show();
            } else {
                $('.pembimbing-section-' + index).hide();
            }
        }

        function setStatusLabel(index, status) {
            var label = '';
            if (status == 1) label = '<span class="badge bg-success">Diterima</span>';
            else if (status == 2) label = '<span class="badge bg-warning text-dark">Revisi</span>';
            else if (status == 3) label = '<span class="badge bg-danger">Ditolak</span>';
            else label = '';
            $('#status-label-' + index).html(label);
        }

        $('#form-judul').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            // Tampilkan konfirmasi sebelum submit
            swal({
                title: "Konfirmasi",
                text: "Yakin ingin menyimpan pengajuan ini?",
                icon: "warning",
                buttons: ["Batal", "Ya, Simpan"],
                dangerMode: true,
            }).then(function(willSave) {
                if (!willSave) return;

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
                        window.location.href = "{{ route('pengajuan-skripsi') }}";
                    },
                    error: function(xhr) {
                        console.log('====================================');
                        console.log(xhr);
                        console.log('====================================');
                        $btn.prop('disabled', false);
                        $btn.html(originalText);
                        swal({
                            title: "Gagal!",
                            text: xhr.responseJSON?.message || "Terjadi kesalahan.",
                            icon: "error",
                            timer: 2000,
                            buttons: false
                        });
                    }
                });
            });
        });

        $('.btn-setujui').on('click', function() {
            var index = $(this).data('index');
            $('#status-judul-' + index).val(1);
            setStatusLabel(index, 1);
            updatePembimbingSection(index);
        });
        $('.btn-tolak').on('click', function() {
            var index = $(this).data('index');
            $('#status-judul-' + index).val(3);
            setStatusLabel(index, 3);
            updatePembimbingSection(index);
        });
        $('.btn-revisi').on('click', function() {
            var index = $(this).data('index');
            $('#status-judul-' + index).val(2);
            setStatusLabel(index, 2);
            updatePembimbingSection(index);
        });

        // Inisialisasi awal
        $('[id^=status-judul-]').each(function() {
            var index = $(this).attr('id').split('-')[2];
            updatePembimbingSection(index);
        });
    });
    </script>

@endsection
