                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="far fa-sticky-note pr-3"></i>Data <?= $title ?></h3>
                      </div>
                      <div class="card-body">
                          <form action="<?= base_url() ?>/akgs" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                              <?= csrf_field(); ?>
                              <input type="text" hidden name="kode" value="<?= $database[20][0]['kd_konf'] ?>">
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="mb-4">
                                           <h2 style="margin-bottom: 0px;"><strong>Informasi Sistem</strong></h2>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="row mb-3">
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label class="mb-0" style="padding-top:6px;">Nama, Singkatan, Versi</label>
                                              </div>
                                          </div>
                                          <div class="col-md-9">
                                              <div class="row">
                                                    <div class="col-md-7">
                                                      <div class="form-group">
                                                        <input type="text" name="namaSistem" value="<?= $database[20][0]['nama_sistem'] ?>" class="form-control mb-2" placeholder="Nama Sistem" required>
                                                      </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                      <div class="form-group">
                                                          <input type="text" name="singkatan" value="<?= $database[20][0]['singkatan'] ?>" class="form-control mb-2" placeholder="Singkatan Sistem" required>
                                                      </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                      <div class="form-group">
                                                          <input type="text" name="versi" value="<?= $database[20][0]['versi'] ?>" class="form-control mb-2" placeholder="Versi Sistem" required>
                                                      </div>
                                                    </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Logo</label> 
                                                  </div>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="form-group">
                                                      <div class="custom-file">
                                                          <input id="pilihFoto" name="logoSistem" onchange="loadFile(event)" accept="image/*" type="file" class="custom-file-input mt-2">
                                                          <label class="custom-file-label">Choose file</label>
                                                          <small class="text-secondary"><label>Format :</label> JPG dan PNG</small>
                                                      </div>  
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                    <img src="<?= base_url() ?>/public/service/icon/<?= $database[20][0]['logo_sistem'] ?>" class="img img-thumbnail">
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <hr>
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="mb-4">
                                           <h2 style="margin-bottom: 0px;"><strong>Kontak</strong></h2>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="row mb-3">
                                          <div class="col-md-6">
                                              <div class="row">
                                                  <div class="col-md-3"><label style="padding-top:6px;">Facebook</label></div>
                                                  <div class="col-md-9">
                                                      <div class="form-group">
                                                        <input type="text" name="facebook" value="<?= $database[20][0]['facebook'] ?>" class="form-control mb-2" placeholder="Facebook" required>
                                                        <small class="text-secondary"><label>Contoh : </label> https://aaaaaaaa</small>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="row">
                                                    <div class="col-md-3"><label style="padding-top:6px;">Instagram</label></div>
                                                    <div class="col-md-9">
                                                      <div class="form-group">
                                                          <input type="text" name="instagram" value="<?= $database[20][0]['instagram'] ?>" class="form-control mb-2" placeholder="Instagram" required>
                                                          <small class="text-secondary"><label>Contoh : </label> https://aaaaaaaa</small>
                                                      </div>
                                                    </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="row mb-3">
                                          <div class="col-md-6">
                                              <div class="row">
                                                  <div class="col-md-3"><label style="padding-top:6px;">Email</label></div>
                                                  <div class="col-md-9">
                                                      <div class="form-group">
                                                        <input type="text" name="email" value="<?= $database[20][0]['email'] ?>" class="form-control mb-2" placeholder="Email" required>
                                                        <small class="text-secondary"><label>Contoh : </label> https://aaaaaaaa</small>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="row">
                                                    <div class="col-md-3"><label style="padding-top:6px;">Twitter</label></div>
                                                    <div class="col-md-9">
                                                      <div class="form-group">
                                                          <input type="text" name="twitter" value="<?= $database[20][0]['twitter'] ?>" class="form-control mb-2" placeholder="Twitter" required>
                                                          <small class="text-secondary"><label>Contoh : </label> https://aaaaaaaa</small>
                                                      </div>
                                                    </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="row mb-3">
                                          <div class="col-md-6">
                                              <div class="row">
                                                  <div class="col-md-3"><label style="padding-top:6px;">Telepon</label></div>
                                                  <div class="col-md-9">
                                                      <div class="form-group">
                                                        <input type="text" name="telepon" value="<?= $database[20][0]['telepon'] ?>" class="form-control mb-2" placeholder="Telepon" required>
                                                        <small class="text-secondary"><label>Contoh : </label> (0000) 000000</small>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="row">
                                                  <div class="col-md-3"><label style="padding-top:6px;">Linked IN</label></div>
                                                  <div class="col-md-9">
                                                      <div class="form-group">
                                                        <input type="text" name="linked" value="<?= $database[20][0]['linked'] ?>" class="form-control mb-2" placeholder="Linked IN" required>
                                                        <small class="text-secondary"><label>Contoh : </label> https://aaaaaaaa</small>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <hr>
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="mb-4">
                                          <h2 style="margin-bottom: 0px;"><strong>Maintenence Sistem</strong></h2>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="row mb-3">
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label class="mb-0" style="padding-top:6px;">Sistem Kelola Data</label>
                                              </div>
                                          </div>
                                          <div class="col-md-3 pt-1">
                                              <div class="form-group d-flex justify-content-center">
                                                  <input type="checkbox" name="simma_pkl_admin" <?php if($database[20][0]['simmapkl_admin'] == "aktif"){ echo "checked"; } ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                              </div>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label class="mb-0" style="padding-top:6px;">Sistem Monitoring & Presensi Peserta PKL</label>
                                              </div>
                                          </div>
                                          <div class="col-md-3 pt-1">
                                              <div class="form-group d-flex justify-content-center">
                                                  <input type="checkbox" name="simma_pkl_karma" <?php if($database[20][0]['simmapkl_karma'] == "aktif"){ echo "checked"; } ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
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