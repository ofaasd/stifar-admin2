<hr>
<div class="form-group">
    <label for="capaian">Materi Capaian Pembelajaran</label>
    <textarea name="capaian" id="capaian" class="form-control"></textarea>
</div>
<div class="form-group mt-4">
    <button class="btn btn-info btn-sm"><i class="fa fa-save"></i> Simpan Capaian</button>
</div>
<br>
<table class="table" id="tablePertemuan" width="100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Type Absensi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($daftar_mhs as $row)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $row['nim'] }}</td>
            <td>{{ $row['nama'] }}</td>
            <td>
                <select name="editAbsensi{{ $row['id'] }}" id="editAbsensi{{ $row['id'] }}" class="form-control" onchange="updateAbsen({{ $row['id'] }}, {{ $row['id_jadwal'] }}, {{ $row['nim'] }})">
                    <option value="" selected disabled> -- Pilih Tipe Absensi --</option>
                    <option value="0" {{ $row['type'] == 0? 'selected=""':''}}>Tidak Hadir</option>
                    <option value="1" {{ $row['type'] == 1? 'selected=""':''}}>Hadir</option>
                    <option value="2" {{ $row['type'] == 2? 'selected=""':''}}>Sakit</option>
                    <option value="3" {{ $row['type'] == 3? 'selected=""':''}}>Izin</option>
                </select>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(function() {
        $("#tablePertemuan").DataTable({
            responsive: true
        })
    })
</script>