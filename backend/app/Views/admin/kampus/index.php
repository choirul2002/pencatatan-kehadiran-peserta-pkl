                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-table pr-3"></i>Data <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <div class="d-flex justify-content-start">
                              <nav aria-label="Page navigation example">
                                  <form id="printPDF" action="<?= base_url() ?>/akg"  method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                    <input hidden type="text" name="button" id="button">
                                    <ul class="pagination p-0 m-0">
                                        <li class="mb-3"><div class="btn-group" role="group">
                                            <a href="<?= base_url() ?>/akt" class="btn btn-success">Tambah</a>
                                            <button value="pdf" name="generate[]" type="submit" class="btn btn-success">PDF</button>
                                            <button value="print" name="generate[]" type="submit" class="btn btn-success">Print</button>
                                        </div></li>
                                        <li><a class="nav-link" style="color: black;">Kategori:</a></li>
                                        <li>
                                            <div class="form-group">
                                                <select name="kategori"  class="form-control select2bs4" required>
                                                    <option value="">. . .</option>
                                                    <option value="all">All</option>
                                                    <option value="sma">SMA</option>
                                                    <option value="smk">SMK</option>
                                                    <option value="man">MAN</option>
                                                    <option value="perguruan tinggi">Perguruan Tinggi</option>
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
                                      <th class="text-center" width="45%">Asal Peserta</th>
                                      <th class="text-center" width="35%">Kontak</th>
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
                                                      <div class="col-md-2 pr-0">
                                                          <strong>Nama</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-9 pl-0 pr-0">
                                                          <?= ucwords($data['NAMA_ASAL']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-2 pr-0">
                                                          <strong>Kategori</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-9 pl-0 pr-0">
                                                          <?php if($data['KATEGORI_ASAL'] == "perguruan tinggi"){ ?>
                                                            <?= ucwords($data['KATEGORI_ASAL']) ?>
                                                          <?php }else{ ?>
                                                            <?= strtoupper($data['KATEGORI_ASAL']) ?>
                                                          <?php } ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-2 pr-0">
                                                          <strong>Alamat</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-9 pl-0 pr-0">
                                                          <?= ucfirst($data['ALAMAT_ASAL']) ?>
                                                      </div>
                                                  </div>
                                              </small>
                                          </td>
                                          <td>
                                              <small>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Telephone</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= $data['TELP_ASAL'] ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Fax</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= $data['FAX_ASAL'] ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Website</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <a id="website" href="<?= $data['WEBSITE_ASAL'] ?>" style="text-decoration: underline;" target="_blank"><?= $data['WEBSITE_ASAL'] ?></a>
                                                      </div>
                                                  </div>
                                              </small>
                                          </td>
                                          <td class="text-center">
                                              <a href="<?= base_url() ?>/ake?id=<?= $data['KD_ASAL'] ?>" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                              <a>|</a>
                                              <a id="hapus" href="<?= base_url() ?>/akh?id=<?= $data['KD_ASAL'] ?>" class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>
                                          </td>
                                      </tr>
                                  <?php $no++;
                                    } ?>
                              </tbody>
                          </table>
                      </div>
                  </div>