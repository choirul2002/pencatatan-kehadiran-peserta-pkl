<div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="far fa-sticky-note pr-3"></i>Data <?= $title ?></h3>
                      </div>
                      <div class="card-body">
                          <form onsubmit="return validateFormPresensi()" action="<?= base_url() ?>/akgsp" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                              <?= csrf_field(); ?>
                              <input type="text" hidden name="kode" value="<?= $database[20][0]['KD_KONF'] ?>">
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
                                                        <input type="text" name="namaSistem" value="<?= $database[20][0]['NAMA_SISTEM'] ?>" class="form-control mb-2" placeholder="Nama Sistem" required>
                                                      </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                      <div class="form-group">
                                                          <input type="text" name="singkatan" value="<?= $database[20][0]['SINGKATAN'] ?>" class="form-control mb-2" placeholder="Singkatan Sistem" required>
                                                      </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                      <div class="form-group">
                                                          <input type="text" name="versi" value="<?= $database[20][0]['VERSI'] ?>" class="form-control mb-2" placeholder="Versi Sistem" required>
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
                                                    <img id="tampilFoto" src="<?= base_url() ?>/service/icon/<?= $database[20][0]['LOGO_SISTEM'] ?>" class="img img-thumbnail">
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
                                          <h2 style="margin-bottom: 0px;"><strong>Waktu Presensi</strong></h2>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="row">
                                          <div class="col-md-2">
                                              <div class="form-group">
                                                  <label class="mb-0" style="padding-top:6px;">Senin - Kamis IN</label>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="row">
                                                  <div class="col-md-5">
                                                      <div class="form-group">
                                                          <input id="inputsekamin1" type="text" value="<?= $database[20][0]['PRE_SEKAM_MULAI'] ?>" class="form-control mb-2" name="sekam_mulai" id="" required>
                                                          <label class="text-secondary"><small><strong>Contoh </strong> : 00:00:00</small></label>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2 text-center pt-2"> - </div>
                                                  <div class="col-md-5">
                                                      <div class="form-group">
                                                          <input id="inputsekamin2" required type="text" value="<?= $database[20][0]['PRE_SEKAM_SELESAI'] ?>"  class="form-control mb-2" name="sekam_selesai" id="">
                                                          <label class="text-secondary"><small><strong>Contoh </strong> : 00:00:00</small></label>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-md-2">
                                              <div class="form-group d-flex justify-content-center">
                                                  <label class="mb-0" style="padding-top:6px;">Jumat IN</label>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="row">
                                                  <div class="col-md-5">
                                                      <div class="form-group">
                                                          <input required id="inputjumin1" type="text" value="<?= $database[20][0]['PRE_JUM_MULAI'] ?>"  class="form-control mb-2" name="jum_mulai" id="">
                                                          <label class="text-secondary"><small><strong>Contoh </strong> : 00:00:00</small></label>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2 text-center pt-2"> - </div>
                                                  <div class="col-md-5">
                                                      <div class="form-group">
                                                          <input required id="inputjumin2" type="text" value="<?= $database[20][0]['PRE_JUM_SELESAI'] ?>"  class="form-control mb-2" name="jum_selesai" id="">
                                                          <label class="text-secondary"><small><strong>Contoh </strong> : 00:00:00</small></label>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="row">
                                          <div class="col-md-2">
                                              <div class="form-group">
                                                  <label class="mb-0" style="padding-top:6px;">Senin - Kamis OUT</label>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="row">
                                                  <div class="col-md-5">
                                                      <div class="form-group">
                                                          <input type="text" value="<?= $database[20][0]['PRE_SEKAM_SELESAI'] ?>" class="form-control mb-2" id="" readonly>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2 text-center pt-2"> - </div>
                                                  <div class="col-md-5">
                                                      <div class="form-group">
                                                          <input required id="inputsekamout" type="text" value="<?= $database[20][0]['PRE_SEKAM_OUT'] ?>"  class="form-control mb-2" name="sekam_out" id="">
                                                          <label class="text-secondary"><small><strong>Contoh </strong> : 00:00:00</small></label>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-md-2">
                                              <div class="form-group d-flex justify-content-center">
                                                  <label class="mb-0" style="padding-top:6px;">Jumat OUT</label>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="row">
                                                  <div class="col-md-5">
                                                      <div class="form-group">
                                                          <input type="text" value="<?= $database[20][0]['PRE_JUM_SELESAI'] ?>"  class="form-control mb-2" id="" readonly>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2 text-center pt-2"> - </div>
                                                  <div class="col-md-5">
                                                      <div class="form-group">
                                                          <input required id="inputjumout" type="text" value="<?= $database[20][0]['PRE_JUM_OUT'] ?>"  class="form-control mb-2" name="jum_out" id="">
                                                          <label class="text-secondary"><small><strong>Contoh </strong> : 00:00:00</small></label>
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
                                          <h2 style="margin-bottom: 0px;"><strong>Radius</strong></h2>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="row">
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label class="mb-0" style="padding-top:6px;">LatLong</label>
                                              </div>
                                          </div>
                                          <div class="col-md-9">
                                              <div class="row">
                                                  <div class="col-md-6">
                                                      <div class="row">
                                                          <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <input id="inputlatitude" type="text" name="latitude" value="<?= $database[20][0]['LATITUDE_KONF'] ?>" class="form-control mb-2" placeholder="Latitude" required>
                                                                    <label class="text-secondary"><small><strong>Contoh </strong> : 0.000 / -0.000</small></label>
                                                                </div>
                                                          </div>
                                                          <div class="col-md-2 text-center pt-2">/</div>
                                                          <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <input id="inputlongitude" type="text" name="longitude" value="<?= $database[20][0]['LONGITUDE_KONF'] ?>" class="form-control mb-2" placeholder="Longitude" required>
                                                                    <label class="text-secondary"><small><strong>Contoh </strong> : 0.000 / -0.000</small></label>
                                                                </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <div class="row">
                                                          <div class="col-md-6 text-center" style="padding-top:6px;"><label class="mb-0" style="padding-top:0px;">Jangkauan</label></div>
                                                          <div class="col-md-6">
                                                              <div class="form-group">
                                                              <input id="inputradius" required type="text" value="<?= $database[20][0]['RADIUS_KONF'] ?>"  class="form-control mb-2" name="radius" id="">
                                                              <label class="text-secondary"><small><strong>Contoh </strong> : 000f</small></label>
                                                          </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="row">
                                          <div class="col-md-3">
                                              <div class="form-group">
                                                  <label class="mb-0" style="padding-top:6px;">Judul Radius</label>
                                              </div>
                                          </div>
                                          <div class="col-md-9">
                                              <div class="row">
                                                  <div class="col-md-12">
                                                        <div class="form-group">
                                                              <input type="text" value="<?= $database[20][0]['JUDUL_RADIUS'] ?>"  class="form-control mb-2" name="judul_radius" id="" required>
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