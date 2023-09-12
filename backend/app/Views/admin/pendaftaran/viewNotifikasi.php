<div class="modal-content">
    <div class="modal-body p-3">
        <div class="text-center">
            <h5>Pendaftaran PKL</h5>
        </div>
        <hr>
        <form id="form" action="<?= base_url() ?>/app" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
            <input hidden type="text" value="<?= $database[0][0]['kd_pendaftaran'] ?>" name="id">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Tanggal</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= $database[0][0]['tanggal'] ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Peserta</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucwords($database[1][0]['nama']) ?></small>
                        </div>
                    </div>
                    <?php for($i=1; $i<count($database[1]); $i++){ ?>
                        <div class="row">
                            <div class="col-sm-4 border-right"></div>
                            <div class="col-sm-8">
                                <small><?= $database[1][$i]['nama'] ?></small>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Asal</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucwords($database[0][0]['kampus']) ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Jurusan</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucwords($database[0][0]['jurusan']) ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Alamat</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucfirst($database[0][0]['alamat']) ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Surat</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><a id="rekomendasi" href="<?= base_url() ?>/service/pendaftaran/<?= $database[0][0]['surat'] ?>">Rekomendasi</a></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Status</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><span class="badge badge-warning" style="width: 70px;">Proses</span></small>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <button style="width: 100px;" type="button" class="btn btn-default" data-dismiss="modal"><small>Keluar</small></button>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <div class="pr-4">
                        <button style="width: 100px;" name="button" type="submit" onclick="submitForm(event)" class="btn btn-danger"><small>Tidak Terima</small></button>
                    </div>
                    <div>
                    <button style="width: 100px;" name="button" type="submit" class="btn btn-primary"><small>Terima</small></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
  function submitForm(event) {
    // Menghentikan aksi form secara sementara
    event.preventDefault();
    event.stopPropagation();

    Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Tidak menerima pendaftaran PKL ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin'
        }).then((result) => {
            if (result.value) {
                document.getElementById("form").submit();
            }
        })
  }
</script>
<script>
    $("a#tdt").click(function(e) {
        e.preventDefault();
        const href = $(this).attr("href");
        Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Tidak menerima pendaftaran PKL ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin'
        }).then((result) => {
            if (result.value) {
                document.location.href = href;
            }
        })
    });
    
      //sweetalert wa
  $("a#rekomendasi").click(function(e) {
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