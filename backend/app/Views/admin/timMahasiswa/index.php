                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-table pr-3"></i>Data <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <nav aria-label="Page navigation example">
                              <form id="printPDF" action="<?= base_url() ?>/atg" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                <ul class="pagination p-0 m-0">
                                    <li><div class="btn-group" role="group">
                                        <a href="<?php echo base_url() ?>/att" class="btn btn-success">Tambah</a>
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
                                    <li><a class="nav-link" style="color: black;">Status Tim:</a></li>
                                    <li>
                                        <div class="form-group">
                                            <select name="statusTim"  class="form-control select2bs4" required>
                                                <option value="">. . .</option>
                                                <option value="all">All</option>
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
                                                    <option value="<?= $data['TAHUN_TIM'] ?>"><?= $data['TAHUN_TIM'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                              </form>
                          </nav>
                          <table id="table" class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                      <th class="text-center" width="7%">No</th>
                                      <th class="text-center" width="40%">Tim Peserta</th>
                                      <th class="text-center" width="40%">Peserta</th>
                                      <th class="text-center" width="13%">Aksi</th>
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
                                                          <strong> Tgl. Mulai</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= $data['TGL_MULAI_TIM'] ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong> Tgl. Selesai</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= $data['TGL_SELESAI_TIM'] ?>
                                                      </div>
                                                  </div>
                                                  <hr class="my-2">
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong> Nama</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= ucwords($data['NAMA_TIM']) ?>
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
                                                          <strong> Nama</strong>
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
                                                          <strong>Pembimbing PKL</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <a id="pembimbing" href="<?= base_url() ?>/akvp?id=<?= $data['KD_KAWAN'] ?>"><?= ucwords($data['NAMA_KAWAN']) ?></a>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Tahun</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= $data['TAHUN_TIM'] ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Status Tim</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                        <?php date_default_timezone_set('Asia/Jakarta'); if(date('Y-m-d') < $data['CEK_TGL_MULAI']){ ?>
                                                            <span class="badge badge-warning" style="width: 70px;">Menunggu</span> 
                                                        <?php }else{ ?>
                                                            <span class="badge <?php if($data['STATUS_TIM'] == "aktif"){ echo "badge-success"; }else{ echo "badge-danger"; } ?>" style="width: 70px;"><?= ucwords($data['STATUS_TIM']) ?></span> 
                                                        <?php } ?>
                                                      </div>
                                                  </div>
                                              </small>
                                          </td>
                                          <td>
                                              <?php $urut = 1;
                                                foreach ($database[2] as $data2) { ?>
                                                  <?php if ($data2['KD_TIM'] == $data['KD_TIM']) { ?>
                                                      <small>
                                                          <div class="row">
                                                              <div class="col-md-12 pr-0">
                                                                  <strong>Peserta <?= $urut ?></strong>
                                                              </div>
                                                          </div>
                                                          <div class="row">
                                                              <div class="col-md-1 pr-0 pl-0"></div>
                                                              <div class="col-md-3 pr-0">
                                                                  <strong>Nama</strong>
                                                              </div>
                                                              <div class="col-md-1 pr-0 pl-0">
                                                                  :
                                                              </div>
                                                              <div class="col-md-7 pl-0 pr-0">
                                                                <a id="pesertaMahasiswa" href="<?= base_url() ?>/amvdm?id=<?= $data2['KD_PST'] ?>"><?= ucwords($data2['NAMA_PST']) ?></a>
                                                              </div>
                                                          </div>
                                                          <div class="row">
                                                              <div class="col-md-1 pr-0 pl-0"></div>
                                                              <div class="col-md-3 pr-0">
                                                                  <strong>Whatshapp</strong>
                                                              </div>
                                                              <div class="col-md-1 pr-0 pl-0">
                                                                  :
                                                              </div>
                                                              <div class="col-md-7 pl-0 pr-0">
                                                                  <a id="hp" href="https://wa.me/<?= $wa = "+62" . substr($data2['NOHP_PST'], 1); ?>?"><?= $data2['NOHP_PST'] ?></a>
                                                              </div>
                                                          </div>
                                                          <div class="row">
                                                              <div class="col-md-1 pr-0 pl-0"></div>
                                                              <div class="col-md-3 pr-0 mb-3">
                                                                  <strong>Status PKL</strong>
                                                              </div>
                                                              <div class="col-md-1 pr-0 pl-0">
                                                                  :
                                                              </div>
                                                              <div class="col-md-7 pl-0 pr-0">
                                                              <?php date_default_timezone_set('Asia/Jakarta'); if(date('Y-m-d') < $data['CEK_TGL_MULAI']){ ?>
                                                            <span class="badge badge-warning" style="width: 70px;">Menunggu</span> 
                                                        <?php }else{ ?>
                                                            <?php if ($data2['STATUS_PST'] == "aktif") { ?>
                                                                <span class="badge badge-success" style="width: 70px;"><?= ucwords($data2['STATUS_PST']) ?></span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger" style="width: 70px;"><?= ucwords($data2['STATUS_PST']) ?></span>
                                                            <?php } ?>
                                                        <?php } ?>
                                                              </div>
                                                          </div>
                                                      </small>
                                                  <?php $urut++;
                                                    } ?>
                                              <?php } ?>
                                          </td>
                                          <td class="text-center">
                                              <a href="<?php echo base_url() ?>/ate?id=<?= $data['KD_TIM'] ?>" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                              <a>|</a>
                                              <a id="hapus" href="<?php echo base_url() ?>/ath?id=<?= $data['KD_TIM'] ?>" class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>
                                          </td>
                                      </tr>
                                  <?php $no++;
                                    } ?>
                              </tbody>
                          </table>
                      </div>
                  </div>