
<table class="display" id="peringkat-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama.</th>
            <th>No. Pendaftaran</th>
            <th>Nilai Rata-rata</th>
            <th>Nilai Tambahan</th>
            <th>Nilai Akhir</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
        <td>{{$row['fake_id']}}</td>
        <td>{{$row['nama']}}</td>
        <td>{{$row['nopen']}}</td>
        <td>{{$row['nrata']}}</td>
        <td>{{$row['ntambahan']}}</td>
        <td>{{($row['nakhir']+$row['ntambahan'])}}</td>
        <td><a href="#" title="add nilai tambahan" id="add_nilai" class="btn btn-primary btn-xs" data-id="{{$row['id']}}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#tambahModal"><i class="fa fa-plus"></i></a> <a href="{{URL::to('admin/admisi/nilai_tambahan/' . $row['id'])}}" title="Lihat Nilai Tambahan" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
    <script>
        //const baseUrl = {!! json_encode(url('/')) !!};
        $(document).ready(function(){
            $("#peringkat-table").DataTable({
                order : [[5, 'desc']]
            });
            $("#add_nilai").click(function(){
                //alert($(this).data('id'));
                $("#id_peserta").val($(this).data('id'));
            });
            $("#formAdd").submit(function(e){
                e.preventDefault();
                $.ajax({
                    data: $(this).serialize(),
                    url: ''.concat(baseUrl).concat('/admin/admisi/peringkat/add_nilai_tambahan'),
                    type: 'POST',
                    success: function success(status) {

                        $("#tambahModal").modal('hide');

                        // sweetalert
                        swal({
                        icon: 'success',
                        title: 'Successfully '.concat(status, '!'),
                        text: ''.concat(status, 'Created Successfully.'),
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                        });
                        refresh();
                    },
                    error: function error(err) {
                        $("#tambahModal").modal('hide');
                        swal({
                        title: 'Duplicate Entry!',
                        text: title + ' Not Saved !',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                        });
                    }
                });
            });
        });
    </script>
