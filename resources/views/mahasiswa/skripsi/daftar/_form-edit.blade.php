<form action="{{ route('mhs.skripsi.daftar.update', $skripsi->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('mahasiswa.skripsi.daftar._form-fields', ['skripsi' => $skripsi])
    <button type="submit" class="btn btn-warning">Perbarui Pengajuan</button>
</form>
