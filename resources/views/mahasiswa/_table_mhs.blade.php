<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
            Data Mahasiswa Aktif 
            @if($selectedProdi != 'all') 
                - Program Studi : {{ $prodi_selected->nama_prodi ?? 'All'}}
            @endif
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="10%">No</th>
                        <th class="text-center">Tahun Angkatan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Jumlah Mahasiswa</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataAngkatan as $index => $row)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold">{{ $row->angkatan }}</td>
                        <td class="text-center">
                            <span class="badge bg-success rounded-pill">Aktif</span>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold fs-5 text-primary">{{ $row->total }}</span> Mahasiswa
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-search"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada data mahasiswa aktif.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end pe-4">Total Keseluruhan</td>
                        <td class="text-center fs-5">{{ $dataAngkatan->sum('total') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<table class="display" id="myTable">
<thead>
    <tr>
        <th></th>
        <th></th>
        <th>NIM</th>
        <th>Nama</th>
        <th>HP</th>
        <th>Email</th>
        <th>Program Studi</th>
        <th>Status Mahasiswa</th>
        <th>Actions</th>
    </tr>
</thead>
@foreach($mhs as $row_mhs)
  <tr>
    <td>{{ $no++ }}</td>
    <td><img class="img-60 b-r-8" alt="" src="{{ (!empty($row_mhs['foto_mhs'])) ? asset('assets/images/mahasiswa/' . $row_mhs['foto_mhs']) : asset('assets/images/user/7.jpg') }}"></td>
    <td>{{ $row_mhs['nim'] }}</td>
    <td><a href="{{ URL::to('mahasiswa/' . $row_mhs['nim']) . "/edit/" }}">{{ $row_mhs['nama'] }}</a></td>
    <td>{{ $row_mhs['hp'] }}</td>
    <td>{{ $row_mhs['email'] }}</td>
    <td>{{ $nama[$row_mhs['id_program_studi']] }}</td>
    <td>{{ $row_mhs['status'] == 1? 'Aktif':'Tidak Aktif' }}</td>
    <td class="d-flex gap-1">
        <a href="{{ URL::to('/mahasiswa/detail/' . $row_mhs['nim']) }}" class="btn btn-warning btn-xs">
          <i class="fa fa-eye"></i>
        </a>
        <a href="{{ URL::to('/mahasiswa/' . $row_mhs['nim']) . "/edit/" }}" class="btn btn-info btn-xs">
          <i class="fa fa-edit"></i>

        </a>
    </td>
  </tr>
@endforeach
</table>
<script>
    $("#myTable").DataTable({
        responsive: true
    })
</script>
