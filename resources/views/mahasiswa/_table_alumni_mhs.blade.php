<table class="display" id="myTable">
<thead>
    <tr>
        <th></th>
        <th></th>
        <th>NIM</th>
        <th>Nama</th>
        @if (isset($isPrintIjazah) && !$isPrintIjazah)
          <th>HP</th>
          <th>Email</th>
        @else
          <th>Tercetak</th>
        @endif
        <th>Program Studi</th>
        <th>Status Mahasiswa</th>
        <th>Actions</th>
    </tr>
</thead>
@if(count($alumni) > 0)
  @foreach($alumni as $row)
    <tr>
      <td>{{ $no++ }}</td>
      <td><img class="img-60 b-r-8" alt="" src="{{ (!empty($row['foto_mhs'])) ? asset('assets/images/mahasiswa/' . $row['foto_mhs']) : asset('assets/images/user/7.jpg') }}"></td>
      <td>{{ $row['nim'] }}</td>
      {{-- <td><a href="{{ URL::to('mahasiswa/' . $row['nim']) . "/edit/" }}">{{ $row['nama'] }}</a></td> --}}
      <td><a href="#">{{ $row['nama'] }}</a></td>
      @if (isset($isPrintIjazah) && !$isPrintIjazah)
        <td>{{ $row['hp'] }}</td>
        <td>{{ $row['email'] }}</td>
      @else
        <td>{{ $row['tercetak']+1 }}x</td>
      @endif
      <td>{{ $nama[$row['id_program_studi']] }}</td>
      <td>{{ "Alumni" }}</td>
      <td class="d-flex gap-1">
          <!-- Button to open modal -->
          <button type="button" class="btn btn-success btn-xs cetak-ijazah-record" title="Cetak Ijazah" data-bs-toggle="modal" data-bs-target="#cetakIjazahModal" data-nama="{{ $row['nama'] }}" data-nim="{{ $row['nimEnkripsi'] }}">
            <i class="fa fa-print"></i>
          </button>
      </td>
    </tr>
  @endforeach
@else
  <tr>
    <td colspan="9" class="text-center">Data alumni tidak ditemukan.</td>
  </tr>
@endif
</table>
<script>
  $(function () {
      $("#myTable").DataTable({
          responsive: true
      });
  });
</script>
@include('admin.akademik.transkrip-ijazah.modal.modal-ijazah')