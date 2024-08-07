<table class="display" id="myTable">
    <thead>
        <tr>
            <th>No.</th>
            <th>NPP</th>
            <th>Nama Dosen</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($anggota as $row)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row['npp'] }}</td>
                <td>{{ $row['nama_lengkap'] }}, {{ $row['gelar_belakang'] }}</td>
                <td>{{ $row['status'] == 1 ? 'Koordinator':'Anggota' }}</td>
                <td><a href="javascript:void(0)" data-id='{{$row->id}}' class="btn btn-danger btn-sm hapusAnggota"><i class="fa fa-trash"></i> Hapus</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $("#myTable").DataTable({
        responsive: true
    })
</script>
