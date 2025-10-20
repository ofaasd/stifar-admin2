<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
{{-- <div class="mt-2">
    <label for="status_krs">Status KRS : </label>
    <select onchange="gantiStatus({{ $ta['id'] }})" name="status_krs" id="status_krs" class="form-control">
        <option value="" selected disabled>Status KRS</option>
        <option value="1" {{ $ta['krs'] == 1 ? 'selected=""':'' }}>Aktif</option>
        <option value="0" {{ $ta['krs'] == 0 ? 'selected=""':'' }}>Tidak Aktif</option>
    </select>
</div> --}}
<div class="table-responsive mt-2">
    <table class="display" id="tableMK">
        <thead>
            <tr>
                <th></th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>HP</th>
                <th>Dosen Wali</th>
                <th>Status Mahasiswa</th>
                <th>Ijinkan UTS</th>
                <th>Ijinkan UAS</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($mhs as $row_mhs)
            <tr>
                <td>{{ $no++ }}</td>
                <td><a href="{{ $ta['krs'] == 1 ? url('admin/masterdata/krs/admin/input/'.$row_mhs['id'].'/'.$ta['id']):'#' }}">{{ $row_mhs['nim'] }}</a></td>
                <td>{{ $row_mhs['nama'] }}</td>
                <td>{{ $row_mhs['hp'] }}</td>
                <td><a href="{{url('dosen/perwalian/' . $row_mhs['id_dsn_wali'])}}">{{ $row_mhs['nama_dosen'] }}</a></td>
                <td>{{ $row_mhs['status'] == 1? 'Aktif':'Tidak Aktif' }}</td>
                <td><span class="badge {{ ($ijinkan_uts[$row_mhs->id] == 0)?"badge-danger":"badge-success" }} ">{!! ($ijinkan_uts[$row_mhs->id] == 0)?'<i class="fa fa-times"></i>':'<i class="fa fa-check"></i>' !!}</span></td>
                <td><span class="badge {{ ($ijinkan_uas[$row_mhs->id] == 0)?"badge-danger":"badge-success" }} ">{!! ($ijinkan_uas[$row_mhs->id] == 0)?'<i class="fa fa-times"></i>':'<i class="fa fa-check"></i>' !!}</span></td>
                <td>
                    <div class="btn-group">
                        <a href="{{ url('admin/akademik/ujian/'.$row_mhs['nim']) }}" class="btn btn-primary btn-xs" title="Lihat Kartu Ujian">
                            <i class="fa fa-eye"></i> View
                        </a>
                        <a href="{{ url('admin/akademik/ujian/cetak_uts/'.$row_mhs['nim']) }}" class="btn btn-info btn-xs" title="Lihat Kartu Ujian">
                            <i class="fa fa-download"></i> UTS
                        </a>
                        <a href="{{ url('admin/akademik/ujian/cetak_uas/'.$row_mhs['nim']) }}" class="btn btn-warning btn-xs" title="Lihat Kartu Ujian">
                            <i class="fa fa-download"></i> UAS
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script>
    var baseUrl2 = {!! json_encode(url('/')) !!};
    $(function() {
            $("#tableMK").DataTable({
                responsive: true
            })
        })
    function gantiStatus(id){
        $.ajax({
            url: baseUrl2+'/admin/masterdata/krs/ganti-status-krs',
            type: 'post',
            dataType: 'json',
            data: {
                krs: $('#status_krs').val(),
                id: id,
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(res){
                if (res.kode == 200) {
                    swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Status KRS telah dibuka',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        window.location.href = baseUrl2+'/admin/masterdata/krs';
                }else{
                    swal({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Status KRS gagal dibuka (Server Error)',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                }
            }
        })
    }
    function simpanData(){
        $.ajax({
            url: baseUrl2+'/admin/masterdata/matakuliah-kurikulum/save',
            type: 'post',
            dataType: 'json',
            data: {
                id_kur: $('#id_kur').val(),
                id_mk: $('#mk').val(),
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(res){
                if (res.kode == 200) {
                    swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Matakuliah Berhasil ditambahkan! silahkan refresh halaman ini.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                }else{
                    swal({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Matakuliah gagal ditambahkan atau sudah terdaftar!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                }
            }
        })
    }
    function updateData(id){
        const baseUrl2 = {!! json_encode(url('/')) !!};
        $.ajax({
            url: baseUrl2+'/admin/masterdata/matakuliah-kurikulum/update',
            type: 'post',
            dataType: 'json',
            data: {
                kode_matkul: $('#kode_matkul_'+id).val(),
                nama_matkul: $('#nama_matkul_'+id).val(),
                nama_inggris: $('#nama_inggris_'+id).val(),
                tp: $('#tp_'+id).val(),
                semester: $('#semester_'+id).val(),
                sks_teori: $('#sks_teori_'+id).val(),
                sks_praktek: $('#sks_praktek_'+id).val(),
                status_mk: $('#status_mk_'+id).val(),
                status: $('#status_'+id).val(),
                id: id,
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(res){
                if (res.kode == 200) {
                    swal({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Matakuliah Berhasil diubah! silahkan refresh halaman ini.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                }else{
                    swal({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Matakuliah gagal diubah!',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                }
            }
        })
    }
</script>
