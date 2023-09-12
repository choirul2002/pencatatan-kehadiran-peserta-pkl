                 <div class="card">
                     <div class="card-header">
                         <h3 class="card-title"><i class="far fa-sticky-note pr-3"></i>Tambah <?= $layout ?></h3>
                     </div>
                     <div class="card-body">
                         <form action="<?= base_url() ?>/ajts" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                             <?= csrf_field(); ?>
                             <div class="row">
                                 <div class="col-md-12">
                                     <div class="form-group mb-3">
                                         <div class="row">
                                             <div class="col-md-3">
                                                 <div class="form-group">
                                                     <label class="mb-0" style="padding-top:6px;">Nama Jabatan</label>
                                                 </div>
                                             </div>
                                             <div class="col-md-9">
                                                 <div class="form-group">
                                                     <input type="text" name="jabatan" class="form-control" placeholder="Nama Jabatan" required>
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