<div class="row">
    <?php foreach ($database[30] as $pendaftar) { ?>
        <div class="col-md-6">
            <div class="card p-3">
                <div class="text-center">
                    <h5>Pendaftaran PKL</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Tanggal</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><?= $pendaftar['tanggal'] ?></small>
                            </div>
                        </div>
                        <?php $no = 1; foreach($database[1] as $mahasiswa){ ?>
                            <?php if($mahasiswa['kd_pendaftaran'] == $pendaftar['kd_pendaftaran']){ ?>
                                <div class="row">
                                    <div class="col-sm-4 border-right"><small <?php if($no > 1){ echo 'hidden'; } ?>><strong>Peserta</strong></small></div>
                                    <div class="col-sm-8">
                                        <small><?= ucwords($mahasiswa['nama']) ?></small>
                                    </div>
                                </div>
                            <?php $no++; } ?>
                        <?php } ?>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Asal</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><?= ucwords($pendaftar['kampus']) ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Jurusan</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><?= ucwords($pendaftar['jurusan']) ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Alamat</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><?= ucfirst($pendaftar['alamat']) ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <small><strong>Surat</strong></small>
                            </div>
                            <div class="col-sm-8">
                                <small><a id="rekomendasi" href="<?= base_url() ?>/service/pendaftaran/<?= $pendaftar['surat'] ?>">Rekomendasi</a></small>
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
                    <div class="col-md-6 d-flex justify-content-start">
                        <div class="pr-4">
                            <a style="width: 100px;" id="tdt" href="<?= base_url() ?>/aptt?id=<?= $pendaftar['kd_pendaftaran'] ?>" class="btn btn-danger"><small>Tidak Terima</small></a>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <div>
                            <a style="width: 100px" href="<?= base_url() ?>/apt?id=<?= $pendaftar['kd_pendaftaran'] ?>" class="btn btn-primary"><small>Terima</small></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>