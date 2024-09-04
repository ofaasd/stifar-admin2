<hr>
<div class="form-group">
    <label for="capaian">Materi Capaian Pembelajaran</label>
    <textarea name="capaian" id="capaian" class="form-control">{{ $capaian }}</textarea>
</div>
<div class="form-group mt-4">
    <button class="btn btn-info btn-sm" onclick="simpanCapaian({{ $id_pertemuan }})"><i class="fa fa-save"></i> Simpan Capaian</button>
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
            <td>{{ $row->nim }}</td>
            <td>{{ $row->nama }}</td>
            <td>
                <select name="editAbsensi{{ $row->id_mhs }}" id="editAbsensi{{ $row->id_mhs }}" class="form-control" onchange="updateAbsen({{ $id_pertemuan }}, {{ $row->id_jadwal }}, {{ $row->id_mhs }})">
                    <option value="" selected disabled> -- Pilih Tipe Absensi --</option>
                    <option value="0" {{ $row->type == 0? 'selected=""':''}}>Tidak Hadir</option>
                    <option value="1" {{ $row->type == 1? 'selected=""':''}}>Hadir</option>
                    <option value="2" {{ $row->type == 2? 'selected=""':''}}>Sakit</option>
                    <option value="3" {{ $row->type == 3? 'selected=""':''}}>Izin</option>
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
    function updateAbsen(id_pertemuan, id_jadwal, id_mhs){
            var tipe = $('#editAbsensi'+id_mhs).val()
            $.ajax({
                url: baseUrl+'/dosen/simpan-absensi-satuan',
                type: 'post',
                data: {
                    id_jadwal: id_jadwal,
                    id_pertemuan: id_pertemuan,
                    id_mhs:id_mhs,
                    type:tipe
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    if(res.kode == 200){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Berhasil disimpan.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }else{
                        swal({
                            icon: 'warning',
                            title: 'Galat!',
                            text: 'Server Error.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
            })
        }
    function simpanCapaian(id_pertemuan){
        var capaian = $('#capaian').val()
        $.ajax({
                url: baseUrl+'/dosen/simpan-capaian',
                type: 'post',
                data: {
                    id_pertemuan: id_pertemuan,
                    capaian: capaian,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                success: function(res){
                    if(res.msg == 'ok'){
                        swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Simpan Sukses.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }else{
                        swal({
                            icon: 'error',
                            title: 'Gagal!',
                            text: '',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
        })
    }
</script>