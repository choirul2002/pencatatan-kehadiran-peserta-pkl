                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="far fa-sticky-note pr-3"></i>Edit <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <form onsubmit="return validateFormAsalPeserta()" action="<?= base_url() ?>/akse" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                              <?= csrf_field(); ?>
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Nama Asal</label>
                                                      <input hidden type="text" value="<?= $database[1][0]['KD_ASAL'] ?>" name="kode" class="form-control" placeholder="Nama Asal" readonly>
                                                  </div>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="form-group">
                                                      <input type="text" value="<?= $database[1][0]['NAMA_ASAL'] ?>" name="kampus" class="form-control" placeholder="Nama Asal" required>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                  <select name="kategori"  class="form-control select2bs4" required>
                                                    <option value="">. . .</option>
                                                    <option value="sma" <?php if ($database[1][0]['KATEGORI_ASAL'] == "sma") {
                                                                                    echo "selected";
                                                                                } ?>>SMA</option>
                                                    <option value="smk" <?php if ($database[1][0]['KATEGORI_ASAL'] == "smk") {
                                                                                    echo "selected";
                                                                                } ?>>SMK</option>
                                                    <option value="man" <?php if ($database[1][0]['KATEGORI_ASAL'] == "man") {
                                                                                    echo "selected";
                                                                                } ?>>MAN</option>
                                                    <option value="perguruan tinggi" <?php if ($database[1][0]['KATEGORI_ASAL'] == "perguruan tinggi") {
                                                                                    echo "selected";
                                                                                } ?>>Perguruan Tinggi</option>
                                                </select>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Telp, Fax, Website</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group mb-0">
                                                      <input type="text" value="<?= $database[1][0]['TELP_ASAL'] ?>" name="telp" class="form-control mb-2" placeholder="Telp" required>
                                                      <small class="text-secondary"><label>Contoh :</label> (0000) 000000 - 000000</small>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group mb-0">
                                                      <input type="text" value="<?= $database[1][0]['FAX_ASAL'] ?>" name="fax" class="form-control mb-2" placeholder="Fax" required>
                                                      <small class="text-secondary"><label>Contoh :</label> (0000) 000000</small>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group mb-0">
                                                      <input id="inputwebsite" type="text" value="<?= $database[1][0]['WEBSITE_ASAL'] ?>" name="website" class="form-control mb-2" placeholder="Website" required>
                                                      <small class="text-secondary"><label>Contoh :</label> https://www.asal.ac.id</small>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0">Alamat</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-9">
                                                  <div class="form-group">
                                                      <textarea name="alamat" class="form-control" rows="5" required><?= ucfirst($database[1][0]['ALAMAT_ASAL']) ?>"</textarea>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="d-flex justify-content-center py-2">
                                          <div class="form-group">
                                              <Button class="btn btn-primary" style="width: 200px;" type="submit">Simpan</Button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>