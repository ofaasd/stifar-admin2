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
                <td>
                    <a href="javascript:void(0)" data-id='{{$row->id}}' data-bs-toggle="modal" data-original-title="test" data-bs-target="#editMK{{ $row->id }}" class="btn btn-success btn-sm"><i class="fa fa-trash"></i> Edit</a> <a href="javascript:void(0)" data-id='{{$row->id}}' class="btn btn-danger btn-sm hapusAnggota"><i class="fa fa-trash"></i> Hapus</a>
                    <div class="modal fade" id="editMK{{ $row->id }}" tabindex="-1" aria-labelledby="editMK{{ $row->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="modal-toggle-wrapper">
                                        <h5 style="text-align: center">Edit Koordinator/Anggota MK</h5>
                                        @csrf
                                            <input type="hidden" name="id" id="id_{{ $row->id }}" value="{{$row->id}}">
                                            <hr>
                                            <div class="mb-3">
                                                <label for="kode_jadwal" class="form-label">Nama Anggota</label>
                                                <select name="nama_anggota" id="nama_anggota_{{$row->id}}" class="js-example-basic-single">
                                                    @foreach($pegawai as $dsn)
                                                        <option value="{{ $dsn['id'] }}" {{($dsn['id'] == $row->id_pegawai_bio)?"selected":""}}>{{ $dsn['nama_lengkap'] }}, {{ $dsn['gelar_belakang'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kode_jadwal" class="form-label">Status</label>
                                                <select name="status" id="status_{{$row->id}}" class="form-control" required>
                                                    <option value="" selected disabled>Pilih Status</option>
                                                    <option value="1" {{($row->status == 1)?"selected":""}}>Koordinator</option>
                                                    <option value="2" {{($row->status == 2)?"selected":""}}>Anggota</option>
                                                </select>
                                            </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button type="button" onclick="updateAnggotaJadwal('{{ $row->id }}')" class="btn btn-primary btn-sm btn-update"><i class="fa fa-save"></i>Update Anggota Matakuliah</button>
                                            </div>
                                            <div class="col-md-6">
                                                <button class="btn bg-danger d-flex align-items-center gap-2 text-light ms-auto" type="button" data-bs-dismiss="modal">Tutup<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $("#myTable").DataTable({
        responsive: true
    })
    $(".js-example-basic-single").select2();
</script>
