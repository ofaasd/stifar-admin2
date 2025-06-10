<div class="mb-3">
    <label for="nama" class="form-label">Nama Gelombang</label>
    <input type="text" name="nama" class="form-control" required>
</div>
<div class="mb-3">
    <label for="nama" class="form-label">Kuota</label>
    <input type="number" name="kuota" class="form-control" required>
</div>
<div class="mb-3">
    <label for="id_tahun_ajaran" class="form-label">Periode (Tahun Ajaran)</label>
    <select name="id_tahun_ajaran" class="form-select" required>
        @foreach($tahunAjaran as $ta)
            <option value="{{ $ta->id }}">{{ $ta->periode_formatted }}</option>
        @endforeach
    </select>
</div>
<div class="row mb-3">
    <div class="col">
        <label>Pendaftaran Mulai</label>
        <input type="date" name="tanggal_mulai_daftar" class="form-control" required>
    </div>
    <div class="col">
        <label>Pendaftaran Selesai</label>
        <input type="date" name="tanggal_selesai_daftar" class="form-control" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col">
        <label>Pelaksanaan Mulai</label>
        <input type="date" name="tanggal_mulai_pelaksanaan" class="form-control" required>
    </div>
    <div class="col">
        <label>Pelaksanaan Selesai</label>
        <input type="date" name="tanggal_selesai_pelaksanaan" class="form-control" required>
    </div>
</div>
