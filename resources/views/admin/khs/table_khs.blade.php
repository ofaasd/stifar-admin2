<table class="display" id="myTable">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
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
        <td><img class="img-60 b-r-8" alt="" src="{{ (!empty($row_mhs->foto_mhs))?asset('assets/images/mahasiswa/' . $row_mhs->foto_mhs):asset('assets/images/user/7.jpg') }}"></td>
        <td>{{ $row_mhs['nim'] }}</td>
        <td><a href="{{ URL::to('mahasiswa/' . $row_mhs['nim']) . "/edit/" }}">{{ $row_mhs['nama'] }}</a></td>
        <td>{{ $row_mhs['hp'] }}</td>
        <td>{{ $row_mhs['email'] }}</td>
        <td>{{ $nama[$row_mhs['id_program_studi']] }}</td>
        <td>{{ $row_mhs['status'] == 1? 'Aktif':'Tidak Aktif' }}</td>
        <td>
            <a href="{{ URL::to('/admin/akademik/khs/' . $row_mhs['nim']) }}" class="btn btn-warning btn-xs" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Lihat KHS Mahasiswa">
              <i class="fa fa-eye"></i>
            </a>
      </tr>
    @endforeach
    </table>
    <script>
        $("#myTable").DataTable({
            responsive: true
        })
    </script>
