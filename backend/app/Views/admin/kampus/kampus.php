<div class="modal-content">
    <div class="modal-body p-3">
        <div style="text-align: center;">
            <h2><strong>Asal Peserta</strong></h2>
        </div>
        <hr>
            <div class="row">
                <div class="col-sm-3 border-right">
                    <small><strong>Nama</strong></small>
                </div>
                <div class="col-sm-9">
                    <small><?= ucwords($kampus[0]['NAMA_ASAL']) ?></small>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 border-right">
                    <small><strong>Kategori</strong></small>
                </div>
                <div class="col-sm-9">
                    <small>
                    <?php if($kampus[0]['KATEGORI_ASAL'] == "perguruan tinggi"){ ?>
                                                            <?= ucwords($kampus[0]['KATEGORI_ASAL']) ?>
                                                          <?php }else{ ?>
                                                            <?= strtoupper($kampus[0]['KATEGORI_ASAL']) ?>
                                                          <?php } ?>    
                    </small>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 border-right">
                    <small><strong>Telp</strong></small>
                </div>
                <div class="col-sm-9">
                    <small><?= $kampus[0]['TELP_ASAL'] ?></small>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 border-right">
                    <small><strong>Fax</strong></small>
                </div>
                <div class="col-sm-9">
                    <small><?= $kampus[0]['FAX_ASAL'] ?></small>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 border-right">
                    <small><strong>Website</strong></small>
                </div>
                <div class="col-sm-9">
                    <small><a id="website" href="<?= $kampus[0]['WEBSITE_ASAL'] ?>" target="_blank"><?= $kampus[0]['WEBSITE_ASAL'] ?></a></small>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 border-right">
                    <small><strong>Alamat</strong></small>
                </div>
                <div class="col-sm-9">
                    <small><?= ucfirst($kampus[0]['ALAMAT_ASAL']) ?></small>
                </div>
            </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <button style="width: 100px;" type="button" class="btn btn-default" data-dismiss="modal"><small>Keluar</small></button>
            </div>
        </div>
    </div>
</div>
<script>
      //sweetalert wa
  $("a#website").click(function(e) {
    e.preventDefault();
    const href = $(this).attr("href");
    let timerInterval
    Swal.fire({
      title: 'Loading system',
      html: 'Open in <strong></strong> seconds.',
      showConfirmButton: false,
      timer: 2000,
      onBeforeOpen: () => {
        Swal.showLoading()
        timerInterval = setInterval(() => {
          Swal.getContent().querySelector('strong')
            .textContent = Swal.getTimerLeft()
        }, 100)
      },
      onClose: () => {
        clearInterval(timerInterval)
      }
    }).then((result) => {
      if (result.dismiss === Swal.DismissReason.timer) {
        window.open(href);
      }
    })
  });
</script>