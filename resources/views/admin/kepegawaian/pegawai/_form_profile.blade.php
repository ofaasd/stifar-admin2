<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2">
                    <label class="col-sm-12 col-form-label">Jenis Pegawai : </label>
                    <div class="col-sm-12">
                        <select class="form-control" name="jenis_pegawai" id="jenis_pegawai" required>
                        <option value="0">--- Pilih Jenis Pegawai --- </option>
                            @foreach($jenis_pegawai as $row)
                                <option value='{{$row->id}}' {{($curr_jenis_pegawai->id_jenis_pegawai == $row->id)?"selected" : "" }}>{{$row->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <label class="col-sm-12 col-form-label">Posisi Pegawai</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="posisi_pegawai" id="status_pegawai" required>
                            @foreach($list_jenis as $row)
                                <option value='{{$row->id}}' {{($curr_jenis_pegawai->id == $row->id)?"selected" : "" }}>{{$row->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Nomor Induk Yayasan(NIY): </label>
            <div class="col-sm-12">
                <input type="hidden" class="form-control" name="nip" id="initial_npp" value="">
                <div class="input-group">
                    <input type="text" name="npp" class="form-control" placeholder="cth : 060710112" id="nip" aria-describedby="inputGroupPrepend" required value="{{$pegawai->npp}}">
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Homebase: </label>
            <div id="homebase">
                <select name="homebase" class="form-control">
                    <option value="0">Tidak Ada</option>
                    @foreach($progdi as $pro)
                        <option value="{{$pro->id}}" {{($pegawai->homebase == $pro->id)?"selected":""}}>{{$pro->nama_prodi}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">NUPTK : </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="nuptk" class="form-control" placeholder="NUPTK" id="nuptk" aria-describedby="inputGroupPrepend" required value="{{$pegawai->nuptk}}">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-form-label">NIDN : </label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="nidn" value="{{$pegawai->nidn}}" >
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Nama Lengkap : </label>
            <div class="col-sm-12">
                <input type="text" id="nama_lengkap" class="form-control" name="nama_lengkap" value="{{$pegawai->nama_lengkap}}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2">
                    <label class="col-sm-12 col-form-label">Gelar Depan : </label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="gelar_depan" value="{{$pegawai->gelar_depan}}" placeholder="Ex: Dr., Ir.,">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <label class="col-sm-12 col-form-label">Gelar Belakang : </label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="gelar_belakang" value="{{$pegawai->gelar_belakang}}" placeholder="Ex: S.ked, S.Farm, M.Farm">
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Jenis Kelamin : </label>
            <div class="col-sm-12">
                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" >

                    @foreach($jenis_kelamin as $key=>$row)
                        <option value="{{$key}}" {{($key==$pegawai->jenis_kelamin)?"selected":""}} >{{$row}}</option>
                    @endforeach

                </select>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="form-label">Alamat Email</label>
            <div class="col-sm-12">
                <input class="form-control" name="email" placeholder="your-email@domain.com" value="{{$pegawai->email1}}">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-12 col-form-label">No. KTP : </label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="no_ktp" value="{{$pegawai->ktp}}" >
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-12 col-form-label">No. KK : </label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="no_kk" value="{{$pegawai->no_kk}}" >
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-10 col-form-label">No. BPJS Kesehatan : </label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="no_bpjs_kesehatan" value="{{$pegawai->no_bpjs_kesehatan}}" >
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-12 col-form-label">No. BPJS ketenagakerjaan : </label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="no_bpjs_ketenagakerjaan" value="{{$pegawai->no_bpjs_ketenagakerjaan}}" >
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-10 col-form-label">Alamat Tempat Tinggal : </label>
            <div class="col-sm-12">
                <textarea class="form-control" name="alamat" value="" id="alamat" required>{{$pegawai->alamat}}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-10 col-form-label">Nama Provinsi :</label>
            <div class="col-sm-12">
                <p>
                    <select name="provinsi" id="provinsi" class="form-control" required="">
                        <option selected="" disabled="">Pilih Provinsi</option>
                        @foreach($wilayah as $w)
                            <option value="{{ $w->id_wil }}" {{(!empty($pegawai->provinsi) && $pegawai->provinsi == $w->id_wil)?"selected":""}}>{{$w->nm_wil}}</option>
                        @endforeach
                    </select>
                </p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-10 col-form-label">Nama Kota/Kabupaten :</label>
            <div class="col-sm-12">
                <p>
                    <select name="kotakab" id="kotakab" class="form-control" required="">
                        <option selected="" disabled="">Pilih Kota/Kabupaten</option>
                        @foreach($kota as $w)
                            <option value="{{ $w->id_wil }}" {{(!empty($pegawai->kotakab) && $pegawai->kotakab == $w->id_wil)?"selected":""}}>{{$w->nm_wil}}</option>
                        @endforeach
                    </select>
                </p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-10 col-form-label">Nama Kecamatan :</label>
            <div class="col-sm-12">
                <p>
                    <select name="kecamatan" id="kecamatan" class="form-control" required="">
                        <option selected="" disabled="">Daftar Kecamatan</option>
                        @foreach($kecamatan as $w)
                            <option value="{{ $w->id_wil }}" {{(!empty($pegawai->kecamatan) && $pegawai->kecamatan == $w->id_wil)?"selected":""}}>{{$w->nm_wil}}</option>
                        @endforeach
                    </select>
                </p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-10 col-form-label">Nama Kelurahan :</label>
            <div class="col-sm-12">
                <p><input type="text" class="form-control" placeholder="Nama Kelurahan" name="kelurahan" required="" value="{{$pegawai->kelurahan}}"></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-10 col-form-label">Golongan Darah :</label>
            <div class="col-sm-12">
                <select name="golongan_darah" id="goldar" class="form-control">
                        <option selected="" disabled="">Pilih Golongan Darah</option>
                        @php
                            $golongan = array("A","B","AB","O");
                        @endphp
                        @foreach($golongan as $value)
                            <option value="{{$value}}" {{(!empty($pegawai->golongan_darah) && $value == $pegawai->golongan_darah)?"selected":""}}>{{$value}}</option>
                        @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-10 col-form-label">Status Kepegawaian : </label>
            <div class="col-sm-12">
                <select class="form-control" name="status">
                   @foreach($status as $row)
                        <option value={{$row}} {{($row == $pegawai->status_pegawai && !empty($pegawai->status_pegawai))?"selected":""}}>{{$row }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-form-label">Status Perkawinan : </label>
            <div class="col-sm-12">
                <select class="form-control" name="status_nikah" id="status_nikah">
                    @foreach($status_kawin as $key=>$row)
                        <option value="{{$key}}" {{(isset($pegawai->status_nikah) && $key == $pegawai->status_nikah )?"selected":""}}>{{$row}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="detail_status">
            <div class="menikah">
                <div class="form-group row">
                    <label class="col-sm-12 col-form-label">Nama Pasangan : </label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="nama_pasangan" value="{{$pegawai->nama_pasangan}}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-form-label">Tgl Lahir Pasangan : </label>
                    <div class="col-sm-12">
                        <input type="date" class="form-control" name="tgl_lahir_pasangan" value="{{date('Y-m-d', strtotime($pegawai->tgl_lahir_pasangan))}}" id="datepicker2">
                    </div>

                </div>
                <div class="form-group row">
                    <label class="col-sm-8 col-form-label">Pekerjaan Pasangan : </label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="pekerjaan_pasangan" value="{{$pegawai->pekerjaan_pasangan}}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label">Jumlah Anak : </label>
                    <div class="col-sm-12">
                        <input type="number" class="form-control" name="jumlah_anak" value="{{$pegawai->jumlah_anak}}" >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
