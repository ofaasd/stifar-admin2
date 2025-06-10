<div class="mb-3">
    <label class="form-label">Judul Skripsi</label>
    <input type="text" class="form-control" value="{{ $skripsi->judul }}" readonly>
</div>

<div class="mb-3">
    <label class="form-label">Abstrak</label>
    <textarea class="form-control" rows="4" readonly>{{ $skripsi->abstrak }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Metodologi</label>
    <textarea class="form-control" rows="4" readonly>{{ $skripsi->metodologi }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Dosen Pembimbing</label>
    <ul class="list-group">
        @forelse ($skripsi->pembimbing as $p)
            <li class="list-group-item">{{ $p->dosen->nama ?? $p->nip }}</li>
        @empty
            <li class="list-group-item text-muted">Belum ada pembimbing ditetapkan</li>
        @endforelse
    </ul>
</div>

<div class="mb-3">
    <label class="form-label">Proposal Awal</label>
    <div class="input-group">
        <input type="text" class="form-control" value="{{ basename($skripsi->proposal) }}" readonly>
        <a href="{{ asset('storage/' . $skripsi->proposal) }}" class="btn btn-outline-secondary" target="_blank">Unduh</a>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Tanggal Disetujui</label>
    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($skripsi->tanggal_persetujuan)->translatedFormat('d F Y') }}" readonly>
</div>
