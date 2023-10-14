<div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-table pr-3"></i>Data <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <table id="table" class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                      <th class="text-center" width="7%">No</th>
                                      <th class="text-center" width="35%">Deskripsi</th>
                                      <th class="text-center" width="45%">Testimoni</th>
                                      <th class="text-center" width="15%">Aksi</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php $no = 1;
                                    foreach ($database[1] as $data) { ?>
                                      <tr>
                                          <td class="text-center"><small><?= $no ?>.</small></td>
                                          <td>
                                              <small>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Tanggal</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= $data['tanggal'] ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Nama</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <a id="foto" href="<?= base_url() ?>/atvm?foto=<?= $data['foto'] ?>"><?= ucwords($data['nama_testi']) ?></a>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Asal</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= ucwords($data['kampus']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-3 pr-0">
                                                          <strong>Publish</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-8 pl-0 pr-0">
                                                          <?= ucwords($data['publish']) ?>
                                                      </div>
                                                  </div>
                                              </small>
                                          </td>
                                          <td>
                                              <small>" <?= $data['testimoni'] ?> "</small>
                                          </td>
                                          <td class="text-center">
                                              <a href="<?= base_url() ?>/atvp?testi=<?= $data['id_testi'] ?>" id="publish" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Setting"><i class="fas fa-wrench"></i></a>
                                            </td>
                                      </tr>
                                  <?php $no++;
                                    } ?>
                              </tbody>
                          </table>
                      </div>
                  </div>