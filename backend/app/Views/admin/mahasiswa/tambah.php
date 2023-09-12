                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="far fa-sticky-note pr-3"></i>Tambah <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <form onsubmit="return validateFormPeserta()" action="<?= base_url() ?>/amts" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                              <?= csrf_field(); ?>
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Nama Peserta</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-9">
                                                  <div class="form-group">
                                                      <input type="text" name="mahasiswa" class="form-control" placeholder="Nama Peserta" required>
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
                                                          <option value="">. . .</option>
                                                          <option value="Laki-laki">Laki-laki</option>
                                                          <option value="Perempuan">Perempuan</option>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <select name="agama" class="form-control select2bs4" required>
                                                          <option value="">. . .</option>
                                                          <option value="Budha">Budha</option>
                                                          <option value="Hindu">Hindu</option>
                                                          <option value="Islam">Islam</option>
                                                          <option value="Konghucu">Konghucu</option>
                                                          <option value="Kristen">Kristen</option>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <select name="kampus" class="form-control select2bs4" required>
                                                          <option value="">. . .</option>
                                                          <?php foreach ($database[1] as $data) { ?>
                                                              <option value="<?= $data['KD_ASAL'] ?>"><?= ucwords($data['NAMA_ASAL']) ?></option>
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
                                                      <textarea name="alamat" class="form-control" rows="5" required></textarea>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Email, Whatshapp, Tahun Masuk</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-0">
                                                      <input id="inputemail" type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                                                      <small class="text-secondary"><label>Contoh :</label> abcdef@gmail.com</small>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group mb-0">
                                                      <input id="inputwhatshapp" type="number" name="wa" class="form-control mb-2" placeholder="Whatshapp" required>
                                                      <small class="text-secondary"><label>Contoh :</label> 080000000000</small>
                                                  </div>
                                              </div>
                                              <div class="col-md-2">
                                                  <div class="form-group mb-0">
                                                      <input type="number" name="tahun" class="form-control mb-2" placeholder="Tahun Masuk" required>
                                                      <small class="text-secondary"><label>Contoh :</label> 0000</small>
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
                  