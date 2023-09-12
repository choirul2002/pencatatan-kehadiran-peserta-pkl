<div class="modal-content">
    <div class="modal-body p-3">
        <div style="text-align: center;">
            <h2><strong>Peserta PKL</strong></h2>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <small><strong>Nama</strong></small>
                    </div>
                    <div class="col-sm-8">
                        <small><?= ucwords($mahasiswa[0]['NAMA_PST']) ?></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <small><strong>Kelamin</strong></small>
                    </div>
                    <div class="col-sm-8">
                        <small><?= ucwords($mahasiswa[0]['JK_PST']) ?></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <small><strong>Agama</strong></small>
                    </div>
                    <div class="col-sm-8">
                        <small><?= ucwords($mahasiswa[0]['AGAMA_PST']) ?></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <small><strong>Whatshapp</strong></small>
                    </div>
                    <div class="col-sm-8">
                        <small>
                            <a id="hp" href="https://wa.me/<?= $wa = "+62" . substr($mahasiswa[0]['NOHP_PST'], 1); ?>?"><?= $mahasiswa[0]['NOHP_PST'] ?></a>
                        </small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4 border-right">
                        <small><strong>Alamat</strong></small>
                    </div>
                    <div class="col-sm-8">
                        <small><?= ucfirst($mahasiswa[0]['ALAMAT_PST']) ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img class="img img-thumbnail" style="height: 185px;" src="<?= base_url() ?>/service/profil/<?= $mahasiswa[0]['FOTO_PST'] ?>" alt="">
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
        $("a#hp").click(function(e) {
          e.preventDefault();
          const href = $(this).attr("href");
          let timerInterval
          Swal.fire({
            title: 'Whatshapp system',
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
              window.open(href, "_blank");
            }
          })
        });
</script>