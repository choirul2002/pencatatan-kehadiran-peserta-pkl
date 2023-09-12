<div class="modal-content">
    <div class="modal-body p-3">
        <div class="text-center">
            <h5><strong><?= ucwords($database[0][0]['NAMA_TIM']) ?></strong></h5>
        </div>
        <hr>
        <form id="form" action="<?= base_url() ?>/atnat" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
            <input hidden type="text" value="<?= $database[0][0]['KD_TIM'] ?>" name="id">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Pembimbing PKL</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= $database[0][0]['NAMA_KAWAN'] ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>PKL Mulai</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= $database[0][0]['TGL_MULAI_TIM'] ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>PKL Selesai</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= $database[0][0]['TGL_SELESAI_TIM'] ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Peserta</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucwords($database[1][0]['NAMA_PST']) ?></small>
                        </div>
                    </div>
                    <?php for($i=1; $i<count($database[1]); $i++){ ?>
                        <div class="row">
                            <div class="col-sm-4 border-right"></div>
                            <div class="col-sm-8">
                                <small><?= ucwords($database[1][$i]['NAMA_PST']) ?></small>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Asal</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucwords($database[0][0]['NAMA_ASAL']) ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Alamat</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucwords($database[0][0]['ALAMAT_ASAL']) ?></small>
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
                    <button style="width: 100px" type="submit" onclick="submitForm(event)" class="btn btn-danger"><small>Non-Aktif</small></button>
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
            text: "Ingin menonaktifkan tim ini!",
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
    //swet alert konfirmasi
    $("a#nonaktif").click(function(e) {
        e.preventDefault();
        const href = $(this).attr("href");
        Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Ingin menonaktifkan peserta ini!",
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
</script>