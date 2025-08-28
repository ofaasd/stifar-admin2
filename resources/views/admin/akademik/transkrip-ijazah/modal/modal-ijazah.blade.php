<div class="modal fade" id="cetakIjazahModal" tabindex="-1" aria-labelledby="cetak-ijazah" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <form method="POST" id="formCetakIjazah" action="{{ url('/admin/alumni/cetak-ijazah') }}" target="_blank">
          @csrf
          <input type="hidden" name="nimEnkripsi" id="nim-ijazah-cetak">
          <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="cetak-ijazah">Cetak Ijazah | <span id="nama-ijazah-cetak"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <div class="mb-3">
              <label for="akreditasi1" class="form-label">Akreditasi BAN-PT</label>
              <input type="text" class="form-control" id="akreditasi1" name="akreditasi1" value=" TERAKREDITASI B SK BAN-PT No. 500/SK/BAN-PT/Ak.Ppj/PT/VIII/2022" required>
          </div>
          <div class="mb-3">
              <label for="akreditasi2" class="form-label">Akreditasi LAM-PTKes</label>
              <input type="text" class="form-control" id="akreditasi2" name="akreditasi2" value=" Terakreditasi Baik Sekali SK LAM-PTKes 0815/LAM-PTKes/Akr/Sar/IX/2022" required>
          </div>
          <div class="mb-3">
              <label for="akreditasi2Eng" class="form-label">Akreditasi LAM-PTKes Inggris</label>
              <input type="text" class="form-control" id="akreditasi2Eng" name="akreditasi2Eng" value=" accredited with grade 'very good' SK LAM-PTKes 0815/LAM-PTKes/Akr/Sar/IX/2022" required>
          </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary btn-sm" id="btn-cetak">Cetak</button>
          </div>
          </div>
      </form>
  </div>
</div>


<!-- Add jQuery before your script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>

<script>
    $(function () {
      //Cetak ijazah Record
      $(document).on('click', '.cetak-ijazah-record', function () {
          const nim = $(this).data('nim');
          const nama = $(this).data('nama');
          $('#nim-ijazah-cetak').val(nim);
          $('#nama-ijazah-cetak').text(nama);
      });

      $('#formCetakIjazah').on('submit', function(e) {
        e.preventDefault();
        swal({
            title: 'Konfirmasi Cetak Ijazah',
            text: 'Hati-hati dalam mencetak, karena hitungan duplikat terus bertambah setiap kali mencetak. Lanjutkan mencetak?',
            icon: 'warning',
            buttons: {
                cancel: {
                    text: 'Batal',
                    value: null,
                    visible: true,
                    className: 'btn btn-label-secondary',
                    closeModal: true,
                },
                confirm: {
                    text: 'Lanjutkan Cetak',
                    value: true,
                    visible: true,
                    className: 'btn btn-primary me-3',
                    closeModal: true
                }
            },
            dangerMode: true,
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            }
        }).then(function(result) {
            if (result) {
                $('#formCetakIjazah')[0].submit();
            }
        });
      });
    });
</script>