<div class="modal-content">
    <div class="modal-body">
        <div class="card card-widget widget-user">
            <div class="widget-user-header bg-secondary">
                <h1 class="widget-user-username"><strong><b><?= ucwords($filter_mahasiswa[0]['nama_mhs']) ?></b></strong></h1>
                <p><?= ucwords($filter_mahasiswa[0]['nama_kmps']) ?></p>
            </div>
            <a id="fotoPersom" href="<?= base_url() ?>/amvm?foto=<?= $filter_mahasiswa[0]['foto_mhs'] ?>">
                <div class="widget-user-image pt-1">
                    <img class="img-circle elevation-2" src="<?= base_url() ?>/public/service/profil/<?= $filter_mahasiswa[0]['foto_mhs'] ?>" style="width: 100px;height: 100px;" alt="User Avatar">
                </div>
            </a>
            <div class="card-body" style="padding-top: 50px;">
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <?php foreach ($filter_mahasiswa[2] as $data) { ?>
                                <?php if ($data['kd_mhs'] == $filter_mahasiswa[0]['kd_mhs']) { ?>
                                    <h5><b><?= $data['hadir'] ?></b></h5>
                                <?php } ?>
                            <?php } ?>
                            <span class="description-text"><b>Hadir</b></span>
                        </div>
                    </div>
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <?php foreach ($filter_mahasiswa[1] as $data) { ?>
                                <?php if ($data['kd_mhs'] == $filter_mahasiswa[0]['kd_mhs']) { ?>
                                    <h5><b><?= $data['terlambat'] ?></b></h5>
                                <?php } ?>
                            <?php } ?>
                            <span class="description-text"><b>Telat</b></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="description-block">
                            <?php foreach ($filter_mahasiswa[3] as $data) { ?>
                                <?php if ($data['kd_mhs'] == $filter_mahasiswa[0]['kd_mhs']) { ?>
                                    <h5><b><?= $data['izin'] ?></b></h5>
                                <?php } ?>
                            <?php } ?>
                            <span class="description-text"><b>Izin</b></span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <strong>Nama</strong>
                    </div>
                    <div class="col-sm-8">
                        <?= ucwords($filter_mahasiswa[0]['nama_mhs']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <strong>Asal</strong>
                    </div>
                    <div class="col-sm-8">
                        <?= ucwords($filter_mahasiswa[0]['nama_kmps']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <strong>Kelamin</strong>
                    </div>
                    <div class="col-sm-8">
                        <?= ucwords($filter_mahasiswa[0]['jk_mhs']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <strong>Agama</strong>
                    </div>
                    <div class="col-sm-8">
                        <?= ucwords($filter_mahasiswa[0]['agama_mhs']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <strong>Alamat</strong>
                    </div>
                    <div class="col-sm-8">
                        <?= ucfirst($filter_mahasiswa[0]['alamat_mhs']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <strong>Handphone</strong>
                    </div>
                    <div class="col-sm-8">
                        <?= $filter_mahasiswa[0]['nohp_mhs'] ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <strong>Tgl. Mulai</strong>
                    </div>
                    <div class="col-sm-8">
                        <?= $filter_mahasiswa[0]['tgl_mulai'] ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <strong>Tgl. Selesai</strong>
                    </div>
                    <div class="col-sm-8">
                        <?= $filter_mahasiswa[0]['tgl_selesai'] ?>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <button style="width:100px;" type="button" class="btn btn-default" data-dismiss="modal"><small>Keluar</small></button>
            <a style="width:100px;" id="nonaktif" href="<?= base_url() ?>/amnap?id=<?= $filter_mahasiswa[0]['kd_mhs'] ?>" class="btn btn-danger"><small>Non-Aktif</small></a>
        </div>
    </div>
</div>

<script>
    //swet alert konfirmasi
    $("a#nonaktif").click(function(e) {
        e.preventDefault();
        const href = $(this).attr("href");
        Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Ingin menonaktifkan mahasiswa ini!",
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

    //hide modal notifikasi
    $("a#fotoPersom").click(function(e) {
        e.preventDefault();
        $('#modal-default').modal("hide");

        $('#modal-foto-person').modal("show");
        $('#modalFotoPerson').load($(this).attr("href"));
    });
</script>