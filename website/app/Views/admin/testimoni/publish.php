<div class="modal-content">
    <div class="modal-body p-3">
        <div style="text-align: center;">
            <h2><strong>Setting Publish</strong></h2>
        </div>
        <hr>
        <form action="<?= base_url() ?>/atsp" method="post">
            <input hidden type="text" value="<?= $testi[0]['id_testi'] ?>" name="id_testi" id="">
            <div class="row">
                <div class="col-md-4" style="padding-top: 6px;">Publish</div>
                <div class="col-md-1" style="padding-top: 6px;">:</div>
                <div class="col-md-7">
                    <div class="form-group">
                        <select name="publish"  class="form-control select2bs4" required>
                            <option value="">. . .</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
                        </select>                
                    </div>
                </div>
            </div>
            <hr class="mt-0">
            <div class="d-flex justify-content-between">
                <button style="width: 100px;" type="button" class="btn btn-default" data-dismiss="modal"><small>Keluar</small></button>
                <button style="width: 100px;" type="submit" class="btn btn-primary"><small>Konfirmasi</small></button>
            </div>
        </form>
    </div>
</div>
<script>
    $(function() {
      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    });
</script>
