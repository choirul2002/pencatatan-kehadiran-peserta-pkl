                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="far fa-sticky-note pr-3"></i>Edit Profil</h3>
                      </div>
                      <div class="card-body">
                          <form onsubmit="return validateFormProfil()" action="<?= base_url() ?>/ps" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                              <?= csrf_field(); ?>
                              <div class="row">
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="exampleInputFile">Foto Karyawan</label>
                                      </div>
                                      <div style="margin-bottom: 28px;" class="d-flex justify-content-center">
                                          <img class="img img-thumbnail" id="tampilFoto" style="height:275px;width:208px;" src="<?= base_url() ?>/service/profil/<?= $database[0]['FOTO_KAWAN'] ?>" alt="">
                                      </div>
                                  </div>
                                  <div class="col-md-8">
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Nama Karyawan, Whatshapp</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-5">
                                                  <div class="form-group">
                                                      <input type="text" name="karyawan" value="<?= $database[0]['NAMA_KAWAN'] ?>" class="form-control" placeholder="Nama Karyawan" required>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-0">
                                                      <input id="inputwhatshapp" type="number" name="wa" value="<?= $database[0]['NOHP_KAWAN'] ?>" class="form-control mb-2" placeholder="Whatshapp" required>
                                                      <small class="text-secondary"><label>Contoh : </label> 080000000000</small>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Kelamin, Jabatan, Agama</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <select name="kelamin" class="form-control select2bs4" required>
                                                          <option value="laki-laki" <?php if ($database[0]['JK_KAWAN'] == "laki-laki") {
                                                                                        echo "selected";
                                                                                    } ?>>Laki-laki</option>
                                                          <option value="perempuan" <?php if ($database[0]['JK_KAWAN'] == "perempuan") {
                                                                                        echo "selected";
                                                                                    } ?>>Perempuan</option>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <select name="jabatan" class="form-control select2bs4" required>
                                                          <?php foreach ($database[1] as $data) { ?>
                                                              <option value="<?= $data['KD_JBTN'] ?>" <?php if ($database[0]['KD_JBTN'] == $data['KD_JBTN']) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?= ucwords($data['NAMA_JBTN']) ?></option>
                                                          <?php } ?>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <select name="agama" class="form-control select2bs4" required>
                                                          <option value="budha" <?php if ($database[0]['AGAMA_KAWAN'] == "budha") {
                                                                                    echo "selected";
                                                                                } ?>>Budha</option>
                                                          <option value="hindu" <?php if ($database[0]['AGAMA_KAWAN'] == "hindu") {
                                                                                    echo "selected";
                                                                                } ?>>Hindu</option>
                                                          <option value="islam" <?php if ($database[0]['AGAMA_KAWAN'] == "islam") {
                                                                                    echo "selected";
                                                                                } ?>>Islam</option>
                                                          <option value="katholik" <?php if ($database[0]['AGAMA_KAWAN'] == "katholik") {
                                                                                        echo "selected";
                                                                                    } ?>>Katholik</option>
                                                          <option value="kristen" <?php if ($database[0]['AGAMA_KAWAN'] == "kristen") {
                                                                                        echo "selected";
                                                                                    } ?>>Kristen</option>
                                                          <option value="protestan" <?php if ($database[0]['AGAMA_KAWAN'] == "protestan") {
                                                                                        echo "selected";
                                                                                    } ?>>Protestan</option>
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
                                                      <textarea name="alamat" class="form-control" rows="5" required><?= ucfirst($database[0]['ALAMAT_KAWAN']) ?></textarea>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <div class="custom-file">
                                              <input id="pilihFoto" name="foto" onchange="loadFile(event)" accept="image/*" type="file" class="custom-file-input mb-2">
                                              <label class="custom-file-label">Choose file</label>
                                              <small class="text-secondary"><label>Format :</label> JPG dan PNG</small>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-8">
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Email, Password</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-5">
                                                  <div class="form-group">
                                                      <input id="inputemail" type="email" name="email" value="<?= $database[0]['EMAIL'] ?>" class="form-control mb-2" placeholder="Email" required>
                                                      <small class="text-secondary"><label>Contoh :</label> abcdef@gmail.com</small>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group">
                                                      <input type="password" id="password" name="password" value="<?= $database[0]['PASSWORD'] ?>" class="form-control mb-2" placeholder="Password" required>
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
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="d-flex justify-content-center pb-2">
                                  <div class="form-group">
                                      <Button class="btn btn-primary" style="width: 200px;" type="submit">Simpan</Button>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>