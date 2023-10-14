                 <div class="card">
                     <div class="card-header">
                         <h3 class="card-title"><i class="far fa-sticky-note pr-3"></i>Edit <?= $layout ?></h3>
                     </div>
                     <div class="card-body">
                         <form onsubmit="return validateFormKaryawan()" action="<?= base_url() ?>/akrse" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                             <?= csrf_field(); ?>
                             <div class="row">
                                 <div class="col-md-12">
                                     <div class="form-group mb-3">
                                         <div class="row">
                                             <div class="col-md-3">
                                                 <div class="form-group">
                                                     <label class="mb-0" style="padding-top:6px;">NIP, Nama Karyawan, Whatshapp</label>
                                                     <input hidden type="text" value="<?= $database[1][0]['KD_KAWAN'] ?>" name="kode" class="form-control" placeholder="Nama Karyawan" required>
                                                 </div>
                                             </div>
                                             <div class="col-md-3">
                                                 <div class="form-group">
                                                     <input type="number" value="<?= $database[1][0]['NIP_KAWAN'] ?>" name="nip" class="form-control" placeholder="NIP Karyawan">
                                                     <small class="text-secondary"><label>Contoh :</label> 00000000000000000000</small>
                                                 </div>
                                             </div>
                                             <div class="col-md-4">
                                                 <div class="form-group">
                                                     <input type="text" value="<?= $database[1][0]['NAMA_KAWAN'] ?>" name="karyawan" class="form-control" placeholder="Nama Karyawan" required>
                                                 </div>
                                             </div>
                                             <div class="col-md-2">
                                                 <div class="form-group mb-0">
                                                     <input id="inputwhatshapp" type="number" value="<?= $database[1][0]['NOHP_KAWAN'] ?>" name="wa" class="form-control mb-2" placeholder="Whatshapp" required>
                                                     <small class="text-secondary"><label>Contoh :</label> 080000000000</small>
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
                                                         <option value="Laki-laki" <?php if ($database[1][0]['JK_KAWAN'] == "Laki-laki") {
                                                                                        echo "selected";
                                                                                    } ?>>Laki-laki</option>
                                                         <option value="Perempuan" <?php if ($database[1][0]['JK_KAWAN'] == "Perempuan") {
                                                                                        echo "selected";
                                                                                    } ?>>Perempuan</option>
                                                     </select>
                                                 </div>
                                             </div>
                                             <div class="col-md-3">
                                                 <div class="form-group">
                                                     <select name="jabatan" class="form-control select2bs4" required>
                                                         <?php foreach ($database[2] as $data) { ?>
                                                             <option value="<?= $data['KD_JBTN'] ?>" <?php if ($database[1][0]['KD_JBTN'] == $data['KD_JBTN']) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?= ucwords($data['NAMA_JBTN']) ?></option>
                                                         <?php } ?>
                                                     </select>
                                                 </div>
                                             </div>
                                             <div class="col-md-3">
                                                 <div class="form-group">
                                                     <select name="agama" class="form-control select2bs4" required>
                                                         <option value="Budha" <?php if ($database[1][0]['AGAMA_KAWAN'] == "Budha") {
                                                                                    echo "selected";
                                                                                } ?>>Budha</option>
                                                         <option value="Hindu" <?php if ($database[1][0]['AGAMA_KAWAN'] == "Hindu") {
                                                                                    echo "selected";
                                                                                } ?>>Hindu</option>
                                                         <option value="Islam" <?php if ($database[1][0]['AGAMA_KAWAN'] == "Islam") {
                                                                                    echo "selected";
                                                                                } ?>>Islam</option>
                                                         <option value="Katholik" <?php if ($database[1][0]['AGAMA_KAWAN'] == "Konghucu") {
                                                                                        echo "selected";
                                                                                    } ?>>Konghucu</option>
                                                         <option value="Kristen" <?php if ($database[1][0]['AGAMA_KAWAN'] == "Kristen") {
                                                                                        echo "selected";
                                                                                    } ?>>Kristen</option>
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
                                                     <textarea name="alamat" class="form-control" rows="5" required><?= ucfirst($database[1][0]['ALAMAT_KAWAN']) ?></textarea>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="form-group mb-3">
                                         <div class="row">
                                             <div class="col-md-3">
                                                 <div class="form-group">
                                                     <label class="mb-0" style="padding-top:6px;">Email, Password, Level</label>
                                                 </div>
                                             </div>
                                             <div class="col-md-4">
                                                 <div class="form-group mb-0">
                                                     <input id="inputemail" type="email" value="<?= $database[1][0]['EMAIL'] ?>" name="email" class="form-control mb-2" placeholder="Email" required>
                                                     <small class="text-secondary"><label>Contoh :</label> abcdef@gmail.com</small>
                                                 </div>
                                             </div>
                                             <div class="col-md-2">
                                                 <div class="form-group mb-0">
                                                     <input type="password" value="<?= $database[1][0]['PASSWORD'] ?>" id="password" name="password" class="form-control mb-2" placeholder="Password" required>
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
                                             <div class="col-md-3">
                                                 <div class="form-group">
                                                     <select name="level" class="form-control select2bs4" required>
                                                         <option selected>. . .</option>
                                                         <option value="admin" <?php if ($database[1][0]['LEVEL'] == "admin") {
                                                                                    echo "selected";
                                                                                } ?>>Admin</option>
                                                         <option value="karyawan" <?php if ($database[1][0]['LEVEL'] == "karyawan") {
                                                                                        echo "selected";
                                                                                    } ?>>Karyawan</option>
                                                     </select>
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