{{-- Modal Transkrip Nilai --}}
<div class="modal fade" id="cetakTranskripModal" tabindex="-1" aria-labelledby="cetak-transkrip-nilai" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="formCetakTranskripNilai" action="{{ url('/admin/akademik/yudisium/cetak-transkrip-nilai') }}" target="_blank">
            @csrf
                <input type="hidden" name="nimEnkripsi" id="nim-transkrip">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cetak-ijazah">Cetak Transkrip Nilai <span id="nama-transkrip"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nomor-sk" class="form-label">Nomor SK</label>
                        <input type="text" class="form-control" id="nomor-sk" name="nomorSk" value="153/D/O/2000 tanggal 10 Agustus 2000" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomor-seri" class="form-label">Nomor Seri Transkrip</label>
                        <input type="text" class="form-control" id="nomor-seri" name="nomorSeri" value="063032" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-submit">Cetak</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(function () {
        $(document).on('click', '.cetak-transkrip-record', function () {
            const nim = $(this).data('nim');
            const nama = $(this).data('nama');
            $('#nim-transkrip').val(nim);
            $('#nama-transkrip').text(nama);
        });
    });
</script>