<div class="row">
        <div class="col-lg-6">
            <?php foreach ($database[83] as $tim) { ?>
            <div class="card p-3">
                <div class="text-center">
                    <h5 style="margin-bottom:0px;"><strong><?= ucwords($tim['NAMA_TIM']) ?></strong></h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Pembimbing PKL</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><a id="pembimbing" href="<?= base_url() ?>/akvp?id=<?= $tim['KD_KAWAN'] ?>"><?= ucwords($tim['NAMA_KAWAN']) ?></a></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>PKL Mulai</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><?= $tim['TGL_MULAI_TIM'] ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>PKL Selesai</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><?= $tim['TGL_SELESAI_TIM'] ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Peserta</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <?php foreach($database[82] as $detail){ ?>
                                    <?php if($detail['KD_TIM'] == $tim['KD_TIM']){ ?>
                                        <small><a id="pesertaMahasiswa" href="<?= base_url() ?>/amvdm?id=<?= $detail['KD_PST'] ?>"><?= ucwords($detail['NAMA_PST']) ?></a></small><br>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Asal</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><a id="kampus" href="<?= base_url() ?>/akvk?id=<?= $tim['KD_ASAL'] ?>"><?= ucwords($tim['NAMA_ASAL']) ?></a></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Alamat</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><?= ucwords($tim['ALAMAT_ASAL']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <a id="notinonaktif" href="<?= base_url() ?>/atnn?id=<?= $tim['KD_TIM'] ?>" style="width: 100px" class="btn btn-danger"><small>Non-Aktif</small></a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="col-lg-6">
            <?php foreach ($database[84] as $timb) { ?>
            <div class="card p-3">
                <div class="text-center">
                    <h5 style="margin-bottom:0px;"><strong><?= ucwords($timb['NAMA_TIM']) ?></strong></h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Pembimbing PKL</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><a id="pembimbing" href="<?= base_url() ?>/akvp?id=<?= $timb['KD_KAWAN'] ?>"><?= ucwords($timb['NAMA_KAWAN']) ?></a></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>PKL Mulai</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><?= $timb['TGL_MULAI_TIM'] ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>PKL Selesai</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><?= $timb['TGL_SELESAI_TIM'] ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Peserta</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <?php foreach($database[82] as $detail){ ?>
                                    <?php if($detail['KD_TIM'] == $timb['KD_TIM']){ ?>
                                        <small><a id="pesertaMahasiswa" href="<?= base_url() ?>/amvdm?id=<?= $detail['KD_PST'] ?>"><?= ucwords($detail['NAMA_PST']) ?></a></small><br>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Asal</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><a id="kampus" href="<?= base_url() ?>/akvk?id=<?= $timb['KD_ASAL'] ?>"><?= ucwords($timb['NAMA_ASAL']) ?></a></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Alamat</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><?= ucwords($timb['ALAMAT_ASAL']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <a id="notinonaktif" href="<?= base_url() ?>/atnn?id=<?= $timb['KD_TIM'] ?>" style="width: 100px" class="btn btn-danger"><small>Non-Aktif</small></a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
</div>
