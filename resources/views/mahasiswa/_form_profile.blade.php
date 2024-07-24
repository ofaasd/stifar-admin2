<div class="row">
    <div class="col-md-6">
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Program Studi : </label>
            <div class="col-sm-12">
                <select class="form-control" name="jenis_pegawai" id="jenis_pegawai" >
                <option value="0">--- Pilih Program Studi --- </option>
                    @foreach($prodi as $key=>$value)
                        <option value='{{$key}}' {{($mahasiswa->id_program_studi == $key)?"selected" : "" }}>{{$value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Nomor Induk Mahasiswa(NIM): </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="nim" class="form-control" placeholder="cth : 060710112" id="nip" aria-describedby="inputGroupPrepend"  value="{{$mahasiswa->nim}}">
                </div>
            </div>
        </div>
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
            <label class="col-sm-12 col-form-label">No. HP Ortu: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="hp_ortu" class="form-control" id="hp_ortu"  value="{{$mahasiswa->hp_ortu}}">
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
        <div class="mb-2">
            <label class="col-sm-12 col-form-label">Angkatan: </label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="angkatan" class="form-control" id="angkatan"  value="{{$mahasiswa->angkatan}}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">

    </div>
</div>
