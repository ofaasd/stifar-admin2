<form action="{{ route('mhs.skripsi.daftar.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('mahasiswa.skripsi.daftar._form-fields')
    <button type="submit" class="btn btn-primary">Ajukan Skripsi</button>
</form>
