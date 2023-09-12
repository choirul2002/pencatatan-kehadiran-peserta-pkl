<div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-table pr-3"></i>Data <?= $layout ?></h3>
                      </div>
                      <div class="card-body">
                          <div class="d-flex justify-content-start">
                          <nav aria-label="Page navigation example">
                              <form action="<?= base_url() ?>/apg" target="_blank" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                <ul class="pagination p-0 m-0">
                                    <li><div class="btn-group" role="group">
                                        <button value="pdf" name="generate[]" type="submit" class="btn btn-success">PDF</button>
                                        <button value="print" name="generate[]" type="submit" class="btn btn-success">Print</button>
                                    </div></li>
                                    <li><a class="nav-link" style="color: black;">Status Tim:</a></li>
                                    <li>
                                        <div class="form-group">
                                            <select name="statusPendaftar"  class="form-control select2bs4" required>
                                                <option value="">. . .</option>
                                                <option value="all">All</option>
                                                <option value="diterima">Diterima</option>
                                                <option value="tidak diterima">Tidak Diterima</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li><a class="nav-link" style="color: black;">Tahun:</a></li>
                                    <li>
                                        <div class="form-group">
                                            <select name="tahun"  class="form-control select2bs4" required>
                                                <option value="">. . .</option>
                                                <option value="all">All</option>
                                                <?php foreach($database[3] as $data){ ?>
                                                    <option value="<?= $data['tahun'] ?>"><?= $data['tahun'] ?></option>
                                                <?php } ?>
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
                                      <th class="text-center" width="45%">Pendaftar</th>
                                      <th class="text-center" width="35%">Anggota</th>
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
                                                      <div class="col-md-2 pr-0">
                                                          <strong>Tanggal</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-9 pl-0 pr-0">
                                                          <?= $data['tanggal'] ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-2 pr-0">
                                                          <strong>Asal</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-9 pl-0 pr-0">
                                                          <?= ucwords($data['kampus']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-2 pr-0">
                                                          <strong>Jurusan</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-9 pl-0 pr-0">
                                                          <?= ucwords($data['jurusan']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-2 pr-0">
                                                          <strong>Alamat</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-9 pl-0 pr-0">
                                                          <?= ucfirst($data['alamat']) ?>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-2 pr-0">
                                                          <strong>Surat</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-9 pl-0 pr-0">
                                                        <a id="rekomendasi" href="<?= base_url() ?>/service/pendaftaran/<?= $data['surat'] ?>">Rekomendasi</a>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-2 pr-0">
                                                          <strong>Status</strong>
                                                      </div>
                                                      <div class="col-md-1 pr-0 pl-0">
                                                          :
                                                      </div>
                                                      <div class="col-md-9 pl-0 pr-0">
                                                          <?php if ($data['status_pendaftaran'] == "diterima") { ?>
                                                              <span class="badge badge-success" style="width: 100px;"><?= ucwords($data['status_pendaftaran']) ?></span>
                                                          <?php } else { ?>
                                                              <span class="badge badge-danger" style="width: 100px;"><?= ucwords($data['status_pendaftaran']) ?></span>
                                                          <?php } ?>
                                                      </div>
                                                  </div>
                                              </small>
                                          </td>
                                          <td>
                                              <small>
                                                  <?php $urut = 1; foreach($database[2] as $mahasiswa){ ?>
                                                    <?php if($mahasiswa['kd_pendaftaran'] == $data['kd_pendaftaran']){ ?>
                                                        <div class="row">
                                                            <div class="col-md-3 pr-0">
                                                                <strong <?php if($urut > 1){ echo 'hidden'; } ?>>Peserta</strong>
                                                            </div>
                                                            <div class="col-md-1 pr-0 pl-0 d-flex justify-content-center">
                                                                :
                                                            </div>
                                                            <div class="col-md-8 pl-0 pr-0">
                                                                <?= ucwords($mahasiswa['nama']) ?>
                                                            </div>
                                                        </div>
                                                    <?php $urut++; } ?>
                                                  <?php } ?>
                                              </small>
                                          </td>
                                      </tr>
                                  <?php $no++;
                                    } ?>
                              </tbody>
                          </table>
                      </div>
                  </div>