                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-table pr-3"></i>Data <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <div class="d-flex justify-content-start">
                              <nav aria-label="Page navigation example">
                                  <form id="printPDF" action="<?= base_url() ?>/amg" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                    <ul class="pagination p-0 m-0">
                                        <li><div class="btn-group" role="group">
                                            <a href="<?= base_url() ?>/amt" class="btn btn-success">Tambah</a>
                                            <button value="pdf" name="generate[]" type="submit" class="btn btn-success">PDF</button>
                                            <button value="print" name="generate[]" type="submit" class="btn btn-success">Print</button>
                                        </div></li>
                                        <li><a class="nav-link" style="color: black;">Asal:</a></li>
                                        <li>
                                            <div class="form-group">
                                                <select name="kampus"  class="form-control select2bs4" required>
                                                    <option value="">. . .</option>
                                                    <option value="all">All</option>
                                                    <?php foreach($database[21] as $data){ ?>
                                                        <option value="<?= $data['KD_ASAL'] ?>"><?= ucwords($data['NAMA_ASAL']) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </li>
                                        <li><a class="nav-link" style="color: black;">Status PKL:</a></li>
                                        <li>
                                            <div class="form-group">
                                                <select name="statusPKL"  class="form-control select2bs4" required>
                                                    <option value="">. . .</option>
                                                    <option value="all">All</option>
                                                    <option value="kosong">Kosong</option>
                                                    <option value="menunggu">Menunggu</option>
                                                    <option value="aktif">Aktif</option>
                                                    <option value="tidak aktif">Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </li>
                                        <li><a class="nav-link" style="color: black;">Tahun:</a></li>
                                        <li>
                                            <div class="form-group">
                                                <select name="tahun"  class="form-control select2bs4" required>
                                                    <option value="">. . .</option>
                                                    <option value="all">All</option>
                                                    <?php foreach($database[22] as $data){ ?>
                                                        <option value="<?= $data['TAHUN_PST'] ?>"><?= $data['TAHUN_PST'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                  </form>
                              </nav>
                          </div>
                          <table id="table" class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                      <th class="text-center" width="7%">No</th>
                                      <th class="text-center" width="45%">Peserta PKL</th>
                                      <th class="text-center" width="35%">Akun</th>
                                      <th class="text-center" width="12%">Aksi</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php $no = 1;
                                    foreach ($database[1] as $data) { ?>
                                      <tr>
                                          <td class="text-center"><small><?= $no ?>.</small></td>
                                          <td>
                                              <small>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Nama</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                        <a id="foto" href="<?= base_url() ?>/amvm?foto=<?= $data['FOTO_PST'] ?>"><?= ucwords($data['NAMA_PST']) ?></a>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Asal</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <a id="kampus" href="<?= base_url() ?>/akvk?id=<?= $data['KD_ASAL'] ?>"><?= ucwords($data['NAMA_ASAL']) ?></a>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Kelamin</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= ucwords($data['JK_PST']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Agama</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= ucwords($data['AGAMA_PST']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Alamat</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= ucfirst($data['ALAMAT_PST']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Whatshapp</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-7 pl-0 pr-0">
                                                          <a id="hp" href="https://wa.me/<?= $wa = "+62" . substr($data['NOHP_PST'], 1); ?>?"><?= $data['NOHP_PST'] ?></a>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Kategori</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?php if($data['KATEGORI_ASAL'] == "perguruan tinggi"){ ?>
                                                            <?= ucwords($data['KATEGORI_ASAL']) ?>
                                                          <?php }else{ ?>
                                                            <?= strtoupper($data['KATEGORI_ASAL']) ?>
                                                          <?php } ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Tahun PKL</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= $data['TAHUN_PST'] ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Tim Peserta</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?php if($data['KELOMPOK'] == "iya"){ ?>
                                                            <img style="height:20px;width:20px;" src="<?= base_url() ?>/service/icon/iya.png" alt="">
                                                            <?php }else{ ?>
                                                                <img style="height:20px;width:20px;" src="<?= base_url() ?>/service/icon/tidak.png" alt="">
                                                          <?php } ?>
                                                      </div>
                                                  </div>
                                              </small>
                                          </td>
                                          <td>
                                              <small>
                                                  <div class="row">
                                                      <div class="col-md-4 pr-0">
                                                          <strong>Email</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-7 pl-0 pr-0">
                                                          <a style="text-decoration: underline;" href="mailto:<?= $data['EMAIL'] ?>"> <?= $data['EMAIL'] ?></a>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-4 pr-0">
                                                          <strong>Password</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-7 pl-0 pr-0">
                                                          <?= $data['PASSWORD'] ?>
                                                      </div>
                                                  </div>
                                                  <?php if($data['KELOMPOK'] == "iya"){ ?>
                                                            <div class="row">
                                                                <div class="col-md-4 pr-0">
                                                                    <strong>Status PKL</strong>
                                                                </div>
                                                                <div class="col-md-1 pr-0 pl-0">
                                                                    :
                                                                </div>
                                                                <div class="col-md-7 pl-0 pr-0">
                                                                    <?php if ($data['STATUS_PST'] == "aktif") { ?>
                                                                        <span class="badge badge-success" style="width: 70px;"><?= ucwords($data['STATUS_PST']) ?></span>
                                                                    <?php }else if($data['STATUS_PST'] == "menunggu"){ ?>
                                                                        <span class="badge badge-warning" style="width: 70px;"><?= ucwords($data['STATUS_PST']) ?></span>
                                                                    <?php } else { ?>
                                                                        <span class="badge badge-danger" style="width: 70px;"><?= ucwords($data['STATUS_PST']) ?></span>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <?php } else{ ?>
                                                                <div class="row">
                                                                    <div class="col-md-4 pr-0">
                                                                        <strong>Status PKL</strong>
                                                                    </div>
                                                                    <div class="col-md-1 pr-0 pl-0">
                                                                        :
                                                                    </div>
                                                                    <div class="col-md-7 pl-0 pr-0">
                                                                            <span class="badge badge-secondary" style="width: 70px;"> - </span>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                              </small>
                                          </td>
                                          <td class="text-center">
                                          <?php if($data['KELOMPOK'] == "iya"){ ?>
                                            <a href="<?= base_url() ?>/ame?id=<?= $data['KD_PST'] ?>" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a>|</a>
                                                <a id="hapus" href="<?= base_url() ?>/amh?id=<?= $data['KD_PST'] ?>" class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>
                                            <?php }else{ ?>
                                                <div>
                                                    <a href="<?= base_url() ?>/ame?id=<?= $data['KD_PST'] ?>" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                                    <a>|</a>
                                                    <a id="hapus" href="<?= base_url() ?>/amh?id=<?= $data['KD_PST'] ?>" class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>
                                                </div>
                                                <div style="padding-top:4px;">
                                                    <a style="margin_top:100px;" href="<?= base_url() ?>/attp?id=<?= $data['KD_PST'] ?>" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Tim Peserta"><i class="fa fa-arrow-right"></i></a>
                                                </div>
                                            <?php } ?>
                                          </td>
                                      </tr>
                                  <?php $no++;
                                    } ?>
                              </tbody>
                          </table>
                      </div>
                  </div>