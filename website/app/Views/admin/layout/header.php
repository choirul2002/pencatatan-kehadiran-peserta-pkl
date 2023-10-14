<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-tooltip="tooltip" title="Sembunyikan" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item">
      <a class="nav-link"><?= $database[20][0]['NAMA_SISTEM'] ?></a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <?php if ($database[80] > 0) { ?>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" data-tooltip="tooltip" title="Notifikasi" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-danger navbar-badge"><?= $database[80] ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <?php foreach ($database[81] as $data) { ?>
            <a id="view" href="<?= base_url() ?>/atv?id=<?= $data['KD_TIM'] ?>" class="dropdown-item">
              <div class="media">
                <div class="media-body">
                  <h6 class="dropdown-item-title" style="font-size: 14px;">
                    <strong><?= ucwords($data['NAMA_TIM']) ?></strong>
                    <span class="float-right text-sm"><i class="fas fa-graduation-cap"></i></span>
                  </h6>
                  <p style="font-size: 12px;">Masa PKL sudah habis</p>
                  <p style="font-size: 12px;" class="text-muted pt-1"><i class="far fa-calendar-alt mr-1"></i> <?= $data['TGL_SELESAI_TIM'] ?></p>
                </div>
              </div>
            </a>
            <div class="dropdown-divider"></div>
          <?php } ?>
          <a href="<?= base_url() ?>/atn" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi (<?= $database[80] ?>)</a>
        </div>
      </li>
    <?php } else { ?>
      <li class="nav-item dropdown">
        <a class="nav-link" href="<?= base_url() ?>/atn">
          <i class="far fa-bell"></i>
        </a>
      </li>
    <?php } ?>

    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" data-tooltip="tooltip" title="Pengaturan" href="#">
        <i class="fas fa-cog"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right" style="width: 70px;">
        <a href="<?= base_url() ?>/p" class="dropdown-item">
          <i class="fas fa-user mr-2"></i></i> Profil Akun
        </a>
        <div class="dropdown-divider"></div>
        <a id="logout" href="<?= base_url() ?>/ld" class="dropdown-item">
          <i class="fa fa-sign-out-alt mr-2"></i>Log Out
        </a>
      </div>
    </li>
  </ul>
</nav>