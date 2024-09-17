<div class="row">
    <div class="col-md-6">
        <input type="hidden" name="id_program_studi" value="{{$mahasiswa->id_program_studi}}">
        <input type="hidden" name="nim" value="{{$mahasiswa->nim}}">
        <input type="hidden" name="id_dsn_wali" value="{{$mahasiswa->id_dsn_wali}}">
        <input type="hidden" name="angkatan" value="{{$mahasiswa->angkatan}}">

        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Nama: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="nama" class="form-control" placeholder="Nama" id="nama"  value="{{$mahasiswa->nama}}">
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">No. KTP: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="no_ktp" class="form-control" id="no_ktp"  value="{{$mahasiswa->no_ktp}}">
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Jenis Kelamin: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <div class="m-t-15 m-checkbox-inline custom-radio-ml">
                        <div class="form-check form-check-inline radio radio-primary">
                            <input class="form-check-input" id="jenis_kelamin1" type="radio" name="jenis_kelamin" value="1" {{($mahasiswa->jk == 1)?"checked":""}}>
                            <label class="form-check-label mb-0" for="jenis_kelamin1">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline radio radio-primary">
                            <input class="form-check-input" id="jenis_kelamin2" type="radio" name="jenis_kelamin" value="2" {{($mahasiswa->jk == 2)?"checked":""}}>
                            <label class="form-check-label mb-0" for="jenis_kelamin2">Perempuan</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Agama : </label>
            <div class="col-sm-12">
                <select class="form-control" name="agama" id="agama" >
                <option value="0">--- Pilih Agama --- </option>
                    @foreach($agama as $key=>$value)
                        <option value='{{$key}}' {{($mahasiswa->agama == $key)?"selected" : "" }}>{{$value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Tempat Lahir: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="tempat_lahir" class="form-control" id="tempat_lahir"  value="{{$mahasiswa->tempat_lahir}}">
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Tanggal Lahir: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="date" name="tgl_lahir" class="form-control" id="tgl_lahir"  value="{{$mahasiswa->tgl_lahir}}">
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Nama Ibu: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="nama_ibu" class="form-control" id="nama_ibu"  value="{{$mahasiswa->nama_ibu}}">
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Nama Ayah: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="nama_ayah" class="form-control" id="nama_ayah"  value="{{$mahasiswa->nama_ayah}}">
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">No. HP: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="hp" class="form-control" id="hp"  value="{{$mahasiswa->hp}}">
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">No. HP Ortu: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="hp_ortu" class="form-control" id="hp_ortu"  value="{{$mahasiswa->hp_ortu}}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Alamat: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <textarea name="alamat" class="form-control" placeholder="Alamat" id="alamat">{{$mahasiswa->alamat}}</textarea>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Alamat Semarang: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <textarea name="alamat_semarang" class="form-control" placeholder="Alamat Kos / Alamat Semarang" id="alamat_semarang">{{$mahasiswa->alamat_semarang}}</textarea>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-10 col-form-label">Nama Provinsi :</label>
            <div class="col-sm-12">
                <p>
                    <select name="provinsi" id="provinsi" class="form-control" >
                        <option selected="" disabled="">Pilih Provinsi</option>
                        @foreach($wilayah as $w)
                            <option value="{{ $w->id_wil }}" {{(!empty($mahasiswa->provinsi) && $mahasiswa->provinsi == $w->id_wil)?"selected":""}}>{{$w->nm_wil}}</option>
                        @endforeach
                    </select>
                </p>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-10 col-form-label">Nama Kota/Kabupaten :</label>
            <div class="col-sm-12">
                <p>
                    <select name="kotakab" id="kotakab" class="form-control" >
                        <option selected="" disabled="">Pilih Kota/Kabupaten</option>
                        @foreach($kota as $w)
                            <option value="{{ $w->id_wil }}" {{(!empty($mahasiswa->kokab) && $mahasiswa->kokab == $w->id_wil)?"selected":""}}>{{$w->nm_wil}}</option>
                        @endforeach
                    </select>
                </p>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-10 col-form-label">Nama Kecamatan :</label>
            <div class="col-sm-12">
                <p>
                    <select name="kecamatan" id="kecamatan" class="form-control" >
                        <option selected="" disabled="">Daftar Kecamatan</option>
                        @foreach($kecamatan as $w)
                            <option value="{{ $w->id_wil }}" {{(!empty($mahasiswa->kecamatan) && $mahasiswa->kecamatan == $w->id_wil)?"selected":""}}>{{$w->nm_wil}}</option>
                        @endforeach
                    </select>
                </p>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-10 col-form-label">Nama Kelurahan :</label>
            <div class="col-sm-12">
                <p><input type="text" class="form-control" placeholder="Nama Kelurahan" name="kelurahan"  value="{{$mahasiswa->kelurahan}}"></p>
            </div>
        </div>
        <div class="mb-2">
            <div class="row">
                <div class="col-md-6">
                    <label class="col-sm-10 col-form-label">RT :</label>
                    <div class="col-sm-12">
                        <p><input type="text" class="form-control" placeholder="" name="rt"  value="{{$mahasiswa->rt}}"></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="col-sm-10 col-form-label">RW :</label>
                    <div class="col-sm-12">
                        <p><input type="text" class="form-control" placeholder="" name="rw"  value="{{$mahasiswa->rw}}"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Email: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="email" name="email" class="form-control" id="email"  value="{{$mahasiswa->email}}">
                </div>
            </div>
        </div>
        {{-- <div class="mb-2">
            <label class="col-sm-10 col-form-label">Dosen Wali :</label>
            <div class="col-sm-12">
                <p>
                    <select name="id_dsn_wali" id="id_dsn_wali" class="form-control" >
                        <option selected="" disabled="">Pilih Dosen</option>
                        @foreach($dosen as $row)
                            <option value="{{ $row->id }}" {{(!empty($mahasiswa->id_dsn_wali) && $mahasiswa->id_dsn_wali == $row->id)?"selected":""}}>{{$row->nama_lengkap}}</option>
                        @endforeach
                    </select>
                </p>
            </div>
        </div> --}}

    </div>
</div>
