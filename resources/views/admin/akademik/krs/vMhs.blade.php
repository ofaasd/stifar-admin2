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
                <th>KRS Diambil</th>
                <th>KRS Divalidasi</th>
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
                <td><span class="badge {{ ($jumlah_sks[$row_mhs->id] == 0)?"badge-danger":"badge-primary" }} ">{{ $jumlah_sks[$row_mhs->id] }}</span></td>
                <td>
                    @if($jumlah_sks_validasi[$row_mhs->id] == 0)
                        <span class="badge badge-danger">{{ $jumlah_sks_validasi[$row_mhs->id] }}</span>
                    @elseif($jumlah_sks_validasi[$row_mhs->id] < $jumlah_sks[$row_mhs->id])
                        <span class="badge badge-warning">{{ $jumlah_sks_validasi[$row_mhs->id] }}</span>
                    @else
                        <span class="badge badge-success">{{ $jumlah_sks_validasi[$row_mhs->id] }}</span>
                    @endif

                </td>
                <td>
                    <a href="{{ $ta['krs'] == 1 ? url('admin/masterdata/krs/admin/input/'.$row_mhs['id'].'/'.$ta['id']):'#' }}" class="btn btn-success btn-xs">
                        <i class="fa fa-edit"></i>
                        Input KRS
                    </a>
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
