@extends('layouts.master')

@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/echart.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('style')
<style>
.widget-1{
    background-image:none;
}
    .select2-container .select2-selection--single {
        height: 38px !important; /* Menyamakan tinggi dengan input bootstrap */
        padding: 5px;
        border: 1px solid #dee2e6;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .loading-overlay {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255, 0.8);
        z-index: 50; display: none;
        justify-content: center; align-items: center;
        border-radius: 0.375rem;
    }
</style>
@endsection

@section('breadcrumb-title')
    <h3>{{$title}}</h3>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Default</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row widget-grid">
        <div class="col-xxl-4 col-xl-4 col-sm-4 box-col-4">
            <div class="row">
            <div class="col-xl-12">
                <div class="card widget-1">
                <div class="card-body">
                    <div class="widget-content">
                    <div class="widget-round secondary">
                        <div class="bg-round">
                        <svg class="svg-fill">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"> </use>
                        </svg>
                        <svg class="half-circle svg-fill">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                        </svg>
                        </div>
                    </div>
                    <div>
                        <h4>{{number_format($jumlah_krs)}} / {{number_format($jumlah_mhs)}}</h4><span class="f-light">Jumlah Mahasiswa KRS</span>
                    </div>
                    </div>
                    <!-- <div class="font-secondary f-w-500"><i class="icon-arrow-up icon-rotate me-1"></i><span>+50%</span></div> -->
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-xxl-4 col-xl-4 col-sm-4 box-col-4">
            <div class="row">
            <div class="col-xl-12">
                <div class="card widget-1">
                <div class="card-body">
                    <div class="widget-content">
                    <div class="widget-round success">
                        <div class="bg-round">
                            <svg class="svg-fill">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#rate') }}"> </use>
                            </svg>
                            <svg class="half-circle svg-fill">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4>{{number_format($jumlah_uts)}} / {{number_format($jumlah_mhs)}}</h4><span class="f-light">Jumlah Mahasiswa UTS</span>
                    </div>
                    </div>
                    <!-- <div class="font-primary f-w-500"><i class="icon-arrow-up icon-rotate me-1"></i><span>+70%</span></div> -->
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-xxl-4 col-xl-4 col-sm-4 box-col-4">
        <div class="row">
            <div class="col-xl-12">
            <div class="card widget-1" >
                <div class="card-body">
                <div class="widget-content">
                    <div class="widget-round warning">
                    <div class="bg-round">
                        <svg class="svg-fill">
                        <use href="{{ asset('assets/svg/icon-sprite.svg#rate') }}"> </use>
                        </svg>
                        <svg class="half-circle svg-fill">
                        <use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
                        </svg>
                    </div>
                    </div>
                    <div>
                    <h4>{{number_format($jumlah_uas)}} / {{number_format($jumlah_mhs)}}</h4><span class="f-light">Jumlah Mahasiswa UAS</span>
                    </div>
                </div>
                <!-- <div class="font-warning f-w-500"><i class="icon-arrow-down icon-rotate me-1"></i><span>-20%</span></div> -->
                </div>
            </div>
        </div>
       </div>
    </div>
      <div class="col-xxl-6 col-sm-6 box-col-6">
        <div class="card profile-box">
          <div class="card-body bg-warning" style="border-radius:10px;">
            <div class="media">
              <div class="media-body">
                <div class="greeting-user">
                  <h4 class="f-w-600">Keuangan Mahasiswa</h4>
                  <p>Lihat detail keuangan mahasiswa</p><br />
                  <div class="whatsnew-btn"><a href="{{ url('admin/keuangan/tagihan')}}" class="btn btn-outline-white">Lihat Sekarang</a></div>
                </div>
              </div>
              <div>
                <div class="clockbox">
                  <svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
                    <g id="face">
                      <circle class="circle" cx="300" cy="300" r="253.9"></circle>
                      <path class="hour-marks" d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6"></path>
                      <circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
                    </g>
                    <g id="hour">
                      <path class="hour-hand" d="M300.5 298V142"></path>
                      <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                    </g>
                    <g id="minute">
                      <path class="minute-hand" d="M300.5 298V67"></path>
                      <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                    </g>
                    <g id="second">
                      <path class="second-hand" d="M300.5 350V55"></path>
                      <circle class="sizing-box" cx="300" cy="300" r="253.9">   </circle>
                    </g>
                  </svg>
                </div>
                <div class="badge f-10 p-0" id="txt"></div>
              </div>
            </div>
            <div class="cartoon"><img class="img-fluid" src="{{ asset('assets/images/dashboard/cartoon.svg') }}" alt="vector women with leptop"></div>
          </div>
        </div>
      </div>
      <div class="col-xxl-6 col-sm-6 box-col-6">
        <div class="card profile-box">
          <div class="card-body">
            <div class="media">
              <div class="media-body">
                <div class="greeting-user">
                  <h4 class="f-w-600">Tahun Ajaran Aktif</h4>
                  <p>{{$ta->keterangan}}</p><br />
                  <div class="whatsnew-btn"><a href="{{ url('admin/masterdata/ta')}}" class="btn btn-outline-white">Lihat Sekarang</a></div>
                </div>
              </div>  
            </div>  
          </div>
        </div>
    </div>
    <div class="row mb-3">
      <div class="col-12">
          <div class="card shadow-sm">
              <div class="card-header bg-white py-3">
                  <div class="row align-items-center">
                      <div class="col-md-6">
                          <h5 class="mb-0 text-primary fw-bold">📊 Monitoring Pembayaran Mahasiswa</h5>
                          <small class="text-muted">Data pemasukan per Prodi & Angkatan (2024-2025)</small>
                      </div>
                      
                      <div class="col-md-6 mt-3 mt-md-0">
                          <div class="row g-2">
                              <div class="col-md-6">
                                  <label class="form-label small fw-bold">Tahun Transaksi</label>
                                  <select id="filter-tahun" class="form-select select2-bs5">
                                      @foreach($tahunBayar as $thn)
                                          <option value="{{ $thn }}" {{ $thn == date('Y') ? 'selected' : '' }}>
                                              Tahun {{ $thn }}
                                          </option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label small fw-bold">Angkatan Mahasiswa</label>
                                  <select id="filter-angkatan" class="form-select select2-bs5">
                                      <option value="">Semua Angkatan (2024+)</option>
                                      @foreach($angkatan as $akt)
                                          <option value="{{ $akt }}">Angkatan {{ $akt }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
          <div class="card shadow-sm position-relative">
              <div id="loading" class="loading-overlay">
                  <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                  </div>
              </div>

              <div class="card-body">
                  <div id="chart-keuangan"></div>
              </div>
          </div>
      </div>
    </div>
    <div class="row">
    </div>

    <div class="row mt-4">
        <div class="col-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-dark fw-bold">📋 Rekapitulasi Total Pembayaran</h5>
                    <small class="text-muted">Rincian total per prodi dalam satu tahun sesuai filter di atas.</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="bg-light text-uppercase small fw-bold text-muted">
                                <tr>
                                    <th class="ps-4" width="5%">#</th>
                                    <th>Program Studi</th>
                                    <th class="text-end pe-4">Total Pendapatan (Rp)</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-rekap">
                                <tr><td colspan="3" class="text-center py-4">Memuat data...</td></tr>
                            </tbody>
                            <tfoot class="bg-light fw-bold border-top">
                                <tr>
                                    <td colspan="2" class="text-end pe-3">GRAND TOTAL</td>
                                    <td class="text-end pe-4 text-primary" id="grand-total">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
          <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-bold text-primary">💰 Monitoring Pembayaran Bulanan</h5>
                    <small class="text-muted">Khusus Prodi D3 & Apoteker (Tagihan Bulanan)</small>
                </div>
                <div class="card-body">
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <!-- <label class="form-label fw-bold small">Program Studi</label> -->
                            <select id="filter-prodi-bulanan" class="form-select select2">
                                <option value=""></option> @foreach($prodi_bulanan as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <!-- <label class="form-label fw-bold small">Angkatan</label> -->
                            <select id="filter-angkatan-bulanan" class="form-select select2">
                                @foreach($angkatan as $akt)
                                    <option value="{{ $akt }}">{{ $akt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <!-- <label class="form-label fw-bold small">Bulan Transaksi</label> -->
                            <select id="filter-bulan-bulanan" class="form-select select2">
                                @foreach(range(1,12) as $m)
                                    <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 10)) }} ({{ $m }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <!-- <label class="form-label fw-bold small">Tahun Transaksi</label> -->
                            <select id="filter-tahun-bulanan" class="form-select select2">
                                @foreach($tahunBayar as $thn)
                                    <option value="{{ $thn }}" {{ $thn == date('Y') ? 'selected' : '' }}>{{ $thn }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div id="chart-tagihan-bulanan" style="min-height: 350px;"></div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold mb-3">Ringkasan Data</h6>
                            
                            <div class="stat-card blue">
                                <div class="stat-value" id="val-sudah">0</div>
                                <div class="stat-label">Mahasiswa Sudah Bayar</div>
                            </div>

                            <div class="stat-card red">
                                <div class="stat-value" id="val-belum">0</div>
                                <div class="stat-label">Mahasiswa Belum Bayar</div>
                            </div>

                            <div class="mt-3 p-3 bg-dark rounded text-center">
                                <small>Total Mahasiswa Aktif</small>
                                <h4 class="fw-bold mb-0" id="val-total">0</h4>
                                <small class="text-light">Persentase Lunas: <span id="val-persen">0%</span></small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
    <script type="text/javascript">
        var session_layout = '{{ session()->get('layout') }}';
    </script>
@endsection

@section('script')
<script src="{{ asset('assets/js/clock.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>

{{-- <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script> --}}
<script src="{{ asset('assets/js/height-equal.js') }}"></script>
<script src="{{ asset('assets/js/animation/wow/wow.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    new WOW().init();
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function(){
    // Prepare data from Blade variables (serialize only needed fields)
    var prodi = @json($prodi->map(function($p){ return ['id' => $p->id, 'nama_prodi' => $p->nama_prodi]; }));
    var bayarMap = @json($total_bayar_statistik ?? []);
    var tagihanMap = @json($total_tagihan_statistik ?? []);

    var labels = prodi.map(function(p){ return p.nama_prodi; });
    var bayarData = prodi.map(function(p){ return Number(bayarMap[p.id] ?? 0); });
    // Gunakan data total tagihan sebagai dataset kedua (sebelumnya dihitung sebagai tunggakan)
    var tagihanData = prodi.map(function(p){ return Number(tagihanMap[p.id] ?? 0); });

    // Scale numbers to millions for shorter display
    var scaleFactor = 1000000; // 1 = 1 juta
    var bayarDataScaled = bayarData.map(function(v){ return +(v/scaleFactor); });
    var tunggakanDataScaled = tagihanData.map(function(v){ return +(v/scaleFactor); });

    
  })();

  $(document).ready(function() {
            
    // 1. Setup Select2 agar pas di Bootstrap
    $('.select2').select2({
          theme: 'bootstrap-5',
          width: '100%',
          placeholder: 'Pilih opsi...'
      });

    // 2. Formatter Rupiah (Untuk Tooltip & Axis)
    const formatRupiah = (number) => {
      return new Intl.NumberFormat('id-ID', { 
          style: 'currency', 
          currency: 'IDR',
          minimumFractionDigits: 0,
          maximumFractionDigits: 0 
      }).format(number);
    };

    const formatSingkat = (num) => {
        if (num >= 1000000000) return (num / 1000000000).toFixed(1) + ' M';
        if (num >= 1000000) return (num / 1000000).toFixed(1) + ' Jt';
        if (num >= 1000) return (num / 1000).toFixed(0) + ' Rb';
        return num;
    };

    // 3. Konfigurasi ApexCharts
    var options = {
        series: [],
        chart: {
            type: 'bar',
            height: 500,
            fontFamily: 'Segoe UI, sans-serif',
            toolbar: { show: true }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 5,
                dataLabels: { position: 'top' }
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: {
            categories: [],
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            title: { text: 'Nominal Pendapatan (Rp)' },
            labels: {
                formatter: function (val) { return formatSingkat(val); }
            }
        },
        fill: { opacity: 1 },
        tooltip: {
            y: {
                formatter: function (val) { return formatRupiah(val); }
            }
        },
        legend: { position: 'bottom', horizontalAlign: 'center' },
        colors: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545', '#fd7e14', '#ffc107', '#198754', '#20c997', '#0dcaf0'],
        noData: { text: 'Menunggu Data...' }
    };

    var chart = new ApexCharts(document.querySelector("#chart-keuangan"), options);
    chart.render();

    // 4. Fungsi Load Data AJAX
    
    function loadData() {
      var tahun = $('#filter-tahun').val();
      var angkatan = $('#filter-angkatan').val();

      $('#loading').css('display', 'flex'); 

      $.ajax({
          url: "{{ url('admin/keuangan/dashboard/get-data') }}",
          type: "GET",
          data: { 
              tahun_bayar: tahun,
              angkatan: angkatan
          },
          success: function(res) {
              // 1. Update Chart
              chart.updateOptions({
                  xaxis: { categories: res.categories },
                  series: res.series
              });

              // 2. Update Table (BARU)
              var html = '';
              var grandTotal = 0;

              if (res.table_data.length > 0) {
                  $.each(res.table_data, function(index, item) {
                      grandTotal += item.total; // Hitung Grand Total
                      
                      html += `<tr>
                          <td class="ps-4 text-center text-muted">${index + 1}</td>
                          <td class="fw-bold text-dark">${item.prodi}</td>
                          <td class="text-end pe-4 font-monospace">${formatRupiah(item.total)}</td>
                      </tr>`;
                  });
              } else {
                  html = `<tr><td colspan="3" class="text-center py-4 text-muted">Tidak ada data transaksi</td></tr>`;
              }

              // Render ke HTML
              $('#tbody-rekap').html(html);
              $('#grand-total').text(formatRupiah(grandTotal));

              $('#loading').hide();
          },
          error: function(e) {
              console.error(e);
              $('#loading').hide();
              $('#tbody-rekap').html('<tr><td colspan="3" class="text-center text-danger">Gagal memuat data</td></tr>');
          }
      });
  }

    // 5. Event Listener untuk Filter
    $('#filter-tahun, #filter-angkatan').on('change', function() {
        loadData();
    });

    // Load Awal
    loadData();


    //Bagian chart data bulanan
    var options_bulanan = {
            series: [], // Data Kosong Awal
            labels: ['Sudah Bayar', 'Belum Bayar'],
            chart: {
                type: 'donut', // Bisa diganti 'pie'
                height: 350,
                fontFamily: 'Segoe UI, sans-serif',
                animations: { enabled: true }
            },
            colors: ['#008FFB', '#FF4560'], // Biru (Lunas), Merah (Belum)
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: { show: true },
                            value: { show: true },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function (w) {
                                    // Menampilkan total mahasiswa di tengah donut
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: true },
            legend: { position: 'bottom' },
            noData: { text: 'Silakan pilih Prodi terlebih dahulu...' }
        };

        var chart_bulanan = new ApexCharts(document.querySelector("#chart-tagihan-bulanan"), options_bulanan);
        chart_bulanan.render();

        // 3. Fungsi Load Data
        function loadDataBulanan() {
            var prodi = $('#filter-prodi-bulanan').val();
            var angkatan = $('#filter-angkatan-bulanan').val();
            var bulan = $('#filter-bulan-bulanan').val();
            var tahun = $('#filter-tahun-bulanan').val();

            // Jangan load jika prodi belum dipilih (karena wajib)
            if (!prodi) return;

            // Tampilkan loading di chart
            chart_bulanan.updateOptions({ noData: { text: 'Memuat data...' }, series: [] });

            $.ajax({
                url: "{{ url('admin/keuangan/dashboard/get-data-bulanan') }}",
                type: "GET",
                data: { 
                    prodi: prodi, 
                    angkatan: angkatan, 
                    bulan: bulan, 
                    tahun: tahun 
                },
                success: function(res) {
                    // Update Chart
                    chart_bulanan.updateSeries(res.series);

                    // Update Angka Ringkasan di Samping
                    $('#val-sudah').text(res.series[0]); // Index 0 = Sudah
                    $('#val-belum').text(res.series[1]); // Index 1 = Belum
                    $('#val-total').text(res.details.total_mhs);
                    $('#val-persen').text(res.details.persen_bayar + '%');
                },
                error: function(e) {
                    console.error(e);
                    alert('Gagal memuat data.');
                }
            });
        }

        // 4. Trigger Filter
        $('#filter-prodi-bulanan, #filter-angkatan-bulanan, #filter-bulan-bulanan, #filter-tahun-bulanan').on('change', function() {
            loadDataBulanan();
        });

        // Cek jika sudah ada pilihan awal (misal saat refresh), trigger load
        if ($('#filter-prodi-bulanan').val()) {
            loadDataBulanan();
        }
});
</script>
@endsection
