<div class="card">
                     <div class="card-header">
                         <h3 class="card-title"><i class="far fa-sticky-note pr-3"></i>Edit Libur Nasional</h3>
                     </div>
                     <div class="card-body">
                         <form onsubmit="return validateFormLiburNasional()" action="<?= base_url() ?>/alnse" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                             <?= csrf_field(); ?>
                             <div class="row">
                                 <div class="col-md-12">
                                     <div class="form-group mb-3">
                                         <div class="row">
                                             <div class="col-md-3">
                                                 <div class="form-group">
                                                     <label class="mb-0" style="padding-top:6px;">Tanggal, Nama Acara</label>
                                                 </div>
                                             </div>
                                             <div class="col-md-9">
                                                 <div class="row">
                                                     <div class="col-md-3">
                                                      <div class="form-group">
                                                      <input hidden name="id" value="<?= $database[32][0]['ID_LBR'] ?>" type="text">
                                                          <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                              <input id="inputtanggal" type="text" value="<?= $database[32][0]['TANGGAL_LBR'] ?>" name="tanggal" class="form-control" data-target="#reservationdate" placeholder="Tanggal" required />
                                                              <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                              </div>
                                                          </div>
                                                          <label class="text-secondary"><small><strong>Contoh </strong> : 0000-00-00</small></label>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-9">
                                                      <div class="form-group">
                                                          <input type="text" value="<?= $database[32][0]['KEGIATAN_LBR'] ?>" name="acara" class="form-control" placeholder="Nama Acara" required>
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
                                             <Button class="btn btn-primary" style="width: 200px;" type="submit">Simpan</Button>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </form>
                     </div>
                 </div>