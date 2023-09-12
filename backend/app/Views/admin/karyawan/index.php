                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-table pr-3"></i>Data <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <div class="d-flex justify-content-start">
                              <nav aria-label="Page navigation example">
                                  <form id="printPDF" action="<?= base_url() ?>/akrg" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                    <ul class="pagination p-0 m-0">
                                        <li><div class="btn-group" role="group">
                                            <a href="<?= base_url() ?>/akrt" class="btn btn-success">Tambah</a>
                                            <button value="pdf" name="generate[]" type="submit" class="btn btn-success">PDF</button>
                                            <button value="print" name="generate[]" type="submit" class="btn btn-success">Print</button>
                                        </div></li>
                                        <li><a class="nav-link" style="color: black;">Level:</a></li>
                                        <li>
                                            <div class="form-group">
                                                <select name="level"  class="form-control select2bs4" required>
                                                    <option value="">. . .</option>
                                                    <option value="all">All</option>
                                                    <option value="admin">Admin</option>
                                                    <option value="karyawan">Karyawan</option>
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                  </form>
                              </nav>
                          </div>
                          <table id="table" class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                      <th class="text-center" width="7%">No</th>
                                      <th class="text-center" width="45%">Karyawan</th>
                                      <th class="text-center" width="35%">Akun</th>
                                      <th class="text-center" width="12%">Aksi</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php $no = 1;
                                    foreach ($database[1] as $data) { ?>
                                      <tr>
                                          <td class="text-center"><small><?= $no ?></small></td>
                                          <td>
                                              <small>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Nama</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                        <a id="foto" href="<?= base_url() ?>/akvm?foto=<?= $data['FOTO_KAWAN'] ?>"><?= ucwords($data['NAMA_KAWAN']) ?></a>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>NIP</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                            <?php if($data['NIP_KAWAN']){ ?>
                                                                <?= $data['NIP_KAWAN'] ?>
                                                            <?php }else{ ?>
                                                                <?php echo "-"; ?>
                                                            <?php }?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Kelamin</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= ucwords($data['JK_KAWAN']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Agama</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= ucwords($data['AGAMA_KAWAN']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Alamat</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= ucfirst($data['ALAMAT_KAWAN']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Whatshapp</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-7 pl-0 pr-0">
                                                          <a id="hp" href="https://wa.me/<?= $wa = "+62" . substr($data['NAMA_KAWAN'], 1); ?>?"><?= $data['NOHP_KAWAN'] ?></a>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Jabatan</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= ucwords($data['NAMA_JBTN']) ?>
                                                      </div>
                                                  </div>
                                              </small>
                                          </td>
                                          <td>
                                              <small>
                                                  <div class="row">
                                                      <div class="col-md-4 pr-0">
                                                          <strong>Email</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-7 pl-0 pr-0">
                                                          <a style="text-decoration: underline;" href="mailto:<?= $data['EMAIL'] ?>"><?= $data['EMAIL'] ?></a>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-4 pr-0">
                                                          <strong>Password</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-7 pl-0 pr-0">
                                                          <?= $data['PASSWORD'] ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-4 pr-0">
                                                          <strong>Level</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-7 pl-0 pr-0">
                                                          <?php if($data['LEVEL'] == "karyawan"){ ?>
                                                            <?php echo "Karyawan"; ?>
                                                          <?php }else{ ?>
                                                            <?= ucwords($data['LEVEL']) ?>
                                                          <?php } ?>
                                                      </div>
                                                  </div>
                                              </small>
                                          </td>
                                          <td class="text-center">
                                              <div>

                                                  <a href="<?= base_url() ?>/akre?id=<?= $data['KD_KAWAN'] ?>" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                                  <a>|</a>
                                                  <a id="hapus" href="<?= base_url() ?>/akrh?id=<?= $data['KD_KAWAN'] ?>" class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>
                                              </div>
                                              <div  style="padding-top:4px;">

                                                  <a style="margin_top:100px;" href="<?= base_url() ?>/attk?id=<?= $data['KD_KAWAN'] ?>" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Tim Peserta"><i class="fa fa-arrow-right"></i></a>
                                              </div>
                                          </td>
                                      </tr>
                                  <?php $no++;
                                    } ?>
                              </tbody>
                          </table>
                      </div>
                  </div>