@php
    $judul = old('judul', $skripsi->judul ?? '');
    $abstrak = old('abstrak', $skripsi->abstrak ?? '');
    $metodologi = old('metodologi', $skripsi->metodologi ?? '');
@endphp

<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Judul Skripsi</label>
            <input type="text" name="judul" class="form-control" required value="{{ $judul }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Abstrak</label>
            <textarea name="abstrak" class="form-control" rows="4" required>{{ $abstrak }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Metodologi</label>
            <textarea name="metodologi" class="form-control" rows="4" required>{{ $metodologi }}</textarea>
        </div>

        @if (isset($skripsi) && $skripsi->proposal)
            <div class="mb-3">
                <label class="form-label">Proposal Saat Ini</label>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" value="{{ basename($skripsi->proposal) }}" readonly>
                    <a href="{{ asset('storage/' . $skripsi->proposal) }}" class="btn btn-outline-secondary" target="_blank">Unduh</a>
                </div>
                <small>Kosongkan jika tidak ingin mengganti.</small>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Unggah Proposal (PDF)</label>
            <input type="file" name="proposal" class="form-control" accept=".pdf" {{ isset($skripsi) ? '' : 'required' }}>
        </div>

        @php
            $pembimbing1 = old('pembimbing_1', $skripsi->pembimbing[0]->nip ?? '');
            $pembimbing2 = old('pembimbing_2', $skripsi->pembimbing[1]->nip ?? '');
        @endphp

        <div class="mb-3">
            <label class="form-label">Dosen Pembimbing 1</label>
            <select name="pembimbing_1" class="form-control" required>
                <option value="">-- Pilih --</option>
                @foreach($dosenList as $dosen)
                    <option value="{{ $dosen->nip }}" {{ $pembimbing1 == $dosen->nip ? 'selected' : '' }}>{{ $dosen->nip }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Dosen Pembimbing 2</label>
            <select name="pembimbing_2" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach($dosenList as $dosen)
                    <option value="{{ $dosen->nip }}" {{ $pembimbing2 == $dosen->nip ? 'selected' : '' }}>{{ $dosen->nip }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>