                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="far fa-sticky-note pr-3"></i>Edit <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <form onsubmit="return validateFormTimPeserta()" action="<?= base_url() ?>/atse" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                              <?= csrf_field(); ?>
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Nama Tim Peserta, Status</label>
                                                      <input hidden type="text" value="<?= $database[1][0]['KD_TIM'] ?>" name="kode" class="form-control" placeholder="Nama Tim Peserta" required>
                                                  </div>
                                              </div>
                                              <div class="col-md-7">
                                                  <div class="form-group">
                                                      <input type="text" value="<?= $database[1][0]['NAMA_TIM'] ?>" name="tim" class="form-control" placeholder="Nama Tim Peserta" required>
                                                  </div>
                                              </div>
                                              <div class="col-md-2">
                                                    <div class="form-group">
                                                        <?php date_default_timezone_set('Asia/Jakarta'); if(date('Y-m-d') < $database[1][0]['TGL_MULAI_TIM']){ ?>
                                                          <input readonly type="text" name="status" value="Menunggu" name="tim" class="form-control" placeholder="Nama Tim Peserta" required>
                                                        <?php }else if(date('Y-m-d') >= $database[1][0]['TGL_MULAI_TIM'] && date('Y-m-d') <= $database[1][0]['TGL_SELESAI_TIM']){?>
                                                          <input readonly type="text" name="status" value="Aktif" name="tim" class="form-control" placeholder="Nama Tim Peserta" required>
                                                        <?php }else if(date('Y-m-d') > $database[1][0]['TGL_MULAI_TIM']){?>
                                                          <input readonly type="text" name="status" value="Tidak Aktif" name="tim" class="form-control" placeholder="Nama Tim Peserta" required>
                                                        <?php }?>
                                                    </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Asal, Pembimbing PKL</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-5">
                                                  <div class="form-group">
                                                      <select name="kampus" class="form-control select2bs4" required>
                                                          <?php foreach ($database[2] as $data) { ?>
                                                              <option value="<?= $data['KD_ASAL'] ?>" <?php if ($database[1][0]['KD_ASAL'] == $data['KD_ASAL']) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?= ucwords($data['NAMA_ASAL']) ?></option>
                                                          <?php } ?>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group">
                                                      <select name="pembimbing" class="form-control select2bs4" required>
                                                          <?php foreach ($database[3] as $data) { ?>
                                                              <option value="<?= $data['KD_KAWAN'] ?>" <?php if ($database[1][0]['KD_KAWAN'] == $data['KD_KAWAN']) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?= ucwords($data['NAMA_KAWAN']) ?></option>
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
                                                      <label class="mb-0" style="padding-top:6px;">Tgl Mulai, Tgl Selesai, Tahun</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                          <input id="inputtanggalmulai" type="text" name="mulai" class="form-control" value="<?= $database[1][0]['TGL_MULAI_TIM'] ?>"data-target="#reservationdate" placeholder="Tgl. Mulai" required />
                                                          <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                          </div>
                                                      </div>
                                                      <small class="text-secondary"><label>Contoh :</label> 0000-00-00</small>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                                          <input id="inputtanggalselesai" type="text" name="selesai" class="form-control" value="<?= $database[1][0]['TGL_SELESAI_TIM'] ?>" data-target="#reservationdate1" placeholder="Tgl. Selesai" required />
                                                          <div class="input-group-append" data-target="#reservationdate1" data-toggle="datetimepicker">
                                                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                          </div>
                                                      </div>
                                                      <small class="text-secondary"><label>Contoh :</label> 0000-00-00</small>
                                                  </div>
                                              </div>
                                              <div class="col-md-2">
                                                  <div class="form-group">
                                                      <input type="number" value="<?= $database[1][0]['TAHUN_TIM'] ?>" name="tahun" class="form-control" placeholder="Tahun" required>
                                                      <small class="text-secondary"><label>Contoh :</label> 0000</small>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="form-group mb-3">
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label class="mb-0" style="padding-top:6px;">Peserta</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-9">
                                                  <div class="form-group">
                                                      <div class="row">
                                                            <div class="col-12 col-sm-8">
                                                              <div class="form-group">
                                                                <div class="select2-primary">
                                                                <?php date_default_timezone_set('Asia/Jakarta'); if(date('Y-m-d') > $database[1][0]['TGL_SELESAI_TIM']){ ?>
                                                                    <select disabled name="input_field[]" class="form-control select2" multiple="multiple" data-placeholder="Select a State" data-dropdown-css-class="select2-primary" style="width: 100%;" required>
                                                                      <?php foreach ($database[5] as $data) { ?>
                                                                          <option <?php foreach($database[21] as $data3){ if($data3['KD_PST'] == $data['KD_PST']){ echo "disabled"; } } ?> <?php foreach($database[4] as $data2){ if($data2['KD_PST'] == $data['KD_PST']){ echo "selected"; } } ?> value="<?= $data['KD_PST'] ?>"><?= ucwords($data['NAMA_PST']) ?><?php foreach($database[21] as $data3){ if($data3['KD_PST'] == $data['KD_PST']){ echo " ( ".ucwords($data3['NAMA_TIM'])." ) "; } } ?></option>
                                                                      <?php } ?>
                                                                  </select>
                                                                    <?php }else{ ?>
                                                                    <select name="input_field[]" class="form-control select2" multiple="multiple" data-placeholder="Select a State" data-dropdown-css-class="select2-primary" style="width: 100%;" required>
                                                                        <?php foreach ($database[5] as $data) { ?>
                                                                            <option <?php foreach($database[21] as $data3){ if($data3['KD_PST'] == $data['KD_PST']){ echo "disabled"; } } ?> <?php foreach($database[4] as $data2){ if($data2['KD_PST'] == $data['KD_PST']){ echo "selected"; } } ?> value="<?= $data['KD_PST'] ?>"><?= ucwords($data['NAMA_PST']) ?><?php foreach($database[21] as $data3){ if($data3['KD_PST'] == $data['KD_PST']){ echo " ( ".ucwords($data3['NAMA_TIM'])." ) "; } } ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } ?>
                                                                </div>
                                                              </div>
                                                            </div>
                                                      </div>
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
                                              <?php date_default_timezone_set('Asia/Jakarta'); if(date('Y-m-d') > $database[1][0]['TGL_SELESAI_TIM']){ ?>
                                                <Button disabled class="btn btn-primary" style="width: 200px;" type="submit">Simpan</Button>
                                                                    <?php }else{ ?>
                                                                        <Button class="btn btn-primary" style="width: 200px;" type="submit">Simpan</Button>
                                                                <?php } ?>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>