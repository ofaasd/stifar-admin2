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
        <!-- Button to open modal -->
        <button type="button" class="btn btn-success btn-xs" title="Cetak Ijazah" data-bs-toggle="modal" data-bs-target="#ijazahModal{{ $row_mhs['nim'] }}">
          <i class="fa fa-print"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="ijazahModal{{ $row_mhs['nim'] }}" tabindex="-1" aria-labelledby="ijazahModalLabel{{ $row_mhs['nim'] }}" aria-hidden="true">
          <div class="modal-dialog modal-lg">
          <form method="POST" action="{{ url('/admin/alumni/cetak-ijazah') }}" target="_blank">
            @csrf
            <input type="hidden" name="nimEnkripsi" value="{{ $row_mhs['nimEnkripsi'] }}">
            <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="ijazahModalLabel{{ $row_mhs['nim'] }}">Cetak Ijazah</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row mb-3">
                <div class="col">
                  <label for="seri_ijazah{{ $row_mhs['nim'] }}" class="form-label">Nomor Seri Ijazah</label>
                  <input type="text" class="form-control" id="seri_ijazah{{ $row_mhs['nim'] }}" name="seri_ijazah" value="063032481012025100001" required>
                </div>
                <div class="col">
                  <label for="lulus_pada{{ $row_mhs['nim'] }}" class="form-label">Lulus Pada</label>
                  <input type="date" class="form-control" id="lulus_pada{{ $row_mhs['nim'] }}" name="lulus_pada" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="akreditasi1{{ $row_mhs['nim'] }}" class="form-label">Akreditasi BAN-PT</label>
                <input type="text" class="form-control" id="akreditasi1{{ $row_mhs['nim'] }}" name="akreditasi1" value=" TERAKREDITASI B SK BAN-PT No. 500/SK/BAN-PT/Ak.Ppj/PT/VIII/2022" required>
              </div>
              <div class="mb-3">
                <label for="akreditasi2{{ $row_mhs['nim'] }}" class="form-label">Akreditasi LAM-PTKes</label>
                <input type="text" class="form-control" id="akreditasi2{{ $row_mhs['nim'] }}" name="akreditasi2" value=" Terakreditasi Baik Sekali SK LAM-PTKes 0815/LAM-PTKes/Akr/Sar/IX/2022" required>
              </div>
              <div class="mb-3">
                <label for="akreditasi2Eng{{ $row_mhs['nim'] }}" class="form-label">Akreditasi LAM-PTKes Inggris</label>
                <input type="text" class="form-control" id="akreditasi2Eng{{ $row_mhs['nim'] }}" name="akreditasi2Eng" value=" accredited with grade 'very good' SK LAM-PTKes 0815/LAM-PTKes/Akr/Sar/IX/2022" required>
              </div>
              <div class="mb-3">
                <label for="nama_ketua_prodi{{ $row_mhs['nim'] }}" class="form-label">Nama Ketua Program Studi</label>
                <input type="text" class="form-control" id="nama_ketua_prodi{{ $row_mhs['nim'] }}" name="nama_ketua_prodi" value=" Dr. apt. Dwi Hadi Setya Palupi, M.Si." required>
              </div>
              <div class="mb-3">
                <label for="niy_ketua_prodi{{ $row_mhs['nim'] }}" class="form-label">NIY Ketua Program Studi</label>
                <input type="text" class="form-control" id="niy_ketua_prodi{{ $row_mhs['nim'] }}" name="niy_ketua_prodi" value="YP 040204002" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-success btn-sm">Cetak</button>
            </div>
            </div>
          </form>
          </div>
        </div>
    </td>
  </tr>
@endforeach
</table>
<script>
    $("#myTable").DataTable({
        responsive: true
    })
</script>
