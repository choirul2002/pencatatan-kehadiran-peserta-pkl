<div class="row">
    <?php foreach ($database[1] as $data) { ?>
        <div class="col-md-4">
            <div class="card card-widget widget-user">
                <div class="widget-user-header bg-secondary">
                    <h1 class="widget-user-username"><strong><b><?= ucwords($data['nama_mhs']) ?></b></strong></h1>
                    <p><?= ucwords($data['nama_kmps']) ?></p>
                </div>
                <a id="foto" href="<?= base_url() ?>/amvm?foto=<?= $data['foto_mhs'] ?>">
                    <div class="widget-user-image pt-1">
                        <img class="img-circle elevation-2" src="<?= base_url() ?>/public/service/profil/<?= $data['foto_mhs'] ?>" style="width: 100px;height: 100px;" alt="User Avatar">
                    </div>
                </a>
                <div class="card-body" style="padding-top: 50px;">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <?php foreach ($database[3] as $data3) { ?>
                                    <?php if ($data3['kd_mhs'] == $data['kd_mhs']) { ?>
                                        <h5><b><?= $data3['hadir'] ?></b></h5>
                                    <?php } ?>
                                <?php } ?>
                                <span class="description-text"><b>Hadir</b></span>
                            </div>
                        </div>
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <?php foreach ($database[2] as $data2) { ?>
                                    <?php if ($data2['kd_mhs'] == $data['kd_mhs']) { ?>
                                        <h5><b><?= $data2['terlambat'] ?></b></h5>
                                    <?php } ?>
                                <?php } ?>
                                <span class="description-text"><b>Telat</b></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="description-block">
                                <?php foreach ($database[4] as $data4) { ?>
                                    <?php if ($data4['kd_mhs'] == $data['kd_mhs']) { ?>
                                        <h5><b><?= $data4['izin'] ?></b></h5>
                                    <?php } ?>
                                <?php } ?>
                                <span class="description-text"><b>Sakit</b></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Nama</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucwords($data['nama_mhs']) ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Asal</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucwords($data['nama_kmps']) ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Kelamin</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucwords($data['jk_mhs']) ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Agama</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucwords($data['agama_mhs']) ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Alamat</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= ucfirst($data['alamat_mhs']) ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Handphone</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= $data['nohp_mhs'] ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Tgl. Mulai</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= $data['tgl_mulai'] ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <small><strong>Tgl. Selesai</strong></small>
                        </div>
                        <div class="col-sm-8">
                            <small><?= $data['tgl_selesai'] ?></small>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-center">
                        <a id="nonaktif" href="<?= base_url() ?>/amna?id=<?= $data['kd_mhs'] ?>" class="btn btn-danger btn-block"><small>Non-Aktif</small></a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>