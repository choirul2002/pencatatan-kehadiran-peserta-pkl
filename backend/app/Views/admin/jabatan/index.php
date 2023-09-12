                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-table pr-3"></i>Data <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <div class="d-flex justify-content-start pb-3">
                              <nav aria-label="Page navigation example">
                                  <ul class="pagination p-0 m-0">
                                      <li><a href="<?= base_url() ?>/ajt" class="btn btn-success">Tambah</a></li>
                                  </ul>
                              </nav>
                          </div>
                          <table id="table" class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                      <th class="text-center" width="7%">No</th>
                                      <th class="text-center" width="78%">Jabatan</th>
                                      <th class="text-center" width="15%">Aksi</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php $no = 1;
                                    foreach ($database['1'] as $data) { ?>
                                      <tr>
                                          <td class="text-center"><?= $no ?>.</td>
                                          <td><?= ucwords($data['NAMA_JBTN']) ?></td>
                                          <td class="text-center">
                                              <a href="<?= base_url() ?>/ajte?id=<?= $data['KD_JBTN'] ?>" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                              <a>|</a>
                                              <a id="hapus" href="<?= base_url() ?>/ajth?id=<?= $data['KD_JBTN'] ?>" class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>
                                          </td>
                                      </tr>
                                  <?php $no++;
                                    } ?>
                              </tbody>
                          </table>
                      </div>
                  </div>