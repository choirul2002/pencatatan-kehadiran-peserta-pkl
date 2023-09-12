                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="far fa-sticky-note pr-3"></i>Edit <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <form onsubmit="return validateFormPeserta()" action="<?= base_url() ?>/amse" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                              <?= csrf_field(); ?>
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Nama Peserta</label>
                                                      <input type="text" hidden value="<?= $database[2][0]['KD_PST'] ?>" name="kode" class="form-control" placeholder="Nama Mahasiswa" readonly>
                                                  </div>
                                              </div>
                                              <div class="col-md-9">
                                                  <div class="form-group">
                                                      <input type="text" value="<?= $database[2][0]['NAMA_PST'] ?>" name="mahasiswa" class="form-control" placeholder="Nama Mahasiswa" required>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Kelamin, Agama, Asal</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <select name="kelamin" class="form-control select2bs4" required>
                                                          <option value="Laki-laki" <?php if ($database[2][0]['JK_PST'] == "Laki-laki") {
                                                                                        echo "selected";
                                                                                    } ?>>Laki-laki</option>
                                                          <option value="Perempuan" <?php if ($database[2][0]['JK_PST'] == "Perempuan") {
                                                                                        echo "selected";
                                                                                    } ?>>Perempuan</option>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <select name="agama" class="form-control select2bs4" required>
                                                          <option value="Budha" <?php if ($database[2][0]['AGAMA_PST'] == "Budha") {
                                                                                    echo "selected";
                                                                                } ?>>Budha</option>
                                                          <option value="Hindu" <?php if ($database[2][0]['AGAMA_PST'] == "Hindu") {
                                                                                    echo "selected";
                                                                                } ?>>Hindu</option>
                                                          <option value="Islam" <?php if ($database[2][0]['AGAMA_PST'] == "Islam") {
                                                                                    echo "selected";
                                                                                } ?>>Islam</option>
                                                          <option value="Katholik" <?php if ($database[2][0]['AGAMA_PST'] == "Konghucu") {
                                                                                        echo "selected";
                                                                                    } ?>>Konghucu</option>
                                                          <option value="Kristen" <?php if ($database[2][0]['AGAMA_PST'] == "Kristen") {
                                                                                        echo "selected";
                                                                                    } ?>>Kristen</option>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <select name="kampus" class="form-control select2bs4" required>
                                                          <?php foreach ($database[1] as $data) { ?>
                                                              <option value="<?= $data['KD_ASAL'] ?>" <?php if ($database[2][0]['KD_ASAL'] == $data['KD_ASAL']) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?= ucwords($data['NAMA_ASAL']) ?></option>
                                                          <?php } ?>
                                                      </select>
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
                                                      <textarea name="alamat" class="form-control" rows="5" required><?= ucfirst($database[2][0]['ALAMAT_PST']) ?></textarea>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Whatshapp, Tahun Masuk</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group mb-0">
                                                      <input id="inputwhatshapp" type="number" value="<?= $database[2][0]['NOHP_PST'] ?>" name="wa" class="form-control mb-2" placeholder="Whatshapp" required>
                                                      <small class="text-secondary"><label>Contoh :</label> 080000000000</small>
                                                  </div>
                                              </div>
                                              <div class="col-md-2">
                                                  <div class="form-group mb-0">
                                                      <input type="number" name="tahun" value="<?= $database[2][0]['TAHUN_PST'] ?>" class="form-control mb-2" placeholder="Tahun Masuk" required>
                                                      <small class="text-secondary"><label>Contoh :</label> 0000</small>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Email, Password, Status Peserta</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-0">
                                                      <input id="inputemail" type="email" value="<?= $database[2][0]['EMAIL'] ?>" name="email" class="form-control mb-2" placeholder="Email" required>
                                                      <small class="text-secondary"><label>Contoh :</label> abcdef@gmail.com</small>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group mb-0">
                                                      <input type="password" value="<?= $database[2][0]['PASSWORD'] ?>" id="password" name="password" class="form-control mb-2" placeholder="Password" required>
                                                      <small class="text-secondary">
                                                          <div class="icheck-primary">
                                                              <input type="checkbox" onclick="myFunction()" id="remember">
                                                              <label for="remember">
                                                                  Lihat Password
                                                              </label>
                                                          </div>
                                                      </small>
                                                  </div>
                                              </div>
                                              <div class="col-md-2">
                                                  <div class="form-group">
                                                  <input readonly type="text" name="status" value="<?= ucwords($database[2][0]['STATUS_PST']) ?>" name="tim" class="form-control" placeholder="Nama Peserta" required>
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