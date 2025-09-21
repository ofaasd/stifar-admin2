
<form id="form_set_pertemuan" action="javascript:void(0)" method="POST">
<div id="tablePertemuan">
    <a href="{{URL::to('dosen/cetak_absensi/' . $jadwal->id)}}" class="btn btn-primary" target="_blank">Cetak Absensi</a>
    <input type="hidden" name="id_jadwal" value="{{$jadwal->id}}">
    <table class="table">
        <thead>
            <tr>
                <th>Pertemuan</th>
                <th>Tanggal Pertemuan</th>
                <th>Dosen Pengampu</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @for($i=1; $i<=14; $i++)
            <tr>
                <td>Pertemuan ke {{$i}}</td>
                <input type="hidden" name="no_pertemuan[]" value={{$i}}>
                <td><input type="date" name="tanggal_pertemuan[]" value="{{$list_pertemuan[$i]['tanggal_pertemuan']}}" class="form-control"></td>
                <td>
                    <select name="id_dosen[]" class="form-control">
                        <option value="0">--Pilih Dosen Pengampu</option>
                        @foreach($anggota as $row)
                            <option value="{{$row->id_dsn}}" {{($row->id_dsn == $list_pertemuan[$i]['id_dosen'])?"selected":""}}>{{$list_pegawai[$row->id_dsn]}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    @if($list_pertemuan[$i]['id_dosen'] != 0)
                        <a href="{{url('dosen/absensi/' . $jadwal->id .'/'. $list_pertemuan[$i]['id'] .'/input_new')}}" class="btn btn-primary btn-sm">Input Absensi</a>
                    @endif
                </td>
            </tr>
            @endfor
        </tbody>
    </table>

</div>
<hr>
<button type="submit" class="btn btn-primary btn-save"><i class="fa fa-save"></i> Tambah Pertemuan</button>
</form>
<script>
$("#form_set_pertemuan").submit(function(){
    $(".btn-save").attr('disabled',true);
    const data = $(this).serialize();
    const url = {!! json_encode(url('/')) !!};
    $.ajax({
        url: url+'/jadwal/tambah-pertemuan2',
        type: 'post',
        data: data,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        success: function(res){
            var result = res.kode
            var list = res.pertemuan
            if(result == 200){
                swal({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Setting Pertemuan Berhasil Dibuat',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
                $(".btn-save").attr('disabled',false);
                $(".pertemuan-location").html('');
                $('#modalPertemuan').modal('hide');
            }else{
                swal({
                    icon: 'error',
                    title: 'galat',
                    text: 'Data Gagal disimpan',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
                $(".btn-save").attr('disabled',false);
            }
        }
    })
});
</script>
