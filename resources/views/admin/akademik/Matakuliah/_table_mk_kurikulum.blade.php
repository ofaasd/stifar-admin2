<table class="display" id="tableMK">
    <thead>
        <tr>
            <th></th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Nama Inggris</th>
            <th>Jenis Mata Kuliah</th>
            {{-- <th>T/P</th> --}}
            <th>Kredit</th>
            <th>Smt</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list_mk as $mk)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $mk['kode_matkul'] }}</td>
                <td>{{ $mk['nama_matkul'] }}</td>
                <td>{{ $mk['nama_matkul_eng'] }}</td>
                <td>{{ $mk['status_mk'] }}</td>
                {{-- <td>{{ $mk['tp'] }}</td> --}}

                <td>
                    @if(empty($mk['sks_praktek']) && !empty($mk['sks_teori']))
                        {{ $mk['sks_teori'] }} T
                    @elseif(empty($mk['sks_teori']) && !empty($mk['sks_praktek']))
                        {{ $mk['sks_praktek'] }} P
                    @elseif(!empty($mk['sks_teori']) && !empty($mk['sks_praktek']))
                        {{ $mk['sks_teori'] }} T / {{ $mk['sks_praktek'] }} P
                    @else
                        T / P
                    @endif
                </td>
                <td>{{ $mk['semester'] }}</td>
                <td>{{ $mk['status'] }}</td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm btn-icon edit-record" data-bs-toggle="modal" data-original-title="editmk" data-bs-target="#edit_{{ $mk['id'] }}">
                            <i class="fa fa-edit"></i> Edit
                    </a>
                    <div class="modal fade" id="edit_{{ $mk['id'] }}" tabindex="-1" aria-labelledby="edit_{{ $mk['id'] }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="modal-toggle-wrapper">
                                        <h5 style="text-align: center">Edit</h5>
                                        @csrf
                                        <div class="form-group mt-2">
                                            <label for="kode_matkul">Kode Matakuliah :</label>
                                            <input type="text" class="form-control" name="kode_matkul" id="kode_matkul_{{ $mk['id'] }}" value="{{ $mk['kode_matkul'] }}" required="" readonly=""/>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="nama_matkul">Nama Matakuliah :</label>
                                            <input type="text" class="form-control" name="nama_matkul" id="nama_matkul_{{ $mk['id'] }}" value="{{ $mk['nama_matkul'] }}" required=""/>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="nama_inggris">Nama Inggris :</label>
                                            <input type="text" class="form-control" name="nama_inggris" id="nama_inggris_{{ $mk['id'] }}" value="{{ $mk['nama_matkul_eng'] }}" required=""/>
                                        </div>
                                        {{-- <div class="form-group mt-2">
                                            <label for="kelompok">Teori/Praktek :</label>
                                            <select name="tp" id="tp_{{ $mk['id'] }}" class="form-control" required="">
                                                <option value="T" {{ $mk['tp'] == 'T' ? 'selected=""':'' }}>T</option>
                                                <option value="P" {{ $mk['tp'] == 'P' ? 'selected=""':'' }}>P</option>
                                                <option value="TP" {{ $mk['tp'] == 'TP' ? 'selected=""':'' }}>TP</option>
                                            </select>
                                        </div> --}}
                                        <div class="form-group mt-2">
                                            <label for="nama_inggris">Semester :</label>
                                            <input type="number" class="form-control" name="semester" id="semester_{{ $mk['id'] }}" value="{{ $mk['semester'] }}" required=""/>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="nama_inggris">Jumlah SKS (Kredit) :</label>
                                            <div class="row">
                                                <div class="col-sm-4">Teori
                                                    <input type="number" name="sks_teori" id="sks_teori_{{ $mk['id'] }}" value="{{ $mk['sks_teori'] }}" required=""/>
                                                </div>,
                                                <div class="col-sm-4" style="margin-left: 15px;">Praktek
                                                    <input type="number" name="sks_praktek" id="sks_praktek_{{ $mk['id'] }}" value="{{ $mk['sks_praktek'] }}" required=""/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="kelompok">Status Mata Kuliah :</label>
                                            <select name="status_mk" id="status_mk_{{ $mk['id'] }}" class="form-control" required="">
                                                <option value="Wajib" {{ $mk['status_mk'] == 'Wajib' ? 'selected=""':'' }}>Wajib</option>
                                                <option value="Pilihan" {{ $mk['status_mk'] == 'Pilihan' ? 'selected=""':'' }}>Pilihan</option>
                                                <option value="Lainnya" {{ $mk['status_mk'] == 'Lainnya' ? 'selected=""':'' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="status">Status Penawaran :</label>
                                            <select class="form-control" name="status" id="status_{{ $mk['id'] }}" required="">

                                                <option value="Aktif" {{ $mk['status'] == 'Aktif' ? 'selected=""':'' }}>Aktif</option>
                                                <option value="Tidak Aktif" {{ $mk['status'] == 'Tidak Aktif' ? 'selected=""':'' }}>Tidak Aktif</option>
                                            </select>
                                        </div>
                                        <div class="mt-2"></div>
                                        <button type="button" onclick="updateData({{ $mk['id'] }})" id="btnUpdate{{ $mk['id'] }}" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                                        <button class="btn bg-danger d-flex align-items-center gap-2 text-light ms-auto btn-sm" type="button" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="javasctip:void(0)" data-id1="{{$mk['id']}}" data-id2='{{$id_kur}}' class="btn btn-danger btn-sm btn-icon delete-record">
                        <i class="fa fa-trash"></i> Delete
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
     $(function() {
        $("#tableMK").DataTable({
            responsive: true
        });
        $(".js-example-basic-single").select2();
        $(".delete-record").click(function(){
            const delete_url = "{{ url('admin/masterdata/matakuliah-kurikulum/delete')}}/"+$(this).data('id1')+'-'+$(this).data('id2');
            $.ajax({
                url: delete_url,
                type: 'get',

                success: function(res){
                    refresh_table();
                }
            })
        });
    })
</script>
